<?php

namespace App\Http\Requests\Batch;

use Illuminate\Foundation\Http\FormRequest;

class UpdateBatchStatusRequest extends FormRequest
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
