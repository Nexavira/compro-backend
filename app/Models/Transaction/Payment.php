<?php

namespace App\Models\Transaction;

use App\Models\BaseModel;
use App\Models\System\File;
use App\Models\Tenant\Tenant;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Payment extends BaseModel
{
    protected $table = 'trx_payments';

    public function subscription()
    {
        return $this->belongsTo(Subscription::class);
    }

    public function proofOfPayment()
    {
        return $this->belongsTo(File::class, 'proof_of_payment_id');
    }

    public function casts(): array
    {
        return array_merge(parent::casts(), [

        ]);
    }

    protected function dueDate(): Attribute
    {
        return Attribute::make(
            get: fn($value) => $value ? \Carbon\Carbon::parse($value) : null,
            set: fn($value) => $value instanceof \Carbon\Carbon ? $value->format('Y-m-d') : ($value ? \Carbon\Carbon::parse($value)->format('Y-m-d') : null),
        );
    }

    protected function paymentDate(): Attribute
    {
        return Attribute::make(
            get: fn($value) => $value ? \Carbon\Carbon::parse($value) : null,
            set: fn($value) => $value instanceof \Carbon\Carbon ? $value->format('Y-m-d') : ($value ? \Carbon\Carbon::parse($value)->format('Y-m-d') : null),
        );
    }

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }
}
