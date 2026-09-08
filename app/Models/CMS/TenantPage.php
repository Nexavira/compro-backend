<?php

namespace App\Models\Cms;

use App\Models\BaseModel;


class TenantPage extends BaseModel
{
    protected $table = 'cms_pages';

    public function casts(): array
    {
        return array_merge(parent::casts(), [
            'content_blocks' => 'json',
            'meta' => 'json',
        ]);
    }

    public function tenantTemplate()
    {
        return $this->belongsTo(TenantTemplate::class);
    }
}
