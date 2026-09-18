<?php

namespace Tests\Unit\Controllers\Tenant\Cms;

use App\Http\Controllers\Api\V1\Tenant\Cms\TenantTemplatePageController;
use App\Http\Requests\Api\V1\Tenant\Cms\TenantTemplatePage\GetTenantTemplatePageRequest;
use App\Models\Cms\GlobalTemplate;
use App\Models\Cms\TenantTemplate;
use App\Models\CMS\TenantTemplatePage;
use App\Models\Tenant\Tenant;
use App\Models\Tenant\TenantCategory;
use Illuminate\Foundation\Testing\RefreshDatabase;
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
            'name' => 'Public Unit Tenant',
            'slug' => 'public-unit-tenant',
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
            'title' => 'Unit Public Page',
            'slug' => 'unit-public-page',
            'template_data' => ['key' => 'val'],
            'is_active' => 1,
        ]);
    }

    /**
     * Test direct get method call on Tenant TenantTemplatePageController.
     */
    public function test_controller_get_public_page_returns_json_response(): void
    {
        $payload = ['tenant_slug' => $this->tenant->slug];
        $request = GetTenantTemplatePageRequest::create("/api/v1/t/{$this->tenant->slug}/cms/page/{$this->tenantTemplatePage->slug}", 'GET', $payload);
        $request->setContainer($this->app);

        $response = $this->controller->get($request, $this->tenant->slug, $this->tenantTemplatePage->slug);

        $this->assertEquals(200, $response->getStatusCode());
        $responseData = $response->getData(true);
        $this->assertTrue($responseData['success']);
        $this->assertEquals('Berhasil mengambil data halaman.', $responseData['message']);
        $this->assertEquals('unit-public-page', $responseData['data']['slug']);
    }
}
