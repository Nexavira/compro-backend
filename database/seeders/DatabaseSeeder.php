<?php

namespace Database\Seeders;

use Database\Seeders\Auth\PermissionSeeder;
use Database\Seeders\Auth\RoleSeeder;
use Database\Seeders\Auth\UserSeeder;
use Database\Seeders\Master\PackageSeeder;
use Database\Seeders\Tenant\TenantCategorySeeder;
use Database\Seeders\Tenant\TenantSeeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        $this->call([
            RoleSeeder::class,
            TenantCategorySeeder::class,
            // GlobalTemplateSeeder::class,
            // GlobalTemplateAboutPageSeeder::class,
            TenantSeeder::class,
            UserSeeder::class,
            PermissionSeeder::class,
            PackageSeeder::class,
        ]);
    }
}
