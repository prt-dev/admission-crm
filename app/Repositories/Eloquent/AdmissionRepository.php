<?php

namespace App\Repositories\Eloquent;

use App\Models\Admission;
use App\Repositories\Contracts\AdmissionRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class AdmissionRepository implements AdmissionRepositoryInterface
{
    public function __construct(
        protected Admission $model
    ) {}

    public function all(array $columns = ['*']): Collection
    {
        return $this->model->all($columns);
    }

    public function paginate(int $perPage = 15, array $columns = ['*'], array $filters = []): LengthAwarePaginator
    {
        return $this->model
            ->select($columns)
            ->with(['lead', 'course', 'batch', 'counselor', 'user'])
            ->when(!empty($filters['search']), function ($query) use ($filters) {
                $query->search($filters['search']);
            })
            ->when(isset($filters['course_id']) && $filters['course_id'] !== '', function ($query) use ($filters) {
                $query->where('course_id', (int) $filters['course_id']);
            })
            ->when(isset($filters['batch_id']) && $filters['batch_id'] !== '', function ($query) use ($filters) {
                $query->where('batch_id', (int) $filters['batch_id']);
            })
            ->when(isset($filters['status']) && $filters['status'] !== '', function ($query) use ($filters) {
                $query->byStatus((int) $filters['status']);
            })
            ->when(isset($filters['payment_status']) && $filters['payment_status'] !== '', function ($query) use ($filters) {
                $query->byPaymentStatus((int) $filters['payment_status']);
            })
            ->orderBy(
                $filters['sort_by'] ?? 'created_at',
                strtolower($filters['sort_order'] ?? 'desc') === 'asc' ? 'asc' : 'desc'
            )
            ->paginate($perPage);
    }

    public function findById(int|string $id, array $with = ['lead', 'course', 'batch', 'counselor', 'user']): ?Admission
    {
        return $this->model->with($with)->find($id);
    }

    public function findByAdmissionNumber(string $admissionNumber): ?Admission
    {
        return $this->model->where('admission_number', $admissionNumber)->first();
    }

    public function findByRegistrationNumber(string $registrationNumber): ?Admission
    {
        return $this->model->where('registration_number', $registrationNumber)->first();
    }

    public function create(array $data): Admission
    {
        return $this->model->create($data);
    }

    public function update(int|string $id, array $data): ?Admission
    {
        $admission = $this->findById($id, []);

        if (!$admission) {
            return null;
        }

        $admission->fill($data);
        $admission->save();

        return $admission->fresh(['lead', 'course', 'batch', 'counselor', 'user']);
    }

    public function delete(int|string $id): bool
    {
        $admission = $this->findById($id, []);

        if (!$admission) {
            return false;
        }

        return (bool) $admission->delete();
    }

    public function updateStatus(int|string $id, int $status): ?Admission
    {
        $admission = $this->findById($id, []);

        if (!$admission) {
            return null;
        }

        $admission->status = $status;
        $admission->save();

        return $admission;
    }

    public function updatePaymentStatus(int|string $id, int $paymentStatus, float $paidAmount, float $dueAmount): ?Admission
    {
        $admission = $this->findById($id, []);

        if (!$admission) {
            return null;
        }

        $admission->payment_status = $paymentStatus;
        $admission->paid_amount = $paidAmount;
        $admission->due_amount = $dueAmount;
        $admission->save();

        return $admission;
    }
}
