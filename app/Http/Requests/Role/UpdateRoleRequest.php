<?php

namespace App\Http\Requests\Role;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRoleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $id = $this->route('id');

        return [
            'name' => ['sometimes', 'required', 'string', 'max:100'],
            'slug' => ['sometimes', 'required', 'string', 'max:100', 'unique:roles,slug,' . $id],
            'description' => ['nullable', 'string'],
            'permissions' => ['nullable', 'array'],
            'status' => ['nullable', 'integer', 'in:1,2'],
        ];
    }
}
