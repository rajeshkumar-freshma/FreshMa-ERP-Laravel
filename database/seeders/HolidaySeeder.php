<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use App\Models\Holiday; // Ensure you have the correct namespace for the Holiday model
use Illuminate\Support\Facades\DB;

class HolidaySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        DB::table('holidays')->delete();

        DB::beginTransaction();

        // DB::unprepared('SET IDENTITY_INSERT holidays ON');

        // Dummy data for Holiday
        $holidays = [
            ['name' => 'New Year\'s Day', 'date' => '2024-01-01', 'holiday_type' => 0, 'status' => 1],
            ['name' => 'Independence Day', 'date' => '2024-07-04', 'holiday_type' => 0, 'status' => 1],
            ['name' => 'Thanksgiving', 'date' => '2024-11-28', 'holiday_type' => 0, 'status' => 1],
            ['name' => 'Christmas Day', 'date' => '2024-12-25', 'holiday_type' => 1, 'status' => 1],
            ['name' => 'Labor Day', 'date' => '2024-09-02', 'holiday_type' => 1, 'status' => 1],
        ];

        foreach ($holidays as $holiday) {
            $holidayRecord = new Holiday();
            $holidayRecord->name = $holiday['name'];
            $holidayRecord->date = $holiday['date']; // Ensure this is in the correct format (YYYY-MM-DD)
            $holidayRecord->holiday_type = $holiday['holiday_type'];
            $holidayRecord->status = $holiday['status'];
            $holidayRecord->save();
        }

        // DB::unprepared('SET IDENTITY_INSERT holidays OFF');

        DB::commit();

    }
}
