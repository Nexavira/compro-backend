<?php

namespace App\Services\Cms\TenantPage;

use App\Models\Cms\TenantPage;
use App\Models\Cms\TenantTemplate;
use App\Rules\ExistsId;
use App\Rules\ExistsUuid;
use App\Services\DefaultService;
use App\Services\ServiceInterface;

class StoreTenantPageService extends DefaultService implements ServiceInterface
{
    public function process($dto)
    {
        $dto = $this->prepare($dto);
        $page = new TenantPage();

        $page->tenant_template_id = $dto['tenant_template_id'] ?? null;
        $page->title = $dto['title'] ?? null;
        $page->slug = $dto['slug'] ?? null;
        $page->template_data = $dto['template_data'] ?? null;
        $page->is_active = $dto['is_active'] ?? 1;

        $this->prepareAuditActive($page);
        $this->prepareAuditInsert($page);
        $page->save();

        $this->results['data'] = $page;
        $this->results['message'] = "Tenant page successfully stored";
    }

    public function prepare($dto)
    {
        if (isset($dto['tenant_template_uuid'])) {
            $dto['tenant_template_id'] = $this->findIdByUuid(TenantTemplate::query(), $dto['tenant_template_uuid']);
        }

        return $dto;
    }

    public function rules($dto)
    {
        return [
            'tenant_template_id' => ['required', 'integer', new ExistsId(new TenantTemplate)],
            'tenant_template_uuid' => ['required', 'uuid', new ExistsUuid(new TenantTemplate)],
            'title' => ['required', 'string'],
            'slug' => ['required', 'string'],
            'template_data' => ['nullable', 'array'],
            'is_active' => ['nullable', 'integer', 'in:0,1'],
        ];
    }
}
