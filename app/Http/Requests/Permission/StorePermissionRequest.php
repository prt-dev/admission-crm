<?php

namespace App\Http\Requests\Permission;

use Illuminate\Foundation\Http\FormRequest;

class StorePermissionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:100'],
            'slug' => ['nullable', 'string', 'max:100', 'unique:permissions,slug'],
            'module' => ['required', 'string', 'max:50'],
            'description' => ['nullable', 'string'],
            'status' => ['nullable', 'integer', 'in:1,2'],
        ];
    }
}
