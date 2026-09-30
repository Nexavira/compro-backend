<?php

namespace Tests\Unit\Middleware;

use App\Http\Middleware\VerifyTenantAccess;
use App\Models\Auth\Role;
use App\Models\Auth\RoleUser;
use App\Models\Auth\User;
use App\Models\Tenant\Tenant;
use App\Models\Tenant\TenantCategory;
use App\Models\Tenant\TenantUser;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class VerifyTenantAccessTest extends TestCase
{
    use RefreshDatabase;

    protected VerifyTenantAccess $middleware;
    protected TenantCategory $category;
    protected Tenant $tenant;
    protected Tenant $suspendedTenant;
    protected User $user;
    protected User $unauthorizedUser;

    protected function setUp(): void
    {
        parent::setUp();

        $this->middleware = new VerifyTenantAccess();

        $this->category = TenantCategory::create([
            'name' => 'General Category',
            'code' => 'GEN',
            'description' => 'General',
        ]);

        $this->tenant = Tenant::create([
            'name' => 'Active Tenant',
            'slug' => 'active-tenant',
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

        Role::create([
            'name' => 'Master Admin',
            'code' => 'master_admin',
            'guard_name' => 'admin',
        ]);

        $role = Role::create([
            'name' => 'Member',
            'code' => 'member',
            'guard_name' => 'api',
        ]);

        $this->user = User::create([
            'email' => 'user@example.com',
            'password' => bcrypt('password'),
            'is_active' => 1,
        ]);
        RoleUser::create(['user_id' => $this->user->id, 'role_id' => $role->id]);
        TenantUser::create(['user_id' => $this->user->id, 'tenant_id' => $this->tenant->id]);
        $this->user->refresh();

        $this->unauthorizedUser = User::create([
            'email' => 'unauth@example.com',
            'password' => bcrypt('password'),
            'is_active' => 1,
        ]);
        RoleUser::create(['user_id' => $this->unauthorizedUser->id, 'role_id' => $role->id]);
        $this->unauthorizedUser->refresh();
    }

    /**
     * Test returns 400 when tenant_slug parameter is missing.
     */
    public function test_returns_400_when_tenant_slug_is_missing(): void
    {
        $request = Request::create('/api/v1/t//cms/tenant-template', 'GET');

        $response = $this->middleware->handle($request, function () {
            return response()->json(['success' => true]);
        });

        $this->assertEquals(400, $response->getStatusCode());
        $data = json_decode($response->getContent(), true);
        $this->assertFalse($data['success']);
        $this->assertEquals('Tenant slug is required.', $data['message']);
    }

    /**
     * Test returns 404 when tenant is not found.
     */
    public function test_returns_404_when_tenant_is_not_found(): void
    {
        $request = Request::create('/api/v1/t/non-existent/cms/tenant-template', 'GET');
        $request->setRouteResolver(function () {
            $route = new \Illuminate\Routing\Route('GET', 'api/v1/t/{tenant_slug}/cms/tenant-template', []);
            $route->parameters = ['tenant_slug' => 'non-existent'];
            return $route;
        });

        $response = $this->middleware->handle($request, function () {
            return response()->json(['success' => true]);
        });

        $this->assertEquals(404, $response->getStatusCode());
        $data = json_decode($response->getContent(), true);
        $this->assertFalse($data['success']);
        $this->assertEquals('Tenant not found.', $data['message']);
    }

    /**
     * Test returns 403 when tenant is suspended.
     */
    public function test_returns_403_when_tenant_is_suspended(): void
    {
        $request = Request::create("/api/v1/t/{$this->suspendedTenant->slug}/cms/tenant-template", 'GET');
        $request->setRouteResolver(function () {
            $route = new \Illuminate\Routing\Route('GET', 'api/v1/t/{tenant_slug}/cms/tenant-template', []);
            $route->parameters = ['tenant_slug' => $this->suspendedTenant->slug];
            return $route;
        });
        $request->setUserResolver(fn () => $this->user);

        $response = $this->middleware->handle($request, function () {
            return response()->json(['success' => true]);
        });

        $this->assertEquals(403, $response->getStatusCode());
        $data = json_decode($response->getContent(), true);
        $this->assertFalse($data['success']);
        $this->assertEquals('Tenant account has been suspended.', $data['message']);
    }

    /**
     * Test returns 403 when user is not authorized for tenant.
     */
    public function test_returns_403_when_user_is_not_authorized_for_tenant(): void
    {
        $request = Request::create("/api/v1/t/{$this->tenant->slug}/cms/tenant-template", 'GET');
        $request->setRouteResolver(function () {
            $route = new \Illuminate\Routing\Route('GET', 'api/v1/t/{tenant_slug}/cms/tenant-template', []);
            $route->parameters = ['tenant_slug' => $this->tenant->slug];
            return $route;
        });
        $request->setUserResolver(fn () => $this->unauthorizedUser);

        $response = $this->middleware->handle($request, function () {
            return response()->json(['success' => true]);
        });

        $this->assertEquals(403, $response->getStatusCode());
        $data = json_decode($response->getContent(), true);
        $this->assertFalse($data['success']);
        $this->assertEquals('Unauthorized access to this tenant.', $data['message']);
    }

    /**
     * Test passes through and binds tenant instance when user is authorized.
     */
    public function test_passes_through_when_user_is_authorized(): void
    {
        $request = Request::create("/api/v1/t/{$this->tenant->slug}/cms/tenant-template", 'GET');
        $request->setRouteResolver(function () {
            $route = new \Illuminate\Routing\Route('GET', 'api/v1/t/{tenant_slug}/cms/tenant-template', []);
            $route->parameters = ['tenant_slug' => $this->tenant->slug];
            return $route;
        });
        $request->setUserResolver(fn () => $this->user);

        $response = $this->middleware->handle($request, function () {
            return response()->json(['success' => true, 'bound_tenant' => app('tenant')->id]);
        });

        $this->assertEquals(200, $response->getStatusCode());
        $data = json_decode($response->getContent(), true);
        $this->assertTrue($data['success']);
        $this->assertEquals($this->tenant->id, $data['bound_tenant']);
    }
}
