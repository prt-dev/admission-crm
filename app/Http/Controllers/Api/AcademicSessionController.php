<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\AcademicSession\StoreAcademicSessionRequest;
use App\Http\Requests\AcademicSession\UpdateAcademicSessionRequest;
use App\Http\Requests\AcademicSession\UpdateAcademicSessionStatusRequest;
use App\Http\Resources\AcademicSessionResource;
use App\Http\Resources\BatchResource;
use App\Responses\ApiResponse;
use App\Services\AcademicSessionService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AcademicSessionController extends Controller
{
    public function __construct(
        protected AcademicSessionService $sessionService
    ) {}

    public function index(Request $request): JsonResponse
    {
        $filters = [
            'search' => $request->query('search'),
            'status' => $request->query('status'),
            'is_current' => $request->query('is_current'),
            'sort_by' => $request->query('sort_by', 'start_date'),
            'sort_order' => $request->query('sort_order', 'desc'),
        ];

        $perPage = (int) $request->query('per_page', 15);
        $sessions = $this->sessionService->getPaginatedSessions($perPage, ['*'], $filters);

        return ApiResponse::paginate(
            $sessions,
            AcademicSessionResource::class,
            'Academic sessions retrieved successfully.'
        );
    }

    public function current(): JsonResponse
    {
        $session = $this->sessionService->getCurrentSession();

        if (!$session) {
            return ApiResponse::error('No active academic session found.', Response::HTTP_NOT_FOUND);
        }

        return ApiResponse::success(
            new AcademicSessionResource($session),
            'Current academic session retrieved successfully.'
        );
    }

    public function store(StoreAcademicSessionRequest $request): JsonResponse
    {
        $session = $this->sessionService->createSession($request->validated());

        return ApiResponse::success(
            new AcademicSessionResource($session),
            'Academic session created successfully.',
            Response::HTTP_CREATED
        );
    }

    public function show(int|string $id): JsonResponse
    {
        $session = $this->sessionService->getSessionById($id);

        if (!$session) {
            return ApiResponse::error('Academic session not found.', Response::HTTP_NOT_FOUND);
        }

        return ApiResponse::success(
            new AcademicSessionResource($session),
            'Academic session retrieved successfully.'
        );
    }

    public function update(UpdateAcademicSessionRequest $request, int|string $id): JsonResponse
    {
        $session = $this->sessionService->updateSession($id, $request->validated());

        if (!$session) {
            return ApiResponse::error('Academic session not found or update failed.', Response::HTTP_NOT_FOUND);
        }

        return ApiResponse::success(
            new AcademicSessionResource($session),
            'Academic session updated successfully.'
        );
    }

    public function destroy(int|string $id): JsonResponse
    {
        $deleted = $this->sessionService->deleteSession($id);

        if (!$deleted) {
            return ApiResponse::error('Academic session not found or deletion failed.', Response::HTTP_NOT_FOUND);
        }

        return ApiResponse::success(
            null,
            'Academic session deleted successfully.'
        );
    }

    public function updateStatus(UpdateAcademicSessionStatusRequest $request, int|string $id): JsonResponse
    {
        $session = $this->sessionService->updateSessionStatus($id, (int) $request->validated()['status']);

        if (!$session) {
            return ApiResponse::error('Academic session not found.', Response::HTTP_NOT_FOUND);
        }

        return ApiResponse::success(
            new AcademicSessionResource($session),
            'Academic session status updated successfully.'
        );
    }

    public function setCurrent(int|string $id): JsonResponse
    {
        $session = $this->sessionService->setCurrentSession($id);

        if (!$session) {
            return ApiResponse::error('Academic session not found.', Response::HTTP_NOT_FOUND);
        }

        return ApiResponse::success(
            new AcademicSessionResource($session),
            'Academic session set as current active session successfully.'
        );
    }

    public function batches(int|string $id): JsonResponse
    {
        $batches = $this->sessionService->getBatchesBySession($id);

        return ApiResponse::success(
            $batches,
            'Batches for academic session retrieved successfully.'
        );
    }
}
