<?php

namespace App\Policies\CMS;

use App\Models\Auth\User;
use App\Models\CMS\TenantTemplate;
use App\Models\Tenant\Tenant;
use App\Policies\BasePolicy;

class TenantTemplatePolicy extends BasePolicy
{
    public function viewAny(User $user, ?Tenant $tenant = null): bool
    {
        if ($tenant) {
            return $user->canAccessTenant($tenant);
        }

        return true;
    }

    public function view(User $user, TenantTemplate $tenantTemplate): bool
    {
        if ($tenantTemplate->tenant) {
            return $user->canAccessTenant($tenantTemplate->tenant);
        }

        return true;
    }

    public function create(User $user, ?Tenant $tenant = null): bool
    {
        if ($tenant) {
            return $user->canAccessTenant($tenant);
        }

        return true;
    }

    public function update(User $user, TenantTemplate $tenantTemplate): bool
    {
        if ($tenantTemplate->tenant) {
            return $user->canAccessTenant($tenantTemplate->tenant);
        }

        return true;
    }

    public function delete(User $user, TenantTemplate $tenantTemplate): bool
    {
        if ($tenantTemplate->tenant) {
            return $user->canAccessTenant($tenantTemplate->tenant);
        }

        return true;
    }
}
