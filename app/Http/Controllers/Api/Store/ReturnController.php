<?php

namespace App\Http\Controllers\Api\Store;

use App\Core\CommonComponent;
use App\Http\Controllers\Controller;
use App\Models\PaymentTransaction;
use App\Models\PaymentTransactionDocument;
use App\Models\Product;
use App\Models\PurchaseSalesDocument;
use App\Models\SalesOrder;
use App\Models\SalesOrderReturn;
use App\Models\SalesOrderReturnDetail;
use App\Models\SalesOrderReturnExpense;
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

class ReturnController extends Controller
{
    public function returnlist(Request $request)
    {
        // try {
        $return_from = $request->return_from;
        if ($request->warehouse_id != null) {
            $warehouse_id = array($request->warehouse_id);
        } else {
            $warehouse_id = Auth::user()->user_warehouse();
        }

        $sales_order_return_number = $request->sales_order_return_number;
        $from_date = $request->from_date;
        $to_date = $request->to_date;
        $status = $request->status;
        $customer_id = $request->customer_id;
        if ($request->store_id != null) {
            $store_id = array($request->store_id);
        } else {
            $store_id = Auth::user()->user_stores();
        }
        $purchaselists = SalesOrderReturn::where(function ($query) use ($warehouse_id, $store_id, $customer_id, $sales_order_return_number, $from_date, $to_date, $status) {
            if (count($warehouse_id) > 0 && count($store_id) > 0) {
                $query->whereIn('to_warehouse_id', $warehouse_id)->OrWhereIn('from_store_id', $store_id);
            } else if (count($warehouse_id) > 0) {
                $query->whereIn('to_warehouse_id', $warehouse_id);
            } else if (count($store_id) > 0) {
                $query->whereIn('from_store_id', $store_id);
            }
        })
            ->where(function ($query) use ($warehouse_id, $store_id, $customer_id, $sales_order_return_number, $from_date, $to_date, $status, $return_from) {
                if ($status != null) {
                    $query->where('status', $status);
                }
                if ($return_from != null) {
                    $query->where('return_from', $return_from);
                }
                if ($customer_id != null) {
                    $query->where('from_vendor_id', $customer_id);
                }
                if ($sales_order_return_number != null) {
                    $query->where('sales_order_return_number', 'LIKE', '%' . $sales_order_return_number . '%');
                }
                if ($from_date != null && $to_date != null) {
                    $dateformatwithtime = CommonComponent::dateformatwithtime($from_date, $to_date);
                    $query->whereBetween('return_date', $dateformatwithtime);
                }
            })
            ->with([
                'from_store' => function ($query) {
                    $query->select('id', 'store_name', 'store_code');
                },
            ])
            ->with([
                'from_vendor' => function ($query) {
                    $query->select('id', 'first_name', 'last_name', 'user_type', 'status');
                },
            ])
            ->with([
                'to_warehouse' => function ($query) {
                    $query->select('id', 'name', 'code');
                },
            ])
            ->with([
                'created_by_details' => function ($query) {
                    $query->select('id', 'first_name', 'last_name', 'user_type', 'status');
                },
            ])
            ->orderBy('id', 'DESC')
            ->paginate(15);
        // ->toSql();

        return response()->json([
            'status' => 200,
            'returnlists' => $purchaselists,
            'message' => 'Return fetched successfully.',
        ]);
        // } catch (\Exception $e) {
        //     Log::error($e);
        //     return response()->json([
        //         'status' => 400,
        //         'message' => 'Data not found.',
        //     ]);
        // }
    }

