<?php

namespace App\Services;

use App\Models\Admission;
use App\Models\Attendance;
use App\Repositories\Contracts\AttendanceRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class AttendanceService
{
    public function __construct(
        protected AttendanceRepositoryInterface $attendanceRepository
    ) {}

    public function getPaginatedAttendances(int $perPage = 15, array $columns = ['*'], array $filters = []): LengthAwarePaginator
    {
        return $this->attendanceRepository->paginate($perPage, $columns, $filters);
    }

    public function getAllAttendances(array $columns = ['*']): Collection
    {
        return $this->attendanceRepository->all($columns);
    }

    public function getAttendanceById(int|string $id): ?Attendance
    {
        return $this->attendanceRepository->findById($id);
    }

    public function createAttendance(array $data): Attendance
    {
        if (empty($data['date'])) {
            $data['date'] = now()->toDateString();
        }

        if (empty($data['duration'])) {
            $data['duration'] = 1.00;
        }

        if (empty($data['type'])) {
            $data['type'] = Attendance::TYPE_THEORY;
        } else {
            $data['type'] = strtoupper($data['type']);
        }

        if (!isset($data['status'])) {
            $data['status'] = Attendance::STATUS_PRESENT;
        }

        // If course_id is missing, auto-populate from admission
        if (empty($data['course_id']) && !empty($data['admission_id'])) {
            $admission = Admission::find($data['admission_id']);
            if ($admission) {
                $data['course_id'] = $admission->course_id;
                if (empty($data['batch_id'])) {
                    $data['batch_id'] = $admission->batch_id;
                }
            }
        }

        return $this->attendanceRepository->create($data);
    }

    public function updateAttendance(int|string $id, array $data): ?Attendance
    {
        if (isset($data['type'])) {
            $data['type'] = strtoupper($data['type']);
        }

        return $this->attendanceRepository->update($id, $data);
    }

    public function deleteAttendance(int|string $id): bool
    {
        return $this->attendanceRepository->delete($id);
    }

    public function updateAttendanceStatus(int|string $id, int $status): ?Attendance
    {
        return $this->attendanceRepository->updateStatus($id, $status);
    }

    /**
     * Record attendance in bulk for multiple students (e.g. whole batch session).
     */
    public function bulkRecordAttendance(array $data): Collection
    {
        $batchId = $data['batch_id'];
        $courseId = $data['course_id'] ?? null;
        $date = $data['date'] ?? now()->toDateString();
        $duration = $data['duration'] ?? 1.00;
        $type = strtoupper($data['type'] ?? Attendance::TYPE_THEORY);
        $topicCovered = $data['topic_covered'] ?? null;
        $markedBy = $data['marked_by'] ?? null;

        $records = [];
        foreach ($data['students'] as $student) {
            $admissionId = $student['admission_id'];
            $status = $student['status'] ?? Attendance::STATUS_PRESENT;
            $remarks = $student['remarks'] ?? null;

            $records[] = [
                'admission_id' => $admissionId,
                'batch_id' => $batchId,
                'course_id' => $courseId,
                'date' => $date,
                'duration' => $duration,
                'type' => $type,
                'status' => $status,
                'topic_covered' => $topicCovered,
                'remarks' => $remarks,
                'marked_by' => $markedBy,
            ];
        }

        return $this->attendanceRepository->bulkRecord($records);
    }

    public function getBatchAttendance(int|string $batchId, string $date, ?string $type = null): Collection
    {
        return $this->attendanceRepository->getByBatchAndDate($batchId, $date, $type);
    }

    public function getStudentAttendanceSummary(int|string $admissionId): array
    {
        return $this->attendanceRepository->getStudentSummary($admissionId);
    }

    public function getBatchAttendanceSummary(int|string $batchId): array
    {
        return $this->attendanceRepository->getBatchSummary($batchId);
    }
}
