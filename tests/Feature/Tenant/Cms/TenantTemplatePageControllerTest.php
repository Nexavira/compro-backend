<?php

namespace Tests\Feature\Tenant\Cms;

use App\Models\Cms\GlobalTemplate;
use App\Models\Cms\TenantTemplate;
use App\Models\CMS\TenantTemplatePage;
use App\Models\Tenant\Tenant;
use App\Models\Tenant\TenantCategory;
use Illuminate\Foundation\Testing\RefreshDatabase;
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
            'description' => 'General category',
        ]);

        $this->tenant = Tenant::create([
            'name' => 'Public Tenant',
            'slug' => 'public-tenant',
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
            'title' => 'Landing Page',
            'slug' => 'landing-page',
            'template_data' => ['banner' => 'Hero image'],
            'is_active' => 1,
        ]);
    }

    /**
     * Test get public tenant template page by tenant_slug and page slug successfully.
     */
    public function test_get_public_tenant_template_page_by_slug_successfully(): void
    {
        $url = "/api/v1/t/{$this->tenant->slug}/cms/page/{$this->tenantTemplatePage->slug}";

        $response = $this->getJson($url);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'Berhasil mengambil data halaman.',
                'data' => [
                    'id' => $this->tenantTemplatePage->uuid,
                    'title' => 'Landing Page',
                    'slug' => 'landing-page',
                ]
            ]);
    }

    /**
     * Test get public tenant template page returns 404 when page slug not found.
     */
    public function test_get_public_tenant_template_page_returns_404_when_page_not_found(): void
    {
        $url = "/api/v1/t/{$this->tenant->slug}/cms/page/non-existent-page-slug";

        $response = $this->getJson($url);

        $response->assertStatus(404)
            ->assertJson([
                'success' => false,
                'message' => 'Page not found',
            ]);
    }
}
