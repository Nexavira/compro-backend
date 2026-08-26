<?php

namespace App\Services\Subscription;

use App\Models\Master\Package;
use App\Models\Tenant\Tenant;
use App\Models\Transaction\Subscription;
use App\Rules\ExistsId;
use App\Rules\ExistsUuid;
use App\Services\DefaultService;
use App\Services\ServiceInterface;
use Illuminate\Support\Str;

class StoreSubscriptionService extends DefaultService implements ServiceInterface
{
    public function process($dto)
    {
        $dto = $this->prepare($dto);

        $subscription = new Subscription();
        $subscription->tenant_id = $dto['tenant_id'];
        $subscription->subscription_number = $dto['subscription_number'];
        $subscription->package_id = $dto['package_id'];
        $subscription->package_name = $dto['package_name'];
        $subscription->billing_cycle = $dto['billing_cycle'];
        $subscription->status = $dto['status'];
        $subscription->next_billing_date = $dto['next_billing_date'] ?? null;
        $subscription->trial_end_at = $dto['trial_end_at'] ?? null;
        $subscription->start_at = $dto['start_at'] ?? null;
        $subscription->end_at = $dto['end_at'] ?? null;
        $subscription->amount = $dto['amount'];

        $this->prepareAuditActive($subscription);
        $this->prepareAuditInsert($subscription);
        $subscription->save();

        $this->results['data'] = $subscription;
        $this->results['message'] = 'Subscription successfully created';
    }

    public function prepare($dto)
    {
        if (isset($dto['tenant_uuid']) && !isset($dto['tenant_id'])) {
            $dto['tenant_id'] = $this->findIdByUuid(Tenant::query(), $dto['tenant_uuid']);
        }

        if (isset($dto['package_uuid']) && !isset($dto['package_id'])) {
            $dto['package_id'] = $this->findIdByUuid(Package::query(), $dto['package_uuid']);
        }

        $package = Package::find($dto['package_id']);
        $dto['package_name'] = $package->name;
        $dto['billing_cycle'] = $package->billing_cycle;

        $dto['subscription_number'] = $dto['subscription_number'] ?? 'SUB-' . now()->format('Ymd') . '-' . strtoupper(Str::random(5));

        return $dto;
    }

    public function rules($dto)
    {
        return [
            'tenant_id' => ['nullable', 'integer', new ExistsId(new Tenant)],
            'tenant_uuid' => ['required_without:tenant_id', 'uuid', new ExistsUuid(new Tenant)],
            'package_id' => ['nullable', 'integer', new ExistsId(new Package)],
            'package_uuid' => ['required_without:package_id', 'uuid', new ExistsUuid(new Package)],
            'status' => ['required', 'string'],
            'amount' => ['required', 'numeric'],
        ];
    }
}
