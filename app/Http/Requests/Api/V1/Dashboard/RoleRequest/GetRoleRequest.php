<?php

namespace App\Http\Requests\Api\V1\Dashboard\RoleRequest;

use App\Helpers\FormRequestApi;
use App\Models\Auth\Role;
use App\Rules\ExistsUuid;
use App\Traits\Identifier;

class GetRoleRequest extends FormRequestApi
{
    use Identifier;

    public function authorize()
    {
        return true;
    }

    public function prepareForValidation()
    {
        $this->merge([
            'role_uuid' => $this->role_uuid
        ]);
    }

    public function rules()
    {
        return [
            "role_uuid" => ['nullable', 'uuid', new ExistsUuid(new Role())] 
        ];
    }
}
