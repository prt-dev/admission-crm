<?php

namespace App\Repositories\Eloquent;

use App\Models\Lead;
use App\Models\LeadFollowUp;
use App\Repositories\Contracts\LeadRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class LeadRepository implements LeadRepositoryInterface
{
    /**
     * @param Lead $model
     * @param LeadFollowUp $followUpModel
     */
    public function __construct(
        protected Lead $model,
        protected LeadFollowUp $followUpModel
    ) {}

    /**
     * Get all leads.
     */
    public function all(array $columns = ['*']): Collection
    {
        return $this->model->all($columns);
    }

    /**
     * Get paginated leads with filters using Eloquent ORM.
     */
    public function paginate(int $perPage = 15, array $columns = ['*'], array $filters = []): LengthAwarePaginator
    {
        return $this->model
            ->select($columns)
            ->with(['assignedUser', 'creator'])
            ->when(!empty($filters['search']), function ($query) use ($filters) {
                $query->search($filters['search']);
            })
            ->when(isset($filters['status']) && $filters['status'] !== '', function ($query) use ($filters) {
                $query->byStatus((int) $filters['status']);
            })
            ->when(isset($filters['assigned_to']) && $filters['assigned_to'] !== '', function ($query) use ($filters) {
                $query->assignedTo((int) $filters['assigned_to']);
            })
            ->when(!empty($filters['source']), function ($query) use ($filters) {
                $query->bySource($filters['source']);
            })
            ->orderBy(
                $filters['sort_by'] ?? 'created_at',
                strtolower($filters['sort_order'] ?? 'desc') === 'asc' ? 'asc' : 'desc'
            )
            ->paginate($perPage);
    }

    /**
     * Find a lead by primary ID.
     */
    public function findById(int|string $id, array $with = ['assignedUser', 'creator', 'followUps.user']): ?Lead
    {
        return $this->model->with($with)->find($id);
    }

    /**
     * Create a new lead using Eloquent create().
     */
    public function create(array $data): Lead
    {
        return $this->model->create($data);
    }

    /**
     * Update a lead using Eloquent save().
     */
    public function update(int|string $id, array $data): ?Lead
    {
        $lead = $this->findById($id, []);

        if (!$lead) {
            return null;
        }

        $lead->fill($data);
        $lead->save();

        return $lead->fresh(['assignedUser', 'creator']);
    }

    /**
     * Delete a lead using Eloquent delete().
     */
    public function delete(int|string $id): bool
    {
        $lead = $this->findById($id, []);

        if (!$lead) {
            return false;
        }

        // Also delete associated follow-ups via Eloquent
        $this->followUpModel->where('lead_id', $lead->id)->delete();

        return (bool) $lead->delete();
    }

    /**
     * Update lead status.
     */
    public function updateStatus(int|string $id, int $status): ?Lead
    {
        $lead = $this->findById($id, []);

        if (!$lead) {
            return null;
        }

        $lead->status = $status;
        if ($status === 4 && empty($lead->converted_at)) {
            $lead->converted_at = now();
        }
        $lead->save();

        return $lead;
    }

    /**
     * Assign lead to a user.
     */
    public function assignLead(int|string $id, ?int $userId): ?Lead
    {
        $lead = $this->findById($id, []);

        if (!$lead) {
            return null;
        }

        $lead->assigned_to = $userId;
        $lead->save();

        return $lead->fresh(['assignedUser', 'creator']);
    }

    /**
     * Add a follow-up for a lead.
     */
    public function addFollowUp(int|string $leadId, array $data): ?LeadFollowUp
    {
        $lead = $this->findById($leadId, []);

        if (!$lead) {
            return null;
        }

        $data['lead_id'] = $lead->id;
        $followUp = $this->followUpModel->create($data);

        // Optionally update next follow-up date on lead
        if (!empty($data['scheduled_at'])) {
            $lead->next_follow_up_at = $data['scheduled_at'];
            $lead->save();
        }

        return $followUp->fresh(['user', 'lead']);
    }

    /**
     * Get all follow-ups for a lead.
     */
    public function getFollowUps(int|string $leadId): Collection
    {
        return $this->followUpModel
            ->with(['user'])
            ->where('lead_id', $leadId)
            ->orderBy('created_at', 'desc')
            ->get();
    }

    /**
     * Update follow-up status.
     */
    public function updateFollowUpStatus(int|string $followUpId, int $status, ?string $completedAt = null): ?LeadFollowUp
    {
        $followUp = $this->followUpModel->find($followUpId);

        if (!$followUp) {
            return null;
        }

        $followUp->status = $status;
        if ($status === 2) {
            $followUp->completed_at = $completedAt ? $completedAt : now();
        }
        $followUp->save();

        return $followUp->fresh(['user']);
    }
}
