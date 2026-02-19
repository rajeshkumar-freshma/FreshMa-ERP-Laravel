<?php

namespace Database\Seeders;

use App\Models\FishCutting;
use Illuminate\Database\Seeder; // Make sure this matches the namespace of your FishCutting model
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class FishCuttingTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        DB::table('fish_cuttings')->delete();

        DB::beginTransaction();

        // DB::unprepared('SET IDENTITY_INSERT fish_cuttings ON');

        // Dummy data for FishCutting
        $fishCuttings = [
            ['weight' => 50, 'product_id' => 1, 'store_id' => 1, 'cutting_date' => Carbon::now()->subDays(10)->format('Y-m-d'), 'status' => 1, 'remarks' => 'Initial cutting.'],
        ];

        foreach ($fishCuttings as $fishCutting) {
            $fishCuttingRecord = new FishCutting();
            $fishCuttingRecord->weight = $fishCutting['weight'];
            $fishCuttingRecord->product_id = $fishCutting['product_id'];
            $fishCuttingRecord->store_id = $fishCutting['store_id'];
            $fishCuttingRecord->cutting_date = $fishCutting['cutting_date']; // Ensure date format is correct
            $fishCuttingRecord->status = $fishCutting['status'];
            $fishCuttingRecord->remarks = $fishCutting['remarks'];
            $fishCuttingRecord->save();
        }

        // DB::unprepared('SET IDENTITY_INSERT fish_cuttings OFF');

        DB::commit();

    }
}
