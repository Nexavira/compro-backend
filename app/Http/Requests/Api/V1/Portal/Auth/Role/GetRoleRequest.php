<?php

namespace App\Http\Requests\Api\V1\Portal\Auth\Role;

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
            'role_uuid' => $this->role_uuid,
            'role_id_in' => [3, 4]
        ]);
    }

    public function rules()
    {
        return [
            "role_uuid" => ['nullable', 'bail', 'uuid', new ExistsUuid(new Role)]
        ];
    }
}
