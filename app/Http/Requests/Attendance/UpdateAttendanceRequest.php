<?php

namespace App\Http\Requests\Attendance;

use Illuminate\Foundation\Http\FormRequest;

class UpdateAttendanceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'admission_id' => ['sometimes', 'required', 'integer'],
            'batch_id' => ['sometimes', 'required', 'integer'],
            'course_id' => ['nullable', 'integer'],
            'date' => ['sometimes', 'required', 'date'],
            'duration' => ['sometimes', 'required', 'numeric', 'min:0.1', 'max:24'],
            'type' => ['sometimes', 'required', 'string', 'in:T,P,O,t,p,o'],
            'status' => ['sometimes', 'required', 'integer', 'in:1,2,3,4,5'],
            'topic_covered' => ['nullable', 'string', 'max:255'],
            'remarks' => ['nullable', 'string'],
            'marked_by' => ['nullable', 'integer'],
        ];
    }
}
