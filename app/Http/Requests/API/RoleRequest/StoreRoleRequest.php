<?php

namespace App\Http\Requests\API\RoleRequest;

use App\Helpers\FormRequestApi;
use App\Traits\Identifier;

class StoreRoleRequest extends FormRequestApi
{
    use Identifier;

    /**
     * Determine if the area is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => ['required'],
            'code' => ['required'],
            'description' => ['nullable'],
        ];
    }
}
