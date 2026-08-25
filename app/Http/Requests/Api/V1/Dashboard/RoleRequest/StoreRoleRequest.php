<?php

namespace App\Http\Requests\Api\V1\Dashboard\RoleRequest;

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
