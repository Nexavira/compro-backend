<?php

namespace App\Http\Requests\Api\V1\Portal\Auth;

use App\Helpers\FormRequestApi;

class RegisterUserRequest extends FormRequestApi
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            // 'full_name' => ['required', 'string', 'max:255'],
            // 'phone_number' => ['required', 'string', 'max:20'],
            // 'email' => ['required', 'email', 'unique:auth_users,email'],
            // 'password' => ['required', 'string', 'min:8', 'confirmed'],
        ];
    }
}
