<?php

namespace App\Http\Requests\API\PermissionRequest;

use App\Helpers\FormRequestApi;
use App\Models\Auth\Permission;
use App\Rules\ExistsUuid;
use App\Traits\Identifier;

class GetPermissionRequest extends FormRequestApi
{
    use Identifier;

    /**
     * Determine if the permission is authorized to make this request.
     *
     * @return bool
     */
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

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            "permission_uuid" => ['nullable', 'uuid', new ExistsUuid(new Permission())] 
        ];
    }
}
