<?php

namespace App\Services\Tenant;

use App\Models\System\File;
use App\Models\Tenant\Tenant;
use App\Models\Tenant\TenantCategory;
use App\Rules\ExistsId;
use App\Rules\ExistsUuid;
use App\Rules\UniqueData;
use App\Services\DefaultService;
use App\Services\ServiceInterface;
use Illuminate\Support\Str;

class EditTenantInformationService extends DefaultService implements ServiceInterface
{
    public function process($dto)
    {
        $dto = $this->prepare($dto);
        $tenant = Tenant::find($dto['tenant_id']);

        $tenant->name = $dto['name'] ?? $tenant->name;
        $tenant->slug = $dto['slug'] ?? $tenant->slug;
        $tenant->logo_id = $dto['logo_id'] ?? $tenant->logo_id;
        $tenant->favicon_id = $dto['favicon_id'] ?? $tenant->favicon_id;
        $tenant->settings = $dto['settings'] ?? $tenant->settings;
        $tenant->description = $dto['description'] ?? $tenant->description;

        $this->prepareAuditUpdate($tenant);
        $tenant->save();

        $this->results['data'] = $tenant;
        $this->results['message'] = "Tenant successfully updated";
    }

    public function prepare($dto)
    {
        $dto['tenant_id'] = $this->findIdByUuid(Tenant::query(), $dto['tenant_uuid']);
        
        if (isset($dto['logo_uuid']) and $dto['logo_uuid'] != '') {
            $dto['logo_id'] = $this->findIdByUuid(File::query(), $dto['logo_uuid']);
        }
        if (isset($dto['favicon_uuid']) and $dto['favicon_uuid'] != '') {
            $dto['favicon_id'] = $this->findIdByUuid(File::query(), $dto['favicon_uuid']);
        }
        if (isset($dto['tenant_category_uuid']) and $dto['tenant_category_uuid'] != '') {
            $dto['tenant_category_id'] = $this->findIdByUuid(TenantCategory::query(), $dto['tenant_category_uuid']);
        }

        return $dto;
    }

    public function rules($dto)
    {
        return [
            'logo_id' => ['nullable', 'integer', new ExistsId(new File)],
            'logo_uuid' => ['nullable', 'uuid', new ExistsUuid(new File)],
            'favicon_id' => ['nullable', 'integer', new ExistsId(new File)],
            'favicon_uuid' => ['nullable', 'uuid', new ExistsUuid(new File)],

            'name' => ['required', 'string', 'max:255', new UniqueData('tnt_tenants', 'name')],
            'slug' => ['nullable', 'string', 'max:255', new UniqueData('tnt_tenants', 'slug')],
            'settings' => ['nullable', 'json'],
            'description' => ['nullable', 'string'],
        ];
    }
}
