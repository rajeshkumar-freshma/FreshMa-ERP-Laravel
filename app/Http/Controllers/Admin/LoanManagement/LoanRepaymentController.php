<?php

namespace App\Http\Controllers\Admin\LoanManagement;

use App\DataTables\LoanManagement\LoanRepaymentDataTable;
use App\Http\Controllers\Controller;
use App\Http\Requests\LoanManagement\LoanRepaymentFormRequest;
use App\Models\Loan;
use App\Models\LoanRepayment;
use App\Models\LoanTransaction;
use App\Models\PaymentTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class LoanRepaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(LoanRepaymentDataTable $dataTable)
    {
        return $dataTable->render('pages.loan_management.loan_repayment.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data['loanTransactions'] = Loan::where('loan_status', 2)->get();
        return view('pages.loan_management.loan_repayment.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(LoanRepaymentFormRequest $request)
    {
        // try {
        // Check if the associated loan exists and has been applied for
        $loan = Loan::findOrFail($request->loan_id);
        if ($loan->loan_status != 2) {
            // If the loan status is not "applied" (status 2), return with an error message
            return back()->withInput()->with('error', 'Cannot make repayment for a loan that has not been applied for.');
        }

        $loanRepayment = new LoanRepayment([
            'loan_id' => $request->loan_id,
            'payment_date' => $request->payment_date,
            'invoice_number' => 1,
            'instalment_amount' => $request->instalment_amount,
            'pay_amount' => $request->pay_amount,
            'due_amount' => $request->due_amount,
        ]);
        $loanRepayment->save();

        $loan = Loan::find($loanRepayment->loan_id);

        $totalPaidAmount = LoanRepayment::where('loan_id', $loanRepayment->loan_id)
            ->sum('pay_amount');

        $loanTransaction = new LoanTransaction([
            'loan_id' => $loanRepayment->loan_id,
            'type' => 1,
            'amount' => $loanRepayment->pay_amount,
            'transaction_datetime' => now(),
            'status' => 1,
            'note' => 'Transaction notes go here',
        ]);

        $loanTransaction->save();

        Log::info("Payment Transaction");

        if ($totalPaidAmount > $loan->applied_amount) {
            $loan->loan_status = 4;
            $loan->save();
        }

        $paymentTransaction = new PaymentTransaction([
            'reference_id' => $loanTransaction->loan_id,
            'type' => $loanTransaction->type,
            'amount' => $loanTransaction->amount,
            // 'payment_type_id' => 1,
            'transaction_datetime' => $loanTransaction->transaction_datetime,
            'status' => $loanTransaction->status,
            'note' => $loanTransaction->note,
        ]);

        $paymentTransaction->save();

        if ($request->submission_type == 1) {
            return redirect()->route('admin.loan-repayment.index')->with('success', 'Loan Repayment Created  Successfully');
        } elseif ($request->submission_type == 2) {
            return back()->with('success', 'Loan Repayment Created  Successfully');
        }

        // } catch (\Exception $e) {
        //     // Log the error
        //     Log::error($e);

        //     // Redirect back with input and error message
        //     return back()->withInput()->with('error', 'Loan Repayment Creation Failed');
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
            $data['loan_repayment'] = LoanRepayment::findOrFail($id);
            return view('pages.loan_management.loan_repayment.view', $data);
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
        $data['loanRepayment'] = LoanRepayment::FindOrFail($id);
        $data['loanTransactions'] = Loan::where('loan_status', 2)->get();
        return view('pages.loan_management.loan_repayment.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(LoanRepaymentFormRequest $request, $id)
    {
        try {
            $loanRepayment = LoanRepayment::findOrFail($id);
            // Check if the associated loan exists and has been applied for
            $loan = Loan::findOrFail($loanRepayment->loan_id);
            if ($loan->loan_status != 2) {
                // If the loan status is not "applied" (status 2), return with an error message
                return back()->withInput()->with('error', 'Cannot update repayment for a loan that has not been applied for.');
            }

            $loanRepayment->update([
                'loan_id' => $request->loan_id,
                'payment_date' => $request->payment_date,
                'invoice_number' => 1,
                'instalment_amount' => $request->instalment_amount,
                'pay_amount' => $request->pay_amount,
                'due_amount' => $request->due_amount,
            ]);

            $loan = Loan::find($loanRepayment->loan_id);

            $totalPaidAmount = LoanRepayment::where('loan_id', $loanRepayment->loan_id)
                ->sum('pay_amount');

            $loanTransactions = LoanTransaction::where('loan_id', $loanRepayment->loan_id)->get();

            foreach ($loanTransactions as $loanTransaction) {
                $loanTransaction->update([
                    'type' => 1,
                    'amount' => $loanRepayment->pay_amount,
                    'transaction_datetime' => now(),
                    'status' => 1,
                    'note' => 'Transaction notes go here',
                ]);
            }

            Log::info("Payment Transaction");

            if ($totalPaidAmount > $loan->applied_amount) {
                $loan->update(['loan_status' => 4]);
            }

            $paymentTransaction = PaymentTransaction::where('reference_id', $loanTransaction->loan_id)->first();
            $paymentTransaction->update([
                'reference_id' => $loanTransaction->loan_id,
                'type' => $loanTransaction->type,
                'amount' => $loanTransaction->amount,
                // 'payment_type_id' => 1,
                'transaction_datetime' => $loanTransaction->transaction_datetime,
                'status' => $loanTransaction->status,
                'note' => $loanTransaction->note,
            ]);

            return redirect()->route('admin.loan-repayment.index')->with('success', 'Loan Repayment Updated Successfully');
        } catch (\Exception $e) {
            Log::error($e);
            return back()->withInput()->with('error', 'Loan Repayment Updated Failed');
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
            LoanRepayment::findOrFail($id)->delete();

            return response()->json([
                'status' => 200,
                'message' => 'Loan Repayment  Deleted Successfully.',
            ]);
        } catch (\Exception $e) {
            Log::error($e);
            return response()->json([
                'status' => 400,
                'message' => 'Sorry, Something went Wrong',
            ]);
        }
    }
    public function getLoanDetails(Request $request)
    {
        try {
            Log::info('loans');
            $loan = Loan::findOrFail($request->loanId);
            Log::info($loan);
            if (!$loan) {
                return response()->json([
                    'status' => 404,
                    'message' => 'Loan not found',
                ]);
            }
            // Update loan status

            // if ($request->status == 2) {
            //     // Create LoanTransaction
            //     Log::info('loans amount');
            //     $loanTransaction = new LoanTransaction();
            //     $loanTransaction->loan_id = $loan->id;
            //     $loanTransaction->type = 1;
            //     $loanTransaction->amount = $loan->applied_amount;
            //     $loanTransaction->transaction_datetime = Carbon::now();
            //     $loanTransaction->status = 1; // You may want to adjust this based on your logic
            //     $loanTransaction->note = 'Transaction notes go here'; // Add relevant notes

            //     $loanTransaction->save();

            //     Log::info("payment Transaction");
            //     $paymentTransaction = new PaymentTransaction();
            //     $paymentTransaction->reference_id = $loanTransaction->id;
            //     $paymentTransaction->type = $loanTransaction->type;
            //     $paymentTransaction->amount = $loanTransaction->amount;
            //     $paymentTransaction->transaction_datetime = $loanTransaction->transaction_datetime;
            //     $paymentTransaction->status = $loanTransaction->status;
            //     $paymentTransaction->note = $loanTransaction->note;

            //     $paymentTransaction->save();
            // }
            // Create PaymentTransaction

            return response()->json([
                'status' => 200,
                'data' => $loan,
                'message' => 'Loan found',
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
