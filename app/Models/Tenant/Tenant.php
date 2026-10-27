<?php

namespace App\Models\Tenant;

use App\Models\BaseModel;
use App\Models\System\File;
use App\Models\CMS\GlobalTemplate;
use App\Models\CMS\TenantTemplate;
use App\Models\Auth\User;
use App\Models\CMS\TenantTemplatePage;
use Illuminate\Database\Eloquent\Casts\AsArrayObject;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Tenant extends BaseModel
{
    protected $table = 'tnt_tenants';

    protected $fillable = [];

    public function casts(): array
    {
        return array_merge(parent::casts(), [
            'settings' => AsArrayObject::class,
        ]);
    }

    public function tenantCategory(): BelongsTo
    {
        return $this->belongsTo(TenantCategory::class, 'tenant_category_id', 'id');
    }

    public function logo(): BelongsTo
    {
        return $this->belongsTo(File::class, 'logo_id', 'id');
    }

    public function favicon(): BelongsTo
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
        $tenant = self::where('custom_domain', $host)->first();

        if (!$tenant && str_ends_with($host, '.nexavira.test')) {
            $slug = str_replace('.nexavira.test', '', $host);
            $tenant = self::where('slug', $slug)->first();
        }

        return $tenant;
    }

    public function globalTemplate(): BelongsTo
    {
        return $this->belongsTo(GlobalTemplate::class, 'global_template_id', 'id');
    }

    public function tenantTemplates(): HasMany
    {
        return $this->hasMany(TenantTemplate::class, 'tenant_id', 'id');
    }

    public function tenantTemplate(): HasOne
    {
        return $this->hasOne(TenantTemplate::class, 'tenant_id', 'id')->where('active_template', true);
    }

    public function pages(): HasMany
    {
        return $this->hasMany(TenantTemplatePage::class, 'tenant_id', 'id');
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'tnt_tenant_user', 'tenant_id', 'user_id')
            ->withPivot('role')
            ->withTimestamps();
    }
}
