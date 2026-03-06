<?php

namespace Database\Seeders;

use App\Models\Admin;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolesTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('roles')->truncate();
        DB::table('role_has_permissions')->truncate();

        // Populate roles
        $roles = [
            [
                'name' => 'Super Admin',
                'guard_name' => 'admin',
            ],
            [
                'name' => 'Admin',
                'guard_name' => 'admin',
            ],
            [
                'name' => 'Manager',
                'guard_name' => 'admin',
            ],
            [
                'name' => 'Store Manager',
                'guard_name' => 'admin',
            ],
        ];

        foreach ($roles as $roleData) {
            Role::query()->updateOrCreate(
                [
                    'name' => $roleData['name'],
                    'guard_name' => $roleData['guard_name'],
                ]
            );
        }

        // Assign all permissions in a single sync instead of one-by-one.
        $superAdminRole = Role::query()
            ->where('name', 'Super Admin')
            ->where('guard_name', 'admin')
            ->first();

        if ($superAdminRole) {
            $permissions = Permission::query()
                ->where('guard_name', 'admin')
                ->pluck('name')
                ->all();
            $superAdminRole->syncPermissions($permissions);
        }

        $user = Admin::query()->first();
        if ($user && $superAdminRole) {
            $user->syncRoles([$superAdminRole->name]);
        }
    }
}
