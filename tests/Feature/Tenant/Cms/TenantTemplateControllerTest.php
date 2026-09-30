<?php

namespace Tests\Feature\Tenant\Cms;

use App\Models\Auth\Role;
use App\Models\Auth\RoleUser;
use App\Models\Auth\User;
use App\Models\Cms\GlobalTemplate;
use App\Models\Cms\TenantTemplate;
use App\Models\Tenant\Tenant;
use App\Models\Tenant\TenantCategory;
use App\Models\Tenant\TenantUser;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Laravel\Passport\Passport;
use Tests\TestCase;

class TenantTemplateControllerTest extends TestCase
{
    use RefreshDatabase;

    protected TenantCategory $category;
    protected Tenant $tenantA;
    protected Tenant $tenantB;
    protected Tenant $suspendedTenant;
    protected User $userA;
    protected User $userB;
    protected User $masterAdmin;
    protected Role $masterAdminRole;
    protected Role $regularRole;
    protected GlobalTemplate $globalTemplate;
    protected TenantTemplate $tenantTemplateA;
    protected TenantTemplate $tenantTemplateB;

    protected function setUp(): void
    {
        parent::setUp();

        $this->category = TenantCategory::create([
            'name' => 'Technology',
            'code' => 'TECH',
            'description' => 'Technology category',
        ]);

        $this->tenantA = Tenant::create([
            'name' => 'Tenant Alpha',
            'slug' => 'tenant-alpha',
            'tenant_category_id' => $this->category->id,
            'is_active' => 1,
            'is_suspended' => 0,
        ]);

        $this->tenantB = Tenant::create([
            'name' => 'Tenant Beta',
            'slug' => 'tenant-beta',
            'tenant_category_id' => $this->category->id,
            'is_active' => 1,
            'is_suspended' => 0,
        ]);

        $this->suspendedTenant = Tenant::create([
            'name' => 'Suspended Tenant',
            'slug' => 'suspended-tenant',
            'tenant_category_id' => $this->category->id,
            'is_active' => 1,
            'is_suspended' => 1,
        ]);

        $this->masterAdminRole = Role::create([
            'name' => 'Master Admin',
            'code' => 'master_admin',
            'guard_name' => 'admin',
        ]);

        $this->regularRole = Role::create([
            'name' => 'Tenant Member',
            'code' => 'tenant_member',
            'guard_name' => 'api',
        ]);

        $this->userA = User::create([
            'email' => 'user_a@example.com',
            'password' => bcrypt('password'),
            'is_active' => 1,
        ]);
        RoleUser::create(['user_id' => $this->userA->id, 'role_id' => $this->regularRole->id]);
        TenantUser::create(['user_id' => $this->userA->id, 'tenant_id' => $this->tenantA->id]);

        $this->userB = User::create([
            'email' => 'user_b@example.com',
            'password' => bcrypt('password'),
            'is_active' => 1,
        ]);
        RoleUser::create(['user_id' => $this->userB->id, 'role_id' => $this->regularRole->id]);
        TenantUser::create(['user_id' => $this->userB->id, 'tenant_id' => $this->tenantB->id]);

        $this->masterAdmin = User::create([
            'email' => 'master_admin@example.com',
            'password' => bcrypt('password'),
            'is_active' => 1,
        ]);
        RoleUser::create(['user_id' => $this->masterAdmin->id, 'role_id' => 1]); // ID 1 master_admin

        $this->globalTemplate = GlobalTemplate::create([
            'title' => 'Global Corporate Template',
            'slug' => 'global-corporate-template',
            'tier' => 'basic',
            'tenant_category_id' => $this->category->id,
            'is_active' => 1,
        ]);

        $this->tenantTemplateA = TenantTemplate::create([
            'tenant_id' => $this->tenantA->id,
            'global_template_id' => $this->globalTemplate->id,
            'template_settings' => ['theme' => 'light', 'primary_color' => '#0000ff'],
            'is_active' => 1,
        ]);

        $this->tenantTemplateB = TenantTemplate::create([
            'tenant_id' => $this->tenantB->id,
            'global_template_id' => $this->globalTemplate->id,
            'template_settings' => ['theme' => 'dark', 'primary_color' => '#ff0000'],
            'is_active' => 1,
        ]);
    }

