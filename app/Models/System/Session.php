<?php

namespace App\Models\System;

use App\Models\BaseModel;

class Session extends BaseModel
{
    protected $table = 'sessions';

    public function user()
    {
        return $this->belongsTo(\App\Models\Auth\User::class);
    }

    public function tenant()
    {
        return $this->belongsTo(\App\Models\Tenant\Tenant::class);
    }
}
