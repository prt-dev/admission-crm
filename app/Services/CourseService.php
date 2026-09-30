<?php

namespace App\Services;

use App\Models\Course;
use App\Repositories\Contracts\CourseRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class CourseService
{
    public function __construct(
        protected CourseRepositoryInterface $courseRepository
    ) {}

    public function getPaginatedCourses(int $perPage = 15, array $columns = ['*'], array $filters = []): LengthAwarePaginator
    {
        return $this->courseRepository->paginate($perPage, $columns, $filters);
    }

    public function getAllCourses(array $columns = ['*']): Collection
    {
        return $this->courseRepository->all($columns);
    }

    public function getCourseById(int|string $id): ?Course
    {
        return $this->courseRepository->findById($id);
    }

    public function createCourse(array $data): Course
    {
        return $this->courseRepository->create($data);
    }

    public function updateCourse(int|string $id, array $data): ?Course
    {
        return $this->courseRepository->update($id, $data);
    }

    public function deleteCourse(int|string $id): bool
    {
        return $this->courseRepository->delete($id);
    }

    public function updateCourseStatus(int|string $id, int $status): ?Course
    {
        return $this->courseRepository->updateStatus($id, $status);
    }
}
