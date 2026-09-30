<?php

namespace Tests\Unit\Controllers\Tenant\Cms;

use App\Http\Controllers\Api\V1\Tenant\Cms\TenantTemplateController;
use App\Http\Requests\Api\V1\Tenant\Cms\TenantTemplate\GetTenantTemplateRequest;
use App\Models\Cms\GlobalTemplate;
use App\Models\Cms\TenantTemplate;
use App\Models\Tenant\Tenant;
use App\Models\Tenant\TenantCategory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Routing\Redirector;
use Tests\TestCase;

class TenantTemplateControllerUnitTest extends TestCase
{
    use RefreshDatabase;

    protected TenantTemplateController $controller;
    protected TenantCategory $category;
    protected Tenant $tenant;
    protected GlobalTemplate $globalTemplate;
    protected TenantTemplate $tenantTemplate;

    protected function setUp(): void
    {
        parent::setUp();

        $this->controller = new TenantTemplateController();

        $this->category = TenantCategory::create([
            'name' => 'General Category',
            'code' => 'GEN',
            'description' => 'General category',
        ]);

        $this->tenant = Tenant::create([
            'name' => 'Unit Tenant',
            'slug' => 'unit-tenant',
            'tenant_category_id' => $this->category->id,
            'is_active' => 1,
            'is_suspended' => 0,
        ]);

        $this->globalTemplate = GlobalTemplate::create([
            'title' => 'Global Template',
            'slug' => 'global-template',
            'tier' => 'basic',
            'tenant_category_id' => $this->category->id,
            'is_active' => 1,
        ]);

        $this->tenantTemplate = TenantTemplate::create([
            'tenant_id' => $this->tenant->id,
            'global_template_id' => $this->globalTemplate->id,
            'template_settings' => ['theme' => 'light'],
            'is_active' => 1,
        ]);
    }

    /**
     * Test direct get method call on Tenant TenantTemplateController fetching list.
     */
    public function test_controller_get_tenant_templates_returns_json_response(): void
    {
        $payload = [
            'tenant_slug' => $this->tenant->slug,
        ];
        $request = GetTenantTemplateRequest::create("/api/v1/t/{$this->tenant->slug}/cms/tenant-template", 'GET', $payload);
        $request->setContainer($this->app);
        $request->setRedirector($this->app->make(Redirector::class));
        $request->validateResolved();

        $response = $this->controller->get($request, $this->tenant->slug);

        $this->assertEquals(200, $response->getStatusCode());
        $responseData = $response->getData(true);
        $this->assertTrue($responseData['success']);
        $this->assertEquals('Tenant Template successfully fetched', $responseData['message']);
        $this->assertIsArray($responseData['data']);
        $this->assertCount(1, $responseData['data']);
    }

    /**
     * Test direct get method call with uuid on Tenant TenantTemplateController.
     */
    public function test_controller_get_specific_tenant_template_returns_json_response(): void
    {
        $payload = [
            'tenant_slug' => $this->tenant->slug,
            'tenant_template_uuid' => $this->tenantTemplate->uuid,
        ];
        $request = GetTenantTemplateRequest::create("/api/v1/t/{$this->tenant->slug}/cms/tenant-template/{$this->tenantTemplate->uuid}", 'GET', $payload);
        $request->setContainer($this->app);
        $request->setRedirector($this->app->make(Redirector::class));
        $request->validateResolved();

        $response = $this->controller->get($request, $this->tenant->slug, $this->tenantTemplate->uuid);

        $this->assertEquals(200, $response->getStatusCode());
        $responseData = $response->getData(true);
        $this->assertTrue($responseData['success']);
        $this->assertEquals('Tenant Template successfully fetched', $responseData['message']);
        $this->assertEquals($this->tenantTemplate->uuid, $responseData['data']['uuid']);
    }
}
