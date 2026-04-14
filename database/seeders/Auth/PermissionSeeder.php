<?php

namespace Database\Seeders\Auth;

use App\Models\Auth\Permission;
use App\Models\Auth\Role;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        $files = ['admin', 'web', 'mobile'];

        $masterRole = Role::where('code', 'master_admin')->first();

        foreach ($files as $guard) {
            $path = database_path("data/permissions/{$guard}.json");

            if (!File::exists($path)) {
                $this->command->warn("File seeder untuk guard [{$guard}] tidak ditemukan. Skip...");
                continue;
            }

            $permissionsData = json_decode(File::get($path), true);

            foreach ($permissionsData as $data) {
                $code = Str::lower(
                    $guard . '_' . 
                    Str::slug($data['prefix_label'], '_') . '_' . 
                    Str::slug($data['module_label'], '_') . '_' . 
                    Str::slug($data['action_label'], '_')
                );

                $permission = Permission::updateOrCreate(
                    ['code' => $code],
                    [
                        'uuid' => (string) Str::uuid(), 
                        'guard_name' => $guard,
                        'prefix_label' => $data['prefix_label'],
                        'module_label' => $data['module_label'],
                        'action_label' => $data['action_label'],
                        'is_active' => 1,
                        'version' => 1,
                    ]
                );

                $masterRole->permissions()->syncWithoutDetaching([$permission->id]);
            }            
        }
    }
}