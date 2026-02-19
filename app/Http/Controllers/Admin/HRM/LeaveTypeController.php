<?php

namespace App\Http\Controllers\Admin\HRM;

use App\DataTables\HRM\LeaveTypeDataTable;
use App\Http\Controllers\Controller;
use App\Http\Requests\HRM\LeaveTypeFormRequest;
use App\Models\LeaveType;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;

class LeaveTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(LeaveTypeDataTable $dataTable)
    {
        return $dataTable->render('pages.hrm.leave_type.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('pages.hrm.leave_type.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(LeaveTypeFormRequest $request)
    {
        // try {
        $date = Carbon::now();
        $leave_type = new LeaveType();
        $leave_type->name = $request->name;
        $leave_type->date = $date;
        $leave_type->status = $request->status;
        $leave_type->save();

        if ($request->submission_type == 1) {
            return redirect()->route('admin.leave_type.index')->with('success', 'Leave Type Store Successfully');
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
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data['leave_type'] = LeaveType::findOrFail($id);
        return view('pages.hrm.leave_type.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(LeaveTypeFormRequest $request, $id)
    {
        // try {
        $date = Carbon::now();
        $income_expense_type = LeaveType::findOrFail($id);
        $income_expense_type->name = $request->name;
        $income_expense_type->date = $date;
        $income_expense_type->status = $request->status;
        $income_expense_type->save();

        return redirect()->route('admin.leave_type.index')->with('success', 'Leave Type Updated Successfully');
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
            LeaveType::findOrFail($id)->delete();

            return redirect()->route('admin.leave_type.index')->with('success', 'Leave Type Deleted Successfully');
        } catch (\Exception $e) {
            Log::error($e);
            return response()->json([
                'status' => 400,
                'message' => 'Sorry, Something went Wrong',
            ]);
        }
    }
}
