<?php

namespace App\Repositories\Contracts;

use App\Models\Attendance;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

interface AttendanceRepositoryInterface
{
    public function all(array $columns = ['*']): Collection;
    public function paginate(int $perPage = 15, array $columns = ['*'], array $filters = []): LengthAwarePaginator;
    public function findById(int|string $id, array $with = ['admission', 'batch', 'course', 'marker']): ?Attendance;
    public function create(array $data): Attendance;
    public function update(int|string $id, array $data): ?Attendance;
    public function delete(int|string $id): bool;
    public function updateStatus(int|string $id, int $status): ?Attendance;
    public function bulkRecord(array $records): Collection;
    public function getByBatchAndDate(int|string $batchId, string $date, ?string $type = null): Collection;
    public function getStudentSummary(int|string $admissionId): array;
    public function getBatchSummary(int|string $batchId): array;
}
