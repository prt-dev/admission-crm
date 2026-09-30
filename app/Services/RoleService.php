<?php

namespace App\Services;

use App\Models\Role;
use App\Repositories\Contracts\RoleRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Str;

class RoleService
{
    public function __construct(
        protected RoleRepositoryInterface $roleRepository
    ) {}

    public function getPaginatedRoles(int $perPage = 15, array $columns = ['*'], array $filters = []): LengthAwarePaginator
    {
        return $this->roleRepository->paginate($perPage, $columns, $filters);
    }

    public function getAllRoles(array $columns = ['*']): Collection
    {
        return $this->roleRepository->all($columns);
    }

    public function getRoleById(int|string $id): ?Role
    {
        return $this->roleRepository->findById($id);
    }

    public function getRoleBySlug(string $slug): ?Role
    {
        return $this->roleRepository->findBySlug($slug);
    }

    public function createRole(array $data): Role
    {
        if (empty($data['slug']) && !empty($data['name'])) {
            $data['slug'] = Str::slug($data['name']);
        }

        return $this->roleRepository->create($data);
    }

    public function updateRole(int|string $id, array $data): ?Role
    {
        if (isset($data['name']) && empty($data['slug'])) {
            $data['slug'] = Str::slug($data['name']);
        }

        return $this->roleRepository->update($id, $data);
    }

    public function deleteRole(int|string $id): bool
    {
        return $this->roleRepository->delete($id);
    }

    public function updateRoleStatus(int|string $id, int $status): ?Role
    {
        return $this->roleRepository->updateStatus($id, $status);
    }
}