    public function returnorderdetails(Request $request)
    {
        // try {
        $sales_order_return_id = $request->sales_order_return_id;
        $return_details = SalesOrderReturn::with([
            'supplier' => function ($query) use ($sales_order_return_id) {
                $query->select('id', 'first_name', 'last_name', 'user_type', 'status');
            },
        ])
            ->with([
                'from_store' => function ($query) {
                    $query->select('id', 'store_name', 'store_code');
                },
            ])
            ->with([
                'from_vendor' => function ($query) {
                    $query->select('id', 'first_name', 'last_name', 'user_type', 'status');
                },
            ])
            ->with([
                'to_warehouse' => function ($query) {
                    $query->select('id', 'name', 'code');
                },
            ])
            ->with([
                'created_by_details' => function ($query) {
                    $query->select('id', 'first_name', 'last_name', 'user_type', 'status');
                },
            ])->with(['sales_order_details'])
            ->findOrFail($sales_order_return_id);
        $return_details->invoice_number = SalesOrder::where('id', $return_details->sales_order_id)->value('invoice_number');

        $return_product_details = SalesOrderReturnDetail::where('sales_order_return_id', $sales_order_return_id)
            ->with('unit_details:id,unit_name,unit_short_code,allow_decimal,operator,operation_value')
            ->get();

        $return_expense_details = SalesOrderReturnExpense::where('sales_order_return_id', $sales_order_return_id)
            ->with('income_expense_details')
            ->get();

        $return_expense_docs = PurchaseSalesDocument::where([['type', 3], ['reference_id', $sales_order_return_id], ['document_type', 1]])->get(); //document_type = 1 => expense, 3 => store return

        $return_transport_trackings = TransportTracking::where('sales_order_return_id', $sales_order_return_id)
            ->with('transport_type_details')
            ->get();

        $return_transport_trackings_docs = PurchaseSalesDocument::where([['type', 3], ['reference_id', $sales_order_return_id], ['document_type', 2]])->get(); //document_type = 2 => Transaport Tracking, 3 => store return

        $return_order_transactions = $return_details->return_order_transactions;

        $paid_amount = $return_details->return_order_paid_transactions->sum('amount'); // 1=>credit
        $return_amount = $return_details->return_order_refund_transactions->sum('amount'); // 2=>debit
        $total_amount = $return_details->total_amount;
        $due_amount = $total_amount - $paid_amount;
        $due_amount = $due_amount > 0 ? $due_amount : 0;

        return response()->json([
            'status' => 200,
            'return_details' => $return_details,
            'return_product_details' => $return_product_details,
            'return_expense_details' => $return_expense_details,
            'return_transport_trackings' => $return_transport_trackings,
            'return_expense_docs' => $return_expense_docs,
            'return_transport_trackings_docs' => $return_transport_trackings_docs,
            'return_order_transactions' => $return_order_transactions,
            'paid_amount' => round($paid_amount, 2),
            'total_amount' => round($total_amount, 2),
            'due_amount' => round($due_amount, 2),
            'return_amount' => round($return_amount, 2),
            'message' => 'Return Order fetched successfully.',
        ]);
    }

