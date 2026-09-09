<?php

namespace App\Http\Requests\Api\V1\Portal\Cms\GlobalTemplate;

use App\Helpers\FormRequestApi;
use App\Models\Master\Package;
use App\Models\Tenant\TenantCategory;
use App\Rules\ExistsId;
use App\Rules\ExistsUuid;
use App\Rules\UniqueData;

class StoreGlobalTemplateRequest extends FormRequestApi
{
    public function authorize(): bool
    {
        return true;
    }

    public function prepareForValidation()
    {

    }

    public function rules()
    {
        return [
            'tenant_category_id' => ['nullable', 'integer', new ExistsId(new TenantCategory)],
            'tenant_category_uuid' => ['nullable', 'uuid', new ExistsUuid(new TenantCategory)],
            'package_id' => ['nullable', 'integer', new ExistsId(new Package)],
            'package_uuid' => ['nullable', 'uuid', new ExistsUuid(new Package)],
            'tier' => ['required', 'string'],
            'title' => ['required', 'string', new UniqueData('cms_global_templates', 'title' ?? null)],
            'description' => ['nullable'],
            'brand_settings' => ['nullable'],
        ];
    }
}
