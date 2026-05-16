<?php

namespace App\Models\Transaction;

use App\Models\BaseModel;
use App\Models\System\File;
use App\Models\Tenant\Tenant;

class Payment extends BaseModel
{
    protected $table = 'trx_payments';

    public function subscription()
    {
        return $this->belongsTo(Subscription::class);
    }

    public function proofOfPayment()
    {
        return $this->belongsTo(File::class, 'proof_of_payment_id');
    }

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }
}
