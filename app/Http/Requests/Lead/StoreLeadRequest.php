<?php

namespace App\Http\Requests\Lead;

use Illuminate\Foundation\Http\FormRequest;

class StoreLeadRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'first_name' => ['required', 'string', 'max:100'],
            'last_name' => ['nullable', 'string', 'max:100'],
            'email' => ['nullable', 'email', 'max:255'],
            'phone' => ['required', 'string', 'max:30'],
            'alternate_phone' => ['nullable', 'string', 'max:30'],
            'city' => ['nullable', 'string', 'max:100'],
            'state' => ['nullable', 'string', 'max:100'],
            'source' => ['nullable', 'string', 'max:100'],
            'course_interested' => ['nullable', 'string', 'max:150'],
            'assigned_to' => ['nullable', 'integer'],
            'status' => ['nullable', 'integer', 'in:1,2,3,4,5,6'],
            'notes' => ['nullable', 'string'],
            'next_follow_up_at' => ['nullable', 'date'],
            'created_by' => ['nullable', 'integer'],
        ];
    }
}
