<?php

namespace Database\Seeders;

use App\Models\Partner;
use App\Models\UserInfo;
use App\Models\Country;
use App\Models\State;
use App\Models\City;
use App\Models\Currency;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class PartnerTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $existingPartnerIds = DB::table('admins')
            ->whereIn('user_type', [2, 3])
            ->pluck('id')
            ->all();

        if (!empty($existingPartnerIds)) {
            DB::table('user_infos')
                ->where('admin_type', 1)
                ->whereIn('admin_id', $existingPartnerIds)
                ->delete();
        }

        DB::table('admins')->whereIn('user_type', [2, 3])->delete();

        $managerRoleId = DB::table('roles')
            ->where('name', 'Manager')
            ->where('guard_name', 'admin')
            ->value('id');
        $partnerRoleId = DB::table('roles')
            ->where('name', 'Store Manager')
            ->where('guard_name', 'admin')
            ->value('id');

        $countryId = Country::query()->value('id');
        $stateId = State::query()->where('country_id', $countryId)->value('id') ?? State::query()->value('id');
        $cityId = City::query()->where('state_id', $stateId)->value('id') ?? City::query()->value('id');
        $currencyId = Currency::query()->value('id');

        // Define partners to seed
        $partners = [
            [
                'first_name' => 'John',
                'last_name' => 'Doe',
                'email' => 'john.doe@example.com',
                'password' => Hash::make('12345678'), // Use hashed passwords
                'phone_number' => '1234567822',
                'user_type' => 2, // Manager
                'user_code' => 'UC00001',
                'status' => 1,
                'role_id' => $managerRoleId,
                'created_at' => now(),
                'updated_at' => now(),
                'api_token' => bin2hex(random_bytes(30)), // Generate unique API token
            ],
            [
                'first_name' => 'Jane',
                'last_name' => 'Smith',
                'email' => 'jane.smith@example.com',
                'password' => Hash::make('12345678'), // Use hashed passwords
                'phone_number' => '0987654344',
                'user_type' => 3, // Partner
                'user_code' => 'UC00002',
                'status' => 1,
                'role_id' => $partnerRoleId,
                'created_at' => now(),
                'updated_at' => now(),
                'api_token' => bin2hex(random_bytes(30)), // Generate unique API token
            ],
        ];

        // Insert new records
        foreach ($partners as $partnerData) {
            // Create a new partner
            $partner = Partner::create($partnerData);

            // Insert related UserInfo
            UserInfo::create([
                'admin_type' => 1,
                'admin_id' => $partner->id,
                'company' => 'Company',
                'website' => 'https://example.com',
                'address' => '1234 Example St',
                'country_id' => $countryId,
                'state_id' => $stateId,
                'city_id' => $cityId,
                'currency_id' => $currencyId,
                'gst_number' => 'GST',
                'joined_at' => now(),
                'image' => null, // Assuming no image is provided
                'image_path' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
