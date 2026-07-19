<?php

namespace App\Http\Requests\API\RoleRequest;

use App\Helpers\FormRequestApi;
use App\Traits\Identifier;

class StoreRoleRequest extends FormRequestApi
{
    use Identifier;

    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => ['required'],
            'code' => ['required'],
            'description' => ['nullable'],
        ];
    }
}
