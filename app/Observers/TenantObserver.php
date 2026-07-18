<?php

namespace App\Observers;

use App\Models\Tenant\Tenant;
use App\Services\TemplateCloningService;

class TenantObserver
{
    /**
     * Handle the Tenant "created" event.
     */
    public function created(Tenant $tenant): void {}

    /**
     * Handle the Tenant "updated" event.
     */
    public function updated(Tenant $tenant): void
    {
        // Check if global_template_id was changed
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
