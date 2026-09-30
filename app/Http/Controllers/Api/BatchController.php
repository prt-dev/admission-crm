<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Batch\StoreBatchRequest;
use App\Http\Requests\Batch\UpdateBatchRequest;
use App\Http\Requests\Batch\UpdateBatchStatusRequest;
use App\Http\Resources\BatchResource;
use App\Responses\ApiResponse;
use App\Services\BatchService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class BatchController extends Controller
{
    public function __construct(
        protected BatchService $batchService
    ) {}

    public function index(Request $request): JsonResponse
    {
        $filters = [
            'search' => $request->query('search'),
            'course_id' => $request->query('course_id'),
            'status' => $request->query('status'),
            'sort_by' => $request->query('sort_by', 'created_at'),
            'sort_order' => $request->query('sort_order', 'desc'),
        ];

        $perPage = (int) $request->query('per_page', 15);
        $batches = $this->batchService->getPaginatedBatches($perPage, ['*'], $filters);

        return ApiResponse::paginate(
            $batches,
            BatchResource::class,
            'Batches retrieved successfully.'
        );
    }

    public function store(StoreBatchRequest $request): JsonResponse
    {
        $batch = $this->batchService->createBatch($request->validated());

        return ApiResponse::success(
            new BatchResource($batch),
            'Batch created successfully.',
            Response::HTTP_CREATED
        );
    }

    public function show(int|string $id): JsonResponse
    {
        $batch = $this->batchService->getBatchById($id);

        if (!$batch) {
            return ApiResponse::error('Batch not found.', Response::HTTP_NOT_FOUND);
        }

        return ApiResponse::success(
            new BatchResource($batch),
            'Batch retrieved successfully.'
        );
    }

    public function update(UpdateBatchRequest $request, int|string $id): JsonResponse
    {
        $batch = $this->batchService->updateBatch($id, $request->validated());

        if (!$batch) {
            return ApiResponse::error('Batch not found or update failed.', Response::HTTP_NOT_FOUND);
        }

        return ApiResponse::success(
            new BatchResource($batch),
            'Batch updated successfully.'
        );
    }

    public function destroy(int|string $id): JsonResponse
    {
        $deleted = $this->batchService->deleteBatch($id);

        if (!$deleted) {
            return ApiResponse::error('Batch not found or deletion failed.', Response::HTTP_NOT_FOUND);
        }

        return ApiResponse::success(
            null,
            'Batch deleted successfully.'
        );
    }

    public function updateStatus(UpdateBatchStatusRequest $request, int|string $id): JsonResponse
    {
        $batch = $this->batchService->updateBatchStatus($id, (int) $request->validated()['status']);

        if (!$batch) {
            return ApiResponse::error('Batch not found.', Response::HTTP_NOT_FOUND);
        }

        return ApiResponse::success(
            new BatchResource($batch),
            'Batch status updated successfully.'
        );
    }
}
