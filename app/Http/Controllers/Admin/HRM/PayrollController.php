<?php

namespace App\Http\Controllers\Admin\HRM;

use App\DataTables\HRM\PayrollDataTable;
use App\Http\Controllers\Controller;
use App\Http\Requests\HRM\PayrollFormRequest;
use App\Models\Payroll;
use App\Models\PayrollDetail;
use App\Models\PayrollTemplate;
use App\Models\PayrollType;
use App\Models\Staff;
use App\Models\UserAdvance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PayrollController extends Controller
{
    public function index(PayrollDataTable $dataTable)
    {
        return $dataTable->render('pages.hrm.payroll.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data['employee'] = Staff::where('status', 1)->get();
        $data['earnings'] = PayrollType::where('status', 1)->where('payroll_types', 1)->get();
        $data['deductions'] = PayrollType::where('status', 1)->where('payroll_types', 0)->get();
        $data['payroll_template'] = PayrollTemplate::where('status', 1)->get();
        return view('pages.hrm.payroll.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(PayrollFormRequest $request)
    {
        // try {
        // return $request->all();
        $employee_id = $request->employee_id;
        $payrollData = $request->payroll_data;
        $year = $request->payroll_year;
        $month = $request->payroll_month;
        $status = $request->status;
        $remarks = $request->remarks;
        $loss_of_pay_days = $request->loss_of_pay_days;
        $number_of_working_days = $request->number_of_working_days;
        $gross_salary = $request->gross_salary;

        $payroll = new Payroll();
        $payroll->employee_id = $employee_id;
        $payroll->month = $month;
        $payroll->year = $year;
        $payroll->gross_salary = $gross_salary;
        $payroll->remarks = $remarks;
        $payroll->status = $status;
        $payroll->loss_of_pay_days = $loss_of_pay_days;
        $payroll->no_of_working_days = $number_of_working_days;
        $payroll->save();
        if (isset($payrollData) && count($payrollData) > 0) {
            foreach ($payrollData as $item) {
                $payrollDetail = new PayrollDetail();
                $payrollDetail->payroll_id = $payroll->id;
                $payrollDetail->payroll_type_id = $item['payroll_type_id'];
                $payrollDetail->amount = $item['amount'];
                $payrollDetail->save();
            }
        }
        if ($request->submission_type == 1) {
            return redirect()->route('admin.payroll.index')->with('success', 'Payroll Store Successfully');
        } elseif ($request->submission_type == 2) {
            return back()->with('success', 'Payroll Store Successfully');
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
            $data['payroll'] = Payroll::findOrFail($id);
            $data['employee'] = Staff::all();
            return view('pages.hrm.payroll.view', $data);
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
            // Retrieve the payroll and related details
            $data['payroll'] = Payroll::findOrFail($id);
            $data['payroll_details'] = PayrollDetail::where('payroll_id', $id)->get();

            // Separate earnings and deductions
            $data['earnings'] = $data['deductions'] = [];

            foreach ($data['payroll_details'] as $payrollDetail) {
                // Ensure that the payroll_type relationship is loaded
                $payrollType = $payrollDetail->payroll_type;

                if ($payrollType && $payrollType->payroll_types == 1) {

                    $data['earnings'][] = $payrollDetail;
                } elseif ($payrollType) {
                    $data['deductions'][] = $payrollDetail;
                } else {
                    Log::warning("Invalid payroll_type for PayrollDetail ID {$payrollDetail->id}");
                }
            }
            // Retrieve the list of employees
            $data['employees'] = Staff::where('status', 1)->get();

            return view('pages.hrm.payroll.edit', $data);
        } catch (\Exception $e) {
            // Handle exceptions, e.g., record not found
            Log::error("Error in Payroll edit: " . $e->getMessage());
            return redirect()->back()->with('error', 'Error fetching payroll data.');
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
        $employee_id = $request->employee_id;
        $payrollData = $request->payroll_data;
        $year = $request->payroll_year;
        $month = $request->payroll_month;
        $status = $request->status;
        $remarks = $request->remarks;
        $loss_of_pay_days = $request->loss_of_pay_days;
        $number_of_working_days = $request->number_of_working_days;
        $gross_salary = $request->gross_salary;

        $payroll = Payroll::findOrFail($id);
        $payroll->employee_id = $employee_id;
        $payroll->month = $month;
        $payroll->year = $year;
        $payroll->gross_salary = $gross_salary;
        $payroll->remarks = $remarks;
        $payroll->status = $status;
        $payroll->loss_of_pay_days = $loss_of_pay_days;
        $payroll->no_of_working_days = $number_of_working_days;
        $payroll->save(); // Use save() instead of update()

        // Delete existing PayrollDetails for the given Payroll
        // $payroll->PayrollDetail()->delete();

        // Update or create new PayrollDetails
        foreach ($payrollData as $item) {
            PayrollDetail::updateOrCreate(
                [
                    'payroll_id' => $payroll->id,
                    'payroll_type_id' => $item['payroll_type_id'],
                    'amount' => $item['amount'],
                ]
            );
        }
        if ($request->submission_type == 1) {
            return redirect()->route('admin.payroll.index')->with('success', 'Payroll Store Successfully');
        } elseif ($request->submission_type == 2) {
            return back()->with('success', 'Payroll Store Successfully');
        }
        // } catch (\Exception $e) {
        //     Log::error($e);
        //     return back()->withInput()->with('error', 'Payroll Update Failed');
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
            Payroll::findOrFail($id)->delete();

            return redirect()->route('admin.payroll.index')->with('success', 'Payroll Deleted Successfully');
        } catch (\Exception $e) {
            Log::error($e);
            return response()->json([
                'status' => 400,
                'message' => 'Sorry, Something went Wrong',
            ]);
        }
    }

    public function getPayrollSlip(Request $request)
    {
        try {
            // Validate the request data if needed
            $employeeId = $request->employeeId;
            Log::info($employeeId);

            // Create a new LeaveType instance
            $payrollData = PayrollTemplate::where('employee_id', $employeeId)
                ->where('status', 1)
                ->first(); // Use first() instead of get()

            // Optionally, you can return the saved data in the response
            Log::info($payrollData);
            return response()->json([
                'status' => 200,
                'data' => $payrollData,
                'message' => 'Pay roll template Added Successfully',
            ]);
        } catch (\Exception $e) {
            Log::error($e);

            return response()->json([
                'status' => 400,
                'message' => 'Sorry, Something went Wrong',
            ]);
        }
    }

    public function getPayrollSlipwithpreviousdata(Request $request)
    {
        try {
            // Validate the request data if needed
            $employeeId = $request->employeeId;
            Log::info("employeeId");
            Log::info($request->all());

            // Create a new LeaveType instance
            $payrollData = PayrollTemplate::where('employee_id', $employeeId)
                ->where('status', 1)
                ->first(); // Use first() instead of get()

            $payRollId = $request->payRollId;
            $payroll_details = [];
            if ($payRollId != null && $payRollId != 'null') {
                $payroll_details = PayrollDetail::where('payroll_id', $payRollId)->get();
            }
            $payroll_templates = json_decode($payrollData->payroll_templates, true);
            Log::info(" payroll_templates");
            Log::info($payroll_templates);

            $earning_count = 0;
            $deduction_count = 0;
            $data = [];
            if (count($payroll_templates) > 0) {
                foreach ($payroll_templates as $key => $payroll_template) {
                    $payroll_templates_Ids[] = $payroll_template['payroll_type_id'];
                }
                foreach ($payroll_templates as $payroll_template) {
                    // Ensure that the payroll_type relationship is loaded
                    $payrollType = PayrollType::where('id', $payroll_template['payroll_type_id'])->first();
                    if (isset($payroll_details) && count($payroll_details) > 0) {
                        $payrollIds = $payroll_details->pluck('payroll_type_id')->toArray();
                        foreach ($payroll_details as $payroll_detail) {
                            if ($payrollType && $payrollType?->payroll_types == 1 && $payroll_detail->payroll_type_id == $payroll_template['payroll_type_id']) {
                                $data['earnings'][$earning_count]['amount'] = $payroll_detail->amount ?? 0;
                                $data['earnings'][$earning_count]['payroll_type_name'] = $payrollType->name;
                                $data['earnings'][$earning_count]['payroll_type_id'] = $payrollType->id;
                                $earning_count++;
                            } elseif ($payrollType && $payrollType?->payroll_types == 2 && $payroll_detail->payroll_type_id == $payroll_template['payroll_type_id']) {
                                $data['deductions'][$deduction_count]['amount'] = $payroll_detail->amount ?? 0;
                                $data['deductions'][$deduction_count]['payroll_type_name'] = $payrollType->name;
                                $data['deductions'][$deduction_count]['payroll_type_id'] = $payrollType->id;
                                $deduction_count++;
                            } elseif (!in_array($payroll_template['payroll_type_id'], $payrollIds) && $payrollType->payroll_types == 1) {
                                $data['earnings'][$earning_count]['amount'] = $payroll_template['amount'];
                                $data['earnings'][$earning_count]['payroll_type_name'] = $payrollType->name;
                                $data['earnings'][$earning_count]['payroll_type_id'] = $payrollType->id;
                                array_push($payrollIds, $payrollType->id);
                                $earning_count++;
                            } elseif (!in_array($payroll_template['payroll_type_id'], $payrollIds) && $payrollType->payroll_types == 2) {
                                $data['deductions'][$earning_count]['amount'] = $payroll_template['amount'];
                                $data['deductions'][$earning_count]['payroll_type_name'] = $payrollType->name;
                                $data['deductions'][$earning_count]['payroll_type_id'] = $payrollType->id;
                                array_push($payrollIds, $payrollType->id);
                                $deduction_count++;
                            } elseif (!in_array($payroll_detail->payroll_type_id, $payroll_templates_Ids)) {
                                $payrollType = PayrollType::where('id', $payroll_detail->payroll_type_id)->first();
                                if (!in_array($payroll_detail->payroll_type_id, $payroll_templates_Ids) && $payrollType->payroll_types == 1) {
                                    $data['earnings'][$earning_count]['amount'] = $payroll_template['amount'];
                                    $data['earnings'][$earning_count]['payroll_type_name'] = $payrollType->name;
                                    $data['earnings'][$earning_count]['payroll_type_id'] = $payrollType->id;
                                    array_push($payroll_templates_Ids, $payroll_detail->payroll_type_id);
                                    $earning_count++;
                                } elseif (!in_array($payroll_detail->payroll_type_id, $payroll_templates_Ids) && $payrollType->payroll_types == 2) {
                                    $data['deductions'][$earning_count]['amount'] = $payroll_template['amount'];
                                    $data['deductions'][$earning_count]['payroll_type_name'] = $payrollType->name;
                                    $data['deductions'][$earning_count]['payroll_type_id'] = $payrollType->id;
                                    array_push($payroll_templates_Ids, $payroll_detail->payroll_type_id);
                                    $deduction_count++;
                                }
                            }
                        }
                    } else {
                        if ($payrollType->payroll_types == 1) {
                            $data['earnings'][$earning_count]['amount'] = $payroll_template['amount'];
                            $data['earnings'][$earning_count]['payroll_type_name'] = $payrollType->name;
                            $data['earnings'][$earning_count]['payroll_type_id'] = $payrollType->id;
                            $earning_count++;
                        } elseif ($payrollType->payroll_types == 2) {
                            $data['deductions'][$earning_count]['amount'] = $payroll_template['amount'];
                            $data['deductions'][$earning_count]['payroll_type_name'] = $payrollType->name;
                            $data['deductions'][$earning_count]['payroll_type_id'] = $payrollType->id;
                            $deduction_count++;
                        }
                    }
                }
            } else {
                return response()->json([
                    'status' => 400,
                    'message' => 'Sorry, There is no payroll template available',
                ]);
            }

            if (count($data) > 0) {
                $view = view('pages.hrm.payroll.earning_deduction_render', $data)->render();
                return response()->json([
                    'status' => 200,
                    'data' => $view,
                ]);
            } else {
                $view = view('pages.hrm.payroll.earning_deduction_render', $data)->render();
                return response()->json([
                    'status' => 400,
                    'data' => null,
                ]);
            }
        } catch (\Exception $e) {
            Log::error($e);

            return response()->json([
                'status' => 400,
                'message' => 'Sorry, Something went Wrong',
            ]);
        }
    }

    public function userAdvanced(Request $request)
    {
        try {
            // Validate the request data if needed
            $employeeId = $request->employeeId; // Use input method to get request data

            Log::info("Employee IDS: " . $employeeId);

            // Use first() instead of get() to retrieve a single model instance
            $payrollType = PayrollType::where('status', 1)
                ->where('payroll_types', 0)
                ->where('is_loan', 1)
                ->first();
            Log::info("$payrollType IDS: " . $payrollType);

            if (!$payrollType) {
                return response()->json([
                    'status' => 404,
                    'message' => 'Payroll type not found',
                ]);
            }

            // Use optional() to handle null values and prevent errors
            $amount = optional(UserAdvance::where('user_id', $employeeId)
                    ->where('status', 1)
                    ->where('type', 1)
                    ->first())
                ->amount;

            Log::info("User Advance Amount: " . $amount);

            return response()->json([
                'status' => 200,
                'amount' => $amount,
                'payrollTypeId' => $payrollType->id, // Access the 'id' property of the retrieved model
                'message' => 'Payroll template added successfully',
            ]);
        } catch (\Exception $e) {
            Log::error($e);

            return response()->json([
                'status' => 500,
                'message' => 'Sorry, something went wrong',
            ]);
        }
    }

    public function AddPayrollMethod(Request $request)
    {
        try {
            $employeeId = $request->employeeId;
            $type = $request->type;
            $payrollTypeMethods = PayrollType::where('status', 1)->where('payroll_types', $type)->get();
            Log::info("payrollTypeMethods");
            Log::info($payrollTypeMethods);
            return response()->json([
                'status' => 200,
                'data' => $payrollTypeMethods,
                'type' => $type,
                'message' => 'Pay roll type mthods Added Successfully',
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
