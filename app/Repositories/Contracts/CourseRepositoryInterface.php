<?php

namespace App\Repositories\Contracts;

use App\Models\Course;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

interface CourseRepositoryInterface
{
    public function all(array $columns = ['*']): Collection;
    public function paginate(int $perPage = 15, array $columns = ['*'], array $filters = []): LengthAwarePaginator;
    public function findById(int|string $id, array $with = ['creator', 'batches']): ?Course;
    public function create(array $data): Course;
    public function update(int|string $id, array $data): ?Course;
    public function delete(int|string $id): bool;
    public function updateStatus(int|string $id, int $status): ?Course;
}
