<?php

namespace App\Http\Controllers\Admin\HRM;

use App\DataTables\HRM\HolidayDataTable;
use App\Http\Controllers\Controller;
use App\Http\Requests\HRM\HolidayFormRequest;
use App\Models\Holiday;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class HolidayController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(HolidayDataTable $dataTable)
    {
        return $dataTable->render('pages.hrm.holiday.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('pages.hrm.holiday.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(HolidayFormRequest $request)
    {
        // try {
        $holiday = new Holiday();
        $holiday->name = $request->name;
        $holiday->date = $request->date;
        $holiday->holiday_type = $request->holiday_type;
        $holiday->status = $request->status;
        $holiday->save();

        if ($request->submission_type == 1) {
            return redirect()->route('admin.holiday.index')->with('success', 'Holiday Store Successfully');
        } elseif ($request->submission_type == 2) {
            return back()->with('success', 'Holiday Store Successfully');
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
        $data['holiday'] = Holiday::findOrFail($id);
        return view('pages.hrm.holiday.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(HolidayFormRequest $request, $id)
    {
        // try {
        $holiday = Holiday::findOrFail($id);
        $holiday->name = $request->name;
        $holiday->date = $request->date;
        $holiday->holiday_type = $request->holiday_type;
        $holiday->status = $request->status;
        $holiday->save();

        return redirect()->route('admin.holiday.index')->with('success', 'Holiday Updated Successfully');
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
            Holiday::findOrFail($id)->delete();

            return redirect()->route('admin.holiday.index')->with('success', 'Holiday Deleted Successfully');
        } catch (\Exception $e) {
            Log::error($e);
            return response()->json([
                'status' => 400,
                'message' => 'Sorry, Something went Wrong',
            ]);
        }
    }
}
