<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class RoleHasPermissionsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {


        \DB::table('role_has_permissions')->delete();

        \DB::unprepared('SET IDENTITY_INSERT role_has_permissions ON');

        \DB::table('role_has_permissions')->insert(array (
            0 =>
            array (
                'permission_id' => '1',
                'role_id' => '1',
            ),
            1 =>
            array (
                'permission_id' => '1',
                'role_id' => '2',
            ),
            2 =>
            array (
                'permission_id' => '2',
                'role_id' => '1',
            ),
            3 =>
            array (
                'permission_id' => '2',
                'role_id' => '2',
            ),
        ));

        \DB::unprepared('SET IDENTITY_INSERT role_has_permissions OFF');

    }
}
