<?php

namespace App\Repositories\Contracts;

use App\Models\Permission;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

interface PermissionRepositoryInterface
{
    public function all(array $columns = ['*']): Collection;
    public function paginate(int $perPage = 15, array $columns = ['*'], array $filters = []): LengthAwarePaginator;
    public function findById(int|string $id, array $with = ['roles']): ?Permission;
    public function findBySlug(string $slug): ?Permission;
    public function getByModule(string $module): Collection;
    public function getGroupedByModule(): array;
    public function create(array $data): Permission;
    public function update(int|string $id, array $data): ?Permission;
    public function delete(int|string $id): bool;
    public function updateStatus(int|string $id, int $status): ?Permission;
    public function syncRolePermissions(int|string $roleId, array $permissionIds): array;
    public function getRolePermissions(int|string $roleId): Collection;
}
