<?php

namespace App\Http\Controllers\Admin\Master;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\DataTables\Master\IncomeExpenseTypeDataTable;
use App\Http\Requests\Master\IncomeExpenseTypeFormRequest;
use App\Models\IncomeExpenseType;

class IncomeExpenseTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(IncomeExpenseTypeDataTable $dataTable)
    {
        return $dataTable->render('pages.master.income_expense_type.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('pages.master.income_expense_type.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(IncomeExpenseTypeFormRequest $request)
    {
        // try {
        $income_expense_type = new IncomeExpenseType();
        $income_expense_type->name = $request->name;
        $income_expense_type->type = $request->type;
        $income_expense_type->status = $request->status;
        $income_expense_type->save();

        if ($request->submission_type == 1) {
            return redirect()->route('admin.income-expense-type.index')->with('success', 'Income/Expense Type Store Successfully');
        } elseif ($request->submission_type == 2) {
            return back()->with('success', 'Income/Expense Type Store Successfully');
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

            $data['incomeExpenseType'] = IncomeExpenseType::findOrFail($id);
            return view('pages.master.income_expense_type.view', $data);
        } catch (\Exception $e) {
            Log::error($e);
            return response()->json([
                'status' => 400,
                'message' => 'Sorry, Something went Wrong'
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
        $data['income_expense'] = IncomeExpenseType::findOrFail($id);
        return view('pages.master.income_expense_type.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(IncomeExpenseTypeFormRequest $request, $id)
    {
        // try {
        $income_expense_type = IncomeExpenseType::findOrFail($id);
        $income_expense_type->name = $request->name;
        $income_expense_type->type = $request->type;
        $income_expense_type->status = $request->status;
        $income_expense_type->save();

        return redirect()->route('admin.income-expense-type.index')->with('success', 'Income/Expense Type Updated Successfully');
        // } catch (\Exception $e) {
        //     Log::error($e);
        //     return back()->withInput()->with('error', 'Income/Expense Type Updated Fail');
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
            IncomeExpenseType::findOrFail($id)->delete();

            return response()->json([
                'status' => 200,
                'message' => 'Income/Expense Type Deleted Successfully.'
            ]);
        } catch (\Exception $e) {
            Log::error($e);
            return response()->json([
                'status' => 400,
                'message' => 'Sorry, Something went Wrong'
            ]);
        }
    }
}
