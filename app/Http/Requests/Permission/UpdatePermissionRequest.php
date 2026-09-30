<?php

namespace App\Http\Requests\Permission;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePermissionRequest extends FormRequest
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
            'slug' => ['sometimes', 'required', 'string', 'max:100', 'unique:permissions,slug,' . $id],
            'module' => ['sometimes', 'required', 'string', 'max:50'],
            'description' => ['nullable', 'string'],
            'status' => ['nullable', 'integer', 'in:1,2'],
        ];
    }
}
