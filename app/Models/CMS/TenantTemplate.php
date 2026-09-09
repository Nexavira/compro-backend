<?php

namespace App\Models\CMS;

use App\Models\BaseModel;
use App\Models\Cms\GlobalTemplate;
use App\Models\Tenant\Tenant;

class TenantTemplate extends BaseModel
{
    protected $table = 'cms_tenant_templates';

    public function casts(): array
    {
        return array_merge(parent::casts(), [
            'template_settings' => 'jsonb',
        ]);
    }

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    public function globalTemplate()
    {
        return $this->belongsTo(GlobalTemplate::class);
    }

    public function pages()
    {
        return $this->hasMany(TenantPage::class, 'tenant_template_id', 'id');
    }
}
