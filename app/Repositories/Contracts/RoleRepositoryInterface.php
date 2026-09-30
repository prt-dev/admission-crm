<?php

namespace App\Repositories\Contracts;

use App\Models\Role;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

interface RoleRepositoryInterface
{
    public function all(array $columns = ['*']): Collection;
    public function paginate(int $perPage = 15, array $columns = ['*'], array $filters = []): LengthAwarePaginator;
    public function findById(int|string $id, array $with = ['users']): ?Role;
    public function findBySlug(string $slug): ?Role;
    public function create(array $data): Role;
    public function update(int|string $id, array $data): ?Role;
    public function delete(int|string $id): bool;
    public function updateStatus(int|string $id, int $status): ?Role;
}
