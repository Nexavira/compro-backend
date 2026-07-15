<?php

namespace Database\Seeders;

use App\Models\GlobalTemplate;
use Illuminate\Database\Seeder;

class GlobalTemplateAboutPageSeeder extends Seeder
{
  /**
   * Run the database seeds.
   */
  public function run(): void
  {
    $filePath = database_path('seeders/about-template-data.json');
    $jsonString = file_get_contents($filePath);
    $aboutDataArray = collect(json_decode($jsonString, true));

    foreach ($aboutDataArray['data'] as $index => $data) {
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
          $template = GlobalTemplate::where('slug', 'food-and-beverage')->first();

          \App\Models\GlobalTemplatePage::create([
            'global_template_id' => $template->id,
            'title' => 'About',
            'slug' => 'about',
            'content_blocks' => $contentBlocks,
            'meta' => ['seo' => $data['seo'] ?? []],
            'is_active' => 1,
          ]);
          break;

        // case 1:
        //   $tenantCategory = \App\Models\Tenant\TenantCategory::where('code', 'CMP')->first();

        //   $template = GlobalTemplate::create(
        //     [
        //       'title' => 'Company Profile',
        //       'slug' => 'company-profile',
        //       'tenant_category_id' => $tenantCategory?->id,
        //       'description' => 'A Sophisticated Blueprint of Innovation template suitable for corporate identities',
        //       'brand_settings' => $brandSettings,
        //       'is_active' => 1,
        //     ]
        //   );

        //   \App\Models\GlobalTemplatePage::create([
        //     'global_template_id' => $template->id,
        //     'title' => 'Home',
        //     'slug' => 'home',
        //     'content_blocks' => $contentBlocks,
        //     'meta' => ['seo' => $data['seo'] ?? []],
        //     'is_active' => 1,
        //   ]);
        //   break;

        // case 2:
          $tenantCategory = \App\Models\Tenant\TenantCategory::where('code', 'RET')->first();

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

          \App\Models\GlobalTemplatePage::create([
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
