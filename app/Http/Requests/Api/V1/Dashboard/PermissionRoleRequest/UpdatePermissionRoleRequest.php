<?php

namespace App\Http\Requests\Api\V1\Dashboard\PermissionRoleRequest;

use App\Helpers\FormRequestApi;
use App\Models\Auth\Permission;
use App\Models\Auth\Role;
use App\Rules\ExistsUuid;
use App\Traits\Identifier;

class UpdatePermissionRoleRequest extends FormRequestApi
{
    use Identifier;

    public function authorize()
    {
        return true;
    }

    public function prepareForValidation()
    {
        $this->merge([
            'permission_uuid' => $this->permission_uuid,
            'role_uuid' => $this->role_uuid
        ]);
    }

    public function rules()
    {
        return [
            'role_uuid' => ['required','uuid', new ExistsUuid(new Role())],
            'permission_uuid' => ['required','uuid', new ExistsUuid(new Permission())]
        ];
    }
}
