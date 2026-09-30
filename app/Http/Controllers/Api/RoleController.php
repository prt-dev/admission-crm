<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Role\StoreRoleRequest;
use App\Http\Requests\Role\UpdateRoleRequest;
use App\Http\Requests\Role\UpdateRoleStatusRequest;
use App\Http\Resources\RoleResource;
use App\Responses\ApiResponse;
use App\Services\RoleService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleController extends Controller
{
    public function __construct(
        protected RoleService $roleService
    ) {}

    public function index(Request $request): JsonResponse
    {
        $filters = [
            'search' => $request->query('search'),
            'status' => $request->query('status'),
            'sort_by' => $request->query('sort_by', 'created_at'),
            'sort_order' => $request->query('sort_order', 'asc'),
        ];

        $perPage = (int) $request->query('per_page', 15);
        $roles = $this->roleService->getPaginatedRoles($perPage, ['*'], $filters);

        return ApiResponse::paginate(
            $roles,
            RoleResource::class,
            'Roles retrieved successfully.'
        );
    }

    public function store(StoreRoleRequest $request): JsonResponse
    {
        $role = $this->roleService->createRole($request->validated());

        return ApiResponse::success(
            new RoleResource($role),
            'Role created successfully.',
            Response::HTTP_CREATED
        );
    }

    public function show(int|string $id): JsonResponse
    {
        $role = $this->roleService->getRoleById($id);

        if (!$role) {
            return ApiResponse::error('Role not found.', Response::HTTP_NOT_FOUND);
        }

        return ApiResponse::success(
            new RoleResource($role),
            'Role retrieved successfully.'
        );
    }

    public function update(UpdateRoleRequest $request, int|string $id): JsonResponse
    {
        $role = $this->roleService->updateRole($id, $request->validated());

        if (!$role) {
            return ApiResponse::error('Role not found or update failed.', Response::HTTP_NOT_FOUND);
        }

        return ApiResponse::success(
            new RoleResource($role),
            'Role updated successfully.'
        );
    }

    public function destroy(int|string $id): JsonResponse
    {
        $role = $this->roleService->getRoleById($id);

        if (!$role) {
            return ApiResponse::error('Role not found.', Response::HTTP_NOT_FOUND);
        }

        if ($role->is_system) {
            return ApiResponse::error('System roles cannot be deleted.', Response::HTTP_FORBIDDEN);
        }

        $deleted = $this->roleService->deleteRole($id);

        if (!$deleted) {
            return ApiResponse::error('Role deletion failed.', Response::HTTP_NOT_FOUND);
        }

        return ApiResponse::success(
            null,
            'Role deleted successfully.'
        );
    }

    public function updateStatus(UpdateRoleStatusRequest $request, int|string $id): JsonResponse
    {
        $role = $this->roleService->updateRoleStatus($id, (int) $request->validated()['status']);

        if (!$role) {
            return ApiResponse::error('Role not found.', Response::HTTP_NOT_FOUND);
        }

        return ApiResponse::success(
            new RoleResource($role),
            'Role status updated successfully.'
        );
    }
}
