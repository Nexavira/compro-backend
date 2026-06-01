<?php

namespace App\Models;

class GlobalTemplatePage extends BaseModel
{
    protected $table = 'cms_global_template_pages';

    protected $hidden = [
        'id',
        'created_by',
        'updated_by',
        'deleted_by',
        'created_at',
        'updated_at',
        'deleted_at',
        'version'
    ];

    public function casts(): array
    {
        return array_merge(parent::casts(), [
            'content_blocks' => 'array',
            'meta' => 'array',
        ]);
    }

    public function globalTemplate()
    {
        return $this->belongsTo(GlobalTemplate::class, 'global_template_id', 'id');
    }
}
