<?php

namespace App\Repositories\Eloquent;

use App\Models\Course;
use App\Repositories\Contracts\CourseRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class CourseRepository implements CourseRepositoryInterface
{
    public function __construct(
        protected Course $model
    ) {}

    public function all(array $columns = ['*']): Collection
    {
        return $this->model->all($columns);
    }

    public function paginate(int $perPage = 15, array $columns = ['*'], array $filters = []): LengthAwarePaginator
    {
        return $this->model
            ->select($columns)
            ->with(['creator'])
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

    public function findById(int|string $id, array $with = ['creator', 'batches']): ?Course
    {
        return $this->model->with($with)->find($id);
    }

    public function create(array $data): Course
    {
        return $this->model->create($data);
    }

    public function update(int|string $id, array $data): ?Course
    {
        $course = $this->findById($id, []);

        if (!$course) {
            return null;
        }

        $course->fill($data);
        $course->save();

        return $course->fresh(['creator', 'batches']);
    }

    public function delete(int|string $id): bool
    {
        $course = $this->findById($id, []);

        if (!$course) {
            return false;
        }

        return (bool) $course->delete();
    }

    public function updateStatus(int|string $id, int $status): ?Course
    {
        $course = $this->findById($id, []);

        if (!$course) {
            return null;
        }

        $course->status = $status;
        $course->save();

        return $course;
    }
}
