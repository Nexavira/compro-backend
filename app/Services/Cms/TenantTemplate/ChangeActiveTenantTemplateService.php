<?php

namespace App\Services\Cms\TenantTemplate;

use App\Models\Cms\TenantTemplate;
use App\Rules\ExistsUuid;
use App\Services\DefaultService;
use App\Services\ServiceInterface;

class ChangeActiveTenantTemplateService extends DefaultService implements ServiceInterface
{

    public function process($dto)
    {
        $dto = $this->prepare($dto);
        $tenant_template = !empty($dto['tenant_template_id']) ? TenantTemplate::find($dto['tenant_template_id']) : null;
        if (!$tenant_template) {
            $this->results['response_code'] = 404;
            $this->results['error'] = true;
            $this->results['message'] = "Tenant template not found";
            return;
        }

        $tenant_template->active_template = (bool) $dto['active_template'];

        $this->prepareAuditUpdate($tenant_template);
        $tenant_template->save();

        // check other templates for the same tenant and set them to inactive if this one is active
        if ($tenant_template->active_template) {
            TenantTemplate::where('tenant_id', $tenant_template->tenant_id)
                ->where('id', '!=', $tenant_template->id)
                ->update(['active_template' => false]);
        }

        $this->results['data'] = $tenant_template;
        $this->results['message'] = "Tenant template successfully updated";
    }

    public function prepare($dto)
    {
        $query = TenantTemplate::query();
        if (isset($dto['tenant_slug'])) {
            $query->whereHas('tenant', function ($q) use ($dto) {
                $q->where('slug', $dto['tenant_slug']);
            });
        }
        $dto['tenant_template_id'] = $this->findIdByUuid($query, $dto['tenant_template_uuid'] ?? null);

        return $dto;
    }

    public function rules($dto)
    {
        return [
            'tenant_template_uuid' => ['required', 'uuid', new ExistsUuid(new TenantTemplate)],
            'active_template' => ['required', 'boolean'],
        ];
    }
}
