<?php

namespace App\Policies\Transaction;

use App\Models\Auth\User;
use App\Models\Transaction\Payment;
use App\Policies\BasePolicy;

class PaymentPolicy extends BasePolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('admin_transaction_payment_view');
    }

    public function view(User $user): bool
    { 
        return $user->can('admin_transaction_payment_view');
    }

    public function create(User $user): bool  
    { 
        return $user->can('admin_transaction_payment_create'); 
    }
    
    public function update(User $user, Payment $model): bool
    { 
        if ($this->isMasterRecord($model)) return false;
        return $user->can('admin_cms_payment_payment_verification') || $user->roleUser?->role?->code === 'master_admin';
    }
    
    public function delete(User $user, Payment $model): bool
    { 
        if ($this->isMasterRecord($model)) return false;
        return $user->can('admin_transaction_payment_delete');
    }
}