<?php

namespace App\Services\Cms\GlobalTemplate;

use App\Models\GlobalTemplate;
use App\Services\DefaultService;
use App\Services\ServiceInterface;

class GetGlobalTemplateService extends DefaultService implements ServiceInterface
{

    public function process($dto)
    {
        $dto['per_page'] = $dto['per_page'] ?? 10;
        $dto['page'] = $dto['page'] ?? 1;
        $dto['sort_by'] = $dto['sort_by'] ?? 'updated_at';
        $dto['sort_type'] = $dto['sort_type'] ?? 'desc';

        $model = GlobalTemplate::where('deleted_at', null)
            ->orderBy($dto['sort_by'], $dto['sort_type']);

        if (isset($dto['search_param']) and $dto['search_param'] != null) {
            $model->where(function ($q) use ($dto) {
                $q->where('name', 'ILIKE', '%' . $dto['search_param'] . '%')
                    ->orwhere('title', 'ILIKE', '%' . $dto['search_param'] . '%')
                    ->orwhere('description', 'ILIKE', '%' . $dto['search_param'] . '%');
            });
        }

        if (isset($dto['global_template_id_in'])) {
            $model->whereIn('id', $dto['global_template_id_in']);
        }

        if (isset($dto['global_template_uuid']) and $dto['global_template_uuid'] != '') {
            $model->where('uuid', $dto['global_template_uuid']);
            $data = $model->first();
        } else {
            if (isset($dto['with_pagination'])) {
                $this->results['pagination'] = $this->paginationDetail($dto['per_page'], $dto['page'], $model->count());
                $model = $this->paginateData($model, $dto['per_page'], $dto['page']);
            }
            $data = $model->get();
        }

        $this->results['message'] = "Global Template successfully fetched";
        $this->results['data'] = $data;
    }
}
