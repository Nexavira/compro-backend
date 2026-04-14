<?php

namespace App\Http\Requests\API\RoleRequest;

use App\Helpers\FormRequestApi;
use App\Models\Auth\Role;
use App\Rules\ExistsUuid;
use App\Traits\Identifier;

class GetRoleRequest extends FormRequestApi
{
    use Identifier;

    /**
     * Determine if the role is authorized to make this request.
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
            'role_uuid' => $this->role_uuid
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
            "role_uuid" => ['nullable', 'uuid', new ExistsUuid(new Role())] 
        ];
    }
}
