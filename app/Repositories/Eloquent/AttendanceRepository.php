<?php

namespace App\Repositories\Eloquent;

use App\Models\Admission;
use App\Models\Attendance;
use App\Repositories\Contracts\AttendanceRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class AttendanceRepository implements AttendanceRepositoryInterface
{
    public function __construct(
        protected Attendance $model
    ) {}

    public function all(array $columns = ['*']): Collection
    {
        return $this->model->all($columns);
    }

    public function paginate(int $perPage = 15, array $columns = ['*'], array $filters = []): LengthAwarePaginator
    {
        $query = $this->model->newQuery()->with(['admission', 'batch', 'course', 'marker']);

        if (!empty($filters['search'])) {
            $query->search($filters['search']);
        }

        if (!empty($filters['admission_id'])) {
            $query->byAdmission((int) $filters['admission_id']);
        }

        if (!empty($filters['batch_id'])) {
            $query->byBatch((int) $filters['batch_id']);
        }

        if (!empty($filters['course_id'])) {
            $query->byCourse((int) $filters['course_id']);
        }

        if (!empty($filters['type'])) {
            $query->byType($filters['type']);
        }

        if (!empty($filters['status'])) {
            $query->byStatus((int) $filters['status']);
        }

        if (!empty($filters['date'])) {
            $query->byDate($filters['date']);
        }

        if (!empty($filters['start_date']) || !empty($filters['end_date'])) {
            $query->byDateRange($filters['start_date'] ?? null, $filters['end_date'] ?? null);
        }

        $sortBy = $filters['sort_by'] ?? 'date';
        $sortOrder = $filters['sort_order'] ?? 'desc';
        $query->orderBy($sortBy, $sortOrder);

        return $query->paginate($perPage, $columns);
    }

    public function findById(int|string $id, array $with = ['admission', 'batch', 'course', 'marker']): ?Attendance
    {
        return $this->model->newQuery()->with($with)->find($id);
    }

    public function create(array $data): Attendance
    {
        return $this->model->newQuery()->create($data);
    }

    public function update(int|string $id, array $data): ?Attendance
    {
        $attendance = $this->findById($id, []);
        if (!$attendance) {
            return null;
        }

        $attendance->fill($data);
        $attendance->save();

        return $attendance->fresh(['admission', 'batch', 'course', 'marker']);
    }

    public function delete(int|string $id): bool
    {
        $attendance = $this->findById($id, []);
        if (!$attendance) {
            return false;
        }

        return (bool) $attendance->delete();
    }

    public function updateStatus(int|string $id, int $status): ?Attendance
    {
        $attendance = $this->findById($id, []);
        if (!$attendance) {
            return null;
        }

        $attendance->status = $status;
        $attendance->save();

        return $attendance->fresh(['admission', 'batch', 'course', 'marker']);
    }

    public function bulkRecord(array $records): Collection
    {
        $created = new Collection();

        foreach ($records as $record) {
            // Update if entry for same student, batch, date, and type exists or create new
            $existing = $this->model->newQuery()
                ->where('admission_id', $record['admission_id'])
                ->where('batch_id', $record['batch_id'])
                ->where('date', $record['date'])
                ->where('type', strtoupper($record['type'] ?? Attendance::TYPE_THEORY))
                ->first();

            if ($existing) {
                $existing->fill($record);
                $existing->save();
                $created->push($existing);
            } else {
                $created->push($this->model->newQuery()->create($record));
            }
        }

        return $created;
    }

    public function getByBatchAndDate(int|string $batchId, string $date, ?string $type = null): Collection
    {
        $query = $this->model->newQuery()
            ->with(['admission', 'marker'])
            ->byBatch((int) $batchId)
            ->byDate($date);

        if (!empty($type)) {
            $query->byType($type);
        }

        return $query->get();
    }

    public function getStudentSummary(int|string $admissionId): array
    {
        $records = $this->model->newQuery()
            ->byAdmission((int) $admissionId)
            ->get();

        $totalSessions = $records->count();
        $presentSessions = $records->where('status', Attendance::STATUS_PRESENT)->count();
        $absentSessions = $records->where('status', Attendance::STATUS_ABSENT)->count();
        $lateSessions = $records->where('status', Attendance::STATUS_LATE)->count();
        $halfDaySessions = $records->where('status', Attendance::STATUS_HALF_DAY)->count();
        $leaveSessions = $records->where('status', Attendance::STATUS_LEAVE)->count();

        // Hours calculation by type (T, P, O)
        $theoryHours = (float) $records->where('type', Attendance::TYPE_THEORY)->where('status', Attendance::STATUS_PRESENT)->sum('duration');
        $practicalHours = (float) $records->where('type', Attendance::TYPE_PRACTICAL)->where('status', Attendance::STATUS_PRESENT)->sum('duration');
        $ojtHours = (float) $records->where('type', Attendance::TYPE_OJT)->where('status', Attendance::STATUS_PRESENT)->sum('duration');
        $totalAttendedHours = $theoryHours + $practicalHours + $ojtHours;

        $totalCurriculumHours = (float) $records->sum('duration');
        $attendancePercentage = $totalSessions > 0 ? round(($presentSessions / $totalSessions) * 100, 2) : 0.0;

        return [
            'admission_id' => (int) $admissionId,
            'total_sessions' => $totalSessions,
            'present_count' => $presentSessions,
            'absent_count' => $absentSessions,
            'late_count' => $lateSessions,
            'half_day_count' => $halfDaySessions,
            'leave_count' => $leaveSessions,
            'attendance_percentage' => $attendancePercentage,
            'theory_hours' => $theoryHours,
            'practical_hours' => $practicalHours,
            'ojt_hours' => $ojtHours,
            'total_attended_hours' => $totalAttendedHours,
            'total_curriculum_hours' => $totalCurriculumHours,
        ];
    }

    public function getBatchSummary(int|string $batchId): array
    {
        $records = $this->model->newQuery()
            ->byBatch((int) $batchId)
            ->get();

        $totalRecords = $records->count();
        $presentCount = $records->where('status', Attendance::STATUS_PRESENT)->count();
        $absentCount = $records->where('status', Attendance::STATUS_ABSENT)->count();
        $uniqueDates = $records->pluck('date')->unique()->count();

        $theoryHours = (float) $records->where('type', Attendance::TYPE_THEORY)->where('status', Attendance::STATUS_PRESENT)->sum('duration');
        $practicalHours = (float) $records->where('type', Attendance::TYPE_PRACTICAL)->where('status', Attendance::STATUS_PRESENT)->sum('duration');
        $ojtHours = (float) $records->where('type', Attendance::TYPE_OJT)->where('status', Attendance::STATUS_PRESENT)->sum('duration');

        return [
            'batch_id' => (int) $batchId,
            'total_attendance_records' => $totalRecords,
            'total_sessions_conducted' => $uniqueDates,
            'overall_present_count' => $presentCount,
            'overall_absent_count' => $absentCount,
            'overall_attendance_percentage' => $totalRecords > 0 ? round(($presentCount / $totalRecords) * 100, 2) : 0.0,
            'completed_theory_hours' => $theoryHours,
            'completed_practical_hours' => $practicalHours,
            'completed_ojt_hours' => $ojtHours,
        ];
    }
}
