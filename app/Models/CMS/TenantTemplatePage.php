<?php

namespace App\Models\CMS;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Casts\AsArrayObject;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TenantTemplatePage extends BaseModel
{
    protected $table = 'cms_tenant_template_pages';

    public function casts(): array
    {
        return array_merge(parent::casts(), [
            'template_data' => AsArrayObject::class
        ]);
    }

    public function tenantTemplate(): BelongsTo
    {
        return $this->belongsTo(TenantTemplate::class);
    }
}
