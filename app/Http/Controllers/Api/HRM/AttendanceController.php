<?php

namespace App\Http\Controllers\Api\HRM;

use App\Http\Controllers\Controller;
use App\Models\StaffAttendance;
use App\Models\StaffAttendanceDetails;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AttendanceController extends Controller
{
    public function storeattendancelist(Request $request)
    {
        $store_id = $request->store_id;
        $date = $request->date;
        if ($date == null) {
            $date = Carbon::today();
        }

        $staff_attendance = StaffAttendance::where(function ($query) use ($store_id, $date) {
            $query->where('attendance_date', $date);
            if ($store_id != null) {
                $query->where('store_id', $store_id);
            }
        })
            ->paginate(20);

        return response()->json([
            'status' => 200,
            'data' => $staff_attendance,
            'message' => 'Attendance Details Fetched Successfully.',
        ]);
    }

    public  function attendancestore(Request $request)
    {
        DB::beginTransaction();
        // try {

        $staff_attendance = StaffAttendance::where([['store_id', $request->store_id], ['attendance_date', $request->attendance_date]])->first();
        if ($staff_attendance == null) {
            $staff_attendance = new StaffAttendance();
            $staff_attendance->store_id = $request->store_id;
            $staff_attendance->attendance_date = $request->attendance_date;
            $staff_attendance->save();
        }

        $staff_attendances = json_decode($request->staff_attendance);
        $TotalInMinutes = 0;
        $TotalInHours = 0;
        $present_count = 0;
        $absent_count = 0;
        if (count($staff_attendances) > 0) {
            foreach ($staff_attendances as $key => $attendance) {
                $attendance_detail = StaffAttendanceDetails::where([['staff_attendance_id', $staff_attendance->id], ['staff_id', $attendance->staff_id], ['store_id', $request->store_id]])->first();
                if ($attendance_detail == null) {
                    $attendance_detail = new StaffAttendanceDetails();
                }
                $attendance_detail->staff_attendance_id = $staff_attendance->id;
                $attendance_detail->staff_id = $attendance->staff_id;
                $attendance_detail->store_id = $request->store_id;
                $attendance_detail->in_time = $attendance->in_time;
                $attendance_detail->out_time = @$attendance->out_time;
                $attendance_detail->is_present = @$attendance->is_present;
                $attendance_detail->save();

                $in_time = Carbon::parse($attendance_detail->in_time);
                $out_time = Carbon::parse($attendance_detail->out_time);
                $diff_in_minutes = $out_time->diffInMinutes($in_time);
                $diff_in_hours = $out_time->diffInHours($in_time);
                $TotalInMinutes += $diff_in_minutes;
                $TotalInHours += $diff_in_hours;

                if ($attendance->is_present == 1) {
                    $present_count += 1;
                } else if ($attendance->is_present == 2) {
                    $present_count += 0.5;
                }  else if ($attendance->is_present == 3) {
                    $present_count += 0;
                } else {
                    $absent_count += 0;
                }
            }
            $takenTotalmins = $TotalInMinutes % 60;
            $totalTime = $TotalInHours . ":" . $takenTotalmins;
        }

        $staff_attendance->total_working_hours = $totalTime;
        $staff_attendance->total_present = $present_count;
        $staff_attendance->total_absent = $absent_count;
        $staff_attendance->save();

        DB::commit();

        return response()->json([
            'status' => 200,
            'message' => 'Attendance Stored Successfully.',
        ]);
        // } catch (\Exception $e) {
        //     Log::error($e);
        //     DB::rollback();
        //     return response()->json([
        //         'status' => 400,
        //         'message' => 'Data not found.',
        //     ]);
        // }
    }

    public  function attendanceupdate(Request $request)
    {
        DB::beginTransaction();
        // try {
        $attendance_id = $request->attendance_id;
        $staff_attendance = StaffAttendance::findorfail($attendance_id);
        $staff_attendance->store_id = $request->store_id;
        $staff_attendance->attendance_date = $request->attendance_date;
        $staff_attendance->save();

        $staff_attendances = json_decode($request->staff_attendance);
        $TotalInMinutes = 0;
        $TotalInHours = 0;
        $present_count = 0;
        $absent_count = 0;
        if (count($staff_attendances) > 0) {
            foreach ($staff_attendances as $key => $attendance) {
                if (isset($attendance->id)) {
                    $attendance_detail = StaffAttendanceDetails::findOrFail($attendance->id);
                } else {
                    $attendance_detail = new StaffAttendanceDetails();
                }
                $attendance_detail->staff_attendance_id = $staff_attendance->id;
                $attendance_detail->staff_id = $attendance->staff_id;
                $attendance_detail->store_id = $request->store_id;
                $attendance_detail->in_time = @$attendance->in_time;
                $attendance_detail->out_time = @$attendance->out_time;
                $attendance_detail->is_present = @$attendance->is_present;
                $attendance_detail->save();

                $in_time = Carbon::parse($attendance_detail->in_time);
                $out_time = Carbon::parse($attendance_detail->out_time);
                $diff_in_minutes = $out_time->diffInMinutes($in_time);
                $diff_in_hours = $out_time->diffInHours($in_time);
                $TotalInMinutes += $diff_in_minutes;
                $TotalInHours += $diff_in_hours;

                if ($attendance->is_present == 1) {
                    $present_count += 1;
                } else if ($attendance->is_present == 2) {
                    $present_count += 0.5;
                }  else if ($attendance->is_present == 3) {
                    $present_count += 0;
                } else {
                    $absent_count += 0;
                }
            }
            $takenTotalmins = $TotalInMinutes % 60;
            $totalTime = $TotalInHours . ":" . $takenTotalmins;
        }

        $staff_attendance->total_working_hours = $totalTime;
        $staff_attendance->total_present = $present_count;
        $staff_attendance->total_absent = $absent_count;
        $staff_attendance->save();

        DB::commit();

        return response()->json([
            'status' => 200,
            'message' => 'Attendance Stored Successfully.',
        ]);
        // } catch (\Exception $e) {
        //     Log::error($e);
        //     DB::rollback();
        //     return response()->json([
        //         'status' => 400,
        //         'message' => 'Data not found.',
        //     ]);
        // }
    }
}
