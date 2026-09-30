<?php

namespace App\Http\Requests\Course;

use Illuminate\Foundation\Http\FormRequest;

class StoreCourseRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:150'],
            'code' => ['required', 'string', 'max:50', 'unique:courses,code'],
            'description' => ['nullable', 'string'],
            'duration' => ['nullable', 'string', 'max:50'],
            'fee' => ['nullable', 'numeric', 'min:0'],
            'status' => ['nullable', 'integer', 'in:1,2'],
            'created_by' => ['nullable', 'integer'],
        ];
    }
}
