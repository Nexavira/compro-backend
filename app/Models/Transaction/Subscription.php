<?php

namespace App\Models\Transaction;

use App\Models\BaseModel;
use App\Models\Master\Package;
use App\Models\Tenant\Tenant;

class Subscription extends BaseModel
{
    protected $table = 'tnt_subscriptions';

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    public function package()
    {
        return $this->belongsTo(Package::class);
    }

    public function casts(): array
    {
        return array_merge(parent::casts(), [
            'next_billing_date' => 'date',
        ]);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }
}
