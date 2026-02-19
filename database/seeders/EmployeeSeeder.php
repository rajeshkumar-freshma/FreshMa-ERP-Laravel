<?php

namespace Database\Seeders;

use App\Models\Admin;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\Staff;
use App\Models\UserInfo;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class EmployeeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {

       
        DB::table('admins')->where('user_type',4)->delete();

        DB::beginTransaction();

        // DB::unprepared('SET IDENTITY_INSERT admins ON');

        // Dummy data for Staff
        $staff = new Staff();
        $staff->first_name = 'John';
        $staff->last_name = 'Doe';
        $staff->email = 'john.doe@example.com';
        $staff->password = Hash::make('password123'); // Ensure this matches the validation rules for passwords
        $staff->phone_number = '1234567222';
        $staff->user_type = 4; // 4 => Staff in admins table
        $staff->status = 1; // Active
        $staff->api_token = Hash::make('john.doe@example.com');
        $staff->save();

        // Dummy data for UserInfo
        $userInfo = new UserInfo();
        $userInfo->admin_type = 1; // 1 => Admin
        $userInfo->admin_id = $staff->id;
        $userInfo->address = '123 Main St, Springfield';
        $userInfo->country_id = 1; // Replace with actual country ID
        $userInfo->state_id = 1; // Replace with actual state ID
        $userInfo->city_id = 1; // Replace with actual city ID
        $userInfo->joined_at = Carbon::now()->format('Y-m-d');
        // Dummy financial and identification data
        $userInfo->pan_number = 'ABCDE1234F';
        $userInfo->aadhar_number = '1234-5678-9012';
        $userInfo->esi_number = 'ESI123456';
        $userInfo->pf_number = 'PF123456';
        $userInfo->account_number = '123456789012';
        $userInfo->bank_name = 'Sample Bank';
        $userInfo->name_as_per_record = 'John Doe';
        $userInfo->branch_name = 'Main Branch';
        $userInfo->ifsc_code = 'SAMP0001234';
        $userInfo->save();

        // DB::unprepared('SET IDENTITY_INSERT admins OFF');

        DB::commit();

    }
}
