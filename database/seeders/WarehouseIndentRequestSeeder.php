<?php

namespace Database\Seeders;

use App\Models\WarehouseIndentRequest;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class WarehouseIndentRequestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('warehouse_indent_requests')->delete();

        // Create a dummy WarehouseIndentRequest
        $indentRequestData = [
            'warehouse_id' => 1, // Replace with a valid warehouse ID
           
            'request_code' => 'WIR0001',
            'request_date' => Carbon::now()->format('Y-m-d'),
            'expected_date' => Carbon::now()->addDays(7)->format('Y-m-d'),
            'status' => 1, // Assume status 1 is 'Pending' or 'Approved'
            'total_request_quantity' => 20,
            'total_amount' => 40000.00,
            'remarks' => 'Dummy warehouse indent request for seeding',
            'file' => 'https://example.com/file.jpg', // Example file URL
            'file_path' => 'path/to/file.jpg', // Example file path
        ];

        $indentRequest = WarehouseIndentRequest::create($indentRequestData);

    }
}
