<?php

namespace App\Services\Tenant\Cms;

use App\Models\CMS\TenantTemplatePage;
use App\Services\DefaultService;
use App\Services\ServiceInterface;

class PublicGetTenantTemplatePageService extends DefaultService implements ServiceInterface
{
    public function process($dto)
    {
        $query = TenantTemplatePage::query()
            ->with(['tenantTemplate.tenant'])
            ->whereHas('tenantTemplate.tenant', function ($q) use ($dto) {
                if (isset($dto['tenant_slug'])) {
                    $q->where('slug', $dto['tenant_slug']);
                }
            });

        if (isset($dto['slug'])) {
            $query->where('slug', $dto['slug']);
        }

        if (isset($dto['slug'])) {
            $data = $query->first();
            if (!$data) {
                $this->results['error'] = true;
                $this->results['message'] = "Page not found";
                $this->results['response_code'] = 404;
                return;
            }
        } else {
            $data = $query->get();
        }

        $this->results['data'] = $data;
        $this->results['message'] = "Berhasil mengambil data halaman.";
    }

    public function rules($dto)
    {
        return [];
    }
}
