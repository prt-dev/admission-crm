<?php

namespace App\Http\Requests\Course;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCourseRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $id = $this->route('id');

        return [
            'name' => ['sometimes', 'required', 'string', 'max:150'],
            'code' => ['sometimes', 'required', 'string', 'max:50', 'unique:courses,code,' . $id],
            'description' => ['nullable', 'string'],
            'duration' => ['nullable', 'string', 'max:50'],
            'fee' => ['nullable', 'numeric', 'min:0'],
            'status' => ['nullable', 'integer', 'in:1,2'],
        ];
    }
}
