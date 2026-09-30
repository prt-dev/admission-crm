<?php

namespace App\Services;

use App\Models\Permission;
use App\Repositories\Contracts\PermissionRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Str;

class PermissionService
{
    public function __construct(
        protected PermissionRepositoryInterface $permissionRepository
    ) {}

    public function getPaginatedPermissions(int $perPage = 15, array $columns = ['*'], array $filters = []): LengthAwarePaginator
    {
        return $this->permissionRepository->paginate($perPage, $columns, $filters);
    }

    public function getAllPermissions(array $columns = ['*']): Collection
    {
        return $this->permissionRepository->all($columns);
    }

    public function getGroupedPermissions(): array
    {
        return $this->permissionRepository->getGroupedByModule();
    }

    public function getPermissionById(int|string $id): ?Permission
    {
        return $this->permissionRepository->findById($id);
    }

    public function getPermissionBySlug(string $slug): ?Permission
    {
        return $this->permissionRepository->findBySlug($slug);
    }

    public function createPermission(array $data): Permission
    {
        if (empty($data['slug']) && !empty($data['name'])) {
            $module = $data['module'] ?? 'general';
            $data['slug'] = $module . '.' . Str::slug($data['name']);
        }

        return $this->permissionRepository->create($data);
    }

    public function updatePermission(int|string $id, array $data): ?Permission
    {
        return $this->permissionRepository->update($id, $data);
    }

    public function deletePermission(int|string $id): bool
    {
        return $this->permissionRepository->delete($id);
    }

    public function updatePermissionStatus(int|string $id, int $status): ?Permission
    {
        return $this->permissionRepository->updateStatus($id, $status);
    }

    public function syncRolePermissions(int|string $roleId, array $permissionIds): array
    {
        return $this->permissionRepository->syncRolePermissions($roleId, $permissionIds);
    }

    public function getRolePermissions(int|string $roleId): Collection
    {
        return $this->permissionRepository->getRolePermissions($roleId);
    }
}
