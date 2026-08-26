<?php

namespace App\Http\Requests\Api\V1\Portal\Auth;

use App\Helpers\FormRequestApi;
use App\Models\Auth\Role;
use App\Traits\Identifier;
use Illuminate\Http\Exceptions\HttpResponseException;

class RegisterUserRequest extends FormRequestApi
{
    use Identifier;

    public function authorize(): bool
    {
        return true;
    }

    public function prepareForValidation()
    {
        $role_id = $this->findIdByUuid(Role::query(), $this->role_uuid);

        if (in_array($role_id, [2, 3])) {
            $role_uuid = $this->role_uuid;
        } else {
            throw new HttpResponseException(response()->json([
                'success' => false,
                'message' => 'Anda tidak memiliki hak akses untuk mendaftar sebagai role ini',
                'data' => null
            ], 403));
        }

        $this->merge([
            'role_uuid' => $role_uuid,
        ]);
    }

    public function rules(): array
    {
        return [
            'full_name' => ['required', 'string', 'max:255'],
            'phone_number' => ['required', 'string', 'max:20'],
            'email' => ['required', 'email', 'unique:auth_users,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'role_uuid' => ['required', 'uuid', 'exists:auth_roles,uuid'],
        ];
    }
}
