<?php

namespace App\Services\Payment;

use App\Models\Tenant\Tenant;
use App\Models\Transaction\Payment;
use App\Models\Transaction\Subscription;
use App\Rules\ExistsId;
use App\Rules\ExistsUuid;
use App\Services\DefaultService;
use App\Services\ServiceInterface;
use Illuminate\Support\Str;

class StorePaymentService extends DefaultService implements ServiceInterface
{
    public function process($dto)
    {
        $dto = $this->prepare($dto);
        $payment = new Payment;

        $payment->tenant_id = $dto['tenant_id'];
        $payment->subscription_id = $dto['subscription_id'] ?? null;
        $payment->invoice_number = $dto['invoice_number'];
        $payment->amount_due = $dto['amount_due'];
        $payment->due_date = $dto['due_date'];
        $payment->status = $dto['status'];

        $payment->payment_date = $dto['payment_date'] ?? null;
        $payment->amount_paid = $dto['amount_paid'] ?? 0;
        $payment->payment_method = $dto['payment_method'] ?? null;

        $this->prepareAuditActive($payment);
        $this->prepareAuditInsert($payment);
        $payment->save();

        $this->results['data'] = $payment;
        $this->results['message'] = 'Payment successfully created';
    }

    public function prepare($dto)
    {
        if (isset($dto['tenant_uuid']) && !isset($dto['tenant_id'])) {
            $dto['tenant_id'] = $this->findIdByUuid(Tenant::query(), $dto['tenant_uuid']);
        }

        $dto['invoice_number'] = $dto['invoice_number'] ?? 'INV-' . now()->format('Ymd') . '-' . strtoupper(Str::random(5));

        if (isset($dto['subscription_uuid']) && !isset($dto['subscription_id'])) {
            $dto['subscription_id'] = $this->findIdByUuid(Subscription::query(), $dto['subscription_uuid']);
        }

        return $dto;
    }

    public function rules($dto)
    {
        return [
            'tenant_id' => ['nullable', 'integer', new ExistsId(new Tenant)],
            'tenant_uuid' => ['required_without:tenant_id', 'uuid', new ExistsUuid(new Tenant)],
            'subscription_id' => ['nullable', 'integer', new ExistsId(new Subscription)],
            'subscription_uuid' => ['required_without:subscription_id', 'uuid', new ExistsUuid(new Subscription)],
            'amount_due' => ['required', 'numeric', 'min:0'],
            'status' => ['required', 'string'],
            'due_date' => ['required', 'date'],
        ];
    }
}
