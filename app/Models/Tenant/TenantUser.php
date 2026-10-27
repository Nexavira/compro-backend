<?php

namespace App\Models\Tenant;

use App\Models\Auth\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

class TenantUser extends Model
{
    use HasFactory, Notifiable, HasApiTokens;

    protected $table = 'tnt_tenant_user';
    public $timestamps = false;
    public $incrementing = false;

    protected $fillable = [
        'tenant_id',
        'user_id'
    ];

    protected $hidden = [
        'tenant_id',
        'user_id'
    ];

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class, 'tenant_id', 'id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
