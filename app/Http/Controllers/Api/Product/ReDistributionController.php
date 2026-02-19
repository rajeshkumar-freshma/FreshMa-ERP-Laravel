<?php

namespace App\Http\Controllers\Api\Product;

use App\Core\CommonComponent;
use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductTransfer;
use App\Models\ProductTransferDetail;
use App\Models\ProductTransferExpense;
use App\Models\PurchaseSalesDocument;
use App\Models\StoreIndentRequest;
use App\Models\StoreInventoryDetail;
use App\Models\StoreStockUpdate;
use App\Models\TransportTracking;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ReDistributionController extends Controller
{
    public function redistributionlist(Request $request)
    {
        // try {
        $transfer_order_number = $request->transfer_order_number;

        $from_date = $request->from_date;
        $to_date = $request->to_date;
        $status = $request->status;

        if ($request->warehouse_id != null) {
            $warehouse_id = array($request->warehouse_id);
        } else {
            $warehouse_id = Auth::user()->user_warehouse();
        }

        if ($request->store_id != null) {
            $store_id = array($request->store_id);
        } else {
            $store_id = Auth::user()->user_stores();
        }

        $product_transfer_query = ProductTransfer::where(function ($query) use ($warehouse_id, $store_id) {
            if (count($store_id) > 0) {
                $query->whereIn('from_store_id', $store_id)->OrWhereIn('to_store_id', $store_id);
            }
            if (count($warehouse_id) > 0) {
                $query->whereIn('from_warehouse_id', $warehouse_id)->OrWhereIn('to_warehouse_id', $warehouse_id);
            }
        })
            ->where(function ($query) use ($transfer_order_number, $from_date, $to_date, $status) {
                if ($transfer_order_number != null) {
                    $query->where('transfer_order_number', 'LIKE', '%' . $transfer_order_number . '%');
                }
                if ($status != null) {
                    $query->where('status', $status);
                }
                if ($from_date != null && $to_date != null) {
                    $dateformatwithtime = CommonComponent::dateformatwithtime($from_date, $to_date);
                    $query->whereBetween('transfer_created_date', $dateformatwithtime);
                }
            })
            ->with('from_warehouse:id,name,code', 'from_store:id,store_name,store_code,phone_number,gst_number', 'to_warehouse:id,name,code', 'to_store:id,store_name,store_code,phone_number,gst_number')
            ->orderBy('id', 'DESC');

        $transfercount = $product_transfer_query->count();
        $transfer_amount = $product_transfer_query->sum('total_amount');

        $product_transfer = $product_transfer_query->paginate(15);

        return response()->json([
            'status' => 200,
            'transfercount' => $transfercount,
            'transfer_amount' => $transfer_amount,
            'datas' => $product_transfer,
            'message' => 'Product Transfer fetched successfully.',
        ]);
        // } catch (\Exception $e) {
        //     Log::error($e);
        //     return response()->json([
        //         'status' => 400,
        //         'message' => 'Data not found.',
        //     ]);
        // }
    }

    public function redistributiondetails(Request $request)
    {
        // try {
        $product_transfer_id = $request->product_transfer_id;
        $product_transfer = ProductTransfer::with('from_warehouse:id,name,code', 'from_store:id,store_name,store_code,phone_number,gst_number', 'to_warehouse:id,name,code', 'to_store:id,store_name,store_code,phone_number,gst_number')->findOrFail($product_transfer_id);

        $transfer_product_details = ProductTransferDetail::where('product_transfer_id', $product_transfer_id)->with('unit_details:id,unit_name,unit_short_code,allow_decimal,operator,operation_value')->get();

        $product_transfer_expense = ProductTransferExpense::where('product_transfer_id', $product_transfer_id)->with('income_expense_details')->get();
        $product_transfer_expense_docs = PurchaseSalesDocument::where([['type', 4], ['reference_id', $product_transfer_id], ['document_type', 1]])->get();
        $product_transfer_trackings = TransportTracking::where('product_transfer_id', $product_transfer_id)->with('transport_type_details')->get();
        $product_transfer_trackings_docs = PurchaseSalesDocument::where([['type', 4], ['reference_id', $product_transfer_id], ['document_type', 2]])->get();

        return response()->json([
            'status' => 200,
            'datas' => $product_transfer,
            'transfer_product_details' => $transfer_product_details,
            'product_transfer_expense' => $product_transfer_expense,
            'product_transfer_expense_docs' => $product_transfer_expense_docs,
            'product_transfer_trackings' => $product_transfer_trackings,
            'product_transfer_trackings_docs' => $product_transfer_trackings_docs,
            'message' => 'Re-distribution Details fetched successfully.',
        ]);
        // } catch (\Exception $e) {
        //     Log::error($e);
        //     return response()->json([
        //         'status' => 400,
        //         'message' => 'Data not found.',
        //     ]);
        // }
    }

    public function redistributionindentrequestlist(Request $request)
    {
        $request_code = $request->request_code;
        $indent_request_list = StoreIndentRequest::where(function ($query) use ($request_code) {
            if ($request_code != null) {
                $query->where('request_code', 'LIKE', '%' . $request_code . '%');
            }
            $query->where('status', 1);
            $query->whereBetween('request_date', [Carbon::now()->subDays(5)->format('Y-m-d 00:00:00'), Carbon::now()->format('Y-m-d 23:59:59')]);
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
            ->get();

        return response()->json([
            'status' => 200,
            'indent_request_list' => $indent_request_list,
            'message' => 'Indent Request Fetched successfully.',
        ]);
    }

    public function redistributionstore(Request $request)
    {
        DB::beginTransaction();
        // try {
        $product_transfer = new ProductTransfer();
        $product_transfer->transfer_order_number = $request->transfer_order_number;
        $product_transfer->transfer_from = $request->transfer_from;
        $product_transfer->transfer_to = $request->transfer_to;
        $product_transfer->store_indent_request_id = $request->store_indent_request_id;

        if ($request->from_warehouse_id != null && $request->from_warehouse_id != "null") {
            $product_transfer->from_warehouse_id = $request->from_warehouse_id;
        }
        if ($request->from_store_id != null && $request->from_store_id != "null") {
            $product_transfer->from_store_id = $request->from_store_id;
        }
        if ($request->to_warehouse_id != null && $request->to_warehouse_id != "null") {
            $product_transfer->to_warehouse_id = $request->to_warehouse_id;
        }
        if ($request->to_store_id != null && $request->to_store_id != "null") {
            $product_transfer->to_store_id = $request->to_store_id;
        }
        $product_transfer->tap_id = Auth::user()->id; // transfer authorized person
        $product_transfer->transfer_created_date = $request->transfer_created_date;
        $product_transfer->transfer_received_date = $request->transfer_created_date;
        $product_transfer->status = 1;
        $product_transfer->remarks = $request->remarks;
        $product_transfer->sub_total = $request->sub_total;
        $product_transfer->total_amount = $request->total_amount;
        $product_transfer->is_notification_send_to_admin = $request->is_notification_send_to_admin;
        $product_transfer->save();

        $product_transfer_id = $product_transfer->id;
        if (isset($request->products)) {
            $products = json_decode($request->products);
            if (count($products) > 0) {
                foreach ($products as $key => $product) {
                    $product_data = Product::findOrfail($product->product_id);
                    $indent_request_detail = new ProductTransferDetail();
                    $indent_request_detail->product_transfer_id = $product_transfer_id;
                    $indent_request_detail->product_id = $product_data->id;
                    $indent_request_detail->sku_code = $product_data->sku_code;
                    $indent_request_detail->name = $product_data->name;
                    $indent_request_detail->unit_id = @$product->unit_id;
                    $indent_request_detail->request_quantity = @$product->quantity;
                    $indent_request_detail->given_quantity = @$product->quantity;
                    $indent_request_detail->per_unit_price = $product->amount;
                    $indent_request_detail->sub_total = @$product->sub_total;
                    $indent_request_detail->total = @$product->total != null ? @$product->total : @$product->sub_total;
                    $indent_request_detail->save();

                    $quantity = -$product->quantity;
                    // if ($request->from_warehouse_id != null && $request->from_warehouse_id != "null") {
                    //     if ($quantity != 0) {
                    //         $warehouse_stock_detail_exists = WarehouseStockUpdate::where([['warehouse_id', $request->from_warehouse_id], ['product_id', $product_data->id], ['status', 1]])->orderBy('id', 'DESC')->first();
                    //         $warehouse_stock_detail = new WarehouseStockUpdate();
                    //         $warehouse_stock_detail->warehouse_id = $request->from_warehouse_id;
                    //         $warehouse_stock_detail->product_id = $product_data->id;
                    //         $warehouse_stock_detail->stock_update_on = Carbon::now();
                    //         $warehouse_stock_detail->existing_stock = ($warehouse_stock_detail_exists != null && $warehouse_stock_detail_exists->total_stock != null) ? $warehouse_stock_detail_exists->total_stock : 0;
                    //         $warehouse_stock_detail->adding_stock = @$quantity;
                    //         $warehouse_stock_detail->total_stock = ($warehouse_stock_detail_exists != null && $warehouse_stock_detail_exists->total_stock != null) ? $warehouse_stock_detail_exists->total_stock+@$quantity : @$quantity;
                    //         $warehouse_stock_detail->status = 1;
                    //         $warehouse_stock_detail->box_number = $request->box_number != null ? $request->box_number : 1;
                    //         $warehouse_stock_detail->save();
                    //     }

                    //     $warehouse_inventory = WarehouseInventoryDetail::where([['warehouse_id', $request->from_warehouse_id], ['product_id', $product_data->id], ['status', 1]])->first();
                    //     if ($warehouse_inventory == null) {
                    //         $warehouse_inventory = new WarehouseInventoryDetail();
                    //         $warehouse_inventory->warehouse_id = $request->from_warehouse_id;
                    //         $warehouse_inventory->product_id = $product_data->id;
                    //     }
                    //     $warehouse_inventory->weight = @$warehouse_inventory->weight+@$quantity;
                    //     $warehouse_inventory->status = 1;
                    //     $warehouse_inventory->save();
                    // }

                    if ($request->from_store_id != null && $request->from_store_id != "null") {
                        if ($product->quantity != 0) {
                            $store_stock_detail_exists = StoreStockUpdate::where([['store_id', $request->from_store_id], ['product_id', $product_data->id], ['status', 1]])->orderBy('id', 'DESC')->first();
                            $store_stock_detail = new StoreStockUpdate();
                            $store_stock_detail->from_warehouse_id = 1;
                            $store_stock_detail->store_id = $request->from_store_id;
                            $store_stock_detail->reference_id = $product_transfer->id;
                            $store_stock_detail->reference_table = 7; //7 Re distributions table
                            $store_stock_detail->product_id = $product_data->id;
                            $store_stock_detail->stock_update_on = Carbon::now();
                            $store_stock_detail->existing_stock = ($store_stock_detail_exists != null && $store_stock_detail_exists->total_stock != null) ? $store_stock_detail_exists->total_stock : 0;
                            $store_stock_detail->adding_stock = @$quantity;
                            $store_stock_detail->total_stock = ($store_stock_detail_exists != null && $store_stock_detail_exists->total_stock != null) ? $store_stock_detail_exists->total_stock+@$quantity : @$quantity;
                            $store_stock_detail->status = 1;
                            $store_stock_detail->save();
                        }

                        $store_stock_detail = StoreStockUpdate::where([['store_id', $request->from_store_id], ['product_id', $product_data->id], ['status', 1]])->orderBy('id', 'DESC')->first();
                        $store_inventory = StoreInventoryDetail::where([['store_id', $request->from_store_id], ['product_id', $product_data->id], ['status', 1]])->first();
                        if ($store_inventory == null) {
                            $store_inventory = new StoreInventoryDetail();
                            $store_inventory->store_id = $request->from_store_id;
                            $store_inventory->product_id = $product_data->id;
                        }
                        $store_inventory->weight = @$store_inventory->weight+@$quantity;
                        $store_inventory->status = 1;
                        $store_inventory->save();
                    }
                }
            }
        }

        // Expense Docs Delete
        if (isset($request->deleted_expense_doc_ids) && count(json_decode($request->deleted_expense_doc_ids)) > 0) {
            foreach (json_decode($request->deleted_expense_doc_ids) as $key => $value) {
                if ($value) {
                    $product_transfer_docs = PurchaseSalesDocument::findOrFail($value);
                    CommonComponent::s3BucketFileDelete($product_transfer_docs->file, $product_transfer_docs->file_path);

                    $product_transfer_docs->delete();
                }
            }
        }

        // Expense Docs Store
        if (isset($request->expense_documents) && count($request->expense_documents) > 0 && $request->file('expense_documents')) {
            foreach ($request->file('expense_documents') as $key => $value) {
                if ($value) {
                    $imageData = CommonComponent::s3BucketFileUpload($value, 'product_transfer_expense_docs');
                    $imagePath = $imageData['filePath'];
                    // $imageUrl = $imageData['fileName'];
                    $imageUrl = $imageData['imageURL'];

                    $product_transfer_docs = new PurchaseSalesDocument();
                    $product_transfer_docs->type = 4; // re distribution (Product transfer)
                    $product_transfer_docs->reference_id = $product_transfer_id;
                    $product_transfer_docs->document_type = 1; // Expense
                    $product_transfer_docs->file = @$imageUrl;
                    $product_transfer_docs->file_path = @$imagePath;
                    $product_transfer_docs->save();
                }
            }
        }

        // Expense Data Delete
        if (isset($request->deleted_expense_ids) && count(json_decode($request->deleted_expense_ids)) > 0) {
            ProductTransferExpense::destroy(json_decode($request->deleted_expense_ids));
        }

        // Expense Data Store
        if (isset($request->expense)) {
            $expense = json_decode($request->expense);
            if (count($expense) > 0) {
                foreach ($expense as $expense_key => $exp) {
                    if (isset($exp->id)) {
                        $product_transfer_expense = ProductTransferExpense::where("id", $exp->id)->first();
                    } else {
                        $product_transfer_expense = new ProductTransferExpense();
                    }
                    $product_transfer_expense->product_transfer_id = $product_transfer_id;
                    $product_transfer_expense->income_expense_id = @$exp->expense_type_id;
                    $product_transfer_expense->expense_amount = @$exp->expense_amount;
                    $product_transfer_expense->is_billable = @$exp->is_billable;
                    $product_transfer_expense->save();
                }
            }
        }

        DB::commit();

        return response()->json([
            'status' => 200,
            'datas' => $product_transfer,
            'message' => 'Product Transfer Added successfully.',
        ]);
        // } catch (\Exception $e) {
        //     Log::error($e);
        //     DB::rollback();
        //     return response()->json([
        //         'status' => 400,
        //         'message' => 'Data Store Fail.',
        //     ]);
        // }
    }

    public function redistributionupdate(Request $request)
    {
        DB::beginTransaction();
        // try {
        $product_transfer_id = $request->product_transfer_id;
        $product_transfer = ProductTransfer::findorFail($product_transfer_id);
        $product_transfer->transfer_from = $request->transfer_from;
        $product_transfer->transfer_to = $request->transfer_to;
        if ($request->from_warehouse_id != null && $request->from_warehouse_id != "null") {
            $product_transfer->from_warehouse_id = $request->from_warehouse_id;
        }
        if ($request->from_store_id != null && $request->from_store_id != "null") {
            $product_transfer->from_store_id = $request->from_store_id;
        }
        if ($request->to_warehouse_id != null && $request->to_warehouse_id != "null") {
            $product_transfer->to_warehouse_id = $request->to_warehouse_id;
        }
        if ($request->to_store_id != null && $request->to_store_id != "null") {
            $product_transfer->to_store_id = $request->to_store_id;
        }
        $product_transfer->tap_id = Auth::user()->id;
        $product_transfer->is_inc_exp_billable_for_all = $request->is_inc_exp_billable_for_all;
        $product_transfer->transfer_created_date = $request->transfer_created_date;
        $product_transfer->transfer_received_date = $request->transfer_created_date;
        $product_transfer->status = $request->status;
        $product_transfer->remarks = $request->remarks;
        $product_transfer->sub_total = $request->sub_total;
        $product_transfer->total_amount = $request->total_amount;
        $product_transfer->is_notification_send_to_admin = $request->is_notification_send_to_admin;
        $product_transfer->save();

        // Expense Docs Delete
        if (isset($request->deleted_ids) && count(json_decode($request->deleted_ids)) > 0) {
            ProductTransferDetail::destroy(json_decode($request->deleted_ids));
        }

        if (isset($request->products)) {
            $products = json_decode($request->products);
            if (count($products) > 0) {
                foreach ($products as $key => $product) {
                    $product_data = Product::findOrfail($product->product_id);
                    $old_weight = 0;
                    if (isset($product->id)) {
                        $indent_request_detail = ProductTransferDetail::findOrFail($product->id);
                        $old_weight = $indent_request_detail->given_quantity;
                    } else {
                        $indent_request_detail = new ProductTransferDetail();
                    }

                    $indent_request_detail->product_transfer_id = $product_transfer_id;
                    $indent_request_detail->product_id = $product_data->id;
                    $indent_request_detail->sku_code = $product_data->sku_code;
                    $indent_request_detail->name = $product_data->name;
                    $indent_request_detail->unit_id = @$product->unit_id;
                    $indent_request_detail->given_quantity = @$product->quantity;
                    $indent_request_detail->per_unit_price = $product->amount;
                    $indent_request_detail->sub_total = @$product->sub_total;
                    $indent_request_detail->total = @$product->total != null ? @$product->total : @$product->sub_total;
                    $indent_request_detail->save();

                    if ($request->from_store_id != null && $request->from_store_id != "null") {

                        $quantity = -($indent_request_detail->given_quantity == $old_weight ? 0 : $product->quantity - $old_weight);

                        if ($product->quantity != 0) {
                            $store_stock_detail_exists = StoreStockUpdate::where([['store_id', $request->from_store_id], ['product_id', $product_data->id], ['status', 1]])->orderBy('id', 'DESC')->first();
                            $store_stock_detail = new StoreStockUpdate();
                            $store_stock_detail->from_warehouse_id = 1;
                            $store_stock_detail->store_id = $request->from_store_id;
                            $store_stock_detail->reference_id = $product_transfer->id;
                            $store_stock_detail->reference_table = 7; //7 Re distributions table
                            $store_stock_detail->product_id = $product_data->id;
                            $store_stock_detail->stock_update_on = Carbon::now();
                            $store_stock_detail->existing_stock = ($store_stock_detail_exists != null && $store_stock_detail_exists->total_stock != null) ? $store_stock_detail_exists->total_stock : 0;
                            $store_stock_detail->adding_stock = @$quantity;
                            $store_stock_detail->total_stock = ($store_stock_detail_exists != null && $store_stock_detail_exists->total_stock != null) ? $store_stock_detail_exists->total_stock+@$quantity : @$quantity;
                            $store_stock_detail->status = 1;
                            $store_stock_detail->save();
                        }

                        $store_stock_detail = StoreStockUpdate::where([['store_id', $request->from_store_id], ['product_id', $product_data->id], ['status', 1]])->orderBy('id', 'DESC')->first();
                        $store_inventory = StoreInventoryDetail::where([['store_id', $request->from_store_id], ['product_id', $product_data->id], ['status', 1]])->first();
                        if ($store_inventory == null) {
                            $store_inventory = new StoreInventoryDetail();
                            $store_inventory->store_id = $request->from_store_id;
                            $store_inventory->product_id = $product_data->id;
                        }
                        $store_inventory->weight = @$store_inventory->weight+@$quantity;
                        $store_inventory->status = 1;
                        $store_inventory->save();
                    }

                    if ($request->stock_verified == 1) {
                        $product_transfer->is_verified_by_admin = 1;
                        $product_transfer->save();

                        if ($indent_request_detail->given_quantity == $old_weight) {
                            $to_quantity = $indent_request_detail->given_quantity;
                        } else if ($indent_request_detail->given_quantity > $old_weight) {
                            $to_quantity = ($indent_request_detail->given_quantity - $old_weight);
                            $to_quantity += ($indent_request_detail->given_quantity + $to_quantity);
                        } else {
                            $to_quantity = $old_weight - $indent_request_detail->given_quantity;
                        }
                        // $quantity = $product->quantity == $old_weight ? $product->quantity : $product->quantity - $old_weight;
                        // if ($request->to_warehouse_id != null && $request->to_warehouse_id != "null") {
                        //     if ($quantity != 0) {
                        //         $warehouse_stock_detail_exists = WarehouseStockUpdate::where([['warehouse_id', $request->to_warehouse_id], ['product_id', $product_data->id], ['status', 1]])->orderBy('id', 'DESC')->first();
                        //         $warehouse_stock_detail = new WarehouseStockUpdate();
                        //         $warehouse_stock_detail->warehouse_id = $request->to_warehouse_id;
                        //         $warehouse_stock_detail->product_id = $product_data->id;
                        //         $warehouse_stock_detail->stock_update_on = Carbon::now();
                        //         $warehouse_stock_detail->existing_stock = ($warehouse_stock_detail_exists != null && $warehouse_stock_detail_exists->total_stock != null) ? $warehouse_stock_detail_exists->total_stock : 0;
                        //         $warehouse_stock_detail->adding_stock = @$quantity;
                        //         $warehouse_stock_detail->total_stock = ($warehouse_stock_detail_exists != null && $warehouse_stock_detail_exists->total_stock != null) ? $warehouse_stock_detail_exists->total_stock + @$quantity : @$quantity;
                        //         $warehouse_stock_detail->status = 1;
                        //         $warehouse_stock_detail->box_number = $request->box_number != null ? $request->box_number : 1;
                        //         $warehouse_stock_detail->save();
                        //     }

                        //     $warehouse_inventory = WarehouseInventoryDetail::where([['warehouse_id', $request->to_warehouse_id], ['product_id', $product_data->id], ['status', 1]])->orderBy('id', 'DESC')->first();
                        //     if ($warehouse_inventory == null) {
                        //         $warehouse_inventory = new WarehouseInventoryDetail();
                        //         $warehouse_inventory->warehouse_id = $request->to_warehouse_id;
                        //         $warehouse_inventory->product_id = $product_data->id;
                        //     }
                        //     $warehouse_inventory->weight = @$warehouse_inventory->weight + @$quantity;
                        //     $warehouse_inventory->status = 1;
                        //     $warehouse_inventory->save();
                        // }

                        if ($request->to_store_id != null && $request->to_store_id != "null") {
                            if ($to_quantity != 0) {
                                $store_stock_detail_exists = StoreStockUpdate::where([['store_id', $request->to_store_id], ['product_id', $product_data->id], ['status', 1]])->orderBy('id', 'DESC')->first();
                                $store_stock_detail = new StoreStockUpdate();
                                $store_stock_detail->from_warehouse_id = 1;
                                $store_stock_detail->store_id = $request->to_store_id;
                                $store_stock_detail->reference_id = $product_transfer->id;
                                $store_stock_detail->reference_table = 7; //7 Re distributions table
                                $store_stock_detail->product_id = $product_data->id;
                                $store_stock_detail->stock_update_on = Carbon::now();
                                $store_stock_detail->existing_stock = ($store_stock_detail_exists != null && $store_stock_detail_exists->total_stock != null) ? $store_stock_detail_exists->total_stock : 0;
                                $store_stock_detail->adding_stock = @$to_quantity;
                                $store_stock_detail->total_stock = ($store_stock_detail_exists != null && $store_stock_detail_exists->total_stock != null) ? $store_stock_detail_exists->total_stock+@$to_quantity : @$to_quantity;
                                $store_stock_detail->status = 1;
                                $store_stock_detail->save();
                            }
                            $store_stock_detail = StoreStockUpdate::where([['store_id', $request->to_store_id], ['product_id', $product_data->id], ['status', 1]])->orderBy('id', 'DESC')->first();
                            $store_inventory = StoreInventoryDetail::where([['store_id', $request->to_store_id], ['product_id', $product_data->id], ['status', 1]])->first();
                            if ($store_inventory == null) {
                                $store_inventory = new StoreInventoryDetail();
                                $store_inventory->store_id = $request->to_store_id;
                                $store_inventory->product_id = $product_data->id;
                            }

                            $store_inventory->weight = @$store_inventory->weight+@$to_quantity;
                            $store_inventory->status = 1;
                            $store_inventory->save();
                        }
                    }
                }
            }
        }

        // Expense Docs Delete
        if (isset($request->deleted_expense_doc_ids) && count(json_decode($request->deleted_expense_doc_ids)) > 0) {
            foreach (json_decode($request->deleted_expense_doc_ids) as $key => $value) {
                if ($value) {
                    $product_transfer_docs = PurchaseSalesDocument::findOrFail($value);
                    CommonComponent::s3BucketFileDelete($product_transfer_docs->file, $product_transfer_docs->file_path);

                    $product_transfer_docs->delete();
                }
            }
        }

        // Expense Docs Store
        if (isset($request->expense_documents) && count($request->expense_documents) > 0 && $request->file('expense_documents')) {
            foreach ($request->file('expense_documents') as $key => $value) {
                if ($value) {
                    $imageData = CommonComponent::s3BucketFileUpload($value, 'product_transfer_expense_docs');
                    $imagePath = $imageData['filePath'];
                    // $imageUrl = $imageData['fileName'];
                    $imageUrl = $imageData['imageURL'];

                    $product_transfer_docs = new PurchaseSalesDocument();
                    $product_transfer_docs->type = 4; // re distribution (Product transfer)
                    $product_transfer_docs->reference_id = $product_transfer_id;
                    $product_transfer_docs->document_type = 1; // Expense
                    $product_transfer_docs->file = @$imageUrl;
                    $product_transfer_docs->file_path = @$imagePath;
                    $product_transfer_docs->save();
                }
            }
        }

        // Expense Data Delete
        if (isset($request->deleted_expense_ids) && count(json_decode($request->deleted_expense_ids)) > 0) {
            ProductTransferExpense::destroy(json_decode($request->deleted_expense_ids));
        }

        // Expense Data Store
        if (isset($request->expense)) {
            $expense = json_decode($request->expense);
            if (count($expense) > 0) {
                foreach ($expense as $expense_key => $exp) {
                    if (isset($exp->id)) {
                        $product_transfer_expense = ProductTransferExpense::where("id", $exp->id)->first();
                    } else {
                        $product_transfer_expense = new ProductTransferExpense();
                    }
                    $product_transfer_expense->product_transfer_id = $product_transfer_id;
                    $product_transfer_expense->income_expense_id = @$exp->expense_type_id;
                    $product_transfer_expense->expense_amount = @$exp->expense_amount;
                    $product_transfer_expense->is_billable = @$exp->is_billable;
                    $product_transfer_expense->save();
                }
            }
        }

        DB::commit();

        return response()->json([
            'status' => 200,
            'datas' => $product_transfer,
            'message' => 'Product Transfer Updated successfully.',
        ]);
        // } catch (\Exception $e) {
        //     Log::error($e);
        //     DB::rollback();
        //     return response()->json([
        //         'status' => 400,
        //         'message' => 'Data Store Fail.',
        //     ]);
        // }
    }

    public function producttransferexpenseupdate(Request $request)
    {
        DB::beginTransaction();
        // try {
        $product_transfer_id = $request->product_transfer_id;
        $total_remove_exp_amount = ProductTransferExpense::where("product_transfer_id", $product_transfer_id)->where('is_billable', 1)->sum('ie_amount');
        $total_unbill_amount = ProductTransferExpense::where("product_transfer_id", $product_transfer_id)->where('is_billable', 0)->sum('ie_amount');

        // Expense Docs Delete
        if (isset($request->deleted_expense_doc_ids) && count(json_decode($request->deleted_expense_doc_ids)) > 0) {
            foreach (json_decode($request->deleted_expense_doc_ids) as $key => $value) {
                if ($value) {
                    $product_transfer_docs = PurchaseSalesDocument::findOrFail($value);
                    CommonComponent::s3BucketFileDelete($product_transfer_docs->file, $product_transfer_docs->file_path);

                    $product_transfer_docs->delete();
                }
            }
        }

        // Expense Docs Store
        if (isset($request->expense_documents) && count($request->expense_documents) > 0 && $request->file('expense_documents')) {
            foreach ($request->file('expense_documents') as $key => $value) {
                if ($value) {
                    $imageData = CommonComponent::s3BucketFileUpload($value, 'product_transfer_expense_docs');
                    $imagePath = $imageData['filePath'];
                    // $imageUrl = $imageData['fileName'];
                    $imageUrl = $imageData['imageURL'];

                    $product_transfer_docs = new PurchaseSalesDocument();
                    $product_transfer_docs->type = 4; // re distribution (Product transfer)
                    $product_transfer_docs->reference_id = $product_transfer_id;
                    $product_transfer_docs->document_type = 1; // Expense
                    $product_transfer_docs->file = @$imageUrl;
                    $product_transfer_docs->file_path = @$imagePath;
                    $product_transfer_docs->save();
                }
            }
        }

        // Expense Data Delete
        if (isset($request->deleted_expense_ids) && count(json_decode($request->deleted_expense_ids)) > 0) {
            ProductTransferExpense::destroy(json_decode($request->deleted_expense_ids));
        }

        // Expense Data Store
        if (isset($request->expense)) {
            $total_expense_amount = 0;
            $total_expense_billable_amount = 0;

            $expense = json_decode($request->expense);
            if (count($expense) > 0) {
                foreach ($expense as $expense_key => $exp) {
                    if (isset($exp->id)) {
                        $product_transfer_expense = ProductTransferExpense::where("id", $exp->id)->first();
                    } else {
                        $product_transfer_expense = new ProductTransferExpense();
                    }
                    $product_transfer_expense->product_transfer_id = $product_transfer_id;
                    $product_transfer_expense->income_expense_id = @$exp->expense_type_id;
                    $product_transfer_expense->ie_amount = @$exp->expense_amount;
                    $product_transfer_expense->is_billable = @$exp->is_billable;
                    if ($exp->is_billable == 1) {
                        $total_expense_billable_amount += $exp->expense_amount;
                    }
                    $total_expense_amount += $exp->expense_amount;
                    $product_transfer_expense->save();
                }

                $product_transfer = ProductTransfer::findOrFail($product_transfer_id);
                $product_transfer->total_expense_billable_amount = $total_expense_billable_amount;
                $product_transfer->total_expense_amount = $total_expense_amount;
                $product_transfer->total_amount = ($product_transfer->total_amount - $total_remove_exp_amount) + $total_expense_billable_amount;
                $product_transfer->save();
            }
        } else {
            $product_transfer = ProductTransfer::findOrFail($product_transfer_id);
            $product_transfer->total_expense_billable_amount = 0;
            $product_transfer->total_expense_amount = 0;
            $product_transfer->total_amount = ($product_transfer->total_amount - $total_remove_exp_amount - $total_unbill_amount);
            $product_transfer->save();
        }
        DB::commit();

        return response()->json([
            'status' => 200,
            'message' => 'Expense Updated successfully.',
        ]);
        // } catch (\Exception $e) {
        //     Log::error($e);
        //     DB::rollback();
        //     return response()->json([
        //         'status' => 400,
        //         'message' => 'Data Store Fail.',
        //     ]);
        // }
    }

    public function producttransfertransporttrackingupdate(Request $request)
    {
        DB::beginTransaction();
        // try {
        $product_transfer_id = $request->product_transfer_id;
        // Transaport Tracking Docs Delete
        if (isset($request->deleted_tracking_doc_ids) && count(json_decode($request->deleted_tracking_doc_ids)) > 0) {
            foreach (json_decode($request->deleted_tracking_doc_ids) as $key => $value) {
                if ($value) {
                    $product_transfer_docs = PurchaseSalesDocument::findOrFail($value);
                    CommonComponent::s3BucketFileDelete($product_transfer_docs->file, $product_transfer_docs->file_path);

                    $product_transfer_docs->delete();
                }
            }
        }

        // Transaport Tracking Docs Store
        if (isset($request->transport_tracking_files) && count($request->transport_tracking_files) > 0 && $request->file('transport_tracking_files')) {
            foreach ($request->file('transport_tracking_files') as $key => $value) {
                if ($value) {
                    $imagePath = null;
                    $imageUrl = null;
                    $imageData = CommonComponent::s3BucketFileUpload($value, 'product_transfer_tracking_docs');
                    $imagePath = $imageData['filePath'];
                    // $imageUrl = $imageData['fileName'];
                    $imageUrl = $imageData['imageURL'];

                    $product_transfer_docs = new PurchaseSalesDocument();
                    $product_transfer_docs->type = 4; // re distribution (Product transfer)
                    $product_transfer_docs->reference_id = $product_transfer_id;
                    $product_transfer_docs->document_type = 2; // Transport Tracking
                    $product_transfer_docs->file = @$imageUrl;
                    $product_transfer_docs->file_path = @$imagePath;
                    $product_transfer_docs->save();
                }
            }
        }

        // Transaport Tracking Data Delete
        if (isset($request->deleted_tracking_ids) && count(json_decode($request->deleted_tracking_ids)) > 0) {
            TransportTracking::destroy(json_decode($request->deleted_tracking_ids));
        }

        // Transaport Tracking Data Store
        if (isset($request->transport_tracking_details)) {
            $transport_tracking_details = json_decode($request->transport_tracking_details);
            if (count($transport_tracking_details) > 0) {
                foreach ($transport_tracking_details as $transport_key => $transport_tracking_detail) {
                    if (isset($transport_tracking_detail->id)) {
                        $transport_tracking = TransportTracking::findOrFail($transport_tracking_detail->id);
                    } else {
                        $transport_tracking = new TransportTracking();
                    }
                    $transport_tracking->product_transfer_id = $product_transfer_id;
                    $transport_tracking->transport_type_id = $transport_tracking_detail->transport_type_id;
                    $transport_tracking->transport_name = $transport_tracking_detail->transport_name;
                    $transport_tracking->transport_number = $transport_tracking_detail->transport_number;
                    $transport_tracking->departure_datetime = $transport_tracking_detail->departure_datetime;
                    $transport_tracking->arriving_datetime = $transport_tracking_detail->arriving_datetime;
                    $transport_tracking->from_location = isset($transport_tracking_detail->from_location) ? $transport_tracking_detail->from_location : null;
                    $transport_tracking->to_location = isset($transport_tracking_detail->to_location) ? $transport_tracking_detail->to_location : null;
                    $transport_tracking->phone_number = isset($transport_tracking_detail->phone_number) ? $transport_tracking_detail->phone_number : null;
                    $transport_tracking->save();
                }
            }
        }

        DB::commit();

        return response()->json([
            'status' => 200,
            'message' => 'Transport Tracking Updated successfully.',
        ]);
        // } catch (\Exception $e) {
        //     Log::error($e);
        //     DB::rollback();
        //     return response()->json([
        //         'status' => 400,
        //         'message' => 'Data Store Fail.',
        //     ]);
        // }
    }
}
