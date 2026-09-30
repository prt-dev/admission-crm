<?php

namespace App\Repositories\Contracts;

use App\Models\Admission;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

interface AdmissionRepositoryInterface
{
    public function all(array $columns = ['*']): Collection;
    public function paginate(int $perPage = 15, array $columns = ['*'], array $filters = []): LengthAwarePaginator;
    public function findById(int|string $id, array $with = ['lead', 'course', 'batch', 'counselor', 'user']): ?Admission;
    public function findByAdmissionNumber(string $admissionNumber): ?Admission;
    public function findByRegistrationNumber(string $registrationNumber): ?Admission;
    public function create(array $data): Admission;
    public function update(int|string $id, array $data): ?Admission;
    public function delete(int|string $id): bool;
    public function updateStatus(int|string $id, int $status): ?Admission;
    public function updatePaymentStatus(int|string $id, int $paymentStatus, float $paidAmount, float $dueAmount): ?Admission;
}
