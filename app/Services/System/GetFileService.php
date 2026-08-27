<?php

namespace App\Services\System;

use App\Models\System\File;
use App\Models\Tenant\Tenant;
use App\Services\DefaultService;
use App\Services\ServiceInterface;

class GetFileService extends DefaultService implements ServiceInterface
{
    public function process($dto)
    {
        $dto['per_page'] = $dto['per_page'] ?? 10;
        $dto['page'] = $dto['page'] ?? 1;
        $dto['sort_by'] = $dto['sort_by'] ?? 'updated_at';
        $dto['sort_type'] = $dto['sort_type'] ?? 'desc';

        $model = File::orderBy($dto['sort_by'], $dto['sort_type']);

        if (isset($dto['search_param']) and $dto['search_param'] != null) {
            $model->where(function ($q) use ($dto) {
                $q->where('file_name', 'ILIKE', '%' . $dto['search_param'] . '%')
                    ->orWhere('original_name', 'ILIKE', '%' . $dto['search_param'] . '%');
            });
        }

        if (isset($dto['tenant_uuid']) && $dto['tenant_uuid'] != '') {
            $tenant_id = Tenant::where('uuid', $dto['tenant_uuid'])->first()->id;
            $model->where('tenant_id', $tenant_id);
        }

        if (isset($dto['related_type']) && $dto['related_type'] != '') {
            $model->where('related_type', $dto['related_type']);
        }

        if (isset($dto['related_id']) && $dto['related_id'] != '') {
            $model->where('related_id', $dto['related_id']);
        }

        if (isset($dto['file_uuid']) and $dto['file_uuid'] != '') {
            $model->where('uuid', $dto['file_uuid']);
            $data = $model->first();

            if (!$data) {
                $this->results['error'] = true;
                $this->results['message'] = "File not found";
                $this->results['response_code'] = 404;
                return;
            }
        } else {
            if (isset($dto['with_pagination'])) {
                $this->results['pagination'] = $this->paginationDetail($dto['per_page'], $dto['page'], $model->count());
                $model = $this->paginateData($model, $dto['per_page'], $dto['page']);
            }
            $data = $model->get();
        }

        $this->results['data'] = $data;
        $this->results['message'] = "File successfully fetched";
    }
}
