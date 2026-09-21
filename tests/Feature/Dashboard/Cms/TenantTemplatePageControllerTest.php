<?php

namespace Tests\Feature\Dashboard\Cms;

use App\Models\Cms\GlobalTemplate;
use App\Models\Cms\TenantTemplate;
use App\Models\CMS\TenantTemplatePage;
use App\Models\Tenant\Tenant;
use App\Models\Tenant\TenantCategory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

class TenantTemplatePageControllerTest extends TestCase
{
    use RefreshDatabase;

    protected TenantCategory $category;
    protected Tenant $tenant;
    protected GlobalTemplate $globalTemplate;
    protected TenantTemplate $tenantTemplate;
    protected TenantTemplatePage $tenantTemplatePage;

    protected function setUp(): void
    {
        parent::setUp();

        $this->category = TenantCategory::create([
            'name' => 'General Category',
            'code' => 'GEN',
            'description' => 'General tenant category',
        ]);

        $this->tenant = Tenant::create([
            'name' => 'Test Tenant',
            'slug' => 'test-tenant',
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
            'title' => 'Home Page',
            'slug' => 'home',
            'template_data' => ['header' => 'Welcome'],
            'is_active' => 1,
        ]);
    }

    /**
     * Test get list of tenant template pages without query params.
     */
    public function test_get_tenant_template_pages_list_successfully(): void
    {
        $response = $this->getJson('/api/v1/dashboard/cms/tenant-template-page');

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'Tenant Template Page successfully fetched',
            ])
            ->assertJsonStructure([
                'success',
                'message',
                'data' => [
                    '*' => ['uuid', 'title', 'slug', 'template_data', 'is_active', 'version', 'tenant_template']
                ]
            ]);
    }

    /**
     * Test get list of tenant template pages with pagination.
     */
    public function test_get_tenant_template_pages_with_pagination(): void
    {
        $response = $this->getJson('/api/v1/dashboard/cms/tenant-template-page?with_pagination=true&per_page=5&page=1');

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'Tenant Template Page successfully fetched',
            ])
            ->assertJsonStructure([
                'success',
                'message',
                'data',
                'pagination'
            ]);
    }

    /**
     * Test get single tenant template page by UUID.
     */
    public function test_get_single_tenant_template_page_by_uuid(): void
    {
        $response = $this->getJson('/api/v1/dashboard/cms/tenant-template-page/' . $this->tenantTemplatePage->uuid);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'Tenant Template Page successfully fetched',
                'data' => [
                    'uuid' => $this->tenantTemplatePage->uuid,
                    'title' => 'Home Page',
                    'slug' => 'home',
                ]
            ]);
    }

    /**
     * Test get tenant template pages filtered by tenant_template_uuid.
     */
    public function test_get_tenant_template_pages_filtered_by_tenant_template_uuid(): void
    {
        $response = $this->getJson('/api/v1/dashboard/cms/tenant-template-page?tenant_template_uuid=' . $this->tenantTemplate->uuid);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'Tenant Template Page successfully fetched',
            ]);

        $this->assertNotEmpty($response->json('data'));
    }

    /**
     * Test search tenant template pages by search_param.
     */
    public function test_get_tenant_template_pages_with_search_param(): void
    {
        $response = $this->getJson('/api/v1/dashboard/cms/tenant-template-page?search_param=Initial');

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'Tenant Template Page successfully fetched',
            ]);

        $this->assertCount(1, $response->json('data'));
    }

    /**
     * Test create tenant template page successfully.
     */
    public function test_create_tenant_template_page_successfully(): void
    {
        $payload = [
            'tenant_template_uuid' => $this->tenantTemplate->uuid,
            'title' => 'About Us',
            'slug' => 'about-us',
            'template_data' => ['content' => 'About company'],
            'is_active' => 1,
        ];

        $response = $this->postJson('/api/v1/dashboard/cms/tenant-template-page/create', $payload);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'Tenant page successfully stored',
                'data' => [
                    'title' => 'About Us',
                    'slug' => 'about-us',
                ]
            ]);

        $this->assertDatabaseHas('cms_tenant_template_pages', [
            'tenant_template_id' => $this->tenantTemplate->id,
            'title' => 'About Us',
            'slug' => 'about-us',
        ]);
    }

    /**
     * Test create tenant template page validation failure when required fields missing.
     */
    public function test_create_tenant_template_page_validation_fails_when_required_fields_missing(): void
    {
        $response = $this->postJson('/api/v1/dashboard/cms/tenant-template-page/create', []);

        $response->assertStatus(422)
            ->assertJson([
                'success' => false,
            ])
            ->assertJsonStructure([
                'success',
                'message' => ['tenant_template_uuid', 'title', 'slug']
            ]);
    }

    /**
     * Test create tenant template page fails when tenant_template_uuid does not exist.
     */
    public function test_create_tenant_template_page_fails_when_tenant_template_uuid_does_not_exist(): void
    {
        $payload = [
            'tenant_template_uuid' => (string) Str::uuid(),
            'title' => 'Contact Us',
            'slug' => 'contact-us',
        ];

        $response = $this->postJson('/api/v1/dashboard/cms/tenant-template-page/create', $payload);

        $response->assertStatus(422)
            ->assertJson([
                'success' => false,
            ])
            ->assertJsonStructure([
                'success',
                'message' => ['tenant_template_uuid']
            ]);
    }

    /**
     * Test update tenant template page successfully.
     */
    public function test_update_tenant_template_page_successfully(): void
    {
        $payload = [
            'tenant_template_page_uuid' => $this->tenantTemplatePage->uuid,
            'title' => 'Updated Home Page',
            'slug' => 'updated-home',
            'template_data' => ['header' => 'New Welcome'],
            'is_active' => 1,
        ];

        $response = $this->patchJson('/api/v1/dashboard/cms/tenant-template-page/update', $payload);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'Tenant Template Page successfully updated',
                'data' => [
                    'uuid' => $this->tenantTemplatePage->uuid,
                    'title' => 'Updated Home Page',
                    'slug' => 'updated-home',
                ]
            ]);

        $this->assertDatabaseHas('cms_tenant_template_pages', [
            'id' => $this->tenantTemplatePage->id,
            'title' => 'Updated Home Page',
            'slug' => 'updated-home',
        ]);
    }

    /**
     * Test update tenant template page validation failure when tenant_template_page_uuid is missing.
     */
    public function test_update_tenant_template_page_validation_fails_when_uuid_missing(): void
    {
        $payload = [
            'title' => 'Missing UUID Update',
        ];

        $response = $this->patchJson('/api/v1/dashboard/cms/tenant-template-page/update', $payload);

        $response->assertStatus(422)
            ->assertJson([
                'success' => false,
            ])
            ->assertJsonStructure([
                'success',
                'message' => ['tenant_template_page_uuid']
            ]);
    }

    /**
     * Test update tenant template page validation failure when tenant_template_page_uuid does not exist.
     */
    public function test_update_tenant_template_page_fails_when_page_uuid_does_not_exist(): void
    {
        $payload = [
            'tenant_template_page_uuid' => (string) Str::uuid(),
            'title' => 'Non existent page',
        ];

        $response = $this->patchJson('/api/v1/dashboard/cms/tenant-template-page/update', $payload);

        $response->assertStatus(422)
            ->assertJson([
                'success' => false,
            ])
            ->assertJsonStructure([
                'success',
                'message' => ['tenant_template_page_uuid']
            ]);
    }
}
