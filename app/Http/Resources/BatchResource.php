<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BatchResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'code' => $this->code,
            'course_id' => $this->course_id,
            'course' => $this->whenLoaded('course', function () {
                return $this->course ? [
                    'id' => $this->course->id,
                    'name' => $this->course->name,
                    'code' => $this->course->code,
                ] : null;
            }),
            'start_date' => $this->start_date?->format('Y-m-d'),
            'end_date' => $this->end_date?->format('Y-m-d'),
            'timing' => $this->timing,
            'capacity' => $this->capacity,
            'status' => $this->status,
            'instructor_id' => $this->instructor_id,
            'instructor' => $this->whenLoaded('instructor', function () {
                return $this->instructor ? [
                    'id' => $this->instructor->id,
                    'name' => $this->instructor->name,
                    'email' => $this->instructor->email,
                ] : null;
            }),
            'created_by' => $this->created_by,
            'created_at' => $this->created_at?->toISOString(),
            'updated_at' => $this->updated_at?->toISOString(),
        ];
    }
}
