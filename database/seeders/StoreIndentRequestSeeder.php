<?php

namespace Database\Seeders;

use App\Models\StoreIndentRequest;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class StoreIndentRequestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
   public function run()
{
    DB::table('store_indent_requests')->truncate();

    $warehouse = DB::table('warehouses')->first();
    $store = DB::table('stores')->first();
    $product = DB::table('products')->first();
    $unit = DB::table('units')->first();

    if (!$warehouse) {
        $this->call(WarehousesTableSeeder::class);
        $warehouse = DB::table('warehouses')->first();
    }
    if (!$store) {
        $this->call(StoresTableSeeder::class);
        $store = DB::table('stores')->first();
    }
    if (!$product) {
        $this->call(ProductTableSeeder::class);
        $product = DB::table('products')->first();
    }
    if (!$unit) {
        $this->call(UnitsTableSeeder::class);
        $unit = DB::table('units')->first();
    }

    if (!$warehouse || !$store || !$product || !$unit) {
        $this->command->error('Required master data missing. Seed warehouses, stores, products, and units first.');
        return;
    }

    $storeIndent = StoreIndentRequest::create([
        'warehouse_id' => $warehouse->id,
        'store_id' => $store->id,
        'request_code' => 'IR123456',
        'request_date' => now()->toDateString(),
        'expected_date' => now()->addDays(5)->toDateString(),
        'stock_transferred' => 1,
        'status' => 1,
        'total_request_quantity' => 10,
        'remarks' => 'Dummy data for seeding',
        'file' => 'https://example.com/file.jpg',
        'file_path' => 'path/to/file.jpg',
    ]);

    // If you have relation like:
    // store_indent_request_products table

    $storeIndent->store_indent_product_details()->create([
        'product_id' => $product->id,
        'name' => $product->name ?? 'Sample product',
        'sku_code' => $product->sku_code ?? 'SKU-SEED',
        'unit_id' => $unit->id,
        'request_quantity' => 10,
        'given_quantity' => 10,
    ]);

    $this->command->info('Store indent request seeded successfully!');
}
}
