<?php

namespace App\Services;

use App\Models\Lead;
use App\Models\LeadFollowUp;
use App\Repositories\Contracts\LeadRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class LeadService
{
    /**
     * @param LeadRepositoryInterface $leadRepository
     */
    public function __construct(
        protected LeadRepositoryInterface $leadRepository
    ) {}

    /**
     * Get paginated leads with filters.
     */
    public function getPaginatedLeads(int $perPage = 15, array $columns = ['*'], array $filters = []): LengthAwarePaginator
    {
        return $this->leadRepository->paginate($perPage, $columns, $filters);
    }

    /**
     * Get all leads.
     */
    public function getAllLeads(array $columns = ['*']): Collection
    {
        return $this->leadRepository->all($columns);
    }

    /**
     * Get lead by ID.
     */
    public function getLeadById(int|string $id): ?Lead
    {
        return $this->leadRepository->findById($id);
    }

    /**
     * Create a new lead.
     */
    public function createLead(array $data): Lead
    {
        return $this->leadRepository->create($data);
    }

    /**
     * Update a lead.
     */
    public function updateLead(int|string $id, array $data): ?Lead
    {
        return $this->leadRepository->update($id, $data);
    }

    /**
     * Delete a lead.
     */
    public function deleteLead(int|string $id): bool
    {
        return $this->leadRepository->delete($id);
    }

    /**
     * Update lead status.
     */
    public function updateLeadStatus(int|string $id, int $status): ?Lead
    {
        return $this->leadRepository->updateStatus($id, $status);
    }

    /**
     * Assign lead to an agent/user.
     */
    public function assignLead(int|string $id, ?int $userId): ?Lead
    {
        return $this->leadRepository->assignLead($id, $userId);
    }

    /**
     * Add follow-up for a lead.
     */
    public function addFollowUp(int|string $leadId, array $data): ?LeadFollowUp
    {
        return $this->leadRepository->addFollowUp($leadId, $data);
    }

    /**
     * Get all follow-ups for a lead.
     */
    public function getFollowUps(int|string $leadId): Collection
    {
        return $this->leadRepository->getFollowUps($leadId);
    }

    /**
     * Update follow-up status.
     */
    public function updateFollowUpStatus(int|string $followUpId, int $status, ?string $completedAt = null): ?LeadFollowUp
    {
        return $this->leadRepository->updateFollowUpStatus($followUpId, $status, $completedAt);
    }
}
