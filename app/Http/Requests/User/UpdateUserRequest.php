<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class UpdateUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $userId = $this->route('user') ?? $this->route('id');
        if (is_object($userId)) {
            $userId = $userId->id;
        }

        return [
            'name' => ['nullable', 'string', 'max:255'],
            'username' => [
                'sometimes',
                'required',
                'string',
                'max:100',
                'alpha_dash',
                Rule::unique('users', 'username')->ignore($userId),
            ],
            'email' => [
                'nullable',
                'string',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($userId),
            ],
            'phone' => ['nullable', 'string', 'max:30'],
            'password' => ['nullable', 'string', Password::default()],
            'role_id' => ['nullable', 'integer'],
            'status' => ['nullable', 'integer'],
            'last_login_at' => ['nullable', 'date'],
        ];
    }
}