    public function returnorderstore(Request $request)
    {
        DB::beginTransaction();
        // try {
        $sales_order_return = new SalesOrderReturn();
        $sales_order_return->sales_order_return_number = $request->sales_order_return_number;
        $sales_order_return->return_from = $request->return_from;
        $sales_order_return->to_warehouse_id = $request->to_warehouse_id;
        if ($request->sales_order_id != null && $request->sales_order_id != 'null') {
            $sales_details = SalesOrder::select('warehouse_id', 'store_id', 'vendor_id')->find($request->sales_order_id);
            $sales_order_return->from_vendor_id = $sales_details->vendor_id;
            $sales_order_return->from_store_id = $sales_details->store_id;
        }
        if ($request->from_store_id != null && $request->from_store_id != 'null') {
            $sales_order_return->from_store_id = $request->from_store_id;
        }
        if ($request->from_vendor_id != null && $request->from_vendor_id != 'null') {
            $sales_order_return->from_vendor_id = $request->from_vendor_id;
        }
        $sales_order_return->sales_order_id = $request->sales_order_id;
        $sales_order_return->return_type = $request->return_type;
        $sales_order_return->return_date = $request->return_date;
        $sales_order_return->sub_total = $request->sub_total;
        $sales_order_return->round_off_amount = $request->round_off_amount;
        $sales_order_return->adjustment_amount = $request->adjustment_amount;
        $sales_order_return->total_amount = $request->total_amount;
        $sales_order_return->payment_status = $request->payment_status != null ? $request->payment_status : 2; // Unpaid
        $sales_order_return->status = 1;
        $sales_order_return->is_same_day_return = $request->is_same_day_return;
        $sales_order_return->save();

        $products = json_decode($request->products);
        if (count($products) > 0) {
            foreach ($products as $key => $product) {
                $product_data = Product::findOrfail($product->product_id);
                $order_product_detail = new SalesOrderReturnDetail();
                $order_product_detail->sales_order_return_id = $sales_order_return->id;
                $order_product_detail->product_id = $product_data->id;
                $order_product_detail->unit_id = $product_data->unit_id;
                $order_product_detail->sku_code = $product_data->sku_code;
                $order_product_detail->name = $product_data->name;
                $order_product_detail->quantity = @$product->quantity;
                $order_product_detail->per_unit_price = @$product->amount;
                $order_product_detail->total = @$product->sub_total;
                $order_product_detail->status = 1;
                $order_product_detail->save();
            }
        }
        DB::commit();

        return response()->json([
            'status' => 200,
            'message' => 'Return Order Expense Stored Successfully.',
        ]);
        // } catch (\Exception $e) {
        //     Log::error($e);
        //     DB::rollback();
        //     return response()->json([
        //         'status' => 400,
        //         'message' => 'Data not found.',
        //     ]);
        // }
    }

    public function returnorderupdate(Request $request)
    {
        DB::beginTransaction();
        try {
            $sales_order_return_id = $request->sales_order_return_id;
            $sales_order_return = SalesOrderReturn::findOrFail($sales_order_return_id);

            // Update Sales Order Return details
            $sales_order_return->fill([
                'sales_order_return_number' => $request->sales_order_return_number,
                'return_from' => $request->return_from,
                'to_warehouse_id' => $request->to_warehouse_id,
                'from_store_id' => $request->from_store_id,
                'from_vendor_id' => $request->from_vendor_id,
                'sales_order_id' => $request->sales_order_id,
                'return_type' => $request->return_type,
                'return_date' => $request->return_date,
                'sub_total' => $request->sub_total,
                'round_off_amount' => $request->round_off_amount,
                'adjustment_amount' => $request->adjustment_amount,
                'total_amount' => $sales_order_return->total_expense_billable_amount + $request->total_amount,
                'status' => $request->status,
                'is_same_day_return' => $request->is_same_day_return,
            ]);
            $sales_order_return->save();

            // Handle deleted IDs
            if ($request->has('deleted_ids') && count(json_decode($request->deleted_ids)) > 0) {
                SalesOrderReturnDetail::destroy(json_decode($request->deleted_ids));
            }

            $products = json_decode($request->products);
            if (!empty($products)) {
                foreach ($products as $product) {
                    $product_data = Product::findOrFail($product->product_id);

                    $order_product_detail = SalesOrderReturnDetail::find($product->id) ?? new SalesOrderReturnDetail();
                    $old_weight = $order_product_detail->quantity ?? 0;

                    // Update or create Sales Order Return Detail
                    $order_product_detail->fill([
                        'sales_order_return_id' => $sales_order_return->id,
                        'product_id' => $product_data->id,
                        'unit_id' => $product_data->unit_id,
                        'sku_code' => $product_data->sku_code,
                        'name' => $product_data->name,
                        'quantity' => $product->quantity ?? 0,
                        'per_unit_price' => $product->amount ?? 0,
                        'total' => $product->sub_total ?? 0,
                        'status' => 1,
                    ]);
                    $order_product_detail->save();

                    // Calculate stock update quantities
                    $request_quantity = $order_product_detail->quantity;

                    if ($request_quantity == $old_weight) {
                        $to_quantity = $request_quantity;
                    } else if ($request_quantity > $old_weight) {
                        $to_quantitys = ($request_quantity - $old_weight);
                        $to_quantity += ($request_quantity + $to_quantitys);
                    } else {
                        $to_quantity = $old_weight - $request_quantity;
                    }
                    // if ($request_quantity == $old_weight) {
                    //     $from_quantity = -$request_quantity;
                    // } else if ($request_quantity > $old_weight) {
                    //     $from_quantitys = ($request_quantity - $old_weight);
                    //     $from_quantity += ($request_quantity + $from_quantitys);
                    // } else {
                    //     $from_quantity = $old_weight - $request_quantity;
                    // }
                    $from_quantity = -($request_quantity == $old_weight ? $request_quantity : $request_quantity - $old_weight);
                    Log::info("all data");
                    Log::info($from_quantity);
                    Log::info($to_quantity);
                    if ($request->stock_verified) {
                        if ($request->sales_order_id != null) {
                            $sales_order = SalesOrder::findOrFail($request->sales_order_id);
                            $sales_order->status = config('app.returned_status');
                            $sales_order->save();
                        }

                        // Handle stock updates based on return_from
                        if ($request->return_from == 1) { // Store to Warehouse
                            Log::info("return_from1");
                            $this->updateStoreStock($request, $product_data, $from_quantity);
                            $this->updateWarehouseStock($request, $product_data, $to_quantity);
                        } else if ($request->return_from == 2) { // Vendor to Store
                            $this->updateStoreStock($request, $product_data, $to_quantity);
                        }
                    }
                }
            }
            DB::commit();

            return response()->json([
                'status' => 200,
                'message' => 'Return Order Expense Updated Successfully.',
            ]);
        } catch (\Exception $e) {
            Log::error($e);
            DB::rollback();
            return response()->json([
                'status' => 400,
                'message' => 'Data not found.',
            ]);
        }
    }

