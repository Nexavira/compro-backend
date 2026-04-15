<?php

namespace App\Models\Tenant;

use App\Models\BaseModel;

class TenantCategory extends BaseModel
{
    protected $table = 'tnt_tenant_categories';

    public function tenants()
    {
        return $this->hasMany(Tenant::class, 'tenant_category_id', 'id');
    }
}
