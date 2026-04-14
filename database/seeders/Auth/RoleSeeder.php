<?php

namespace Database\Seeders\Auth;

use App\Models\Auth\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        Role::create([
            'name' => 'Master Admin',
            'code' => 'master_admin',
            'description' => 'Ini adalah role Master Admin',
        ]);
    }
}