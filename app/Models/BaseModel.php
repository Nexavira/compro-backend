<?php

namespace App\Models;

use App\Traits\Blameable;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

class BaseModel extends Model
{
    use HasFactory, Notifiable, HasApiTokens, SoftDeletes, Blameable, HasUuids;

    protected $guarded = ['id'];
    protected $dateFormat = 'U';

    protected $hidden = [
        'id',
        'created_by',
        'updated_by',
        'deleted_by',
        'created_at',
        'updated_at',
        'deleted_at',
        'is_active',
        'version'
    ];

    public function casts(): array
    {
        return [
            'created_at' => 'datetime:U',
            'updated_at' => 'datetime:U',
            'deleted_at' => 'datetime:U',
        ];
    }
    
    public function uniqueIds(): array
    {
        return ['uuid'];
    }

    public function getKeyName()
    {
        return 'id';
    }

    public function getRouteKeyName(): string
    {
        return 'uuid';
    }
}