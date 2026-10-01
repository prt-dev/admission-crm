<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AttendanceResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'admission_id' => $this->admission_id,
            'batch_id' => $this->batch_id,
            'course_id' => $this->course_id,
            
            // Core attendance keys
            'date' => $this->date?->format('Y-m-d') ?? $this->date,
            'duration' => (float) $this->duration,
            'type' => $this->type,
            'type_label' => $this->type_label,
            'status' => $this->status,
            'status_label' => $this->status_label,
            
            // Additional details
            'topic_covered' => $this->topic_covered,
            'remarks' => $this->remarks,
            'marked_by' => $this->marked_by,

            // Relational payloads when loaded
            'admission' => $this->whenLoaded('admission', function () {
                return $this->admission ? [
                    'id' => $this->admission->id,
                    'admission_number' => $this->admission->admission_number,
                    'full_name' => $this->admission->full_name,
                    'email' => $this->admission->email,
                    'phone' => $this->admission->phone,
                ] : null;
            }),
            'batch' => $this->whenLoaded('batch', function () {
                return $this->batch ? [
                    'id' => $this->batch->id,
                    'name' => $this->batch->name,
                    'code' => $this->batch->code,
                    'timing' => $this->batch->timing,
                ] : null;
            }),
            'course' => $this->whenLoaded('course', function () {
                return $this->course ? [
                    'id' => $this->course->id,
                    'name' => $this->course->name,
                    'code' => $this->course->code,
                ] : null;
            }),
            'marker' => $this->whenLoaded('marker', function () {
                return $this->marker ? [
                    'id' => $this->marker->id,
                    'name' => $this->marker->name,
                    'email' => $this->marker->email,
                ] : null;
            }),

            'created_at' => $this->created_at?->toISOString(),
            'updated_at' => $this->updated_at?->toISOString(),
        ];
    }
}
