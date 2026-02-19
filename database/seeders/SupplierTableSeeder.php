<?php

namespace Database\Seeders;

use App\Models\SalaryDetail;
use App\Models\Supplier;
use App\Models\UserInfo;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SupplierTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {

        // Clear existing suppliers and related data
        DB::table('users')->where('user_type', 2)->delete();
        DB::table('user_infos')->delete();
        DB::table('salary_details')->delete();
        // DB::unprepared('SET IDENTITY_INSERT admins ON');

        $suppliers = [
            [
                'first_name' => 'John',
                'last_name' => 'Doe',
                'email' => 'john.doe@example.com',
                'password' => bcrypt('password123'),
                'phone_number' => '1234567811',
                'user_code' => 'CODE123',
                'email_verified_at' => now(),
                'phone_verified_at' => now(),
                'user_type' => 2, //1=> Vendor in admins table
                'api_token' => '',
                'fcm_token' => 'FCM_TOKEN_HERE',
                'address1' => '123 Main St',
                'address2' => 'Apt 4B',
                'address3' => null,
                'voipToken' => 'VOIP_TOKEN_HERE',
                'lat' => '40.7128',
                'lon' => '74.0060',
                'os' => 'iOS',
                'status' => 1,
            ],
            [
                'first_name' => 'Jane',
                'last_name' => 'Smith',
                'email' => 'jane.smith@example.com',
                'password' => bcrypt('password123'),
                'phone_number' => '0987654321',
                'user_code' => 'CODE456',
                'email_verified_at' => now(),
                'phone_verified_at' => now(),
                'user_type' => 2, //1=> Vendor in admins table
                'api_token' => '',
                'fcm_token' => 'FCM_TOKEN_HERE',
                'address1' => '123 Main St',
                'address2' => 'Apt 4B',
                'address3' => null,
                'voipToken' => 'VOIP_TOKEN_HERE',
                'lat' => '40.7128',
                'lon' => '74.0060',
                'os' => 'iOS',
                'status' => 1,
            ],
        ];

        foreach ($suppliers as $supplierData) {
            // Create the supplier
            $supplier = Supplier::create($supplierData);

            // Create dummy UserInfo for each supplier
            UserInfo::create([
                'admin_type' => 2, // Supplier
                'user_id' => $supplier->id,
                'company' => 'Company ',
                'website' => 'https://example.com',
                'address' => '1234 Example St',
                'country_id' => rand(1, 10), // Example IDs; adjust as needed
                'state_id' => rand(1, 10),
                'city_id' => rand(1, 10),
                'currency_id' => rand(1, 10),
                'gst_number' => 'GST',
                'joined_at' => now(),
                'pan_number' => 'PAN',
                'aadhar_number' => 'AADHAR',
                'esi_number' => 'ESI',
                'pf_number' => 'PF',
                'account_number' => 'ACC',
                'bank_name' => 'Bank ',
                'name_as_per_record' => 'Name ',
                'branch_name' => 'Branch ',
                'ifsc_code' => 'IFSC',
            ]);

            // Create dummy SalaryDetail for each supplier
            SalaryDetail::create([
                'admin_type' => 2, // Supplier
                'user_id' => $supplier->id,
                'salary_type' => 3, //Monthly
                'amount_type' => 1, //1 fixed
                'amount' => rand(30000, 50000),
                'percentage' => rand(1, 100),
                'remarks' => 'Dummy salary detail for ' . $supplier->first_name,
            ]);
        }

        // DB::unprepared('SET IDENTITY_INSERT admins OFF');

    }
}
