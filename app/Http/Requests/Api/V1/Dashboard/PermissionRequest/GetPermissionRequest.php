<?php

namespace App\Http\Requests\Api\V1\Dashboard\PermissionRequest;

use App\Helpers\FormRequestApi;
use App\Models\Auth\Permission;
use App\Rules\ExistsUuid;
use App\Traits\Identifier;

class GetPermissionRequest extends FormRequestApi
{
    use Identifier;

    public function authorize()
    {
        return true;
    }

    public function prepareForValidation()
    {
        $this->merge([
            'permission_uuid' => $this->permission_uuid
        ]);
    }

    public function rules()
    {
        return [
            "permission_uuid" => ['nullable', 'uuid', new ExistsUuid(new Permission())] 
        ];
    }
}
