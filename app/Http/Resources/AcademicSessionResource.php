<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AcademicSessionResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'code' => $this->code,
            'start_date' => $this->start_date?->format('Y-m-d'),
            'end_date' => $this->end_date?->format('Y-m-d'),
            'formatted_date_range' => $this->formatted_date_range,
            'is_current' => (bool) $this->is_current,
            'status' => $this->status,
            'status_label' => $this->status_label,
            'description' => $this->description,
            'batches_count' => $this->batches_count ?? ($this->relationLoaded('batches') ? $this->batches->count() : 0),
            'admissions_count' => $this->admissions_count ?? ($this->relationLoaded('admissions') ? $this->admissions->count() : 0),
            'batches' => $this->whenLoaded('batches', function () {
                return $this->batches->map(function ($batch) {
                    return [
                        'id' => $batch->id,
                        'name' => $batch->name,
                        'code' => $batch->code,
                        'timing' => $batch->timing,
                        'capacity' => $batch->capacity,
                        'status' => $batch->status,
                    ];
                });
            }),
            'creator' => $this->whenLoaded('creator', function () {
                return $this->creator ? [
                    'id' => $this->creator->id,
                    'name' => $this->creator->name,
                    'email' => $this->creator->email,
                ] : null;
            }),
            'created_at' => $this->created_at?->toISOString(),
            'updated_at' => $this->updated_at?->toISOString(),
        ];
    }
}
