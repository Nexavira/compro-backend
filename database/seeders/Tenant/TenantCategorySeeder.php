<?php

namespace Database\Seeders\Tenant;

use App\Models\Tenant\TenantCategory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TenantCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Financial',
                'code' => 'FIN',
                'description' => 'Financial Services and Institutions',
            ],
            [
                'name' => 'Healthcare',
                'code' => 'HLT',
                'description' => 'Healthcare and Medical Services',
            ],
            [
                'name' => 'Logistics',
                'code' => 'LOG',
                'description' => 'Logistics and Supply Chain',
            ],
            [
                'name' => 'Education',
                'code' => 'EDU',
                'description' => 'Educational Institutions',
            ],
            [
                'name' => 'Manufacturing',
                'code' => 'MFG',
                'description' => 'Manufacturing and Operations',
            ],
            [
                'name' => 'Technology',
                'code' => 'TEC',
                'description' => 'Technology and Software Services',
            ]
        ];

        foreach ($categories as $category) {
            TenantCategory::create($category);
        }
    }
}
