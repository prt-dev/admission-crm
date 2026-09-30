<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\StoreUserRequest;
use App\Http\Requests\User\UpdateUserRequest;
use App\Http\Requests\User\UpdateUserStatusRequest;
use App\Http\Resources\UserResource;
use App\Responses\ApiResponse;
use App\Services\UserService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class UserController extends Controller
{
    /**
     * @param UserService $userService
     */
    public function __construct(
        protected UserService $userService
    ) {}

    /**
     * Display a listing of users.
     */
    public function index(Request $request): JsonResponse
    {
        $filters = [
            'search' => $request->query('search'),
            'role' => $request->query('role'),
            'status' => $request->query('status'),
            'sort_by' => $request->query('sort_by', 'created_at'),
            'sort_order' => $request->query('sort_order', 'desc'),
        ];

        $perPage = (int) $request->query('per_page', 15);
        $users = $this->userService->getPaginatedUsers($perPage, ['*'], $filters);

        return ApiResponse::paginate(
            $users,
            UserResource::class,
            'Users retrieved successfully.'
        );
    }

    /**
     * Store a newly created user in storage.
     */
    public function store(StoreUserRequest $request): JsonResponse
    {
        $user = $this->userService->createUser($request->validated());

        return ApiResponse::success(
            new UserResource($user),
            'User created successfully.',
            Response::HTTP_CREATED
        );
    }

    /**
     * Display the specified user.
     */
    public function show(int|string $id): JsonResponse
    {
        $user = $this->userService->getUserById($id);

        if (!$user) {
            return ApiResponse::error('User not found.', Response::HTTP_NOT_FOUND);
        }

        return ApiResponse::success(
            new UserResource($user),
            'User retrieved successfully.'
        );
    }

    /**
     * Update the specified user in storage.
     */
    public function update(UpdateUserRequest $request, int|string $id): JsonResponse
    {
        $user = $this->userService->updateUser($id, $request->validated());

        if (!$user) {
            return ApiResponse::error('User not found or update failed.', Response::HTTP_NOT_FOUND);
        }

        return ApiResponse::success(
            new UserResource($user),
            'User updated successfully.'
        );
    }

    /**
     * Remove the specified user from storage.
     */
    public function destroy(int|string $id): JsonResponse
    {
        $deleted = $this->userService->deleteUser($id);

        if (!$deleted) {
            return ApiResponse::error('User not found or deletion failed.', Response::HTTP_NOT_FOUND);
        }

        return ApiResponse::success(
            null,
            'User deleted successfully.'
        );
    }

    /**
     * Update the user status.
     */
    public function updateStatus(UpdateUserStatusRequest $request, int|string $id): JsonResponse
    {
        $user = $this->userService->updateUserStatus($id, $request->validated()['status']);

        if (!$user) {
            return ApiResponse::error('User not found.', Response::HTTP_NOT_FOUND);
        }

        return ApiResponse::success(
            new UserResource($user),
            'User status updated successfully.'
        );
    }

    /**
     * Record last login for user.
     */
    public function recordLastLogin(int|string $id): JsonResponse
    {
        $user = $this->userService->recordLastLogin($id);

        if (!$user) {
            return ApiResponse::error('User not found.', Response::HTTP_NOT_FOUND);
        }

        return ApiResponse::success(
            new UserResource($user),
            'Last login updated successfully.'
        );
    }
}
