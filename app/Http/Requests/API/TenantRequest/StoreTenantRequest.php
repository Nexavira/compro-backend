<?php

namespace App\Http\Requests\API\TenantRequest;

use App\Helpers\FormRequestApi;
use App\Models\Tenant\Tenant;
use App\Rules\ExistsUuid;
use App\Traits\Identifier;

class StoreTenantRequest extends FormRequestApi
{
    use Identifier;

    public function authorize()
    {
        return true;
    }

    public function prepareForValidation()
    {

    }

    public function rules()
    {
        return [

        ];
    }
}
