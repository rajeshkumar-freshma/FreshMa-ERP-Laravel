<?php

namespace App\Http\Controllers\Admin\Product;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\DataTables\Product\StockManagementDataTable;
use App\Models\WarehouseStockUpdate;
use App\Models\WarehouseInventoryDetail;
use App\Models\WarehouseStockUpdateHistory;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Log;

class StockManagementController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(StockManagementDataTable $dataTable)
    {
        return $dataTable->render('pages.product.stock_management.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
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
        $data['warehouse_stock_update'] = WarehouseStockUpdate::findOrfail($id);
        return view('pages.product.stock_management.edit', $data);
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
        DB::beginTransaction();
        // try {

        $validator = Validator::make($request->all(), [
            'box_number' => 'required|integer',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator);
        }
        $warehouse_stock_update = WarehouseStockUpdate::findOrfail($id);

        $history = new WarehouseStockUpdateHistory();
        $history->stock_update_history_id = $warehouse_stock_update->id;
        $history->warehouse_id = $warehouse_stock_update->warehouse_id;
        $history->product_id = $warehouse_stock_update->product_id;
        $history->old_stock = $warehouse_stock_update->total_stock;
        $history->new_stock = $warehouse_stock_update->total_stock;
        $history->old_box_number = $warehouse_stock_update->box_number;
        $history->new_box_number = $request->box_number;
        $history->status = $warehouse_stock_update->status;
        $history->save();

        $warehouse_stock_update->box_number = $request->box_number;
        $warehouse_stock_update->status = $request->status;
        $warehouse_stock_update->save();

        DB::commit();

        if ($request->submission_type == 1) {
            return redirect()
                ->route('admin.stock-management.index')
                ->with('success', 'Stock Updated Successfully');
        } elseif ($request->submission_type == 2) {
            return back()->with('success', 'Stock Updated Successfully');
        }
        // } catch (\Exception $e) {
        //     Log::error($e);
        //     DB::rollback();
        //     return back()->withInput()->with('error', 'Stock Updated Fail');
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
        //
    }
}
