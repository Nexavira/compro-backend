<?php

namespace App\Http\Resources\Api\V1\Dashboard\Transaction\Subscription;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class GetSubscriptionResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->uuid,
            'package_id' => $this->package ? $this->package->uuid : null,
            'package_name' => $this->package ? $this->package->name : null,
            'status' => $this->status,
            'amount' => $this->amount,
            'start_at' => $this->start_at,
            'end_at' => $this->end_at,
            'trial_end_at' => $this->trial_end_at,
            'next_billing_date' => $this->next_billing_date,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
