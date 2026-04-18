<?php

namespace App\Models\Transaction;

use App\Models\BaseModel;
use App\Models\Tenant\Tenant;

class Subscription extends BaseModel
{
    protected $table = 'tnt_subscriptions';

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }
}
