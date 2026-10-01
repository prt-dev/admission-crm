<?php

namespace App\Repositories\Eloquent;

use App\Models\AcademicSession;
use App\Models\Batch;
use App\Repositories\Contracts\AcademicSessionRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class AcademicSessionRepository implements AcademicSessionRepositoryInterface
{
    public function __construct(
        protected AcademicSession $model
    ) {}

    public function all(array $columns = ['*']): Collection
    {
        return $this->model->all($columns);
    }

    public function paginate(int $perPage = 15, array $columns = ['*'], array $filters = []): LengthAwarePaginator
    {
        $query = $this->model->newQuery()->with(['creator'])->withCount(['batches', 'admissions']);

        if (!empty($filters['search'])) {
            $query->search($filters['search']);
        }

        if (!empty($filters['status'])) {
            $query->byStatus((int) $filters['status']);
        }

        if (isset($filters['is_current'])) {
            $query->where('is_current', filter_var($filters['is_current'], FILTER_VALIDATE_BOOLEAN));
        }

        $sortBy = $filters['sort_by'] ?? 'start_date';
        $sortOrder = $filters['sort_order'] ?? 'desc';
        $query->orderBy($sortBy, $sortOrder);

        return $query->paginate($perPage, $columns);
    }

    public function findById(int|string $id, array $with = ['batches', 'admissions', 'creator']): ?AcademicSession
    {
        return $this->model->newQuery()->with($with)->withCount(['batches', 'admissions'])->find($id);
    }

    public function findByCode(string $code): ?AcademicSession
    {
        return $this->model->newQuery()->where('code', $code)->first();
    }

    public function getCurrentSession(): ?AcademicSession
    {
        return $this->model->newQuery()->where('is_current', true)->first();
    }

    public function create(array $data): AcademicSession
    {
        // If marked as current, reset previous current sessions
        if (!empty($data['is_current'])) {
            $this->model->newQuery()->where('is_current', true)->update(['is_current' => false]);
        }

        return $this->model->newQuery()->create($data);
    }

    public function update(int|string $id, array $data): ?AcademicSession
    {
        $session = $this->findById($id, []);
        if (!$session) {
            return null;
        }

        // If updated to be current, reset all other sessions
        if (!empty($data['is_current']) && !$session->is_current) {
            $this->model->newQuery()->where('id', '!=', $id)->where('is_current', true)->update(['is_current' => false]);
        }

        $session->fill($data);
        $session->save();

        return $session->fresh(['batches', 'admissions', 'creator']);
    }

    public function delete(int|string $id): bool
    {
        $session = $this->findById($id, []);
        if (!$session) {
            return false;
        }

        return (bool) $session->delete();
    }

    public function updateStatus(int|string $id, int $status): ?AcademicSession
    {
        $session = $this->findById($id, []);
        if (!$session) {
            return null;
        }

        $session->status = $status;
        $session->save();

        return $session->fresh(['batches', 'admissions', 'creator']);
    }

    public function setCurrentSession(int|string $id): ?AcademicSession
    {
        $session = $this->findById($id, []);
        if (!$session) {
            return null;
        }

        // Set all other sessions to is_current = false
        $this->model->newQuery()->where('id', '!=', $id)->update(['is_current' => false]);

        $session->is_current = true;
        $session->status = AcademicSession::STATUS_ACTIVE;
        $session->save();

        return $session->fresh(['batches', 'admissions', 'creator']);
    }

    public function getBatchesBySession(int|string $id): Collection
    {
        return Batch::with(['course', 'instructor'])
            ->where('academic_session_id', $id)
            ->get();
    }
}
