<?php

namespace App\Http\Controllers\Admin\Master;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\DataTables\Master\DenominationTypeDataTable;
use App\Http\Requests\Master\DenominationTypeFormRequest;
use App\Models\DenominationType;
use Illuminate\Support\Facades\Log;
use Haruncpi\LaravelIdGenerator\IdGenerator;

class DenominationTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index(DenominationTypeDataTable $dataTable)
    {
        return $dataTable->render('pages.master.denomination.index');
    }

    /**
     * Show the form for creating a new resource.
    *
    * @return \Illuminate\Http\Response
    */
    public function create()
    {
        return view('pages.master.denomination.create');
    }

    public function store(Request $request)
    {
        $denominationCode = 'DN' . str_pad(DenominationType::count() + 1, 5, '0', STR_PAD_LEFT);
        Log::info('Generated denomination code:', ['denomination_code' => $denominationCode]);

        DenominationType::create([
            'value' => $request->denomination_value,
            'denomination_code' => $denominationCode,
            'description' => $request->description,
        ]);

        return redirect()->route('admin.denomination-type.index')->with('success', 'Denomination Type Stored Successfully');
    }


/**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data['denomination'] = DenominationType::findOrFail($id);
        return view('pages.master.denomination.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        Log::info('Update request data:', $request->all());

        Log::info('Updating');

        $denominationType = DenominationType::findOrFail($id);
        $denominationCode = $denominationType->denomination_code;

        Log::info('Updating denomination code:', ['denomination_code' => $denominationCode]);

        // Update the record
        $denominationType->update([
            'value' => $request->denomination_value,
            'denomination_code' => $denominationCode,
            'description' => $request->description,
            'status' => $request->status,
        ]);

        return redirect()->route('admin.denomination-type.index')->with('success', 'Denomination Type Updated Successfully');
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
            DenominationType::findOrFail($id)->delete();

            return response()->json([
                'status' => 200,
                'message' => 'Denomination Type Deleted Successfully.'
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
