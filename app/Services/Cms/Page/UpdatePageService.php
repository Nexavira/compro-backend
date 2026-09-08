<?php

namespace App\Services\Cms\Page;

use App\Models\CMS\TenantTemplate;
use App\Rules\ExistsId;
use App\Rules\ExistsUuid;
use App\Services\DefaultService;
use App\Services\ServiceInterface;

class UpdatePageService extends DefaultService implements ServiceInterface
{

    public function process($dto)
    {
        $dto = $this->prepare($dto);
        $page = Page::find($dto['page_id']);

        $page->tenant_template_id = $dto['tenant_template_id'] ?? null;
        $page->title = $dto['title'] ?? $page->title;
        $page->slug = $dto['slug'] ?? $page->slug;
        $page->template_data = $dto['template_data'] ?? $page->template_data;

        $this->prepareAuditUpdate($page);
        $page->save();

        $this->results['data'] = $page;
        $this->results['message'] = "Page successfully updated";
    }

    public function prepare($dto)
    {
        if (isset($dto['page_uuid'])) {
            $dto['page_id'] = $this->findIdByUuid(Page::query(), $dto['page_uuid']);
        }

        if (isset($dto['tenant_template_uuid'])) {
            $dto['tenant_template_id'] = $this->findIdByUuid(TenantTemplate::query(), $dto['tenant_template_uuid']);
        }

        return $dto;
    }

    public function rules($dto)
    {
        return [
            'page_id' => ['required', 'integer', new ExistsId(new Page)],
            'page_uuid' => ['required', 'uuid', new ExistsUuid(new Page)],
            'tenant_template_id' => ['nullable', 'integer', new ExistsId(new TenantTemplate)],
            'tenant_template_uuid' => ['nullable', 'uuid', new ExistsUuid(new TenantTemplate)],
            'title' => ['nullable', 'string'],
            'slug' => ['nullable', 'string'],
            'template_data' => ['nullable'],
        ];
    }
}
