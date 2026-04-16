<?php

namespace App\Models\CMS;

use App\Models\BaseModel;

class Page extends BaseModel
{
    protected $table = 'cms_pages';

    public function casts(): array
    {
        return array_merge(parent::casts(), [
            'content_blocks' => 'json',
        ]);
    }

    public function tenant()
    {
        return $this->belongsTo(\App\Models\Tenant\Tenant::class);
    }
}
