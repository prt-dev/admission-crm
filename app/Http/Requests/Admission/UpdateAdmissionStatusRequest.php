<?php

namespace App\Http\Requests\Admission;

use Illuminate\Foundation\Http\FormRequest;

class UpdateAdmissionStatusRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'status' => ['required', 'integer', 'in:1,2,3,4'],
        ];
    }
}
