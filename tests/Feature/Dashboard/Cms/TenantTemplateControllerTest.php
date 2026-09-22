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

class TenantTemplateControllerTest extends TestCase
{
    use RefreshDatabase;

    protected TenantCategory $category;
    protected Tenant $tenant;
    protected GlobalTemplate $globalTemplate;

    protected function setUp(): void
    {
        parent::setUp();

        $this->category = TenantCategory::create([
            'name'        => 'General Category',
            'code'        => 'GEN',
            'description' => 'General tenant category',
        ]);

        $this->tenant = Tenant::create([
            'name'               => 'Test Tenant',
            'slug'               => 'test-tenant',
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

    // -------------------------------------------------------------------------
    // POST /api/v1/dashboard/cms/tenant-template/create
    // -------------------------------------------------------------------------

    /**
     * Test create tenant template (without pages) successfully — uses StoreTenantTemplateService.
     */
    public function test_create_tenant_template_without_pages_successfully(): void
    {
        $payload = [
            'tenant_uuid'          => $this->tenant->uuid,
            'global_template_uuid' => $this->globalTemplate->uuid,
            'template_settings'    => ['theme' => 'light'],
            'is_active'            => 1,
        ];

        $response = $this->postJson('/api/v1/dashboard/cms/tenant-template/create', $payload);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'Tenant template successfully stored',
            ])
            ->assertJsonStructure([
                'success',
                'message',
                'data' => ['uuid', 'is_active', 'template_settings', 'version', 'tenant', 'global_template'],
            ]);

        $this->assertDatabaseHas('cms_tenant_templates', [
            'tenant_id'          => $this->tenant->id,
            'global_template_id' => $this->globalTemplate->id,
        ]);
    }

    /**
     * Test create tenant template with pages — uses CreateTenantTemplateService.
     */
    public function test_create_tenant_template_with_pages_successfully(): void
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
                    'title' => 'About Us',
                    'slug'  => 'about-us',
                ],
            ],
        ];

        $response = $this->postJson('/api/v1/dashboard/cms/tenant-template/create', $payload);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'Tenant template and pages successfully stored',
            ])
            ->assertJsonStructure([
                'success',
                'message',
                'data' => ['uuid', 'is_active', 'template_settings', 'version', 'tenant', 'global_template'],
            ]);

        $this->assertDatabaseHas('cms_tenant_templates', [
            'tenant_id'          => $this->tenant->id,
            'global_template_id' => $this->globalTemplate->id,
        ]);

        $this->assertDatabaseHas('cms_tenant_template_pages', [
            'title' => 'Home',
            'slug'  => 'home',
        ]);

        $this->assertDatabaseHas('cms_tenant_template_pages', [
            'title' => 'About Us',
            'slug'  => 'about-us',
        ]);
    }

    /**
     * Test create with pages persists the correct number of pages.
     */
    public function test_create_tenant_template_with_pages_persists_correct_page_count(): void
    {
        $payload = [
            'tenant_uuid'          => $this->tenant->uuid,
            'global_template_uuid' => $this->globalTemplate->uuid,
            'pages'                => [
                ['title' => 'Page 1', 'slug' => 'page-1'],
                ['title' => 'Page 2', 'slug' => 'page-2'],
                ['title' => 'Page 3', 'slug' => 'page-3'],
            ],
        ];

        $this->postJson('/api/v1/dashboard/cms/tenant-template/create', $payload)
            ->assertStatus(200)
            ->assertJson(['success' => true]);

        $this->assertCount(3, TenantTemplatePage::all());
    }

    /**
     * Test create without pages does not persist any pages.
     */
    public function test_create_tenant_template_without_pages_does_not_create_pages(): void
    {
        $payload = [
            'tenant_uuid'          => $this->tenant->uuid,
            'global_template_uuid' => $this->globalTemplate->uuid,
        ];

        $this->postJson('/api/v1/dashboard/cms/tenant-template/create', $payload)
            ->assertStatus(200)
            ->assertJson(['success' => true]);

        $this->assertCount(0, TenantTemplatePage::all());
    }

    /**
     * Test create tenant template using template_settings as array.
     */
    public function test_create_tenant_template_with_template_settings(): void
    {
        $settings = [
            'primary_color'   => '#3b82f6',
            'secondary_color' => '#64748b',
            'font_family'     => 'Inter',
        ];

        $payload = [
            'tenant_uuid'          => $this->tenant->uuid,
            'global_template_uuid' => $this->globalTemplate->uuid,
            'template_settings'    => $settings,
        ];

        $response = $this->postJson('/api/v1/dashboard/cms/tenant-template/create', $payload);

        $response->assertStatus(200)
            ->assertJson(['success' => true]);

        $this->assertDatabaseHas('cms_tenant_templates', [
            'tenant_id' => $this->tenant->id,
        ]);
    }

    // -------------------------------------------------------------------------
    // Validation failures — missing required fields
    // -------------------------------------------------------------------------

    /**
     * Test create fails when required fields (tenant_uuid, global_template_uuid) are missing.
     */
    public function test_create_tenant_template_fails_when_required_fields_missing(): void
    {
        $response = $this->postJson('/api/v1/dashboard/cms/tenant-template/create', []);

        $response->assertStatus(422)
            ->assertJson(['success' => false])
            ->assertJsonStructure([
                'success',
                'message' => ['tenant_uuid', 'global_template_uuid'],
            ]);
    }

    /**
     * Test create fails when tenant_uuid is missing.
     */
    public function test_create_tenant_template_fails_when_tenant_uuid_missing(): void
    {
        $payload = [
            'global_template_uuid' => $this->globalTemplate->uuid,
        ];

        $response = $this->postJson('/api/v1/dashboard/cms/tenant-template/create', $payload);

        $response->assertStatus(422)
            ->assertJson(['success' => false])
            ->assertJsonStructure([
                'success',
                'message' => ['tenant_uuid'],
            ]);
    }

    /**
     * Test create fails when global_template_uuid is missing.
     */
    public function test_create_tenant_template_fails_when_global_template_uuid_missing(): void
    {
        $payload = [
            'tenant_uuid' => $this->tenant->uuid,
        ];

        $response = $this->postJson('/api/v1/dashboard/cms/tenant-template/create', $payload);

        $response->assertStatus(422)
            ->assertJson(['success' => false])
            ->assertJsonStructure([
                'success',
                'message' => ['global_template_uuid'],
            ]);
    }

    // -------------------------------------------------------------------------
    // Validation failures — non-existent UUIDs
    // -------------------------------------------------------------------------

    /**
     * Test create fails when tenant_uuid does not exist.
     */
    public function test_create_tenant_template_fails_when_tenant_uuid_does_not_exist(): void
    {
        $payload = [
            'tenant_uuid'          => (string) Str::uuid(),
            'global_template_uuid' => $this->globalTemplate->uuid,
        ];

        $response = $this->postJson('/api/v1/dashboard/cms/tenant-template/create', $payload);

        $response->assertStatus(422)
            ->assertJson(['success' => false])
            ->assertJsonStructure([
                'success',
                'message' => ['tenant_uuid'],
            ]);
    }

    /**
     * Test create fails when global_template_uuid does not exist.
     */
    public function test_create_tenant_template_fails_when_global_template_uuid_does_not_exist(): void
    {
        $payload = [
            'tenant_uuid'          => $this->tenant->uuid,
            'global_template_uuid' => (string) Str::uuid(),
        ];

        $response = $this->postJson('/api/v1/dashboard/cms/tenant-template/create', $payload);

        $response->assertStatus(422)
            ->assertJson(['success' => false])
            ->assertJsonStructure([
                'success',
                'message' => ['global_template_uuid'],
            ]);
    }

    // -------------------------------------------------------------------------
    // Validation failures — invalid field values
    // -------------------------------------------------------------------------

    /**
     * Test create fails when is_active has an invalid value (not 0 or 1).
     */
    public function test_create_tenant_template_fails_when_is_active_is_invalid(): void
    {
        $payload = [
            'tenant_uuid'          => $this->tenant->uuid,
            'global_template_uuid' => $this->globalTemplate->uuid,
            'is_active'            => 5,
        ];

        $response = $this->postJson('/api/v1/dashboard/cms/tenant-template/create', $payload);

        $response->assertStatus(422)
            ->assertJson(['success' => false])
            ->assertJsonStructure([
                'success',
                'message' => ['is_active'],
            ]);
    }

    /**
     * Test create fails when pages.*.title is missing while pages are provided.
     */
    public function test_create_tenant_template_fails_when_page_title_missing(): void
    {
        $payload = [
            'tenant_uuid'          => $this->tenant->uuid,
            'global_template_uuid' => $this->globalTemplate->uuid,
            'pages'                => [
                ['slug' => 'home'],  // missing title
            ],
        ];

        $response = $this->postJson('/api/v1/dashboard/cms/tenant-template/create', $payload);

        $response->assertStatus(422)
            ->assertJson(['success' => false]);
    }

    /**
     * Test create fails when pages.*.slug is missing while pages are provided.
     */
    public function test_create_tenant_template_fails_when_page_slug_missing(): void
    {
        $payload = [
            'tenant_uuid'          => $this->tenant->uuid,
            'global_template_uuid' => $this->globalTemplate->uuid,
            'pages'                => [
                ['title' => 'Home'],  // missing slug
            ],
        ];

        $response = $this->postJson('/api/v1/dashboard/cms/tenant-template/create', $payload);

        $response->assertStatus(422)
            ->assertJson(['success' => false]);
    }

    // -------------------------------------------------------------------------
    // Response data correctness
    // -------------------------------------------------------------------------

    /**
     * Test create response data contains the correct tenant information.
     */
    public function test_create_tenant_template_response_data_contains_tenant_info(): void
    {
        $payload = [
            'tenant_uuid'          => $this->tenant->uuid,
            'global_template_uuid' => $this->globalTemplate->uuid,
        ];

        $response = $this->postJson('/api/v1/dashboard/cms/tenant-template/create', $payload);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'data'    => [
                    'tenant' => [
                        'uuid' => $this->tenant->uuid,
                        'name' => $this->tenant->name,
                        'slug' => $this->tenant->slug,
                    ],
                ],
            ]);
    }

    /**
     * Test create response data contains the correct global_template information.
     */
    public function test_create_tenant_template_response_data_contains_global_template_info(): void
    {
        $payload = [
            'tenant_uuid'          => $this->tenant->uuid,
            'global_template_uuid' => $this->globalTemplate->uuid,
        ];

        $response = $this->postJson('/api/v1/dashboard/cms/tenant-template/create', $payload);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'data'    => [
                    'global_template' => [
                        'uuid'  => $this->globalTemplate->uuid,
                        'title' => $this->globalTemplate->title,
                        'tier'  => $this->globalTemplate->tier,
                    ],
                ],
            ]);
    }

    /**
     * Test only one tenant template record is created per valid request.
     */
    public function test_create_tenant_template_creates_exactly_one_record(): void
    {
        $payload = [
            'tenant_uuid'          => $this->tenant->uuid,
            'global_template_uuid' => $this->globalTemplate->uuid,
        ];

        $this->postJson('/api/v1/dashboard/cms/tenant-template/create', $payload)
            ->assertStatus(200);

        $this->assertCount(1, TenantTemplate::all());
    }
}

