<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ItemTypesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {

        DB::table('item_types')->delete();

        DB::unprepared('SET IDENTITY_INSERT item_types ON');

        DB::beginTransaction();

        DB::table('item_types')->insert(array(
            0 => array(
                'id' => '1',
                'name' => 'Vanjaram',
                'status' => '1',
                'created_by' => '1',
                'updated_by' => '1',
                'created_at' => '2023-04-07 06:59:43.373',
                'updated_at' => '2023-04-07 06:59:43.373',
                'deleted_at' => null,
            ),
            1 => array(
                'id' => '2',
                'name' => 'Seer Fish',
                'status' => '1',
                'created_by' => '1',
                'updated_by' => '1',
                'created_at' => '2023-04-07 06:59:55.183',
                'updated_at' => '2023-04-07 06:59:55.183',
                'deleted_at' => null,
            ),
            2 => array(
                'id' => '3',
                'name' => 'Eral',
                'status' => '1',
                'created_by' => '1',
                'updated_by' => '1',
                'created_at' => '2023-04-07 07:00:02.793',
                'updated_at' => '2023-04-07 07:00:23.787',
                'deleted_at' => '2023-04-07 07:00:23.787',
            ),
            3 => array(
                'id' => '4',
                'name' => 'Koduva',
                'status' => '0',
                'created_by' => '1',
                'updated_by' => '1',
                'created_at' => '2023-04-07 07:00:12.103',
                'updated_at' => '2023-04-07 07:00:12.103',
                'deleted_at' => null,
            ),
            4 => array(
                'id' => '5',
                'name' => 'Eral',
                'status' => '1',
                'created_by' => '1',
                'updated_by' => '1',
                'created_at' => '2023-04-07 07:00:32.163',
                'updated_at' => '2023-04-07 07:00:32.163',
                'deleted_at' => null,
            ),
            5 => array(
                'id' => '6',
                'name' => 'Vanjaram',
                'status' => '1',
                'created_by' => '1',
                'updated_by' => '1',
                'created_at' => '2023-04-07 07:00:47.677',
                'updated_at' => '2023-04-07 07:00:47.677',
                'deleted_at' => null,
            ),
            6 => array(
                'id' => '7',
                'name' => 'Nethili',
                'status' => '1',
                'created_by' => '1',
                'updated_by' => '1',
                'created_at' => '2023-04-07 07:06:12.147',
                'updated_at' => '2023-04-07 07:06:12.147',
                'deleted_at' => null,
            ),
            7 => array(
                'id' => '8',
                'name' => 'Kadamba',
                'status' => '1',
                'created_by' => '1',
                'updated_by' => '1',
                'created_at' => '2023-04-07 07:06:19.047',
                'updated_at' => '2023-04-07 07:06:19.047',
                'deleted_at' => null,
            ),
            8 => array(
                'id' => '9',
                'name' => 'Viral',
                'status' => '1',
                'created_by' => '1',
                'updated_by' => '1',
                'created_at' => '2023-04-07 07:06:26.010',
                'updated_at' => '2023-04-07 07:06:26.010',
                'deleted_at' => null,
            ),
            9 => array(
                'id' => '10',
                'name' => 'Vavval',
                'status' => '1',
                'created_by' => '1',
                'updated_by' => '1',
                'created_at' => '2023-04-07 07:06:34.067',
                'updated_at' => '2023-04-07 07:06:34.067',
                'deleted_at' => null,
            ),
            10 => array(
                'id' => '11',
                'name' => 'Sankara',
                'status' => '1',
                'created_by' => '1',
                'updated_by' => '1',
                'created_at' => '2023-04-07 07:06:41.057',
                'updated_at' => '2023-04-07 07:06:41.057',
                'deleted_at' => null,
            ),
            11 => array(
                'id' => '12',
                'name' => 'Oysters',
                'status' => '1',
                'created_by' => '1',
                'updated_by' => '1',
                'created_at' => '2023-04-07 07:06:50.633',
                'updated_at' => '2023-04-07 07:06:50.633',
                'deleted_at' => null,
            ),
        ));

        DB::unprepared('SET IDENTITY_INSERT item_types OFF');

        DB::commit();
    }
}
