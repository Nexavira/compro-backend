<?php

namespace App\Models\System;

use App\Models\Auth\User;
use App\Models\BaseModel;
use App\Models\Tenant\Tenant;

class Session extends BaseModel
{
    protected $table = 'sessions';

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }
}
