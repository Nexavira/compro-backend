<?php

namespace App\Services\Cms\TenantPage;

use App\Models\Cms\TenantPage;
use App\Models\Cms\TenantTemplate;
use App\Rules\ExistsId;
use App\Rules\ExistsUuid;
use App\Services\DefaultService;
use App\Services\ServiceInterface;

class UpdateTenantPageService extends DefaultService implements ServiceInterface
{
    public function process($dto)
    {
        $dto = $this->prepare($dto);
        $page = TenantPage::find($dto['tenant_page_id']);

        if (isset($dto['tenant_template_id'])) {
            $page->tenant_template_id = $dto['tenant_template_id'];
        }
        if (isset($dto['title'])) {
            $page->title = $dto['title'];
        }
        if (isset($dto['slug'])) {
            $page->slug = $dto['slug'];
        }
        if (isset($dto['template_data'])) {
            $page->template_data = $dto['template_data'];
        }
        if (isset($dto['is_active'])) {
            $page->is_active = $dto['is_active'];
        }

        $this->prepareAuditUpdate($page);
        $page->save();

        $this->results['data'] = $page;
        $this->results['message'] = "Tenant page successfully updated";
    }

    public function prepare($dto)
    {
        if (isset($dto['tenant_page_uuid'])) {
            $dto['tenant_page_id'] = $this->findIdByUuid(TenantPage::query(), $dto['tenant_page_uuid']);
        }
        if (isset($dto['tenant_template_uuid'])) {
            $dto['tenant_template_id'] = $this->findIdByUuid(TenantTemplate::query(), $dto['tenant_template_uuid']);
        }

        return $dto;
    }

    public function rules($dto)
    {
        return [
            'tenant_page_uuid' => ['required', 'uuid', new ExistsUuid(new TenantPage)],
            'tenant_template_id' => ['nullable', 'integer', new ExistsId(new TenantTemplate)],
            'tenant_template_uuid' => ['nullable', 'uuid', new ExistsUuid(new TenantTemplate)],
            'title' => ['nullable', 'string'],
            'slug' => ['nullable', 'string'],
            'template_data' => ['nullable', 'array'],
            'is_active' => ['nullable', 'integer', 'in:0,1'],
        ];
    }
}
