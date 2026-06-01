<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GlobalTemplate extends BaseModel
{
    protected $table = 'cms_global_templates';

    protected $hidden = [
        'id',
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
        return $this->belongsTo(\App\Models\Tenant\TenantCategory::class, 'tenant_category_id', 'id');
    }

    public function pages()
    {
        return $this->hasMany(GlobalTemplatePage::class, 'global_template_id', 'id');
    }
}
