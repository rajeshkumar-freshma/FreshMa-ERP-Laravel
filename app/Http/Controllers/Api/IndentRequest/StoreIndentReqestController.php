<?php

namespace App\Http\Controllers\Api\IndentRequest;

use App\Core\CommonComponent;
use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductTransfer;
use App\Models\ProductTransferDetail;
use App\Models\Store;
use App\Models\StoreIndentRequest;
use App\Models\StoreIndentRequestAction;
use App\Models\StoreIndentRequestDetail;
use App\Models\StoreInventoryDetail;
use App\Models\StoreStockDailyUpdate;
use App\Models\StoreStockUpdate;
use App\Models\WarehouseInventoryDetail;
use App\Models\WarehouseStockUpdate;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class StoreIndentReqestController extends Controller
{
    public function storeindentrequestlist(Request $request)
    {
        // try {

        if ($request->store_id != null) {
            $store_id = array($request->store_id);
        } else {
            $store_id = Auth::user()->user_stores();
        }
        $purchase_ordered_count = StoreIndentRequest::whereIn('status', config('app.purchase_ordered_status'))->whereIn('store_id', $store_id)->count();
        $purchase_received_count = StoreIndentRequest::whereIn('status', config('app.purchase_received_status'))->whereIn('store_id', $store_id)->count();

        if ($request->warehouse_id != null) {
            $warehouse_id = array($request->warehouse_id);
        } else {
            $warehouse_id = Auth::user()->user_warehouse();
        }

        $request_code = $request->request_code;
        $status = $request->status;
        $from_date = $request->from_date;
        $to_date = $request->to_date;

        $indent_request_list = StoreIndentRequest::where(function ($query) use ($warehouse_id, $store_id, $request_code, $from_date, $to_date, $status) {
            if (count($warehouse_id) > 0) {
                $query->whereIn('warehouse_id', $warehouse_id);
            }
            if (count($store_id) > 0) {
                $query->whereIn('store_id', $store_id);
            }
            if ($status != null) {
                $query->where('status', $status);
            }
            if ($request_code != null) {
                $query->where('request_code', 'LIKE', '%' . $request_code . '%');
            }
            if ($from_date != null && $to_date != null) {
                $dateformatwithtime = CommonComponent::dateformatwithtime($from_date, $to_date);
                $query->whereBetween('request_date', $dateformatwithtime);
            }
        })
            ->with('store_data')
            ->with(['warehouse' => function ($query) {
                $query->select('id', 'name', 'code');
            }])
            ->with(['created_by_details' => function ($query) {
                $query->select('id', 'first_name', 'last_name', 'user_type', 'status');
            }])
            ->select('id', 'warehouse_id', 'store_id', 'request_code', 'status', 'request_date', 'expected_date', 'total_request_quantity', 'created_by')
            ->orderBy('id', 'DESC')
            ->paginate(15);

        return response()->json([
            'status' => 200,
            'purchase_ordered_count' => $purchase_ordered_count,
            'purchase_received_count' => $purchase_received_count,
            'indent_request_list' => $indent_request_list,
            'message' => 'Indent Request Fetched successfully.',
        ]);
        // } catch (\Exception $e) {
        //     Log::error($e);
        //     return response()->json([
        //         'status' => 400,
        //         'message' => 'Data not found.',
        //     ]);
        // }
    }

    public function storeindentrequestdetails(Request $request)
    {
        // try {
        $store_indent_request_id = $request->store_indent_request_id;
        $indentdetails = StoreIndentRequest::with('store_data')
            ->with(['warehouse' => function ($query) {
                $query->select('id', 'name', 'code');
            }])
            ->with(['created_by_details' => function ($query) {
                $query->select('id', 'first_name', 'last_name', 'user_type', 'status');
            }])
            ->findOrFail($store_indent_request_id);

        $indentproductdetails = StoreIndentRequestDetail::where('store_indent_request_id', $store_indent_request_id)->with('unit_details:id,unit_name,unit_short_code,allow_decimal,operator,operation_value')->get();

        return response()->json([
            'status' => 200,
            'datas' => $indentdetails,
            'productdetails' => $indentproductdetails,
            'message' => 'Indent Request fetched successfully.',
        ]);
        // } catch (\Exception $e) {
        //     Log::error($e);
        //     return response()->json([
        //         'status' => 400,
        //         'message' => 'Data not found.',
        //     ]);
        // }
    }

    public function storeindentrequeststore(Request $request)
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

        $store_data = Store::findOrfail($request->store_id);
        $indent_request = new StoreIndentRequest();
        $indent_request->warehouse_id = $store_data != null ? $store_data->warehouse_id : null;
        $indent_request->store_id = $request->store_id;
        $indent_request->request_code = $request->request_code;
        $indent_request->request_date = $request->request_date;
        $indent_request->expected_date = $request->expected_date;
        $indent_request->status = 1;
        $indent_request->total_request_quantity = $request->total_request_quantity;
        $indent_request->remarks = $request->remarks;
        if ($imageUrl != null) {
            $indent_request->file = $imageUrl;
            $indent_request->file_path = $imagePath;
        }
        $indent_request->save();

        if (isset($request->products)) {
            Log::info($request->products);
            $products = json_decode($request->products);
            if (count($products) > 0) {
                foreach ($products as $key => $product) {
                    $product_data = Product::findOrfail($product->product_id);
                    $indent_request_detail = new StoreIndentRequestDetail();
                    $indent_request_detail->store_indent_request_id = $indent_request->id;
                    $indent_request_detail->product_id = $product_data->id;
                    $indent_request_detail->sku_code = $product_data->sku_code;
                    $indent_request_detail->name = $product_data->name;
                    $indent_request_detail->unit_id = @$product->unit_id;
                    $indent_request_detail->request_quantity = @$product->quantity;
                    $indent_request_detail->given_quantity = @$product->quantity;
                    $indent_request_detail->remarks = @$product->remarks;
                    $indent_request_detail->added_by_requestor = 1;
                    $indent_request_detail->status = 1;
                    $indent_request_detail->save();

                    // $quantity = @$product->quantity;
                    // $warehouse_stock_detail_exists = WarehouseStockUpdate::where([['warehouse_id', $indent_request->warehouse_id], ['product_id', $product_data->id], ['status', 1]])->orderBy('id', 'DESC')->first();
                    // $warehouse_stock_detail = new WarehouseStockUpdate();
                    // $warehouse_stock_detail->warehouse_id = $indent_request->warehouse_id;
                    // $warehouse_stock_detail->product_id = $product_data->id;
                    // $warehouse_stock_detail->stock_update_on = Carbon::now();
                    // $warehouse_stock_detail->existing_stock = $warehouse_stock_detail_exists ? $warehouse_stock_detail_exists->total_stock : 0;
                    // $warehouse_stock_detail->adding_stock = 0;
                    // $warehouse_stock_detail->total_stock = ($warehouse_stock_detail_exists != null && $warehouse_stock_detail_exists->total_stock != null) ? $warehouse_stock_detail_exists->total_stock + $quantity : $quantity;
                    // $warehouse_stock_detail->status = 1;
                    // $warehouse_stock_detail->box_number = 1;
                    // $warehouse_stock_detail->save();

                    // $warehouse_inventory = WarehouseInventoryDetail::where([['warehouse_id', $indent_request->warehouse_id], ['product_id', $product_data->id], ['status', 1]])->first();
                    // if ($warehouse_inventory == null) {
                    //     $warehouse_inventory = new WarehouseInventoryDetail();
                    //     $warehouse_inventory->warehouse_id = $indent_request->warehouse_id;
                    //     $warehouse_inventory->product_id = $product_data->id;
                    // }
                    // $warehouse_inventory->weight = @$warehouse_inventory->weight - $quantity;
                    // $warehouse_inventory->status = 1;
                    // $warehouse_inventory->save();

                }
            }
        }

        $request_action = new StoreIndentRequestAction();
        $request_action->store_indent_request_id = $indent_request->id;
        $request_action->status = 1;
        $request_action->action_date = Carbon::now();
        $request_action->remarks = $request->remarks;
        $request_action->save();

        DB::commit();

        return response()->json([
            'status' => 200,
            'datas' => $indent_request,
            'message' => 'Data Stored successfully.',
        ]);
        // } catch (\Exception $e) {
        //     Log::error($e);
        //     DB::rollback();
        //     return response()->json([
        //         'status' => 400,
        //         'message' => 'There are some Technical Issue, Kindly contact Admin.',
        //     ]);
        // }
    }

    public function storeindentrequestupdate(Request $request)
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

        $store_data = Store::findOrfail($request->store_id);
        $store_indent_request_id = $request->store_indent_request_id;
        $indent_request = StoreIndentRequest::findOrFail($store_indent_request_id);
        $indent_request->warehouse_id = $store_data != null ? $store_data->warehouse_id : null;
        $indent_request->store_id = $request->store_id;
        $indent_request->request_code = $request->request_code;
        $indent_request->request_date = $request->request_date;
        $indent_request->expected_date = $request->expected_date;
        $indent_request->status = $request->status;
        $indent_request->remarks = $request->remarks;
        if ($imageUrl != null) {
            $indent_request->file = $imageUrl;
            $indent_request->file_path = $imagePath;
        }
        $indent_request->save();

        if (isset($request->deleted_ids) && count(json_decode($request->deleted_ids)) > 0) {
            StoreIndentRequestDetail::destroy($request->deleted_ids);
        }

        if (isset($request->products)) {
            $products = json_decode($request->products);
            if (count($products) > 0) {
                foreach ($products as $key => $product) {
                    $product_data = Product::findOrfail($product->product_id);
                    $old_weight = 0;
                    if (isset($product->id)) {
                        $indent_request_detail = StoreIndentRequestDetail::findOrFail($product->id);
                        $old_weight = $indent_request_detail->request_quantity;
                    } else {
                        $indent_request_detail = new StoreIndentRequestDetail();
                        $indent_request_detail->store_indent_request_id = $indent_request->id;
                        $indent_request_detail->added_by_requestor = 0;
                    }
                    $indent_request_detail->product_id = $product_data->id;
                    $indent_request_detail->sku_code = $product_data->sku_code;
                    $indent_request_detail->name = $product_data->name;
                    $indent_request_detail->unit_id = @$product->unit_id;
                    $indent_request_detail->request_quantity = @$product->quantity;
                    $indent_request_detail->given_quantity = @$product->quantity;
                    $indent_request_detail->remarks = @$product->remarks;
                    $indent_request_detail->save();

                    if ($request->stock_verified == 1 && $request->status == 10) {
                        $quantity = $product->quantity;
                        if ($request->store_id != null && $request->store_id != "null") {
                            if ($quantity != 0) {

                                //stock reduced from warehouse
                                $warehouse_stock_detail_exists = WarehouseStockUpdate::where([['warehouse_id', $indent_request->warehouse_id], ['product_id', $product_data->id], ['status', 1]])->orderBy('id', 'DESC')->first();
                                $warehouse_stock_detail = new WarehouseStockUpdate();
                                $warehouse_stock_detail->warehouse_id = $indent_request->warehouse_id;
                                $warehouse_stock_detail->product_id = $product_data->id;
                                $warehouse_stock_detail->stock_update_on = Carbon::now();
                                $warehouse_stock_detail->existing_stock = $warehouse_stock_detail_exists ? $warehouse_stock_detail_exists->total_stock : 0;
                                $warehouse_stock_detail->adding_stock = 0;
                                $warehouse_stock_detail->total_stock = ($warehouse_stock_detail_exists != null && $warehouse_stock_detail_exists->total_stock != null) ? $warehouse_stock_detail_exists->total_stock + $quantity : $quantity;
                                $warehouse_stock_detail->status = 1;
                                $warehouse_stock_detail->box_number = 1;
                                $warehouse_stock_detail->save();

                                $warehouse_inventory = WarehouseInventoryDetail::where([['warehouse_id', $indent_request->warehouse_id], ['product_id', $product_data->id], ['status', 1]])->first();
                                if ($warehouse_inventory == null) {
                                    $warehouse_inventory = new WarehouseInventoryDetail();
                                    $warehouse_inventory->warehouse_id = $indent_request->warehouse_id;
                                    $warehouse_inventory->product_id = $product_data->id;
                                }
                                $warehouse_inventory->weight = @$warehouse_inventory->weight - $quantity;
                                $warehouse_inventory->status = 1;
                                $warehouse_inventory->save();


                                // Check if there's an existing stock detail
                                $store_stock_detail_exists = StoreStockUpdate::where([
                                    ['store_id', $request->store_id],
                                    ['product_id', $product_data->id],
                                    ['status', 1]
                                ])->orderBy('id', 'DESC')->first();

                                // Create a new stock update record
                                $store_stock_detail = new StoreStockUpdate();
                                $store_stock_detail->from_warehouse_id = $request->warehouse_id;
                                $store_stock_detail->store_id = $request->store_id;
                                $store_stock_detail->product_id = $product_data->id;
                                $store_stock_detail->reference_id = $indent_request->id;
                                $store_stock_detail->reference_table = 9; //9 Store Indent Request table
                                $store_stock_detail->stock_update_on = Carbon::now();
                                $store_stock_detail->existing_stock = ($store_stock_detail_exists != null && $store_stock_detail_exists->total_stock != null) ? $store_stock_detail_exists->total_stock : 0;
                                $store_stock_detail->adding_stock = $quantity ?? 0; // Default to 0 if $quantity is not set
                                $store_stock_detail->total_stock = $store_stock_detail->existing_stock + $store_stock_detail->adding_stock;
                                $store_stock_detail->status = 1;
                                $store_stock_detail->save();

                                // Update daily stock record if exists
                                $data = StoreStockDailyUpdate::where('store_id', $store_stock_detail->store_id)
                                ->where('product_id', $store_stock_detail->product_id)
                                ->whereBetween('stock_update_on', [
                                    Carbon::parse($store_stock_detail->stock_update_on)->startOfDay(),
                                    Carbon::parse($store_stock_detail->stock_update_on)->endOfDay()
                                ])
                                ->orderBy('id', 'DESC')
                                ->first();

                                if ($data) {
                                    $data->update(['opening_stock' => $store_stock_detail->total_stock]);
                                }
                            }

                            $store_stock_detail = StoreStockUpdate::where([['store_id', $request->store_id], ['product_id', $product_data->id], ['status', 1]])->orderBy('id', 'DESC')->first();
                            $store_inventory = StoreInventoryDetail::where([['store_id', $request->store_id], ['product_id', $product_data->id], ['status', 1]])->first();
                            if ($store_inventory == null) {
                                $store_inventory = new StoreInventoryDetail();
                                $store_inventory->store_id = $request->store_id;
                                $store_inventory->product_id = $product_data->id;
                            }
                            $store_inventory->weight = @$store_inventory->weight + @$quantity;
                            $store_inventory->status = 1;
                            $store_inventory->save();
                            // return $store_inventory;
                        }
                    }
                }
            }
        }

        $request_action = new StoreIndentRequestAction();
        $request_action->store_indent_request_id = $indent_request->id;
        $request_action->status = $request->status;
        $request_action->action_date = Carbon::now();
        $request_action->remarks = $request->remarks;
        $request_action->save();

        DB::commit();

        if ($request->convert_to_distribution) {
            $distributiondata = $this->convertdistribution($indent_request);
        }
        return response()->json([
            'status' => 200,
            'datas' => $indent_request,
            'message' => 'Data Updated successfully.',
        ]);
        // } catch (\Exception $e) {
        //     Log::error($e);
        //     DB::rollback();
        //     return response()->json([
        //         'status' => 400,
        //         'message' => 'There are some Technical Issue, Kindly contact Admin.',
        //     ]);
        // }
    }

    public function convertdistribution($indent_request)
    {
        DB::beginTransaction();
        try {
            $product_transfer = new ProductTransfer();
            $transfer_order_number = CommonComponent::invoice_no('redistribution');
            $product_transfer->transfer_order_number = $transfer_order_number;
            $product_transfer->transfer_from = 1;
            $product_transfer->transfer_to = 2;
            $product_transfer->store_indent_request_id = $indent_request->id;
            if ($indent_request->warehouse_id != null && $indent_request->warehouse_id != "null") {
                $product_transfer->from_warehouse_id = $indent_request->warehouse_id;
            }
            if ($indent_request->store_id != null && $indent_request->store_id != "null") {
                $product_transfer->to_store_id = $indent_request->store_id;
            }
            $product_transfer->tap_id = Auth::user()->id; // transfer authorized person
            $product_transfer->transfer_created_date = Carbon::now();
            $product_transfer->status = 1;
            $product_transfer->remarks = "its converted from indent request.";
            $product_transfer->is_notification_send_to_admin = 1;
            $product_transfer->save();

            $product_transfer_id = $product_transfer->id;
            if (count($indent_request->store_indent_product_details) > 0) {
                $products = $indent_request->store_indent_product_details;
                if (count($products) > 0) {
                    foreach ($products as $key => $product) {
                        $indent_request_detail = new ProductTransferDetail();
                        $indent_request_detail->product_transfer_id = $product_transfer_id;
                        $indent_request_detail->product_id = $product->product_id;
                        $indent_request_detail->sku_code = $product->sku_code;
                        $indent_request_detail->name = $product->name;
                        $indent_request_detail->unit_id = @$product->unit_id;
                        $indent_request_detail->request_quantity = @$product->request_quantity;
                        $indent_request_detail->given_quantity = @$product->request_quantity;
                        $indent_request_detail->per_unit_price = 0;
                        $indent_request_detail->sub_total = 0;
                        $indent_request_detail->total = 0;
                        $indent_request_detail->status = 1;
                        $indent_request_detail->save();
                    }
                }
            }
            DB::commit();

            return response()->json([
                'status' => 200,
                'datas' => $indent_request,
                'message' => 'Data Updated successfully.',
            ]);
        } catch (\Exception $e) {
            Log::error($e);
            DB::rollback();
            return false;
        }
    }
}
