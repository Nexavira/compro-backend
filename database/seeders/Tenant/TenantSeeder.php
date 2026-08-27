<?php

namespace Database\Seeders\Tenant;

use App\Models\Tenant\Tenant;
use App\Models\Tenant\TenantCategory;
use App\Models\GlobalTemplate;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TenantSeeder extends Seeder
{

    public function run(): void
    {
        $tenant_category_technology = TenantCategory::where('code', 'FNB')->first();
        $etoile_template = GlobalTemplate::where('is_active', 1)->first();
        Tenant::create([
            'tenant_category_id' => $tenant_category_technology->id,
            'name' => 'Nexavira',
            'slug' => 'nexavira',
            'custom_domain' => 'nexavira.com',
            'global_template_id' => $etoile_template?->id,
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
    }
}
