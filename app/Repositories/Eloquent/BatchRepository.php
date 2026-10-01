<?php

namespace App\Repositories\Eloquent;

use App\Models\Batch;
use App\Repositories\Contracts\BatchRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class BatchRepository implements BatchRepositoryInterface
{
    public function __construct(
        protected Batch $model
    ) {
    }

    public function all(array $columns = ['*']): Collection
    {
        return $this->model->all($columns);
    }

    public function paginate(int $perPage = 15, array $columns = ['*'], array $filters = []): LengthAwarePaginator
    {
        return $this->model
            ->select($columns)
            ->with(['courses', 'instructor', 'creator'])
            ->when(!empty($filters['search']), function ($query) use ($filters) {
                $query->search($filters['search']);
            })
            ->when(isset($filters['course_id']) && $filters['course_id'] !== '', function ($query) use ($filters) {
                $query->byCourse((int) $filters['course_id']);
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

    public function findById(int|string $id, array $with = ['courses', 'instructor', 'creator']): ?Batch
    {
        return $this->model->with($with)->find($id);
    }

    public function create(array $data): Batch
    {
        $courseIds = $data['course_ids'] ?? null;
        unset($data['course_ids']);

        $batch = $this->model->create($data);

        if ($courseIds !== null) {
            $batch->courses()->sync($courseIds);
        }

        return $batch->fresh(['courses', 'instructor', 'creator']);
    }

    public function update(int|string $id, array $data): ?Batch
    {
        $batch = $this->findById($id, []);

        if (!$batch) {
            return null;
        }

        $courseIds = $data['course_ids'] ?? null;
        unset($data['course_ids']);

        $batch->fill($data);
        $batch->save();

        if ($courseIds !== null) {
            $batch->courses()->sync($courseIds);
        }

        return $batch->fresh(['courses', 'instructor', 'creator']);
    }

    public function delete(int|string $id): bool
    {
        $batch = $this->findById($id, []);

        if (!$batch) {
            return false;
        }

        return (bool) $batch->delete();
    }

    public function updateStatus(int|string $id, int $status): ?Batch
    {
        $batch = $this->findById($id, []);

        if (!$batch) {
            return null;
        }

        $batch->status = $status;
        $batch->save();

        return $batch;
    }
}
