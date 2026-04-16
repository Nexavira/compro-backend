<?php

namespace App\Models\CMS;

use App\Models\BaseModel;

class Post extends BaseModel
{
    protected $table = 'cms_posts';

    public function casts(): array
    {
        return array_merge(parent::casts(), [
            'metadata' => 'json',
        ]);
    }

    public function tenant()
    {
        return $this->belongsTo(\App\Models\Tenant\Tenant::class);
    }
}
