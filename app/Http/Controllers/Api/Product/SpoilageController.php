<?php

namespace App\Http\Controllers\Api\Product;

use App\Core\CommonComponent;
use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\PurchaseSalesDocument;
use App\Models\Spoilage;
use App\Models\SpoilageExpense;
use App\Models\SpoilageProductDetail;
use App\Models\Store;
use App\Models\StoreInventoryDetail;
use App\Models\StoreStockUpdate;
use App\Models\TransportTracking;
use App\Models\WarehouseInventoryDetail;
use App\Models\WarehouseStockUpdate;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SpoilageController extends Controller
{
    public function spoilagelist(Request $request)
    {
        // try {
        $spoilage_order_number = $request->spoilage_order_number;

        if ($request->warehouse_id != null) {
            $warehouse_id = array($request->warehouse_id);
        } else {
            $warehouse_id = Auth::user()->user_warehouse();
        }

        $from_date = $request->from_date;
        $to_date = $request->to_date;
        $status = $request->status;
        if ($request->store_id != null) {
            $store_id = array($request->store_id);
        } else {
            $store_id = Auth::user()->user_stores();
        }

        $spoilage_query = Spoilage::where(function ($query) use ($spoilage_order_number, $from_date, $to_date, $status) {
            if ($spoilage_order_number != null) {
                $query->where('spoilage_order_number', 'LIKE', '%' . $spoilage_order_number . '%');
            }
            if ($status != null) {
                $query->where('status', $status);
            }
            if ($from_date != null && $to_date != null) {
                $dateformatwithtime = CommonComponent::dateformatwithtime($from_date, $to_date);
                $query->whereBetween('spoilage_date', $dateformatwithtime);
            }
        })
            ->where(function ($query) use ($warehouse_id, $store_id) {
                if (count($store_id) > 0 && count($warehouse_id) > 0) {
                    $query->whereIn('from_store_id', $store_id)->OrwhereIn('from_warehouse_id', $warehouse_id)->OrwhereIn('to_warehouse_id', $warehouse_id);
                } elseif (count($store_id) > 0) {
                    $query->whereIn('from_store_id', $store_id);
                } elseif (count($warehouse_id) > 0) {
                    $query->whereIn('from_warehouse_id', $warehouse_id)->OrwhereIn('to_warehouse_id', $warehouse_id);
                }
            })
            ->with('from_warehouse:id,name,code', 'from_store:id,store_name,store_code,phone_number,gst_number', 'to_warehouse:id,name,code', 'supplier_details:id,first_name,last_name,user_code,user_type,phone_number,status')
            ->orderBy('id', 'DESC');

        $spoilagecount = $spoilage_query->count();
        $spoilage_amount = $spoilage_query->sum('total_amount');

        $spoilage_data = $spoilage_query->paginate(15);

        return response()->json([
            'status' => 200,
            'spoilagecount' => $spoilagecount,
            'spoilage_amount' => $spoilage_amount,
            'datas' => $spoilage_data,
            'message' => 'spoilage fetched successfully.',
        ]);
        // } catch (\Exception $e) {
        //     Log::error($e);
        //     return response()->json([
        //         'status' => 400,
        //         'message' => 'Data not found.',
        //     ]);
        // }
    }

    public function spoilagedetail(Request $request)
    {
        // try {
        $spoilage_id = $request->spoilage_id;
        $spoilage = Spoilage::with('from_warehouse:id,name,code,status', 'from_store:id,store_name,store_code,phone_number,gst_number', 'to_warehouse:id,name,code,status', 'supplier_details:id,first_name,last_name,user_code')->findOrFail($spoilage_id);

        $transfer_product_details = SpoilageProductDetail::where('spoilage_id', $spoilage_id)->with('unit_details:id,unit_name,unit_short_code,allow_decimal,operator,operation_value')->get();

        $spoilage_expense = SpoilageExpense::where('spoilage_id', $spoilage_id)->with('income_expense_details')->get();
        $spoilage_expense_docs = PurchaseSalesDocument::where([['type', 5], ['reference_id', $spoilage_id], ['document_type', 1]])->get(); // 5 => spoilage. 1 => expense
        $spoilage_trackings = TransportTracking::where('spoilage_id', $spoilage_id)->with('transport_type_details')->get();
        $spoilage_trackings_docs = PurchaseSalesDocument::where([['type', 5], ['reference_id', $spoilage_id], ['document_type', 2]])->get(); // 5 => spoilage. 2 => transport tracking

        return response()->json([
            'status' => 200,
            'datas' => $spoilage,
            'transfer_product_details' => $transfer_product_details,
            'spoilage_expense' => $spoilage_expense,
            'spoilage_expense_docs' => $spoilage_expense_docs,
            'spoilage_trackings' => $spoilage_trackings,
            'spoilage_trackings_docs' => $spoilage_trackings_docs,
            'message' => 'Spoilage Details fetched successfully.',
        ]);
        // } catch (\Exception $e) {
        //     Log::error($e);
        //     return response()->json([
        //         'status' => 400,
        //         'message' => 'Data not found.',
        //     ]);
        // }
    }

    public function spoilagestore(Request $request)
    {
        // DB::beginTransaction();
        // dd($request->all());
        // try {
        $spoilage = new Spoilage();
        $spoilage->spoilage_order_number = $request->spoilage_order_number;
        $spoilage->spoilage_in = $request->spoilage_in;
        $spoilage->is_return = $request->is_return;
        if ($request->from_warehouse_id != null && $request->from_warehouse_id != "null" && $request->from_warehouse_id != "undefined") {
            $spoilage->from_warehouse_id = $request->from_warehouse_id;
        }
        if ($request->from_store_id != null && $request->from_store_id != "null" && $request->from_store_id != "undefined") {
            $spoilage->from_store_id = $request->from_store_id;
        }
        if ($request->to_supplier_id != null && $request->to_supplier_id != "null" && $request->to_supplier_id != "undefined") {
            $spoilage->to_supplier_id = $request->to_supplier_id;
        }
        if ($request->to_warehouse_id != null && $request->to_warehouse_id != "null" && $request->to_warehouse_id != "undefined") {
            $spoilage->to_warehouse_id = $request->to_warehouse_id;
        }
        $spoilage->spoilage_date = $request->spoilage_date;
        $spoilage->verified_person = Auth::user()->id;
        $spoilage->sub_total = $request->sub_total ?? $request->total_amount;
        $spoilage->round_off_amount = $request->round_off_amount;
        $spoilage->adjustment_amount = $request->adjustment_amount;
        $spoilage->total_amount = $request->total_amount;
        $spoilage->status = 1;
        $spoilage->is_notification_send_to_admin = $request->is_notification_send_to_admin;
        $spoilage->save();

        $spoilage_id = $spoilage->id;
        if (isset($request->products)) {
            $products = json_decode($request->products);
            if (count($products) > 0) {
                foreach ($products as $key => $product) {
                    $product_data = Product::findOrfail($product->product_id);
                    $indent_request_detail = new SpoilageProductDetail();
                    $indent_request_detail->spoilage_id = $spoilage_id;
                    $indent_request_detail->product_id = $product_data->id;
                    $indent_request_detail->sku_code = $product_data->sku_code;
                    $indent_request_detail->name = $product_data->name;
                    $indent_request_detail->unit_id = @$product->unit_id;
                    $indent_request_detail->quantity = @$product->quantity;
                    $indent_request_detail->per_unit_price = $product->amount;
                    $indent_request_detail->sub_total = @$product->sub_total;
                    $indent_request_detail->status = 1;
                    $indent_request_detail->total = @$product->total != null ? @$product->total : @$product->sub_total;
                    $indent_request_detail->save();

                    $quantity = $product->quantity;
                    if ($request->status == 10 && $request->to_warehouse_id != null && $request->to_warehouse_id != "null") {
                        if ($quantity != 0) {
                            $warehouse_stock_detail_exists = WarehouseStockUpdate::where([['warehouse_id', $request->to_warehouse_id], ['product_id', $product_data->id], ['status', 1]])->orderBy('id', 'DESC')->first();
                            $warehouse_stock_detail = new WarehouseStockUpdate();
                            $warehouse_stock_detail->warehouse_id = $request->to_warehouse_id;
                            $warehouse_stock_detail->product_id = $product_data->id;
                            $warehouse_stock_detail->stock_update_on = Carbon::now();
                            $warehouse_stock_detail->existing_stock = ($warehouse_stock_detail_exists != null && $warehouse_stock_detail_exists->total_stock != null) ? $warehouse_stock_detail_exists->total_stock : 0;
                            $warehouse_stock_detail->adding_stock = @$quantity;
                            $warehouse_stock_detail->total_stock = ($warehouse_stock_detail_exists != null && $warehouse_stock_detail_exists->total_stock != null) ? $warehouse_stock_detail_exists->total_stock - @$quantity : @$quantity;
                            $warehouse_stock_detail->status = 1;
                            $warehouse_stock_detail->box_number = $request->box_number != null ? $request->box_number : 1;
                            $warehouse_stock_detail->save();
                        }

                        $warehouse_inventory = WarehouseInventoryDetail::where([['warehouse_id', $request->to_warehouse_id], ['product_id', $product_data->id], ['status', 1]])->first();
                        if ($warehouse_inventory == null) {
                            $warehouse_inventory = new WarehouseInventoryDetail();
                            $warehouse_inventory->warehouse_id = $request->to_warehouse_id;
                            $warehouse_inventory->product_id = $product_data->id;
                        }
                        $warehouse_inventory->weight = @$warehouse_inventory->weight - @$quantity;
                        $warehouse_inventory->status = 1;
                        $warehouse_inventory->save();
                    }

                    if ($request->from_store_id != null && $request->from_store_id != "null") {
                        if ($quantity != 0) {
                            $store_detail = Store::select('warehouse_id')->find($request->from_store_id);
                            $store_stock_detail_exists = StoreStockUpdate::where([['store_id', $request->from_store_id], ['product_id', $product_data->id], ['status', 1]])->orderBy('id', 'DESC')->first();
                            $store_stock_detail = new StoreStockUpdate();
                            $store_stock_detail->from_warehouse_id = $store_detail->warehouse_id;
                            $store_stock_detail->store_id = $request->from_store_id;
                            $store_stock_detail->product_id = $product_data->id;
                            $store_stock_detail->reference_id = $spoilage->id;
                            $store_stock_detail->reference_table = 13; //13 Spoilage table
                            $store_stock_detail->stock_update_on = Carbon::now();
                            $store_stock_detail->existing_stock = ($store_stock_detail_exists != null && $store_stock_detail_exists->total_stock != null) ? $store_stock_detail_exists->total_stock : 0;
                            $store_stock_detail->adding_stock = @$quantity;
                            $store_stock_detail->total_stock = ($store_stock_detail_exists != null && $store_stock_detail_exists->total_stock != null) ? $store_stock_detail_exists->total_stock - @$quantity : @$quantity;
                            $store_stock_detail->status = 1;
                            $store_stock_detail->save();
                        }


                        $store_inventory = StoreInventoryDetail::where([['store_id', $request->from_store_id], ['product_id', $product_data->id], ['status', 1]])->latest()->first();
                        if ($store_inventory == null) {
                            $store_inventory = new StoreInventoryDetail();
                            $store_inventory->store_id = $request->from_store_id;
                            $store_inventory->product_id = $product_data->id;
                        }
                        $store_inventory->weight = @$store_inventory->weight - @$quantity;
                        $store_inventory->status = 1;
                        $store_inventory->save();
                        Log::info("Store inventory");
                        Log::info($store_inventory);

                    }
                }
            }
        }

        // Expense Docs Delete
        if (isset($request->deleted_expense_doc_ids) && count(json_decode($request->deleted_expense_doc_ids)) > 0) {
            foreach (json_decode($request->deleted_expense_doc_ids) as $key => $value) {
                if ($value) {
                    $spoilage_docs = PurchaseSalesDocument::findOrFail($value);
                    CommonComponent::s3BucketFileDelete($spoilage_docs->file, $spoilage_docs->file_path);

                    $spoilage_docs->delete();
                }
            }
        }

        // Expense Docs Store
        if (isset($request->expense_documents) && count($request->expense_documents) > 0 && $request->file('expense_documents')) {
            foreach ($request->file('expense_documents') as $key => $value) {
                if ($value) {
                    $imageData = CommonComponent::s3BucketFileUpload($value, 'spoilage_expense_docs');
                    $imagePath = $imageData['filePath'];
                    // $imageUrl = $imageData['fileName'];
                    $imageUrl = $imageData['imageURL'];

                    $spoilage_docs = new PurchaseSalesDocument();
                    $spoilage_docs->type = 5; // Spoilage
                    $spoilage_docs->reference_id = $spoilage_id;
                    $spoilage_docs->document_type = 1; // Expense
                    $spoilage_docs->file = @$imageUrl;
                    $spoilage_docs->file_path = @$imagePath;
                    $spoilage_docs->save();
                }
            }
        }

        // Expense Data Delete
        if (isset($request->deleted_expense_ids) && count(json_decode($request->deleted_expense_ids)) > 0) {
            SpoilageExpense::destroy(json_decode($request->deleted_expense_ids));
        }

        // Expense Data Store
        if (isset($request->expense)) {
            $expense = json_decode($request->expense);
            if (count($expense) > 0) {
                foreach ($expense as $expense_key => $exp) {
                    if (isset($exp->id)) {
                        $spoilage_expense = SpoilageExpense::where("id", $exp->id)->first();
                    } else {
                        $spoilage_expense = new SpoilageExpense();
                    }
                    $spoilage_expense->spoilage_id = $spoilage_id;
                    $spoilage_expense->income_expense_id = @$exp->expense_type_id;
                    $spoilage_expense->ie_amount = @$exp->expense_amount;
                    $spoilage_expense->is_billable = @$exp->is_billable;
                    $spoilage_expense->save();
                }
            }
        }

        DB::commit();

        return response()->json([
            'status' => 200,
            'datas' => $spoilage,
            'message' => 'Spoilage Added successfully.',
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

    public function spoilageupdate(Request $request)
    {
        // return $request->all();
        DB::beginTransaction();
        // try {
        Log::info("request->all()");
        Log::info($request->all());
        $spoilage_id = $request->spoilage_id;

        $spoilage = Spoilage::findOrFail($spoilage_id);
        $spoilage->is_return = $request->is_return ?? 0;
        // if ($request->from_warehouse_id != null && $request->from_warehouse_id != "undefined" && $request->from_warehouse_id != "null") {
        //     $spoilage->from_warehouse_id = $request->from_warehouse_id;
        // }

        if ($request->from_store_id != null && $request->from_store_id != "undefined" && $request->from_store_id != "null") {
            $spoilage->from_store_id = $request->from_store_id;
        }
        if ($request->to_supplier_id != null && $request->to_supplier_id != "undefined" && $request->to_supplier_id != "null") {
            $spoilage->to_supplier_id = $request->to_supplier_id;
        }
        if ($request->to_warehouse_id != null && $request->to_warehouse_id != "undefined" && $request->to_warehouse_id != "null") {
            $spoilage->to_warehouse_id = $request->to_warehouse_id;
        }
        $spoilage->status = $request->status;
        $spoilage->spoilage_date = $request->spoilage_date;
        $spoilage->verified_person = Auth::user()->id;
        $spoilage->sub_total = $request->sub_total ?? $request->total_amount;
        $spoilage->round_off_amount = $request->round_off_amount;
        $spoilage->adjustment_amount = $request->adjustment_amount;
        $spoilage->total_amount = $spoilage->total_expense_billable_amount + $request->total_amount;
        $spoilage->is_notification_send_to_admin = $request->is_notification_send_to_admin;
        $spoilage->save();
        Log::info("spoilage");
        Log::info($spoilage);
        if (isset($request->deleted_ids) && count(json_decode($request->deleted_ids)) > 0) {
            SpoilageProductDetail::destroy(json_decode($request->deleted_ids));
        }

        if (isset($request->products)) {
            $products = json_decode($request->products);
            if (count($products) > 0) {
                foreach ($products as $key => $product) {
                    $product_data = Product::findOrfail($product->product_id);
                    $old_weight = 0;
                    if (isset($product->id)) {
                        $indent_request_detail = SpoilageProductDetail::findOrFail($product->id);
                        $old_weight = $indent_request_detail->quantity;
                    } else {
                        $indent_request_detail = new SpoilageProductDetail();
                        $indent_request_detail->spoilage_id = $spoilage_id;
                    }
                    $indent_request_detail->product_id = $product_data->id;
                    $indent_request_detail->sku_code = $product_data->sku_code;
                    $indent_request_detail->name = $product_data->name;
                    $indent_request_detail->unit_id = @$product->unit_id;
                    $indent_request_detail->quantity = @$product->quantity;
                    $indent_request_detail->per_unit_price = $product->amount;
                    $indent_request_detail->sub_total = @$product->sub_total;
                    $indent_request_detail->total = @$product->total != null ? @$product->total : @$product->sub_total;
                    $indent_request_detail->status = $request->status;
                    $indent_request_detail->save();
                    Log::info("indent_request_detail");
                    Log::info($indent_request_detail);
                    $request_status = $request->status;
                    $from_warehouse_id = $request->to_warehouse_id ?? $request->from_warehouse_id;
                    $request_box_number = $request->box_number;

                    if ($request->stock_verified == 1) {
                        $warehouse_stock_detail = WarehouseStockUpdate::where([['warehouse_id', $from_warehouse_id], ['product_id', $product_data->id], ['status', 1]])->latest()->first();

                        if ($request_status == '10') {
                            $previous_adding_stock = @$warehouse_stock_detail->adding_stock;
                            $previous_existing_stock = @$warehouse_stock_detail->existing_stock;
                            $previous_total_stock = @$warehouse_stock_detail->total_stock;

                            $given_quantity = $indent_request_detail->quantity;

                            $equal_check_adding = $previous_adding_stock ? $previous_adding_stock == $given_quantity : false;
                            $greater_check_adding = $previous_adding_stock ? $previous_adding_stock < $given_quantity : false;
                            if ($request->to_warehouse_id != null && $request->to_warehouse_id != "undefined" && $request->to_warehouse_id != "null") {
                                if ($equal_check_adding) {  // check if previous and current quantity are same
                                    Log::info('entered equal condition');
                                    $difference_stock = 0;
                                    $adding_stock = $given_quantity;
                                    $existing_stock = $previous_existing_stock ?? 0;
                                    $total_stock = $previous_total_stock;
                                } elseif ($greater_check_adding) {
                                    Log::info('entered greater condition');  // check if  current quantity are greater than previous quantity
                                    $difference_stock = $previous_adding_stock + $given_quantity;
                                    $adding_stock = $given_quantity;
                                    $existing_stock = $previous_total_stock ?? 0;
                                    $total_stock = $previous_total_stock + $difference_stock;
                                } else {
                                    Log::info('entered else condition');
                                    $difference_stock = $previous_adding_stock - $given_quantity;
                                    $adding_stock = $given_quantity;
                                    $existing_stock = $previous_total_stock ?? 0;
                                    $total_stock = $previous_total_stock - $difference_stock;
                                }
                            }

                            if ($warehouse_stock_detail == null) {

                                $warehouse_stock_detail = new WarehouseStockUpdate();
                                $warehouse_stock_detail->warehouse_id = $from_warehouse_id;
                                $warehouse_stock_detail->product_id = $product_data->id;
                            }
                            $warehouse_stock_detail->stock_update_on = Carbon::now();
                            $warehouse_stock_detail->existing_stock = $existing_stock;
                            $warehouse_stock_detail->adding_stock = $adding_stock;
                            $warehouse_stock_detail->total_stock = $total_stock;
                            $warehouse_stock_detail->status = 1;
                            $warehouse_stock_detail->stock_verified = $request_status == '10' ? 1 : 0;
                            $warehouse_stock_detail->box_number = $request_box_number != null ? $request_box_number : $warehouse_stock_detail->box_number;
                            $warehouse_stock_detail->save();
                            Log::info("warehouse_stock_detail");
                            Log::info($warehouse_stock_detail);

                            $warehouse_inventory = WarehouseInventoryDetail::where([['warehouse_id', $from_warehouse_id], ['product_id', $product_data->id], ['status', 1]])->latest()->first();
                            if ($equal_check_adding) { // check if previous and current quantity are same
                                $inventory_weight = $warehouse_inventory->weight + $difference_stock;
                            } elseif ($greater_check_adding) { // check if  current quantity are greater than previous quantity
                                $inventory_weight = $warehouse_inventory->weight + $difference_stock;
                            } else {
                                $inventory_weight = $warehouse_inventory->weight - $difference_stock;
                            }

                            if ($warehouse_inventory == null) {
                                $warehouse_inventory = new WarehouseInventoryDetail();
                                $warehouse_inventory->warehouse_id = $from_warehouse_id;
                                $warehouse_inventory->product_id = $product_data->id;
                            }
                            $warehouse_inventory->weight = $inventory_weight;
                            $warehouse_inventory->status = 1;
                            $warehouse_inventory->save();
                            Log::info('Warehouse inventory');
                            Log::info($warehouse_inventory);

                            $store_stock_detail = StoreStockUpdate::where([['store_id', $request->from_store_id], ['product_id', $product_data->id], ['reference_id', $spoilage->id], ['status', 1]])->latest()->first();
                            if ($request->from_store_id != null && $request->from_store_id != "null") {

                                $previous_store_adding_stock = $store_stock_detail->adding_stock ?? 0;
                                $previous_store_existing_stock = $store_stock_detail->existing_stock ?? 0;
                                $previous_store_total_stock = $store_stock_detail->total_stock ?? 0;

                                $store_existing_stock = $previous_store_total_stock;

                                $greater_qty_check = $previous_store_adding_stock < $given_quantity;
                                $lessthan_qty_check = $previous_store_adding_stock > $given_quantity;
                                $equal_qty_check = $previous_store_adding_stock == $given_quantity;
                                $stock_difference = $store_existing_stock + $given_quantity;

                                $store_detail = Store::select('warehouse_id')->find($request->from_store_id);

                                if ($store_stock_detail == null) {
                                    $store_stock_detail = new StoreStockUpdate();
                                    $store_stock_detail->from_warehouse_id = $store_detail->warehouse_id;
                                    $store_stock_detail->store_id = $request->from_store_id;
                                    $store_stock_detail->product_id = $product_data->id;
                                }

                                $store_stock_detail->reference_id = $spoilage->id;
                                $store_stock_detail->reference_table = 13; // 13 Spoilage table
                                $store_stock_detail->stock_update_on = Carbon::now();

                                if ($greater_qty_check) {
                                    Log::info('Greater qty check');// If the current quantity is greater than previous quantity
                                    $stock_qty_difference = $previous_store_adding_stock - $given_quantity;
                                    $store_stock_detail->existing_stock = $store_existing_stock ?? 0;
                                    $store_stock_detail->adding_stock = $given_quantity;
                                    $store_stock_detail->total_stock = $store_existing_stock - $stock_qty_difference;
                                }
                                if ($lessthan_qty_check) {
                                    Log::info('Lessthan qty check');// If the current quantity is less than previous quantity
                                    $stock_qty_difference = $previous_store_adding_stock - $given_quantity;
                                    $store_stock_detail->existing_stock = $store_existing_stock ?? 0;
                                    $store_stock_detail->adding_stock = $stock_qty_difference;
                                    $store_stock_detail->total_stock = $store_existing_stock + $stock_qty_difference;
                                }
                                if($equal_qty_check){
                                    Log::info('Equal qty check');// If the current quantity is equal to previous quantity
                                    $store_stock_detail->existing_stock = $store_existing_stock ?? 0;
                                    $store_stock_detail->adding_stock = 0;
                                    $store_stock_detail->total_stock = $store_existing_stock ?? 0;
                                }


                                $store_stock_detail->status = 1;
                                $store_stock_detail->save();

                                Log::info("store_stock_detail");
                                Log::info($store_stock_detail);

                                $store_inventory = StoreInventoryDetail::where([
                                    ['store_id', $request->from_store_id],
                                    ['product_id', $product_data->id],
                                    ['status', 1]
                                ])->latest()->first();

                                if ($store_inventory == null) {
                                    $store_inventory = new StoreInventoryDetail();
                                    $store_inventory->store_id = $request->from_store_id;
                                    $store_inventory->product_id = $product_data->id;
                                }

                                if ($greater_qty_check) {
                                    Log::info('Equal qty check');
                                    $store_inventory->weight = $previous_store_existing_stock - $given_quantity;
                                } else if ($lessthan_qty_check) {
                                    Log::info('Lessthan qty check');
                                    $store_inventory->weight = $stock_difference;
                                } else {
                                    Log::info('Greater qty check');
                                    $store_inventory->weight = $previous_store_total_stock;
                                }

                                $store_inventory->status = 1;
                                $store_inventory->save();

                                Log::info("store_inventory");
                                Log::info($store_inventory);

                            }
                        }
                    }
                }
            }
        }

        // Expense Docs Delete
        if (isset($request->deleted_expense_doc_ids) && count(json_decode($request->deleted_expense_doc_ids)) > 0) {
            foreach (json_decode($request->deleted_expense_doc_ids) as $key => $value) {
                if ($value) {
                    $spoilage_docs = PurchaseSalesDocument::findOrFail($value);
                    CommonComponent::s3BucketFileDelete($spoilage_docs->file, $spoilage_docs->file_path);

                    $spoilage_docs->delete();
                }
            }
        }

        // Expense Docs Store
        if (isset($request->expense_documents) && count($request->expense_documents) > 0 && $request->file('expense_documents')) {
            foreach ($request->file('expense_documents') as $key => $value) {
                if ($value) {
                    $imageData = CommonComponent::s3BucketFileUpload($value, 'spoilage_expense_docs');
                    $imagePath = $imageData['filePath'];
                    // $imageUrl = $imageData['fileName'];
                    $imageUrl = $imageData['imageURL'];

                    $spoilage_docs = new PurchaseSalesDocument();
                    $spoilage_docs->type = 5; // Spoilage
                    $spoilage_docs->reference_id = $spoilage_id;
                    $spoilage_docs->document_type = 1; // Expense
                    $spoilage_docs->file = @$imageUrl;
                    $spoilage_docs->file_path = @$imagePath;
                    $spoilage_docs->save();
                }
            }
        }

        // Expense Data Delete
        if (isset($request->deleted_expense_ids) && count(json_decode($request->deleted_expense_ids)) > 0) {
            SpoilageExpense::destroy(json_decode($request->deleted_expense_ids));
        }

        // Expense Data Store
        if (isset($request->expense)) {
            $expense = json_decode($request->expense);
            if (count($expense) > 0) {
                foreach ($expense as $expense_key => $exp) {
                    if (isset($exp->id)) {
                        $spoilage_expense = SpoilageExpense::where("id", $exp->id)->first();
                    } else {
                        $spoilage_expense = new SpoilageExpense();
                    }
                    $spoilage_expense->spoilage_id = $spoilage_id;
                    $spoilage_expense->income_expense_id = @$exp->expense_type_id;
                    $spoilage_expense->ie_amount = @$exp->expense_amount;
                    $spoilage_expense->is_billable = @$exp->is_billable;
                    $spoilage_expense->save();
                }
            }
        }

        DB::commit();

        return response()->json([
            'status' => 200,
            'datas' => $spoilage,
            'message' => 'Spoilage Updated successfully.',
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

    public function spoilageexpenseupdate(Request $request)
    {
        DB::beginTransaction();
        // try {
        $spoilage_id = $request->spoilage_id;
        $total_remove_exp_amount = SpoilageExpense::where("spoilage_id", $spoilage_id)->where('is_billable', 1)->sum('ie_amount');
        $total_unbill_amount = SpoilageExpense::where("spoilage_id", $spoilage_id)->where('is_billable', 0)->sum('ie_amount');
        // Expense Docs Delete
        if (isset($request->deleted_expense_doc_ids) && count(json_decode($request->deleted_expense_doc_ids)) > 0) {
            foreach (json_decode($request->deleted_expense_doc_ids) as $key => $value) {
                if ($value) {
                    $spoilage_docs = PurchaseSalesDocument::findOrFail($value);
                    CommonComponent::s3BucketFileDelete($spoilage_docs->file, $spoilage_docs->file_path);

                    $spoilage_docs->delete();
                }
            }
        }

        // Expense Docs Store
        if (isset($request->expense_documents) && count($request->expense_documents) > 0 && $request->file('expense_documents')) {
            foreach ($request->file('expense_documents') as $key => $value) {
                if ($value) {
                    $imageData = CommonComponent::s3BucketFileUpload($value, 'spoilage_expense_docs');
                    $imagePath = $imageData['filePath'];
                    // $imageUrl = $imageData['fileName'];
                    $imageUrl = $imageData['imageURL'];

                    $spoilage_docs = new PurchaseSalesDocument();
                    $spoilage_docs->type = 5; // Spoilage
                    $spoilage_docs->reference_id = $spoilage_id;
                    $spoilage_docs->document_type = 1; // Expense
                    $spoilage_docs->file = @$imageUrl;
                    $spoilage_docs->file_path = @$imagePath;
                    $spoilage_docs->save();
                }
            }
        }

        // Expense Data Delete
        if (isset($request->deleted_expense_ids) && count(json_decode($request->deleted_expense_ids)) > 0) {
            SpoilageExpense::destroy(json_decode($request->deleted_expense_ids));
        }

        // Expense Data Store
        if (isset($request->expense)) {
            $total_expense_amount = 0;
            $total_expense_billable_amount = 0;
            $expense = json_decode($request->expense);
            if (count($expense) > 0) {
                foreach ($expense as $expense_key => $exp) {
                    if (isset($exp->id)) {
                        $spoilage_expense = SpoilageExpense::where("id", $exp->id)->first();
                    } else {
                        $spoilage_expense = new SpoilageExpense();
                    }
                    $spoilage_expense->spoilage_id = $spoilage_id;
                    $spoilage_expense->income_expense_id = @$exp->expense_type_id;
                    $spoilage_expense->ie_amount = @$exp->expense_amount;
                    $spoilage_expense->is_billable = @$exp->is_billable;
                    if ($exp->is_billable == 1) {
                        $total_expense_billable_amount += $exp->expense_amount;
                    }
                    $total_expense_amount += $exp->expense_amount;
                    $spoilage_expense->save();
                }

                $spoilage_detail = Spoilage::findOrFail($spoilage_id);
                $spoilage_detail->total_expense_billable_amount = $total_expense_billable_amount;
                $spoilage_detail->total_expense_amount = $total_expense_amount;
                $spoilage_detail->total_amount = ($spoilage_detail->total_amount - $total_remove_exp_amount) + $total_expense_billable_amount;
                $spoilage_detail->save();
            }
        } else {
            $spoilage_detail = Spoilage::findOrFail($spoilage_id);
            $spoilage_detail->total_expense_billable_amount = 0;
            $spoilage_detail->total_expense_amount = 0;
            $spoilage_detail->total_amount = ($spoilage_detail->total_amount - $total_remove_exp_amount - $total_unbill_amount);
            $spoilage_detail->save();
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

    public function spoilagetransporttrackingupdate(Request $request)
    {
        DB::beginTransaction();
        // try {
        $spoilage_id = $request->spoilage_id;
        // Transaport Tracking Docs Delete
        if (isset($request->deleted_tracking_doc_ids) && count(json_decode($request->deleted_tracking_doc_ids)) > 0) {
            foreach (json_decode($request->deleted_tracking_doc_ids) as $key => $value) {
                if ($value) {
                    $spoilage_docs = PurchaseSalesDocument::findOrFail($value);
                    CommonComponent::s3BucketFileDelete($spoilage_docs->file, $spoilage_docs->file_path);

                    $spoilage_docs->delete();
                }
            }
        }

        // Transaport Tracking Docs Store
        if (isset($request->transport_tracking_files) && count($request->transport_tracking_files) > 0 && $request->file('transport_tracking_files')) {
            foreach ($request->file('transport_tracking_files') as $key => $value) {
                if ($value) {
                    $imagePath = null;
                    $imageUrl = null;
                    $imageData = CommonComponent::s3BucketFileUpload($value, 'spoilage_tracking_docs');
                    $imagePath = $imageData['filePath'];
                    // $imageUrl = $imageData['fileName'];
                    $imageUrl = $imageData['imageURL'];

                    $spoilage_docs = new PurchaseSalesDocument();
                    $spoilage_docs->type = 5; // Spoilage
                    $spoilage_docs->reference_id = $spoilage_id;
                    $spoilage_docs->document_type = 2; // Transport Tracking
                    $spoilage_docs->file = @$imageUrl;
                    $spoilage_docs->file_path = @$imagePath;
                    $spoilage_docs->save();
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
                    $transport_tracking->spoilage_id = $spoilage_id;
                    $transport_tracking->transport_type_id = $transport_tracking_detail->transport_type_id;
                    $transport_tracking->transport_name = $transport_tracking_detail->transport_name;
                    $transport_tracking->transport_number = $transport_tracking_detail->transport_number;
                    $transport_tracking->departure_datetime = $transport_tracking_detail->departure_datetime;
                    $transport_tracking->arriving_datetime = $transport_tracking_detail->arriving_datetime;
                    $transport_tracking->from_location = isset($transport_tracking_detail->from_location) ? $transport_tracking_detail->from_location : NULL;
                    ;
                    $transport_tracking->to_location = isset($transport_tracking_detail->to_location) ? $transport_tracking_detail->to_location : NULL;
                    ;
                    $transport_tracking->phone_number = isset($transport_tracking_detail->phone_number) ? $transport_tracking_detail->phone_number : NULL;
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
