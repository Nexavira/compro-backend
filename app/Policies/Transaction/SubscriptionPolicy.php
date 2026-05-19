<?php

namespace App\Policies\Transaction;

use App\Models\Auth\User;
use App\Models\Transaction\Subscription;
use App\Policies\BasePolicy;

class SubscriptionPolicy extends BasePolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('admin_transaction_subscription_view');
    }

    public function view(User $user): bool
    { 
        return $user->can('admin_transaction_subscription_view');
    }

    public function create(User $user): bool  
    { 
        return $user->can('admin_transaction_subscription_create'); 
    }
    
    public function update(User $user, Subscription $model): bool
    { 
        if ($this->isMasterRecord($model)) return false;
        return $user->can('admin_transaction_subscription_edit');
    }
    
    public function delete(User $user, Subscription $model): bool
    { 
        if ($this->isMasterRecord($model)) return false;
        return $user->can('admin_transaction_subscription_delete');
    }
}