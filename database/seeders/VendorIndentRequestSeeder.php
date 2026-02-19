<?php

namespace Database\Seeders;

use App\Models\VendorIndentRequest;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class VendorIndentRequestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('vendor_indent_requests')->delete();
        // Dummy data for vendor_indent_requests
        $vendorIndentRequestData = [
            'vendor_id' => 3, // Replace with a valid vendor ID
            'request_code' => 'VR123456',
            'request_date' => Carbon::now()->format('Y-m-d'),
            'expected_date' => Carbon::now()->addDays(7)->format('Y-m-d'),
            'status' => 1, // Assume status 1 is for 'Pending' or 'Approved'
            'total_request_quantity' => 10,
            'total_amount' => 10000.00,
            'remarks' => 'Dummy request data for seeding',
            'file' => 'https://example.com/file.jpg', // Example file URL
            'file_path' => 'path/to/file.jpg', // Example file path
        ];

        $vendorIndentRequest = VendorIndentRequest::create($vendorIndentRequestData);
    }
}
