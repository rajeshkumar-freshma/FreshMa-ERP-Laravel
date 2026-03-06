<?php

namespace Tests\Unit\Seeders;

use App\Models\Admin;
use App\Models\City;
use App\Models\Country;
use App\Models\Currency;
use App\Models\State;
use App\Models\UserInfo;
use Database\Seeders\EmployeeSeeder;
use Database\Seeders\PartnerTableSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class PartnerAndEmployeeSeederTest extends TestCase
{
    use RefreshDatabase;

    public function test_partner_seeder_keeps_non_partner_admin_and_rebuilds_partner_rows(): void
    {
        $this->seedLocationAndCurrency();
        $this->seedPartnerRoles();

        $mainAdmin = Admin::query()->create([
            'first_name' => 'Main',
            'last_name' => 'Admin',
            'email' => 'main-admin@example.com',
            'password' => bcrypt('secret'),
            'phone_number' => '9000000001',
            'user_type' => 1,
            'role_id' => 1,
            'api_token' => 'token-main-admin',
            'status' => 1,
        ]);

        UserInfo::query()->create([
            'admin_type' => 1,
            'admin_id' => $mainAdmin->id,
            'country_id' => Country::query()->value('id'),
            'state_id' => State::query()->value('id'),
            'city_id' => City::query()->value('id'),
            'currency_id' => Currency::query()->value('id'),
        ]);

        $oldPartner = Admin::query()->create([
            'first_name' => 'Old',
            'last_name' => 'Partner',
            'email' => 'old-partner@example.com',
            'password' => bcrypt('secret'),
            'phone_number' => '9000000002',
            'user_type' => 2,
            'role_id' => 1,
            'api_token' => 'token-old-partner',
            'status' => 1,
        ]);

        UserInfo::query()->create([
            'admin_type' => 1,
            'admin_id' => $oldPartner->id,
            'country_id' => Country::query()->value('id'),
            'state_id' => State::query()->value('id'),
            'city_id' => City::query()->value('id'),
            'currency_id' => Currency::query()->value('id'),
        ]);

        $this->seed(PartnerTableSeeder::class);

        $this->assertDatabaseHas('admins', [
            'id' => $mainAdmin->id,
            'user_type' => '1',
        ]);
        $this->assertDatabaseHas('user_infos', [
            'admin_id' => $mainAdmin->id,
            'admin_type' => 1,
        ]);

        $partnerAdmins = Admin::query()->whereIn('user_type', [2, 3])->get();
        $this->assertCount(2, $partnerAdmins);
        $this->assertDatabaseMissing('user_infos', [
            'admin_id' => $oldPartner->id,
            'admin_type' => 1,
        ]);

        $this->assertSame(
            2,
            UserInfo::query()
                ->where('admin_type', 1)
                ->whereIn('admin_id', $partnerAdmins->pluck('id')->all())
                ->count()
        );
    }

    public function test_employee_seeder_keeps_non_staff_admin_rows(): void
    {
        $this->seedLocationAndCurrency();

        $mainAdmin = Admin::query()->create([
            'first_name' => 'Main',
            'last_name' => 'Admin',
            'email' => 'main-admin2@example.com',
            'password' => bcrypt('secret'),
            'phone_number' => '9000000011',
            'user_type' => 1,
            'role_id' => 1,
            'api_token' => 'token-main-admin-2',
            'status' => 1,
        ]);

        UserInfo::query()->create([
            'admin_type' => 1,
            'admin_id' => $mainAdmin->id,
            'country_id' => Country::query()->value('id'),
            'state_id' => State::query()->value('id'),
            'city_id' => City::query()->value('id'),
            'currency_id' => Currency::query()->value('id'),
        ]);

        $oldStaff = Admin::query()->create([
            'first_name' => 'Old',
            'last_name' => 'Staff',
            'email' => 'old-staff@example.com',
            'password' => bcrypt('secret'),
            'phone_number' => '9000000012',
            'user_type' => 4,
            'role_id' => 1,
            'api_token' => 'token-old-staff',
            'status' => 1,
        ]);

        UserInfo::query()->create([
            'admin_type' => 1,
            'admin_id' => $oldStaff->id,
            'country_id' => Country::query()->value('id'),
            'state_id' => State::query()->value('id'),
            'city_id' => City::query()->value('id'),
            'currency_id' => Currency::query()->value('id'),
        ]);

        $this->seed(EmployeeSeeder::class);

        $this->assertDatabaseHas('admins', [
            'id' => $mainAdmin->id,
            'user_type' => '1',
        ]);
        $this->assertDatabaseMissing('admins', [
            'id' => $oldStaff->id,
        ]);
        $this->assertSame(1, Admin::query()->where('user_type', 4)->count());

        $newStaff = Admin::query()
            ->where('user_type', 4)
            ->where('email', 'john.doe@example.com')
            ->first();
        $this->assertNotNull($newStaff);
        $this->assertDatabaseHas('user_infos', [
            'admin_id' => $newStaff->id,
            'admin_type' => 1,
        ]);
    }

    private function seedLocationAndCurrency(): void
    {
        $country = Country::query()->create([
            'name' => 'India',
            'country_code' => 'IN',
            'currency_code' => 'INR',
            'phone_code' => '91',
            'currency' => 'Indian Rupee',
        ]);

        $state = State::query()->create([
            'name' => 'Tamil Nadu',
            'country_id' => $country->id,
        ]);

        City::query()->create([
            'name' => 'Chennai',
            'state_id' => $state->id,
        ]);

        Currency::query()->create([
            'name' => 'Indian Rupee',
            'code' => 'INR',
            'symbol' => 'Rs',
            'country_code' => 'IN',
            'exchange_rate' => '1',
        ]);
    }

    private function seedPartnerRoles(): void
    {
        Role::query()->create([
            'name' => 'Manager',
            'guard_name' => 'admin',
        ]);

        Role::query()->create([
            'name' => 'Store Manager',
            'guard_name' => 'admin',
        ]);
    }
}
