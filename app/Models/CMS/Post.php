<?php

namespace App\Models\CMS;

use App\Models\BaseModel;
use App\Models\Tenant\Tenant;
use Illuminate\Database\Eloquent\Casts\AsArrayObject;

class Post extends BaseModel
{
    protected $table = 'cms_posts';

    public function casts(): array
    {
        return array_merge(parent::casts(), [
            'metadata' => AsArrayObject::class,
        ]);
    }

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }
}
