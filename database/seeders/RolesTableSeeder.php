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
        DB::table('roles')->delete();
        DB::table('role_has_permissions')->delete();

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
            $role = new Role();
            $role->name = $roleData['name'];
            $role->guard_name = $roleData['guard_name'];
            $role->save();
        }
        // Assign permissions to roles
        $role = Role::where('name', 'Super Admin')->first();

        $permissions = Permission::get();
        foreach ($permissions as $permission) {
            $role->givePermissionTo($permission->name);
        }

        $superAdminRole = Role::where('name', 'Super Admin')->first();
        $user = Admin::first();
        $user->assignRole($superAdminRole);
    }
}
