<?php

namespace App\Models\Master;

use App\Models\BaseModel;
use App\Models\Transaction\Subscription;

class Package extends BaseModel
{
    protected $table = 'mst_packages';

    protected $hidden = [
        'id',
        'is_active',
        'version',
        'created_by',
        'updated_by',
        'deleted_by',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public function subscriptions()
    {
        return $this->hasMany(Subscription::class);
    }
}
