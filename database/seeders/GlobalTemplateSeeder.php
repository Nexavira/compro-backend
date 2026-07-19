<?php

namespace Database\Seeders;

use App\Models\GlobalTemplate;
use App\Models\GlobalTemplatePage;
use App\Models\Tenant\TenantCategory;
use Illuminate\Database\Seeder;

class GlobalTemplateSeeder extends Seeder
{

  public function run(): void
  {

    $filePath = database_path('seeders/home-template-data.json');
    $jsonString = file_get_contents($filePath);
    $homeDataArray = collect(json_decode($jsonString, true));

    foreach ($homeDataArray['data'] as $index => $data) {
      $brandSettings = [
        'brand' => $data['brand'] ?? [],
        'social' => $data['social'] ?? [],
      ];

      $contentBlocks = [];
      if (isset($data['sections'])) {
        foreach ($data['sections'] as $type => $sectionData) {
          $contentBlocks[] = [
            'type' => $type,
            'data' => $sectionData
          ];
        }
      }

      switch ($index) {
        case 0:
          $tenantCategory = TenantCategory::where('code', 'FNB')->first();

          $template = GlobalTemplate::create(
            [
              'title' => 'Food and Beverage',
              'slug' => 'food-and-beverage',
              'tenant_category_id' => $tenantCategory?->id,
              'description' => 'A Modern Culinary Symphony template suitable for fine dining.',
              'brand_settings' => $brandSettings,
              'is_active' => 1,
            ]
          );

          GlobalTemplatePage::create([
            'global_template_id' => $template->id,
            'title' => 'Home',
            'slug' => 'home',
            'content_blocks' => $contentBlocks,
            'meta' => ['seo' => $data['seo'] ?? []],
            'is_active' => 1,
          ]);
          break;

        case 1:
          $tenantCategory = TenantCategory::where('code', 'CMP')->first();

          $template = GlobalTemplate::create(
            [
              'title' => 'Company Profile',
              'slug' => 'company-profile',
              'tenant_category_id' => $tenantCategory?->id,
              'description' => 'A Sophisticated Blueprint of Innovation template suitable for corporate identities',
              'brand_settings' => $brandSettings,
              'is_active' => 1,
            ]
          );

          GlobalTemplatePage::create([
            'global_template_id' => $template->id,
            'title' => 'Home',
            'slug' => 'home',
            'content_blocks' => $contentBlocks,
            'meta' => ['seo' => $data['seo'] ?? []],
            'is_active' => 1,
          ]);
          break;

        case 2:
          $tenantCategory = TenantCategory::where('code', 'RET')->first();

          $template = GlobalTemplate::create(
            [
              'title' => 'Retail',
              'slug' => 'retail',
              'tenant_category_id' => $tenantCategory?->id,
              'description' => 'A Vibrant Commerce Canvas template suitable for modern storefronts',
              'brand_settings' => $brandSettings,
              'is_active' => 1,
            ]
          );

          GlobalTemplatePage::create([
            'global_template_id' => $template->id,
            'title' => 'Home',
            'slug' => 'home',
            'content_blocks' => $contentBlocks,
            'meta' => ['seo' => $data['seo'] ?? []],
            'is_active' => 1,
          ]);
          break;
      }

    }
  }
}