    private function updateStoreStock($request, $product_data, $quantity)
    {
        Log::info("updateStoreStock");

        $store_stock_detail_exists = StoreStockUpdate::where([
            ['store_id', $request->from_store_id],
            ['product_id', $product_data->id],
            ['status', 1],
        ])->orderBy('id', 'DESC')->first();
        Log::info("store_stock_detail_exists");
        Log::info($store_stock_detail_exists);
        $store_stock_detail = new StoreStockUpdate();
        $store_stock_detail->fill([
            'store_id' => $request->from_store_id,
            'product_id' => $product_data->id,
            'reference_id' => $request->sales_order_return_id,
            'reference_table' => 3, // Sales Order return table
            'stock_update_on' => Carbon::now(),
            'existing_stock' => $store_stock_detail_exists->total_stock ?? 0,
            'adding_stock' => $quantity,
            'total_stock' => ($store_stock_detail_exists->total_stock ?? 0) + $quantity,
            'status' => 1,
        ]);
        $store_stock_detail->save();
        Log::info("store_stock_detail");
        Log::info($store_stock_detail);
        $store_inventory = StoreInventoryDetail::where([
            ['store_id', $request->from_store_id],
            ['product_id', $product_data->id],
            ['status', 1],
        ])->first() ?? new StoreInventoryDetail();
        Log::info("store_inventory");
        Log::info($store_inventory);
        $store_inventory->fill([
            'store_id' => $request->from_store_id,
            'product_id' => $product_data->id,
            'weight' => ($store_inventory->weight ?? 0) + $quantity,
            'status' => 1,
        ]);
        $store_inventory->save();
        Log::info("store_inventory updated");
        Log::info($store_inventory);
    }

