<?php

namespace App\Http\Controllers\Admin\LoanManagement;

use App\Core\CommonComponent;
use App\DataTables\LoanManagement\LoanDataTable;
use App\Http\Controllers\Controller;
use App\Http\Requests\LoanManagement\LoanFormRequest;
use App\Models\CompanyLoanBankAccountDetail;
use App\Models\Loan;
use App\Models\LoanCategory;
use App\Models\LoanTransaction;
use App\Models\PaymentTransaction;
use App\Models\Payroll;
use App\Models\Staff;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Log;

class LoanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(LoanDataTable $dataTable)
    {
        return $dataTable->render('pages.loan_management.loan.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data['loan_code'] = commoncomponent::invoice_no('loan_code');
        $data['employees'] = Staff::where('status', 1)->get();
        $data['product_categories'] = LoanCategory::where('status', 1)->get();
        $data['loan_bank_account'] = CompanyLoanBankAccountDetail::all();
        return view('pages.loan_management.loan.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(LoanFormRequest $request)
    {
        try {
            // return $request->file('documents');
            // return $request->all();
            $filePath = null;
            if ($request->hasFile('documents') && $request->file('documents') != null) {
                $fileData = CommonComponent::s3BucketFileUpload($request->documents, 'loan');
                $filePath = $fileData['filePath'];
            }
            $bank_id = null;
            $employee_id = null;

            // Check loan_type_id and set values accordingly
            if ($request->loan_type_id == 1) {
                $bank_id = $request->bank_id;
            } else {
                $employee_id = $request->employee_id;
            }

            $loan = new Loan();
            $loan->loan_type_id = $request->loan_type_id;
            $loan->bank_id = $bank_id;
            $loan->employee_id = $employee_id;
            $loan->loan_code = $request->loan_code;
            $loan->loan_category_id = $request->loan_category_id;
            $loan->phone_number = $request->phone_number;
            $loan->applied_amount = $request->applied_amount;
            $loan->applied_on = $request->applied_on;
            $loan->deduct_form_salary = $request->deduct_form_salary;
            $loan->guarantors = $request->guarantors;
            $loan->remarks = $request->remarks;
            $loan->principal_amount = $request->principal_amount;
            $loan->first_payment_date = $request->first_payment_date;
            $loan->loan_tenure = $request->loan_tenure;
            $loan->loan_term = $request->loan_term;
            $loan->interest_rate = $request->interest_rate;
            $loan->interest_frequency = $request->interest_frequency;
            $loan->repayment_frequency = $request->repayment_frequency;
            $loan->repayment_amount = $request->repayment_amount;
            $loan->late_payment_penalty_rate = $request->late_payment_penalty_rate;
            $loan->description = $request->description;
            if ($filePath != null) {
                $loan->documents = $filePath; // Note: Consider changing this column name
            }
            $loan->loan_officer = '';
            $loan->disburse_method = $request->disburse_method;
            $loan->distributed_date = $request->distributed_date;
            $loan->loan_status = $request->loan_status;
            $loan->disburse_notes = $request->disburse_notes ?? '';
            // // Set loan_officer based on loan_type_id
            // if ($request->loan_type_id == 1) {
            //     // Bank loan, set bank officer as loan officer
            //     // Replace $bankOfficerId with the actual ID of the bank officer
            // } else {
            //     // Employee loan, set employee as loan officer
            //     $loan->loan_officer = $request->employee_id;
            // }
            // Save the Loan instance
            $loan->save();

            if ($request->submission_type == 1) {
                return redirect()->route('admin.loans.index')->with('success', 'Loan  Successfully');
            } elseif ($request->submission_type == 2) {
                return back()->with('success', 'Loan  Successfully');
            }
        } catch (\Exception $e) {
            // Log the error
            Log::error($e);

            // Redirect back with input and error message
            return back()->withInput()->with('error', 'Loan  Creation Failed');
        }
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
            $data['loan'] = Loan::findOrFail($id);
            $data['employee'] = Staff::all();
            $data['loan_category'] = LoanCategory::all();
            return view('pages.loan_management.loan.view', $data);
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
        $data['employees'] = Staff::where('status', 1)->get();
        $data['loans'] = Loan::FindOrFail($id);
        $data['product_categories'] = LoanCategory::where('status', 1)->get();
        $data['loan_bank_account'] = CompanyLoanBankAccountDetail::all();
        return view('pages.loan_management.loan.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(LoanFormRequest $request, $id)
    {
        try {
            $filePath = null;
            if ($request->hasFile('documents') && $request->file('documents') != null) {
                $fileData = CommonComponent::s3BucketFileUpload($request->documents, 'loan');
                $filePath = $fileData['filePath'];
            }

            $bank_id = null;
            $employee_id = null;

            // Check loan_type_id and set values accordingly
            if ($request->loan_type_id == 1) {
                $bank_id = $request->bank_id;
            } else {
                $employee_id = $request->employee_id;
            }

            $loan = Loan::findOrFail($id);
            $loan->loan_type_id = $request->loan_type_id;
            $loan->bank_id = $bank_id;
            $loan->employee_id = $employee_id;
            $loan->loan_code = $request->loan_code;
            $loan->loan_category_id = $request->loan_category_id;
            $loan->phone_number = $request->phone_number;
            $loan->applied_amount = $request->applied_amount;
            $loan->applied_on = $request->applied_on;
            $loan->deduct_form_salary = $request->deduct_form_salary;
            $loan->guarantors = $request->guarantors;
            $loan->remarks = $request->remarks;
            $loan->principal_amount = $request->principal_amount;
            $loan->first_payment_date = $request->first_payment_date;
            $loan->loan_tenure = $request->loan_tenure;
            $loan->loan_term = $request->loan_term;
            $loan->interest_rate = $request->interest_rate;
            $loan->interest_frequency = $request->interest_frequency;
            $loan->repayment_frequency = $request->repayment_frequency;
            $loan->repayment_amount = $request->repayment_amount;
            $loan->late_payment_penalty_rate = $request->late_payment_penalty_rate;
            $loan->description = $request->description;
            if ($filePath != null) {
                $loan->documents = $filePath; // Note: Consider changing this column name
            }
            $loan->disburse_method = $request->disburse_method;
            $loan->distributed_date = $request->distributed_date;
            $loan->loan_status = $request->loan_status;
            $loan->disburse_notes = $request->disburse_notes ?? '';

            // Save the Loan instance
            $loan->update();

            return redirect()->route('admin.loans.index')->with('success', 'Loan  Updated Successfully');
        } catch (\Exception $e) {
            Log::error($e);
            return back()->withInput()->with('error', 'Loan  Updated Fail');
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
            Loan::findOrFail($id)->delete();

            return response()->json([
                'status' => 200,
                'message' => 'Loan  Deleted Successfully.',
            ]);
        } catch (\Exception $e) {
            Log::error($e);
            return response()->json([
                'status' => 400,
                'message' => 'Sorry, Something went Wrong',
            ]);
        }
    }
    public function getLoanCategoryDetails(Request $request)
    {
        try {
            $loan_category_id = $request->loanCategroyId;
            $LoanCategoryDetails = LoanCategory::findOrFail($loan_category_id);
            return response()->json([
                'status' => 200,
                'loan_categroy_details' => $LoanCategoryDetails,
                'message' => 'Loan Category Details Fetched Successfully.',
            ]);
        } catch (\Exception $e) {
            Log::error($e);
            return response()->json([
                'status' => 400,
                'message' => 'Sorry, Something went Wrong',
            ]);
        }
    }

    public function loanStatusChanged(Request $request)
    {
        try {
            Log::info('loans');
            $loan = Loan::findOrFail($request->loan_id);
            if (!$loan) {
                return response()->json([
                    'status' => 404,
                    'message' => 'Loan not found',
                ]);
            }
            // Update loan status
            $loan->loan_status = $request->status;
            $loan->save();

            if ($loan->loan_status == 2 && $loan->deduct_form_salary == 1) {
                $payroll = Payroll::where('employee_id', $loan->employee_id)->first();
                if ($payroll) {
                    $debitFromSalary = $payroll->gross_salary - $loan->repayment_amount;
                    $payroll->update([
                        'gross_salary' => $debitFromSalary,
                    ]);
                } else {
                    // Handle the case where the payroll record is not found for the given employee_id
                    // You might want to log an error or take appropriate action.
                }
                // $payrollType = PayrollType::where('status', 1)->where('is_loan', 1)->first();
                // $payrolltemplate = PayrollTemplate::where('employee_id', $loan->employee_id)->first();
                // PaySilpAutoGenerateJob::dispatch();
            }

            if ($loan->loan_status == 2) {
                // Create LoanTransaction
                Log::info('loans amount');
                $loanTransaction = new LoanTransaction();
                $loanTransaction->loan_id = $loan->id;
                $loanTransaction->type = 2;
                $loanTransaction->amount = $loan->applied_amount;
                $loanTransaction->transaction_datetime = Carbon::now();
                $loanTransaction->status = 1; // You may want to adjust this based on your logic
                $loanTransaction->note = 'Transaction notes go here'; // Add relevant notes

                $loanTransaction->save();

                Log::info("payment Transaction");
                $paymentTransaction = new PaymentTransaction();
                $paymentTransaction->reference_id = $loanTransaction->id;
                $paymentTransaction->type = $loanTransaction->type;
                $paymentTransaction->amount = $loanTransaction->amount;
                $paymentTransaction->payment_type_id = $loan->disburse_method;
                $paymentTransaction->transaction_datetime = $loanTransaction->transaction_datetime;
                $paymentTransaction->status = $loanTransaction->status;
                $paymentTransaction->note = $loanTransaction->note;

                $paymentTransaction->save();
            }
            // Create PaymentTransaction

            return redirect()->route('admin.loans.index')->with('success', 'Status Updated Successfully');
        } catch (\Exception $e) {
            Log::error($e);

            return response()->json([
                'status' => 500,
                'message' => 'Sorry, something went wrong',
            ]);
        }
    }

    public function storeBankAccount(Request $request)
    {
        try {
            Log::info("request");
            Log::info($request);
            $bankAccounts = new CompanyLoanBankAccountDetail();
            $bankAccounts->bank_name = $request->bank_name;
            $bankAccounts->branch_name = $request->bank_branch;
            $bankAccounts->ifsc_code = $request->ifsc_code;
            $bankAccounts->save();

            return response()->json([
                'status' => 200,
                'message' => 'Successfully created bank account',
                'data' => $bankAccounts,
            ]);
        } catch (\Exception $e) {
            Log::error($e);

            return response()->json([
                'status' => 500,
                'message' => 'Sorry, something went wrong',
            ]);
        }
    }
}
