<?php

namespace App\Http\Requests\Api\V1\Dashboard\TenantCategoryRequest;

use App\Helpers\FormRequestApi;
use App\Models\Tenant\TenantCategory;
use App\Rules\ExistsUuid;
use App\Traits\Identifier;

class GetTenantCategoryRequest extends FormRequestApi
{
    use Identifier;

    public function authorize()
    {
        return true;
    }

    public function prepareForValidation()
    {
        $this->merge([
            'tenant_category_uuid' => $this->tenant_category_uuid
        ]);
    }

    public function rules()
    {
        return [
            "tenant_category_uuid" => ['nullable', 'uuid', new ExistsUuid(new TenantCategory())] 
        ];
    }
}
