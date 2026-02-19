<?php

namespace App\Http\Controllers\Admin\Master;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\DataTables\Master\TaxRateDataTable;
use App\Http\Requests\Master\TaxRateFormRequest;
use App\Models\TaxRate;

class TaxRateController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(TaxRateDataTable $dataTable)
    {
        return $dataTable->render('pages.master.tax_rate.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('pages.master.tax_rate.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(TaxRateFormRequest $request)
    {
        // try {
        $tax_rates = new TaxRate();
        $tax_rates->tax_name = $request->tax_name;
        $tax_rates->tax_rate = $request->tax_rate;
        $tax_rates->status = $request->status;
        $tax_rates->save();

        if ($request->submission_type == 1) {
            return redirect()->route('admin.tax-rate.index')->with('success', 'Tax Rate Store Successfully');
        } elseif ($request->submission_type == 2) {
            return back()->with('success', 'Tax Rate Store Successfully');
        }
        // } catch (\Exception $e) {
        //     Log::error($e);
        //     return back()->withInput()->with('error', 'Tax Rate Stored Fail');
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

            $data['taxRate'] = TaxRate::findOrFail($id);
            return view('pages.master.tax_rate.view', $data);
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
        $data['tax_rate'] = TaxRate::findOrFail($id);
        return view('pages.master.tax_rate.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(TaxRateFormRequest $request, $id)
    {
        // try {
        $tax_rates = TaxRate::findOrFail($id);
        $tax_rates->tax_name = $request->tax_name;
        $tax_rates->tax_rate = $request->tax_rate;
        $tax_rates->status = $request->status;
        $tax_rates->save();

        return redirect()->route('admin.tax-rate.index')->with('success', 'Tax Rate Updated Successfully');
        // } catch (\Exception $e) {
        //     Log::error($e);
        //     return back()->withInput()->with('error', 'Tax Rate Updated Fail');
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
            TaxRate::findOrFail($id)->delete();

            return response()->json([
                'status' => 200,
                'message' => 'Tax Rate Deleted Successfully.'
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