    private function updateWarehouseStock($request, $product_data, $quantity)
    {
        Log::info("updateWarehouseStock");

        $warehouse_stock_detail_exists = WarehouseStockUpdate::where([
            ['warehouse_id', $request->to_warehouse_id],
            ['product_id', $product_data->id],
            ['status', 1],
        ])->orderBy('id', 'DESC')->first();
        Log::info("warehouse_stock_detail_exists");
        Log::info($warehouse_stock_detail_exists);
        $warehouse_stock_detail = new WarehouseStockUpdate();
        $warehouse_stock_detail->fill([
            'warehouse_id' => $request->to_warehouse_id,
            'product_id' => $product_data->id,
            'stock_update_on' => Carbon::now(),
            'existing_stock' => $warehouse_stock_detail_exists->total_stock ?? 0,
            'adding_stock' => $quantity,
            'total_stock' => ($warehouse_stock_detail_exists->total_stock ?? 0) + $quantity,
            'status' => 1,
            'box_number' => $request->box_number ?? 1,
        ]);
        $warehouse_stock_detail->save();
        Log::info("warehouse_stock_detail");
        Log::info($warehouse_stock_detail);
        $warehouse_inventory = WarehouseInventoryDetail::where([
            ['warehouse_id', $request->to_warehouse_id],
            ['product_id', $product_data->id],
            ['status', 1],
        ])->first() ?? new WarehouseInventoryDetail();
        Log::info("warehouse_inventory");
        Log::info($warehouse_inventory);
        $warehouse_inventory->fill([
            'warehouse_id' => $request->to_warehouse_id,
            'product_id' => $product_data->id,
            'weight' => ($warehouse_inventory->weight ?? 0) + $quantity,
            'status' => 1,
        ]);
        $warehouse_inventory->save();
        Log::info("warehouse_inventory updated");
        Log::info($warehouse_inventory);
    }

