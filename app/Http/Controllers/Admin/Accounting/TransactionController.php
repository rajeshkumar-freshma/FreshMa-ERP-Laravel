<?php

namespace App\Http\Controllers\Admin\Accounting;

use App\DataTables\Accounting\TransactionDataTable;
use App\Http\Controllers\Controller;
use App\Http\Requests\Accounting\TransactionFormRequest;
use App\Models\Account;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class TransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(TransactionDataTable $dataTable)
    {
        return $dataTable->render('pages.accounting.transactions.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data['accounts'] = Account::where('status', 1)->get();
        return view('pages.accounting.transactions.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(TransactionFormRequest $request)
    {
        // try {
        // return $request->all();
        $transactions = Transaction::create([
            'transaction_account' => $request->transaction_account,
            'transaction_type' => $request->transaction_type,
            'available_balance' => $request->available_balance,
            'transaction_amount' => $request->transaction_amount,
            'transaction_date' => $request->transaction_date,
            'notes' => $request->notes,
            'status' => $request->status,
        ]);
        Log::info("request->available_balance,");
        Log::info($request->available_balance);
        Log::info("request->transfer_amount");
        Log::info($request->transfer_amount);

        if ($request->transaction_type == 0) {
            $transactionAmount = Account::findOrFail($request->transaction_account);
            Log::info("transactionAmount");
            Log::info($transactionAmount);
            $current_balance = $transactionAmount->balance + $request->transaction_amount;
            $transactionAmount->update(['balance' => $current_balance]);
            Log::info("current_balance transactionAmount ");
            Log::info($current_balance);
        }

        if ($request->transaction_type == 1) {
            $transactionAmount = Account::findOrFail($request->transaction_account);
            Log::info("transactionAmount");
            Log::info($transactionAmount);
            $current_balance = $transactionAmount->balance - $request->transaction_amount;
            $transactionAmount->update(['balance' => $current_balance]);
            Log::info("current_balance transactionAmount ");
            Log::info($current_balance);
        }

        if ($request->submission_type == 1) {
            return redirect()->route('admin.transaction.index')->with('success', 'Transaction Stored Successfully');
        } elseif ($request->submission_type == 2) {
            return back()->with('success', 'Transaction Stored Successfully');
        }

        // } catch (\Exception $e) {
        //     Log::error($e);
        //     return back()->withInput()->with('error', 'Transaction Created Fail');
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
        $data['accounts'] = Account::where('status', 1)->get();
        $data['transactions'] = Transaction::findOrfail($id);
        return view('pages.accounting.transactions.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(TransactionFormRequest $request, $id)
    {
        try {
            $transactions = Transaction::findOrFail($id);
            $transactions->transaction_account = $request->transaction_account;
            $transactions->transaction_type = $request->transaction_type;
            $transactions->available_balance = $request->available_balance;
            $transactions->transaction_amount = $request->transaction_amount;
            $transactions->transaction_date = $request->transaction_date;
            $transactions->notes = $request->initial_notes;
            $transactions->status = $request->status;
            $transactions->save();

            if ($request->transaction_type == 0) {
                $transactionAmount = Account::findOrFail($request->transaction_account);
                Log::info("transactionAmount");
                Log::info($transactionAmount);
                $current_balance = $transactionAmount->balance + $request->transaction_amount;
                $transactionAmount->update(['balance' => $current_balance]);
                Log::info("current_balance transactionAmount ");
                Log::info($current_balance);
            }

            if ($request->transaction_type == 1) {
                $transactionAmount = Account::findOrFail($request->transaction_account);
                Log::info("transactionAmount");
                Log::info($transactionAmount);
                $current_balance = $transactionAmount->balance - $request->transaction_amount;
                $transactionAmount->update(['balance' => $current_balance]);
                Log::info("current_balance transactionAmount ");
                Log::info($current_balance);
            }
            return redirect()->route('admin.transaction.index')->with('success', 'Transaction Updated Successfully');
        } catch (\Exception $e) {
            Log::error($e);
            return back()->withInput()->with('error', 'Transaction Updated Fail');
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
            Account::findOrFail($id)->delete();

            return redirect()->route('admin.transaction.index')->with('success', 'Transaction Deleted Successfully');
        } catch (\Exception $e) {
            Log::error($e);
            return response()->json([
                'status' => 400,
                'message' => 'Sorry, Something went Wrong',
            ]);
        }
    }
    public function getBankBalance(Request $request)
    {
        try {
            $bankId = $request->input('bankId');

            if ($bankId) {
                $bankBalance = Account::where('id', $bankId)
                    ->where('status', 1)
                    ->value('balance');

                if ($bankBalance !== null) {
                    return response()->json([
                        'status' => 200,
                        'bank_balance' => $bankBalance,
                    ]);
                } else {
                    return response()->json([
                        'status' => 400,
                        'message' => 'Bank Account not found or inactive',
                    ]);
                }
            } else {
                return response()->json([
                    'status' => 400,
                    'message' => 'Bank Account ID is required',
                ]);
            }
        } catch (\Exception $e) {
            Log::error($e);
            return response()->json([
                'status' => 500,
                'message' => 'Internal Server Error',
            ]);
        }
    }
}
