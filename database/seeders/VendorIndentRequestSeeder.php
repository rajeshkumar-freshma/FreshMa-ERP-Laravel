<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\VendorIndentRequest;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class VendorIndentRequestSeeder extends Seeder
{
    public function run()
    {
        DB::table('vendor_indent_requests')->truncate();

        // Fetch first vendor user
        $vendor = User::where('user_type', 1)->first(); // vendor user_type is 1

        if (!$vendor) {
            $this->call(VendorTableSeeder::class);
            $vendor = User::where('user_type', 1)->first();
        }

        if (!$vendor) {
            $this->command->warn('No vendor found. Please seed vendors first.');
            return;
        }

        VendorIndentRequest::create([
            'vendor_id' => $vendor->id,
            'request_code' => 'VR' . rand(100000, 999999),
            'request_date' => Carbon::now()->format('Y-m-d'),
            'expected_date' => Carbon::now()->addDays(7)->format('Y-m-d'),
            'status' => 1,
            'total_request_quantity' => 10,
            'total_amount' => 10000.00,
            'remarks' => 'Dummy request data for seeding',
            'file' => 'https://example.com/file.jpg',
            'file_path' => 'path/to/file.jpg',
            'created_by' => $vendor->id,
            'updated_by' => $vendor->id,
        ]);
    }
}
