<?php

namespace App\Http\Requests\Lead;

use Illuminate\Foundation\Http\FormRequest;

class StoreLeadFollowUpRequest extends FormRequest
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
            'user_id' => ['nullable', 'integer'],
            'type' => ['required', 'string', 'in:call,email,meeting,whatsapp,note'],
            'remarks' => ['required', 'string'],
            'scheduled_at' => ['nullable', 'date'],
            'status' => ['nullable', 'integer', 'in:1,2,3'],
        ];
    }
}
