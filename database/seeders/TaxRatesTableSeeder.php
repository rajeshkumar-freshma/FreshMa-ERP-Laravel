<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class TaxRatesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {


        \DB::table('tax_rates')->delete();

        \DB::unprepared('SET IDENTITY_INSERT tax_rates ON');

        \DB::table('tax_rates')->insert(array (
            0 =>
            array (
                'id' => '1',
                'tax_name' => 'GST',
                'tax_rate' => '18%',
                'status' => '1',
                'created_by' => '1',
                'updated_by' => '1',
                'created_at' => '2023-04-07 07:36:30.207',
                'updated_at' => '2023-04-07 07:36:30.207',
                'deleted_at' => NULL,
            ),
            1 =>
            array (
                'id' => '2',
                'tax_name' => 'CGST',
                'tax_rate' => '9%',
                'status' => '0',
                'created_by' => '1',
                'updated_by' => '1',
                'created_at' => '2023-04-07 07:36:41.393',
                'updated_at' => '2023-04-07 07:37:00.797',
                'deleted_at' => '2023-04-07 07:37:00.797',
            ),
            2 =>
            array (
                'id' => '3',
                'tax_name' => 'SGST',
                'tax_rate' => '9%',
                'status' => '0',
                'created_by' => '1',
                'updated_by' => '1',
                'created_at' => '2023-04-07 07:36:52.123',
                'updated_at' => '2023-04-07 07:36:52.123',
                'deleted_at' => NULL,
            ),
            3 =>
            array (
                'id' => '4',
                'tax_name' => 'CGST',
                'tax_rate' => '9%',
                'status' => '1',
                'created_by' => '1',
                'updated_by' => '1',
                'created_at' => '2023-04-07 07:37:09.717',
                'updated_at' => '2023-04-07 07:37:09.717',
                'deleted_at' => NULL,
            ),
        ));

        \DB::unprepared('SET IDENTITY_INSERT tax_rates OFF');


    }
}
