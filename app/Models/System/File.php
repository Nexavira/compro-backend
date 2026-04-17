<?php

namespace App\Models\System;

use App\Models\BaseModel;
use Illuminate\Support\Facades\Storage;

class File extends BaseModel
{
    protected $table = 'sys_files';

    protected $guarded = ['id'];

    public function getUrlAttribute()
    {
        return Storage::disk($this->storage_disk)->url($this->file_path);
    }
}
