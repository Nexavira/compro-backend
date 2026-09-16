<?php

namespace Tests\Unit\Services\Cms\TenantTemplate;

use App\Models\Cms\GlobalTemplate;
use App\Models\Cms\TenantTemplate;
use App\Models\Tenant\Tenant;
use App\Models\Tenant\TenantCategory;
use App\Services\Cms\TenantTemplate\UpdateTenantTemplateService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

class UpdateTenantTemplateServiceTest extends TestCase
{
    use RefreshDatabase;

    protected UpdateTenantTemplateService $service;
    protected TenantCategory $category;
    protected Tenant $tenant;
    protected GlobalTemplate $globalTemplate;
    protected TenantTemplate $tenantTemplate;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new UpdateTenantTemplateService();

        $this->category = TenantCategory::create([
            'name' => 'General Category',
            'code' => 'GEN',
            'description' => 'General category',
        ]);

        $this->tenant = Tenant::create([
            'name' => 'Template Tenant',
            'slug' => 'template-tenant',
            'tenant_category_id' => $this->category->id,
            'is_active' => 1,
        ]);

        $this->globalTemplate = GlobalTemplate::create([
            'title' => 'Default Global Template',
            'slug' => 'default-global-template',
            'tier' => 'basic',
            'tenant_category_id' => $this->category->id,
            'is_active' => 1,
        ]);

        $this->tenantTemplate = TenantTemplate::create([
            'tenant_id' => $this->tenant->id,
            'global_template_id' => $this->globalTemplate->id,
            'template_settings' => ['primary_color' => '#ffffff'],
            'is_active' => 1,
        ]);
    }

    /**
     * Test successful update of tenant template using UUIDs.
     */
    public function test_update_tenant_template_successfully(): void
    {
        $newGlobalTemplate = GlobalTemplate::create([
            'title' => 'New Global Template',
            'slug' => 'new-global-template',
            'tier' => 'pro',
            'tenant_category_id' => $this->category->id,
            'is_active' => 1,
        ]);

        $newTenant = Tenant::create([
            'name' => 'New Tenant',
            'slug' => 'new-tenant',
            'tenant_category_id' => $this->category->id,
            'is_active' => 1,
        ]);

        $inputData = [
            'tenant_template_uuid' => $this->tenantTemplate->uuid,
            'tenant_uuid' => $newTenant->uuid,
            'global_template_uuid' => $newGlobalTemplate->uuid,
            'template_settings' => ['primary_color' => '#000000', 'font' => 'Inter'],
        ];

        $result = $this->service->execute($inputData);

        $this->assertEquals(200, $result['response_code']);
        $this->assertEquals('Tenant template successfully updated', $result['message']);
        $this->assertNull($result['error']);

        $updated = $result['data'];
        $this->assertInstanceOf(TenantTemplate::class, $updated);
        $this->assertEquals($newTenant->id, $updated->tenant_id);
        $this->assertEquals($newGlobalTemplate->id, $updated->global_template_id);

        $this->assertDatabaseHas('cms_tenant_templates', [
            'id' => $this->tenantTemplate->id,
            'tenant_id' => $newTenant->id,
            'global_template_id' => $newGlobalTemplate->id,
        ]);
    }

    /**
     * Test successful update of tenant template using integer IDs.
     */
    public function test_update_tenant_template_with_ids_successfully(): void
    {
        $newTenant = Tenant::create([
            'name' => 'Another Tenant',
            'slug' => 'another-tenant',
            'tenant_category_id' => $this->category->id,
            'is_active' => 1,
        ]);

        $inputData = [
            'tenant_template_uuid' => $this->tenantTemplate->uuid,
            'tenant_id' => $newTenant->id,
            'global_template_id' => $this->globalTemplate->id,
            'template_settings' => ['header_style' => 'sticky'],
        ];

        $result = $this->service->execute($inputData);

        $this->assertEquals(200, $result['response_code']);
        $this->assertEquals('Tenant template successfully updated', $result['message']);
        $this->assertEquals($newTenant->id, $result['data']->tenant_id);
    }

    /**
     * Test partial update of template settings keeping existing tenant and global template IDs.
     */
    public function test_update_tenant_template_partially_keeping_existing_values(): void
    {
        $inputData = [
            'tenant_template_uuid' => $this->tenantTemplate->uuid,
            'template_settings' => ['theme' => 'dark_mode'],
        ];

        $result = $this->service->execute($inputData);

        $this->assertEquals(200, $result['response_code']);
        $this->assertEquals($this->tenant->id, $result['data']->tenant_id);
        $this->assertEquals($this->globalTemplate->id, $result['data']->global_template_id);
    }

    /**
     * Test update fails when required tenant_template_uuid is missing.
     */
    public function test_update_tenant_template_fails_when_tenant_template_uuid_is_missing(): void
    {
        $inputData = [
            'template_settings' => ['theme' => 'light'],
        ];

        $result = $this->service->execute($inputData);

        $this->assertEquals(422, $result['response_code']);
        $this->assertNotNull($result['error']);
    }

    /**
     * Test update fails when tenant_template_uuid does not exist in database.
     */
    public function test_update_tenant_template_fails_when_tenant_template_uuid_does_not_exist(): void
    {
        $inputData = [
            'tenant_template_uuid' => (string) Str::uuid(),
            'template_settings' => ['theme' => 'light'],
        ];

        $result = $this->service->execute($inputData);

        $this->assertEquals(422, $result['response_code']);
        $this->assertNotNull($result['error']);
    }

    /**
     * Test update fails when provided tenant_uuid does not exist in database.
     */
    public function test_update_tenant_template_fails_when_tenant_uuid_does_not_exist(): void
    {
        $inputData = [
            'tenant_template_uuid' => $this->tenantTemplate->uuid,
            'tenant_uuid' => (string) Str::uuid(),
        ];

        $result = $this->service->execute($inputData);

        $this->assertEquals(422, $result['response_code']);
        $this->assertNotNull($result['error']);
    }

    /**
     * Test update fails when provided tenant_id does not exist in database.
     */
    public function test_update_tenant_template_fails_when_tenant_id_does_not_exist(): void
    {
        $inputData = [
            'tenant_template_uuid' => $this->tenantTemplate->uuid,
            'tenant_id' => 999999,
        ];

        $result = $this->service->execute($inputData);

        $this->assertEquals(422, $result['response_code']);
        $this->assertNotNull($result['error']);
    }

    /**
     * Test update fails when provided global_template_uuid does not exist in database.
     */
    public function test_update_tenant_template_fails_when_global_template_uuid_does_not_exist(): void
    {
        $inputData = [
            'tenant_template_uuid' => $this->tenantTemplate->uuid,
            'global_template_uuid' => (string) Str::uuid(),
        ];

        $result = $this->service->execute($inputData);

        $this->assertEquals(422, $result['response_code']);
        $this->assertNotNull($result['error']);
    }

    /**
     * Test update fails when provided global_template_id does not exist in database.
     */
    public function test_update_tenant_template_fails_when_global_template_id_does_not_exist(): void
    {
        $inputData = [
            'tenant_template_uuid' => $this->tenantTemplate->uuid,
            'global_template_id' => 999999,
        ];

        $result = $this->service->execute($inputData);

        $this->assertEquals(422, $result['response_code']);
        $this->assertNotNull($result['error']);
    }
}
