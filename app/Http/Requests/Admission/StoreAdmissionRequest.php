<?php

namespace App\Http\Requests\Admission;

use Illuminate\Foundation\Http\FormRequest;

class StoreAdmissionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'admission_number' => ['nullable', 'string', 'max:50', 'unique:admissions,admission_number'],
            'registration_number' => ['nullable', 'string', 'max:50', 'unique:admissions,registration_number'],
            'user_id' => ['nullable', 'integer'],
            'lead_id' => ['nullable', 'integer'],
            'course_id' => ['required', 'integer'],
            'batch_id' => ['nullable', 'integer'],
            
            // Student personal details
            'first_name' => ['required', 'string', 'max:100'],
            'last_name' => ['nullable', 'string', 'max:100'],
            'email' => ['required', 'email', 'max:255'],
            'phone' => ['required', 'string', 'max:30'],
            'alternate_phone' => ['nullable', 'string', 'max:30'],
            'dob' => ['nullable', 'date'],
            'gender' => ['nullable', 'string', 'max:20'],
            'guardian_name' => ['nullable', 'string', 'max:150'],
            'guardian_phone' => ['nullable', 'string', 'max:30'],
            'address' => ['nullable', 'string'],
            'city' => ['nullable', 'string', 'max:100'],
            'state' => ['nullable', 'string', 'max:100'],
            'pincode' => ['nullable', 'string', 'max:20'],
            'qualification' => ['nullable', 'string', 'max:150'],

            // Admission details
            'admission_date' => ['nullable', 'date'],
            'course_fee' => ['required', 'numeric', 'min:0'],
            'discount_amount' => ['nullable', 'numeric', 'min:0'],
            'paid_amount' => ['nullable', 'numeric', 'min:0'],
            'status' => ['nullable', 'integer', 'in:1,2,3,4'],
            'remarks' => ['nullable', 'string'],
            'admitted_by' => ['nullable', 'integer'],
        ];
    }
}
