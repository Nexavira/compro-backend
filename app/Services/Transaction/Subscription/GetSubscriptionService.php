<?php

namespace App\Services\Transaction\Subscription;

use App\Models\Tenant\Tenant;
use App\Models\Transaction\Subscription;
use App\Services\DefaultService;
use App\Services\ServiceInterface;

class GetSubscriptionService extends DefaultService implements ServiceInterface
{
    public function process($dto)
    {
        $dto = $this->prepare($dto);
        $query = Subscription::query()->with(['package']);

        if (isset($dto['tenant_id'])) {
            $query->where('tenant_id', $dto['tenant_id']);
        }

        if (isset($dto['search'])) {
            $query->whereHas('package', function ($q) use ($dto) {
                $q->where('name', 'like', '%' . $dto['search'] . '%');
            });
        }

        if (isset($dto['is_paginate']) && $dto['is_paginate'] === false) {
            $this->results['data'] = $query->orderBy('created_at', 'desc')->get();
        } else {
            $limit = $dto['limit'] ?? 10;
            $this->results['data'] = $query->orderBy('created_at', 'desc')->paginate($limit);
            $this->results['pagination'] = $this->generatePagination($this->results['data']);
        }

        $this->results['message'] = 'Berhasil mengambil data langganan.';
    }

    public function prepare($dto)
    {
        if (isset($dto['tenant_uuid'])) {
            $dto['tenant_id'] = $this->findIdByUuid(Tenant::query(), $dto['tenant_uuid']);
        }
        return $dto;
    }

    public function rules($dto)
    {
        return [];
    }
}
