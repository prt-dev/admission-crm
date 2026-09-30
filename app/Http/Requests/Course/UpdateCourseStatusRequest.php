<?php

namespace App\Http\Requests\Course;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCourseStatusRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'status' => ['required', 'integer', 'in:1,2'],
        ];
    }
}
