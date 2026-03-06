<?php

namespace Database\Seeders;

use App\Models\FishCutting;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Throwable;

class FishCuttingTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        // DB::table('fish_cuttings')->truncate();
        $productId = DB::table('products')->value('id');
        if (!$productId) {
            $this->call(ProductTableSeeder::class);
            $productId = DB::table('products')->value('id');
        }

        $storeId = DB::table('stores')->value('id');
        if (!$storeId) {
            $this->call(StoresTableSeeder::class);
            $storeId = DB::table('stores')->value('id');
        }

        if (!$productId || !$storeId) {
            throw new \RuntimeException('FishCutting seeder requires at least one product and one store.');
        }

        DB::beginTransaction();

        try {
            // DB::unprepared('SET IDENTITY_INSERT fish_cuttings ON');

            // Dummy data for FishCutting
            $fishCuttings = [
                ['weight' => 50, 'product_id' => $productId, 'store_id' => $storeId, 'cutting_date' => Carbon::now()->subDays(10)->format('Y-m-d'), 'status' => 1, 'remarks' => 'Initial cutting.'],
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
        } catch (Throwable $exception) {
            DB::rollBack();
            throw $exception;
        }

    }
}
