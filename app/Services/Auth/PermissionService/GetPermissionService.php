<?php

namespace App\Services\AuthService\PermissionService;

use App\Models\Auth\Permission;
use App\Services\DefaultService;
use App\Services\ServiceInterface;

class GetPermissionService extends DefaultService implements ServiceInterface
{
    public function process($dto)
    {
        $dto['per_page'] = $dto['per_page'] ?? 10;
        $dto['page'] = $dto['page'] ?? 1;
        $dto['sort_by'] = $dto['sort_by'] ?? 'updated_at';
        $dto['sort_type'] = $dto['sort_type'] ?? 'desc';

        $model = Permission::where('deleted_at', null)
            ->orderBy($dto['sort_by'], $dto['sort_type']);

        if (isset($dto['search_param']) and $dto['search_param'] != null) {
            $model->where(function ($q) use ($dto) {
                $q->where('name', 'ILIKE', '%'.$dto['search_param'].'%');
            });
        }

        if (isset($dto['permission_uuid']) and $dto['permission_uuid'] != '') {
            $model->where('uuid', $dto['permission_uuid']);
            $data = $model->first();
        } else {
            if (isset($dto['with_pagination'])) {
                $this->results['pagination'] = $this->paginationDetail($dto['per_page'], $dto['page'], $model->count());
                $model = $this->paginateData($model, $dto['per_page'], $dto['page']);
            }
            $data = $model->get();
        }

        $this->results['message'] = 'Permission successfully fetched';
        $this->results['data'] = $data;
    }
}
