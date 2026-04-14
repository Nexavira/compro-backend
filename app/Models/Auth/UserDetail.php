<?php

namespace App\Models\Auth;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class UserDetail extends BaseModel
{
    protected $table = 'auth_user_details'; 

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
