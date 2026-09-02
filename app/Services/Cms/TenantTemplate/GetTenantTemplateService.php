<?php

namespace App\Services\Cms\TenantTemplate;

use App\Models\TenantTemplate;
use App\Services\DefaultService;
use App\Services\ServiceInterface;

class GetTenantTemplateService extends DefaultService implements ServiceInterface
{

    public function process($dto)
    {
        $dto['per_page'] = $dto['per_page'] ?? 10;
        $dto['page'] = $dto['page'] ?? 1;
        $dto['sort_by'] = $dto['sort_by'] ?? 'updated_at';
        $dto['sort_type'] = $dto['sort_type'] ?? 'desc';

        $model = TenantTemplate::where('deleted_at', null)
            ->orderBy($dto['sort_by'], $dto['sort_type']);

        // if (isset($dto['search_param']) and $dto['search_param'] != null) {
        //     $model->where(function ($q) use ($dto) {
        //         $q->where('name', 'ILIKE', '%' . $dto['search_param'] . '%')
        //             ->orwhere('code', 'ILIKE', '%' . $dto['search_param'] . '%')
        //             ->orwhere('description', 'ILIKE', '%' . $dto['search_param'] . '%')
        //             ->orwhere('type', 'ILIKE', '%' . $dto['search_param'] . '%');
        //     });
        // }

        if (isset($dto['tenant_template_id_in'])) {
            $model->whereIn('id', $dto['tenant_template_id_in']);
        }

        if (isset($dto['tenant_template_uuid']) and $dto['tenant_template_uuid'] != '') {
            $model->where('uuid', $dto['tenant_template_uuid']);
            $data = $model->first();
        } else {
            if (isset($dto['with_pagination'])) {
                $this->results['pagination'] = $this->paginationDetail($dto['per_page'], $dto['page'], $model->count());
                $model = $this->paginateData($model, $dto['per_page'], $dto['page']);
            }
            $data = $model->get();
        }

        $this->results['message'] = "Tenant Template successfully fetched";
        $this->results['data'] = $data;
    }
}
