<?php

namespace Tests\Unit\Seeders;

use App\Models\Admin;
use Database\Seeders\PermissionGroupTableSeeder;
use Database\Seeders\PermissionsTableSeeder;
use Database\Seeders\RolesTableSeeder;
use Database\Seeders\UsersSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class RolesSeederTest extends TestCase
{
    use RefreshDatabase;

    public function test_roles_seeder_assigns_all_admin_permissions_to_super_admin(): void
    {
        $this->seed(UsersSeeder::class);
        $this->seed(PermissionGroupTableSeeder::class);
        $this->seed(PermissionsTableSeeder::class);
        $this->seed(RolesTableSeeder::class);

        $superAdminRole = Role::query()
            ->where('name', 'Super Admin')
            ->where('guard_name', 'admin')
            ->firstOrFail();

        $allAdminPermissionNames = Permission::query()
            ->where('guard_name', 'admin')
            ->pluck('name')
            ->sort()
            ->values()
            ->all();

        $superAdminPermissionNames = $superAdminRole->permissions()
            ->pluck('name')
            ->sort()
            ->values()
            ->all();

        $this->assertSame($allAdminPermissionNames, $superAdminPermissionNames);
    }

    public function test_roles_seeder_assigns_super_admin_role_to_first_admin(): void
    {
        $this->seed(UsersSeeder::class);
        $this->seed(PermissionGroupTableSeeder::class);
        $this->seed(PermissionsTableSeeder::class);
        $this->seed(RolesTableSeeder::class);

        $firstAdmin = Admin::query()->firstOrFail();
        $this->assertTrue($firstAdmin->hasRole('Super Admin'));
    }
}
