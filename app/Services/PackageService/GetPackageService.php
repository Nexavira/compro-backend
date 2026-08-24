<?php

namespace App\Services\PackageService;

use App\Models\Master\Package;
use App\Services\DefaultService;
use App\Services\ServiceInterface;

class GetPackageService extends DefaultService implements ServiceInterface
{

    public function process($dto)
    {
        $dto['per_page'] = $dto['per_page'] ?? 10;
        $dto['page'] = $dto['page'] ?? 1;
        $dto['sort_by'] = $dto['sort_by'] ?? 'updated_at';
        $dto['sort_type'] = $dto['sort_type'] ?? 'desc';

        $model = Package::where('deleted_at', null)
            ->orderBy($dto['sort_by'], $dto['sort_type']);

        if (isset($dto['search_param']) and $dto['search_param'] != null) {
            $model->where(function ($q) use ($dto) {
                $q->where('name', 'ILIKE', '%' . $dto['search_param'] . '%')
                    ->orwhere('code', 'ILIKE', '%' . $dto['search_param'] . '%')
                    ->orwhere('description', 'ILIKE', '%' . $dto['search_param'] . '%');
            });
        }

        if (isset($dto['website_type']) and $dto['website_type'] != '') {
            $model->where('website_type', $dto['website_type']);
        }

        if (isset($dto['billing_cycle']) and $dto['billing_cycle'] != '') {
            $model->where('billing_cycle', $dto['billing_cycle']);
        }

        if (isset($dto['package_uuid']) and $dto['package_uuid'] != '') {
            $model->where('uuid', $dto['package_uuid']);
            $data = $model->first();
        } else {
            if (isset($dto['with_pagination'])) {
                $this->results['pagination'] = $this->paginationDetail($dto['per_page'], $dto['page'], $model->count());
                $model = $this->paginateData($model, $dto['per_page'], $dto['page']);
            }
            $data = $model->get();
        }

        $this->results['message'] = "Package successfully fetched";
        $this->results['data'] = $data;
    }
}
