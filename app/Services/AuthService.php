<?php

namespace App\Services;

use App\Models\User;
use App\Repositories\Contracts\UserRepositoryInterface;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\Cookie;

class AuthService
{
    /**
     * @param UserRepositoryInterface $userRepository
     * @param JwtService $jwtService
     */
    public function __construct(
        protected UserRepositoryInterface $userRepository,
        protected JwtService $jwtService
    ) {
    }

    /**
     * Authenticate user credentials.
     *
     * @param string $login Email or username
     * @param string $password
     * @return array{user: User, token: string}|null
     */
    public function authenticate(string $login, string $password): ?array
    {
        $user = User::with('role')
            ->where(function ($query) use ($login) {
                $query->where('username', $login)
                    ->orWhere('email', $login)
                    ->orWhere('phone', $login);
            })
            ->first();

        if (!$user || !Hash::check($password, $user->password)) {
            return null;
        }

        if ($user->status !== 1) {
            return null;
        }

        // Update last login
        $user->touchLastLogin();

        // Generate JWT token
        $token = $this->jwtService->generate([
            'sub' => $user->id,
            'role_id' => $user->role_id,
            'email' => $user->email,
        ]);

        return [
            'user' => $user,
            'token' => $token,
        ];
    }

    /**
     * Get user instance by decoded JWT token payload.
     *
     * @param object $jwtPayload
     * @return User|null
     */
    public function getUserFromPayload(object $jwtPayload): ?User
    {
        $userId = $jwtPayload->sub ?? null;

        if (!$userId) {
            return null;
        }

        return User::with('role')->find($userId);
    }

    /**
     * Change user password.
     *
     * @param User $user
     * @param string $currentPassword
     * @param string $newPassword
     * @return bool
     */
    public function changePassword(User $user, string $currentPassword, string $newPassword): bool
    {
        if (!Hash::check($currentPassword, $user->password)) {
            return false;
        }

        $user->password = $newPassword;
        return $user->save();
    }

    /**
     * Create access token cookie.
     *
     * @param string $token
     * @return Cookie
     */
    public function makeAuthCookie(string $token): Cookie
    {
        $ttlMinutes = (int) (config('jwt.ttl', 3600) / 60);

        return cookie(
            'access_token',
            $token,
            $ttlMinutes,
            '/',
            null,
            false,    // secure (set true in HTTPS production)
            true,     // httpOnly
            false,
            'lax'
        );
    }

    /**
     * Create cookie that forgets/clears access token.
     *
     * @return Cookie
     */
    public function makeLogoutCookie(): Cookie
    {
        return cookie()->forget('access_token');
    }
}
