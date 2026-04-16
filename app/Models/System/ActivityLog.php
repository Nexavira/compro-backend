<?php

namespace App\Models\System;

use App\Models\BaseModel;

class ActivityLog extends BaseModel
{
    protected $table = 'sys_activity_logs';

    public function casts(): array
    {
        return array_merge(parent::casts(), [
            'properties' => 'json',
        ]);
    }

    public function subject()
    {
        return $this->morphTo();
    }

    public function causer()
    {
        return $this->morphTo();
    }

    public function tenant()
    {
        return $this->belongsTo(\App\Models\Tenant\Tenant::class);
    }
}
