<?php

namespace App\Services\Cms\TenantTemplate;

use App\Models\Cms\TenantTemplate;
use App\Models\Cms\GlobalTemplate;
use App\Models\Tenant\Tenant;
use App\Rules\ExistsId;
use App\Rules\ExistsUuid;
use App\Services\DefaultService;
use App\Services\ServiceInterface;

class UpdateTenantTemplateService extends DefaultService implements ServiceInterface
{

    public function process($dto)
    {
        $dto = $this->prepare($dto);
        $tenant_template = TenantTemplate::find($dto['tenant_template_id']);

        $tenant_template->tenant_id = $dto['tenant_id'] ?? $tenant_template->tenant_id;
        $tenant_template->global_template_id = $dto['global_template_id'] ?? $tenant_template->global_template_id;
        $tenant_template->template_settings = $dto['template_settings'] ?? $tenant_template->template_settings;
        $tenant_template->active_template = $dto['active_template'] ?? $tenant_template->active_template;

        $this->prepareAuditUpdate($tenant_template);
        $tenant_template->save();

        $this->results['data'] = $tenant_template;
        $this->results['message'] = "Tenant template successfully updated";
    }

    public function prepare($dto)
    {
        if (isset($dto['tenant_template_uuid'])) {
            $dto['tenant_template_id'] = $this->findIdByUuid(TenantTemplate::query(), $dto['tenant_template_uuid']);
        }

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
            'tenant_template_uuid' => ['required', 'uuid', new ExistsUuid(new TenantTemplate)],
            'tenant_id' => ['nullable', 'integer', new ExistsId(new Tenant)],
            'tenant_uuid' => ['nullable', 'uuid', new ExistsUuid(new Tenant)],
            'global_template_id' => ['nullable', 'integer', new ExistsId(new GlobalTemplate)],
            'global_template_uuid' => ['nullable', 'uuid', new ExistsUuid(new GlobalTemplate)],
            'template_settings' => ['nullable'],
            'active_template' => ['boolean'],
        ];
    }
}
