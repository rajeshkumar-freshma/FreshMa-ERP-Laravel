<?php

namespace App\Http\Controllers\Admin\HRM;

use App\DataTables\HRM\PayrollTemplateDataTable;
use App\Http\Controllers\Controller;
use App\Http\Requests\HRM\PayrollTemplateFormRequest;
use App\Models\PayrollTemplate;
use App\Models\PayrollType;
use App\Models\Staff;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PayrollTemplateController extends Controller
{
    public function index(PayrollTemplateDataTable $dataTable)
    {
        return $dataTable->render('pages.hrm.payroll_template.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data['employee'] = Staff::where('status', 1)->get();
        $data['payroll_types'] = PayrollType::where('status', 1)->get();
        return view('pages.hrm.payroll_template.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(PayrollTemplateFormRequest $request)
    {
        // try {
        // $validator = Validator::make(
        //     $request->all(),
        //     [
        //         'payroll_name' => 'required|string|max:191|min:1',
        //         'payroll_type' => 'required|integer',
        //         'status' => 'required|integer',
        //     ],
        //     [
        //         '*.required' => 'This field is required',
        //         '*.regex' => 'Only alphabets and digits are allowed',
        //         '*.max' => 'Maximum character limit is :max',
        //         '*.min' => 'Minimum :min characters are required',
        //     ]
        // );

        // if ($validator->fails()) {
        //     return redirect()->back()->withErrors($validator)->withInput();
        // }
        $payrollData = $request->input('payroll_data');
        $payrollTemplate = new PayrollTemplate();
        $payrollTemplate->employee_id = $request->input('employee_id');
        $payrollTemplate->status = $request->input('status');
        $payrollTemplate->payroll_templates = json_encode(array_values($payrollData));
        $payrollTemplate->save();

        if ($request->submission_type == 1) {
            return redirect()->route('admin.pay-roll-template.index')->with('success', 'Leave Type Store Successfully');
        } elseif ($request->submission_type == 2) {
            return back()->with('success', 'PayRoll Template Store Successfully');
        }
        // } catch (\Exception $e) {
        //     Log::error($e);
        //     return back()->withInput()->with('error', 'Payroll Template Type Stored Fail');
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
        $data['employee'] = Staff::where('status', 1)->get();
        $data['payroll_types'] = PayrollType::where('status', 1)->get();
        $data['pay_roll_template'] = PayrollTemplate::findOrFail($id);
        // Decode the JSON data from the database
        $data['stored_payroll_data'] = json_decode($data['pay_roll_template']->payroll_templates, true);
        return view('pages.hrm.payroll_template.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(PayrollTemplateFormRequest $request, $id)
    {
        try {
            $payrollData = $request->input('payroll_data');
            $payrollTemplate = PayrollTemplate::findOrFail($id);
            $payrollTemplate->employee_id = $request->input('employee_id');
            $payrollTemplate->status = $request->input('status');
            $payrollTemplate->payroll_templates = json_encode(array_values($payrollData));
            $payrollTemplate->update();

            // Redirect with a success message
            return redirect()->route('admin.pay-roll-template.index')->with('success', 'PayRoll Template Updated Successfully');
        } catch (\Exception $e) {
            Log::error($e);
            return back()->withInput()->with('error', 'Payroll Template Updated Fail');
        }
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
            PayrollTemplate::findOrFail($id)->delete();

            return redirect()->route('admin.pay-roll-template.index')->with('success', 'Leave Types Deleted Successfully');
        } catch (\Exception $e) {
            Log::error($e);
            return response()->json([
                'status' => 400,
                'message' => 'Sorry, Something went Wrong',
            ]);
        }
    }
    // public function storedLeaveType(Request $request)
    // {
    //     try {
    //         // Validate the request data if needed
    //         $request->validate([
    //             'leave_type_name' => 'required|string',
    //             'leave_type_status' => 'required|integer',
    //         ]);

    //         // Create a new LeaveType instance
    //         $leaveType = new LeaveType();
    //         $leaveType->name = $request->input('leave_type_name');
    //         $leaveType->status = $request->input('leave_type_status');
    //         $leaveType->save();

    //         // Optionally, you can return the saved data in the response
    //         $data = $leaveType;

    //         return response()->json([
    //             'status' => 200,
    //             'data' => $data,
    //             'message' => 'Leave Type Added Successfully',
    //         ]);
    //     } catch (\Exception $e) {
    //         Log::error($e);

    //         return response()->json([
    //             'status' => 400,
    //             'message' => 'Sorry, Something went Wrong',
    //         ]);
    //     }
    // }
}
