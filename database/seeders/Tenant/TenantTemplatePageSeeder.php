<?php

namespace Database\Seeders\Tenant;

use App\Models\CMS\TenantTemplate;
use App\Models\CMS\TenantTemplatePage;
use Illuminate\Database\Seeder;

class TenantTemplatePageSeeder extends Seeder
{
    public function run(): void
    {
        $tenantTemplate = TenantTemplate::first();

        if ($tenantTemplate) {
            TenantTemplatePage::create([
                'tenant_template_id' => $tenantTemplate->id,
                'title' => 'Home Page',
                'slug' => 'home',
                'template_data' => [
                    'hero_title' => 'Welcome to Nexavira',
                    'hero_subtitle' => 'Building modern digital solutions',
                ],
                'is_active' => 1,
            ]);

            TenantTemplatePage::create([
                'tenant_template_id' => $tenantTemplate->id,
                'title' => 'About Us',
                'slug' => 'about-us',
                'template_data' => [
                    'content' => 'Nexavira is a leading technology company.',
                ],
                'is_active' => 1,
            ]);
        }
    }
}

