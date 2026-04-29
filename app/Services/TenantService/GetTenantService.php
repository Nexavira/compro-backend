<?php
namespace App\Services\TenantService;

use App\Models\Tenant\Tenant;
use App\Services\DefaultService;
use App\Services\ServiceInterface;

class GetTenantService extends DefaultService implements ServiceInterface {

    public function process($dto)
    {
        $dto['per_page'] = $dto['per_page'] ?? 10;
        $dto['page'] = $dto['page'] ?? 1;
        $dto['sort_by'] = $dto['sort_by'] ?? 'updated_at';
        $dto['sort_type'] = $dto['sort_type'] ?? 'desc';

        $model = Tenant::where('deleted_at',null)
        ->orderBy($dto['sort_by'], $dto['sort_type']);

        if (isset($dto['search_param']) and $dto['search_param'] != null) {
            $model->where(function ($q) use ($dto) {
                $q->where('name', 'ILIKE', '%' . $dto['search_param'] . '%')
                    ->orwhere('code', 'ILIKE', '%' . $dto['search_param'] . '%')
                    ->orwhere('description', 'ILIKE', '%' . $dto['search_param'] . '%');
            });
        }

        if (isset($dto['tenant_uuid']) and $dto['tenant_uuid'] != '') {
            $model->where('uuid', $dto['tenant_uuid']);
            $data = $model->first();
        } else {
            if (isset($dto['with_pagination'])) {
                $this->results['pagination'] = $this->paginationDetail($dto['per_page'], $dto['page'], $model->count());
                $model = $this->paginateData($model, $dto['per_page'], $dto['page']);
            }
            $data = $model->get();
        }

        $this->results['message'] = "Tenant successfully fetched";
        $this->results['data'] = $data;
    }

}
