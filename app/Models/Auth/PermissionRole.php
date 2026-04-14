<?php

namespace App\Models\Auth;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

class PermissionRole extends Model
{
    use HasFactory, Notifiable, HasApiTokens;

    protected $table = 'auth_permission_role';
    public $timestamps = false;
    public $incrementing = false;

    protected $fillable = [
        'role_id',
        'permission_id'
    ];

    protected $hidden = 
    [   
        'role_id',
        'permission_id',
    ];

    public function role()
    {
        return $this->belongsTo(Role::class, 'role_id', 'id');
    }

    public function permission()
    {
        return $this->belongsTo(Permission::class, 'permission_id', 'id');
    }
}
