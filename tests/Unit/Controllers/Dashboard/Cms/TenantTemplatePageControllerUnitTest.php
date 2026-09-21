<?php

namespace Tests\Unit\Controllers\Dashboard\Cms;

use App\Http\Controllers\Api\V1\Dashboard\Cms\TenantTemplatePageController;
use App\Http\Requests\Api\V1\Dashboard\Cms\TenantTemplatePage\GetTenantTemplatePageRequest;
use App\Http\Requests\Api\V1\Dashboard\Cms\TenantTemplatePage\StoreTenantTemplatePageRequest;
use App\Http\Requests\Api\V1\Dashboard\Cms\TenantTemplatePage\UpdateTenantTemplatePageRequest;
use App\Models\Cms\GlobalTemplate;
use App\Models\Cms\TenantTemplate;
use App\Models\CMS\TenantTemplatePage;
use App\Models\Tenant\Tenant;
use App\Models\Tenant\TenantCategory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Routing\Redirector;
use Tests\TestCase;

class TenantTemplatePageControllerUnitTest extends TestCase
{
    use RefreshDatabase;

    protected TenantTemplatePageController $controller;
    protected TenantCategory $category;
    protected Tenant $tenant;
    protected GlobalTemplate $globalTemplate;
    protected TenantTemplate $tenantTemplate;
    protected TenantTemplatePage $tenantTemplatePage;

    protected function setUp(): void
    {
        parent::setUp();

        $this->controller = new TenantTemplatePageController();

        $this->category = TenantCategory::create([
            'name' => 'General Category',
            'code' => 'GEN',
            'description' => 'General category',
        ]);

        $this->tenant = Tenant::create([
            'name' => 'Unit Test Tenant',
            'slug' => 'unit-test-tenant',
            'tenant_category_id' => $this->category->id,
            'is_active' => 1,
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

        $this->tenantTemplatePage = TenantTemplatePage::create([
            'tenant_template_id' => $this->tenantTemplate->id,
            'title' => 'Initial Page',
            'slug' => 'initial-page',
            'template_data' => ['key' => 'val'],
            'is_active' => 1,
        ]);
    }

    /**
     * Test direct get method call on Dashboard TenantTemplatePageController.
     */
    public function test_controller_get_returns_json_response(): void
    {
        $request = GetTenantTemplatePageRequest::create('/api/v1/dashboard/cms/tenant-template-page', 'GET');
        $request->setContainer($this->app);
        $request->setRedirector($this->app->make(Redirector::class));
        $request->validateResolved();

        $response = $this->controller->get($request);

        $this->assertEquals(200, $response->getStatusCode());
        $responseData = $response->getData(true);
        $this->assertTrue($responseData['success']);
        $this->assertEquals('Tenant Template Page successfully fetched', $responseData['message']);
    }

    /**
     * Test direct create method call on Dashboard TenantTemplatePageController.
     */
    public function test_controller_create_returns_json_response(): void
    {
        $payload = [
            'tenant_template_uuid' => $this->tenantTemplate->uuid,
            'title' => 'New Unit Page',
            'slug' => 'new-unit-page',
            'is_active' => 1,
        ];

        $request = StoreTenantTemplatePageRequest::create('/api/v1/dashboard/cms/tenant-template-page/create', 'POST', $payload);
        $request->setContainer($this->app);
        $request->setRedirector($this->app->make(Redirector::class));
        $request->validateResolved();

        $response = $this->controller->create($request);

        $this->assertEquals(200, $response->getStatusCode());
        $responseData = $response->getData(true);
        $this->assertTrue($responseData['success']);
        $this->assertEquals('Tenant page successfully stored', $responseData['message']);
        $this->assertEquals('New Unit Page', $responseData['data']['title']);
    }

    /**
     * Test direct update method call on Dashboard TenantTemplatePageController.
     */
    public function test_controller_update_returns_json_response(): void
    {
        $payload = [
            'tenant_template_page_uuid' => $this->tenantTemplatePage->uuid,
            'title' => 'Updated Unit Page Title',
            'slug' => 'updated-unit-page-slug',
        ];

        $request = UpdateTenantTemplatePageRequest::create('/api/v1/dashboard/cms/tenant-template-page/update', 'PATCH', $payload);
        $request->setContainer($this->app);
        $request->setRedirector($this->app->make(Redirector::class));
        $request->validateResolved();

        $response = $this->controller->update($request);

        $this->assertEquals(200, $response->getStatusCode());
        $responseData = $response->getData(true);
        $this->assertTrue($responseData['success']);
        $this->assertEquals('Tenant Template Page successfully updated', $responseData['message']);
        $this->assertEquals('Updated Unit Page Title', $responseData['data']['title']);
    }
}
