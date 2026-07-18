<?php

namespace App\Services\TenantService;

use App\Models\System\File;
use App\Models\Tenant\Tenant;
use App\Models\Tenant\TenantCategory;
use App\Rules\ExistsUuid;
use App\Rules\UniqueData;
use App\Services\DefaultService;
use App\Services\ServiceInterface;
use Illuminate\Support\Str;

class StoreTenantService extends DefaultService implements ServiceInterface
{

    public function process($dto)
    {
        $dto = $this->prepare($dto);
        $tenant = new Tenant();

        $tenant->name = $dto['name'];
        $tenant->slug = $dto['slug'] ?? Str::slug($dto['name']);
        $tenant->theme_code = $dto['theme_code'];
        $tenant->tenant_category_id = $dto['tenant_category_id'];

        $tenant->logo_id = $dto['logo_id'] ?? null;
        $tenant->favicon_id = $dto['favicon_id'] ?? null;
        $tenant->custom_domain = $dto['custom_domain'] ?? null;
        $tenant->settings = $dto['settings'] ?? null;
        $tenant->description = $dto['description'] ?? null;
        $tenant->is_suspended = $dto['is_suspended'] ?? 1;

        $this->prepareAuditActive($tenant);
        $this->prepareAuditInsert($tenant);
        $tenant->save();

        $this->results['data'] = $tenant;
        $this->results['message'] = "Tenant successfully stored";
    }

    public function prepare($dto)
    {
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
            'name' => ['required', 'string', 'max:255', new UniqueData('tnt_tenants', 'name')],
            'slug' => ['nullable', 'string', 'max:255', new UniqueData('tnt_tenants', 'slug')],
            'theme_code' => ['required', 'string', 'max:255'],
            'tenant_category_uuid' => ['required', 'uuid', new ExistsUuid(new TenantCategory())],
            'logo_uuid' => ['nullable', 'uuid', new ExistsUuid(new File())],
            'favicon_uuid' => ['nullable', 'uuid', new ExistsUuid(new File())],
            'custom_domain' => ['nullable', 'string', 'max:255'],
            'settings' => ['nullable', 'json'],
            'description' => ['nullable', 'string'],
            'is_suspended' => ['nullable', 'integer', 'in:0,1'],
        ];
    }
}
