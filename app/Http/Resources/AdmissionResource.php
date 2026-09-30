<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AdmissionResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'admission_number' => $this->admission_number,
            'registration_number' => $this->registration_number,
            
            // Student details
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'full_name' => $this->full_name,
            'email' => $this->email,
            'phone' => $this->phone,
            'alternate_phone' => $this->alternate_phone,
            'dob' => $this->dob?->format('Y-m-d'),
            'gender' => $this->gender,
            'guardian_name' => $this->guardian_name,
            'guardian_phone' => $this->guardian_phone,
            'address' => $this->address,
            'city' => $this->city,
            'state' => $this->state,
            'pincode' => $this->pincode,
            'qualification' => $this->qualification,
            
            // Relationships
            'user_id' => $this->user_id,
            'user' => $this->whenLoaded('user', function () {
                return $this->user ? [
                    'id' => $this->user->id,
                    'name' => $this->user->name,
                    'email' => $this->user->email,
                ] : null;
            }),
            'lead_id' => $this->lead_id,
            'lead' => $this->whenLoaded('lead', function () {
                return $this->lead ? [
                    'id' => $this->lead->id,
                    'full_name' => $this->lead->full_name,
                    'phone' => $this->lead->phone,
                ] : null;
            }),
            'course_id' => $this->course_id,
            'course' => $this->whenLoaded('course', function () {
                return $this->course ? [
                    'id' => $this->course->id,
                    'name' => $this->course->name,
                    'code' => $this->course->code,
                ] : null;
            }),
            'batch_id' => $this->batch_id,
            'batch' => $this->whenLoaded('batch', function () {
                return $this->batch ? [
                    'id' => $this->batch->id,
                    'name' => $this->batch->name,
                    'code' => $this->batch->code,
                    'timing' => $this->batch->timing,
                ] : null;
            }),
            
            // Financial & Status
            'admission_date' => $this->admission_date?->format('Y-m-d'),
            'course_fee' => (float) $this->course_fee,
            'discount_amount' => (float) $this->discount_amount,
            'final_fee' => (float) $this->final_fee,
            'paid_amount' => (float) $this->paid_amount,
            'due_amount' => (float) $this->due_amount,
            'payment_status' => $this->payment_status,
            'status' => $this->status,
            'remarks' => $this->remarks,
            'admitted_by' => $this->admitted_by,
            'counselor' => $this->whenLoaded('counselor', function () {
                return $this->counselor ? [
                    'id' => $this->counselor->id,
                    'name' => $this->counselor->name,
                ] : null;
            }),
            'created_at' => $this->created_at?->toISOString(),
            'updated_at' => $this->updated_at?->toISOString(),
        ];
    }
}
