<?php

namespace App\Http\Requests\Api\V1\Portal\Tenant;

use App\Helpers\FormRequestApi;
use App\Rules\ExistsUuid;
use App\Rules\UniqueData;
use App\Models\System\File;

class EditTenantInformationRequest extends FormRequestApi
{
    public function authorize()
    {
        return true;
    }

    public function prepareForValidation()
    {
        $this->merge([
            'tenant_id' => $this->tenant_uuid
        ]);
    }

    public function rules()
    {
        return [
            'tenant_uuid' => ['required', 'uuid', new ExistsUuid(new File)],
            'logo_uuid' => ['nullable', 'uuid', new ExistsUuid(new File)],
            'favicon_uuid' => ['nullable', 'uuid', new ExistsUuid(new File)],
            'name' => ['required', 'string', 'max:255', new UniqueData('tnt_tenants', 'name')],
            'slug' => ['nullable', 'string', 'max:255', new UniqueData('tnt_tenants', 'slug')],
            'settings' => ['nullable', 'json'],
            'description' => ['nullable', 'string']
        ];
    }
}
