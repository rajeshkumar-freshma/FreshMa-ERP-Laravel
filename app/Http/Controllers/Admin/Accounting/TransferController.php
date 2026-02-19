<?php

namespace App\Http\Controllers\Admin\Accounting;

use App\DataTables\Accounting\TransferDataTable;
use App\Http\Controllers\Controller;
use App\Http\Requests\Accounting\TransferFormRequest;
use App\Models\Account;
use App\Models\Transaction;
use App\Models\Transfer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class TransferController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(TransferDataTable $dataTable)
    {
        return $dataTable->render('pages.accounting.transfer.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data['accounts'] = Account::where('status', 1)->get();
        return view('pages.accounting.transfer.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(TransferFormRequest $request)
    {
        // try {
        // return $request->all();
        // Create Transfer record
        $transfer = new Transfer();
        $transfer->transfer_reason = $request->transfer_reason;
        $transfer->from_account_id = $request->from_account_id;
        $transfer->to_account_id = $request->to_account_id;
        $transfer->available_balance = $request->available_balance;
        $transfer->transfer_amount = $request->transfer_amount;
        $transfer->transaction_date = $request->transaction_date;
        $transfer->notes = $request->notes;
        $transfer->status = $request->status;
        $transfer->save();

        // Update From account balance and create transaction
        if ($transfer->from_account_id !== null) {
            $fromAccount = Account::findOrFail($transfer->from_account_id);
            $fromAccount->balance -= $transfer->transfer_amount;
            $fromAccount->save();

            // Create transaction for From account
            $transaction = new Transaction();
            $transaction->transaction_account = $transfer->from_account_id;
            $transaction->transaction_type = 1; // Withdraw transaction
            $transaction->available_balance = $transfer->available_balance;
            $transaction->transaction_amount = $transfer->transfer_amount;
            $transaction->transaction_date = $request->transaction_date;
            $transaction->notes = $request->notes;
            $transaction->status = $transfer->status;
            $transaction->save();
        }

        // Update To account balance and create transaction
        if ($transfer->to_account_id !== null) {
            $toAccount = Account::findOrFail($transfer->to_account_id);
            $toAccount->balance += $transfer->transfer_amount;
            $toAccount->save();

            // Create transaction for To account
            $transaction = new Transaction();
            $transaction->transaction_account = $transfer->to_account_id;
            $transaction->transaction_type = 0; // Deposit transaction
            $transaction->available_balance = $toAccount->balance;
            $transaction->transaction_amount = $transfer->transfer_amount;
            $transaction->transaction_date = $request->transaction_date;
            $transaction->notes = $request->notes;
            $transaction->status = $transfer->status;
            $transaction->save();
        }

        // Redirect based on submission type
        if ($request->submission_type == 1) {
            return redirect()->route('admin.transfer.index')->with('success', 'Transfer Stored Successfully');
        } elseif ($request->submission_type == 2) {
            return back()->with('success', 'Transfer Stored Successfully');
        }

        // } catch (\Exception $e) {
        //     Log::error($e);
        //     return back()->withInput()->with('error', 'Transfer Creation Failed');
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
        $data['transfer'] = Transfer::findOrfail($id);
        return view('pages.accounting.transfer.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(TransferFormRequest $request, $id)
    {
        // try {
        // return $rquest;
        $transfer = Transfer::find($id);
        $transfer->transfer_reason = $request->transfer_reason;
        $transfer->from_account_id = $request->from_account_id;
        $transfer->to_account_id = $request->to_account_id;
        $transfer->available_balance = $request->available_balance;
        $transfer->transfer_amount = $request->transfer_amount;
        $transfer->transaction_date = $request->transaction_date;
        $transfer->notes = $request->initial_notes;
        $transfer->status = $request->status;
        $transfer->save();

        // Update From account balance and create transaction
        if ($transfer->from_account_id !== null) {
            $fromAccount = Account::findOrFail($transfer->from_account_id);
            $fromAccount->update(['balance' => $fromAccount->balance - $transfer->transfer_amount]);

            // Update transaction for From account
            $fromTransaction = Transaction::where('transaction_account', $transfer->from_account_id)
                ->where('transaction_type', 1) // Assuming 1 is the withdraw transaction type
                ->update([
                    'available_balance' => $transfer->available_balance,
                    'transaction_amount' => $transfer->transfer_amount,
                    'transaction_date' => $transfer->transaction_date,
                    'notes' => $transfer->notes,
                    'status' => $transfer->status,
                ]);
        }

        // Update To account balance and create transaction
        if ($transfer->to_account_id !== null) {
            $toAccount = Account::findOrFail($transfer->to_account_id);
            $toAccount->update(['balance' => $toAccount->balance + $transfer->transfer_amount]);

            // Update transaction for To account
            $toTransaction = Transaction::where('transaction_account', $transfer->to_account_id)
                ->where('transaction_type', 0) // Assuming 0 is the deposit transaction type
                ->update([
                    'available_balance' => $toAccount->balance,
                    'transaction_amount' => $transfer->transfer_amount,
                    'transaction_date' => $transfer->transaction_date,
                    'notes' => $transfer->notes,
                    'status' => $transfer->status,
                ]);
        }

        if ($request->from_account_id == $request->to_account_id) {
            $toAccount = Account::findOrFail($request->from_account_id);
            $update_balance = $toAccount->balance;
            $toAccount->update(['balance' => $update_balance]);
        }

        // Redirect based on submission type
        if ($request->submission_type == 1) {
            return redirect()->route('admin.transfer.index')->with('success', 'Transfer Update Successfully');
        } elseif ($request->submission_type == 2) {
            return back()->with('success', 'Transfer Update Successfully');
        }
        // } catch (\Exception $e) {
        //     Log::error($e);
        //     return back()->withInput()->with('error', 'Transfer Updated Fail');
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
            Account::findOrFail($id)->delete();

            return redirect()->route('admin.transfer.index')->with('success', 'Transfer Deleted Successfully');
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
