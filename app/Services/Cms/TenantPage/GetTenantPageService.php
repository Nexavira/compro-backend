<?php

namespace App\Services\Cms\TenantPage;

use App\Models\Cms\TenantPage;
use App\Services\DefaultService;
use App\Services\ServiceInterface;

class GetTenantPageService extends DefaultService implements ServiceInterface
{
    public function process($dto)
    {
        $dto['per_page'] = $dto['per_page'] ?? 10;
        $dto['page'] = $dto['page'] ?? 1;
        $dto['sort_by'] = $dto['sort_by'] ?? 'updated_at';
        $dto['sort_type'] = $dto['sort_type'] ?? 'desc';

        $model = TenantPage::with(['tenantTemplate'])
            ->where('deleted_at', null)
            ->orderBy($dto['sort_by'], $dto['sort_type']);

        if (isset($dto['tenant_template_uuid']) && $dto['tenant_template_uuid'] != null) {
            $model->whereHas('tenantTemplate', function ($q) use ($dto) {
                $q->where('uuid', $dto['tenant_template_uuid']);
            });
        }

        if (isset($dto['search_param']) && $dto['search_param'] != null) {
            $model->where(function ($q) use ($dto) {
                $q->where('title', 'ILIKE', '%' . $dto['search_param'] . '%')
                    ->orWhere('slug', 'ILIKE', '%' . $dto['search_param'] . '%');
            });
        }

        if (isset($dto['tenant_page_uuid']) && $dto['tenant_page_uuid'] != '') {
            $model->where('uuid', $dto['tenant_page_uuid']);
            $data = $model->first();
        } else {
            if (isset($dto['with_pagination']) && $dto['with_pagination'] == true) {
                $this->results['pagination'] = $this->paginationDetail($dto['per_page'], $dto['page'], $model->count());
                $model = $this->paginateData($model, $dto['per_page'], $dto['page']);
            }
            $data = $model->get();
        }

        $this->results['message'] = "Tenant Page successfully fetched";
        $this->results['data'] = $data;
    }
}
