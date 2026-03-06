<?php

namespace Database\Seeders;

use App\Models\Admin;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class ModelHasRolesTableSeeder extends Seeder
{

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $superAdminRole = Role::query()
            ->where('name', 'Super Admin')
            ->where('guard_name', 'admin')
            ->first();

        $admin = Admin::query()->where('user_type', 1)->orderBy('id')->first();

        if ($admin && $superAdminRole) {
            $admin->syncRoles([$superAdminRole->name]);
        }
    }
}
