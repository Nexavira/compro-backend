<?php

namespace App\Models\Transaction;

use App\Models\BaseModel;

class Payment extends BaseModel
{
    protected $table = 'trx_payments';

    public function subscription()
    {
        return $this->belongsTo(\App\Models\Tenant\Subscription::class);
    }

    public function proofOfPayment()
    {
        return $this->belongsTo(\App\Models\System\File::class, 'proof_of_payment_id');
    }
}
