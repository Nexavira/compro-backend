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

class EditTenantTemplateService extends DefaultService implements ServiceInterface
{
    public function process($dto)
    {
        $dto = $this->prepare($dto);
        DB::beginTransaction();
        try {
            $global_template = TenantTemplate::find($dto['tenant_template_id']);

            if (isset($dto['tenant_id'])) {
                $global_template->tenant_id = $dto['tenant_id'];
            }
            if (isset($dto['global_template_id'])) {
                $global_template->global_template_id = $dto['global_template_id'];
            }
            if (isset($dto['template_settings'])) {
                $global_template->template_settings = $dto['template_settings'];
            }

            $this->prepareAuditUpdate($global_template);
            $global_template->save();

            if (isset($dto['pages']) && is_array($dto['pages'])) {
                foreach ($dto['pages'] as $pageData) {
                    if (isset($pageData['uuid'])) {
                        $page = TenantPage::where('uuid', $pageData['uuid'])->first();
                        if ($page) {
                            $page->title = $pageData['title'] ?? $page->title;
                            $page->slug = $pageData['slug'] ?? $page->slug;
                            if (array_key_exists('template_data', $pageData)) {
                                $page->template_data = $pageData['template_data'];
                            }
                            if (isset($pageData['is_active'])) {
                                $page->is_active = $pageData['is_active'];
                            }

                            $this->prepareAuditUpdate($page);
                            $page->save();
                        }
                    } else {
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
            }

            DB::commit();

            $global_template->load('tenant', 'globalTemplate');
            $this->results['data'] = $global_template;
            $this->results['message'] = "Tenant template and pages successfully updated";
        } catch (\Exception $e) {
            DB::rollBack();
            $this->results['error'] = true;
            $this->results['response_code'] = 500;
            $this->results['message'] = $e->getMessage();
        }
    }

    public function prepare($dto)
    {
        if (isset($dto['tenant_template_uuid'])) {
            $dto['tenant_template_id'] = $this->findIdByUuid(TenantTemplate::query(), $dto['tenant_template_uuid']);
        }
        if (isset($dto['global_template_uuid'])) {
            $dto['global_template_id'] = $this->findIdByUuid(GlobalTemplate::query(), $dto['global_template_uuid']);
        }

        return $dto;
    }

    public function rules($dto)
    {
        return [
            'tenant_template_uuid' => ['required', 'uuid', new ExistsUuid(new TenantTemplate)],
            'tenant_id' => ['nullable', 'integer', new ExistsId(new Tenant)],
            'tenant_uuid' => ['nullable', 'uuid', new ExistsUuid(new Tenant)],
            'global_template_id' => ['nullable', 'integer', new ExistsId(new GlobalTemplate)],
            'global_template_uuid' => ['nullable', 'uuid', new ExistsUuid(new GlobalTemplate)],
            'template_settings' => ['nullable'],
            'pages' => ['nullable', 'array'],
            'pages.*.uuid' => ['nullable', 'uuid', new ExistsUuid(new TenantPage)],
            'pages.*.title' => ['required_with:pages', 'string'],
            'pages.*.slug' => ['required_with:pages', 'string'],
            'pages.*.template_data' => ['nullable', 'array'],
            'pages.*.is_active' => ['nullable', 'integer', 'in:0,1'],
        ];
    }
}
