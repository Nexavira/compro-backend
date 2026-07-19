<?php

namespace App\Observers;

use App\Models\Tenant\Tenant;
use App\Services\TemplateCloningService;

class TenantObserver
{

    public function created(Tenant $tenant): void {}

    public function updated(Tenant $tenant): void
    {

        if ($tenant->wasChanged('global_template_id')) {
            $this->handleTemplateCloning($tenant);
        }
    }

    private function handleTemplateCloning(Tenant $tenant)
    {
        if ($tenant->global_template_id && $tenant->globalTemplate) {
            $cloningService = new TemplateCloningService();
            $cloningService->cloneTemplateToTenant($tenant->globalTemplate, $tenant);
        }
    }
}
