<?php

namespace App\Services;

use App\Models\Batch;
use App\Repositories\Contracts\BatchRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class BatchService
{
    public function __construct(
        protected BatchRepositoryInterface $batchRepository
    ) {}

    public function getPaginatedBatches(int $perPage = 15, array $columns = ['*'], array $filters = []): LengthAwarePaginator
    {
        return $this->batchRepository->paginate($perPage, $columns, $filters);
    }

    public function getAllBatches(array $columns = ['*']): Collection
    {
        return $this->batchRepository->all($columns);
    }

    public function getBatchById(int|string $id): ?Batch
    {
        return $this->batchRepository->findById($id);
    }

    public function createBatch(array $data): Batch
    {
        return $this->batchRepository->create($data);
    }

    public function updateBatch(int|string $id, array $data): ?Batch
    {
        return $this->batchRepository->update($id, $data);
    }

    public function deleteBatch(int|string $id): bool
    {
        return $this->batchRepository->delete($id);
    }

    public function updateBatchStatus(int|string $id, int $status): ?Batch
    {
        return $this->batchRepository->updateStatus($id, $status);
    }
}
