<?php

namespace App\Repositories\Eloquent;

use App\Models\Role;
use App\Repositories\Contracts\RoleRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class RoleRepository implements RoleRepositoryInterface
{
    public function __construct(
        protected Role $model
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
            ->when(isset($filters['status']) && $filters['status'] !== '', function ($query) use ($filters) {
                $query->byStatus((int) $filters['status']);
            })
            ->orderBy(
                $filters['sort_by'] ?? 'created_at',
                strtolower($filters['sort_order'] ?? 'desc') === 'asc' ? 'asc' : 'desc'
            )
            ->paginate($perPage);
    }

    public function findById(int|string $id, array $with = ['users']): ?Role
    {
        return $this->model->with($with)->find($id);
    }

    public function findBySlug(string $slug): ?Role
    {
        return $this->model->where('slug', $slug)->first();
    }

    public function create(array $data): Role
    {
        return $this->model->create($data);
    }

    public function update(int|string $id, array $data): ?Role
    {
        $role = $this->findById($id, []);

        if (!$role) {
            return null;
        }

        $role->fill($data);
        $role->save();

        return $role->fresh(['users']);
    }

    public function delete(int|string $id): bool
    {
        $role = $this->findById($id, []);

        if (!$role || $role->is_system) {
            return false;
        }

        return (bool) $role->delete();
    }

    public function updateStatus(int|string $id, int $status): ?Role
    {
        $role = $this->findById($id, []);

        if (!$role) {
            return null;
        }

        $role->status = $status;
        $role->save();

        return $role;
    }
}
