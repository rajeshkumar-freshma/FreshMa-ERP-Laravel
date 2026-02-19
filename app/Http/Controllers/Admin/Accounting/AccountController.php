<?php

namespace App\Http\Controllers\Admin\Accounting;

use App\DataTables\Accounting\AccountDataTable;
use App\Http\Controllers\Controller;
use App\Http\Requests\Accounting\AccountFormRequest;
use App\Models\Account;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class AccountController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(AccountDataTable $dataTable)
    {
        return $dataTable->render('pages.accounting.account.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('pages.accounting.account.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(AccountFormRequest $request)
    {
        // try {
        $account = Account::create([
            'account_holder_name' => $request->name,
            'account_number' => $request->account_number,
            'bank_name' => $request->bank_name,
            'branch_name' => $request->branch_name,
            'account_type' => $request->account_type,
            'bank_ifsc_code' => $request->bank_ifsc_code,
            'balance' => $request->initial_balance,
            'address' => $request->address,
            'notes' => $request->notes,
            'status' => $request->status,
        ]);

        if ($request->submission_type == 1) {
            return redirect()->route('admin.accounts.index')->with('success', 'Accounts  Store Successfully');
        } elseif ($request->submission_ == 2) {
            return back()->with('success', 'Accounts  Store Successfully');
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

        $data['account'] = Account::findOrfail($id);
        return view('pages.accounting.account.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(AccountFormRequest $request, $id)
    {
        try {
            $leave = Account::findOrFail($id);
            $leave->account_holder_name = $request->name;
            $leave->account_number = $request->account_number;
            $leave->bank_name = $request->bank_name;
            $leave->branch_name = $request->branch_name;
            $leave->account_type = $request->account_type;
            $leave->bank_ifsc_code = $request->bank_ifsc_code;
            $leave->balance = $request->initial_balance;
            $leave->address = $request->address;
            $leave->notes = $request->notes;
            $leave->status = $request->status;
            $leave->save(); // Use the save() method instead of update()

            return redirect()->route('admin.accounts.index')->with('success', 'Accounts Updated Successfully');
        } catch (\Exception $e) {
            Log::error($e);
            return back()->withInput()->with('error', 'Account Updated Fail');
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

            return redirect()->route('admin.accounts.index')->with('success', 'Accounts Deleted Successfully');
        } catch (\Exception $e) {
            Log::error($e);
            return response()->json([
                'status' => 400,
                'message' => 'Sorry, Something went Wrong',
            ]);
        }
    }
}
