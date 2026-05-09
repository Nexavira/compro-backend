<?php

namespace App\Models\Tenant;

use App\Models\BaseModel;
use App\Models\System\File;

class Tenant extends BaseModel
{
    protected $table = 'tnt_tenants';

    protected $fillable = [
        'name',
        'slug',
        'tenant_category_id',
        'custom_domain',
        'logo_id',
        'favicon_id',
        'theme_mode',
    ];

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

    public static function resolveFromRequest($host)
    {
        // 1. Try to find by custom domain first
        $tenant = self::where('custom_domain', $host)->first();

        // 2. If not found, try to find by slug (subdomain)
        if (!$tenant && str_ends_with($host, '.nexavira.test')) {
            $slug = str_replace('.nexavira.test', '', $host);
            $tenant = self::where('slug', $slug)->first();
        }

        return $tenant;
    }
}
