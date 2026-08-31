<?php

namespace App\Services\Page;

use App\Models\CMS\Page;
use App\Services\DefaultService;
use App\Services\ServiceInterface;

class GetPageService extends DefaultService implements ServiceInterface
{

    public function process($dto)
    {
        $dto['per_page'] = $dto['per_page'] ?? 10;
        $dto['page'] = $dto['page'] ?? 1;
        $dto['sort_by'] = $dto['sort_by'] ?? 'updated_at';
        $dto['sort_type'] = $dto['sort_type'] ?? 'desc';

        $model = Page::where('deleted_at', null)
            ->orderBy($dto['sort_by'], $dto['sort_type']);

        // if (isset($dto['search_param']) and $dto['search_param'] != null) {
        //     $model->where(function ($q) use ($dto) {
        //         $q->where('name', 'ILIKE', '%' . $dto['search_param'] . '%')
        //             ->orwhere('code', 'ILIKE', '%' . $dto['search_param'] . '%')
        //             ->orwhere('description', 'ILIKE', '%' . $dto['search_param'] . '%')
        //             ->orwhere('type', 'ILIKE', '%' . $dto['search_param'] . '%');
        //     });
        // }

        if (isset($dto['page_id_in'])) {
            $model->whereIn('id', $dto['page_id_in']);
        }

        if (isset($dto['page_uuid']) and $dto['page_uuid'] != '') {
            $model->where('uuid', $dto['page_uuid']);
            $data = $model->first();
        } else {
            if (isset($dto['with_pagination'])) {
                $this->results['pagination'] = $this->paginationDetail($dto['per_page'], $dto['page'], $model->count());
                $model = $this->paginateData($model, $dto['per_page'], $dto['page']);
            }
            $data = $model->get();
        }

        $this->results['message'] = "Page successfully fetched";
        $this->results['data'] = $data;
    }
}
