<?php

namespace Database\Seeders;

use App\Models\TransportType;
use Illuminate\Database\Seeder;

class TransportTypesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {


        // \DB::table('transport_types')->delete();

        \DB::unprepared('SET IDENTITY_INSERT transport_types ON');

        // \DB::table('transport_types')->insert(array (
        //     0 =>
        //     array (
        //         'id' => '1',
        //         'transport_type' => 'Bus',
        //         'status' => '1',
        //         'created_by' => '1',
        //         'updated_by' => '1',
        //         'created_at' => '2023-04-13 10:52:00.510',
        //         'updated_at' => '2023-04-13 10:52:00.510',
        //         'deleted_at' => NULL,
        //     ),
        //     1 =>
        //     array (
        //         'id' => '2',
        //         'transport_type' => 'Tata Ace22',
        //         'status' => '0',
        //         'created_by' => '1',
        //         'updated_by' => '1',
        //         'created_at' => '2023-04-13 10:52:06.993',
        //         'updated_at' => '2023-04-13 10:53:42.067',
        //         'deleted_at' => '2023-04-13 10:53:42.067',
        //     ),
        //     2 =>
        //     array (
        //         'id' => '3',
        //         'transport_type' => 'Lorry',
        //         'status' => '0',
        //         'created_by' => '1',
        //         'updated_by' => '1',
        //         'created_at' => '2023-04-13 10:52:18.840',
        //         'updated_at' => '2023-04-13 10:52:18.840',
        //         'deleted_at' => NULL,
        //     ),
        //     3 =>
        //     array (
        //         'id' => '4',
        //         'transport_type' => 'Bike',
        //         'status' => '0',
        //         'created_by' => '1',
        //         'updated_by' => '1',
        //         'created_at' => '2023-04-13 10:52:27.250',
        //         'updated_at' => '2023-04-13 10:52:27.250',
        //         'deleted_at' => NULL,
        //     ),
        //     4 =>
        //     array (
        //         'id' => '5',
        //         'transport_type' => 'Tata Ace',
        //         'status' => '1',
        //         'created_by' => '1',
        //         'updated_by' => '1',
        //         'created_at' => '2023-04-13 10:54:18.217',
        //         'updated_at' => '2023-04-13 10:54:18.217',
        //         'deleted_at' => NULL,
        //     ),
        // ));

        \DB::unprepared('SET IDENTITY_INSERT transport_types OFF');
        $transport_types = array(
            array(
                "transport_type" => "Bike",
                "status" => 1,
                "created_by" => 1,
                "updated_by" => 1,
                "created_at" => "2024-04-12T05:20:31.960Z",
                "updated_at" => "2024-04-12T05:20:31.960Z",
                "deleted_at" => null
            ),
            array(
                "transport_type" => "Motorcycle",
                "status" => 1,
                "created_by" => 1,
                "updated_by" => 1,
                "created_at" => "2024-07-02T09:39:40.797Z",
                "updated_at" => "2024-07-02T09:39:40.797Z",
                "deleted_at" => null
            ),
            array(
                "transport_type" => "Truck",
                "status" => 1,
                "created_by" => 1,
                "updated_by" => 1,
                "created_at" => "2024-07-02T09:39:56.513Z",
                "updated_at" => "2024-07-02T09:39:56.513Z",
                "deleted_at" => null
            )
        );
        foreach ($transport_types as $transport_type) {
            TransportType::create($transport_type);
        }
    }
}
