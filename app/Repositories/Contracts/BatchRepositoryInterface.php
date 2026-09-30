<?php

namespace App\Repositories\Contracts;

use App\Models\Batch;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

interface BatchRepositoryInterface
{
    public function all(array $columns = ['*']): Collection;
    public function paginate(int $perPage = 15, array $columns = ['*'], array $filters = []): LengthAwarePaginator;
    public function findById(int|string $id, array $with = ['course', 'instructor', 'creator']): ?Batch;
    public function create(array $data): Batch;
    public function update(int|string $id, array $data): ?Batch;
    public function delete(int|string $id): bool;
    public function updateStatus(int|string $id, int $status): ?Batch;
}
