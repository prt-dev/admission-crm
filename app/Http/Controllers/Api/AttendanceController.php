<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Attendance\BulkStoreAttendanceRequest;
use App\Http\Requests\Attendance\StoreAttendanceRequest;
use App\Http\Requests\Attendance\UpdateAttendanceRequest;
use App\Http\Requests\Attendance\UpdateAttendanceStatusRequest;
use App\Http\Resources\AttendanceResource;
use App\Responses\ApiResponse;
use App\Services\AttendanceService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AttendanceController extends Controller
{
    public function __construct(
        protected AttendanceService $attendanceService
    ) {}

    public function index(Request $request): JsonResponse
    {
        $filters = [
            'search' => $request->query('search'),
            'admission_id' => $request->query('admission_id'),
            'batch_id' => $request->query('batch_id'),
            'course_id' => $request->query('course_id'),
            'type' => $request->query('type'),
            'status' => $request->query('status'),
            'date' => $request->query('date'),
            'start_date' => $request->query('start_date'),
            'end_date' => $request->query('end_date'),
            'sort_by' => $request->query('sort_by', 'date'),
            'sort_order' => $request->query('sort_order', 'desc'),
        ];

        $perPage = (int) $request->query('per_page', 15);
        $attendances = $this->attendanceService->getPaginatedAttendances($perPage, ['*'], $filters);

        return ApiResponse::paginate(
            $attendances,
            AttendanceResource::class,
            'Attendances retrieved successfully.'
        );
    }

    public function store(StoreAttendanceRequest $request): JsonResponse
    {
        $attendance = $this->attendanceService->createAttendance($request->validated());

        return ApiResponse::success(
            new AttendanceResource($attendance),
            'Attendance recorded successfully.',
            Response::HTTP_CREATED
        );
    }

    public function show(int|string $id): JsonResponse
    {
        $attendance = $this->attendanceService->getAttendanceById($id);

        if (!$attendance) {
            return ApiResponse::error('Attendance record not found.', Response::HTTP_NOT_FOUND);
        }

        return ApiResponse::success(
            new AttendanceResource($attendance),
            'Attendance record retrieved successfully.'
        );
    }

    public function update(UpdateAttendanceRequest $request, int|string $id): JsonResponse
    {
        $attendance = $this->attendanceService->updateAttendance($id, $request->validated());

        if (!$attendance) {
            return ApiResponse::error('Attendance record not found or update failed.', Response::HTTP_NOT_FOUND);
        }

        return ApiResponse::success(
            new AttendanceResource($attendance),
            'Attendance record updated successfully.'
        );
    }

    public function destroy(int|string $id): JsonResponse
    {
        $deleted = $this->attendanceService->deleteAttendance($id);

        if (!$deleted) {
            return ApiResponse::error('Attendance record not found or deletion failed.', Response::HTTP_NOT_FOUND);
        }

        return ApiResponse::success(
            null,
            'Attendance record deleted successfully.'
        );
    }

    public function updateStatus(UpdateAttendanceStatusRequest $request, int|string $id): JsonResponse
    {
        $attendance = $this->attendanceService->updateAttendanceStatus($id, (int) $request->validated()['status']);

        if (!$attendance) {
            return ApiResponse::error('Attendance record not found.', Response::HTTP_NOT_FOUND);
        }

        return ApiResponse::success(
            new AttendanceResource($attendance),
            'Attendance status updated successfully.'
        );
    }

    public function bulkStore(BulkStoreAttendanceRequest $request): JsonResponse
    {
        $records = $this->attendanceService->bulkRecordAttendance($request->validated());

        return ApiResponse::success(
            AttendanceResource::collection($records),
            'Batch attendance marked successfully.',
            Response::HTTP_CREATED
        );
    }

    public function batchAttendance(Request $request, int|string $batchId): JsonResponse
    {
        $date = $request->query('date', now()->toDateString());
        $type = $request->query('type');

        $records = $this->attendanceService->getBatchAttendance($batchId, $date, $type);

        return ApiResponse::success(
            AttendanceResource::collection($records),
            'Batch attendance records retrieved successfully.'
        );
    }

    public function studentSummary(int|string $admissionId): JsonResponse
    {
        $summary = $this->attendanceService->getStudentAttendanceSummary($admissionId);

        return ApiResponse::success(
            $summary,
            'Student attendance summary retrieved successfully.'
        );
    }

    public function batchSummary(int|string $batchId): JsonResponse
    {
        $summary = $this->attendanceService->getBatchAttendanceSummary($batchId);

        return ApiResponse::success(
            $summary,
            'Batch attendance summary retrieved successfully.'
        );
    }
}
