<?php

namespace Tests\Unit\Policies;

use App\Models\Auth\Role;
use App\Models\Auth\RoleUser;
use App\Models\Auth\User;
use App\Models\Cms\GlobalTemplate;
use App\Models\Cms\TenantTemplate;
use App\Models\Tenant\Tenant;
use App\Models\Tenant\TenantCategory;
use App\Models\Tenant\TenantUser;
use App\Policies\CMS\TenantTemplatePolicy;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TenantTemplatePolicyTest extends TestCase
{
    use RefreshDatabase;

    protected TenantTemplatePolicy $policy;
    protected TenantCategory $category;
    protected Tenant $tenant;
    protected User $authorizedUser;
    protected User $unauthorizedUser;
    protected User $masterAdminUser;
    protected TenantTemplate $tenantTemplate;

    protected function setUp(): void
    {
        parent::setUp();

        $this->policy = new TenantTemplatePolicy();

        $this->category = TenantCategory::create([
            'name' => 'General',
            'code' => 'GEN',
            'description' => 'General',
        ]);

        $this->tenant = Tenant::create([
            'name' => 'Policy Test Tenant',
            'slug' => 'policy-test-tenant',
            'tenant_category_id' => $this->category->id,
            'is_active' => 1,
            'is_suspended' => 0,
        ]);

        $globalTemplate = GlobalTemplate::create([
            'title' => 'Global Policy Template',
            'slug' => 'global-policy-template',
            'tier' => 'basic',
            'tenant_category_id' => $this->category->id,
            'is_active' => 1,
        ]);

        $this->tenantTemplate = TenantTemplate::create([
            'tenant_id' => $this->tenant->id,
            'global_template_id' => $globalTemplate->id,
            'template_settings' => ['theme' => 'light'],
            'is_active' => 1,
        ]);

        $masterRole = Role::create([
            'name' => 'Master Admin',
            'code' => 'master_admin',
            'guard_name' => 'admin',
        ]);

        $memberRole = Role::create([
            'name' => 'Member',
            'code' => 'member',
            'guard_name' => 'api',
        ]);

        $this->authorizedUser = User::create([
            'email' => 'auth_policy@example.com',
            'password' => bcrypt('password'),
            'is_active' => 1,
        ]);
        RoleUser::create(['user_id' => $this->authorizedUser->id, 'role_id' => $memberRole->id]);
        TenantUser::create(['user_id' => $this->authorizedUser->id, 'tenant_id' => $this->tenant->id]);
        $this->authorizedUser->refresh();

        $this->unauthorizedUser = User::create([
            'email' => 'unauth_policy@example.com',
            'password' => bcrypt('password'),
            'is_active' => 1,
        ]);
        RoleUser::create(['user_id' => $this->unauthorizedUser->id, 'role_id' => $memberRole->id]);
        $this->unauthorizedUser->refresh();

        $this->masterAdminUser = User::create([
            'email' => 'master_admin_policy@example.com',
            'password' => bcrypt('password'),
            'is_active' => 1,
        ]);
        RoleUser::create(['user_id' => $this->masterAdminUser->id, 'role_id' => $masterRole->id]);
        $this->masterAdminUser->refresh();
    }

    public function test_view_any_returns_true_for_authorized_user(): void
    {
        $this->assertTrue($this->policy->viewAny($this->authorizedUser, $this->tenant));
    }

    public function test_view_any_returns_false_for_unauthorized_user(): void
    {
        $this->assertFalse($this->policy->viewAny($this->unauthorizedUser, $this->tenant));
    }

    public function test_view_returns_true_for_authorized_user(): void
    {
        $this->assertTrue($this->policy->view($this->authorizedUser, $this->tenantTemplate));
    }

    public function test_view_returns_false_for_unauthorized_user(): void
    {
        $this->assertFalse($this->policy->view($this->unauthorizedUser, $this->tenantTemplate));
    }

    public function test_master_admin_can_view_any_and_view(): void
    {
        $this->assertTrue($this->policy->viewAny($this->masterAdminUser, $this->tenant));
        $this->assertTrue($this->policy->view($this->masterAdminUser, $this->tenantTemplate));
    }
}
