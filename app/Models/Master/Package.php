<?php

namespace App\Models\Master;

use App\Models\BaseModel;
use App\Models\Transaction\Subscription;
use Illuminate\Database\Eloquent\Casts\AsArrayObject;

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

    protected $casts = [
        'features' => AsArrayObject::class,
    ];

    public function subscriptions()
    {
        return $this->hasMany(Subscription::class);
    }
}
