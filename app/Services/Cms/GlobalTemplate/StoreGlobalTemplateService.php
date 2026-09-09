<?php

namespace App\Services\Cms\GlobalTemplate;

use App\Models\Cms\GlobalTemplate;
use App\Models\Master\Package;
use App\Models\Tenant\TenantCategory;
use App\Rules\ExistsId;
use App\Rules\ExistsUuid;
use App\Rules\UniqueData;
use App\Services\DefaultService;
use App\Services\ServiceInterface;

class StoreGlobalTemplateService extends DefaultService implements ServiceInterface
{

    public function process($dto)
    {
        $dto = $this->prepare($dto);
        $global_template = new GlobalTemplate;

        $global_template->tenant_category_id = $dto['tenant_category_id'] ?? null;
        $global_template->package_id = $dto['package_id'] ?? null;
        $global_template->tier = $dto['tier'];
        $global_template->title = $dto['title'];
        $global_template->description = $dto['description'] ?? null;
        $global_template->brand_settings = $dto['brand_settings'] ?? null;
        $global_template->slug = $dto['slug'] ?? null;

        $this->prepareAuditActive($global_template);
        $this->prepareAuditInsert($global_template);
        $global_template->save();

        $this->results['data'] = $global_template;
        $this->results['message'] = "Global template successfully stored";
    }

    public function prepare($dto)
    {
        if (isset($dto['tenant_category_uuid'])) {
            $dto['tenant_category_id'] = $this->findIdByUuid(TenantCategory::query(), $dto['tenant_category_uuid']);
        }

        if (isset($dto['package_uuid'])) {
            $dto['package_id'] = $this->findIdByUuid(Package::query(), $dto['package_uuid']);
        }

        return $dto;
    }

    public function rules($dto)
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
