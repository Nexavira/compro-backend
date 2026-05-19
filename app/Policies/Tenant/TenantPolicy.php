<?php

namespace App\Policies\Tenant;

use App\Models\Auth\User;
use App\Models\Tenant\Tenant;
use App\Policies\BasePolicy;

class TenantPolicy extends BasePolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('admin_tenant_tenant_view');
    }

    public function view(User $user): bool
    { 
        return $user->can('admin_tenant_tenant_view');
    }

    public function create(User $user): bool  
    { 
        return $user->can('admin_tenant_tenant_create'); 
    }
    
    public function update(User $user, Tenant $tenant): bool
    { 
        if ($tenant->name === 'Nexavira') return false;
        return $user->can('admin_tenant_tenant_edit');
    }
    
    public function delete(User $user, Tenant $tenant): bool
    { 
        if ($tenant->name === 'Nexavira') return false;
        return $user->can('admin_tenant_tenant_delete');
    }
}