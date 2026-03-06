<?php

namespace Database\Seeders;

use App\Models\SalaryDetail;
use App\Models\Supplier;
use App\Models\UserInfo;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SupplierTableSeeder extends Seeder
{
    public function run()
    {
        // Delete ONLY supplier-related data
        DB::table('users')->where('user_type', 2)->delete();
        DB::table('user_infos')->where('admin_type', 2)->delete();
        DB::table('salary_details')->where('admin_type', 2)->delete();

        // Get master records
        $country = \App\Models\Country::first();
        $state = \App\Models\State::first();
        $city = \App\Models\City::first();
        $currency = \App\Models\Currency::first();

        if (!$country || !$state || !$city || !$currency) {
            $this->command->error('Location masters missing for Supplier.');
            return;
        }

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
                'user_type' => 2,
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
                'user_type' => 2,
                'api_token' => '',
                'fcm_token' => 'FCM_TOKEN_HERE',
                'address1' => '456 Market St',
                'address2' => 'Suite 10',
                'address3' => null,
                'voipToken' => 'VOIP_TOKEN_HERE',
                'lat' => '40.7128',
                'lon' => '74.0060',
                'os' => 'Android',
                'status' => 1,
            ],
        ];

        foreach ($suppliers as $supplierData) {

            $supplier = Supplier::create($supplierData);

            UserInfo::create([
                'admin_type' => 2,
                'user_id' => $supplier->id,
                'company' => 'Company Name',
                'website' => 'https://example.com',
                'address' => '1234 Example St',
                'country_id' => $country->id,
                'state_id' => $state->id,
                'city_id' => $city->id,
                'currency_id' => $currency->id,
                'gst_number' => 'GST123',
                'joined_at' => now(),
                'pan_number' => 'PAN123',
                'aadhar_number' => 'AADHAR123',
                'esi_number' => 'ESI123',
                'pf_number' => 'PF123',
                'account_number' => 'ACC123',
                'bank_name' => 'Example Bank',
                'name_as_per_record' => 'Supplier Name',
                'branch_name' => 'Main Branch',
                'ifsc_code' => 'IFSC123',
            ]);

            SalaryDetail::create([
                'admin_type' => 2,
                'user_id' => $supplier->id,
                'salary_type' => 3,
                'amount_type' => 1,
                'amount' => rand(30000, 50000),
                'percentage' => rand(1, 100),
                'remarks' => 'Dummy salary detail',
            ]);
        }

        $this->command->info('Suppliers seeded successfully.');
    }
}