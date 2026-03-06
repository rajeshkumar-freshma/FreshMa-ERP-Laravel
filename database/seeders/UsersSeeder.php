<?php

namespace Database\Seeders;

use App\Models\Admin;
use App\Core\CommonComponent;
use App\Models\Country;
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
        DB::table('user_infos')->truncate();
        DB::table('admins')->truncate();
        DB::table('users')->truncate();
        
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
                'first_name' => 'RRK Retail',
                'last_name' => 'Private Limited',
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
                'first_name' => 'CodeTez',
                'last_name' => 'Technologies',
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
        $admin_type = $type == 'admin' ? 1 : 2;
        $countryId = Country::query()->value('id');

        $dummyInfo = [
            'admin_type' => $admin_type,
            'user_id' => null,
            'admin_id' => null,
            'company' => 'FreshMa',
            'website' => 'https://www.freshma.in/',
            'country_id' => $countryId,
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