    public function returnorderexpenseupdate(Request $request)
    {
        DB::beginTransaction();
        // try {
        $sales_order_return_id = $request->sales_order_return_id;
        $total_remove_exp_amount = SalesOrderReturnExpense::where("sales_order_return_id", $sales_order_return_id)->where('is_billable', 1)->sum('ie_amount');
        $total_unbill_amount = SalesOrderReturnExpense::where("sales_order_return_id", $sales_order_return_id)->where('is_billable', 0)->sum('ie_amount');

        // Expense Docs Delete
        if (isset($request->deleted_expense_doc_ids) && count(json_decode($request->deleted_expense_doc_ids)) > 0) {
            foreach (json_decode($request->deleted_expense_doc_ids) as $key => $value) {
                if ($value) {
                    $sales_order_docs = PurchaseSalesDocument::findOrFail($value);
                    CommonComponent::s3BucketFileDelete($sales_order_docs->file, $sales_order_docs->file_path);

                    $sales_order_docs->delete();
                }
            }
        }

        // Expense Docs Store
        if (isset($request->expense_documents) && count($request->expense_documents) > 0 && $request->file('expense_documents')) {
            foreach ($request->file('expense_documents') as $key => $value) {
                if ($value) {
                    $imageData = CommonComponent::s3BucketFileUpload($value, 'purchase_order_expense');
                    $imagePath = $imageData['filePath'];
                    // $imageUrl = $imageData['fileName'];
                    $imageUrl = $imageData['imageURL'];

                    $sales_order_docs = new PurchaseSalesDocument();
                    $sales_order_docs->type = 3; // Return Order
                    $sales_order_docs->reference_id = $sales_order_return_id;
                    $sales_order_docs->document_type = 1; // Expense
                    $sales_order_docs->file = @$imageUrl;
                    $sales_order_docs->file_path = @$imagePath;
                    $sales_order_docs->save();
                }
            }
        }

        // Expense Data Delete
        if (isset($request->deleted_expense_ids) && count(json_decode($request->deleted_expense_ids)) > 0) {
            SalesOrderReturnExpense::destroy(json_decode($request->deleted_expense_ids));
        }

        // Expense Data Store
        $total_expense_amount = 0;
        $total_expense_billable_amount = 0;
        if (isset($request->expense)) {
            $expense = json_decode($request->expense);
            if (count($expense) > 0) {
                foreach ($expense as $expense_key => $exp) {
                    if (isset($exp->id)) {
                        $sales_expense = SalesOrderReturnExpense::where('id', $exp->id)->first();
                    } else {
                        $sales_expense = new SalesOrderReturnExpense();
                    }
                    $sales_expense->sales_order_return_id = $sales_order_return_id;
                    $sales_expense->income_expense_id = @$exp->expense_type_id;
                    $sales_expense->ie_amount = @$exp->expense_amount;
                    $sales_expense->is_billable = @$exp->is_billable;
                    if ($exp->is_billable == 1) {
                        $total_expense_billable_amount += $exp->expense_amount;
                    }
                    $total_expense_amount += $exp->expense_amount;
                    $sales_expense->save();
                }

                $sales_order_return = SalesOrderReturn::findOrFail($sales_order_return_id);
                $sales_order_return->total_expense_billable_amount = $total_expense_billable_amount;
                $sales_order_return->total_expense_amount = $total_expense_amount;
                $sales_order_return->total_amount = ($sales_order_return->total_amount - $total_remove_exp_amount) + $total_expense_billable_amount;
                $sales_order_return->save();

                if ($total_expense_billable_amount > 0) {
                    // $payment_transaction = new PaymentTransaction();
                    // $payment_transaction->transaction_number = CommonComponent::invoice_no('payment_transaction'); // Auto Generate
                    // $payment_transaction->transaction_type = 3; // Return Order
                    // $payment_transaction->type = 2; // Debit
                    // $payment_transaction->reference_id = $sales_order_return_id;
                    // $payment_transaction->payment_type_id = 1;
                    // $payment_transaction->amount = $total_expense_billable_amount;
                    // $payment_transaction->transaction_datetime = Carbon::now();
                    // $payment_transaction->status = 1;
                    // $payment_transaction->save();

                    $sales_return_details = SalesOrderReturn::with('return_order_transactions')->findOrFail($sales_order_return_id);

                    $paid_amount = $sales_return_details->return_order_transactions->sum('amount');

                    $total_amount = $sales_return_details->total_amount;

                    if ($paid_amount == 0) {
                        $sales_return_details->payment_status = $request->payment_status != null ? $request->payment_status : 2; // UnPaid
                        $sales_return_details->save();
                    } elseif ($paid_amount < $total_amount) {
                        $sales_return_details->payment_status = $request->payment_status != null ? $request->payment_status : 3; // Pending
                        $sales_return_details->save();
                    } elseif ($paid_amount >= $total_amount) {
                        $sales_return_details->payment_status = $request->payment_status != null ? $request->payment_status : 1; // Paid
                        $sales_return_details->save();
                    }
                }
            }
        } else {
            $sales_order_return = SalesOrderReturn::findOrFail($sales_order_return_id);
            $sales_order_return->total_expense_billable_amount = 0;
            $sales_order_return->total_expense_amount = 0;
            $sales_order_return->total_amount = ($sales_order_return->total_amount - $total_remove_exp_amount - $total_unbill_amount);
            $sales_order_return->save();
        }
        DB::commit();

        return response()->json([
            'status' => 200,
            'message' => 'Return Order Expense Updated successfully.',
        ]);
        // } catch (\Exception $e) {
        //     Log::error($e);
        //     DB::rollback();
        //     return response()->json([
        //         'status' => 400,
        //         'message' => 'Data not found.',
        //     ]);
        // }
    }

