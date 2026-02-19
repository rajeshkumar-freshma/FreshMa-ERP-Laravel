<?php

namespace App\Http\Controllers\Admin\HRM;

use App\DataTables\HRM\PayrollTypeDataTable;
use App\Http\Controllers\Controller;
use App\Models\PayrollType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class PayrollTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(PayrollTypeDataTable $dataTable)
    {
        return $dataTable->render('pages.hrm.payroll_types.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('pages.hrm.payroll_types.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // try {
        $validator = Validator::make(
            $request->all(),
            [
                'payroll_name' => 'required|string|max:191|min:1',
                'payroll_type' => 'required|integer',
                'status' => 'required|integer',
            ],
            [
                '*.required' => 'This field is required',
                '*.regex' => 'Only alphabets and digits are allowed',
                '*.max' => 'Maximum character limit is :max',
                '*.min' => 'Minimum :min characters are required',
            ]
        );

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        $payroll = new PayrollType();
        $payroll->name = $request->payroll_name;
        $payroll->details = $request->details != null ? $request->details : '';
        $payroll->payroll_types = $request->payroll_type;
        $payroll->is_loan = $request->is_loan ? $request->is_loan : 0;
        $payroll->status = $request->status ? $request->status : 0;
        $payroll->save();

        if ($request->submission_type == 1) {
            return redirect()->route('admin.pay-roll-type.index')->with('success', 'Payroll Store Successfully');
        } elseif ($request->submission_type == 2) {
            return back()->with('success', 'Payroll Store Successfully');
        }
        // } catch (\Exception $e) {
        //     Log::error($e);
        //     return back()->withInput()->with('error', 'Unit Stored Fail');
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
            $data['payroll_types'] = PayrollType::findOrFail($id);
            return view('pages.hrm.payroll_types.view', $data);
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
        try {
            $data['payroll_type'] = PayrollType::findOrFail($id);
            return view('pages.hrm.payroll_types.edit', $data);
        } catch (\Exception $e) {
            \Log::error($e);
            return back()->withInput()->with('error', 'Payroll update failed');
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        // try {
        $validator = Validator::make(
            $request->all(),
            [
                'payroll_name' => 'required|max:191|min:1',
                'payroll_type' => 'required|integer',
                'status' => 'required|integer',
            ],
            [
                '*.required' => 'This field is required',
                '*.regex' => 'Only alphabets and digits are allowed',
                '*.max' => 'Maximum character limit is :max',
                '*.min' => 'Minimum :min characters are required',
            ]
        );

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        $payroll = PayrollType::findOrFail($id);
        $payroll->name = $request->payroll_name;
        $payroll->details = $request->details != null ? $request->details : '';
        $payroll->payroll_types = $request->payroll_type;
        $payroll->status = $request->status ? $request->status : 0;
        $payroll->is_loan = $request->is_loan ? $request->is_loan : 0;
        $payroll->update(); // Use lowercase 'update' instead of 'Update'

        return redirect()->route('admin.pay-roll-type.index')->with('success', 'Payroll updated successfully');
        // } catch (\Exception $e) {
        //     \Log::error($e);
        //     return back()->withInput()->with('error', 'Payroll update failed');
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
            PayrollType::findOrFail($id)->delete();

            return response()->json([
                'status' => 200,
                'message' => 'Payroll Deleted Successfully.',
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
