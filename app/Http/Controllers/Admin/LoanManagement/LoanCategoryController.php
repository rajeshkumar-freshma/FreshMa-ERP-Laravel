<?php

namespace App\Http\Controllers\Admin\LoanManagement;

use App\DataTables\LoanManagement\LoanCategoryDataTable;
use App\Http\Controllers\Controller;
use App\Http\Requests\LoanManagement\LoanCategoryFormRequest;
use App\Models\LoanCategory;
use App\Models\LoanCharge;
use Illuminate\Http\Request;
use Log;

class LoanCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(LoanCategoryDataTable $dataTable)
    {
        return $dataTable->render('pages.loan_management.loan_category.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data['loan_charge'] = LoanCharge::where('status', 1)->get();
        return view('pages.loan_management.loan_category.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(LoanCategoryFormRequest $request)
    {
        // try {
        // return $request->amount;
        $loanCategory = new LoanCategory();
        $loanCategory->name = $request->name;
        $loanCategory->short_name = $request->short_name;
        $loanCategory->amount = $request->amount;
        $loanCategory->loan_term = $request->loan_term;
        $loanCategory->loan_tenure = $request->loan_tenure;
        $loanCategory->loan_term_method = $request->loan_term_method;
        $loanCategory->interest_rate = $request->interest_rate;
        $loanCategory->interest_type = $request->interest_type;
        $loanCategory->interest_frequency = $request->interest_frequency;
        $loanCategory->repayment_frequency = $request->repayment_frequency;
        $loanCategory->late_payment_penalty_rate = $request->late_payment_penalty_rate;

        // Assuming $request->charges is an array of charge IDs
        $loanCategory->charges = (is_array($request->charges) && count($request->charges) > 0) ? $request->charges[0] : '';

        // Associate charges

        $loanCategory->status = $request->status;
        $loanCategory->description = $request->description;

        // Save the LoanCategory instance
        $loanCategory->save();

        if ($request->submission_type == 1) {
            return redirect()->route('admin.loan-categories.index')->with('success', 'Loan Category Successfully');
        } elseif ($request->submission_type == 2) {
            return back()->with('success', 'Loan Category Successfully');
        }
        // } catch (\Exception $e) {
        //     // Log the error
        //     Log::error($e);

        //     // Redirect back with input and error message
        //     return back()->withInput()->with('error', 'Loan Category Creation Failed');
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
            $data['loan_category'] = LoanCategory::findOrFail($id);
            return view('pages.loan_management.loan_category.view', $data);
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
        // $data['loan_category'] = LoanCategory::findOrFail($id);
        $data['loan_category'] = LoanCategory::findOrFail($id);
        // $chargeIds = $data['loan_category']->charges;

        // if (!empty($chargeIds)) {
        //     $data['loan_category']->load(['loan_charges' => function ($query) use ($chargeIds) {
        //         $query->whereIn('id', $chargeIds);
        //     }]);
        // }

        $data['loan_charge'] = LoanCharge::where('status', 1)->get();
        return view('pages.loan_management.loan_category.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(LoanCategoryFormRequest $request, $id)
    {
        // try {
        // return $request->amount;
        $loanCategory = LoanCategory::updateOrCreate(
            [
                'id' => $id,
            ],
            [
                'name ' => $request->name,
                'short_name ' => $request->short_name,
                'amount ' => $request->amount,
                'loan_term ' => $request->loan_term,
                'loan_tenure ' => $request->loan_tenure,
                'loan_term_method ' => $request->loan_term_method,
                'interest_rate ' => $request->interest_rate,
                'interest_type ' => $request->interest_type,
                'interest_frequency ' => $request->interest_frequency,
                'repayment_frequency ' => $request->repayment_frequency,
                'late_payment_penalty_rate ' => $request->late_payment_penalty_rate,
                // Assuming $request->charges is an array of charge IDs
                // Assuming $request->charges is an array of charge IDs
                'charges ' => (is_array($request->charges) && count($request->charges) > 0) ? $request->charges[0] : '',
                'status ' => $request->status,
                'description ' => $request->description,
                // Save the LoanCategory instance

            ]
        );

        return redirect()->route('admin.loan-categories.index')->with('success', 'Loan Category Updated Successfully');
        // } catch (\Exception $e) {
        //     Log::error($e);
        //     return back()->withInput()->with('error', 'Loan Category Updated Fail');
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
            LoanCategory::findOrFail($id)->delete();

            return response()->json([
                'status' => 200,
                'message' => 'Loan Category Deleted Successfully.',
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
