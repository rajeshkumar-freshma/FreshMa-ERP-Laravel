<?php

namespace App\Http\Controllers\Admin\HRM;

use App\DataTables\HRM\DepartmentDataTable;
use App\Http\Controllers\Controller;
use App\Http\Requests\HRM\DepartmentFormRequest;
use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class DepartmentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(DepartmentDataTable $dataTable)
    {
        return $dataTable->render('pages.hrm.department.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('pages.hrm.department.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(DepartmentFormRequest $request)
    {
        try {
            $department = new Department();
            $department->name = $request->name;
            $department->description = $request->description;
            $department->status = $request->status;
            $department->save();

            if ($request->submission_type == 1) {
                return redirect()->route('admin.department.index')->with('success', 'Department Store Successfully');
            } elseif ($request->submission_type == 2) {
                return back()->with('success', 'Department Store Successfully');
            }
        } catch (\Exception $e) {
            Log::error($e);
            return back()->withInput()->with('error', 'Income/Expense Type Stored Fail');
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
        $data['department'] = Department::findOrFail($id);
        return view('pages.hrm.department.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(DepartmentFormRequest $request, $id)
    {
        try {
            $department = Department::findOrFail($id);
            $department->name = $request->name;
            $department->description = $request->description;
            $department->status = $request->status;
            $department->save();

            return redirect()->route('admin.department.index')->with('success', 'Department Updated Successfully');
        } catch (\Exception $e) {
            Log::error($e);
            return back()->withInput()->with('error', 'department Updated Fail');
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
            Department::findOrFail($id)->delete();

            return redirect()->route('admin.department.index')->with('success', 'Department Deleted Successfully');
        } catch (\Exception $e) {
            Log::error($e);
            return response()->json([
                'status' => 400,
                'message' => 'Sorry, Something went Wrong',
            ]);
        }
    }
}
