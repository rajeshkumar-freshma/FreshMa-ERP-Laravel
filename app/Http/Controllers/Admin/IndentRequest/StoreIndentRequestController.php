<?php

namespace App\Http\Controllers\Admin\IndentRequest;

use App\Core\CommonComponent;
use App\DataTables\IndentRequest\StoreIndentRequestDataTable;
use App\Http\Controllers\Controller;
use App\Http\Requests\IndentRequest\StoreIndentFormRequest;
use App\Models\Product;
use App\Models\Store;
use App\Models\StoreIndentRequest;
use App\Models\StoreIndentRequestAction;
use App\Models\StoreIndentRequestDetail;
use App\Models\StoreInventoryDetail;
use App\Models\StoreStockUpdate;
use App\Models\Unit;
use App\Models\Warehouse;
use App\Models\WarehouseInventoryDetail;
use App\Models\WarehouseStockUpdate;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class StoreIndentRequestController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(StoreIndentRequestDataTable $dataTable)
    {
        return $dataTable->render('pages.indent_request.store.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data['stores'] = Store::select('id', 'store_name', 'store_code')->active()->get();
        $data['warehouses'] = Warehouse::select('id', 'name', 'code')->active()->get();
        $data['units'] = Unit::active()->get();
        return view('pages.indent_request.store.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreIndentFormRequest $request)
    {
        DB::beginTransaction();
        // try {
        $imagePath = null;
        $imageUrl = null;
        if ($request->hasFile('file')) {
            $imageData = CommonComponent::s3BucketFileUpload($request->file, 'store_indent');
            $imagePath = $imageData['filePath'];
            // $imageUrl = $imageData['fileName'];
            $imageUrl = $imageData['imageURL'];
        }

        $store_indent = new StoreIndentRequest();
        $store_indent->warehouse_id = $request->warehouse_id;
        $store_indent->store_id = $request->store_id;
        $store_indent->request_code = $request->ir_code;
        $store_indent->request_date = $request->request_date;
        $store_indent->expected_date = $request->expected_date;
        $store_indent->stock_transferred = $request->stock_transferred;
        $store_indent->status = 1;
        $store_indent->total_request_quantity = $request->total_request_quantity;
        $store_indent->remarks = $request->remarks;
        if ($imageUrl != null) {
            $store_indent->file = $imageUrl;
            $store_indent->file_path = $imagePath;
        }
        $store_indent->save();

        $products = $request->products;
        if (!is_null($products) && is_array($products) && count($products) > 0) {
            foreach ($products['product_id'] as $key => $product) {
                $product_data = Product::findOrfail($products['product_id'][$key]);
                $indent_request_detail = new StoreIndentRequestDetail();
                $indent_request_detail->store_indent_request_id = $store_indent->id;
                $indent_request_detail->product_id = $product_data->id;
                $indent_request_detail->sku_code = $product_data->sku_code;
                $indent_request_detail->name = $product_data->name;
                $indent_request_detail->unit_id = $products['unit_id'][$key];
                $indent_request_detail->request_quantity = $products['quantity'][$key];
                $indent_request_detail->added_by_requestor = 1;
                $indent_request_detail->save();


                // if ($request->stock_transferred == 1) {
                    // $warehouse_stock_detail_exists = WarehouseStockUpdate::where([['warehouse_id', $store_indent->warehouse_id], ['product_id', $product_data->id], ['status', 1]])->orderBy('id', 'DESC')->first();
                    // $warehouse_stock_detail = new WarehouseStockUpdate();
                    // $warehouse_stock_detail->warehouse_id = $store_indent->warehouse_id;
                    // $warehouse_stock_detail->product_id = $product_data->id;
                    // $warehouse_stock_detail->stock_update_on = Carbon::now();
                    // $warehouse_stock_detail->existing_stock = $warehouse_stock_detail_exists ? $warehouse_stock_detail_exists->total_stock : 0;
                    // $warehouse_stock_detail->adding_stock = 0;
                    // $warehouse_stock_detail->total_stock = ($warehouse_stock_detail_exists != null && $warehouse_stock_detail_exists->total_stock != null) ? $warehouse_stock_detail_exists->total_stock + $products['quantity'][$key] : $products['quantity'][$key];
                    // $warehouse_stock_detail->status = 1;
                    // $warehouse_stock_detail->box_number = 1;
                    // $warehouse_stock_detail->save();

                    // $warehouse_inventory = WarehouseInventoryDetail::where([['warehouse_id', $store_indent->warehouse_id], ['product_id', $product_data->id], ['status', 1]])->first();
                    // if ($warehouse_inventory == null) {
                    //     $warehouse_inventory = new WarehouseInventoryDetail();
                    //     $warehouse_inventory->warehouse_id = $store_indent->warehouse_id;
                    //     $warehouse_inventory->product_id = $product_data->id;
                    // }
                    // $warehouse_inventory->weight = @$warehouse_inventory->weight - $products['quantity'][$key];
                    // $warehouse_inventory->status = 1;
                    // $warehouse_inventory->save();

                // }
            }
        }

        $request_action = new StoreIndentRequestAction();
        $request_action->store_indent_request_id = $store_indent->id;
        $request_action->status = 1;
        $request_action->action_date = Carbon::now();
        $request_action->remarks = $request->remarks;
        $request_action->save();

        DB::commit();

        if ($request->submission_type == 1) {
            return redirect()->route('admin.store-indent-request.index')->with('success', 'Indent Request Stored Successfully');
        } elseif ($request->submission_type == 2) {
            return back()->with('success', 'Indent Request Stored Successfully');
        }
        // } catch (\Exception $e) {
        //     Log::error($e);
        //     DB::rollback();
        //     return back()->withInput()->with('error', 'Indent Request Stored Fail');
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
        $data['stores'] = Store::select('id', 'store_name', 'store_code')->active()->get();
        $data['warehouses'] = Warehouse::select('id', 'name', 'code')->active()->get();
        $data['units'] = Unit::active()->get();
        $data['store_indent_request'] = StoreIndentRequest::findOrfail($id);
        return view('pages.indent_request.store.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(StoreIndentFormRequest $request, $id)
    {
        DB::beginTransaction();
        // try {
        $imagePath = null;
        $imageUrl = null;

        $store_indent = StoreIndentRequest::findOrfail($id);
        if ($request->hasFile('file')) {
            $fileDeleted = CommonComponent::s3BucketFileDelete($store_indent->file, $store_indent->file_path);

            $imageData = CommonComponent::s3BucketFileUpload($request->file, 'store_indent');
            $imagePath = $imageData['filePath'];
            // $imageUrl = $imageData['fileName'];
            $imageUrl = $imageData['imageURL'];
        }

        $store_indent->warehouse_id = $request->warehouse_id;
        $store_indent->store_id = $request->store_id;
        $store_indent->request_code = $request->ir_code;
        $store_indent->request_date = $request->request_date;
        $store_indent->expected_date = $request->expected_date;
        $store_indent->stock_transferred = $request->stock_transferred;
        if ($request->status != null) {
            $store_indent->status = $request->status;
        }
        $store_indent->total_request_quantity = $request->total_request_quantity;
        $store_indent->remarks = $request->remarks;
        if ($imageUrl != null) {
            $store_indent->file = $imageUrl;
            $store_indent->file_path = $imagePath;
        }
        $store_indent->save();

        $request_old_ids = [];
        if (isset($request->products['product_id']) && is_array($request->products['product_id'])) {
            foreach ($request->products['product_id'] as $store_key => $value) {
                if (isset($request->products['id'][$store_key]) && $request->products['id'][$store_key] != null) {
                    $request_old_ids[] = $request->products['id'][$store_key];
                }
            }
        }

        $exists_indent_product = StoreIndentRequestDetail::where('store_indent_request_id', $id)->get();
        if (count($exists_indent_product) > 0) {
            foreach ($exists_indent_product as $exists_key => $value) {
                if (!in_array($value->id, $request_old_ids)) {
                    StoreIndentRequestDetail::where('id', $value->id)->delete();
                }
            }
        }

        $products = $request->products;
        if (!is_null($products) && is_array($products) && count($products) > 0) {
            if (isset($products['product_id'])) {
                foreach ($products['product_id'] as $key => $productId) {
                    $product_data = Product::findOrfail($products['product_id'][$key]);
                    if (isset($products['id'][$key])) {
                        if (in_array($products['id'][$key], $exists_indent_product->pluck('id')->toArray())) {
                            $indent_request_detail = StoreIndentRequestDetail::findOrFail($products['id'][$key]);
                        }
                        // else {
                        //     $indent_request_detail = new StoreIndentRequestDetail();
                        //     $indent_request_detail->store_indent_request_id = $store_indent->id;
                        // }
                    } else {
                        $indent_request_detail = new StoreIndentRequestDetail();
                        $indent_request_detail->store_indent_request_id = $store_indent->id;
                    }
                    $indent_request_detail->product_id = $product_data->id;
                    $indent_request_detail->sku_code = $product_data->sku_code;
                    $indent_request_detail->name = $product_data->name;
                    $indent_request_detail->unit_id = $products['unit_id'][$key];
                    $indent_request_detail->request_quantity = $products['quantity'][$key];
                    $indent_request_detail->added_by_requestor = 1;
                    $indent_request_detail->save();

                    // if ($request->stock_transferred && $request->status == 10) {

                    //     $warehouse_stock_detail_exists = WarehouseStockUpdate::where([['warehouse_id', $store_indent->warehouse_id], ['product_id', $product_data->id], ['status', 1]])->orderBy('id', 'DESC')->first();
                    //     $warehouse_stock_detail = new WarehouseStockUpdate();
                    //     $warehouse_stock_detail->warehouse_id = $store_indent->warehouse_id;
                    //     $warehouse_stock_detail->product_id = $product_data->id;
                    //     $warehouse_stock_detail->stock_update_on = Carbon::now();
                    //     $warehouse_stock_detail->existing_stock = $warehouse_stock_detail_exists ? $warehouse_stock_detail_exists->total_stock : 0;
                    //     $warehouse_stock_detail->adding_stock = 0;
                    //     $warehouse_stock_detail->total_stock = ($warehouse_stock_detail_exists != null && $warehouse_stock_detail_exists->total_stock != null) ? $warehouse_stock_detail_exists->total_stock + $products['quantity'][$key] : $products['quantity'][$key];
                    //     $warehouse_stock_detail->status = 1;
                    //     $warehouse_stock_detail->box_number = 1;
                    //     $warehouse_stock_detail->save();

                    //     $warehouse_inventory = WarehouseInventoryDetail::where([['warehouse_id', $store_indent->warehouse_id], ['product_id', $product_data->id], ['status', 1]])->first();
                    //     if ($warehouse_inventory == null) {
                    //         $warehouse_inventory = new WarehouseInventoryDetail();
                    //         $warehouse_inventory->warehouse_id = $store_indent->warehouse_id;
                    //         $warehouse_inventory->product_id = $product_data->id;
                    //     }
                    //     $warehouse_inventory->weight = @$warehouse_inventory->weight - $products['quantity'][$key];
                    //     $warehouse_inventory->status = 1;
                    //     $warehouse_inventory->save();


                    //     $store_stock_detail = StoreStockUpdate::where([['store_id', $request->store_id], ['product_id', $product_data->id], ['status', 1]])->first();
                    //     if ($store_stock_detail == null) {
                    //         $store_stock_detail = new StoreStockUpdate();
                    //         $store_stock_detail->from_warehouse_id = $request->warehouse_id;
                    //         $store_stock_detail->store_id = $request->store_id;
                    //         $store_stock_detail->product_id = $product_data->id;
                    //     }
                    //     $store_stock_detail->reference_id = $store_indent->id;
                    //     $store_stock_detail->reference_table = 9; //9 Store Indent Request
                    //     $store_stock_detail->stock_update_on = Carbon::now();
                    //     $store_stock_detail->existing_stock = $store_stock_detail->total_stock != null ? $store_stock_detail->total_stock : 0;
                    //     $store_stock_detail->adding_stock = @$products['quantity'][$key];
                    //     $store_stock_detail->total_stock = $store_stock_detail->total_stock != null ? $store_stock_detail->total_stock+@$products['quantity'][$key] : @$products['quantity'][$key];
                    //     $store_stock_detail->status = 1;
                    //     $store_stock_detail->save();

                    //     $store_inventory = StoreInventoryDetail::where([['store_id', $request->store_id], ['product_id', $product_data->id], ['status', 1]])->first();
                    //     if ($store_inventory == null) {
                    //         $store_inventory = new StoreInventoryDetail();
                    //         $store_inventory->store_id = $request->store_id;
                    //         $store_inventory->product_id = $product_data->id;
                    //     }
                    //     $store_inventory->weight = @$store_inventory->weight + $store_stock_detail->adding_stock;
                    //     $store_inventory->status = 1;
                    //     $store_inventory->save();
                    // }
                }
            }
        }

        $request_action = new StoreIndentRequestAction();
        $request_action->store_indent_request_id = $store_indent->id;
        $request_action->status = $request->status != null ? $request->status : $store_indent->status;
        $request_action->action_date = Carbon::now();
        $request_action->remarks = $request->remarks;
        $request_action->save();

        DB::commit();

        if ($request->submission_type == 1) {
            return redirect()->route('admin.store-indent-request.index')->with('success', 'Indent Request Updated Successfully');
        } elseif ($request->submission_type == 2) {
            return back()->with('success', 'Indent Request Updated Successfully');
        }
        // } catch (\Exception $e) {
        //     Log::error($e);
        //     DB::rollback();
        //     return back()->withInput()->with('error', 'Indent Request Updated Fail');
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
            StoreIndentRequestDetail::where('store_indent_request_id', $id)->delete();
            $indent_request = StoreIndentRequest::findOrFail($id);

            $indent_request->delete();
            return response()->json([
                'status' => 200,
                'message' => 'Indent request Deleted Successfully.',
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
