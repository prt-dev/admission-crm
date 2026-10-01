<?php

namespace App\Http\Requests\Batch;

use Illuminate\Foundation\Http\FormRequest;

class StoreBatchRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:150'],
            'code' => ['required', 'string', 'max:50', 'unique:batches,code'],
            'course_ids' => ['nullable', 'array'],
            'course_ids.*' => ['integer'],
            'start_date' => ['nullable', 'date'],
            'end_date' => ['nullable', 'date', 'after_or_equal:start_date'],
            'timing' => ['nullable', 'string', 'max:100'],
            'capacity' => ['nullable', 'integer', 'min:1'],
            'status' => ['nullable', 'integer', 'in:1,2,3,4'],
            'instructor_id' => ['nullable', 'integer'],
            'created_by' => ['nullable', 'integer'],
        ];
    }
}