    /**
     * Test unauthenticated request returns 401 Unauthorized.
     */
    public function test_unauthenticated_request_returns_401(): void
    {
        $response = $this->getJson("/api/v1/t/{$this->tenantA->slug}/cms/tenant-template");

        $response->assertStatus(401);
    }

    /**
     * Test authenticated user accessing a different tenant returns 403 Forbidden.
     */
    public function test_authenticated_user_accessing_different_tenant_returns_403(): void
    {
        Passport::actingAs($this->userA, ['*'], 'api');

        // User A tries to access Tenant B's endpoint
        $response = $this->getJson("/api/v1/t/{$this->tenantB->slug}/cms/tenant-template");

        $response->assertStatus(403)
            ->assertJson([
                'success' => false,
                'message' => 'Unauthorized access to this tenant.',
            ]);
    }

    /**
     * Test accessing non-existent tenant slug returns 404 Not Found.
     */
    public function test_accessing_non_existent_tenant_slug_returns_404(): void
    {
        Passport::actingAs($this->userA, ['*'], 'api');

        $response = $this->getJson("/api/v1/t/non-existent-tenant-slug/cms/tenant-template");

        $response->assertStatus(404)
            ->assertJson([
                'success' => false,
                'message' => 'Tenant not found.',
            ]);
    }

    /**
     * Test accessing suspended tenant returns 403 Forbidden.
     */
    public function test_accessing_suspended_tenant_returns_403(): void
    {
        // Associate userA with suspended tenant
        TenantUser::create(['user_id' => $this->userA->id, 'tenant_id' => $this->suspendedTenant->id]);

        Passport::actingAs($this->userA, ['*'], 'api');

        $response = $this->getJson("/api/v1/t/{$this->suspendedTenant->slug}/cms/tenant-template");

        $response->assertStatus(403)
            ->assertJson([
                'success' => false,
                'message' => 'Tenant account has been suspended.',
            ]);
    }

    /**
     * Test master admin can access any tenant.
     */
    public function test_master_admin_can_access_any_tenant(): void
    {
        Passport::actingAs($this->masterAdmin, ['*'], 'api');

        $response = $this->getJson("/api/v1/t/{$this->tenantA->slug}/cms/tenant-template");

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
            ]);
    }

    /**
     * Test authorized user gets tenant template list successfully.
     */
    public function test_authorized_user_gets_tenant_template_list_successfully(): void
    {
        Passport::actingAs($this->userA, ['*'], 'api');

        $response = $this->getJson("/api/v1/t/{$this->tenantA->slug}/cms/tenant-template");

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'Tenant Template successfully fetched',
            ])
            ->assertJsonCount(1, 'data');
    }

    /**
     * Test authorized user gets specific tenant template by uuid successfully.
     */
    public function test_authorized_user_gets_specific_tenant_template_by_uuid_successfully(): void
    {
        Passport::actingAs($this->userA, ['*'], 'api');

        $response = $this->getJson("/api/v1/t/{$this->tenantA->slug}/cms/tenant-template/{$this->tenantTemplateA->uuid}");

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'Tenant Template successfully fetched',
                'data' => [
                    'uuid' => $this->tenantTemplateA->uuid,
                ],
            ]);
    }

    /**
     * Test authorized user cannot access other tenant template uuid through their tenant url.
     */
    public function test_authorized_user_cannot_access_other_tenant_template_uuid(): void
    {
        Passport::actingAs($this->userA, ['*'], 'api');

        // User A asks for Tenant B's template UUID inside Tenant A's endpoint
        $response = $this->getJson("/api/v1/t/{$this->tenantA->slug}/cms/tenant-template/{$this->tenantTemplateB->uuid}");

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'data' => null,
            ]);
    }

    /**
     * Test pagination works correctly.
     */
    public function test_pagination_works_correctly(): void
    {
        Passport::actingAs($this->userA, ['*'], 'api');

        $response = $this->getJson("/api/v1/t/{$this->tenantA->slug}/cms/tenant-template?with_pagination=1&per_page=5&page=1");

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
            ])
            ->assertJsonStructure([
                'success',
                'message',
                'data',
                'pagination' => ['data_per_page', 'total_page', 'total_data'],
            ]);
    }
}
