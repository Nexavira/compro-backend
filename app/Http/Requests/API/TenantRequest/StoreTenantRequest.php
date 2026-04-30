<?php

namespace App\Http\Requests\API\TenantRequest;

use App\Helpers\FormRequestApi;
use App\Models\Tenant\Tenant;
use App\Rules\ExistsUuid;
use App\Traits\Identifier;

class StoreTenantRequest extends FormRequestApi
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
        // $this->merge([
        //     'tenant_uuid' => $this->tenant_uuid
        // ]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            // "tenant_uuid" => ['nullable', 'uuid', new ExistsUuid(new Tenant())] 
        ];
    }
}
