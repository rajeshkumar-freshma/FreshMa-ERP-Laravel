<?php

namespace Database\Seeders;

use App\Models\Admin;
use App\Core\CommonComponent;
use App\Models\User;
use App\Models\UserInfo;
use Faker\Generator;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(Generator $faker)
    {
        DB::table('users')->delete();
        
        $demoUser = User::create([
            'first_name' => 'Walk',
            'last_name' => 'In',
            'email' => 'walkin@user.com',
            'user_code' => CommonComponent::invoice_no('user_code'),
            'password' => Hash::make('12345678'),
            'phone_number' => '1234567890',
            'user_type' => 1, // Vendor
            'email_verified_at' => now(),
            'api_token' => Hash::make('demo@user'),
        ]);

        $this->addDummyInfo($demoUser, 'user');

        if (ENV('APP_ENV') == 'production') {
            $demoUser2 = Admin::create([
                'first_name' => 'Rajesh',
                'last_name' => 'Kumar',
                'email' => 'support@freshma.in',
                'password' => Hash::make('~%eV342_Eu6*'),
                'phone_number' => '9841262993',
                'user_type' => 1, // admin
                'email_verified_at' => now(),
                'phone_verified_at' => now(),
                'api_token' => Hash::make('support@freshma'),
            ]);
        } else {
            $demoUser2 = Admin::create([
                'first_name' => 'Vijay',
                'last_name' => 'Kumar',
                'email' => 'support@codetez.com',
                'password' => Hash::make('12345678'),
                'phone_number' => '1234567890',
                'user_type' => 1, // admin
                'email_verified_at' => now(),
                'phone_verified_at' => now(),
                'api_token' => Hash::make('support@freshma'),
            ]);
        }

        $this->addDummyInfo($demoUser2, 'admin');
    }

    private function addDummyInfo($user, $type)
    {
        if ($type == 'admin') {
            $user_id = null;
            $admin_id = 1;
            $admin_type = 1;
        } else {
            $user_id = 1;
            $admin_id = null;
            $admin_type = 2;
        }

        $dummyInfo = [
            'admin_type' => $admin_type,
            'user_id' => $user_id,
            'admin_id' => $admin_id,
            'company' => 'FreshMa',
            'website' => 'https://www.freshma.in/',
            'country_id' => 1,
        ];

        $info = new UserInfo();
        foreach ($dummyInfo as $key => $value) {
            $info->$key = $value;
        }

        if ($type == 'admin') {
            $info->admin()->associate($user);
        } else {
            $info->user()->associate($user);
        }
        $info->save();
    }
}
