<?php

namespace App\Http\Controllers\Admin\Master;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\DataTables\Master\PartnershipTypeDataTable;
use App\Http\Requests\Master\PartnershipTypeFormRequest;
use App\Models\PartnershipType;

class PartnershipTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(PartnershipTypeDataTable $dataTable)
    {
        return $dataTable->render('pages.master.partnership_type.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('pages.master.partnership_type.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(PartnershipTypeFormRequest $request)
    {
        // try {
        $partnership_type = new PartnershipType();
        $partnership_type->partnership_name = $request->partnership_name;
        $partnership_type->partnership_percentage = $request->partnership_percentage;
        $partnership_type->status = $request->status;
        $partnership_type->save();

        if ($request->submission_type == 1) {
            return redirect()->route('admin.partnership-type.index')->with('success', 'Partnership Type Store Successfully');
        } elseif ($request->submission_type == 2) {
            return back()->with('success', 'Partnership Type Store Successfully');
        }
        // } catch (\Exception $e) {
        //     Log::error($e);
        //     return back()->withInput()->with('error', 'Partnership Type Stored Fail');
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

            $data['partnershiptypes'] = PartnershipType::findOrFail($id);
            return view('pages.master.partnership_type.view', $data);
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
        $data['partnership_type'] = PartnershipType::findOrfail($id);
        return view('pages.master.partnership_type.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(PartnershipTypeFormRequest $request, $id)
    {
        // try {
        $partnership_type = PartnershipType::findOrfail($id);
        $partnership_type->partnership_name = $request->partnership_name;
        $partnership_type->partnership_percentage = $request->partnership_percentage;
        $partnership_type->status = $request->status;
        $partnership_type->save();

        if ($request->submission_type == 1) {
            return redirect()->route('admin.partnership-type.index')->with('success', 'Partnership Type Store Successfully');
        } elseif ($request->submission_type == 2) {
            return back()->with('success', 'Partnership Type Store Successfully');
        }
        // } catch (\Exception $e) {
        //     Log::error($e);
        //     return back()->withInput()->with('error', 'Partnership Type Stored Fail');
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
            PartnershipType::findOrFail($id)->delete();

            return response()->json([
                'status' => 200,
                'message' => 'Partnership Type Deleted Successfully.'
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
