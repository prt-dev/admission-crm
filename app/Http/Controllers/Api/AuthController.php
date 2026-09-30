<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\ChangePasswordRequest;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Resources\UserResource;
use App\Responses\ApiResponse;
use App\Services\AuthService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AuthController extends Controller
{
    /**
     * @param AuthService $authService
     */
    public function __construct(
        protected AuthService $authService
    ) {
    }

    /**
     * Handle user authentication and issue JWT cookie/token.
     *
     * @param LoginRequest $request
     * @return JsonResponse
     */
    public function login(LoginRequest $request): JsonResponse
    {
        $validated = $request->validated();
        $loginInput = $validated['username'] ?? '';
        $password = $validated['password'];

        $authResult = $this->authService->authenticate($loginInput, $password);

        if (!$authResult) {
            return ApiResponse::error(
                'Invalid credentials or inactive account.',
                Response::HTTP_UNAUTHORIZED
            );
        }

        $user = $authResult['user'];
        $token = $authResult['token'];
        $cookie = $this->authService->makeAuthCookie($token);

        return ApiResponse::success([
            'token' => $token,
            'user' => new UserResource($user),
        ], 'Login successful.')
            ->withCookie($cookie);
    }

    /**
     * Get the authenticated user's profile.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function me(Request $request): JsonResponse
    {
        $user = $request->user();

        if (!$user) {
            return ApiResponse::error(
                'Unauthenticated.',
                Response::HTTP_UNAUTHORIZED
            );
        }

        $user->loadMissing('role');

        return ApiResponse::success(
            new UserResource($user),
            'Profile retrieved successfully.'
        );
    }

    /**
     * Log out the authenticated user by invalidating the cookie.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function logout(Request $request): JsonResponse
    {
        $cookie = $this->authService->makeLogoutCookie();

        return ApiResponse::success(
            null,
            'Logged out successfully.'
        )
            ->withCookie($cookie);
    }

    /**
     * Update password for the authenticated user.
     *
     * @param ChangePasswordRequest $request
     * @return JsonResponse
     */
    public function changePassword(ChangePasswordRequest $request): JsonResponse
    {
        $user = $request->user();

        if (!$user) {
            return ApiResponse::error(
                'Unauthenticated.',
                Response::HTTP_UNAUTHORIZED
            );
        }

        $validated = $request->validated();
        $changed = $this->authService->changePassword(
            $user,
            $validated['current_password'],
            $validated['password']
        );

        if (!$changed) {
            return ApiResponse::error(
                'Current password does not match.',
                Response::HTTP_UNPROCESSABLE_ENTITY
            );
        }

        return ApiResponse::success(
            null,
            'Password changed successfully.'
        );
    }
}
