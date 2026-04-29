<?php

namespace App\Http\Requests\API\TenantCategoryRequest;

use App\Helpers\FormRequestApi;
use App\Models\Tenant\TenantCategory;
use App\Rules\ExistsUuid;
use App\Traits\Identifier;

class GetTenantCategoryRequest extends FormRequestApi
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
            'tenant_category_uuid' => $this->tenant_category_uuid
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
            "tenant_category_uuid" => ['nullable', 'uuid', new ExistsUuid(new TenantCategory())] 
        ];
    }
}
