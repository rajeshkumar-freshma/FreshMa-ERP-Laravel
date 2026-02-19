<?php

namespace Database\Seeders;

use App\Models\Warehouse;
use Illuminate\Database\Seeder;

class WarehousesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {

        // \DB::table('warehouses')->delete();

        \DB::unprepared('SET IDENTITY_INSERT warehouses ON');

        \DB::table('warehouses')->insert(array (
            0 =>
            array (
                'id' => '1',
                'name' => 'Chennai',
                'slug' => 'chennai',
                'code' => 'WHC-2400001',
                'phone_number' => '9488617831',
                'email' => 'amarendiran.s@codetez.com',
                'start_date' => '2023-04-03',
                'address' => 'Saidapet',
                'city_id' => '3659',
                'state_id' => '35',
                'country_id' => '101',
                'pincode' => '600015',
                'longitude' => '80.26736490545412',
                'latitude' => '13.09898914336229',
                'direction' => NULL,
                'status' => '1',
                'created_by' => '1',
                'updated_by' => '1',
                'created_at' => '2023-04-06 12:12:21.953',
                'updated_at' => '2023-04-06 13:39:52.763',
                'deleted_at' => '',
            )
        ));

        \DB::unprepared('SET IDENTITY_INSERT warehouses OFF');
        // $warehouses = [
        //     ['RRK RETAIL PVT LTD-CHINTARDRIPET',
        //         'rrk-retail-pvt-ltd-chintardripet',
        //         'WH-2400001', '9841262993',
        //         'Rajeshkumar@freshma.in',
        //         '2024-04-01 00:00:00.0000000',
        //         'NO.15, W COOVAM RIVER ROAD, ADIKESAVARPURAM,CHINTARDRIPET,CHENNAI-600002.',
        //         3659,
        //         35,
        //         101,
        //         '600002',
        //         '80° 16 06.3 E',
        //         '13° 04 45.4 N',
        //         null,
        //         1,
        //         1,
        //         1,
        //         1,
        //         '2024-04-12 10:30:17.45',
        //         '2024-05-04 13:16:01.097',
        //         null],
        // ];
        // foreach ($warehouses as $warehouse) {
        //     Warehouse::create([
        //         'name' => $warehouse[0],
        //         'slug' => $warehouse[1],
        //         'code' => $warehouse[2],
        //         'phone_number' => $warehouse[3],
        //         'email' => $warehouse[4],
        //         'start_date' => $warehouse[5],
        //         'address' => $warehouse[6],
        //         'city_id' => $warehouse[7],
        //         'state_id' => $warehouse[8],
        //         'country_id' => $warehouse[9],
        //         'pincode' => $warehouse[10],
        //         'longitude' => $warehouse[11],
        //         'latitude' => $warehouse[12],
        //         'direction' => $warehouse[13],
        //         'status' => $warehouse[14],
        //         'is_default' => $warehouse[15],
        //         'created_by' => $warehouse[16],
        //         'updated_by' => $warehouse[17],
        //         'created_at' => $warehouse[18],
        //         'updated_at' => $warehouse[19],
        //         'deleted_at' => $warehouse[20],
        //     ]);
        // }
    }
}
