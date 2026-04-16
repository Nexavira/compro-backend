<?php

namespace App\Models\Tenant;

use App\Models\BaseModel;

class Subscription extends BaseModel
{
    protected $table = 'tnt_subscriptions';

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }
}
