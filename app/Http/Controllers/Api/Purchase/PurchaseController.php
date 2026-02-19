<?php

namespace App\Http\Controllers\Api\Purchase;

use App\Core\CommonComponent;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Admin\IndentRequest\WarehouseIndentRequestController;
use App\Models\PaymentTransaction;
use App\Models\PaymentTransactionDocument;
use App\Models\Product;
use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderDetail;
use App\Models\PurchaseOrderAction;
use App\Models\PurchaseSalesDocument;
use App\Models\PurchaseOrderExpense;
use App\Models\PurchaseOrderMultiTransaction;
use App\Models\TransportTracking;
use App\Models\UserAdvance;
use App\Models\UserAdvanceHistory;
use App\Models\WarehouseIndentRequest;
use App\Models\WarehouseIndentRequestDetail;
use App\Models\WarehouseInventoryDetail;
use App\Models\WarehouseStockUpdate;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PurchaseController extends Controller
{
    protected $WarehouseIndentRequestController;
    public function __construct(WarehouseIndentRequestController $WarehouseIndentRequestController)
    {
        $this->WarehouseIndentRequestController = $WarehouseIndentRequestController;
    }

    public function purchaselist(Request $request)
    {
        // try {
        $purchase_ordered_count = PurchaseOrder::whereIn('status', config('app.purchase_ordered_status'))->count();
        $purchase_received_count = PurchaseOrder::whereIn('status', config('app.purchase_received_status'))->count();

        if ($request->warehouse_id != null) {
            $warehouse_id = array($request->warehouse_id);
        } else {
            $warehouse_id = Auth::user()->user_warehouse();
        }
        $supplier_id = $request->supplier_id;
        $purchase_order_number = $request->purchase_order_number;
        $date = $request->date;
        $status = $request->status;
        if ($request->payment_status == 4) { // Credit Sale
            $payment_status = [2, 3];
        } elseif ($request->payment_status != null) {
            $payment_status = array($request->payment_status);
        } else {
            $payment_status = [1, 2, 3];
        }
        $from_date = $request->from_date;
        $to_date = $request->to_date;
        $purchaselists = PurchaseOrder::where(function ($query) use ($warehouse_id, $supplier_id, $purchase_order_number, $from_date, $to_date, $status, $payment_status) {
            if (count($warehouse_id) > 0) {
                $query->whereIn('warehouse_id', $warehouse_id);
            }
            if ($supplier_id != null) {
                $query->where('supplier_id', $supplier_id);
            }
            if ($status != null) {
                $query->where('status', $status);
            }
            if ($purchase_order_number != null) {
                $query->where('purchase_order_number', 'LIKE', '%' . $purchase_order_number . '%');
            }
            if ($from_date != null && $to_date != null) {
                $dateformatwithtime = CommonComponent::dateformatwithtime($from_date, $to_date);
                $query->whereBetween('delivery_date', $dateformatwithtime);
            }
            if ($payment_status != null) {
                $query->whereIn('payment_status', $payment_status);
            }
        })
            ->with(['supplier' => function ($query) {
                $query->select('id', 'first_name', 'last_name', 'user_type', 'status');
            }])
            ->with(['warehouse' => function ($query) {
                $query->select('id', 'name', 'code');
            }])
            ->with(['created_by_details' => function ($query) {
                $query->select('id', 'first_name', 'last_name', 'user_type', 'status');
            }])
            ->select('id', 'purchase_order_number', 'warehouse_id', 'supplier_id', 'status', 'delivery_date', 'payment_status', 'created_by', 'total')
            ->orderBy('id', 'DESC')
            ->paginate(15)
            ->through(function ($purchaselists) {
                $paid_amount = $purchaselists->purchase_order_transactions->sum('amount');
                $purchaselists['paid_amount'] = $paid_amount;
                $purchaselists['pending_amount'] = $purchaselists->total - $paid_amount;
                return $purchaselists;
            });

        return response()->json([
            'status' => 200,
            'purchase_ordered_count' => $purchase_ordered_count,
            'purchase_received_count' => $purchase_received_count,
            'purchaselists' => $purchaselists,
            'message' => 'Vendor/Customer fetched successfully.',
        ]);
        // } catch (\Exception $e) {
        //     Log::error($e);
        //     return response()->json([
        //         'status' => 400,
        //         'message' => 'Data not found.',
        //     ]);
        // }
    }

    public function purchasedetails(Request $request)
    {
        // try {
        $purchase_id = $request->purchase_id;
        $purchase_details = PurchaseOrder::with(['supplier' => function ($query) use ($purchase_id) {
            $query->select('id', 'first_name', 'last_name', 'user_type', 'status');
        }])
            ->with(['warehouse' => function ($query) {
                $query->select('id', 'name', 'code');
            }])
            ->with(['created_by_details' => function ($query) {
                $query->select('id', 'first_name', 'last_name', 'user_type', 'status');
            }])
            ->findOrFail($purchase_id);

        $purchase_product_details = PurchaseOrderDetail::where('purchase_order_id', $purchase_id)->with('unit_details:id,unit_name,unit_short_code,allow_decimal,operator,operation_value')->get();

        $purchase_expense_details = PurchaseOrderExpense::where('purchase_order_id', $purchase_id)->with('income_expense_details')->get();

        $purchase_expense_docs = PurchaseSalesDocument::where([['type', 1], ['reference_id', $purchase_id], ['document_type', 1]])->get();

        $purchase_transport_trackings = TransportTracking::where('purchase_order_id', $purchase_id)->with('transport_type_details')->get();

        $purchase_transport_trackings_docs = PurchaseSalesDocument::where([['type', 1], ['reference_id', $purchase_id], ['document_type', 2]])->get();

        $purchase_order_transactions = PaymentTransaction::where([['transaction_type', 1], ['reference_id', $purchase_id]])->with('payment_type_details', 'payment_transaction_documents')->get();;

        $paid_amount = $purchase_details->purchase_order_transactions->sum('amount');
        $total_amount = $purchase_details->total;
        $due_amount = $total_amount - $paid_amount;
        $due_amount = $due_amount > 0 ? $due_amount : 0;

        return response()->json([
            'status' => 200,
            'purchase_details' => $purchase_details,
            'purchase_product_details' => $purchase_product_details,
            'purchase_expense_details' => $purchase_expense_details,
            'purchase_transport_trackings' => $purchase_transport_trackings,
            'purchase_expense_docs' => $purchase_expense_docs,
            'purchase_transport_trackings_docs' => $purchase_transport_trackings_docs,
            'purchase_order_transactions' => $purchase_order_transactions,
            'paid_amount' => round($paid_amount, 2),
            'total_amount' => round($total_amount, 2),
            'due_amount' => round($due_amount, 2),
            'message' => 'Purchase Order fetched successfully.',
        ]);
        // } catch (\Exception $e) {
        //     Log::error($e);
        //     return response()->json([
        //         'status' => 400,
        //         'message' => 'Data not found.',
        //     ]);
        // }
    }

    public function filterindentrequest(Request $request)
    {
        // try {
        $filter_warehouse_id = $request->filter_warehouse_id;
        $filter_store_id = $request->filter_store_id;
        $filter_vendor_id = $request->filter_vendor_id;
        $filter_status = $request->filter_status;
        $from_date = $request->from_date;
        $to_date = $request->to_date;
        $filter_store_indent_request_id = isset($request->filter_store_indent_request_id) ? $request->filter_store_indent_request_id : [];
        $filter_vendor_indent_request_id = isset($request->filter_vendor_indent_request_id) ? $request->filter_vendor_indent_request_id : [];

        $filtered_datas = $this->WarehouseIndentRequestController->overall_data_filter($filter_warehouse_id, $filter_store_id, $filter_vendor_id, $filter_status, $from_date, $to_date, $filter_store_indent_request_id, $filter_vendor_indent_request_id);

        $data['storeindentDatas'] = $filtered_datas['storeindentrequestdata'];
        $data['vendorindentDatas'] = $filtered_datas['vendorindentrequestdata'];
        $data['overall_requests'] = $filtered_datas['overalldata'];

        return response()->json([
            'status' => 200,
            'datas' => $data,
            'message' => 'Filtered data fetched successfully.',
        ]);
        // } catch (\Exception $e) {
        //     Log::error($e);
        //     return response()->json([
        //         'status' => 400,
        //         'message' => 'Data not found.',
        //     ]);
        // }
    }

    public function purchasestore(Request $request)
    {
        DB::beginTransaction();
        // try {
        $imagePath = null;
        $imageUrl = null;

        $indent_request = new PurchaseOrder();
        if ($request->hasFile('file')) {
            $imageData = CommonComponent::s3BucketFileUpload($request->file, 'purchase_order');
            $imagePath = $imageData['filePath'];
            // $imageUrl = $imageData['fileName'];
            $imageUrl = $imageData['imageURL'];
        }

        $indent_request->purchase_order_number = $request->purchase_order_number;
        $indent_request->warehouse_id = $request->warehouse_id;
        $indent_request->supplier_id = $request->supplier_id;
        $indent_request->warehouse_ir_id = $request->warehouse_ir_id;
        $indent_request->delivery_date = $request->delivery_date;
        $indent_request->status = $request->status;
        $indent_request->sub_total = $request->sub_total;
        $indent_request->total = $request->total_amount;
        $indent_request->remarks = $request->remarks;
        $indent_request->payment_status = $request->payment_status != null ? $request->payment_status : 2; // Unpaid
        if ($imageUrl != null) {
            $indent_request->file = $imageUrl;
            $indent_request->file_path = $imagePath;
        }
        $indent_request->discount_type = $request->discount_type;
        $indent_request->discount_percentage = $request->discount_percentage;
        $indent_request->discount_amount = $request->discount_amount;
        $indent_request->adjustment_amount = $request->adjustment_amount;
        $indent_request->save();

        $products = json_decode($request->products);
        if (count($products) > 0) {
            foreach ($products as $key => $product) {
                $product_data = Product::findOrfail($product->product_id);
                $indent_request_detail = new PurchaseOrderDetail();
                $indent_request_detail->purchase_order_id = $indent_request->id;
                $indent_request_detail->added_by_supplier = 0;
                $indent_request_detail->product_id = $product_data->id;
                $indent_request_detail->sku_code = $product_data->sku_code;
                $indent_request_detail->name = $product_data->name;
                $indent_request_detail->unit_id = @$product->unit_id;
                $indent_request_detail->request_quantity = @$product->quantity;
                $indent_request_detail->given_quantity = @$product->quantity;
                $indent_request_detail->per_unit_price = @$product->amount;
                $indent_request_detail->amount = @$product->sub_total;
                $indent_request_detail->sub_total = @$product->sub_total;
                $indent_request_detail->save();

                if ($request->stock_verified) {
                    $warehouse_stock_detail_exists = WarehouseStockUpdate::where([['warehouse_id', $request->warehouse_id], ['product_id', $product_data->id], ['status', 1]])->first();
                    $warehouse_stock_detail = new WarehouseStockUpdate();
                    $warehouse_stock_detail->warehouse_id = $request->warehouse_id;
                    $warehouse_stock_detail->product_id = $product_data->id;
                    $warehouse_stock_detail->stock_update_on = Carbon::now();
                    $warehouse_stock_detail->existing_stock = ($warehouse_stock_detail_exists != null && $warehouse_stock_detail_exists->total_stock != null) ? $warehouse_stock_detail_exists->total_stock : 0;
                    $warehouse_stock_detail->adding_stock = @$product->quantity;
                    $warehouse_stock_detail->total_stock = ($warehouse_stock_detail_exists != null && $warehouse_stock_detail_exists->total_stock != null) ? $warehouse_stock_detail_exists->total_stock + @$product->quantity : @$product->quantity;
                    $warehouse_stock_detail->status = 1;
                    $warehouse_stock_detail->box_number = $request->box_number != null ? $request->box_number : 1;
                    $warehouse_stock_detail->save();

                    $warehouse_inventory = WarehouseInventoryDetail::where([['warehouse_id', $request->warehouse_id], ['product_id', $product_data->id], ['status', 1]])->first();
                    if ($warehouse_inventory == null) {
                        $warehouse_inventory = new WarehouseInventoryDetail();
                        $warehouse_inventory->warehouse_id = $request->warehouse_id;
                        $warehouse_inventory->product_id = $product_data->id;
                    }
                    $warehouse_inventory->weight = @$warehouse_inventory->weight + $warehouse_stock_detail->adding_stock;
                    $warehouse_inventory->status = 1;
                    $warehouse_inventory->save();
                }
            }
        }

        $request_action = new PurchaseOrderAction();
        $request_action->purchase_order_id = $indent_request->id;
        $request_action->status = $request->status;
        $request_action->action_date = Carbon::now();
        $request_action->remarks = "Purchase Order Adding " . $request->remarks;
        $request_action->save();

        if ($indent_request->payment_status != 2) {
            $payment_transaction = new PaymentTransaction();
            $payment_transaction->transaction_number = CommonComponent::invoice_no('payment_transaction'); // Auto Generate
            $payment_transaction->transaction_type = 1; // Purchase Order
            $payment_transaction->type = 2; // Debit
            $payment_transaction->reference_id = $indent_request->id;
            $payment_transaction->payment_type_id = 1; // Cash On Hand
            $payment_transaction->amount = $request->total_amount;
            $payment_transaction->transaction_datetime = Carbon::now();
            $payment_transaction->status = 1;
            $payment_transaction->note = @$request->note;
            $payment_transaction->save();

            if (isset($request->payment_transaction_documents) && count($request->payment_transaction_documents) > 0 && $request->file('payment_transaction_documents')) {
                CommonComponent::payment_transaction_documents($request->file('payment_transaction_documents'), 1, $payment_transaction->id); // 1=> Purchase Document
            }
        }

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

    public function purchaseorderexpense(Request $request)
    {
        DB::beginTransaction();
        // try {
        // Expense Details store
        $imagePath = null;
        $imageUrl = null;


        $purchase_order_id = $request->purchase_order_id;

        // Expense Docs Store
        if (isset($request->expense_documents) && count($request->expense_documents) > 0 && $request->file('expense_documents')) {
            foreach ($request->file('expense_documents') as $key => $value) {
                if ($value) {
                    $imageData = CommonComponent::s3BucketFileUpload($value, 'purchase_order_expense');
                    $imagePath = $imageData['filePath'];
                    // $imageUrl = $imageData['fileName'];
                    $imageUrl = $imageData['imageURL'];

                    $purchase_order_docs = new PurchaseSalesDocument();
                    $purchase_order_docs->type = 1; // Purchase Order
                    $purchase_order_docs->reference_id = $purchase_order_id;
                    $purchase_order_docs->document_type = 1; // Expense
                    $purchase_order_docs->file = @$imageUrl;
                    $purchase_order_docs->file_path = @$imagePath;
                    $purchase_order_docs->save();
                }
            }
        }

        // Expense Data Store
        $total_expense_amount = 0;
        $total_expense_billable_amount = 0;
        if (isset($request->expense)) {
            $expense = json_decode($request->expense);
            if (count($expense) > 0) {
                foreach ($expense as $expense_key => $exp) {
                    $purchase_expense = new PurchaseOrderExpense();
                    $purchase_expense->purchase_order_id = $purchase_order_id;
                    $purchase_expense->income_expense_id = @$exp->expense_type_id;
                    $purchase_expense->ie_amount = @$exp->expense_amount;
                    $purchase_expense->is_billable = @$exp->is_billable;
                    if ($exp->is_billable == 1) {
                        $total_expense_billable_amount += $exp->expense_amount;
                    }
                    $total_expense_amount += $exp->expense_amount;
                    $purchase_expense->save();
                }
            }

            $indent_request = PurchaseOrder::where('id', $purchase_order_id)->first();
            $indent_request->total_expense_billable_amount = $total_expense_billable_amount;
            $indent_request->total_expense_amount = $total_expense_amount;
            $indent_request->total = $indent_request->total + $total_expense_billable_amount;
            $indent_request->save();
        }

        $imagePath = null;
        $imageUrl = null;
        // Transaport Tracking Docs Store
        if (isset($request->transport_tracking_files) && count($request->transport_tracking_files) > 0 && $request->file('transport_tracking_files')) {
            foreach ($request->file('transport_tracking_files') as $key => $value) {
                if ($value) {
                    $imageData = CommonComponent::s3BucketFileUpload($value, 'purchase_order_transport');
                    $imagePath = $imageData['filePath'];
                    // $imageUrl = $imageData['fileName'];
                    $imageUrl = $imageData['imageURL'];

                    $purchase_order_docs = new PurchaseSalesDocument();
                    $purchase_order_docs->type = 1; // Purchase Order
                    $purchase_order_docs->reference_id = $purchase_order_id;
                    $purchase_order_docs->document_type = 2; // Transport Tracking
                    $purchase_order_docs->file = @$imageUrl;
                    $purchase_order_docs->file_path = @$imagePath;
                    $purchase_order_docs->save();
                }
            }
        }

        // Transaport Tracking Data Store
        if (isset($request->transport_tracking_details)) {
            $transport_tracking_details = json_decode($request->transport_tracking_details);
            if (count($transport_tracking_details) > 0) {
                foreach ($transport_tracking_details as $transport_key => $transport_tracking_detail) {
                    $transport_tracking = new TransportTracking();
                    $transport_tracking->purchase_order_id = $purchase_order_id;
                    $transport_tracking->transport_type_id = $transport_tracking_detail->transport_type_id;
                    $transport_tracking->transport_name = $transport_tracking_detail->transport_name;
                    $transport_tracking->transport_number = $transport_tracking_detail->transport_number;
                    $transport_tracking->departure_datetime = $transport_tracking_detail->departure_datetime;
                    $transport_tracking->arriving_datetime = $transport_tracking_detail->arriving_datetime;
                    $transport_tracking->from_location = isset($transport_tracking_detail->from_location) ? $transport_tracking_detail->from_location : NULL;;
                    $transport_tracking->to_location = isset($transport_tracking_detail->to_location) ? $transport_tracking_detail->to_location : NULL;;
                    $transport_tracking->phone_number = isset($transport_tracking_detail->phone_number) ? $transport_tracking_detail->phone_number : NULL;
                    if ($imageUrl != null) {
                        $transport_tracking->file = $imageUrl;
                        $transport_tracking->file_path = $imagePath;
                    }
                    $transport_tracking->save();
                }
            }
        }

        $request_action = new PurchaseOrderAction();
        $request_action->purchase_order_id = $purchase_order_id;
        $request_action->status = $indent_request->status;
        $request_action->action_date = Carbon::now();
        $request_action->remarks = "Expense Adding " . $request->remarks;
        $request_action->save();

        DB::commit();

        return response()->json([
            'status' => 200,
            'message' => 'Data Stored successfully.',
        ]);
        // } catch (\Exception $e) {
        //     Log::error($e);
        //     DB::rollback();
        //     return back()->withInput()->with('error', 'Warehouse Indent Request Stored Fail');
        // }
    }

    public function purchaseorderedit(Request $request)
    {
        $purchase_id = $request->purchase_order_id;

        $purchaselists = PurchaseOrder::with(['supplier' => function ($query) use ($purchase_id) {
            $query->select('id', 'first_name', 'last_name', 'user_type', 'status');
        }])
            ->with(['warehouse' => function ($query) {
                $query->select('id', 'name', 'code');
            }])
            ->with(['created_by_details' => function ($query) {
                $query->select('id', 'first_name', 'last_name', 'user_type', 'status');
            }])
            ->findOrFail($request->purchase_order_id);

        $purchase_product_details = PurchaseOrderDetail::where('purchase_order_id', $purchase_id)->get();

        $purchase_expense_details = PurchaseOrderExpense::where('purchase_order_id', $purchase_id)->with('income_expense_details')->get();

        $purchase_expense_docs = PurchaseSalesDocument::where([['type', 1], ['reference_id', $purchase_id], ['document_type', 1]])->get();

        $purchase_transport_trackings = TransportTracking::where('purchase_order_id', $purchase_id)->with('transport_type_details')->get();

        $purchase_transport_trackings_docs = PurchaseSalesDocument::where([['type', 1], ['reference_id', $purchase_id], ['document_type', 2]])->get();

        $purchase_order_transactions = $purchaselists->purchase_order_transactions;

        return response()->json([
            'status' => 200,
            'purchaselists' => $purchaselists,
            'purchase_product_details' => $purchase_product_details,
            'purchase_expense_details' => $purchase_expense_details,
            'purchase_transport_trackings' => $purchase_transport_trackings,
            'purchase_expense_docs' => $purchase_expense_docs,
            'purchase_transport_trackings_docs' => $purchase_transport_trackings_docs,
            'purchase_order_transactions' => $purchase_order_transactions,
            'message' => 'Purchase Order fetched successfully.',
        ]);
    }

    public function purchaseorderupdate(Request $request)
    {

        DB::beginTransaction();
        // try {
        $indent_request = PurchaseOrder::findOrFail($request->purchase_order_id);
        $imagePath = $indent_request->file_path;
        $imageUrl = $indent_request->file;
        if ($request->hasFile('file')) {
            $fileDeleted = CommonComponent::s3BucketFileDelete($indent_request->file, $indent_request->file_path);

            $imageData = CommonComponent::s3BucketFileUpload($request->file, 'purchase_order');
            $imagePath = $imageData['filePath'];
            // $imageUrl = $imageData['fileName'];
            $imageUrl = $imageData['imageURL'];
        }

        $indent_request->warehouse_id = $request->warehouse_id;
        $indent_request->supplier_id = $request->supplier_id;
        $indent_request->warehouse_ir_id = $request->warehouse_ir_id;
        $indent_request->delivery_date = $request->delivery_date;
        $indent_request->status = $request->status;
        $indent_request->sub_total = $request->sub_total;
        $indent_request->total = $indent_request->total_expense_billable_amount + $request->total_amount;
        $indent_request->remarks = $request->remarks;
        if ($imageUrl != null) {
            $indent_request->file = $imageUrl;
            $indent_request->file_path = $imagePath;
        }
        $indent_request->discount_type = $request->discount_type;
        $indent_request->discount_percentage = $request->discount_percentage;
        $indent_request->discount_amount = $request->discount_amount;
        $indent_request->adjustment_amount = $request->adjustment_amount;
        $indent_request->save();

        if (isset($request->deleted_ids) && count(json_decode($request->deleted_ids)) > 0) {
            PurchaseOrderDetail::destroy(json_decode($request->deleted_ids));
        }

        if (isset($request->products)) {
            $products = json_decode($request->products);
            if (count($products) > 0) {
                foreach ($products as $key => $product) {
                    $product_data = Product::findOrfail($product->product_id);
                    $old_weight = 0;
                    if (isset($product->id)) {
                        $indent_request_detail = PurchaseOrderDetail::findOrFail($product->id);
                        $old_weight = $indent_request_detail->given_quantity;
                    } else {
                        $indent_request_detail = new PurchaseOrderDetail();
                    }

                    $indent_request_detail->purchase_order_id = $indent_request->id;
                    $indent_request_detail->added_by_supplier = 0;
                    $indent_request_detail->product_id = $product_data->id;
                    $indent_request_detail->sku_code = $product_data->sku_code;
                    $indent_request_detail->name = $product_data->name;
                    $indent_request_detail->unit_id = @$product->unit_id;
                    $indent_request_detail->request_quantity = @$product->quantity;
                    $indent_request_detail->given_quantity = @$product->quantity;
                    $indent_request_detail->per_unit_price = @$product->amount;
                    $indent_request_detail->amount = @$product->sub_total;
                    $indent_request_detail->sub_total = @$product->sub_total;
                    $indent_request_detail->save();

                    // $stock_update_weight = $indent_request_detail->given_quantity - $old_weight;

                    // if ($request->stock_verified) {
                    //     if ($stock_update_weight != 0) {
                    //         $warehouse_stock_detail_exists = WarehouseStockUpdate::where([['warehouse_id', $request->warehouse_id], ['product_id', $product_data->id], ['status', 1]])->orderBy('id', 'DESC')->first();
                    //         $warehouse_stock_detail = new WarehouseStockUpdate();
                    //         $warehouse_stock_detail->warehouse_id = $request->warehouse_id;
                    //         $warehouse_stock_detail->product_id = $product_data->id;
                    //         $warehouse_stock_detail->stock_update_on = Carbon::now();
                    //         $warehouse_stock_detail->existing_stock = ($warehouse_stock_detail_exists != null && $warehouse_stock_detail_exists->total_stock != null) ? $warehouse_stock_detail_exists->total_stock : 0;
                    //         $warehouse_stock_detail->adding_stock = @$stock_update_weight;
                    //         $warehouse_stock_detail->total_stock = ($warehouse_stock_detail_exists != null && $warehouse_stock_detail_exists->total_stock != null) ? $warehouse_stock_detail_exists->total_stock + @$stock_update_weight : @$stock_update_weight;
                    //         $warehouse_stock_detail->status = 1;
                    //         $warehouse_stock_detail->box_number = $request->box_number != null ? $request->box_number : 1;
                    //         $warehouse_stock_detail->save();
                    //     }

                    //     $warehouse_inventory = WarehouseInventoryDetail::where([['warehouse_id', $request->warehouse_id], ['product_id', $product_data->id], ['status', 1]])->first();
                    //     if ($warehouse_inventory == null) {
                    //         $warehouse_inventory = new WarehouseInventoryDetail();
                    //         $warehouse_inventory->warehouse_id = $request->warehouse_id;
                    //         $warehouse_inventory->product_id = $product_data->id;
                    //     }
                    //     $warehouse_inventory->weight = @$warehouse_inventory->weight + @$stock_update_weight;
                    //     $warehouse_inventory->status = 1;
                    //     $warehouse_inventory->save();
                    // }
                $warehouse_stock_detail = WarehouseStockUpdate::where([['warehouse_id', $request->warehouse_id], ['product_id', $product_data->id], ['status', 1]])->first();

                if (($request->stock_verified == '1') && ($request->status == '10')) {
                    $previous_adding_stock = @$warehouse_stock_detail->adding_stock;
                    $previous_existing_stock = @$warehouse_stock_detail->existing_stock;
                    $previous_total_stock = @$warehouse_stock_detail->total_stock;

                    $given_quantity = $indent_request_detail->given_quantity;

                    $equal_check_adding = $previous_adding_stock ? $previous_adding_stock == $given_quantity : false;
                    $greater_check_adding = $previous_adding_stock ? $previous_adding_stock < $given_quantity: false;

                    if($equal_check_adding) {  // check if previous and current quantity are same
                        Log::info('entered equal condition');
                        $adding_stock = $given_quantity;
                        $existing_stock = @$previous_existing_stock;
                        $total_stock = @$previous_total_stock;
                        $difference_stock = 0;
                    } elseif($greater_check_adding) {
                        Log::info('entered greater condition');  // check if  current quantity are greater than previous quantity
                        $difference_stock = $given_quantity -  $previous_adding_stock;
                        $adding_stock = $given_quantity ;
                        $existing_stock = @$previous_total_stock;
                        $total_stock = @$previous_total_stock + $difference_stock;
                    } else {
                        Log::info('entered else condition');
                        $difference_stock = $given_quantity -  $previous_adding_stock;
                        $adding_stock = $given_quantity;
                        $existing_stock = $previous_total_stock ?? 0;
                        $total_stock = @$previous_total_stock + $difference_stock;
                    }

                    if ($warehouse_stock_detail == null) {
                        $warehouse_stock_detail = new WarehouseStockUpdate();
                        $warehouse_stock_detail->warehouse_id = $request->warehouse_id;
                        $warehouse_stock_detail->product_id = $product_data->id;
                    }
                    $warehouse_stock_detail->stock_update_on = Carbon::now();
                    $warehouse_stock_detail->existing_stock = $existing_stock;
                    $warehouse_stock_detail->adding_stock = $adding_stock;
                    $warehouse_stock_detail->total_stock = $total_stock;
                    $warehouse_stock_detail->status = 1;
                    $warehouse_stock_detail->stock_verified = $request->stock_verified;
                    $warehouse_stock_detail->box_number = $request->box_number != null ? $request->box_number : $warehouse_stock_detail->box_number;
                    $warehouse_stock_detail->save();


                    $warehouse_inventory = WarehouseInventoryDetail::where([['warehouse_id', $request->warehouse_id], ['product_id', $product_data->id], ['status', 1]])->first();
                    if($equal_check_adding) { // check if previous and current quantity are same
                        $inventory_weight = @$warehouse_inventory->weight;
                        Log::info('equla weight'. $inventory_weight);
                    } elseif($greater_check_adding) { // check if  current quantity are greater than previous quantity
                        $inventory_weight = @$warehouse_inventory->weight + $difference_stock ;
                        Log::info('greater weight'. $inventory_weight);
                    } else {
                        $inventory_weight = @$warehouse_inventory->weight + $difference_stock;
                        Log::info('else weight'. $inventory_weight);
                    }

                    if ($warehouse_inventory == null) {
                        $warehouse_inventory = new WarehouseInventoryDetail();
                        $warehouse_inventory->warehouse_id = $request->warehouse_id;
                        $warehouse_inventory->product_id = $product_data->id;
                    }
                    $warehouse_inventory->weight = $inventory_weight;
                    $warehouse_inventory->status = 1;
                    $warehouse_inventory->save();
                }
                }
            }
        }

        $request_action = new PurchaseOrderAction();
        $request_action->purchase_order_id = $indent_request->id;
        $request_action->status = $request->status;
        $request_action->action_date = Carbon::now();
        $request_action->remarks = "Purchase Order Adding " . $request->remarks;
        $request_action->save();

        if (isset($request->payment_details)) {
            $payment_details = json_decode($request->payment_details);
            foreach ($payment_details as $key => $payment_detail) {
                $payment_transaction = new PaymentTransaction();
                $payment_transaction->transaction_number = CommonComponent::invoice_no('payment_transaction'); // Auto Generate
                $payment_transaction->transaction_type = 1; // Purchase Order
                $payment_transaction->type = 2; // Debit
                $payment_transaction->reference_id = $indent_request->id;
                $payment_transaction->payment_type_id = $payment_detail->payment_type_id;
                $payment_transaction->amount = $payment_detail->amount;
                $payment_transaction->transaction_datetime = $payment_detail->transaction_datetime;
                $payment_transaction->status = 1;
                $payment_transaction->note = @$payment_detail->note;
                $payment_transaction->save();

                if (isset($request->payment_transaction_documents) && count($request->payment_transaction_documents) > 0 && $request->file('payment_transaction_documents')) {
                    CommonComponent::payment_transaction_documents($request->file('payment_transaction_documents'), 1, $payment_transaction->id); // 1=> Purchase Document
                }
            }
        }

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
        //         'datas' => $indent_request,
        //         'message' => 'There are some Technical Issue, Kindly contact Admin.',
        //     ]);
        // }
    }

    public function purchaseorderexpenseupdate(Request $request)
    {
        DB::beginTransaction();
        // try {
        // Expense Details store
        $imagePath = null;
        $imageUrl = null;

        $purchase_order_id = $request->purchase_order_id;
        $indent_request = PurchaseOrder::where('id', $purchase_order_id)->first();
        $total_remove_exp_amount = PurchaseOrderExpense::where("purchase_order_id", $purchase_order_id)->where('is_billable', 1)->sum('ie_amount');

        // Expense Docs Delete
        if (isset($request->deleted_expense_doc_ids) && count(json_decode($request->deleted_expense_doc_ids)) > 0) {
            $deleted_expense_doc_ids = json_decode($request->deleted_expense_doc_ids);
            foreach ($deleted_expense_doc_ids as $key => $value) {
                if ($value) {
                    $purchase_order_docs = PurchaseSalesDocument::findOrFail($value);
                    CommonComponent::s3BucketFileDelete($purchase_order_docs->file, $purchase_order_docs->file_path);

                    $purchase_order_docs->delete();
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

                    $purchase_order_docs = new PurchaseSalesDocument();
                    $purchase_order_docs->type = 1; // Purchase Order
                    $purchase_order_docs->reference_id = $purchase_order_id;
                    $purchase_order_docs->document_type = 1; // Expense
                    $purchase_order_docs->file = @$imageUrl;
                    $purchase_order_docs->file_path = @$imagePath;
                    $purchase_order_docs->save();
                }
            }
        }

        // Expense Data Delete
        if (isset($request->deleted_expense_ids) && count(json_decode($request->deleted_expense_ids)) > 0) {
            PurchaseOrderExpense::destroy(json_decode($request->deleted_expense_ids));
        }

        // Expense Data Store
        $total_expense_amount = 0;
        $total_expense_billable_amount = 0;
        if (isset($request->expense)) {
            $expense = json_decode($request->expense);
            if (count($expense) > 0) {
                foreach ($expense as $expense_key => $exp) {
                    if (isset($exp->id)) {
                        $purchase_expense = PurchaseOrderExpense::where("id", $exp->id)->first();
                    } else {
                        $purchase_expense = new PurchaseOrderExpense();
                    }
                    $purchase_expense->purchase_order_id = $purchase_order_id;
                    $purchase_expense->income_expense_id = @$exp->expense_type_id;
                    $purchase_expense->ie_amount = @$exp->expense_amount;
                    $purchase_expense->is_billable = @$exp->is_billable;
                    if ($exp->is_billable == 1) {
                        $total_expense_billable_amount += $exp->expense_amount;
                    }
                    $total_expense_amount += $exp->expense_amount;
                    $purchase_expense->save();
                }
            }

            $indent_request = PurchaseOrder::where('id', $purchase_order_id)->first();
            $indent_request->total_expense_billable_amount = $total_expense_billable_amount;
            $indent_request->total_expense_amount = $total_expense_amount;
            $indent_request->total = ($indent_request->total - $total_remove_exp_amount) + $total_expense_billable_amount;
            $indent_request->save();

            if ($total_expense_billable_amount > 0) {
                // $payment_transaction = new PaymentTransaction();
                // $payment_transaction->transaction_number = CommonComponent::invoice_no('payment_transaction'); // Auto Generate
                // $payment_transaction->transaction_type = 1; // Purchase Order
                // $payment_transaction->type = 2; // Debit
                // $payment_transaction->reference_id = $purchase_order_id;
                // $payment_transaction->payment_type_id = 1;
                // $payment_transaction->amount = $total_expense_billable_amount;
                // $payment_transaction->transaction_datetime = Carbon::now();
                // $payment_transaction->status = 1;
                // $payment_transaction->save();

                $indent_request = PurchaseOrder::with('purchase_order_transactions')->where('id', $purchase_order_id)->first();
                $paid_amount = $indent_request->purchase_order_transactions->sum('amount');

                $total_amount = $indent_request->total;

                if ($paid_amount == 0) {
                    $indent_request->payment_status = $request->payment_status != null ? $request->payment_status : 2; // UnPaid
                    $indent_request->save();
                } else if ($paid_amount < $total_amount) {
                    $indent_request->payment_status = $request->payment_status != null ? $request->payment_status : 3; // Pending
                    $indent_request->save();
                } else if ($paid_amount >= $total_amount) {
                    $indent_request->payment_status = $request->payment_status != null ? $request->payment_status : 1; // Paid
                    $indent_request->save();
                }
            }
        }

        // Transaport Tracking Docs Delete
        if (isset($request->deleted_tracking_doc_ids) && count(json_decode($request->deleted_tracking_doc_ids)) > 0) {
            $deleted_tracking_doc_ids = $request->deleted_tracking_doc_ids;
            foreach ($deleted_tracking_doc_ids as $key => $value) {
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
                    $purchase_order_docs->type = 1; // Purchase Order
                    $purchase_order_docs->reference_id = $purchase_order_id;
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
                    $transport_tracking->purchase_order_id = $purchase_order_id;
                    $transport_tracking->transport_type_id = $transport_tracking_detail->transport_type_id;
                    $transport_tracking->transport_name = $transport_tracking_detail->transport_name;
                    $transport_tracking->transport_number = $transport_tracking_detail->transport_number;
                    $transport_tracking->departure_datetime = $transport_tracking_detail->departure_datetime;
                    $transport_tracking->arriving_datetime = $transport_tracking_detail->arriving_datetime;
                    $transport_tracking->from_location = isset($transport_tracking_detail->from_location) ? $transport_tracking_detail->from_location : NULL;;
                    $transport_tracking->to_location = isset($transport_tracking_detail->to_location) ? $transport_tracking_detail->to_location : NULL;;
                    $transport_tracking->phone_number = isset($transport_tracking_detail->phone_number) ? $transport_tracking_detail->phone_number : NULL;
                    if ($imageUrl != null) {
                        $transport_tracking->file = $imageUrl;
                        $transport_tracking->file_path = $imagePath;
                    }
                    $transport_tracking->save();
                }
            }
        }

        $request_action = new PurchaseOrderAction();
        $request_action->purchase_order_id = $purchase_order_id;
        $request_action->status = $indent_request->status;
        $request_action->action_date = Carbon::now();
        $request_action->remarks = "Expense Adding " . $request->remarks;
        $request_action->save();

        DB::commit();

        return response()->json([
            'status' => 200,
            'message' => 'Data Stored successfully.',
        ]);
        // if ($request->submission_type == 1) {
        //     return redirect()->route('admin.warehouse-indent-request.index')->with('success', 'Warehouse Indent Request Stored Successfully');
        // } elseif ($request->submission_type == 2) {
        //     return back()->with('success', 'Warehouse Indent Request Stored Successfully');
        // }
        // } catch (\Exception $e) {
        //     Log::error($e);
        //     DB::rollback();
        //     return back()->withInput()->with('error', 'Warehouse Indent Request Stored Fail');
        // }
    }

    public function purchaseordertransactions(Request $request)
    {
        // try {
        $purchase_order_id = $request->purchase_order_id;

        if (isset($request->payment_details)) {
            $payment_details = json_decode($request->payment_details);
            foreach ($payment_details as $key => $payment_detail) {
                $payment_transaction = new PaymentTransaction();
                $payment_transaction->transaction_number = CommonComponent::invoice_no('payment_transaction'); // Auto Generate
                $payment_transaction->transaction_type = 1; // Purchase Order
                $payment_transaction->type = 2; // Debit
                $payment_transaction->reference_id = $purchase_order_id;
                $payment_transaction->payment_type_id = (int)$payment_detail->payment_type_id;
                $payment_transaction->amount = $payment_detail->amount;
                $payment_transaction->transaction_datetime = $payment_detail->transaction_datetime;
                $payment_transaction->status = 1;
                $payment_transaction->note = @$payment_detail->note;
                $payment_transaction->save();

                if (isset($request->payment_transaction_documents) && count($request->payment_transaction_documents) > 0 && $request->file('payment_transaction_documents')) {
                    CommonComponent::payment_transaction_documents($request->file('payment_transaction_documents'), 1, $payment_transaction->id); // 1=> Purchase Document
                }
            }
        }

        $purchase_order_details = PurchaseOrder::with('purchase_order_transactions')->findOrFail($purchase_order_id);

        $paid_amount = $purchase_order_details->purchase_order_transactions->sum('amount');

        $total_amount = $purchase_order_details->total;

        if ($paid_amount == 0) {
            $purchase_order_details->payment_status = $request->payment_status != null ? $request->payment_status : 2; // UnPaid
            $purchase_order_details->save();
        } else if ($paid_amount < $total_amount) {
            $purchase_order_details->payment_status = $request->payment_status != null ? $request->payment_status : 3; // Pending
            $purchase_order_details->save();
        } else if ($paid_amount >= $total_amount) {
            $purchase_order_details->payment_status = $request->payment_status != null ? $request->payment_status : 1; // Paid
            $purchase_order_details->save();
        }

        if ($paid_amount > $total_amount) {
            $total = $paid_amount - $total_amount;
            $user_advance = UserAdvance::where([['user_id', $purchase_order_details->supplier_id]])->first();
            if ($user_advance == null) {
                $user_advance = new UserAdvance();
            }
            $user_advance->user_id = $purchase_order_details->supplier_id;
            $user_advance->type = 1; // Credit
            $user_advance->amount = $total;
            $user_advance->total_amount = @$user_advance->total_amount + $total;
            $user_advance->save();

            $advancehistory = new UserAdvanceHistory();
            $advancehistory->user_id = $purchase_order_details->supplier_id;;
            $advancehistory->transaction_type = 1; // Purchase
            $advancehistory->reference_id = $purchase_order_details->id;
            $advancehistory->type = 1; // Credit
            $advancehistory->amount = $total;
            $advancehistory->save();
        }

        return response()->json([
            'status' => 200,
            'data' => $purchase_order_details,
            'message' => 'Transaction Stored successfully.',
        ]);
        // } catch (\Exception $e) {
        //     Log::error($e);
        //     return response()->json([
        //         'status' => 400,
        //         'message' => 'Data not found.',
        //     ]);
        // }
    }

    public function purchasepaymentstatusupdate(Request $request)
    {
        // try {
        $purchase_order_id = $request->purchase_order_id;

        $indent_request = PurchaseOrder::findOrFail($purchase_order_id);

        if ($request->payment_status != null) {
            $indent_request->payment_status = $request->payment_status;
            $indent_request->save();
        }

        return response()->json([
            'status' => 200,
            'data' => $indent_request,
            'message' => 'Payment Status Updated Successfully.',
        ]);
        // } catch (\Exception $e) {
        //     Log::error($e);
        //     return response()->json([
        //         'status' => 400,
        //         'message' => 'Data not found.',
        //     ]);
        // }
    }

    public function purchasepaymenttransactionedit(Request $request)
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

    public function purchasepaymenttransactionupdate(Request $request)
    {
        DB::beginTransaction();
        // try {
        $transaction_id = $request->transaction_id;

        if (isset($request->payment_details)) {
            $payment_details = json_decode($request->payment_details);
            foreach ($payment_details as $key => $payment_detail) {
                $payment_transaction = PaymentTransaction::findOrFail($transaction_id);
                $payment_transaction->payment_type_id = (int)$payment_detail->payment_type_id;
                $payment_transaction->amount = $payment_detail->amount;
                $payment_transaction->transaction_datetime = $payment_detail->transaction_datetime;
                $payment_transaction->status = 1;
                $payment_transaction->note = @$payment_detail->note;
                $payment_transaction->save();

                // Payment Transaction Docs Store
                Log::info($request->documents);
                if (isset($request->payment_transaction_documents) && count($request->payment_transaction_documents) > 0 && $request->file('payment_transaction_documents')) {
                    CommonComponent::payment_transaction_documents($request->file('payment_transaction_documents'), 1, $payment_transaction->id); // 1=> Purchase Document
                }
            }
        }

        $purchase_order_details = PurchaseOrder::with('purchase_order_transactions')->findOrFail($payment_transaction->reference_id);

        $paid_amount = $purchase_order_details->purchase_order_transactions->sum('amount');

        $total_amount = $purchase_order_details->total;

        if ($paid_amount == 0) {
            $purchase_order_details->payment_status = $request->payment_status != null ? $request->payment_status : 2; // UnPaid
            $purchase_order_details->save();
        } else if ($paid_amount < $total_amount) {
            $purchase_order_details->payment_status = $request->payment_status != null ? $request->payment_status : 3; // Pending
            $purchase_order_details->save();
        } else if ($paid_amount >= $total_amount) {
            $purchase_order_details->payment_status = $request->payment_status != null ? $request->payment_status : 1; // Paid
            $purchase_order_details->save();
        }
        DB::commit();
        return response()->json([
            'status' => 200,
            'data' => $purchase_order_details,
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

    public function purchasepaymenttransactiondelete(Request $request)
    {
        // try {
        $transaction_id = $request->transaction_id;

        // $purchase_order_id = $request->purchase_order_id;

        $trans = PaymentTransaction::where('id', $transaction_id)->first();

        $purchase_order_id = $trans->reference_id;
        PaymentTransactionDocument::where('reference_id', $transaction_id)->delete();
        PaymentTransaction::destroy($transaction_id);

        $purchase_order_details = PurchaseOrder::with('purchase_order_transactions')->findOrFail($purchase_order_id);

        $paid_amount = $purchase_order_details->purchase_order_transactions->sum('amount');

        $total_amount = $purchase_order_details->total;

        if ($paid_amount == 0) {
            $purchase_order_details->payment_status = $request->payment_status != null ? $request->payment_status : 2; // UnPaid
            $purchase_order_details->save();
        } else if ($paid_amount < $total_amount) {
            $purchase_order_details->payment_status = $request->payment_status != null ? $request->payment_status : 3; // Pending
            $purchase_order_details->save();
        } else if ($paid_amount >= $total_amount) {
            $purchase_order_details->payment_status = $request->payment_status != null ? $request->payment_status : 1; // Paid
            $purchase_order_details->save();
        }

        $sale_order_detail = PurchaseOrder::findOrFail($purchase_order_id);


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

    public function multiplepurchaseorderpaymentupdate(Request $request)
    {
        DB::beginTransaction();
        try {
            $supplier_id = $request->supplier_id;
            $total_paying_amount = $request->amount != null ? $request->amount : 0;
            $advance_amount = $request->advance_amount;
            if ($request->advance_amount_included == 1) {
                $total_paying_amount = $request->amount + $request->advance_amount;
            }

            $purchase_order_ids = $request->purchase_order_id;
            foreach ($purchase_order_ids as $key => $purchase_order_id) {
                if ($total_paying_amount > 0) {
                    $purchase_order_details = PurchaseOrder::findOrFail($purchase_order_id);
                    $paid_amount = $purchase_order_details->purchase_order_transactions->sum('amount');
                    $total_amount = $purchase_order_details->total;
                    $due_amount = $total_amount - $paid_amount;
                    if ($advance_amount > 0) {
                        if ($due_amount >= $advance_amount) {
                            $this->useradvancecreditdebit(1, $purchase_order_id, $supplier_id, $advance_amount, 2); // 1 => referrence table, 2=>debit
                            $advance_amount = 0;
                        } else {
                            $advance_amount = $advance_amount - $due_amount;
                            $this->useradvancecreditdebit(1, $purchase_order_id, $supplier_id, $due_amount, 2); // 1 => referrence table, 2=>debit
                        }
                    }

                    if ($due_amount >= $total_paying_amount) {
                        $paid_amount = $total_paying_amount;
                        $total_paying_amount = 0;
                    } else {
                        $total_paying_amount = $total_paying_amount - $due_amount;
                        $paid_amount = $due_amount;
                    }

                    $payment_transaction = new PaymentTransaction();
                    $payment_transaction->transaction_number = CommonComponent::invoice_no('payment_transaction'); // Auto Generate
                    $payment_transaction->transaction_type = 1; // Purchase Order
                    $payment_transaction->type = 2; // Debit
                    $payment_transaction->reference_id = $purchase_order_id;
                    $payment_transaction->payment_type_id = $request->payment_type;
                    $payment_transaction->amount = $paid_amount;
                    $payment_transaction->transaction_datetime = Carbon::now();
                    $payment_transaction->status = 1;
                    $payment_transaction->save();

                    // Payment Transaction Docs Store
                    if (isset($request->payment_transaction_documents) && count($request->payment_transaction_documents) > 0 && $request->file('payment_transaction_documents')) {
                        CommonComponent::payment_transaction_documents($request->file('payment_transaction_documents'), 1, $payment_transaction->id); // 1=> Purchase Document
                    }

                    // $total_paying_amount -= $due_amount;
                    $purchase_order_details = PurchaseOrder::findOrFail($purchase_order_id);
                    $paid_amount = $purchase_order_details->purchase_order_transactions->sum('amount');
                    $total_amount = $purchase_order_details->total;
                    if ($paid_amount == 0) {
                        $purchase_order_details->payment_status = (isset($request->payment_status) && ($request->payment_status != null)) ? $request->payment_status : 2; // UnPaid
                        $purchase_order_details->save();
                    } else if ($paid_amount >= $total_amount) {
                        $purchase_order_details->payment_status = (isset($request->payment_status) && ($request->payment_status != null)) ? $request->payment_status : 1; // Paid
                        $purchase_order_details->save();
                    } else if ($paid_amount < $total_amount) {
                        $purchase_order_details->payment_status = (isset($request->payment_status) && ($request->payment_status != null)) ? $request->payment_status : 3; // Pending
                        $purchase_order_details->save();
                    }
                }
            }

            if ($total_paying_amount > 0) {
                $this->useradvancecreditdebit(NULL, NULL, $supplier_id, $total_paying_amount, 1); // 1 => referrence table, 1=>credit
            }

            $multi_transaction = new PurchaseOrderMultiTransaction();
            $multi_transaction->purchase_order_id = json_encode($request->purchase_order_id);
            $multi_transaction->supplier_id = $request->supplier_id;
            $multi_transaction->amount = $request->amount;
            $multi_transaction->advance_amount_included = $request->advance_amount_included;
            $multi_transaction->advance_amount = $request->advance_amount;
            $multi_transaction->payment_type_id = $request->payment_type;
            $multi_transaction->transaction_date = $request->transaction_date;
            $multi_transaction->remarks = $request->remarks;
            $multi_transaction->save();

            DB::commit();
            return response()->json([
                'status' => 200,
                'message' => 'Transaction Stored successfully.',
            ]);
        } catch (\Exception $e) {
            Log::error($e);
            DB::rollback();
            return response()->json([
                'status' => 400,
                'message' => 'There are some Technical Issue, Kindly contact Admin.',
            ]);
        }
    }

    public function useradvancecreditdebit($referrence_table, $referrence_id, $supplier_id, $amount, $creditdebit)
    {
        DB::beginTransaction();
        if ($amount > 0) {
            $user_advance = UserAdvance::where([['user_id', $supplier_id]])->first();
            if ($user_advance == null) {
                $user_advance = new UserAdvance();
            }
            $user_advance->user_id = $supplier_id;
            $user_advance->type = $creditdebit; // Credit is 1 and 2 is debit
            $user_advance->amount = $amount;
            if ($creditdebit == 1) {
                $user_advance->total_amount = @$user_advance->total_amount + $amount;
            } else {
                $user_advance->total_amount = @$user_advance->total_amount - $amount;
            }
            $user_advance->save();

            $advancehistory = new UserAdvanceHistory();
            $advancehistory->user_id = $supplier_id;;
            $advancehistory->transaction_type = $referrence_table; // Purchase
            $advancehistory->reference_id = $referrence_id;
            $advancehistory->type = $creditdebit; // Credit is 1 and 2 is debit
            $advancehistory->amount = $amount;
            $advancehistory->save();

            DB::commit();
        }
    }
}
