<?php

namespace App\Models\CMS;

use App\Models\BaseModel;
use App\Models\CMS\GlobalTemplate;
use App\Models\Tenant\Tenant;
use Illuminate\Database\Eloquent\Casts\AsArrayObject;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TenantTemplate extends BaseModel
{
    protected $table = 'cms_tenant_templates';

    public function casts(): array
    {
        return array_merge(parent::casts(), [
            'template_settings' => AsArrayObject::class,
            'active_template' => 'boolean',
        ]);
    }

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    public function globalTemplate(): BelongsTo
    {
        return $this->belongsTo(GlobalTemplate::class);
    }

    public function pages(): HasMany
    {
        return $this->hasMany(TenantTemplatePage::class, 'tenant_template_id', 'id');
    }
}
