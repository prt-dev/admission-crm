<?php

namespace App\Services;

use App\Models\Admission;
use App\Repositories\Contracts\AdmissionRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class AdmissionService
{
    public function __construct(
        protected AdmissionRepositoryInterface $admissionRepository
    ) {}

    public function getPaginatedAdmissions(int $perPage = 15, array $columns = ['*'], array $filters = []): LengthAwarePaginator
    {
        return $this->admissionRepository->paginate($perPage, $columns, $filters);
    }

    public function getAllAdmissions(array $columns = ['*']): Collection
    {
        return $this->admissionRepository->all($columns);
    }

    public function getAdmissionById(int|string $id): ?Admission
    {
        return $this->admissionRepository->findById($id);
    }

    public function getAdmissionByNumber(string $admissionNumber): ?Admission
    {
        return $this->admissionRepository->findByAdmissionNumber($admissionNumber);
    }

    public function createAdmission(array $data): Admission
    {
        if (empty($data['admission_number'])) {
            $data['admission_number'] = 'ADM-' . date('Y') . '-' . strtoupper(substr(uniqid(), -6));
        }

        if (empty($data['registration_number'])) {
            $data['registration_number'] = 'REG-' . date('Y') . '-' . strtoupper(substr(uniqid(), -6));
        }

        if (empty($data['admission_date'])) {
            $data['admission_date'] = now()->toDateString();
        }

        // Calculate final fee and due amount
        $courseFee = (float) ($data['course_fee'] ?? 0);
        $discount = (float) ($data['discount_amount'] ?? 0);
        $finalFee = max(0, $courseFee - $discount);
        $paidAmount = (float) ($data['paid_amount'] ?? 0);
        $dueAmount = max(0, $finalFee - $paidAmount);

        $data['final_fee'] = $finalFee;
        $data['due_amount'] = $dueAmount;

        if ($dueAmount == 0 && $finalFee > 0) {
            $data['payment_status'] = 3; // Paid
        } elseif ($paidAmount > 0 && $dueAmount > 0) {
            $data['payment_status'] = 2; // Partial
        } else {
            $data['payment_status'] = 1; // Pending
        }

        return $this->admissionRepository->create($data);
    }

    public function updateAdmission(int|string $id, array $data): ?Admission
    {
        if (isset($data['course_fee']) || isset($data['discount_amount']) || isset($data['paid_amount'])) {
            $existing = $this->admissionRepository->findById($id, []);
            if ($existing) {
                $courseFee = isset($data['course_fee']) ? (float) $data['course_fee'] : (float) $existing->course_fee;
                $discount = isset($data['discount_amount']) ? (float) $data['discount_amount'] : (float) $existing->discount_amount;
                $finalFee = max(0, $courseFee - $discount);
                $paidAmount = isset($data['paid_amount']) ? (float) $data['paid_amount'] : (float) $existing->paid_amount;
                $dueAmount = max(0, $finalFee - $paidAmount);

                $data['final_fee'] = $finalFee;
                $data['due_amount'] = $dueAmount;

                if ($dueAmount == 0 && $finalFee > 0) {
                    $data['payment_status'] = 3; // Paid
                } elseif ($paidAmount > 0 && $dueAmount > 0) {
                    $data['payment_status'] = 2; // Partial
                } else {
                    $data['payment_status'] = 1; // Pending
                }
            }
        }

        return $this->admissionRepository->update($id, $data);
    }

    public function deleteAdmission(int|string $id): bool
    {
        return $this->admissionRepository->delete($id);
    }

    public function updateAdmissionStatus(int|string $id, int $status): ?Admission
    {
        return $this->admissionRepository->updateStatus($id, $status);
    }

    public function recordPayment(int|string $id, float $amount): ?Admission
    {
        $admission = $this->admissionRepository->findById($id, []);
        if (!$admission) {
            return null;
        }

        $newPaidAmount = (float) $admission->paid_amount + $amount;
        $newDueAmount = max(0, (float) $admission->final_fee - $newPaidAmount);
        $paymentStatus = $newDueAmount <= 0 ? 3 : 2;

        return $this->admissionRepository->updatePaymentStatus($id, $paymentStatus, $newPaidAmount, $newDueAmount);
    }
}
