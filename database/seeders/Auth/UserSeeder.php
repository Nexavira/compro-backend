<?php

namespace Database\Seeders\Auth;

use App\Models\Auth\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::create([
            'email' => 'admin@nexavira.com',
            'password' => Hash::make('password'),
            'is_active' => 1,
            'version' => 0,
        ]);

        $admin->userDetail()->create([
            'user_id' => $admin->id,
            'full_name' => 'Master Admin',
            'phone_number' => '08123456789',
        ]);

        $admin->roleUser()->create([
            'user_id' => $admin->id,
            'role_id' => 1,
        ]);
    }
}