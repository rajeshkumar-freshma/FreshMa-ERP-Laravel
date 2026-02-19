<?php

namespace App\Http\Controllers\Admin\HRM;

use App\DataTables\HRM\LeaveDataTable;
use App\Http\Controllers\Controller;
use App\Http\Requests\HRM\LeaveFormRequest;
use App\Models\Leave;
use App\Models\LeaveType;
use App\Models\Staff;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class LeaveController extends Controller
{
    public function index(LeaveDataTable $dataTable)
    {
        return $dataTable->render('pages.hrm.leave.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data['employee'] = Staff::where('status', 1)->get();
        $data['leave_type'] = LeaveType::where('status', 1)->get();
        return view('pages.hrm.leave.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(LeaveFormRequest $request)
    {
        // try {
        $leave = new Leave();
        $leave->employee_id = $request->employee_id;
        $leave->leave_type = $request->leave_type;
        $leave->approved_status = $request->approved_status;
        $leave->start_date = $request->start_date;
        $leave->end_date = $request->end_date;
        $leave->reasons = $request->reason;
        $leave->remark = $request->remark;
        $leave->is_half_day = $request->is_half_day;
        $leave->save();

        if ($request->submission_type == 1) {
            return redirect()->route('admin.leave.index')->with('success', 'Leave Type Store Successfully');
        } elseif ($request->submission_type == 2) {
            return back()->with('success', 'Leave Type Store Successfully');
        }
        // } catch (\Exception $e) {
        //     Log::error($e);
        //     return back()->withInput()->with('error', 'Income/Expense Type Stored Fail');
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
            $data['leaves'] = Leave::findOrFail($id);
            $data['employee'] = Staff::all();
            $data['leave_types'] = LeaveType::all();
            return view('pages.hrm.leave.view', $data);
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
        $data['employee'] = Staff::where('status', 1)->get();
        $data['leave_type'] = LeaveType::where('status', 1)->get();
        $data['leave'] = Leave::findOrfail($id);
        return view('pages.hrm.leave.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(LeaveFormRequest $request, $id)
    {
        // try {
        $leave = Leave::find($id);
        $leave->employee_id = $request->employee_id;
        $leave->leave_type = $request->leave_type;
        $leave->approved_status = $request->approved_status;
        $leave->start_date = $request->start_date;
        $leave->end_date = $request->end_date;
        $leave->reasons = $request->reason;
        $leave->remark = $request->remark;
        $leave->is_half_day = $request->is_half_day;
        $leave->update();

        return redirect()->route('admin.leave.index')->with('success', 'Leave Type Updated Successfully');
        // } catch (\Exception $e) {
        //     Log::error($e);
        //     return back()->withInput()->with('error', 'Income/Expense Type Updated Fail');
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
            Leave::findOrFail($id)->delete();

            return redirect()->route('admin.leave.index')->with('success', 'Leave Types Deleted Successfully');
        } catch (\Exception $e) {
            Log::error($e);
            return response()->json([
                'status' => 400,
                'message' => 'Sorry, Something went Wrong',
            ]);
        }
    }
    public function storedLeaveType(Request $request)
    {
        try {
            // Validate the request data if needed
            $request->validate([
                'leave_type_name' => 'required|string',
                'leave_type_status' => 'required|integer',
            ]);

            // Create a new LeaveType instance
            $leaveType = new LeaveType();
            $leaveType->name = $request->input('leave_type_name');
            $leaveType->status = $request->input('leave_type_status');
            $leaveType->save();

            // Optionally, you can return the saved data in the response
            $data = $leaveType;

            return response()->json([
                'status' => 200,
                'data' => $data,
                'message' => 'Leave Type Added Successfully',
            ]);
        } catch (\Exception $e) {
            Log::error($e);

            return response()->json([
                'status' => 400,
                'message' => 'Sorry, Something went Wrong',
            ]);
        }
    }
}
