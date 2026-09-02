<?php

namespace App\Services\Cms\TenantTemplate;

use App\Models\CMS\TenantTemplate;
use App\Models\GlobalTemplate;
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
        $global_template = TenantTemplate::find($dto['tenant_template_id']);

        $global_template->tenant_id = $dto['tenant_id'];
        $global_template->global_template_id = $dto['global_template_id'];
        $global_template->template_settings = $dto['template_settings'];

        $this->prepareAuditUpdate($global_template);
        $global_template->save();

        $this->results['data'] = $global_template;
        $this->results['message'] = "Tenant template successfully updated";
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
            'tenant_id' => ['nullable', 'integer', new ExistsId(new Tenant())],
            'tenant_uuid' => ['nullable', 'uuid', new ExistsUuid(new Tenant())],
            'global_template_id' => ['nullable', 'integer', new ExistsId(new GlobalTemplate())],
            'global_template_uuid' => ['nullable', 'uuid', new ExistsUuid(new GlobalTemplate())],
            'template_settings' => ['nullable'],
        ];
    }
}
