<?php

namespace App\Http\Requests\Admission;

use Illuminate\Foundation\Http\FormRequest;

class RecordAdmissionPaymentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'amount' => ['required', 'numeric', 'min:0.01'],
        ];
    }
}
