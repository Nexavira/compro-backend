<?php

namespace App\Http\Requests\API\RoleRequest;

use App\Helpers\FormRequestApi;
use App\Models\Auth\Role;
use App\Rules\ExistsUuid;
use App\Traits\Identifier;

class UpdateRoleRequest extends FormRequestApi
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
        $role_uuid = $this->role_uuid;
        return [
            'role_uuid' => ['required','uuid', new ExistsUuid(new Role())],
            'name' => ['required'],
            'code' => ['required']
        ];
    }
}
