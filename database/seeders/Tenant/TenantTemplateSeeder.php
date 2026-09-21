<?php

namespace Database\Seeders\Tenant;

use App\Models\Cms\GlobalTemplate;
use App\Models\CMS\TenantTemplate;
use App\Models\Tenant\Tenant;
use Illuminate\Database\Seeder;

class TenantTemplateSeeder extends Seeder
{
    public function run(): void
    {
        $tenant = Tenant::first();
        $globalTemplate = GlobalTemplate::first();

        if ($tenant) {
            TenantTemplate::create([
                'tenant_id' => $tenant->id,
                'global_template_id' => $globalTemplate?->id,
                'template_settings' => [
                    'theme' => 'light',
                    'primary_color' => '#007bff',
                    'font_family' => 'Inter',
                ],
                'is_active' => 1,
            ]);
        }
    }
}

