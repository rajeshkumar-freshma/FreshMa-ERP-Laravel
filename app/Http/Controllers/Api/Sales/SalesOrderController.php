<?php

namespace App\Http\Controllers\Api\Sales;

use App\Core\CommonComponent;
use App\Http\Controllers\Controller;
use App\Models\FishCuttingProductMap;
use App\Models\Helper;
use App\Models\PaymentTransaction;
use App\Models\PaymentTransactionDocument;
use App\Models\Product;
use App\Models\PurchaseSalesDocument;
use App\Models\SaleOrderAction;
use App\Models\SalesExpense;
use App\Models\SalesOrder;
use App\Models\SalesOrderDetail;
use App\Models\SalesOrderReturn;
use App\Models\SalesOrderReturnDetail;
use App\Models\Store;
use App\Models\StoreInventoryDetail;
use App\Models\StoreStockUpdate;
use App\Models\TransportTracking;
use App\Models\User;
use App\Models\UserAdvance;
use App\Models\UserAdvanceHistory;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SalesOrderController extends Controller
{
    public function salesorderlist(Request $request)
    {
        try {
            $invoice_number = $request->invoice_number;
            $customer_id = $request->customer_id;
            $status = [];
            if ($request->status != null) {
                $status = array($request->status);
            }
            $retail_credit = 0;
            if ($request->payment_status == 4) { // Credit Sale
                $payment_status = [2, 3];
                $retail_credit = 1;
            } elseif ($request->payment_status != null) {
                $payment_status = array($request->payment_status);
            } else {
                $payment_status = [1, 2, 3];
            }

            $from_date = $request->from_date;
            $to_date = $request->to_date;

            if ($request->store_id != null) {
                $store_id = array($request->store_id);
            } else {
                $store_id = Auth::user()->user_stores();
            }

            $salesOrder_query = SalesOrder::where(function ($query) use ($invoice_number, $store_id, $payment_status, $from_date, $to_date, $customer_id, $status, $retail_credit) {
                if ($invoice_number != null) {
                    $query->where('invoice_number', 'LIKE', '%' . $invoice_number . '%')
                        ->OrWhere('bill_no', 'LIKE', '%' . $invoice_number . '%');
                }
                if ($store_id != null) {
                    $query->whereIn('store_id', $store_id);
                }
                if ($customer_id != null) {
                    $query->where('vendor_id', $customer_id);
                }
                if (count($status) > 0) {
                    $query->whereIn('status', $status);
                }
                if ($retail_credit == 1) {
                    $query->whereNotIn('status', [4]);
                }
                if ($payment_status != null) {
                    $query->whereIn('payment_status', $payment_status);
                }
                if ($from_date != null && $to_date != null) {
                    $dateformatwithtime = CommonComponent::dateformatwithtime($from_date, $to_date);
                    $query->whereBetween('delivered_date', $dateformatwithtime);
                }
            })

                ->whereIn('store_id', $store_id)
                ->with('warehouse:id,name,code', 'store:id,store_name,store_code,phone_number,gst_number', 'vendor:id,first_name,last_name,user_type,status')
                ->orderBy('id', 'DESC');

            $salesordercount = $salesOrder_query->count();
            $sales_amount_query = $salesOrder_query;
            $salesOrders = $salesOrder_query->paginate(15);

            if ($invoice_number != null) {
                $salesamount = $sales_amount_query->where('payment_status', 2)->sum('total_amount');
            } else {
                $salesamount = $sales_amount_query->sum('total_amount');
            }
            return response()->json([
                'status' => 200,
                'salesordercount' => $salesordercount,
                'salesamount' => $salesamount,
                'datas' => $salesOrders,
                'message' => 'Sales Order fetched successfully.',
            ]);
        } catch (\Exception $e) {
            Log::error($e);
            return response()->json([
                'status' => 400,
                'message' => 'Data not found.',
            ]);
        }
    }

    public function salesorderdetails(Request $request)
    {
        // try {
        $sales_id = $request->sales_id;

        $salesOrder = SalesOrder::with('warehouse:id,name,code', 'store:id,store_name,store_code,phone_number,gst_number', 'vendor:id,first_name,last_name,user_type,status')->findOrFail($sales_id);

        $salesOrderDetails = SalesOrderDetail::where('sales_order_id', $sales_id)->with('unit_details:id,unit_name,unit_short_code,allow_decimal,operator,operation_value')->get();

        $sales_expense_details = SalesExpense::where('sales_order_id', $sales_id)->with('income_expense_details')->get();
        $sales_expense_docs = PurchaseSalesDocument::where([['type', 2], ['reference_id', $sales_id], ['document_type', 1]])->get();
        $sales_transport_trackings = TransportTracking::where('sales_order_id', $sales_id)->with('transport_type_details')->get();
        $sales_transport_trackings_docs = PurchaseSalesDocument::where([['type', 2], ['reference_id', $sales_id], ['document_type', 2]])->get();
        $sales_order_transactions = PaymentTransaction::where([['transaction_type', 2], ['reference_id', $sales_id]])->with('payment_type_details', 'payment_transaction_documents')->get();

        $paid_amount = $sales_order_transactions->sum('amount');
        $total_amount = $salesOrder->total_amount;
        $due_amount = ($total_amount - $paid_amount);
        $due_amount = $due_amount > 0 ? $due_amount : 0;

        return response()->json([
            'status' => 200,
            'datas' => $salesOrder,
            'salesOrderDetails' => $salesOrderDetails,
            'sales_expense_details' => $sales_expense_details,
            'sales_expense_docs' => $sales_expense_docs,
            'sales_transport_trackings' => $sales_transport_trackings,
            'sales_transport_trackings_docs' => $sales_transport_trackings_docs,
            'sales_order_transactions' => $sales_order_transactions,
            'paid_amount' => round($paid_amount, 2),
            'total_amount' => round($total_amount, 2),
            'due_amount' => round($due_amount, 2),
            'message' => 'Sales Details fetched successfully.',
        ]);
        // } catch (\Exception $e) {
        //     Log::error($e);
        //     return response()->json([
        //         'status' => 400,
        //         'message' => 'Data not found.',
        //     ]);
        // }
    }

    public function salesorderstore(Request $request)
    {
        DB::beginTransaction();
        // try {
        $imagePath = null;
        $imageUrl = null;

        if ($request->hasFile('file')) {
            $imageData = CommonComponent::s3BucketFileUpload($request->file, 'sales_order');
            $imagePath = $imageData['filePath'];
            // $imageUrl = $imageData['fileName'];
            $imageUrl = $imageData['imageURL'];
        }

        $store_data = Store::findOrfail($request->store_id);

        $indent_request = new SalesOrder();
        $indent_request->sales_from = 2; // warehouse
        $indent_request->sales_type = 2; // ERP Sale
        $indent_request->warehouse_id = $store_data->warehouse_id;
        $indent_request->store_id = $request->store_id;
        if ($request->vendor_id != null && $request->vendor_id != "" && $request->vendor_id != "null") {
            $indent_request->vendor_id = $request->vendor_id;
        }
        if ($request->quatation_id != null && $request->quatation_id != "" && $request->quatation_id != "null") {
            $indent_request->quatation_id = $request->quatation_id;
        }
        $indent_request->invoice_number = $request->invoice_number;
        $indent_request->delivered_date = $request->delivered_date;
        $indent_request->status = $request->status;
        $indent_request->sub_total = $request->sub_total;
        $indent_request->total_amount = $request->total_amount != null ? $request->total_amount : $request->sub_total;
        $indent_request->remarks = $request->remarks != null ? $request->remarks : null;
        $indent_request->is_inc_exp_billable_for_all = $request->is_inc_exp_billable_for_all != null ? $request->is_inc_exp_billable_for_all : null;
        if ($imageUrl != null) {
            $indent_request->file = $imageUrl;
            $indent_request->file_path = $imagePath;
        }
        $indent_request->discount_type = $request->discount_type;
        $indent_request->discount_percentage = $request->discount_percentage;
        $indent_request->discount_amount = $request->discount_amount;
        $indent_request->adjustment_amount = $request->adjustment_amount;
        $indent_request->save();

        if (isset($request->products)) {
            $products = json_decode($request->products);
            if (count($products) > 0) {
                foreach ($products as $key => $product) {
                    $product_data = Product::findOrfail($product->product_id);
                    $indent_request_detail = new SalesOrderDetail();
                    $indent_request_detail->sales_order_id = $indent_request->id;
                    $indent_request_detail->product_id = $product_data->id;
                    $indent_request_detail->sku_code = $product_data->sku_code;
                    $indent_request_detail->name = $product_data->name;
                    $indent_request_detail->unit_id = @$product->unit_id;
                    $indent_request_detail->request_quantity = @$product->request_quantity;
                    $indent_request_detail->given_quantity = @$product->quantity;
                    $indent_request_detail->per_unit_price = $product->amount;
                    $indent_request_detail->amount = @$product->sub_total;
                    $indent_request_detail->sub_total = @$product->sub_total;
                    $indent_request_detail->total = @$product->total != null ? @$product->total : @$product->sub_total;
                    $indent_request_detail->save();

                    $expression = 'subtraction';
                    CommonComponent::fishcuttingcalculation($expression, $product->quantity, $request->store_id, $product_data->id, $indent_request->id);

                    // $quantity = -$product->quantity;
                    // if ($quantity != 0) {
                    //     $store_stock_detail_exists = StoreStockUpdate::where([['store_id', $request->store_id], ['product_id', $product_data->id], ['status', 1]])->orderBy('id', 'DESC')->first();
                    //     $store_stock_detail = new StoreStockUpdate();
                    //     $store_stock_detail->from_warehouse_id = 1;
                    //     $store_stock_detail->store_id = $request->store_id;
                    //     $store_stock_detail->product_id = $product_data->id;
                    //     $store_stock_detail->stock_update_on = Carbon::now();
                    //     $store_stock_detail->existing_stock = ($store_stock_detail_exists != null && $store_stock_detail_exists->total_stock != null) ? $store_stock_detail_exists->total_stock : 0;
                    //     $store_stock_detail->adding_stock = @$quantity;
                    //     $store_stock_detail->total_stock = ($store_stock_detail_exists != null && $store_stock_detail_exists->total_stock != null) ? $store_stock_detail_exists->total_stock + @$quantity : @$quantity;
                    //     $store_stock_detail->status = 1;
                    //     $store_stock_detail->save();
                    // }

                    // $store_stock_detail = StoreStockUpdate::where([['store_id', $request->store_id], ['product_id', $product_data->id], ['status', 1]])->orderBy('id', 'DESC')->first();
                    // $store_inventory = StoreInventoryDetail::where([['store_id', $request->store_id], ['product_id', $product_data->id], ['status', 1]])->first();
                    // if ($store_inventory == null) {
                    //     $store_inventory = new StoreInventoryDetail();
                    //     $store_inventory->store_id = $request->store_id;
                    //     $store_inventory->product_id = $product_data->id;
                    // }
                    // $store_inventory->weight = @$store_inventory->weight + @$quantity;
                    // $store_inventory->status = 1;
                    // $store_inventory->save();

                    // $fishcutting = FishCuttingDetail::where('product_id', $product_data->id)->orderbyDesc('id')->first();
                    // if ($fishcutting != null) {
                    //     $grouped_products = json_decode($fishcutting->grouped_product);
                    //     $slice = ($product->quantity * $fishcutting->slice_percentage) / 100;
                    //     $head = ($product->quantity * $fishcutting->head_percentage) / 100;
                    //     if ($fishcutting->product_id == 147) { // seer egg, just set manually, need to change depending on SKU Code
                    //         $eggs = ($product->quantity * $fishcutting->eggs_percentage) / 100;
                    //     }
                    //     // $wastage = ($product->quantity * $fishcutting->wastage_percentage) / 100;
                    //     $quantity = 0;
                    //     foreach ($grouped_products as $key => $grouped_product) {
                    //         if ($grouped_product->type == 'slice') {
                    //             $quantity = -$slice;
                    //         } else if ($grouped_product->type == 'head') {
                    //             $quantity = -$head;
                    //         } else if ($grouped_product->type == 'eggs') {
                    //             $quantity = -$eggs;
                    //         }
                    //         if ($request->store_id != null && $request->store_id != "null") {
                    //             if ($quantity != 0) {
                    //                 $store_stock_detail_exists = StoreStockUpdate::where([['store_id', $request->store_id], ['product_id', $grouped_product->product_id], ['status', 1]])->orderBy('id', 'DESC')->first();
                    //                 $store_stock_detail = new StoreStockUpdate();
                    //                 $store_stock_detail->store_id = $request->store_id;
                    //                 $store_stock_detail->product_id = $grouped_product->product_id;
                    //                 $store_stock_detail->stock_update_on = Carbon::now();
                    //                 $store_stock_detail->existing_stock = ($store_stock_detail_exists != null && $store_stock_detail_exists->total_stock != null) ? $store_stock_detail_exists->total_stock : 0;
                    //                 $store_stock_detail->adding_stock = @$quantity;
                    //                 $store_stock_detail->total_stock = ($store_stock_detail_exists != null && $store_stock_detail_exists->total_stock != null) ? $store_stock_detail_exists->total_stock + @$quantity : @$quantity;
                    //                 $store_stock_detail->status = 1;
                    //                 $store_stock_detail->save();
                    //             }

                    //             $store_inventory = StoreInventoryDetail::where([['store_id', $request->store_id], ['product_id', $grouped_product->product_id], ['status', 1]])->first();
                    //             if ($store_inventory == null) {
                    //                 $store_inventory = new StoreInventoryDetail();
                    //                 $store_inventory->store_id = $request->store_id;
                    //                 $store_inventory->product_id = $grouped_product->product_id;
                    //             }
                    //             $store_inventory->weight = @$store_inventory->weight + @$quantity;
                    //             $store_inventory->status = 1;
                    //             $store_inventory->save();
                    //         }
                    //     }
                    // }
                }
            }
        }

        $payment_transaction = new PaymentTransaction();
        $payment_transaction->transaction_number = CommonComponent::invoice_no('payment_transaction'); // Auto Generate
        $payment_transaction->transaction_type = 2; // Sales Order
        $payment_transaction->type = 1; // Credit
        $payment_transaction->reference_id = $indent_request->id;
        $payment_transaction->amount = $request->paid_amount ? $request->paid_amount : $indent_request->total_amount;
        $payment_transaction->transaction_datetime = Carbon::now();
        $payment_transaction->status = 1;
        $payment_transaction->save();

        if (isset($request->payment_transaction_documents) && count($request->payment_transaction_documents) > 0 && $request->file('payment_transaction_documents')) {
            CommonComponent::payment_transaction_documents($request->file('payment_transaction_documents'), 2, $payment_transaction->id); // 2=> Sales
        }

        $firebaseToken = User::where('id', $indent_request->vendor_id)
            ->pluck('fcm_token')
            ->first();
        if ($firebaseToken != null) {
            Log::info("user_firebaseToken");
            Log::info($firebaseToken);
            $title = $indent_request->invoice_number;
            $content = 'Freshma - Order has been placed and your order ID is ' . $title;
            Helper::sendPushNotification($firebaseToken, $title, $content);
        }

        $request_action = new SaleOrderAction();
        $request_action->sales_order_id = $indent_request->id;
        $request_action->status = $request->status;
        $request_action->action_date = Carbon::now();
        $request_action->remarks = $request->remarks;
        $request_action->save();

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
        Log::info($request->hasFile('expense_documents'));
        if (isset($request->expense_documents) && count($request->expense_documents) > 0 && $request->hasFile('expense_documents')) {
            // $expense_documents = ($request->expense_documents);
            foreach ($request->file('expense_documents') as $key => $value) {
                if ($value) {
                    $imageData = CommonComponent::s3BucketFileUpload($value, 'sales_order_expense');
                    $imagePath = $imageData['filePath'];
                    // $imageUrl = $imageData['fileName'];
                    $imageUrl = $imageData['imageURL'];

                    $sales_order_docs = new PurchaseSalesDocument();
                    $sales_order_docs->type = 2; // Sales order
                    $sales_order_docs->reference_id = $indent_request->id;
                    $sales_order_docs->document_type = 1; // Expense
                    $sales_order_docs->file = @$imageUrl;
                    $sales_order_docs->file_path = @$imagePath;
                    $sales_order_docs->save();
                }
            }
        }

        // Expense Data Delete
        if (isset($request->deleted_expense_ids) && count(json_decode($request->deleted_expense_ids)) > 0) {
            SalesExpense::destroy(json_decode($request->deleted_expense_ids));
        }

        // Expense Data Store
        $total_expense_amount = 0;
        $total_billable_expense_amount = 0;
        if (isset($request->expense)) {
            $expense = json_decode($request->expense);
            if (count($expense) > 0) {
                foreach ($expense as $expense_key => $exp) {
                    if (isset($exp->id)) {
                        $sales_expense = SalesExpense::where("id", $exp->id)->first();
                    } else {
                        $sales_expense = new SalesExpense();
                    }
                    $sales_expense->sales_order_id = $indent_request->id;
                    $sales_expense->income_expense_id = @$exp->expense_type_id;
                    $sales_expense->ie_amount = @$exp->expense_amount;
                    $sales_expense->is_billable = @$exp->is_billable;
                    $sales_expense->save();

                    if ($exp->is_billable == 1) {
                        $total_billable_expense_amount = 0;
                    }
                    $total_expense_amount = 0;
                }
            }
        }

        // Transaport Tracking Docs Delete
        if (isset($request->deleted_tracking_doc_ids) && count(json_decode($request->deleted_tracking_doc_ids)) > 0) {
            foreach (json_decode($request->deleted_tracking_doc_ids) as $key => $value) {
                if ($value) {
                    $sales_order_tracking_docs = PurchaseSalesDocument::findOrFail($value);
                    CommonComponent::s3BucketFileDelete($sales_order_tracking_docs->file, $sales_order_tracking_docs->file_path);

                    $sales_order_tracking_docs->delete();
                }
            }
        }

        $imagePath = null;
        $imageUrl = null;
        // Transaport Tracking Docs Store
        if (isset($request->transport_tracking_files) && count($request->transport_tracking_files) > 0 && $request->hasFile('transport_tracking_files')) {
            // $transport_tracking_files = ($request->transport_tracking_files);
            foreach ($request->file('transport_tracking_files') as $key => $value) {
                if ($value) {
                    $imageData = CommonComponent::s3BucketFileUpload($value, 'sales_order_transport');
                    $imagePath = $imageData['filePath'];
                    // $imageUrl = $imageData['fileName'];
                    $imageUrl = $imageData['imageURL'];

                    $sales_order_tracking_docs = new PurchaseSalesDocument();
                    $sales_order_tracking_docs->type = 2; // Sales Order
                    $sales_order_tracking_docs->reference_id = $indent_request->id;
                    $sales_order_tracking_docs->document_type = 2; // Transport Tracking
                    $sales_order_tracking_docs->file = @$imageUrl;
                    $sales_order_tracking_docs->file_path = @$imagePath;
                    $sales_order_tracking_docs->save();
                }
            }
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
                    $transport_tracking->sales_order_id = $indent_request->id;
                    $transport_tracking->transport_type_id = $transport_tracking_detail->transport_type_id;
                    $transport_tracking->transport_name = $transport_tracking_detail->transport_name;
                    $transport_tracking->transport_number = $transport_tracking_detail->transport_number;
                    $transport_tracking->departure_datetime = $transport_tracking_detail->departure_datetime;
                    $transport_tracking->arriving_datetime = $transport_tracking_detail->arriving_datetime;
                    $transport_tracking->from_location = isset($transport_tracking_detail->from_location) ? $transport_tracking_detail->from_location : null;
                    $transport_tracking->to_location = isset($transport_tracking_detail->to_location) ? $transport_tracking_detail->to_location : null;
                    $transport_tracking->phone_number = isset($transport_tracking_detail->phone_number) ? $transport_tracking_detail->phone_number : null;
                    if ($imageUrl != null) {
                        $transport_tracking->file = $imageUrl;
                        $transport_tracking->file_path = $imagePath;
                    }
                    $transport_tracking->save();
                }
            }
        }

        DB::commit();

        return response()->json([
            'status' => 200,
            'datas' => $indent_request,
            'message' => 'Sales Order Added successfully.',
        ]);
        // } catch (\Exception $e) {
        //     Log::error($e);
        //     DB::rollback();
        //     return back()
        //         ->withInput()
        //         ->with('error', 'Sales Stored Fail');
        // }
    }

    public function salesorderupdate(Request $request)
    {
        DB::beginTransaction();
        // try {
        $sales_order_id = $request->sales_order_id;

        $indent_request = SalesOrder::findOrFail($sales_order_id);
        $imageUrl = $indent_request->imageUrl;
        $imagePath = $indent_request->imagePath;
        if ($request->hasFile('file')) {
            $imageData = CommonComponent::s3BucketFileUpload($request->file, 'sales_order');
            $imagePath = $imageData['filePath'];
            // $imageUrl = $imageData['fileName'];
            $imageUrl = $imageData['imageURL'];
        }

        $store_data = Store::findOrfail($request->store_id);
        $indent_request->sales_from = 2; // warehouse
        $indent_request->sales_type = 2; // ERP Sale
        $indent_request->warehouse_id = $store_data->warehouse_id;
        $indent_request->store_id = $request->store_id;
        $indent_request->vendor_id = $request->vendor_id;
        if ($request->quatation_id != null && $request->quatation_id != "" && $request->quatation_id != "null") {
            $indent_request->quatation_id = $request->quatation_id;
        }
        $indent_request->invoice_number = $request->invoice_number;
        $indent_request->delivered_date = $request->delivered_date;
        $indent_request->status = $request->status;
        $indent_request->sub_total = $request->sub_total;
        $indent_request->total_amount = $request->total_amount != null ? $indent_request->total_expense_billable_amount + $request->total_amount : $indent_request->total_expense_billable_amount + $request->sub_total;
        $indent_request->remarks = $request->remarks != null ? $request->remarks : null;
        if ($imageUrl != null) {
            $indent_request->file = $imageUrl;
            $indent_request->file_path = $imagePath;
        }
        $indent_request->discount_percentage = $request->discount_percentage;
        $indent_request->discount_amount = $request->discount_amount;
        $indent_request->adjustment_amount = $request->adjustment_amount;
        $indent_request->expected_payment_date = $request->expected_payment_date;
        $indent_request->save();

        if (isset($request->deleted_ids) && count(json_decode($request->deleted_ids)) > 0) {
            SalesOrderDetail::destroy(json_decode($request->deleted_ids));
        }

        if (isset($request->products)) {
            $products = json_decode($request->products);
            if (count($products) > 0) {
                foreach ($products as $key => $product) {
                    $product_data = Product::findOrfail($product->product_id);
                    $old_weight = 0;
                    if (isset($product->id)) {
                        $indent_request_detail = SalesOrderDetail::find($product->id);
                        // if ($indent_request_detail) {
                        $old_weight = $indent_request_detail->given_quantity;
                        // } else {
                        //     $indent_request_detail = new SalesOrderDetail();
                        // }
                    } else {
                        $indent_request_detail = new SalesOrderDetail();
                        $indent_request_detail->sales_order_id = $indent_request->id;
                    }
                    $indent_request_detail->sales_order_id = $indent_request->id;
                    $indent_request_detail->product_id = $product_data->id;
                    $indent_request_detail->sku_code = $product_data->sku_code;
                    $indent_request_detail->name = $product_data->name;
                    $indent_request_detail->unit_id = @$product->unit_id;
                    $indent_request_detail->given_quantity = @$product->quantity;
                    $indent_request_detail->request_quantity = @$product->request_quantity;
                    $indent_request_detail->per_unit_price = $product->amount;
                    $indent_request_detail->amount = @$product->sub_total;
                    $indent_request_detail->sub_total = @$product->sub_total;
                    $indent_request_detail->total = (isset($product->total) && $product->total != null) ? $product->total : $product->sub_total;
                    $indent_request_detail->save();

                    // $quantity = $indent_request_detail->given_quantity;
                    $not_equal_qnty = $indent_request_detail->given_quantity < $old_weight;
                    if ($not_equal_qnty != null) {
                        // $warehouse_stock_detail_exists = WarehouseStockUpdate::where([['warehouse_id', $indent_request->warehouse_id], ['product_id', $product_data->id], ['status', 1]])->orderBy('id', 'DESC')->first();
                        // $warehouse_stock_detail = new WarehouseStockUpdate();
                        // $warehouse_stock_detail->warehouse_id = $indent_request->warehouse_id;
                        // $warehouse_stock_detail->product_id = $product_data->id;
                        // $warehouse_stock_detail->stock_update_on = Carbon::now();
                        // $warehouse_stock_detail->existing_stock = $warehouse_stock_detail_exists ? $warehouse_stock_detail_exists->total_stock : 0;
                        // $warehouse_stock_detail->adding_stock = 0;
                        // $warehouse_stock_detail->total_stock = $warehouse_stock_detail->existing_stock - $quantity;
                        // $warehouse_stock_detail->status = 1;
                        // $warehouse_stock_detail->box_number = 1;
                        // $warehouse_stock_detail->save();

                        // $warehouse_inventory = WarehouseInventoryDetail::where([['warehouse_id', $indent_request->warehouse_id], ['product_id', $product_data->id], ['status', 1]])->first();
                        // if ($warehouse_inventory == null) {
                        //     $warehouse_inventory = new StoreInventoryDetail();
                        //     $warehouse_inventory->store_id = $indent_request->store_id;
                        //     $warehouse_inventory->product_id = $product_data->id;
                        // }
                        // $warehouse_inventory->weight = @$warehouse_inventory->weight - @$quantity;
                        // $warehouse_inventory->status = 1;
                        // $warehouse_inventory->save();

                        // if ($request->store_id != null && $request->store_id != "null") {
                        //     $store_stock_detail_exists = StoreStockUpdate::where([['store_id', $indent_request->store_id], ['product_id', $product_data->id], ['status', 1]])->orderBy('id', 'DESC')->first();
                        //     $store_stock_detail = new StoreStockUpdate();
                        //     $store_stock_detail->store_id = $indent_request->store_id;
                        //     $store_stock_detail->product_id = $product_data->id;
                        //     $store_stock_detail->reference_id = $indent_request->id;
                        //     $store_stock_detail->reference_table = 2; //Sales order table
                        //     $store_stock_detail->stock_update_on = Carbon::now();
                        //     $store_stock_detail->existing_stock = ($store_stock_detail_exists != null && $store_stock_detail_exists->total_stock != null) ? $store_stock_detail_exists->total_stock : 0;
                        //     $store_stock_detail->adding_stock = @$quantity;
                        //     $store_stock_detail->total_stock = ($store_stock_detail_exists != null && $store_stock_detail_exists->total_stock != null) ? $store_stock_detail_exists->total_stock + @$quantity : @$quantity;
                        //     $store_stock_detail->status = 1;
                        //     $store_stock_detail->save();

                        //     $store_stock_detail = StoreStockUpdate::where([['store_id', $indent_request->store_id], ['product_id', $product_data->id], ['status', 1]])->orderBy('id', 'DESC')->first();
                        //     $store_inventory = StoreInventoryDetail::where([['store_id', $indent_request->store_id], ['product_id', $product_data->id], ['status', 1]])->first();
                        //     if ($store_inventory == null) {
                        //         $store_inventory = new StoreInventoryDetail();
                        //         $store_inventory->store_id = $indent_request->store_id;
                        //         $store_inventory->product_id = $product_data->id;
                        //     }
                        //     $store_inventory->weight = @$store_inventory->weight + @$quantity;
                        //     $store_inventory->status = 1;
                        //     $store_inventory->save();
                        // }
                        $stock_update_weight = $old_weight - $indent_request_detail->given_quantity;
                        $unit_price = $indent_request_detail->amount / $indent_request_detail->given_quantity;
                        $per_product_amt = $stock_update_weight * $unit_price;

                        // if ($not_equal_qnty) {
                        $sales_order_return = new SalesOrderReturn();
                        $sales_order_return->sales_order_return_number = CommonComponent::invoice_no('sales_order_return');
                        $sales_order_return->return_from = 2; // 1 store return 2 is vendor return
                        // $sales_order_return->to_warehouse_id = $indent_request->warehouse_id;

                        if (!is_null($sales_order_id) && $sales_order_id !== 'null') {
                            $sales_details = SalesOrder::select('warehouse_id', 'store_id', 'vendor_id')->find($sales_order_id);
                            $sales_order_return->from_vendor_id = $sales_details->vendor_id;
                            $sales_order_return->from_store_id = $sales_details->store_id;
                        }

                        $sales_order_return->from_store_id = $request->store_id ?? $sales_order_return->from_store_id;
                        $sales_order_return->from_vendor_id = $request->vendor_id ?? $sales_order_return->from_vendor_id;
                        $sales_order_return->sales_order_id = $sales_order_id;
                        $sales_order_return->return_type = 2; // partialy return type
                        $sales_order_return->return_date = Carbon::now();
                        $sales_order_return->sub_total = $per_product_amt;
                        $sales_order_return->round_off_amount = $request->round_off_amount;
                        $sales_order_return->adjustment_amount = $request->adjustment_amount;
                        $sales_order_return->total_amount = $per_product_amt;
                        $sales_order_return->payment_status = $request->payment_status ?? 2; // Unpaid
                        $sales_order_return->status = 1; //while edit the sales order qnty comes below the old qnty manually update the status into 1, it means Requested
                        $sales_order_return->is_same_day_return = $request->is_same_day_return;
                        $sales_order_return->save();

                        // Product Details store
                        $products = $request->products;
                        $sales_order_return_product_detail = new SalesOrderReturnDetail();
                        $sales_order_return_product_detail->sales_order_return_id = $sales_order_return->id;
                        $sales_order_return_product_detail->product_id = $product_data->id;
                        $sales_order_return_product_detail->sku_code = $product_data->sku_code;
                        $sales_order_return_product_detail->name = $product_data->name;
                        $sales_order_return_product_detail->per_unit_price = $product->amount ?? 0;
                        $sales_order_return_product_detail->quantity = $stock_update_weight;
                        $sales_order_return_product_detail->unit_id = $product->unit_id ?? null;
                        $sales_order_return_product_detail->total = $per_product_amt;
                        $sales_order_return_product_detail->status = 10;
                        $sales_order_return_product_detail->save();

                        // if (($sales_order_return_product_detail->status == 10 && $sales_order_return->status == 10)|| ($request->from_store_id != null && $request->from_store_id != 'null')) {
                        //     $stock_update_weight = $sales_order_return_product_detail->quantity + $old_weight;
                        //     $warehouse_stock_detail_exists = WarehouseStockUpdate::where([['warehouse_id', $request->to_warehouse_id], ['product_id', $product_data->id], ['status', 1]])->first();
                        //     $warehouse_stock_detail = new WarehouseStockUpdate();
                        //     $warehouse_stock_detail->warehouse_id = $request->to_warehouse_id;
                        //     $warehouse_stock_detail->product_id = $product_data->id;
                        //     $warehouse_stock_detail->stock_update_on = Carbon::now();
                        //     $warehouse_stock_detail->existing_stock = (isset($warehouse_stock_detail_exists) && $warehouse_stock_detail_exists->total_stock != null) ? $warehouse_stock_detail_exists->total_stock : 0;
                        //     $warehouse_stock_detail->adding_stock = $stock_update_weight;
                        //     $warehouse_stock_detail->total_stock = (isset($warehouse_stock_detail_exists) && $warehouse_stock_detail_exists->total_stock != null) ? $warehouse_stock_detail_exists->total_stock + @$stock_update_weight : @$stock_update_weight;
                        //     $warehouse_stock_detail->status = 1;
                        //     $warehouse_stock_detail->box_number = $request->box_number != null ? $request->box_number : 1;
                        //     $warehouse_stock_detail->save();

                        //     $warehouse_inventory = WarehouseInventoryDetail::where([['warehouse_id', $request->to_warehouse_id], ['product_id', $product_data->id], ['status', 1]])->first();
                        //     if ($warehouse_inventory == null) {
                        //         $warehouse_inventory = new WarehouseInventoryDetail();
                        //         $warehouse_inventory->warehouse_id = $request->to_warehouse_id;
                        //         $warehouse_inventory->product_id = $product_data->id;
                        //     }
                        //     $warehouse_inventory->weight = @$warehouse_inventory->weight + $warehouse_stock_detail->adding_stock;
                        //     $warehouse_inventory->status = 1;
                        //     $warehouse_inventory->save();
                        // }

                        // if ($stock_update_weight != 0) {

                        if (($stock_update_weight != 0) && ($sales_order_return_product_detail->status == 10 && $sales_order_return->status == 10) && (!is_null($request->vendor_id) && $request->vendor_id !== 'null')) {
                            $fishcutting = FishCuttingProductMap::where('main_product_id', $product_data->id)->orderbyDesc('id')->first();
                            if ($fishcutting != null) {
                                $grouped_products = json_decode($fishcutting->grouped_product);

                                $quantity = 0;
                                foreach ($grouped_products as $key => $grouped_product) {
                                    $quantity = ($stock_update_weight * $grouped_product->percentage) / 100;
                                    // $wastage = ($stock_update_weight * $fishcutting->wastage_percentage) / 100;
                                    // if ($grouped_product->type == 'slice') {
                                    //     $quantity = $slice;
                                    // } else if ($grouped_product->type == 'head') {
                                    //     $quantity = $head;
                                    // }
                                    if ($request->store_id != null && $request->store_id != "null") {
                                        if ($quantity != 0) {
                                            $store_stock_detail_exists = StoreStockUpdate::where([['store_id', $request->store_id], ['product_id', $grouped_product->product_id], ['status', 1]])->orderBy('id', 'DESC')->first();
                                            $store_stock_detail = new StoreStockUpdate();
                                            $store_stock_detail->store_id = $request->store_id;
                                            $store_stock_detail->product_id = $grouped_product->product_id;
                                            $store_stock_detail->reference_id = $indent_request->id;
                                            $store_stock_detail->reference_table = 2; //2 Sales Order table
                                            $store_stock_detail->stock_update_on = Carbon::now();
                                            $store_stock_detail->existing_stock = ($store_stock_detail_exists != null && $store_stock_detail_exists->total_stock != null) ? $store_stock_detail_exists->total_stock : 0;
                                            $store_stock_detail->adding_stock = @$quantity;
                                            $store_stock_detail->total_stock = ($store_stock_detail_exists != null && $store_stock_detail_exists->total_stock != null) ? $store_stock_detail_exists->total_stock+@$quantity : @$quantity;
                                            $store_stock_detail->status = 1;
                                            $store_stock_detail->save();

                                            $store_inventory = StoreInventoryDetail::where([['store_id', $request->store_id], ['product_id', $grouped_product->product_id], ['status', 1]])->first();
                                            if ($store_inventory == null) {
                                                $store_inventory = new StoreInventoryDetail();
                                                $store_inventory->store_id = $request->store_id;
                                                $store_inventory->product_id = $grouped_product->product_id;
                                            }
                                            $store_inventory->weight = @$store_inventory->weight+@$quantity;
                                            $store_inventory->status = 1;
                                            $store_inventory->save();
                                        }
                                    }
                                }
                            } else {
                                $store_stock_detail_exists = StoreStockUpdate::where([['store_id', $request->store_id], ['product_id', $product_data->id], ['status', 1]])->orderBy('id', 'DESC')->first();
                                $store_stock_detail = new StoreStockUpdate();
                                $store_stock_detail->store_id = $request->store_id;
                                $store_stock_detail->product_id = $product_data->id;
                                $store_stock_detail->reference_id = $indent_request->id;
                                $store_stock_detail->reference_table = 2; //2 Sales Order table
                                $store_stock_detail->stock_update_on = Carbon::now();
                                $store_stock_detail->existing_stock = ($store_stock_detail_exists != null && $store_stock_detail_exists->total_stock != null) ? $store_stock_detail_exists->total_stock : 0;
                                $store_stock_detail->adding_stock = @$stock_update_weight;
                                $store_stock_detail->total_stock = ($store_stock_detail_exists != null && $store_stock_detail_exists->total_stock != null) ? $store_stock_detail_exists->total_stock+@$stock_update_weight : @$stock_update_weight;
                                $store_stock_detail->status = 1;
                                $store_stock_detail->save();

                                $store_inventory = StoreInventoryDetail::where([['store_id', $request->store_id], ['product_id', $product_data->id], ['status', 1]])->first();
                                if ($store_inventory == null) {
                                    $store_inventory = new StoreInventoryDetail();
                                    $store_inventory->store_id = $request->store_id;
                                    $store_inventory->product_id = $product_data->id;
                                }
                                $store_inventory->weight = @$stock_update_weight;
                                $store_inventory->status = 1;
                                $store_inventory->save();
                            }
                        }
                        // }
                    }
                }
            }
        }

        $request_action = new SaleOrderAction();
        $request_action->sales_order_id = $indent_request->id;
        $request_action->status = $request->status;
        $request_action->action_date = Carbon::now();
        $request_action->remarks = $request->remarks;
        $request_action->save();

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
                    $sales_order_docs->type = 2; // Sales Order
                    $sales_order_docs->reference_id = $sales_order_id;
                    $sales_order_docs->document_type = 1; // Expense
                    $sales_order_docs->file = @$imageUrl;
                    $sales_order_docs->file_path = @$imagePath;
                    $sales_order_docs->save();
                }
            }
        }

        // Expense Data Delete
        if (isset($request->deleted_expense_ids) && count(json_decode($request->deleted_expense_ids)) > 0) {
            SalesExpense::destroy(json_decode($request->deleted_expense_ids));
        }

        // Expense Data Store
        if (isset($request->expense)) {
            $expense = json_decode($request->expense);
            if (count($expense) > 0) {
                foreach ($expense as $expense_key => $exp) {
                    if (isset($exp->id)) {
                        $sales_expense = SalesExpense::where("id", $exp->id)->first();
                    } else {
                        $sales_expense = new SalesExpense();
                    }
                    $sales_expense->sales_order_id = $sales_order_id;
                    $sales_expense->income_expense_id = @$exp->expense_type_id;
                    $sales_expense->ie_amount = @$exp->expense_amount;
                    $sales_expense->is_billable = @$exp->is_billable;
                    $sales_expense->save();
                }
            }
        }

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
                    $imageData = CommonComponent::s3BucketFileUpload($value, 'purchase_order_transport');
                    $imagePath = $imageData['filePath'];
                    // $imageUrl = $imageData['fileName'];
                    $imageUrl = $imageData['imageURL'];

                    $purchase_order_docs = new PurchaseSalesDocument();
                    $purchase_order_docs->type = 2; // Sales Order
                    $purchase_order_docs->reference_id = $sales_order_id;
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
                    $transport_tracking->sales_order_id = $indent_request->id;
                    $transport_tracking->transport_type_id = $transport_tracking_detail->transport_type_id;
                    $transport_tracking->transport_name = $transport_tracking_detail->transport_name;
                    $transport_tracking->transport_number = $transport_tracking_detail->transport_number;
                    $transport_tracking->departure_datetime = $transport_tracking_detail->departure_datetime;
                    $transport_tracking->arriving_datetime = $transport_tracking_detail->arriving_datetime;
                    $transport_tracking->from_location = isset($transport_tracking_detail->from_location) ? $transport_tracking_detail->from_location : null;
                    $transport_tracking->to_location = isset($transport_tracking_detail->to_location) ? $transport_tracking_detail->to_location : null;
                    $transport_tracking->phone_number = isset($transport_tracking_detail->phone_number) ? $transport_tracking_detail->phone_number : null;
                    if ($imageUrl != null) {
                        $transport_tracking->file = $imageUrl;
                        $transport_tracking->file_path = $imagePath;
                    }
                    $transport_tracking->save();
                }
            }
        }

        if (isset($request->payment_details)) {
            $payment_details = json_decode($request->payment_details);
            foreach ($payment_details as $key => $payment_detail) {
                $payment_transaction = new PaymentTransaction();
                $payment_transaction->transaction_number = CommonComponent::invoice_no('payment_transaction'); // Auto Generate
                $payment_transaction->transaction_type = 2; // Sales Order
                $payment_transaction->type = 1; // Credit
                $payment_transaction->reference_id = $sales_order_id;
                $payment_transaction->payment_type_id = $payment_detail->payment_type_id;
                $payment_transaction->amount = $payment_detail->amount;
                $payment_transaction->transaction_datetime = $payment_detail->transaction_datetime;
                $payment_transaction->status = 1;
                $payment_transaction->note = @$payment_detail->note;
                $payment_transaction->save();

                if (isset($request->payment_transaction_documents) && count($request->payment_transaction_documents) > 0 && $request->file('payment_transaction_documents')) {
                    CommonComponent::payment_transaction_documents($request->file('payment_transaction_documents'), 2, $payment_transaction->id); // 2=> Sales
                }
            }
        }

        DB::commit();

        // $content = 'Invoice Number' . $request->sales_order_number .' Sales Order Updated Successfully';
        // Helper::sendPushToNotification($content);

        return response()->json([
            'status' => 200,
            'datas' => $indent_request,
            'message' => 'Sales Order Updated successfully.',
        ]);
        // } catch (\Exception $e) {
        //     Log::error($e);
        //     DB::rollback();
        //     return back()
        //         ->withInput()
        //         ->with('error', 'Sales Stored Fail');
        // }
    }

    public function salesorderexpenseupdate(Request $request)
    {
        DB::beginTransaction();
        $sales_order_id = $request->sales_order_id;
        $total_remove_exp_amount = SalesExpense::where("sales_order_id", $sales_order_id)->where('is_billable', 1)->sum('ie_amount');
        $total_unbill_amount = SalesExpense::where("sales_order_id", $sales_order_id)->where('is_billable', 0)->sum('ie_amount');

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
                    $sales_order_docs->type = 2; // Sales Order
                    $sales_order_docs->reference_id = $sales_order_id;
                    $sales_order_docs->document_type = 1; // Expense
                    $sales_order_docs->file = @$imageUrl;
                    $sales_order_docs->file_path = @$imagePath;
                    $sales_order_docs->save();
                }
            }
        }

        // Expense Data Delete
        if (isset($request->deleted_expense_ids) && count(json_decode($request->deleted_expense_ids)) > 0) {
            SalesExpense::destroy(json_decode($request->deleted_expense_ids));
        }

        // Expense Data Store
        $total_expense_amount = 0;
        $total_expense_billable_amount = 0;
        if (isset($request->expense)) {
            $expense = json_decode($request->expense);
            if (count($expense) > 0) {
                foreach ($expense as $expense_key => $exp) {
                    if (isset($exp->id)) {
                        $sales_expense = SalesExpense::where("id", $exp->id)->first();
                    } else {
                        $sales_expense = new SalesExpense();
                    }
                    $sales_expense->sales_order_id = $sales_order_id;
                    $sales_expense->income_expense_id = @$exp->expense_type_id;
                    $sales_expense->ie_amount = @$exp->expense_amount;
                    $sales_expense->is_billable = @$exp->is_billable;
                    if ($exp->is_billable == 1) {
                        $total_expense_billable_amount += $exp->expense_amount;
                    }
                    $total_expense_amount += $exp->expense_amount;
                    $sales_expense->save();
                }

                $sales_order = SalesOrder::findOrFail($sales_order_id);
                $sales_order->total_expense_billable_amount = $total_expense_billable_amount;
                $sales_order->total_expense_amount = $total_expense_amount;
                $sales_order->total_amount = ($sales_order->total_amount - $total_remove_exp_amount) + $total_expense_billable_amount;
                $sales_order->save();

                if ($total_expense_billable_amount > 0) {
                    // $payment_transaction = new PaymentTransaction();
                    // $payment_transaction->transaction_number = CommonComponent::invoice_no('payment_transaction'); // Auto Generate
                    // $payment_transaction->transaction_type = 2; // Sales Order
                    // $payment_transaction->type = 2; // Debit
                    // $payment_transaction->reference_id = $sales_order_id;
                    // $payment_transaction->payment_type_id = 1;
                    // $payment_transaction->amount = $total_expense_billable_amount;
                    // $payment_transaction->transaction_datetime = Carbon::now();
                    // $payment_transaction->status = 1;
                    // $payment_transaction->save();

                    $sales_order = SalesOrder::with('sales_order_transactions')->findOrFail($sales_order_id);
                    $paid_amount = $sales_order->sales_order_transactions->sum('amount');

                    $total_amount = $sales_order->total_amount;

                    if ($paid_amount == 0) {
                        $sales_order->payment_status = $request->payment_status != null ? $request->payment_status : 2; // UnPaid
                        $sales_order->save();
                    } else if ($paid_amount < $total_amount) {
                        $sales_order->payment_status = $request->payment_status != null ? $request->payment_status : 3; // Pending
                        $sales_order->save();
                    } else if ($paid_amount >= $total_amount) {
                        $sales_order->payment_status = $request->payment_status != null ? $request->payment_status : 1; // Paid
                        $sales_order->save();
                    }
                }
            }
        } else {
            $sales_order = SalesOrder::findOrFail($sales_order_id);
            $sales_order->total_expense_billable_amount = 0;
            $sales_order->total_expense_amount = 0;
            $sales_order->total_amount = ($sales_order->total_amount - $total_remove_exp_amount - $total_unbill_amount);
            $sales_order->save();
        }
        DB::commit();

        return response()->json([
            'status' => 200,
            'message' => 'Sales Order Expense Updated successfully.',
        ]);
    }

    public function salesordertransporttrackingupdate(Request $request)
    {
        DB::beginTransaction();
        $sales_order_id = $request->sales_order_id;
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
                    $imageData = CommonComponent::s3BucketFileUpload($value, 'purchase_order_transport');
                    $imagePath = $imageData['filePath'];
                    // $imageUrl = $imageData['fileName'];
                    $imageUrl = $imageData['imageURL'];

                    $purchase_order_docs = new PurchaseSalesDocument();
                    $purchase_order_docs->type = 2; // Sales Order
                    $purchase_order_docs->reference_id = $sales_order_id;
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
                    $transport_tracking->sales_order_id = $sales_order_id;
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
            'message' => 'Sales Order Transport Tracking Updated successfully.',
        ]);
    }

    public function salesordertransactions(Request $request)
    {
        DB::beginTransaction();
        // try {
        $sales_order_id = $request->sales_order_id;
        // $payment_details = $request->payment_detail;
        Log::info($request->payment_details);
        $payment_details = json_decode($request->payment_details);
        Log::info($payment_details);
        foreach ($payment_details as $key => $payment_detail) {
            $payment_transaction = new PaymentTransaction();
            $payment_transaction->transaction_number = CommonComponent::invoice_no('payment_transaction'); // Auto Generate
            $payment_transaction->transaction_type = 2; // Sales Order
            $payment_transaction->type = 1; // Credit
            $payment_transaction->reference_id = $sales_order_id;
            $payment_transaction->payment_type_id = $payment_detail->payment_type_id;
            $payment_transaction->amount = $payment_detail->amount;
            $payment_transaction->transaction_datetime = $payment_detail->transaction_datetime;
            $payment_transaction->status = 1;
            $payment_transaction->note = @$payment_detail->note;
            $payment_transaction->save();
        }

        Log::info($request->payment_transaction_documents);
        if (isset($request->payment_transaction_documents) && count($request->payment_transaction_documents) > 0 && $request->file('payment_transaction_documents')) {
            CommonComponent::payment_transaction_documents($request->file('payment_transaction_documents'), 2, $payment_transaction->id); // 2=> Sales
        }

        $sales_order_details = SalesOrder::with('sales_order_transactions')->findOrFail($sales_order_id);

        $paid_amount = $sales_order_details->sales_order_transactions->sum('amount');

        $total_amount = $sales_order_details->total_amount;

        if ($paid_amount == 0) {
            $sales_order_details->payment_status = $request->payment_status != null ? $request->payment_status : 2; // UnPaid
            $sales_order_details->delivered_date = now()->format('Y-m-d H:i:s');
            $sales_order_details->save();
        } else if ($paid_amount < $total_amount) {
            $sales_order_details->payment_status = $request->payment_status != null ? $request->payment_status : 3; // Pending
            $sales_order_details->delivered_date = now()->format('Y-m-d H:i:s');
            $sales_order_details->save();
        } else if ($paid_amount >= $total_amount) {
            $sales_order_details->payment_status = $request->payment_status != null ? $request->payment_status : 1; // Paid
            $sales_order_details->delivered_date = now()->format('Y-m-d H:i:s');
            $sales_order_details->save();
        }

        if ($paid_amount > $total_amount) {
            $total = $paid_amount - $total_amount;
            $user_advance = UserAdvance::where([['user_id', $sales_order_details->vendor_id]])->first();
            if ($user_advance == null) {
                $user_advance = new UserAdvance();
            }
            $user_advance->user_id = $sales_order_details->vendor_id;
            $user_advance->type = 1; // Credit
            $user_advance->amount = $total;
            $user_advance->total_amount = @$user_advance->total_amount + $total;
            $user_advance->save();

            $advancehistory = new UserAdvanceHistory();
            $advancehistory->user_id = $sales_order_details->vendor_id;
            $advancehistory->transaction_type = 2; // Sales
            $advancehistory->reference_id = $sales_order_details->id;
            $advancehistory->type = 1; // Crdeit
            $advancehistory->amount = $total;
            $advancehistory->save();
        }
        DB::commit();
        return response()->json([
            'status' => 200,
            'data' => $sales_order_details,
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

    public function salespaymentstatusupdate(Request $request)
    {
        DB::beginTransaction();
        // try {
        $sales_order_id = $request->sales_order_id;

        $indent_request = SalesOrder::findOrFail($sales_order_id);

        PaymentTransaction::where([['reference_id', $sales_order_id], ['transaction_type', 5]])->delete();

        if ($request->payment_status != null && $indent_request != null) {
            $indent_request->payment_status = $request->payment_status;
            $indent_request->save();
        }
        DB::commit();

        return response()->json([
            'status' => 200,
            'data' => $indent_request,
            'message' => 'Payment Status Updated Successfully.',
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

    public function salesorderstatusupdate(Request $request)
    {
        DB::beginTransaction();
        // try {
        $sales_order_id = $request->sales_order_id;

        $indent_request = SalesOrder::findOrFail($sales_order_id);
        $indent_request->status = $request->status;
        $indent_request->remarks = $request->reason;
        $indent_request->save();

        DB::commit();

        return response()->json([
            'status' => 200,
            'data' => $indent_request,
            'message' => 'Payment Status Updated Successfully.',
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

    public function salespaymenttransactionedit(Request $request)
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

    public function salespaymenttransactionupdate(Request $request)
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

        $sales_order_details = SalesOrder::with('sales_order_transactions')->findOrFail($payment_transaction->reference_id);

        $paid_amount = $sales_order_details->sales_order_transactions->sum('amount');

        $total_amount = $sales_order_details->total_amount;

        if ($paid_amount == 0) {
            $sales_order_details->payment_status = $request->payment_status != null ? $request->payment_status : 2; // Pending
            $sales_order_details->save();
        } else if ($paid_amount < $total_amount) {
            $sales_order_details->payment_status = $request->payment_status != null ? $request->payment_status : 3; // UnPaid
            $sales_order_details->save();
        } else if ($paid_amount >= $total_amount) {
            $sales_order_details->payment_status = $request->payment_status != null ? $request->payment_status : 1; // UnPaid
            $sales_order_details->save();
        }
        DB::commit();
        return response()->json([
            'status' => 200,
            'data' => $sales_order_details,
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

    public function salespaymenttransactiondelete(Request $request)
    {
        // try {
        $transaction_id = $request->transaction_id;

        $sales_order_id = $request->sales_order_id;

        PaymentTransactionDocument::where('reference_id', $transaction_id)->delete();

        PaymentTransaction::destroy($transaction_id);

        $sales_order_details = SalesOrder::with('sales_order_transactions')->findOrFail($sales_order_id);

        $paid_amount = $sales_order_details->sales_order_transactions->sum('amount');

        $total_amount = $sales_order_details->total_amount;

        if ($paid_amount == 0) {
            $sales_order_details->payment_status = $request->payment_status != null ? $request->payment_status : 2; // Pending
            $sales_order_details->delivered_date = now()->format('Y-m-d H:i:s');
            $sales_order_details->save();
        } else if ($paid_amount < $total_amount) {
            $sales_order_details->payment_status = $request->payment_status != null ? $request->payment_status : 3; // UnPaid
            $sales_order_details->delivered_date = now()->format('Y-m-d H:i:s');
            $sales_order_details->save();
        } else if ($paid_amount >= $total_amount) {
            $sales_order_details->payment_status = $request->payment_status != null ? $request->payment_status : 1; // UnPaid
            $sales_order_details->delivered_date = now()->format('Y-m-d H:i:s');
            $sales_order_details->save();
        }

        $sale_order_detail = SalesOrder::findOrFail($sales_order_id);

        return response()->json([
            'status' => 200,
            'datas' => $sale_order_detail,
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

    public function paymenttransactiondelete(Request $request)
    {
        // try {
        $transaction_docs_id = $request->transaction_docs_id;

        PaymentTransactionDocument::findorfail($transaction_docs_id)->delete();

        return response()->json([
            'status' => 200,
            'message' => 'Payment Transaction Document Deleted Successfully.',
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
