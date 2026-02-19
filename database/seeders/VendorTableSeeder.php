<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class VendorTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {

        DB::table('users')->where('user_type', 1)->delete(); //1=> Vendor in admins table

        // DB::unprepared('SET IDENTITY_INSERT users ON');

        DB::table('users')->insert([
            [
                'first_name' => 'Nimi',
                'last_name' => 'cohe',
                'email' => 'john.doe@example.com',
                'password' => bcrypt('password123'),
                'phone_number' => '1234567890',
                'user_code' => 'CODE123',
                'email_verified_at' => now(),
                'phone_verified_at' => now(),
                'user_type' => 1, //1=> Vendor in admins table
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
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // Add more entries if needed
        ]);

        // DB::unprepared('SET IDENTITY_INSERT users OFF');

    }
}
