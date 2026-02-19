<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class PartnershipTypesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {


        \DB::table('partnership_types')->delete();

        \DB::unprepared('SET IDENTITY_INSERT partnership_types ON');

        \DB::table('partnership_types')->insert(array (
            0 =>
            array (
                'id' => '1',
                'partnership_name' => '10% Partnership',
                'partnership_percentage' => '10',
                'status' => '1',
                'created_by' => '1',
                'updated_by' => '1',
                'created_at' => '2023-04-07 07:42:01.893',
                'updated_at' => '2023-04-07 07:42:01.893',
                'deleted_at' => NULL,
            ),
            1 =>
            array (
                'id' => '2',
                'partnership_name' => '5% Partnership',
                'partnership_percentage' => '5',
                'status' => '1',
                'created_by' => '1',
                'updated_by' => '1',
                'created_at' => '2023-04-07 07:42:10.880',
                'updated_at' => '2023-04-07 07:42:31.603',
                'deleted_at' => '2023-04-07 07:42:31.603',
            ),
            2 =>
            array (
                'id' => '3',
                'partnership_name' => '11.25% Partnership',
                'partnership_percentage' => '11.25',
                'status' => '0',
                'created_by' => '1',
                'updated_by' => '1',
                'created_at' => '2023-04-07 07:42:26.217',
                'updated_at' => '2023-04-07 07:42:26.217',
                'deleted_at' => NULL,
            ),
        ));

        \DB::unprepared('SET IDENTITY_INSERT partnership_types OFF');


    }
}
