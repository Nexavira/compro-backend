<?php

namespace Tests\Unit\Controllers\Dashboard\Cms;

use App\Http\Controllers\Api\V1\Dashboard\Cms\TenantTemplateController;
use App\Http\Requests\Api\V1\Dashboard\Cms\TenantTemplate\StoreTenantTemplateRequest;
use App\Models\Cms\GlobalTemplate;
use App\Models\Cms\TenantTemplate;
use App\Models\CMS\TenantTemplatePage;
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

    protected function setUp(): void
    {
        parent::setUp();

        $this->controller = new TenantTemplateController();

        $this->category = TenantCategory::create([
            'name'        => 'General Category',
            'code'        => 'GEN',
            'description' => 'General category',
        ]);

        $this->tenant = Tenant::create([
            'name'               => 'Unit Test Tenant',
            'slug'               => 'unit-test-tenant',
            'tenant_category_id' => $this->category->id,
            'is_active'          => 1,
        ]);

        $this->globalTemplate = GlobalTemplate::create([
            'title'              => 'Global Template',
            'slug'               => 'global-template',
            'tier'               => 'basic',
            'tenant_category_id' => $this->category->id,
            'is_active'          => 1,
        ]);
    }

    /**
     * Test create (without pages) calls StoreTenantTemplateService and returns json response.
     */
    public function test_controller_create_without_pages_returns_json_response(): void
    {
        $payload = [
            'tenant_uuid'          => $this->tenant->uuid,
            'global_template_uuid' => $this->globalTemplate->uuid,
            'template_settings'    => ['theme' => 'light'],
            'is_active'            => 1,
        ];

        $request = StoreTenantTemplateRequest::create(
            '/api/v1/dashboard/cms/tenant-template/create',
            'POST',
            $payload
        );
        $request->setContainer($this->app);
        $request->setRedirector($this->app->make(Redirector::class));
        $request->validateResolved();

        $response = $this->controller->create($request);

        $this->assertEquals(200, $response->getStatusCode());
        $responseData = $response->getData(true);
        $this->assertTrue($responseData['success']);
        $this->assertEquals('Tenant template successfully stored', $responseData['message']);
        $this->assertArrayHasKey('uuid', $responseData['data']);
    }

    /**
     * Test create (with pages) calls CreateTenantTemplateService and stores pages.
     */
    public function test_controller_create_with_pages_returns_json_response(): void
    {
        $payload = [
            'tenant_uuid'          => $this->tenant->uuid,
            'global_template_uuid' => $this->globalTemplate->uuid,
            'template_settings'    => ['theme' => 'dark'],
            'pages'                => [
                [
                    'title'         => 'Home',
                    'slug'          => 'home',
                    'template_data' => ['header' => 'Welcome'],
                ],
                [
                    'title' => 'About',
                    'slug'  => 'about',
                ],
            ],
        ];

        $request = StoreTenantTemplateRequest::create(
            '/api/v1/dashboard/cms/tenant-template/create',
            'POST',
            $payload
        );
        $request->setContainer($this->app);
        $request->setRedirector($this->app->make(Redirector::class));
        $request->validateResolved();

        $response = $this->controller->create($request);

        $this->assertEquals(200, $response->getStatusCode());
        $responseData = $response->getData(true);
        $this->assertTrue($responseData['success']);
        $this->assertEquals('Tenant template and pages successfully stored', $responseData['message']);
        $this->assertArrayHasKey('uuid', $responseData['data']);
    }

    /**
     * Test controller create response structure contains expected keys.
     */
    public function test_controller_create_response_structure(): void
    {
        $payload = [
            'tenant_uuid'          => $this->tenant->uuid,
            'global_template_uuid' => $this->globalTemplate->uuid,
        ];

        $request = StoreTenantTemplateRequest::create(
            '/api/v1/dashboard/cms/tenant-template/create',
            'POST',
            $payload
        );
        $request->setContainer($this->app);
        $request->setRedirector($this->app->make(Redirector::class));
        $request->validateResolved();

        $response = $this->controller->create($request);

        $responseData = $response->getData(true);
        $this->assertArrayHasKey('success', $responseData);
        $this->assertArrayHasKey('message', $responseData);
        $this->assertArrayHasKey('data', $responseData);
    }

    /**
     * Test controller create persists tenant template to database.
     */
    public function test_controller_create_persists_data_to_database(): void
    {
        $payload = [
            'tenant_uuid'          => $this->tenant->uuid,
            'global_template_uuid' => $this->globalTemplate->uuid,
            'template_settings'    => ['primary_color' => '#ff0000'],
            'is_active'            => 1,
        ];

        $request = StoreTenantTemplateRequest::create(
            '/api/v1/dashboard/cms/tenant-template/create',
            'POST',
            $payload
        );
        $request->setContainer($this->app);
        $request->setRedirector($this->app->make(Redirector::class));
        $request->validateResolved();

        $this->controller->create($request);

        $this->assertDatabaseHas('cms_tenant_templates', [
            'tenant_id'          => $this->tenant->id,
            'global_template_id' => $this->globalTemplate->id,
        ]);
    }

    /**
     * Test controller create with pages persists pages to database.
     */
    public function test_controller_create_with_pages_persists_pages_to_database(): void
    {
        $payload = [
            'tenant_uuid'          => $this->tenant->uuid,
            'global_template_uuid' => $this->globalTemplate->uuid,
            'pages'                => [
                [
                    'title' => 'Services',
                    'slug'  => 'services',
                ],
            ],
        ];

        $request = StoreTenantTemplateRequest::create(
            '/api/v1/dashboard/cms/tenant-template/create',
            'POST',
            $payload
        );
        $request->setContainer($this->app);
        $request->setRedirector($this->app->make(Redirector::class));
        $request->validateResolved();

        $this->controller->create($request);

        $this->assertDatabaseHas('cms_tenant_template_pages', [
            'title' => 'Services',
            'slug'  => 'services',
        ]);
    }
}

