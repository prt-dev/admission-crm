<?php

namespace App\Http\Requests\Role;

use Illuminate\Foundation\Http\FormRequest;

class StoreRoleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:100'],
            'slug' => ['nullable', 'string', 'max:100', 'unique:roles,slug'],
            'description' => ['nullable', 'string'],
            'permissions' => ['nullable', 'array'],
            'status' => ['nullable', 'integer', 'in:1,2'],
            'is_system' => ['nullable', 'boolean'],
        ];
    }
}
