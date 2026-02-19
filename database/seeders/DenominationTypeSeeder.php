<?php

namespace Database\Seeders;

use App\Models\DenominationType;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DenominationTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('denominations')->delete();

        DB::beginTransaction();

        DB::unprepared('SET IDENTITY_INSERT currencies ON');

        $denominations = [
            ['type' => '1', 'value' => 2000, 'denomination_code' => 'DN2000', 'description' => 'Two thousand'],
            ['type' => '2', 'value' => 500, 'denomination_code' => 'DN500', 'description' => 'Five hundred'],
            ['type' => '3', 'value' => 200, 'denomination_code' => 'DN200', 'description' => 'Two hundred'],
            ['type' => '4', 'value' => 100, 'denomination_code' => 'DN100', 'description' => 'One hundred'],
            ['type' => '5', 'value' => 50, 'denomination_code' => 'DN050', 'description' => 'Fifty'],
            ['type' => '6', 'value' => 20, 'denomination_code' => 'DN020', 'description' => 'Twenty'],
            ['type' => '7', 'value' => 10, 'denomination_code' => 'DN010', 'description' => 'Ten'],
            ['type' => '8', 'value' => 5, 'denomination_code' => 'DN005', 'description' => 'Five'],
            ['type' => '9', 'value' => 2, 'denomination_code' => 'DN002', 'description' => 'Two'],
            ['type' => '10', 'value' => 1, 'denomination_code' => 'DN001', 'description' => 'One'],

        ];

        DenominationType::insert($denominations);

        DB::unprepared('SET IDENTITY_INSERT denominations OFF');

        DB::commit();
    }
}
