<?php

namespace App\Http\Controllers\Admin\LoanManagement;

use App\DataTables\LoanManagement\LoanChargeDataTable;
use App\Http\Controllers\Controller;
use App\Http\Requests\LoanManagement\LoanChargeFormRequest;
use App\Models\LoanCharge;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class LoanChargeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(LoanChargeDataTable $dataTable)
    {
        return $dataTable->render('pages.loan_management.loan_charge.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('pages.loan_management.loan_charge.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(LoanChargeFormRequest $request)
    {
        try {
            $loacharge = new LoanCharge([
                'name' => $request->name,
                'short_name' => $request->short_name,
                'amount' => $request->amount,
                'status' => $request->status,
            ]);

            $loacharge->save();

            if ($request->submission_type == 1) {
                return redirect()->route('admin.loan-charges.index')->with('success', 'Repayment Successfully');
            } elseif ($request->submission_type == 2) {
                return back()->with('success', 'Repayment Successfully');
            }

        } catch (\Exception $e) {
            // Log the error
            Log::error($e);

            // Redirect back with input and error message
            return back()->withInput()->with('error', 'Repayment Creation Failed');
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
            $data['loan_charges'] = LoanCharge::findOrFail($id);
            return view('pages.loan_management.loan_charge.view', $data);
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
        $data['loanCharges'] = LoanCharge::FindOrFail($id);
        return view('pages.loan_management.loan_charge.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(LoanChargeFormRequest $request, $id)
    {
        try {
            $loanRepayment = LoanCharge::findOrFail($id);
            $loanRepayment->update([
                'name' => $request->name,
                'short_name' => $request->short_name,
                'amount' => $request->amount,
                'status' => $request->status,
            ]);

            return redirect()->route('admin.loan-charges.index')->with('success', 'Loan Updated Successfully');
        } catch (\Exception $e) {
            Log::error($e);
            return back()->withInput()->with('error', 'LoanCharge Updated Failed');
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
            LoanCharge::findOrFail($id)->delete();

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
}
