<?php

namespace Database\Seeders;

use App\Models\Unit;
use Illuminate\Database\Seeder;

class UnitsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {

        $units = array(
            array(
                "unit_name" => "Kilogram",
                "unit_short_code" => "Kg",
                "base_unit" => "Kilo",
                "allow_decimal" => 2.00,
                "operator" => "*",
                "operation_value" => 1.00,
                "status" => 1,
                "created_by" => 1,
                "updated_by" => 1,
                "created_at" => "2024-04-11T09:36:23.090Z",
                "updated_at" => "2024-04-11T09:36:23.090Z",
                "deleted_at" => null,
                "default" => 1
            )
        );
        foreach ($units as $unit) {
            Unit::create($unit);
        }
        // \DB::table('units')->delete();

        \DB::unprepared('SET IDENTITY_INSERT units ON');

        // \DB::table('units')->insert(array (
        //     0 =>
        //     array (
        //         'id' => '1',
        //         'unit_name' => 'KiloGram',
        //         'unit_short_code' => 'KG',
        //         'base_unit' => 'KiloGram',
        //         'allow_decimal' => '100',
        //         'operator' => '*',
        //         'operation_value' => '1000',
        //         'status' => '1',
        //         'created_by' => '1',
        //         'updated_by' => '1',
        //         'created_at' => '2023-04-07 07:18:32.017',
        //         'updated_at' => '2023-04-07 07:18:58.567',
        //         'deleted_at' => '2023-04-07 07:18:58.567',
        //     ),
        //     1 =>
        //     array (
        //         'id' => '2',
        //         'unit_name' => 'Gram',
        //         'unit_short_code' => 'gram',
        //         'base_unit' => 'Gram',
        //         'allow_decimal' => '0',
        //         'operator' => '*',
        //         'operation_value' => '1',
        //         'status' => '1',
        //         'created_by' => '1',
        //         'updated_by' => '1',
        //         'created_at' => '2023-04-07 07:18:52.853',
        //         'updated_at' => '2023-04-07 07:18:52.853',
        //         'deleted_at' => NULL,
        //     ),
        //     2 =>
        //     array (
        //         'id' => '3',
        //         'unit_name' => 'KiloGram',
        //         'unit_short_code' => 'KG',
        //         'base_unit' => 'KiloGram',
        //         'allow_decimal' => '100',
        //         'operator' => '*',
        //         'operation_value' => '1000',
        //         'status' => '1',
        //         'created_by' => '1',
        //         'updated_by' => '1',
        //         'created_at' => '2023-04-07 07:23:25.477',
        //         'updated_at' => '2023-04-07 07:23:25.477',
        //         'deleted_at' => NULL,
        //     ),
        // ));

        \DB::unprepared('SET IDENTITY_INSERT units OFF');


    }
}
