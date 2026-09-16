<?php

namespace Tests\Unit\Services\Tenant;

use App\Models\System\File;
use App\Models\Tenant\Tenant;
use App\Models\Tenant\TenantCategory;
use App\Services\Tenant\EditTenantInformationService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

class EditTenantInformationServiceTest extends TestCase
{
    use RefreshDatabase;

    protected EditTenantInformationService $service;
    protected TenantCategory $category;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new EditTenantInformationService();

        $this->category = TenantCategory::create([
            'name' => 'General Category',
            'code' => 'GEN',
            'description' => 'General tenant category',
        ]);
    }

    /**
     * Test successful tenant information update with basic data.
     */
    public function test_edit_tenant_information_successfully(): void
    {
        $tenant = Tenant::create([
            'name' => 'Old Tenant Name',
            'slug' => 'old-tenant-name',
            'description' => 'Old description',
            'tenant_category_id' => $this->category->id,
            'is_active' => 1,
        ]);

        $inputData = [
            'tenant_uuid' => $tenant->uuid,
            'name' => 'Updated Tenant Name',
            'slug' => 'updated-tenant-name',
            'description' => 'Updated description for the tenant.',
            'settings' => json_encode(['theme' => 'dark', 'notifications' => true]),
        ];

        $result = $this->service->execute($inputData);

        $this->assertEquals(200, $result['response_code']);
        $this->assertEquals('Tenant successfully updated', $result['message']);
        $this->assertNull($result['error']);

        $updatedTenant = $result['data'];
        $this->assertInstanceOf(Tenant::class, $updatedTenant);
        $this->assertEquals('Updated Tenant Name', $updatedTenant->name);
        $this->assertEquals('updated-tenant-name', $updatedTenant->slug);
        $this->assertEquals('Updated description for the tenant.', $updatedTenant->description);

        $this->assertDatabaseHas('tnt_tenants', [
            'id' => $tenant->id,
            'name' => 'Updated Tenant Name',
            'slug' => 'updated-tenant-name',
            'description' => 'Updated description for the tenant.',
        ]);
    }

    /**
     * Test updating tenant while keeping its existing name and slug.
     */
    public function test_edit_tenant_information_keeping_existing_name_and_slug(): void
    {
        $tenant = Tenant::create([
            'name' => 'Original Name',
            'slug' => 'original-slug',
            'description' => 'Original description',
            'tenant_category_id' => $this->category->id,
            'is_active' => 1,
        ]);

        $inputData = [
            'tenant_uuid' => $tenant->uuid,
            'name' => 'Original Name',
            'slug' => 'original-slug',
            'description' => 'New description only',
        ];

        $result = $this->service->execute($inputData);

        $this->assertEquals(200, $result['response_code']);
        $this->assertNull($result['error']);
        $this->assertEquals('New description only', $result['data']->description);
    }

    /**
     * Test successful tenant update including logo and favicon UUIDs.
     */
    public function test_edit_tenant_information_with_logo_and_favicon_successfully(): void
    {
        $tenant = Tenant::create([
            'name' => 'Tenant With Assets',
            'slug' => 'tenant-with-assets',
            'tenant_category_id' => $this->category->id,
            'is_active' => 1,
        ]);

        $logoFile = File::create([
            'file_name' => 'logo.png',
            'original_name' => 'logo.png',
            'file_path' => 'uploads/logo.png',
            'mime_type' => 'image/png',
            'is_active' => 1,
        ]);

        $faviconFile = File::create([
            'file_name' => 'favicon.ico',
            'original_name' => 'favicon.ico',
            'file_path' => 'uploads/favicon.ico',
            'mime_type' => 'image/x-icon',
            'is_active' => 1,
        ]);

        $inputData = [
            'tenant_uuid' => $tenant->uuid,
            'name' => 'Tenant With Assets Updated',
            'logo_uuid' => $logoFile->uuid,
            'favicon_uuid' => $faviconFile->uuid,
        ];

        $result = $this->service->execute($inputData);

        $this->assertEquals(200, $result['response_code']);
        $this->assertEquals('Tenant successfully updated', $result['message']);

        $updatedTenant = $result['data'];
        $this->assertEquals($logoFile->id, $updatedTenant->logo_id);
        $this->assertEquals($faviconFile->id, $updatedTenant->favicon_id);

        $this->assertDatabaseHas('tnt_tenants', [
            'id' => $tenant->id,
            'logo_id' => $logoFile->id,
            'favicon_id' => $faviconFile->id,
        ]);
    }

    /**
     * Test successful update of tenant category via tenant_category_uuid.
     */
    public function test_edit_tenant_information_with_tenant_category_successfully(): void
    {
        $newCategory = TenantCategory::create([
            'name' => 'New Category',
            'code' => 'NEW',
            'description' => 'New tenant category',
        ]);

        $tenant = Tenant::create([
            'name' => 'Tenant Category Test',
            'slug' => 'tenant-category-test',
            'tenant_category_id' => $this->category->id,
            'is_active' => 1,
        ]);

        $inputData = [
            'tenant_uuid' => $tenant->uuid,
            'name' => 'Tenant Category Test Updated',
            'tenant_category_uuid' => $newCategory->uuid,
        ];

        $result = $this->service->execute($inputData);

        $this->assertEquals(200, $result['response_code']);
        $this->assertEquals($newCategory->id, $result['data']->tenant_category_id);
    }

    /**
     * Test edit tenant fails when required field 'name' is missing.
     */
    public function test_edit_tenant_information_fails_when_name_is_missing(): void
    {
        $tenant = Tenant::create([
            'name' => 'Original Name',
            'slug' => 'original-name',
            'tenant_category_id' => $this->category->id,
            'is_active' => 1,
        ]);

        $inputData = [
            'tenant_uuid' => $tenant->uuid,
            'description' => 'Trying to update without name',
        ];

        $result = $this->service->execute($inputData);

        $this->assertEquals(422, $result['response_code']);
        $this->assertNotNull($result['error']);

        $this->assertDatabaseHas('tnt_tenants', [
            'id' => $tenant->id,
            'name' => 'Original Name',
        ]);
    }

    /**
     * Test edit tenant fails when name already belongs to another tenant.
     */
    public function test_edit_tenant_information_fails_when_name_is_not_unique(): void
    {
        Tenant::create([
            'name' => 'Existing Tenant Name',
            'slug' => 'existing-tenant',
            'tenant_category_id' => $this->category->id,
            'is_active' => 1,
        ]);

        $tenantToEdit = Tenant::create([
            'name' => 'Second Tenant',
            'slug' => 'second-tenant',
            'tenant_category_id' => $this->category->id,
            'is_active' => 1,
        ]);

        $inputData = [
            'tenant_uuid' => $tenantToEdit->uuid,
            'name' => 'Existing Tenant Name',
        ];

        $result = $this->service->execute($inputData);

        $this->assertEquals(422, $result['response_code']);
        $this->assertNotNull($result['error']);
    }

    /**
     * Test edit tenant fails when slug already belongs to another tenant.
     */
    public function test_edit_tenant_information_fails_when_slug_is_not_unique(): void
    {
        Tenant::create([
            'name' => 'Tenant Alpha',
            'slug' => 'existing-slug',
            'tenant_category_id' => $this->category->id,
            'is_active' => 1,
        ]);

        $tenantToEdit = Tenant::create([
            'name' => 'Tenant Beta',
            'slug' => 'beta-slug',
            'tenant_category_id' => $this->category->id,
            'is_active' => 1,
        ]);

        $inputData = [
            'tenant_uuid' => $tenantToEdit->uuid,
            'name' => 'Tenant Beta Updated',
            'slug' => 'existing-slug',
        ];

        $result = $this->service->execute($inputData);

        $this->assertEquals(422, $result['response_code']);
        $this->assertNotNull($result['error']);
    }

    /**
     * Test edit tenant fails when provided logo_uuid does not exist in sys_files.
     */
    public function test_edit_tenant_information_fails_when_logo_uuid_does_not_exist(): void
    {
        $tenant = Tenant::create([
            'name' => 'Tenant Logo Test',
            'slug' => 'tenant-logo-test',
            'tenant_category_id' => $this->category->id,
            'is_active' => 1,
        ]);

        $inputData = [
            'tenant_uuid' => $tenant->uuid,
            'name' => 'Tenant Logo Test',
            'logo_uuid' => (string) Str::uuid(),
        ];

        $result = $this->service->execute($inputData);

        $this->assertEquals(422, $result['response_code']);
        $this->assertNotNull($result['error']);
    }

    /**
     * Test edit tenant fails when provided favicon_uuid does not exist in sys_files.
     */
    public function test_edit_tenant_information_fails_when_favicon_uuid_does_not_exist(): void
    {
        $tenant = Tenant::create([
            'name' => 'Tenant Favicon Test',
            'slug' => 'tenant-favicon-test',
            'tenant_category_id' => $this->category->id,
            'is_active' => 1,
        ]);

        $inputData = [
            'tenant_uuid' => $tenant->uuid,
            'name' => 'Tenant Favicon Test',
            'favicon_uuid' => (string) Str::uuid(),
        ];

        $result = $this->service->execute($inputData);

        $this->assertEquals(422, $result['response_code']);
        $this->assertNotNull($result['error']);
    }

    /**
     * Test edit tenant fails when tenant_category_uuid does not exist.
     */
    public function test_edit_tenant_information_fails_when_tenant_category_uuid_does_not_exist(): void
    {
        $tenant = Tenant::create([
            'name' => 'Tenant Invalid Category Test',
            'slug' => 'tenant-invalid-category',
            'tenant_category_id' => $this->category->id,
            'is_active' => 1,
        ]);

        $inputData = [
            'tenant_uuid' => $tenant->uuid,
            'name' => 'Tenant Invalid Category Test',
            'tenant_category_uuid' => (string) Str::uuid(),
        ];

        $result = $this->service->execute($inputData);

        $this->assertEquals(422, $result['response_code']);
        $this->assertNotNull($result['error']);
    }

    /**
     * Test edit tenant fails when settings is invalid JSON string.
     */
    public function test_edit_tenant_information_fails_when_settings_is_invalid_json(): void
    {
        $tenant = Tenant::create([
            'name' => 'Tenant Settings Test',
            'slug' => 'tenant-settings-test',
            'tenant_category_id' => $this->category->id,
            'is_active' => 1,
        ]);

        $inputData = [
            'tenant_uuid' => $tenant->uuid,
            'name' => 'Tenant Settings Test',
            'settings' => 'not-a-valid-json-string',
        ];

        $result = $this->service->execute($inputData);

        $this->assertEquals(422, $result['response_code']);
        $this->assertNotNull($result['error']);
    }

    /**
     * Test edit tenant fails when tenant_uuid does not exist in database.
     */
    public function test_edit_tenant_information_fails_when_tenant_uuid_does_not_exist(): void
    {
        $inputData = [
            'tenant_uuid' => (string) Str::uuid(),
            'name' => 'Non Existent Tenant',
        ];

        $result = $this->service->execute($inputData);

        $this->assertNotEquals(200, $result['response_code']);
        $this->assertNotNull($result['error']);
    }
}
