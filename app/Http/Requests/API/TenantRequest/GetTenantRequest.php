<?php

namespace App\Http\Requests\API\TenantRequest;

use App\Helpers\FormRequestApi;
use App\Models\Tenant\Tenant;
use App\Rules\ExistsUuid;
use App\Traits\Identifier;

class GetTenantRequest extends FormRequestApi
{
    use Identifier;

    public function authorize()
    {
        return true;
    }

    public function prepareForValidation()
    {
        $this->merge([
            'tenant_uuid' => $this->tenant_uuid
        ]);
    }

    public function rules()
    {
        return [
            "tenant_uuid" => ['nullable', 'uuid', new ExistsUuid(new Tenant())] 
        ];
    }
}
