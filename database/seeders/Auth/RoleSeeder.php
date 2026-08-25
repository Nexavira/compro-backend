<?php

namespace Database\Seeders\Auth;

use App\Models\Auth\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        $datas =
            [
                [
                    'name' => 'Master Admin',
                    'code' => 'master_admin',
                    'description' => 'Ini adalah role Master Admin',
                ],
                [
                    'name' => 'Internal',
                    'code' => 'internal',
                    'description' => 'Ini adalah role Internal',
                ],
                [
                    'name' => 'Owner',
                    'code' => 'owner',
                    'description' => 'Ini adalah role Owner Tenant',
                ],
                [
                    'name' => 'Staff',
                    'code' => 'staff',
                    'description' => 'Ini adalah role Staff Tenant',
                ]
            ];

        foreach ($datas as $data) {
            Role::create([
                'name' => $data['name'],
                'code' => $data['code'],
                'description' => $data['description'],
            ]);
        }
    }
}
