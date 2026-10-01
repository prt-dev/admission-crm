<?php

namespace App\Repositories\Contracts;

use App\Models\AcademicSession;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

interface AcademicSessionRepositoryInterface
{
    public function all(array $columns = ['*']): Collection;
    public function paginate(int $perPage = 15, array $columns = ['*'], array $filters = []): LengthAwarePaginator;
    public function findById(int|string $id, array $with = ['batches', 'admissions', 'creator']): ?AcademicSession;
    public function findByCode(string $code): ?AcademicSession;
    public function getCurrentSession(): ?AcademicSession;
    public function create(array $data): AcademicSession;
    public function update(int|string $id, array $data): ?AcademicSession;
    public function delete(int|string $id): bool;
    public function updateStatus(int|string $id, int $status): ?AcademicSession;
    public function setCurrentSession(int|string $id): ?AcademicSession;
    public function getBatchesBySession(int|string $id): Collection;
}
