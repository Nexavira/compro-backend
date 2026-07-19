<?php

namespace App\Models\Transaction;

use App\Models\BaseModel;
use App\Models\Master\Package;
use App\Models\Tenant\Tenant;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Subscription extends BaseModel
{
    protected $table = 'tnt_subscriptions';

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    public function package()
    {
        return $this->belongsTo(Package::class);
    }

    public function casts(): array
    {
        return array_merge(parent::casts(), [

        ]);
    }

    protected function nextBillingDate(): Attribute
    {
        return Attribute::make(
            get: fn($value) => $value ? \Carbon\Carbon::parse($value) : null,
            set: fn($value) => $value instanceof \Carbon\Carbon ? $value->format('Y-m-d') : ($value ? \Carbon\Carbon::parse($value)->format('Y-m-d') : null),
        );
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }
}
