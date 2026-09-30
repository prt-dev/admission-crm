<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admission\RecordAdmissionPaymentRequest;
use App\Http\Requests\Admission\StoreAdmissionRequest;
use App\Http\Requests\Admission\UpdateAdmissionRequest;
use App\Http\Requests\Admission\UpdateAdmissionStatusRequest;
use App\Http\Resources\AdmissionResource;
use App\Responses\ApiResponse;
use App\Services\AdmissionService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdmissionController extends Controller
{
    public function __construct(
        protected AdmissionService $admissionService
    ) {}

    public function index(Request $request): JsonResponse
    {
        $filters = [
            'search' => $request->query('search'),
            'course_id' => $request->query('course_id'),
            'batch_id' => $request->query('batch_id'),
            'status' => $request->query('status'),
            'payment_status' => $request->query('payment_status'),
            'sort_by' => $request->query('sort_by', 'created_at'),
            'sort_order' => $request->query('sort_order', 'desc'),
        ];

        $perPage = (int) $request->query('per_page', 15);
        $admissions = $this->admissionService->getPaginatedAdmissions($perPage, ['*'], $filters);

        return ApiResponse::paginate(
            $admissions,
            AdmissionResource::class,
            'Admissions retrieved successfully.'
        );
    }

    public function store(StoreAdmissionRequest $request): JsonResponse
    {
        $admission = $this->admissionService->createAdmission($request->validated());

        return ApiResponse::success(
            new AdmissionResource($admission),
            'Admission created successfully.',
            Response::HTTP_CREATED
        );
    }

    public function show(int|string $id): JsonResponse
    {
        $admission = $this->admissionService->getAdmissionById($id);

        if (!$admission) {
            return ApiResponse::error('Admission not found.', Response::HTTP_NOT_FOUND);
        }

        return ApiResponse::success(
            new AdmissionResource($admission),
            'Admission retrieved successfully.'
        );
    }

    public function update(UpdateAdmissionRequest $request, int|string $id): JsonResponse
    {
        $admission = $this->admissionService->updateAdmission($id, $request->validated());

        if (!$admission) {
            return ApiResponse::error('Admission not found or update failed.', Response::HTTP_NOT_FOUND);
        }

        return ApiResponse::success(
            new AdmissionResource($admission),
            'Admission updated successfully.'
        );
    }

    public function destroy(int|string $id): JsonResponse
    {
        $deleted = $this->admissionService->deleteAdmission($id);

        if (!$deleted) {
            return ApiResponse::error('Admission not found or deletion failed.', Response::HTTP_NOT_FOUND);
        }

        return ApiResponse::success(
            null,
            'Admission deleted successfully.'
        );
    }

    public function updateStatus(UpdateAdmissionStatusRequest $request, int|string $id): JsonResponse
    {
        $admission = $this->admissionService->updateAdmissionStatus($id, (int) $request->validated()['status']);

        if (!$admission) {
            return ApiResponse::error('Admission not found.', Response::HTTP_NOT_FOUND);
        }

        return ApiResponse::success(
            new AdmissionResource($admission),
            'Admission status updated successfully.'
        );
    }

    public function recordPayment(RecordAdmissionPaymentRequest $request, int|string $id): JsonResponse
    {
        $amount = (float) $request->validated()['amount'];
        $admission = $this->admissionService->recordPayment($id, $amount);

        if (!$admission) {
            return ApiResponse::error('Admission not found.', Response::HTTP_NOT_FOUND);
        }

        return ApiResponse::success(
            new AdmissionResource($admission),
            'Payment recorded successfully.'
        );
    }
}
