<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\StoreUserRequest;
use App\Http\Requests\User\UpdateUserRequest;
use App\Http\Requests\User\UpdateUserStatusRequest;
use App\Http\Resources\UserResource;
use App\Services\UserService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
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
    public function index(Request $request): AnonymousResourceCollection
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

        return UserResource::collection($users);
    }

    /**
     * Store a newly created user in storage.
     */
    public function store(StoreUserRequest $request): JsonResponse
    {
        $user = $this->userService->createUser($request->validated());

        return (new UserResource($user))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    /**
     * Display the specified user.
     */
    public function show(int|string $id): JsonResponse
    {
        $user = $this->userService->getUserById($id);

        if (!$user) {
            return response()->json([
                'message' => 'User not found.',
            ], Response::HTTP_NOT_FOUND);
        }

        return (new UserResource($user))->response();
    }

    /**
     * Update the specified user in storage.
     */
    public function update(UpdateUserRequest $request, int|string $id): JsonResponse
    {
        $user = $this->userService->updateUser($id, $request->validated());

        if (!$user) {
            return response()->json([
                'message' => 'User not found or update failed.',
            ], Response::HTTP_NOT_FOUND);
        }

        return (new UserResource($user))->response();
    }

    /**
     * Remove the specified user from storage.
     */
    public function destroy(int|string $id): JsonResponse
    {
        $deleted = $this->userService->deleteUser($id);

        if (!$deleted) {
            return response()->json([
                'message' => 'User not found or deletion failed.',
            ], Response::HTTP_NOT_FOUND);
        }

        return response()->json([
            'message' => 'User deleted successfully.',
        ], Response::HTTP_OK);
    }

    /**
     * Update the user status.
     */
    public function updateStatus(UpdateUserStatusRequest $request, int|string $id): JsonResponse
    {
        $user = $this->userService->updateUserStatus($id, $request->validated()['status']);

        if (!$user) {
            return response()->json([
                'message' => 'User not found.',
            ], Response::HTTP_NOT_FOUND);
        }

        return (new UserResource($user))->response();
    }

    /**
     * Record last login for user.
     */
    public function recordLastLogin(int|string $id): JsonResponse
    {
        $user = $this->userService->recordLastLogin($id);

        if (!$user) {
            return response()->json([
                'message' => 'User not found.',
            ], Response::HTTP_NOT_FOUND);
        }

        return response()->json([
            'message' => 'Last login updated successfully.',
            'user' => new UserResource($user),
        ], Response::HTTP_OK);
    }
}
