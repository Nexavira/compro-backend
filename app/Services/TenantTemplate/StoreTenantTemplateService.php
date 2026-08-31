<?php

namespace App\Services\TenantTemplate;

use App\Models\CMS\TenantTemplate;
use App\Models\Tenant;
use App\Models\GlobalTemplate;
use App\Rules\ExistsId;
use App\Rules\ExistsUuid;
use App\Services\DefaultService;
use App\Services\ServiceInterface;

class StoreTenantTemplateService extends DefaultService implements ServiceInterface
{

    public function process($dto)
    {
        $dto = $this->prepare($dto);
        $global_template = new TenantTemplate();

        $global_template->tenant_id = $dto['tenant_id'] ?? null;
        $global_template->global_template_id = $dto['global_template_id'] ?? null;
        $global_template->template_settings = $dto['template_settings'] ?? null;

        $this->prepareAuditActive($global_template);
        $this->prepareAuditInsert($global_template);
        $global_template->save();

        $this->results['data'] = $global_template;
        $this->results['message'] = "Tenant template successfully stored";
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
            'tenant_id' => ['nullable', 'integer', new ExistsId(new Tenant())],
            'tenant_uuid' => ['nullable', 'uuid', new ExistsUuid(new Tenant())],
            'global_template_id' => ['nullable', 'integer', new ExistsId(new GlobalTemplate())],
            'global_template_uuid' => ['nullable', 'uuid', new ExistsUuid(new GlobalTemplate())],
            'template_settings' => ['nullable'],
        ];
    }
}
