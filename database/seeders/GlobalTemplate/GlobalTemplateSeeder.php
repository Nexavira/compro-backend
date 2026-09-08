<?php

namespace Database\Seeders\GlobalTemplate;

use App\Models\Cms\GlobalTemplate;
use Illuminate\Database\Seeder;

class GlobalTemplateSeeder extends Seeder
{

  public function run(): void
  {
    $filePath = database_path('seeders/GlobalTemplate/JsonData/setting-data.json');
    $jsonString = file_get_contents($filePath);
    $settingDataArray = collect(json_decode($jsonString, true));


    GlobalTemplate::create([
      'tenant_category_id' => 1,
      'package_id' => 1,
      'tier' => 'basic',
      'title' => 'Home Template',
      'description' => 'This is the default template for all tenants.',
      'is_active' => 1,
      'brand_settings' => json_encode($settingDataArray['data']),
    ]);
  }
}
