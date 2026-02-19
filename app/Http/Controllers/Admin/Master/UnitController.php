<?php

namespace App\Http\Controllers\Admin\Master;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\DataTables\Master\UnitDataTable;
use App\Http\Requests\Master\UnitFormRequest;
use App\Models\Unit;
use Illuminate\Support\Facades\DB;

class UnitController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(UnitDataTable $dataTable)
    {
        return $dataTable->render('pages.master.unit.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('pages.master.unit.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(UnitFormRequest $request)
    {
        try {

        DB::beginTransaction();
        if ($request->default == 1) {
            Unit::where('default', 1)->update(['default' => 0]);
        }

        $unit = new Unit();
        $unit->unit_name = $request->unit_name;
        $unit->unit_short_code = $request->unit_short_code;
        $unit->base_unit = $request->base_unit;
        $unit->allow_decimal = $request->allow_decimal;
        $unit->operator = $request->operator;
        $unit->operation_value = $request->operation_value;
        $unit->status = $request->status;
        $unit->default = $request->default ;
        $unit->save();

        DB::commit();
        // Unit::where('id', $request->id)->update(['default' => $request->default]);

        if ($request->submission_type == 1) {
            return redirect()->route('admin.unit.index')->with('success', 'Unit Store Successfully');
        } elseif ($request->submission_type == 2) {
            return back()->with('success', 'Unit Store Successfully');
        }

        } catch (\Exception $e) {
            DB::rollback();
            Log::error($e);
            return back()->withInput()->with('error', 'Unit Stored Fail');
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

            $data['unit'] = Unit::findOrFail($id);
            return view('pages.master.unit.view', $data);
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
        $data['unit'] = Unit::findOrFail($id);
        return view('pages.master.unit.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UnitFormRequest $request, $id)
    {
        try {
            DB::beginTransaction();
            if ($request->default == 1) {
                Unit::where('default', 1)->update(['default' => 0]);
            }
            $unit = Unit::findOrFail($id);
            $unit->unit_name = $request->unit_name;
            $unit->unit_short_code = $request->unit_short_code;
            $unit->base_unit = $request->base_unit;
            $unit->allow_decimal = $request->allow_decimal;
            $unit->operator = $request->operator;
            $unit->operation_value = $request->operation_value;
            $unit->status = $request->status ? $request->status : 0;
            $unit->default = $request->default;
            $unit->save();

            $defaults = Unit::where('default', 1)->get();
            if(count($defaults) == 0){
                $unit->default = 1;
                $unit->save();
            }

            DB::commit();
            return redirect()->route('admin.unit.index')->with('success', 'Unit Updated Successfully');
        } catch (\Exception $e) {
            DB::rollback();
            Log::error($e);
            return back()->withInput()->with('error', 'Unit Updated Fail');
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
            Unit::findOrFail($id)->delete();

            return response()->json([
                'status' => 200,
                'message' => 'Unit Deleted Successfully.'
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
