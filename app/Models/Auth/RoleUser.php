<?php

namespace App\Models\Auth;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

class RoleUser extends Model
{
    use HasFactory, Notifiable, HasApiTokens;

    protected $table = 'auth_role_user';
    public $timestamps = false;
    public $incrementing = false;

    protected $fillable = [
        'user_id',
        'role_id'
    ];

    protected $hidden = 
    [   
        'user_id',
        'role_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function role()
    {
        return $this->belongsTo(Role::class, 'role_id', 'id');
    }
}