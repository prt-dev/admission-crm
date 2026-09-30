<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LeadFollowUpResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'lead_id' => $this->lead_id,
            'user_id' => $this->user_id,
            'user' => $this->whenLoaded('user', function () {
                return $this->user ? [
                    'id' => $this->user->id,
                    'name' => $this->user->name,
                    'email' => $this->user->email,
                ] : null;
            }),
            'type' => $this->type,
            'remarks' => $this->remarks,
            'scheduled_at' => $this->scheduled_at?->toISOString() ?? $this->scheduled_at,
            'completed_at' => $this->completed_at?->toISOString() ?? $this->completed_at,
            'status' => $this->status,
            'created_at' => $this->created_at?->toISOString(),
            'updated_at' => $this->updated_at?->toISOString(),
        ];
    }
}
