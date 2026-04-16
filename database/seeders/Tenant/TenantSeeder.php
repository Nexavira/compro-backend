<?php

namespace Database\Seeders\Tenant;

use App\Models\Tenant\Tenant;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TenantSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Tenant::create([
            'uuid' => '019d966f-04e3-7f06-b2a2-e0a740c33b9b',
            'tenant_category_id' => 1,
            'name' => 'Nexavira',
            'slug' => 'nexavira',
            'custom_domain' => 'nexavira.com',
            'theme_code' => 'light_neon',
            'is_suspended' => false,
            'is_active' => true,
            'version' => 1,
            'created_by' => 1,
            'updated_by' => 1,
            'deleted_by' => null,
            'created_at' => 1713150000,
            'updated_at' => 1713150000,
            'deleted_at' => null,
        ]);

        Tenant::create([
            'uuid' => '550e8400-e29b-41d4-a716-446655440001',
            'tenant_category_id' => 1,
            'name' => 'GOR Jaya Abadi',
            'slug' => 'gor-jaya',
            'custom_domain' => 'gorjaya.com',
            'theme_code' => 'light_neon',
            'is_suspended' => false,
            'is_active' => true,
            'version' => 1,
            'created_by' => 1,
            'updated_by' => 1,
            'deleted_by' => null,
            'created_at' => 1713160000,
            'updated_at' => 1713160000,
            'deleted_at' => null,
        ]);

        Tenant::create([
            'uuid' => '550e8400-e29b-41d4-a716-446655440002',
            'tenant_category_id' => 2,
            'name' => 'Klinik Sehat Bersama',
            'slug' => 'klinik-sehat',
            'custom_domain' => null,
            'theme_code' => 'clean_medical',
            'is_suspended' => true,
            'is_active' => true,
            'version' => 1,
            'created_by' => 1,
            'updated_by' => 1,
            'deleted_by' => null,
            'created_at' => 1713250000,
            'updated_at' => 1713250000,
            'deleted_at' => null,
        ]);

        Tenant::create([
            'uuid' => '550e8400-e29b-41d4-a716-446655440003',
            'tenant_category_id' => 5,
            'name' => 'PT Manufaktur Maju',
            'slug' => 'manufaktur-maju',
            'custom_domain' => 'majumanufaktur.co.id',
            'theme_code' => 'dark_corporate',
            'is_suspended' => false,
            'is_active' => true,
            'version' => 1,
            'created_by' => 1,
            'updated_by' => 1,
            'deleted_by' => null,
            'created_at' => 1713350000,
            'updated_at' => 1713350000,
            'deleted_at' => null,
        ]);
    }
}
