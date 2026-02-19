<?php

namespace App\Http\Controllers\admin\HRM;

use App\DataTables\HRM\StaffAttendanceDataTable;
use App\Http\Controllers\Controller;
use App\Http\Requests\HRM\StaffAttendanceFormRequest;
use App\Models\Staff;
use App\Models\StaffAttendance;
use App\Models\StaffAttendanceDetails;
use App\Models\StaffStoreMapping;
use App\Models\Store;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;
use Throwable;

class StaffAttendanceController extends Controller
{
    public function index(StaffAttendanceDataTable $dataTable)
    {
        return $dataTable->render('pages.hrm.staff_attendance.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data['store'] = Store::where('status', 1)->get();
        // $data['staff_store_mapping'] = StaffStoreMapping::where('status', 1)->get();
        return view('pages.hrm.staff_attendance.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StaffAttendanceFormRequest $request)
    {
        // try {
        $totalInMinutes = 0;
        $totalInHours = 0;
        $presentCount = 0;
        $absentCount = 0;

        // Loop through each selected employee ID
        foreach ($request->employee_id as $index => $employeeId) {
            // Retrieve or create the staff attendance record for each employee
            $staffAttendance = StaffAttendance::where([['store_id', $request->store_id], ['attendance_date', $request->attendance_date]])->first();
            if ($staffAttendance == null) {
                $staffAttendance = new StaffAttendance();
                $staffAttendance->store_id = $request->store_id;
                $staffAttendance->attendance_date = $request->attendance_date;
                $staffAttendance->status = $request->status;
                $staffAttendance->save();
            }

            // Create a new instance of StaffAttendanceDetails for each employee
            $attendanceDetail = $staffAttendance->details->where('staff_id', $employeeId)->first() ?? new StaffAttendanceDetails();

            // Update the staff attendance details
            $attendanceDetail->staff_attendance_id = $staffAttendance->id;
            $attendanceDetail->staff_id = $employeeId;
            $attendanceDetail->store_id = $request->store_id;
            $attendanceDetail->in_time = Carbon::parse($request->in_datetime[$employeeId])->format('Y-m-d H:i:s');
            $attendanceDetail->out_time = Carbon::parse($request->out_datetime[$employeeId])->format('Y-m-d H:i:s');
            $attendanceDetail->is_present = $request->attendance_type[$employeeId];
            $attendanceDetail->save();

            // $attendanceDetail = new StaffAttendanceDetails();
            // $attendanceDetail->staff_attendance_id = $staffAttendance->id;
            // $attendanceDetail->staff_id = $employeeId;
            // $attendanceDetail->store_id = $request->store_id;
            // $attendanceDetail->in_time = Carbon::parse($request->in_datetime[$employeeId])->format('Y-m-d H:i:s');
            // $attendanceDetail->out_time = Carbon::parse($request->out_datetime[$employeeId])->format('Y-m-d H:i:s');
            // $attendanceDetail->is_present = $request->attendance_type[$employeeId];
            // $attendanceDetail->save();

            // Calculate time differences
            $inTime = Carbon::parse($attendanceDetail->in_time);
            $outTime = Carbon::parse($attendanceDetail->out_time);
            $diffInMinutes = $outTime->diffInMinutes($inTime);
            $diffInHours = $outTime->diffInHours($inTime);

            // Update counters
            $totalInMinutes += $diffInMinutes;
            $totalInHours += $diffInHours;

            // Update attendance type counts
            if ($request->attendance_type[$employeeId] == 1) {
                $presentCount += 1;
            } elseif ($request->attendance_type[$employeeId] == 2) {
                $presentCount += 0.5;
            } elseif ($request->attendance_type[$employeeId] == 3) {
                // Handle other cases if needed
            } else {
                $absentCount += 0;
            }
        }

        // Calculate total time
        $takenTotalMinutes = $totalInMinutes % 60;
        $totalTime = $totalInHours . ":" . $takenTotalMinutes;

        // Update the last staff attendance record with totals
        $staffAttendance->total_working_hours = $totalTime;
        $staffAttendance->total_present = $presentCount;
        $staffAttendance->total_absent = $absentCount;
        $staffAttendance->save();

        // Redirect based on submission type
        if ($request->submission_type == 1) {
            return redirect()->route('admin.staff_attendance.index')->with('success', 'Staff Attendance Stored Successfully');
        } elseif ($request->submission_type == 2) {
            return back()->with('success', 'Staff Attendance Stored Successfully');
        }
        // } catch (\Exception $e) {
        //     Log::error($e);
        //     return back()->withInput()->with('error', 'Staff Attendance Store Failed');
        // }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {
            $data['staff_attendance'] = StaffAttendance::findOrFail($id);
            $data['stores'] = Store::all();
            return view('pages.hrm.staff_attendance.view', $data);
        } catch (\Exception $e) {
            Log::error($e);
            return response()->json([
                'status' => 400,
                'message' => 'Sorry, Something went Wrong',
            ]);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data['staff_attendance'] = StaffAttendance::findOrFail($id);
        $data['staff_attendance_details'] = StaffAttendanceDetails::where('staff_attendance_id', $id)->get();
        $data['store'] = Store::where('status', 1)->get();
        // $data['employees'] = []; // You may need to adjust this based on your actual relationship
        // if (!empty($data['staff_attendance_details'])) {
        //     $employeeIds = $data['staff_attendance_details']->pluck('employee_id')->toArray();
        //     $data['employees'] = Staff::whereIn('id', $employeeIds)->get();
        // }
        return view('pages.hrm.staff_attendance.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(StaffAttendanceFormRequest $request, $id)
    {
        // try {
        // Find the staff attendance record
        $staffAttendance = StaffAttendance::findOrFail($id);
        // return $request->all();
        // Update the staff attendance record
        $totalInMinutes = 0;
        $totalInHours = 0;
        $presentCount = 0;
        $absentCount = 0;

        if ($staffAttendance != null) {
            foreach ($request->employee_id as $index => $employeeId) {
                // Find the existing or create a new staff attendance detail
                $attendanceDetail = $staffAttendance->details->where('staff_id', $employeeId)->first() ?? new StaffAttendanceDetails();

                // Update the staff attendance details
                $attendanceDetail->staff_attendance_id = $staffAttendance->id;
                $attendanceDetail->staff_id = $employeeId;
                $attendanceDetail->store_id = $request->store_id;
                $attendanceDetail->in_time = Carbon::parse($request->in_datetime[$employeeId])->format('Y-m-d H:i:s');
                $attendanceDetail->out_time = Carbon::parse($request->out_datetime[$employeeId])->format('Y-m-d H:i:s');
                $attendanceDetail->is_present = $request->attendance_type[$employeeId];
                $attendanceDetail->save();

                // $attendanceDetail->update([
                //     'staff_id' => $employeeId,
                //     'store_id' => $request->store_id,
                //     'in_time' => Carbon::parse($request->in_datetime[$employeeId])->format('Y-m-d H:i:s'),
                //     'out_time' => Carbon::parse($request->out_datetime[$employeeId])->format('Y-m-d H:i:s'),
                //     'is_present' => $request->attendance_type[$employeeId],
                // ]);

                // Calculate time differences
                $inTime = Carbon::parse($attendanceDetail->in_time);
                $outTime = Carbon::parse($attendanceDetail->out_time);
                $diffInMinutes = $outTime->diffInMinutes($inTime);
                $diffInHours = $outTime->diffInHours($inTime);

                // Update counters
                $totalInMinutes += $diffInMinutes;
                $totalInHours += $diffInHours;

                // Update attendance type counts
                if ($request->attendance_type[$employeeId] == 1) {
                    $presentCount += 1;
                } elseif ($request->attendance_type[$employeeId] == 2) {
                    $presentCount += 0.5;
                } elseif ($request->attendance_type[$employeeId] == 3) {
                    // Handle other cases if needed
                } else {
                    $absentCount += 0;
                }
            }

            // Calculate total time
            $takenTotalMinutes = $totalInMinutes % 60;
            $totalTime = $totalInHours . ":" . $takenTotalMinutes;

            // Update the staff attendance record with totals
            $staffAttendance->update([
                'store_id' => $request->store_id,
                'attendance_date' => $request->attendance_date,
                'status' => $request->status,
                'total_working_hours' => $totalTime,
                'total_present' => $presentCount,
                'total_absent' => $absentCount,
                'status' => $request->status,
            ]);

            return redirect()->route('admin.staff_attendance.index')->with('success', 'Staff Attendance Updated Successfully');
        }
        // } catch (\Exception $e) {
        //     Log::error($e);
        //     return back()->withInput()->with('error', 'Staff Attendance Update Failed');
        // }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            StaffAttendance::findOrFail($id)->delete();

            return redirect()->route('admin.staff_attendance.index')->with('success', 'Staff Attendance Deleted Successfully');
        } catch (\Exception $e) {
            Log::error($e);
            return response()->json([
                'status' => 400,
                'message' => 'Sorry, Something went Wrong',
            ]);
        }
    }
    public function getEmployees(Request $request)
    {
        try {
            $storeId = $request->input('storeId');
            $staffAttendanceId = $request->input('staffAttendanceId');
            $attendance_date = $request->input('attendance_date');

            // Fetch employees
            $employees = StaffStoreMapping::where('status', 1)
                ->where('store_id', $storeId)
                ->get();

            $employeeIds = $employees->pluck('staff_id');

            // Fetch staffs based on employee IDs
            $staffs = Staff::whereIn('id', $employeeIds)
                ->where('status', 1)
                ->where('user_type', 4)
                ->get();

            $check_staffAttendance = StaffAttendance::where('store_id', $storeId)
                ->whereDate('attendance_date', Carbon::parse($attendance_date)->toDateString())
                ->latest()->first();
            if ($check_staffAttendance) {
                $attendanceDetails = StaffAttendanceDetails::where('staff_attendance_id', $check_staffAttendance->id)
                    ->get();
            } else {
                $attendanceDetails = [];
            }
            // Fetch attendance details based on staff attendance ID

            // Prepare attendance types data (assuming you have them defined somewhere)
            $attendanceTypes = config('app.attendance_type'); // Adjust this according to your actual configuration

            return response()->json([
                'success' => true,
                'status' => 200,
                'employees' => $staffs,
                'attendanceDetails' => $attendanceDetails,
                'attendanceTypes' => $attendanceTypes, // Include attendance types in the response
            ]);
        } catch (Throwable $e) {
            return response()->json([
                'success' => false,
                'status' => 400,
                'error' => $e->getMessage(),
            ]);
        }
    }
}
