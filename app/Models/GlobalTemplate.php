<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GlobalTemplate extends BaseModel
{
    protected $table = 'cms_global_templates';

    public function casts(): array
    {
        return array_merge(parent::casts(), [
            'content_blocks' => 'json',
        ]);
    }
}
