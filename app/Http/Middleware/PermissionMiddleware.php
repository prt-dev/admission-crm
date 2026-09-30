<?php

namespace App\Http\Middleware;

use App\Responses\ApiResponse;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PermissionMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @param string ...$permissions
     * @return Response
     */
    public function handle(Request $request, Closure $next, string ...$permissions): Response
    {
        $user = $request->user();

        if (!$user) {
            return ApiResponse::error(
                'Authentication required.',
                Response::HTTP_UNAUTHORIZED
            );
        }

        $user->loadMissing('role.permissions');

        // Check if user has any of the required permissions
        foreach ($permissions as $permission) {
            if ($user->hasPermission($permission)) {
                return $next($request);
            }
        }

        return ApiResponse::error(
            'You do not have permission to perform this action.',
            Response::HTTP_FORBIDDEN
        );
    }
}
