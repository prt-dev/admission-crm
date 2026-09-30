<?php

namespace App\Repositories\Contracts;

use App\Models\Lead;
use App\Models\LeadFollowUp;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

interface LeadRepositoryInterface
{
    /**
     * Get all leads.
     *
     * @param array<int, string> $columns
     * @return Collection<int, Lead>
     */
    public function all(array $columns = ['*']): Collection;

    /**
     * Get paginated leads with filters.
     *
     * @param int $perPage
     * @param array<int, string> $columns
     * @param array<string, mixed> $filters
     * @return LengthAwarePaginator
     */
    public function paginate(int $perPage = 15, array $columns = ['*'], array $filters = []): LengthAwarePaginator;

    /**
     * Find a lead by ID.
     *
     * @param int|string $id
     * @param array<string> $with
     * @return Lead|null
     */
    public function findById(int|string $id, array $with = ['assignedUser', 'creator', 'followUps.user']): ?Lead;

    /**
     * Create a new lead.
     *
     * @param array<string, mixed> $data
     * @return Lead
     */
    public function create(array $data): Lead;

    /**
     * Update a lead.
     *
     * @param int|string $id
     * @param array<string, mixed> $data
     * @return Lead|null
     */
    public function update(int|string $id, array $data): ?Lead;

    /**
     * Delete a lead.
     *
     * @param int|string $id
     * @return bool
     */
    public function delete(int|string $id): bool;

    /**
     * Update lead status.
     *
     * @param int|string $id
     * @param int $status
     * @return Lead|null
     */
    public function updateStatus(int|string $id, int $status): ?Lead;

    /**
     * Assign lead to a user.
     *
     * @param int|string $id
     * @param int|null $userId
     * @return Lead|null
     */
    public function assignLead(int|string $id, ?int $userId): ?Lead;

    /**
     * Add a follow-up for a lead.
     *
     * @param int|string $leadId
     * @param array<string, mixed> $data
     * @return LeadFollowUp|null
     */
    public function addFollowUp(int|string $leadId, array $data): ?LeadFollowUp;

    /**
     * Get follow-ups for a lead.
     *
     * @param int|string $leadId
     * @return Collection<int, LeadFollowUp>
     */
    public function getFollowUps(int|string $leadId): Collection;

    /**
     * Update follow-up status.
     *
     * @param int|string $followUpId
     * @param int $status
     * @param string|null $completedAt
     * @return LeadFollowUp|null
     */
    public function updateFollowUpStatus(int|string $followUpId, int $status, ?string $completedAt = null): ?LeadFollowUp;
}
