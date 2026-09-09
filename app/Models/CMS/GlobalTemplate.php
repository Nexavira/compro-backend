<?php

namespace App\Models\Cms;

use App\Models\BaseModel;
use App\Models\Tenant\TenantCategory;

class GlobalTemplate extends BaseModel
{
    protected $table = 'cms_global_templates';

    protected $hidden = [
        'id',
        'tenant_category_id',
        'created_by',
        'updated_by',
        'deleted_by',
        'created_at',
        'updated_at',
        'deleted_at',
        'version'
    ];

    public function casts(): array
    {
        return array_merge(parent::casts(), [
            'brand_settings' => 'array',
        ]);
    }
    public function tenantCategory()
    {
        return $this->belongsTo(TenantCategory::class, 'tenant_category_id', 'id');
    }
}
