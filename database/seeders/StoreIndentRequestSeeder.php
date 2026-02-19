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
        DB::table('store_indent_requests')->delete();
        // Dummy data for store_indent_requests
        $storeIndentData = [
            'warehouse_id' => 1, // Replace with a valid warehouse ID
            'store_id' => 1, // Replace with a valid store ID
            'request_code' => 'IR123456',
            'request_date' => Carbon::now()->format('Y-m-d'),
            'expected_date' => Carbon::now()->addDays(5)->format('Y-m-d'),
            'stock_transferred' => 1, // Assume stock is transferred
            'status' => 1,
            'total_request_quantity' => 10,
            'remarks' => 'Dummy data for seeding',
            'file' => 'https://example.com/file.jpg', // Example image URL
            'file_path' => 'path/to/file.jpg', // Example file path
        ];

        $storeIndent = StoreIndentRequest::create($storeIndentData);

        // Dummy product details
        $products = [
            [
                'product_id' => 1, // Replace with a valid product ID
                'unit_id' => 1, // Replace with a valid unit ID
                'quantity' => 10,
            ],

        ];

        ('Store indent request and related data seeded!');
    }
}
