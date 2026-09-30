<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Course\StoreCourseRequest;
use App\Http\Requests\Course\UpdateCourseRequest;
use App\Http\Requests\Course\UpdateCourseStatusRequest;
use App\Http\Resources\CourseResource;
use App\Responses\ApiResponse;
use App\Services\CourseService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CourseController extends Controller
{
    public function __construct(
        protected CourseService $courseService
    ) {}

    public function index(Request $request): JsonResponse
    {
        $filters = [
            'search' => $request->query('search'),
            'status' => $request->query('status'),
            'sort_by' => $request->query('sort_by', 'created_at'),
            'sort_order' => $request->query('sort_order', 'desc'),
        ];

        $perPage = (int) $request->query('per_page', 15);
        $courses = $this->courseService->getPaginatedCourses($perPage, ['*'], $filters);

        return ApiResponse::paginate(
            $courses,
            CourseResource::class,
            'Courses retrieved successfully.'
        );
    }

    public function store(StoreCourseRequest $request): JsonResponse
    {
        $course = $this->courseService->createCourse($request->validated());

        return ApiResponse::success(
            new CourseResource($course),
            'Course created successfully.',
            Response::HTTP_CREATED
        );
    }

    public function show(int|string $id): JsonResponse
    {
        $course = $this->courseService->getCourseById($id);

        if (!$course) {
            return ApiResponse::error('Course not found.', Response::HTTP_NOT_FOUND);
        }

        return ApiResponse::success(
            new CourseResource($course),
            'Course retrieved successfully.'
        );
    }

    public function update(UpdateCourseRequest $request, int|string $id): JsonResponse
    {
        $course = $this->courseService->updateCourse($id, $request->validated());

        if (!$course) {
            return ApiResponse::error('Course not found or update failed.', Response::HTTP_NOT_FOUND);
        }

        return ApiResponse::success(
            new CourseResource($course),
            'Course updated successfully.'
        );
    }

    public function destroy(int|string $id): JsonResponse
    {
        $deleted = $this->courseService->deleteCourse($id);

        if (!$deleted) {
            return ApiResponse::error('Course not found or deletion failed.', Response::HTTP_NOT_FOUND);
        }

        return ApiResponse::success(
            null,
            'Course deleted successfully.'
        );
    }

    public function updateStatus(UpdateCourseStatusRequest $request, int|string $id): JsonResponse
    {
        $course = $this->courseService->updateCourseStatus($id, (int) $request->validated()['status']);

        if (!$course) {
            return ApiResponse::error('Course not found.', Response::HTTP_NOT_FOUND);
        }

        return ApiResponse::success(
            new CourseResource($course),
            'Course status updated successfully.'
        );
    }
}
