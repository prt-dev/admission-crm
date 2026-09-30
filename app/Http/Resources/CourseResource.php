<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CourseResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'code' => $this->code,
            'description' => $this->description,
            'duration' => $this->duration,
            'fee' => (float) $this->fee,
            'status' => $this->status,
            'created_by' => $this->created_by,
            'creator' => $this->whenLoaded('creator', function () {
                return $this->creator ? [
                    'id' => $this->creator->id,
                    'name' => $this->creator->name,
                ] : null;
            }),
            'created_at' => $this->created_at?->toISOString(),
            'updated_at' => $this->updated_at?->toISOString(),
        ];
    }
}
