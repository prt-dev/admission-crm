<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Lead\AssignLeadRequest;
use App\Http\Requests\Lead\StoreLeadFollowUpRequest;
use App\Http\Requests\Lead\StoreLeadRequest;
use App\Http\Requests\Lead\UpdateLeadFollowUpStatusRequest;
use App\Http\Requests\Lead\UpdateLeadRequest;
use App\Http\Requests\Lead\UpdateLeadStatusRequest;
use App\Http\Resources\LeadFollowUpResource;
use App\Http\Resources\LeadResource;
use App\Responses\ApiResponse;
use App\Services\LeadService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class LeadController extends Controller
{
    /**
     * @param LeadService $leadService
     */
    public function __construct(
        protected LeadService $leadService
    ) {}

    /**
     * Display a listing of leads.
     */
    public function index(Request $request): JsonResponse
    {
        $filters = [
            'search' => $request->query('search'),
            'status' => $request->query('status'),
            'assigned_to' => $request->query('assigned_to'),
            'source' => $request->query('source'),
            'sort_by' => $request->query('sort_by', 'created_at'),
            'sort_order' => $request->query('sort_order', 'desc'),
        ];

        $perPage = (int) $request->query('per_page', 15);
        $leads = $this->leadService->getPaginatedLeads($perPage, ['*'], $filters);

        return ApiResponse::paginate(
            $leads,
            LeadResource::class,
            'Leads retrieved successfully.'
        );
    }

    /**
     * Store a newly created lead in storage.
     */
    public function store(StoreLeadRequest $request): JsonResponse
    {
        $lead = $this->leadService->createLead($request->validated());

        return ApiResponse::success(
            new LeadResource($lead),
            'Lead created successfully.',
            Response::HTTP_CREATED
        );
    }

    /**
     * Display the specified lead with follow-ups.
     */
    public function show(int|string $id): JsonResponse
    {
        $lead = $this->leadService->getLeadById($id);

        if (!$lead) {
            return ApiResponse::error('Lead not found.', Response::HTTP_NOT_FOUND);
        }

        return ApiResponse::success(
            new LeadResource($lead),
            'Lead retrieved successfully.'
        );
    }

    /**
     * Update the specified lead in storage.
     */
    public function update(UpdateLeadRequest $request, int|string $id): JsonResponse
    {
        $lead = $this->leadService->updateLead($id, $request->validated());

        if (!$lead) {
            return ApiResponse::error('Lead not found or update failed.', Response::HTTP_NOT_FOUND);
        }

        return ApiResponse::success(
            new LeadResource($lead),
            'Lead updated successfully.'
        );
    }

    /**
     * Remove the specified lead from storage.
     */
    public function destroy(int|string $id): JsonResponse
    {
        $deleted = $this->leadService->deleteLead($id);

        if (!$deleted) {
            return ApiResponse::error('Lead not found or deletion failed.', Response::HTTP_NOT_FOUND);
        }

        return ApiResponse::success(
            null,
            'Lead deleted successfully.'
        );
    }

    /**
     * Update the lead status.
     */
    public function updateStatus(UpdateLeadStatusRequest $request, int|string $id): JsonResponse
    {
        $lead = $this->leadService->updateLeadStatus($id, (int) $request->validated()['status']);

        if (!$lead) {
            return ApiResponse::error('Lead not found.', Response::HTTP_NOT_FOUND);
        }

        return ApiResponse::success(
            new LeadResource($lead),
            'Lead status updated successfully.'
        );
    }

    /**
     * Assign lead to an agent/user.
     */
    public function assign(AssignLeadRequest $request, int|string $id): JsonResponse
    {
        $assignedTo = $request->validated()['assigned_to'] ?? null;
        $lead = $this->leadService->assignLead($id, $assignedTo !== null ? (int) $assignedTo : null);

        if (!$lead) {
            return ApiResponse::error('Lead not found.', Response::HTTP_NOT_FOUND);
        }

        return ApiResponse::success(
            new LeadResource($lead),
            'Lead assigned successfully.'
        );
    }

    /**
     * Get follow-up records for a specific lead.
     */
    public function getFollowUps(int|string $id): JsonResponse
    {
        $lead = $this->leadService->getLeadById($id);

        if (!$lead) {
            return ApiResponse::error('Lead not found.', Response::HTTP_NOT_FOUND);
        }

        $followUps = $this->leadService->getFollowUps($id);

        return ApiResponse::success(
            LeadFollowUpResource::collection($followUps),
            'Lead follow-ups retrieved successfully.'
        );
    }

    /**
     * Add a follow-up for a lead.
     */
    public function addFollowUp(StoreLeadFollowUpRequest $request, int|string $id): JsonResponse
    {
        $followUp = $this->leadService->addFollowUp($id, $request->validated());

        if (!$followUp) {
            return ApiResponse::error('Lead not found or follow-up could not be added.', Response::HTTP_NOT_FOUND);
        }

        return ApiResponse::success(
            new LeadFollowUpResource($followUp),
            'Follow-up added successfully.',
            Response::HTTP_CREATED
        );
    }

    /**
     * Update status of a follow-up record.
     */
    public function updateFollowUpStatus(UpdateLeadFollowUpStatusRequest $request, int|string $id, int|string $followUpId): JsonResponse
    {
        $validated = $request->validated();
        $followUp = $this->leadService->updateFollowUpStatus(
            $followUpId,
            (int) $validated['status'],
            $validated['completed_at'] ?? null
        );

        if (!$followUp) {
            return ApiResponse::error('Follow-up not found.', Response::HTTP_NOT_FOUND);
        }

        return ApiResponse::success(
            new LeadFollowUpResource($followUp),
            'Follow-up status updated successfully.'
        );
    }
}
