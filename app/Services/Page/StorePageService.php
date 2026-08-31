<?php

namespace App\Services\Page;

use App\Models\CMS\Page;
use App\Models\CMS\TenantTemplate;
use App\Rules\ExistsId;
use App\Rules\ExistsUuid;
use App\Services\DefaultService;
use App\Services\ServiceInterface;

class StorePageService extends DefaultService implements ServiceInterface
{

    public function process($dto)
    {
        $dto = $this->prepare($dto);
        $page = new Page();

        $page->tenant_template_id = $dto['tenant_template_id'] ?? null;
        $page->title = $dto['title'];
        $page->slug = $dto['slug'];
        $page->template_data = $dto['template_data'] ?? null;

        $this->prepareAuditActive($page);
        $this->prepareAuditInsert($page);
        $page->save();

        $this->results['data'] = $page;
        $this->results['message'] = "Page successfully stored";
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
            'tenant_template_id' => ['nullable', 'integer', new ExistsId(new TenantTemplate())],
            'tenant_template_uuid' => ['nullable', 'uuid', new ExistsUuid(new TenantTemplate())],
            'title' => ['required', 'string'],
            'slug' => ['required', 'string'],
            'template_data' => ['nullable'],
        ];
    }
}
