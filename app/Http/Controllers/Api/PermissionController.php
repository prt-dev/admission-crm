<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Permission\StorePermissionRequest;
use App\Http\Requests\Permission\SyncRolePermissionsRequest;
use App\Http\Requests\Permission\UpdatePermissionRequest;
use App\Http\Requests\Permission\UpdatePermissionStatusRequest;
use App\Http\Resources\PermissionResource;
use App\Responses\ApiResponse;
use App\Services\PermissionService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PermissionController extends Controller
{
    public function __construct(
        protected PermissionService $permissionService
    ) {}

    /**
     * Display a paginated listing of permissions.
     */
    public function index(Request $request): JsonResponse
    {
        $filters = [
            'search' => $request->query('search'),
            'module' => $request->query('module'),
            'status' => $request->query('status'),
            'sort_by' => $request->query('sort_by', 'module'),
            'sort_order' => $request->query('sort_order', 'asc'),
        ];

        $perPage = (int) $request->query('per_page', 50);
        $permissions = $this->permissionService->getPaginatedPermissions($perPage, ['*'], $filters);

        return ApiResponse::paginate(
            $permissions,
            PermissionResource::class,
            'Permissions retrieved successfully.'
        );
    }

    /**
     * Display permissions grouped by module (for frontend permission matrix).
     */
    public function grouped(): JsonResponse
    {
        $grouped = $this->permissionService->getGroupedPermissions();
        $formatted = [];

        foreach ($grouped as $module => $items) {
            $formatted[$module] = PermissionResource::collection($items);
        }

        return ApiResponse::success(
            $formatted,
            'Grouped permissions catalog retrieved successfully.'
        );
    }

    /**
     * Store a newly created permission.
     */
    public function store(StorePermissionRequest $request): JsonResponse
    {
        $permission = $this->permissionService->createPermission($request->validated());

        return ApiResponse::success(
            new PermissionResource($permission),
            'Permission created successfully.',
            Response::HTTP_CREATED
        );
    }

    /**
     * Display the specified permission.
     */
    public function show(int|string $id): JsonResponse
    {
        $permission = $this->permissionService->getPermissionById($id);

        if (!$permission) {
            return ApiResponse::error('Permission not found.', Response::HTTP_NOT_FOUND);
        }

        return ApiResponse::success(
            new PermissionResource($permission),
            'Permission retrieved successfully.'
        );
    }

    /**
     * Update the specified permission.
     */
    public function update(UpdatePermissionRequest $request, int|string $id): JsonResponse
    {
        $permission = $this->permissionService->updatePermission($id, $request->validated());

        if (!$permission) {
            return ApiResponse::error('Permission not found or update failed.', Response::HTTP_NOT_FOUND);
        }

        return ApiResponse::success(
            new PermissionResource($permission),
            'Permission updated successfully.'
        );
    }

    /**
     * Remove the specified permission.
     */
    public function destroy(int|string $id): JsonResponse
    {
        $deleted = $this->permissionService->deletePermission($id);

        if (!$deleted) {
            return ApiResponse::error('Permission not found or deletion failed.', Response::HTTP_NOT_FOUND);
        }

        return ApiResponse::success(
            null,
            'Permission deleted successfully.'
        );
    }

    /**
     * Update the permission status.
     */
    public function updateStatus(UpdatePermissionStatusRequest $request, int|string $id): JsonResponse
    {
        $permission = $this->permissionService->updatePermissionStatus($id, (int) $request->validated()['status']);

        if (!$permission) {
            return ApiResponse::error('Permission not found.', Response::HTTP_NOT_FOUND);
        }

        return ApiResponse::success(
            new PermissionResource($permission),
            'Permission status updated successfully.'
        );
    }

    /**
     * Get permissions assigned to a role.
     */
    public function getRolePermissions(int|string $roleId): JsonResponse
    {
        $permissions = $this->permissionService->getRolePermissions($roleId);

        return ApiResponse::success(
            PermissionResource::collection($permissions),
            'Role permissions retrieved successfully.'
        );
    }

    /**
     * Sync permissions for a role.
     */
    public function syncRolePermissions(SyncRolePermissionsRequest $request, int|string $roleId): JsonResponse
    {
        $permissionIds = $request->validated()['permission_ids'];
        $syncedSlugs = $this->permissionService->syncRolePermissions($roleId, $permissionIds);

        return ApiResponse::success([
            'role_id' => (int) $roleId,
            'permissions' => $syncedSlugs,
        ], 'Role permissions updated successfully.');
    }
}
