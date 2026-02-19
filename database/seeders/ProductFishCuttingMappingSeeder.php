<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\FishCuttingProductMap;

class ProductFishCuttingMappingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('fish_cutting_product_maps')->delete();
        // Dummy data for fish cutting mapping
        $fishCuttingData = [
            'main_product_id' => 1, // Replace with a valid product ID from your products table
            'status' => 1, // 1 for active, 0 for inactive
            'grouped_product' => json_encode([
                ['product_id' => 2, 'type' => 1, 'percentage' => 80],
                ['product_id' => 3, 'type' => 2, 'percentage' => 10]
            ]),
            'wastage_percentage' => 10, // Example wastage percentage
            'remarks' => 'Initial dummy data for fish cutting mapping',
            'created_at' => now(),
            'updated_at' => now(),
        ];

        // Insert dummy data into the fish_cutting_product_maps table
        FishCuttingProductMap::create($fishCuttingData);

    }
}
