<?php

namespace App\Http\Resources\Api\V1\Dashboard\Transaction\Payment;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class GetPaymentResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->uuid,
            'subscription_id' => $this->subscription ? $this->subscription->uuid : null,
            'amount_due' => $this->amount_due,
            'status' => $this->status,
            'due_date' => $this->due_date,
            'payment_date' => $this->payment_date,
            'amount_paid' => $this->amount_paid,
            'payment_method' => $this->payment_method,
            'payment_proof_url' => $this->paymentProof ? $this->paymentProof->url : null,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
