<?php

namespace App\Models\Tenant;

use App\Models\ApiKey;
use App\Models\BaseModel;
use App\Models\System\File;

class Tenant extends BaseModel
{
    protected $table = 'tnt_tenants';

    protected $fillable = [];

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

    public function getActiveApiKey()
    {
        return $this->hasOne(ApiKey::class, 'tenant_id')->where('is_active', 1);
    }

    public static function resolveFromRequest($host)
    {
        $tenant = self::where('custom_domain', $host)->first();

        if (!$tenant && str_ends_with($host, '.nexavira.test')) {
            $slug = str_replace('.nexavira.test', '', $host);
            $tenant = self::where('slug', $slug)->first();
        }

        return $tenant;
    }
}
