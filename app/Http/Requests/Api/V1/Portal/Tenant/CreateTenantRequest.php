<?php

namespace App\Http\Requests\Api\V1\Portal\Tenant;

use App\Helpers\FormRequestApi;

class CreateTenantRequest extends FormRequestApi
{
    public function authorize()
    {
        return true;
    }

    public function prepareForValidation()
    {
        $this->merge([]);
    }

    public function rules()
    {
        return [];
    }
}
