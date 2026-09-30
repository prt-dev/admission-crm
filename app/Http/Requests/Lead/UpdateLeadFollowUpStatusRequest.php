<?php

namespace App\Http\Requests\Lead;

use Illuminate\Foundation\Http\FormRequest;

class UpdateLeadFollowUpStatusRequest extends FormRequest
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
            'status' => ['required', 'integer', 'in:1,2,3'], // 1=Pending, 2=Completed, 3=Cancelled
            'completed_at' => ['nullable', 'date'],
        ];
    }
}
