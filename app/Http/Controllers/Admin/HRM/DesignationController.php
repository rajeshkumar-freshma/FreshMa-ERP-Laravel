<?php

namespace App\Http\Controllers\Admin\HRM;

use App\DataTables\HRM\DesignationDataTable;
use App\Http\Controllers\Controller;
use App\Http\Requests\HRM\DesignationFormRequest;
use App\Models\Designation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class DesignationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(DesignationDataTable $dataTable)
    {
        return $dataTable->render('pages.hrm.designation.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('pages.hrm.designation.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(DesignationFormRequest $request)
    {
        try {
            $designation = new Designation();
            $designation->name = $request->name;
            $designation->description = $request->description;
            $designation->status = $request->status;
            $designation->save();

            if ($request->submission_type == 1) {
                return redirect()->route('admin.designation.index')->with('success', 'designation Store Successfully');
            } elseif ($request->submission_type == 2) {
                return back()->with('success', 'designation Store Successfully');
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
        $data['designation'] = Designation::findOrFail($id);
        return view('pages.hrm.designation.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(DesignationFormRequest $request, $id)
    {
        try {
            $designation = Designation::findOrFail($id);
            $designation->name = $request->name;
            $designation->description = $request->description;
            $designation->status = $request->status;
            $designation->save();

            return redirect()->route('admin.designation.index')->with('success', 'designation Updated Successfully');
        } catch (\Exception $e) {
            Log::error($e);
            return back()->withInput()->with('error', 'designation Updated Fail');
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
            Designation::findOrFail($id)->delete();

            return redirect()->route('admin.designation.index')->with('success', 'designation Deleted Successfully');
        } catch (\Exception $e) {
            Log::error($e);
            return response()->json([
                'status' => 400,
                'message' => 'Sorry, Something went Wrong',
            ]);
        }
    }
}