    public function returnordertransporttrackingupdate(Request $request)
    {
        DB::beginTransaction();
        // try {
        $sales_order_return_id = $request->sales_order_return_id;
        // Transaport Tracking Docs Delete
        if (isset($request->deleted_tracking_doc_ids) && count(json_decode($request->deleted_tracking_doc_ids)) > 0) {
            foreach (json_decode($request->deleted_tracking_doc_ids) as $key => $value) {
                if ($value) {
                    $purchase_order_docs = PurchaseSalesDocument::findOrFail($value);
                    CommonComponent::s3BucketFileDelete($purchase_order_docs->file, $purchase_order_docs->file_path);

                    $purchase_order_docs->delete();
                }
            }
        }

        // Transaport Tracking Docs Store
        if (isset($request->transport_tracking_files) && count($request->transport_tracking_files) > 0 && $request->file('transport_tracking_files')) {
            foreach ($request->file('transport_tracking_files') as $key => $value) {
                if ($value) {
                    $imagePath = null;
                    $imageUrl = null;
                    $imageData = CommonComponent::s3BucketFileUpload($value, 'sales_order_return_transport');
                    $imagePath = $imageData['filePath'];
                    // $imageUrl = $imageData['fileName'];
                    $imageUrl = $imageData['imageURL'];

                    $purchase_order_docs = new PurchaseSalesDocument();
                    $purchase_order_docs->type = 3; // Return Order
                    $purchase_order_docs->reference_id = $sales_order_return_id;
                    $purchase_order_docs->document_type = 2; // Transport Tracking
                    $purchase_order_docs->file = @$imageUrl;
                    $purchase_order_docs->file_path = @$imagePath;
                    $purchase_order_docs->save();
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
                    $transport_tracking->sales_order_return_id = $sales_order_return_id;
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
            'message' => 'Return Order Transport Tracking Updated successfully.',
        ]);
        // } catch (\Exception $e) {
        //     Log::error($e);
        //     DB::rollback();
        //     return response()->json([
        //         'status' => 400,
        //         'message' => 'Data not found.',
        //     ]);
        // }
    }

    public function returnordertransactions(Request $request)
    {
        DB::beginTransaction();
        // try {
        $sales_order_return_id = $request->sales_order_return_id;

        $payment_details = json_decode($request->payment_details);
        foreach ($payment_details as $key => $payment_detail) {
            $payment_transaction = new PaymentTransaction();
            $payment_transaction->transaction_number = CommonComponent::invoice_no('payment_transaction'); // Auto Generate
            $payment_transaction->transaction_type = 3; // Return Order
            $payment_transaction->type = 1; // Credit
            $payment_transaction->reference_id = $sales_order_return_id;
            $payment_transaction->payment_type_id = $payment_detail->payment_type_id;
            $payment_transaction->amount = $payment_detail->amount;
            $payment_transaction->transaction_datetime = $payment_detail->transaction_datetime;
            $payment_transaction->status = 1;
            $payment_transaction->note = @$payment_detail->note;
            $payment_transaction->save();

            Log::info($request->payment_transaction_documents);
            if (isset($request->payment_transaction_documents) && count($request->payment_transaction_documents) > 0 && $request->file('payment_transaction_documents')) {
                CommonComponent::payment_transaction_documents($request->file('payment_transaction_documents'), 4, $payment_transaction->id); // 2=> Return
            }
        }

        $sales_return_details = SalesOrderReturn::with('return_order_transactions')->findOrFail($sales_order_return_id);

        $paid_amount = $sales_return_details->return_order_transactions->sum('amount');

        $total_amount = $sales_return_details->total_amount;

        if ($paid_amount == 0) {
            $sales_return_details->payment_status = $request->payment_status != null ? $request->payment_status : 2; // UnPaid
            $sales_return_details->save();
        } elseif ($paid_amount < $total_amount) {
            $sales_return_details->payment_status = $request->payment_status != null ? $request->payment_status : 3; // Pending
            $sales_return_details->save();
        } elseif ($paid_amount >= $total_amount) {
            $sales_return_details->payment_status = $request->payment_status != null ? $request->payment_status : 1; // Paid
            $sales_return_details->save();
        }

        DB::commit();
        return response()->json([
            'status' => 200,
            'data' => $sales_return_details,
            'message' => 'Transaction Stored successfully.',
        ]);
        // } catch (\Exception $e) {
        //     Log::error($e);
        //     DB::rollback();
        //     return response()->json([
        //         'status' => 400,
        //         'message' => 'Data not found.',
        //     ]);
        // }
    }

    public function returnpaymenttransactionedit(Request $request)
    {
        try {
            $transaction_id = $request->transaction_id;

            $payment_transaction = PaymentTransaction::with('payment_transaction_documents')->findOrFail($transaction_id);

            return response()->json([
                'status' => 200,
                'data' => $payment_transaction,
                'message' => 'Payment Transaction Updated Successfully.',
            ]);
        } catch (\Exception $e) {
            Log::error($e);
            DB::rollback();
            return response()->json([
                'status' => 400,
                'message' => 'Data not found.',
            ]);
        }
    }

    public function returnpaymenttransactionupdate(Request $request)
    {
        DB::beginTransaction();
        // try {
        $transaction_id = $request->transaction_id;

        if (isset($request->payment_details)) {
            $payment_details = json_decode($request->payment_details);
            foreach ($payment_details as $key => $payment_detail) {
                $payment_transaction = PaymentTransaction::findOrFail($transaction_id);
                $payment_transaction->payment_type_id = (int) $payment_detail->payment_type_id;
                $payment_transaction->amount = $payment_detail->amount;
                $payment_transaction->transaction_datetime = $payment_detail->transaction_datetime;
                $payment_transaction->status = 1;
                $payment_transaction->note = @$payment_detail->note;
                $payment_transaction->save();
            }
        }

        Log::info($request->payment_transaction_documents);
        if (isset($request->payment_transaction_documents) && count($request->payment_transaction_documents) > 0 && $request->file('payment_transaction_documents')) {
            CommonComponent::payment_transaction_documents($request->file('payment_transaction_documents'), 2, $payment_transaction->id); // 2=> Sales
        }

        $sales_return_details = SalesOrderReturn::with('return_order_transactions')->findOrFail($payment_transaction->reference_id);

        $paid_amount = $sales_return_details->return_order_transactions->sum('amount');

        $total_amount = $sales_return_details->total_amount;

        if ($paid_amount == 0) {
            $sales_return_details->payment_status = $request->payment_status != null ? $request->payment_status : 2; // UnPaid
            $sales_return_details->save();
        } elseif ($paid_amount < $total_amount) {
            $sales_return_details->payment_status = $request->payment_status != null ? $request->payment_status : 3; // Pending
            $sales_return_details->save();
        } elseif ($paid_amount >= $total_amount) {
            $sales_return_details->payment_status = $request->payment_status != null ? $request->payment_status : 1; // Paid
            $sales_return_details->save();
        }

        DB::commit();

        $sales_return_details = SalesOrderReturn::with('return_order_transactions')->findOrFail($payment_transaction->reference_id);

        return response()->json([
            'status' => 200,
            'data' => $sales_return_details,
            'message' => 'Transaction Updated successfully.',
        ]);
        // } catch (\Exception $e) {
        //     Log::error($e);
        //     DB::rollback();
        //     return response()->json([
        //         'status' => 400,
        //         'message' => 'Data not found.',
        //     ]);
        // }
    }

    public function returnpaymenttransactiondelete(Request $request)
    {
        // try {
        $transaction_id = $request->transaction_id;

        $sales_order_return_id = $request->sales_order_return_id;

        PaymentTransactionDocument::where('reference_id', $transaction_id)->delete();

        PaymentTransaction::destroy($transaction_id);

        $sales_return_details = SalesOrderReturn::with('return_order_transactions')->findOrFail($sales_order_return_id);

        $paid_amount = $sales_return_details->return_order_transactions->sum('amount');

        $total_amount = $sales_return_details->total_amount;

        if ($paid_amount == 0) {
            $sales_return_details->payment_status = $request->payment_status != null ? $request->payment_status : 2; // UnPaid
            $sales_return_details->save();
        } elseif ($paid_amount < $total_amount) {
            $sales_return_details->payment_status = $request->payment_status != null ? $request->payment_status : 3; // Pending
            $sales_return_details->save();
        } elseif ($paid_amount >= $total_amount) {
            $sales_return_details->payment_status = $request->payment_status != null ? $request->payment_status : 1; // Paid
            $sales_return_details->save();
        }

        $sales_return_details = SalesOrderReturn::with('return_order_transactions')->findOrFail($sales_order_return_id);

        return response()->json([
            'status' => 200,
            'datas' => $sales_return_details,
            'message' => 'Payment Transaction Deleted Successfully.',
        ]);
        // } catch (\Exception $e) {
        //     Log::error($e);
        //     DB::rollback();
        //     return response()->json([
        //         'status' => 400,
        //         'message' => 'Data not found.',
        //     ]);
        // }
    }
}
