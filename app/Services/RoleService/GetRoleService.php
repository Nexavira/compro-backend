<?php
namespace App\Services\RoleService;

use App\Models\Auth\Role;
use App\Services\DefaultService;
use App\Services\ServiceInterface;

class GetRoleService extends DefaultService implements ServiceInterface {

    public function process($dto)
    {
        $dto['per_page'] = $dto['per_page'] ?? 10;
        $dto['page'] = $dto['page'] ?? 1;
        $dto['sort_by'] = $dto['sort_by'] ?? 'updated_at';
        $dto['sort_type'] = $dto['sort_type'] ?? 'desc';

        $model = Role::where('deleted_at',null)
        ->orderBy($dto['sort_by'], $dto['sort_type']);

        if (isset($dto['search_param']) and $dto['search_param'] != null) {
            $model->where(function ($q) use ($dto) {
                $q->where('name', 'ILIKE', '%' . $dto['search_param'] . '%')
                    ->orwhere('code', 'ILIKE', '%' . $dto['search_param'] . '%')
                    ->orwhere('description', 'ILIKE', '%' . $dto['search_param'] . '%')
                    ->orwhere('type', 'ILIKE', '%' . $dto['search_param'] . '%');
            });
        }

        if (isset($dto['type'])) {
            $model->where('type', $dto['type']);
        }

        if (isset($dto['code'])) {
            $model->where('code', $dto['code']);
        }

        if (isset($dto['with_role_permission'])) {
            $model->with('rolePermission.permission');
        }

        if(isset($dto['app_package_code'])) {
            $model->whereHas('appPackage', function($query) use ($dto) {
                $query->where('code', $dto['app_package_code']);
            });
        }

        if (isset($dto['role_uuid']) and $dto['role_uuid'] != '') {
            $model->where('uuid', $dto['role_uuid']);
            $data = $model->first();
        } else {
            if (isset($dto['with_pagination'])) {
                $this->results['pagination'] = $this->paginationDetail($dto['per_page'], $dto['page'], $model->count());
                $model = $this->paginateData($model, $dto['per_page'], $dto['page']);
            }
            $data = $model->get();
        }

        $this->results['message'] = "Role successfully fetched";
        $this->results['data'] = $data;
    }

}
