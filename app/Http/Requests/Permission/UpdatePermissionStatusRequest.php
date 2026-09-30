<?php

namespace App\Http\Requests\Permission;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePermissionStatusRequest extends FormRequest
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
