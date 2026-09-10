<?php

namespace App\Http\Requests\Api\V1\Portal\Tenant;

use App\Helpers\FormRequestApi;
use App\Models\Tenant\TenantCategory;
use App\Rules\ExistsUuid;
use App\Rules\UniqueData;
use App\Models\System\File;
use App\Models\Master\Package;
use App\Models\CMS\GlobalTemplate;

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
        return [
            'package_uuid' => ['required', 'uuid', new ExistsUuid(new Package())],
            'global_template_uuid' => ['nullable', 'uuid', new ExistsUuid(new GlobalTemplate)],
            'tenant_category_uuid' => ['required', 'uuid', new ExistsUuid(new TenantCategory())],
            'logo_uuid' => ['nullable', 'uuid', new ExistsUuid(new File)],
            'favicon_uuid' => ['nullable', 'uuid', new ExistsUuid(new File)],
            'name' => ['required', 'string', 'max:255', new UniqueData('tnt_tenants', 'name')],
            'slug' => ['nullable', 'string', 'max:255', new UniqueData('tnt_tenants', 'slug')],
            'custom_domain' => ['nullable', 'string', 'max:255'],
            'settings' => ['nullable', 'json'],
            'description' => ['nullable', 'string'],
            'is_suspended' => ['required', 'integer', 'in:0,1'],
            'role_detail' => ['nullable', 'string', 'max:255'],
            'is_trial' => ['required', 'boolean'],
        ];
    }
}
