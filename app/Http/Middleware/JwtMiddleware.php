<?php

namespace App\Http\Middleware;

use App\Responses\ApiResponse;
use App\Services\AuthService;
use App\Services\JwtService;
use Closure;
use Exception;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class JwtMiddleware
{
    /**
     * @param JwtService $jwtService
     * @param AuthService $authService
     */
    public function __construct(
        protected JwtService $jwtService,
        protected AuthService $authService
    ) {}

    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @return Response
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check cookie first, fallback to Authorization Bearer header
        $token = $request->cookie('access_token') ?? $request->bearerToken();

        if (!$token) {
            return ApiResponse::error(
                'Authentication token required.',
                Response::HTTP_UNAUTHORIZED
            );
        }

        try {
            $payload = $this->jwtService->decode($token);

            $user = $this->authService->getUserFromPayload($payload);

            if (!$user || $user->status !== 1) {
                return ApiResponse::error(
                    'User not found or inactive account.',
                    Response::HTTP_UNAUTHORIZED
                );
            }

            // Set user on request
            $request->setUserResolver(fn () => $user);
            $request->attributes->set('jwt', $payload);
            $request->attributes->set('auth_user', $user);

        } catch (Exception $e) {
            return ApiResponse::error(
                'Invalid or expired authentication token.',
                Response::HTTP_UNAUTHORIZED
            );
        }

        return $next($request);
    }
}