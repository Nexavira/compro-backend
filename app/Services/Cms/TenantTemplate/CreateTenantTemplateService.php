<?php

namespace App\Services\Cms\TenantTemplate;

use App\Models\Cms\TenantTemplate;
use App\Models\Cms\GlobalTemplate;
use App\Models\Tenant\Tenant;
use App\Models\Cms\TenantPage;
use App\Rules\ExistsId;
use App\Rules\ExistsUuid;
use App\Services\DefaultService;
use App\Services\ServiceInterface;
use Illuminate\Support\Facades\DB;

class CreateTenantTemplateService extends DefaultService implements ServiceInterface
{
    public function process($dto)
    {
        $dto = $this->prepare($dto);
        DB::beginTransaction();
        try {
            $global_template = new TenantTemplate();

            $global_template->tenant_id = $dto['tenant_id'] ?? null;
            $global_template->global_template_id = $dto['global_template_id'] ?? null;
            $global_template->template_settings = $dto['template_settings'] ?? null;

            $this->prepareAuditActive($global_template);
            $this->prepareAuditInsert($global_template);
            $global_template->save();

            // Store pages
            if (isset($dto['pages']) && is_array($dto['pages'])) {
                foreach ($dto['pages'] as $pageData) {
                    $page = new TenantPage();
                    $page->tenant_template_id = $global_template->id;
                    $page->title = $pageData['title'];
                    $page->slug = $pageData['slug'];
                    $page->template_data = $pageData['template_data'] ?? null;
                    $page->is_active = $pageData['is_active'] ?? 1;

                    $this->prepareAuditActive($page);
                    $this->prepareAuditInsert($page);
                    $page->save();
                }
            }

            DB::commit();

            // Refresh model to include relations or just return the newly created instance
            $global_template->load('tenant', 'globalTemplate');
            $this->results['data'] = $global_template;
            $this->results['message'] = "Tenant template and pages successfully stored";
        } catch (\Exception $e) {
            DB::rollBack();
            $this->results['error'] = true;
            $this->results['response_code'] = 500;
            $this->results['message'] = $e->getMessage();
        }
    }

    public function prepare($dto)
    {
        if (isset($dto['tenant_uuid'])) {
            $dto['tenant_id'] = $this->findIdByUuid(Tenant::query(), $dto['tenant_uuid']);
        }

        if (isset($dto['global_template_uuid'])) {
            $dto['global_template_id'] = $this->findIdByUuid(GlobalTemplate::query(), $dto['global_template_uuid']);
        }

        return $dto;
    }

    public function rules($dto)
    {
        return [
            'tenant_id' => ['nullable', 'integer', new ExistsId(new Tenant)],
            'tenant_uuid' => ['nullable', 'uuid', new ExistsUuid(new Tenant)],
            'global_template_id' => ['nullable', 'integer', new ExistsId(new GlobalTemplate)],
            'global_template_uuid' => ['nullable', 'uuid', new ExistsUuid(new GlobalTemplate)],
            'template_settings' => ['nullable'],
            'pages' => ['nullable', 'array'],
            'pages.*.title' => ['required_with:pages', 'string'],
            'pages.*.slug' => ['required_with:pages', 'string'],
            'pages.*.template_data' => ['nullable', 'array'],
        ];
    }
}
