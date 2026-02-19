<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use App\Models\LeaveType;
use Illuminate\Support\Facades\DB;

class LeaveTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        DB::table('leave_types')->delete();
        // Dummy data for LeaveType
        $leaveTypes = [
            ['name' => 'Annual Leave', 'status' => 1],
            ['name' => 'Sick Leave', 'status' => 1],
            ['name' => 'Casual Leave', 'status' => 1],
            ['name' => 'Maternity Leave', 'status' => 1],
            ['name' => 'Paternity Leave', 'status' => 1],
        ];

        foreach ($leaveTypes as $type) {
            $leave_type = new LeaveType();
            $leave_type->name = $type['name'];
            $leave_type->date = Carbon::now(); // Current timestamp for the date field
            $leave_type->status = $type['status'];
            $leave_type->save();
        }

    }
}
