<?php

namespace App\Repositories\Eloquent;

use App\Models\Permission;
use App\Models\Role;
use App\Models\RolePermission;
use App\Repositories\Contracts\PermissionRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class PermissionRepository implements PermissionRepositoryInterface
{
    public function __construct(
        protected Permission $model,
        protected RolePermission $rolePermissionModel,
        protected Role $roleModel
    ) {}

    public function all(array $columns = ['*']): Collection
    {
        return $this->model->all($columns);
    }

    public function paginate(int $perPage = 15, array $columns = ['*'], array $filters = []): LengthAwarePaginator
    {
        return $this->model
            ->select($columns)
            ->when(!empty($filters['search']), function ($query) use ($filters) {
                $query->search($filters['search']);
            })
            ->when(!empty($filters['module']), function ($query) use ($filters) {
                $query->byModule($filters['module']);
            })
            ->when(isset($filters['status']) && $filters['status'] !== '', function ($query) use ($filters) {
                $query->where('status', (int) $filters['status']);
            })
            ->orderBy($filters['sort_by'] ?? 'module', $filters['sort_order'] ?? 'asc')
            ->paginate($perPage);
    }

    public function findById(int|string $id, array $with = ['roles']): ?Permission
    {
        return $this->model->with($with)->find($id);
    }

    public function findBySlug(string $slug): ?Permission
    {
        return $this->model->where('slug', $slug)->first();
    }

    public function getByModule(string $module): Collection
    {
        return $this->model->where('module', $module)->active()->get();
    }

    public function getGroupedByModule(): array
    {
        $permissions = $this->model->active()->orderBy('module')->orderBy('name')->get();
        $grouped = [];

        foreach ($permissions as $permission) {
            $grouped[$permission->module][] = $permission;
        }

        return $grouped;
    }

    public function create(array $data): Permission
    {
        return $this->model->create($data);
    }

    public function update(int|string $id, array $data): ?Permission
    {
        $permission = $this->findById($id, []);

        if (!$permission) {
            return null;
        }

        $permission->fill($data);
        $permission->save();

        return $permission;
    }

    public function delete(int|string $id): bool
    {
        $permission = $this->findById($id, []);

        if (!$permission) {
            return false;
        }

        // Delete code-level relations in role_permissions
        $this->rolePermissionModel->where('permission_id', $permission->id)->delete();

        return (bool) $permission->delete();
    }

    public function updateStatus(int|string $id, int $status): ?Permission
    {
        $permission = $this->findById($id, []);

        if (!$permission) {
            return null;
        }

        $permission->status = $status;
        $permission->save();

        return $permission;
    }

    public function syncRolePermissions(int|string $roleId, array $permissionIds): array
    {
        $role = $this->roleModel->find($roleId);

        if (!$role) {
            return [];
        }

        // Code-level sync: remove existing links and recreate
        $this->rolePermissionModel->where('role_id', $role->id)->delete();

        $validPermissions = $this->model->whereIn('id', $permissionIds)->get();
        $slugs = [];

        foreach ($validPermissions as $permission) {
            $this->rolePermissionModel->create([
                'role_id' => $role->id,
                'permission_id' => $permission->id,
            ]);
            $slugs[] = $permission->slug;
        }

        // Also update the permissions json column on the role for fast array lookup
        $role->permissions = $slugs;
        $role->save();

        return $slugs;
    }

    public function getRolePermissions(int|string $roleId): Collection
    {
        return $this->model
            ->whereHas('roles', function ($query) use ($roleId) {
                $query->where('roles.id', $roleId);
            })
            ->get();
    }
}
