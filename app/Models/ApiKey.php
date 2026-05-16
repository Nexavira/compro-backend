<?php

namespace App\Models;

use App\Models\Tenant\Tenant;

class ApiKey extends BaseModel
{
    protected $table = 'sys_api_keys';

    public function casts(): array
    {
        return array_merge(parent::casts(), [
            'permissions' => 'json',
        ]);
    }

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }
}
