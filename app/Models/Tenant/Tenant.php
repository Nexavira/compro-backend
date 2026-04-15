<?php

namespace App\Models\Tenant;

use App\Models\BaseModel;
use App\Models\System\File;

class Tenant extends BaseModel
{
    protected $table = 'tnt_tenants';

    public function tenantCategory()
    {
        return $this->belongsTo(TenantCategory::class, 'tenant_category_id', 'id');
    }

    public function logo()
    {
        return $this->belongsTo(File::class, 'logo_id', 'id');
    }

    public function favicon()
    {
        return $this->belongsTo(File::class, 'favicon_id', 'id');
    }

    public function getLogoUrlAttribute()
    {
        return $this->logo?->url;
    }

    public function getFaviconUrlAttribute()
    {
        return $this->favicon?->url;
    }
}
