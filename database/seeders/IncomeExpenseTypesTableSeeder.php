<?php

namespace Database\Seeders;

use App\Models\IncomeExpenseType;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class IncomeExpenseTypesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {

        DB::table('income_expense_types')->delete();

        DB::beginTransaction();

        // DB::unprepared('SET IDENTITY_INSERT income_expense_types ON');

        // \DB::table('income_expense_types')->insert(array (
        //     0 =>
        //     array (
        //         'id' => '1',
        //         'name' => 'Food Cost',
        //         'type' => '2',
        //         'status' => '1',
        //         'created_by' => '1',
        //         'updated_by' => '1',
        //         'created_at' => '2023-04-07 07:23:49.947',
        //         'updated_at' => '2023-04-07 07:23:49.947',
        //         'deleted_at' => NULL,
        //     ),
        //     1 =>
        //     array (
        //         'id' => '2',
        //         'name' => 'Vehicle Rent',
        //         'type' => '1',
        //         'status' => '1',
        //         'created_by' => '1',
        //         'updated_by' => '1',
        //         'created_at' => '2023-04-07 07:24:12.977',
        //         'updated_at' => '2023-04-07 07:24:29.820',
        //         'deleted_at' => '2023-04-07 07:24:29.820',
        //     ),
        //     2 =>
        //     array (
        //         'id' => '3',
        //         'name' => 'Petrol',
        //         'type' => '2',
        //         'status' => '1',
        //         'created_by' => '1',
        //         'updated_by' => '1',
        //         'created_at' => '2023-04-07 07:24:24.587',
        //         'updated_at' => '2023-04-07 07:25:25.250',
        //         'deleted_at' => '2023-04-07 07:25:25.250',
        //     ),
        //     3 =>
        //     array (
        //         'id' => '4',
        //         'name' => 'Petrol',
        //         'type' => '1',
        //         'status' => '1',
        //         'created_by' => '1',
        //         'updated_by' => '1',
        //         'created_at' => '2023-04-07 07:24:59.077',
        //         'updated_at' => '2023-04-07 07:25:35.047',
        //         'deleted_at' => NULL,
        //     ),
        // ));

        $income_expense_types = array(
            array(

                "name" => "Petrol/Diesel",
                "type" => 2,
                "status" => 1,
                "created_by" => 1,
                "updated_by" => 1,
                "created_at" => "2024-04-12T05:19:02.197Z",
                "updated_at" => "2024-04-12T05:19:02.197Z",
                "deleted_at" => null,
            ),
            array(

                "name" => "Electricity",
                "type" => 2,
                "status" => 1,
                "created_by" => 1,
                "updated_by" => 1,
                "created_at" => "2024-04-12T05:19:26.593Z",
                "updated_at" => "2024-04-12T05:19:26.593Z",
                "deleted_at" => null,
            ),
        );
        foreach ($income_expense_types as $income_expense_type) {
            IncomeExpenseType::create($income_expense_type);
        }

        // DB::unprepared('SET IDENTITY_INSERT income_expense_types OFF');
        
        DB::commit();
    }
}
