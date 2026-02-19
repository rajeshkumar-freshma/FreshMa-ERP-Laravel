<?php

namespace App\Http\Controllers\Admin\Product;

use App\Core\CommonComponent;
use App\DataTables\Product\ProductTransferDataTable;
use App\Http\Controllers\Controller;
use App\Models\IncomeExpenseType;
use App\Models\PaymentTransaction;
use App\Models\PaymentTransactionDocument;
use App\Models\PaymentType;
use App\Models\Product;
use App\Models\ProductTransfer;
use App\Models\ProductTransferDetail;
use App\Models\ProductTransferExpense;
use App\Models\Store;
use App\Models\StoreIndentRequest;
use App\Models\StoreInventoryDetail;
use App\Models\StoreStockUpdate;
use App\Models\TransportTracking;
use App\Models\TransportType;
use App\Models\Unit;
use App\Models\Warehouse;
use App\Models\WarehouseInventoryDetail;
use App\Models\WarehouseStockUpdate;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ProductTransferController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(ProductTransferDataTable $dataTable)
    {
        return $dataTable->render('pages.product.product_transfer.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $data['warehouses'] = Warehouse::get();
        $data['stores'] = Store::get();
        $data['transport_types'] = TransportType::active()->get();
        $data['expense_types'] = IncomeExpenseType::active()->get();
        $data['store_indent_requests'] = StoreIndentRequest::select('id', 'store_id', 'request_code')->get();
        $data['units'] = Unit::active()->get();
        $data['payment_types'] = PaymentType::where('status', 1)
            ->where('payment_category', '!=', 2)
            ->orWhereIn('store_id', Auth::user()->user_stores())
            ->get();
        return view('pages.product.product_transfer.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        DB::beginTransaction();
        // try {
        // return $request->all();
        if ($request->transfer_from == 1 && $request->transfer_to == 1) {
            return back()->with('error', 'Transferring products between two warehouses is not permitted.');
        }

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
        $product_transfer->transfer_received_date = $request->transfer_received_date;
        $product_transfer->status = $request->status;
        $product_transfer->remarks = $request->remarks;
        $product_transfer->is_notification_send_to_admin = 1;
        $product_transfer->save();

        $product_transfer_id = $product_transfer->id;
        $products = $request->products;
        if (count($products) > 0) {
            foreach ($products['product_id'] as $key => $product) {
                $product_data = Product::findOrfail($products['product_id'][$key]);
                $product_transfer_product_detail = new ProductTransferDetail();
                $product_transfer_product_detail->product_transfer_id = $product_transfer_id;
                $product_transfer_product_detail->product_id = $product_data->id;
                $product_transfer_product_detail->sku_code = $product_data->sku_code;
                $product_transfer_product_detail->name = $product_data->name;
                $product_transfer_product_detail->request_quantity = @$products['quantity'][$key];
                $product_transfer_product_detail->given_quantity = @$products['quantity'][$key];
                $product_transfer_product_detail->unit_id = @$products['unit_id'][$key];
                $product_transfer_product_detail->is_inc_exp_billable = @$products['unit_id'][$key];
                $product_transfer_product_detail->save();
                // $quantity = -@$products['quantity'][$key];
                // $to_quantity = @$products['quantity'][$key];
                $quantity = -(int) ($products['quantity'][$key] ?? 0);
                $to_quantity = (int) ($products['quantity'][$key] ?? 0);

                // Update warehouse stocks
                if ($request->from_warehouse_id && $quantity != 0) {
                    $this->storeWarehouseStock($request->from_warehouse_id, $product_data->id, $quantity, $request->box_number);
                }
                if ($request->status == 10 && $request->to_warehouse_id && $to_quantity != 0) {
                    $this->storeWarehouseStock($request->to_warehouse_id, $product_data->id, $to_quantity, $request->box_number);
                }

                if ($request->from_store_id != null && $request->from_store_id != "null") {
                    if ($quantity != 0) {
                        $store_stock_detail_exists = StoreStockUpdate::where([['store_id', $request->from_store_id], ['product_id', $product_data->id], ['status', 1]])->orderBy('id', 'DESC')->first();
                        $store_stock_detail = new StoreStockUpdate();
                        $store_stock_detail->from_warehouse_id = null;
                        $store_stock_detail->store_id = $request->from_store_id;
                        $store_stock_detail->product_id = $product_data->id;
                        $store_stock_detail->reference_id = $product_transfer->id;
                        $store_stock_detail->reference_table = 6; //10 Product Transfer table
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
                if ($request->status == 10 && $request->to_store_id && $to_quantity != 0) {
                    $this->storeStoreStock($request->to_store_id, $product_data->id, $to_quantity, $product_transfer_id, $request->from_warehouse_id);
                }

                // if ($request->from_warehouse_id != null && $request->from_warehouse_id != "null") {
                //     if ($quantity != 0) {
                //         $warehouse_stock_detail_exists = WarehouseStockUpdate::where([['warehouse_id', $request->from_warehouse_id], ['product_id', $product_data->id], ['status', 1]])->orderBy('id', 'DESC')->first();
                //         $warehouse_stock_detail = new WarehouseStockUpdate();
                //         $warehouse_stock_detail->warehouse_id = $request->from_warehouse_id;
                //         $warehouse_stock_detail->product_id = $product_data->id;
                //         $warehouse_stock_detail->stock_update_on = Carbon::now();
                //         $warehouse_stock_detail->existing_stock = ($warehouse_stock_detail_exists != null && $warehouse_stock_detail_exists->total_stock != null) ? $warehouse_stock_detail_exists->total_stock : 0;
                //         $warehouse_stock_detail->adding_stock = @$quantity;
                //         $warehouse_stock_detail->total_stock = ($warehouse_stock_detail_exists != null && $warehouse_stock_detail_exists->total_stock != null) ? $warehouse_stock_detail_exists->total_stock + @$quantity : @$quantity;
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
                //     $warehouse_inventory->weight = @$warehouse_inventory->weight + @$quantity;
                //     $warehouse_inventory->status = 1;
                //     $warehouse_inventory->save();
                // }

                // if ($request->to_warehouse_id != null && $request->to_warehouse_id != "null") {
                //     if ($to_quantity != 0) {
                //         $warehouse_stock_detail_exists = WarehouseStockUpdate::where([['warehouse_id', $request->to_warehouse_id], ['product_id', $product_data->id], ['status', 1]])->orderBy('id', 'DESC')->first();
                //         $warehouse_stock_detail = new WarehouseStockUpdate();
                //         $warehouse_stock_detail->warehouse_id = $request->to_warehouse_id;
                //         $warehouse_stock_detail->product_id = $product_data->id;
                //         $warehouse_stock_detail->stock_update_on = Carbon::now();
                //         $warehouse_stock_detail->existing_stock = ($warehouse_stock_detail_exists != null && $warehouse_stock_detail_exists->total_stock != null) ? $warehouse_stock_detail_exists->total_stock : 0;
                //         $warehouse_stock_detail->adding_stock = @$to_quantity;
                //         $warehouse_stock_detail->total_stock = ($warehouse_stock_detail_exists != null && $warehouse_stock_detail_exists->total_stock != null) ? $warehouse_stock_detail_exists->total_stock + @$to_quantity : @$to_quantity;
                //         $warehouse_stock_detail->status = 1;
                //         $warehouse_stock_detail->box_number = $request->box_number != null ? $request->box_number : 1;
                //         $warehouse_stock_detail->save();
                //     }
                //     $warehouse_inventory = WarehouseInventoryDetail::where([['warehouse_id', $request->to_warehouse_id], ['product_id', $product_data->id], ['status', 1]])->first();
                //     if ($warehouse_inventory == null) {
                //         $warehouse_inventory = new WarehouseInventoryDetail();
                //         $warehouse_inventory->warehouse_id = $request->to_warehouse_id;
                //         $warehouse_inventory->product_id = $product_data->id;
                //     }
                //     $warehouse_inventory->weight = @$warehouse_inventory->weight + @$to_quantity;
                //     $warehouse_inventory->status = 1;
                //     $warehouse_inventory->save();
                // }
                // if ($request->from_store_id != null && $request->from_store_id != "null") {
                //     if ($quantity != 0) {
                //         $store_stock_detail_exists = StoreStockUpdate::where([['store_id', $request->from_store_id], ['product_id', $product_data->id], ['status', 1]])->orderBy('id', 'DESC')->first();
                //         $store_stock_detail = new StoreStockUpdate();
                //         $store_stock_detail->from_warehouse_id = NULL;
                //         $store_stock_detail->store_id = $request->from_store_id;
                //         $store_stock_detail->product_id = $product_data->id;
                //         $store_stock_detail->reference_id = $product_transfer->id;
                //         $store_stock_detail->reference_table = 6; //10 Product Transfer table
                //         $store_stock_detail->stock_update_on = Carbon::now();
                //         $store_stock_detail->existing_stock = ($store_stock_detail_exists != null && $store_stock_detail_exists->total_stock != null) ? $store_stock_detail_exists->total_stock : 0;
                //         $store_stock_detail->adding_stock = @$quantity;
                //         $store_stock_detail->total_stock = ($store_stock_detail_exists != null && $store_stock_detail_exists->total_stock != null) ? $store_stock_detail_exists->total_stock + @$quantity : @$quantity;
                //         $store_stock_detail->status = 1;
                //         $store_stock_detail->save();
                //     }

                //     $store_stock_detail = StoreStockUpdate::where([['store_id', $request->from_store_id], ['product_id', $product_data->id], ['status', 1]])->orderBy('id', 'DESC')->first();
                //     $store_inventory = StoreInventoryDetail::where([['store_id', $request->from_store_id], ['product_id', $product_data->id], ['status', 1]])->first();
                //     if ($store_inventory == null) {
                //         $store_inventory = new StoreInventoryDetail();
                //         $store_inventory->store_id = $request->from_store_id;
                //         $store_inventory->product_id = $product_data->id;
                //     }
                //     $store_inventory->weight = @$store_inventory->weight + @$quantity;
                //     $store_inventory->status = 1;
                //     $store_inventory->save();
                // }
                // if ($request->to_store_id != null && $request->to_store_id != "null") {
                //     if ($to_quantity != 0) {
                //         $store_stock_detail_exists = StoreStockUpdate::where([['store_id', $request->to_store_id], ['product_id', $product_data->id], ['status', 1]])->orderBy('id', 'DESC')->first();
                //         $store_stock_detail = new StoreStockUpdate();
                //         $store_stock_detail->from_warehouse_id = $request->from_warehouse_id;
                //         $store_stock_detail->store_id = $request->to_store_id;
                //         $store_stock_detail->product_id = $product_data->id;
                //         $store_stock_detail->reference_id = $product_transfer->id;
                //         $store_stock_detail->reference_table = 6; //10 Product Transfer table
                //         $store_stock_detail->stock_update_on = Carbon::now();
                //         $store_stock_detail->existing_stock = ($store_stock_detail_exists != null && $store_stock_detail_exists->total_stock != null) ? $store_stock_detail_exists->total_stock : 0;
                //         $store_stock_detail->adding_stock = @$to_quantity;
                //         $store_stock_detail->total_stock = ($store_stock_detail_exists != null && $store_stock_detail_exists->total_stock != null) ? $store_stock_detail_exists->total_stock + @$to_quantity : @$to_quantity;
                //         $store_stock_detail->status = 1;
                //         $store_stock_detail->save();
                //     }
                //     $store_stock_detail = StoreStockUpdate::where([['store_id', $request->to_store_id], ['product_id', $product_data->id], ['status', 1]])->orderBy('id', 'DESC')->first();
                //     $store_inventory = StoreInventoryDetail::where([['store_id', $request->to_store_id], ['product_id', $product_data->id], ['status', 1]])->first();
                //     if ($store_inventory == null) {
                //         $store_inventory = new StoreInventoryDetail();
                //         $store_inventory->store_id = $request->to_store_id;
                //         $store_inventory->product_id = $product_data->id;
                //     }
                //     $store_inventory->weight = @$store_inventory->weight + @$to_quantity;
                //     $store_inventory->status = 1;
                //     $store_inventory->save();
                // }
            }
        }

        // Expense Details store
        $total_expense_amount = 0;
        $total_expense_billable_amount = 0;
        $expense = $request->expense;
        if (count($expense) > 0 && $expense['expense_type_id'][0] != null) {
            foreach ($expense['expense_type_id'] as $expense_key => $exp) {
                $product_transfer_expense = new ProductTransferExpense();
                $product_transfer_expense->product_transfer_id = $product_transfer_id;
                $product_transfer_expense->income_expense_id = @$expense['expense_type_id'][$expense_key];
                $product_transfer_expense->ie_amount = @$expense['expense_amount'][$expense_key];
                $product_transfer_expense->is_billable = @$expense['is_billable'][$expense_key];
                if (@$expense['is_billable'][$expense_key] == 1) {
                    $total_expense_billable_amount += @$expense['expense_amount'][$expense_key];
                }
                $total_expense_amount += @$expense['expense_amount'][$expense_key];
                $product_transfer_expense->save();
            }
            $product_transfer = ProductTransfer::findOrFail($product_transfer_id);
            $product_transfer->total_expense_billable_amount = $total_expense_billable_amount;
            $product_transfer->is_inc_exp_billable_for_all = $total_expense_billable_amount > 0 ? 1 : 0;
            $product_transfer->total_expense_billable_amount = $total_expense_billable_amount;
            $product_transfer->total_expense_amount = $total_expense_amount;
            $product_transfer->save();
        }

        $transport_tracking = $request->transport_tracking;
        if (count($transport_tracking) > 0) {
            foreach ($transport_tracking['transport_type_id'] as $track_key => $exp) {
                $imagePath = null;
                $imageUrl = null;
                if ($request->hasFile('transport_tracking.transport_tracking_file.' . $track_key)) {
                    $imageData = CommonComponent::s3BucketFileUpload($transport_tracking['transport_tracking_file'][$track_key], 'product_transfer_transport');
                    $imagePath = $imageData['filePath'];
                    // $imageUrl = $imageData['fileName'];
                    $imageUrl = $imageData['imageURL'];
                }

                $transport_trackings = new TransportTracking();
                $transport_trackings->product_transfer_id = $product_transfer_id;
                $transport_trackings->transport_type_id = $transport_tracking['transport_type_id'][$track_key];
                $transport_trackings->transport_name = $transport_tracking['transport_name'][$track_key];
                $transport_trackings->transport_number = $transport_tracking['transport_number'][$track_key];
                $transport_trackings->departure_datetime = $transport_tracking['departure_datetime'][$track_key];
                $transport_trackings->arriving_datetime = $transport_tracking['arriving_datetime'][$track_key];
                $transport_trackings->from_location = $transport_tracking['from_location'][$track_key];
                $transport_trackings->to_location = $transport_tracking['to_location'][$track_key];
                if ($imageUrl != null) {
                    $transport_trackings->file = $imageUrl;
                    $transport_trackings->file_path = $imagePath;
                }
                $transport_trackings->save();
            }
        }

        // Payment Transaction Details store
        $payment_details = $request->payment_details;
        if (count($payment_details) > 0 && $payment_details['payment_type_id'][0] != null) {
            foreach ($payment_details['payment_type_id'] as $payment_key => $payment) {
                $payment_transaction = new PaymentTransaction();
                $payment_transaction->transaction_number = CommonComponent::invoice_no('payment_transaction'); // Auto Generate
                $payment_transaction->transaction_type = 7; // Product transfer
                $payment_transaction->type = 1; // Credit
                $payment_transaction->reference_id = $product_transfer->id;
                $payment_transaction->payment_type_id = @$payment_details['payment_type_id'][$payment_key];
                $payment_transaction->amount = @$payment_details['transaction_amount'][$payment_key];
                $payment_transaction->transaction_datetime = @$payment_details['transaction_datetime'][$payment_key];
                $payment_transaction->note = @$payment_details['remark'][$payment_key];
                $payment_transaction->status = 1;
                // return $payment_transaction;
                $payment_transaction->save();

                if (isset($payment_details['payment_transaction_documents'][$payment_key]) && count($payment_details['payment_transaction_documents']) > 0) {
                    CommonComponent::payment_transaction_documents(($payment_details['payment_transaction_documents'][$payment_key]), 1, $payment_transaction->id); // 1=> Purchase Document
                }
            }

            $product_transfer_details = ProductTransfer::with('purchase_order_transactions')->findOrFail($product_transfer->id);

            $paid_amount = $product_transfer_details->purchase_order_transactions->sum('amount');

            $total_amount = $product_transfer_details->total;

            // if ($paid_amount == 0) {
            //     $product_transfer_details->payment_status = $request->payment_status != null ? $request->payment_status : 2; // UnPaid
            //     $product_transfer_details->save();
            // } else if ($paid_amount < $total_amount) {
            //     $product_transfer_details->payment_status = $request->payment_status != null ? $request->payment_status : 3; // Pending
            //     $product_transfer_details->save();
            // } else if ($paid_amount >= $total_amount) {
            //     $product_transfer_details->payment_status = $request->payment_status != null ? $request->payment_status : 1; // Paid
            //     $product_transfer_details->save();
            // }
        }
        DB::commit();

        if ($request->submission_type == 1) {
            return redirect()->route('admin.product-transfer.index')->with('success', 'Product Transfer Stored Successfully');
        } elseif ($request->submission_type == 2) {
            return back()->with('success', 'Product Transfer Stored Successfully');
        }
        // } catch (\Exception $e) {
        //     Log::error($e);
        //     DB::rollback();
        //     return back()->with('danger', 'Product Transfer Stored Fail.');
        // }
    }
    private function storeWarehouseStock($warehouse_id, $product_id, $quantity, $box_number = 1)
    {
        $warehouse_stock_detail_exists = WarehouseStockUpdate::where([
            ['warehouse_id', $warehouse_id],
            ['product_id', $product_id],
            ['status', 1],
        ])->orderBy('id', 'DESC')->first();

        $warehouse_stock_detail = new WarehouseStockUpdate();
        $warehouse_stock_detail->warehouse_id = $warehouse_id;
        $warehouse_stock_detail->product_id = $product_id;
        $warehouse_stock_detail->stock_update_on = Carbon::now();
        $warehouse_stock_detail->existing_stock = $warehouse_stock_detail_exists ? $warehouse_stock_detail_exists->total_stock : 0;
        $warehouse_stock_detail->adding_stock = $quantity;
        $warehouse_stock_detail->total_stock = $warehouse_stock_detail->existing_stock + $quantity;
        $warehouse_stock_detail->status = 1;
        $warehouse_stock_detail->box_number = $box_number ?: 1;
        $warehouse_stock_detail->save();

        // Update warehouse inventory
        $warehouse_inventory = WarehouseInventoryDetail::where([
            ['warehouse_id', $warehouse_id],
            ['product_id', $product_id],
            ['status', 1],
        ])->first();

        if (!$warehouse_inventory) {
            $warehouse_inventory = new WarehouseInventoryDetail();
            $warehouse_inventory->warehouse_id = $warehouse_id;
            $warehouse_inventory->product_id = $product_id;
        }

        $warehouse_inventory->weight += $quantity;
        $warehouse_inventory->status = 1;
        $warehouse_inventory->save();
    }

    // Helper function to update store stock
    private function storeStoreStock($store_id, $product_id, $quantity, $reference_id, $from_warehouse_id = null)
    {
        $store_stock_detail_exists = StoreStockUpdate::where([
            ['store_id', $store_id],
            ['product_id', $product_id],
            ['status', 1],
        ])->orderBy('id', 'DESC')->first();
        Log::info("store_stock_detail_exists");
        Log::info($store_stock_detail_exists);
        $store_stock_detail = new StoreStockUpdate();
        $store_stock_detail->from_warehouse_id = $from_warehouse_id;
        $store_stock_detail->store_id = $store_id;
        $store_stock_detail->product_id = $product_id;
        $store_stock_detail->reference_id = $reference_id;
        $store_stock_detail->reference_table = 6; // Product Transfer table
        $store_stock_detail->stock_update_on = Carbon::now();
        $store_stock_detail->existing_stock = $store_stock_detail_exists ? $store_stock_detail_exists->total_stock : 0;
        $store_stock_detail->adding_stock = $quantity;
        $store_stock_detail->total_stock = $store_stock_detail->existing_stock + $quantity;
        $store_stock_detail->status = 1;
        $store_stock_detail->save();
        Log::info("from store to to store_stock_detail");
        Log::info($store_stock_detail);
        // Update store inventory
        $store_inventory = StoreInventoryDetail::where([
            ['store_id', $store_id],
            ['product_id', $product_id],
            ['status', 1],
        ])->first();

        if (!$store_inventory) {
            $store_inventory = new StoreInventoryDetail();
            $store_inventory->store_id = $store_id;
            $store_inventory->product_id = $product_id;
        }

        $store_inventory->weight += $quantity;
        $store_inventory->status = 1;
        $store_inventory->save();
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $data = $common_data = $this->product_overview($id);
        return view('pages.product.product_transfer.show', $data);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $data['indent_request'] = ProductTransfer::findOrfail($id);
        $data['product_transfer'] = ProductTransfer::find($id);
        $data['warehouses'] = Warehouse::get();
        $data['stores'] = Store::get();
        $data['transport_types'] = TransportType::active()->get();
        $data['expense_types'] = IncomeExpenseType::active()->get();
        $data['units'] = Unit::active()->get();
        $data['payment_types'] = PaymentType::where('status', 1)
            ->where('payment_category', '!=', 2)
            ->orWhereIn('store_id', Auth::user()->user_stores())
            ->get();
        return view('pages.product.product_transfer.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        DB::beginTransaction();
        // try {
        // return $request->all();
        $indent_request = ProductTransfer::findOrfail($id);
        $product_transfer = ProductTransfer::find($id);
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
        $product_transfer->transfer_received_date = $request->transfer_received_date;
        $product_transfer->status = $request->status;
        $product_transfer->remarks = $request->remarks;
        $product_transfer->is_notification_send_to_admin = 1;
        $product_transfer->save();

        $request_old_ids = [];
        foreach ($request->products['product_id'] as $check_key => $value) {
            if ($request->products['id'][$check_key] != null) {
                $request_old_ids[] = $request->products['id'][$check_key];
            }
        }

        $exists_product_transfer_details = ProductTransferDetail::where('product_transfer_id', $id)->get();
        if (count($exists_product_transfer_details) > 0) {
            foreach ($exists_product_transfer_details as $exists_key => $value) {
                if (!in_array($value->id, $request_old_ids)) {
                    ProductTransferDetail::where('id', $value->id)->delete();
                }
            }
        }

        $product_transfer_id = $product_transfer->id;
        $products = $request->products;
        if (count($products) > 0) {
            foreach ($products['product_id'] as $key => $product) {
                $product_data = Product::findOrfail($products['product_id'][$key]);
                $old_weight = 0;
                if (isset($products['id'][$key])) {
                    if (in_array($products['id'][$key], $exists_product_transfer_details->pluck('id')->toArray())) {
                        $product_transfer_product_detail = ProductTransferDetail::findOrFail($products['id'][$key]);
                        $old_weight = $product_transfer_product_detail->given_quantity;
                    }
                } else {
                    $product_transfer_product_detail = new ProductTransferDetail();
                }
                $product_transfer_product_detail->product_transfer_id = $product_transfer_id;
                $product_transfer_product_detail->product_id = $product_data->id;
                $product_transfer_product_detail->sku_code = $product_data->sku_code;
                $product_transfer_product_detail->name = $product_data->name;
                $product_transfer_product_detail->request_quantity = @$products['quantity'][$key];
                $product_transfer_product_detail->given_quantity = @$products['quantity'][$key];
                $product_transfer_product_detail->unit_id = @$products['unit_id'][$key];
                $product_transfer_product_detail->save();

                // $quantity = -(int)($products['quantity'][$key] ?? 0);
                // $to_quantity = (int)($products['quantity'][$key] ?? 0);

                // $quantity =  - ($product_transfer_product_detail->given_quantity == $old_weight ? 0 : @$products['quantity'][$key] - $old_weight);

                // $to_quantity = $product_transfer_product_detail->given_quantity == $old_weight ? 0 : @$products['quantity'][$key] - $old_weight;

                $box_no = $request->box_number ?? 1;

                if ($product_transfer_product_detail->given_quantity == $old_weight) {
                    $to_quantity = $product_transfer_product_detail->given_quantity;
                } else if ($product_transfer_product_detail->given_quantity > $old_weight) {
                    $to_quantity = ($product_transfer_product_detail->given_quantity - $old_weight);
                    $to_quantity += ($product_transfer_product_detail->given_quantity + $to_quantity);
                } else {
                    $to_quantity = $old_weight - $product_transfer_product_detail->given_quantity;
                }

                $quantity = -($product_transfer_product_detail->given_quantity == $old_weight ? 0 : @$products['quantity'][$key] - $old_weight);
                if ($request->from_warehouse_id && $quantity != 0) {
                    $this->handleWarehouseStockUpdate($request->from_warehouse_id, $product_data->id, $quantity, $box_no);
                }
                if ($request->status == 10 && $request->to_warehouse_id && $to_quantity != 0) {

                    $this->handleWarehouseStockUpdate($request->to_warehouse_id, $product_data->id, $to_quantity, $box_no);
                }
                if ($request->from_store_id && $quantity != 0) {
                    $this->handleStoreStockUpdate($request->from_store_id, $product_data->id, $quantity, $request->from_warehouse_id, $product_transfer_id);
                }
                if ($request->status == 10 && $request->to_store_id && $to_quantity != 0) {

                    $this->handleStoreStockUpdate($request->to_store_id, $product_data->id, $to_quantity, $request->from_warehouse_id, $product_transfer_id);
                }
            }
        }

        // Expense Details store
        $request_old_expense_ids = [];
        if (isset($request->expense['expense_type_id']) && count($request->expense['expense_type_id']) > 0) {
            foreach ($request->expense['expense_type_id'] as $exp_key => $value) {
                if ($request->expense['expense_id'][$exp_key] != null) {
                    $request_old_expense_ids[] = $request->expense['expense_id'][$exp_key];
                }
            }
        }

        $pte_details = ProductTransferExpense::where('product_transfer_id', $id)->get();
        if (count($pte_details) > 0) {
            foreach ($pte_details as $exists_key => $value) {
                if (!in_array($value->id, $request_old_expense_ids)) {
                    ProductTransferExpense::where('id', $value->id)->delete();
                }
            }
        }
        // Expense Details store
        $total_expense_amount = 0;
        $total_expense_billable_amount = 0;
        $expense = $request->expense;
        if (count($expense) > 0 && $expense['expense_type_id'][0] != null) {
            foreach ($expense['expense_type_id'] as $expense_key => $exp) {
                if (isset($expense['expense_id'][$expense_key]) && $expense['expense_id'][$expense_key] != null) {
                    if (in_array($expense['expense_id'][$expense_key], $pte_details->pluck('id')->toArray())) {
                        $product_transfer_expense = ProductTransferExpense::findOrFail($expense['expense_id'][$expense_key]);
                    }
                } else {
                    $product_transfer_expense = new ProductTransferExpense();
                }
                $product_transfer_expense->product_transfer_id = $product_transfer_id;
                $product_transfer_expense->income_expense_id = @$expense['expense_type_id'][$expense_key];
                $product_transfer_expense->ie_amount = @$expense['expense_amount'][$expense_key];
                $product_transfer_expense->is_billable = @$expense['is_billable'][$expense_key];
                if (@$expense['is_billable'][$expense_key] == 1) {
                    $total_expense_billable_amount += @$expense['expense_amount'][$expense_key];
                }
                $total_expense_amount += @$expense['expense_amount'][$expense_key];
                $product_transfer_expense->save();
            }
            $product_transfer = ProductTransfer::findOrFail($product_transfer_id);
            $product_transfer->total_expense_billable_amount = $total_expense_billable_amount;
            $product_transfer->total_expense_amount = $total_expense_amount;
            $product_transfer->save();
        }
        // Payment Details store
        $request_old_payment_ids = [];
        if (isset($request->payment_details['payment_type_id']) && count($request->payment_details['payment_type_id']) > 0) {
            foreach ($request->payment_details['payment_type_id'] as $pay_key => $value) {
                if (isset($request->payment_details['payment_id'][$pay_key]) && $request->payment_details['payment_id'][$pay_key] != null) {
                    $request_old_payment_ids[] = $request->payment_details['payment_id'][$pay_key];
                }
            }
        }

        $poe_details = PaymentTransaction::where('reference_id', $indent_request->id)->get();
        if (count($poe_details) > 0) {
            foreach ($poe_details as $exists_key => $value) {
                if (!in_array($value->id, $request_old_payment_ids)) {
                    PaymentTransactionDocument::where('reference_id', $value->id)->delete();
                    PaymentTransaction::where('id', $value->id)->delete();
                }
            }
        }

        // Payment Transaction Details store
        $payment_details = $request->payment_details;
        if (count($payment_details) > 0 && $payment_details['payment_type_id'] != null) {
            foreach ($payment_details['payment_type_id'] as $payment_key => $payment) {
                if ($payment_details['payment_type_id'][$payment_key] != null) {
                    if (isset($payment_details['payment_id'][$payment_key]) && $payment_details['payment_id'][$payment_key] != null) {
                        if (in_array($payment_details['payment_id'][$payment_key], $poe_details->pluck('id')->toArray())) {
                            $payment_transaction = PaymentTransaction::findOrFail($payment_details['payment_id'][$payment_key]);
                        }
                    } else {
                        $payment_transaction = new PaymentTransaction();
                        $payment_transaction->transaction_number = CommonComponent::invoice_no('payment_transaction'); // Auto Generate
                        $payment_transaction->transaction_type = 7; //Product Transfer
                        $payment_transaction->type = 1; // Credit
                        $payment_transaction->reference_id = $product_transfer->id;
                    }
                    $payment_transaction->payment_type_id = @$payment_details['payment_type_id'][$payment_key];
                    $payment_transaction->amount = @$payment_details['transaction_amount'][$payment_key];
                    $payment_transaction->transaction_datetime = @$payment_details['transaction_datetime'][$payment_key];
                    $payment_transaction->note = @$payment_details['remark'][$payment_key];
                    $payment_transaction->status = 1;
                    $payment_transaction->save();
                }

                if (isset($payment_details['payment_transaction_documents'][$payment_key]) && count($payment_details['payment_transaction_documents']) > 0) {
                    CommonComponent::payment_transaction_documents(($payment_details['payment_transaction_documents'][$payment_key]), 1, $payment_transaction->id); // 1=> Purchase Document
                }
            }

            $product_transfer_details = ProductTransfer::with('purchase_order_transactions')->findOrFail($product_transfer->id);

            $paid_amount = $product_transfer_details->purchase_order_transactions->sum('amount');

            $total_amount = $product_transfer_details->total;

            // if ($paid_amount == 0) {
            //     $product_transfer_details->payment_status = $request->payment_status != null ? $request->payment_status : 2; // UnPaid
            //     $product_transfer_details->save();
            // } else if ($paid_amount < $total_amount) {
            //     $product_transfer_details->payment_status = $request->payment_status != null ? $request->payment_status : 3; // Pending
            //     $product_transfer_details->save();
            // } else if ($paid_amount >= $total_amount) {
            //     $product_transfer_details->payment_status = $request->payment_status != null ? $request->payment_status : 1; // Paid
            //     $product_transfer_details->save();
            // }
        }

        $portt_details = TransportTracking::where('product_transfer_id', $id)->get();
        $request_old_sott_ids = [];
        if (isset($request->transport_tracking['transport_type_id']) && count($request->transport_tracking['transport_type_id']) > 0) {
            foreach ($request->transport_tracking['transport_type_id'] as $exp_key => $value) {
                if ($request->transport_tracking['transport_tracking_id'][$exp_key] != null) {
                    $request_old_sott_ids[] = $request->transport_tracking['transport_tracking_id'][$exp_key];
                }
            }
        }

        if (count($portt_details) > 0) {
            foreach ($portt_details as $exists_key => $value) {
                if (!in_array($value->id, $request_old_sott_ids)) {
                    TransportTracking::where('id', $value->id)->delete();
                }
            }
        }

        $transport_tracking = $request->transport_tracking;
        if (count($transport_tracking) > 0) {
            foreach ($transport_tracking['transport_type_id'] as $track_key => $exp) {
                $imagePath = null;
                $imageUrl = null;
                if ($request->hasFile('transport_tracking.transport_tracking_file.' . $track_key)) {
                    $imageData = CommonComponent::s3BucketFileUpload($transport_tracking['transport_tracking_file'][$track_key], 'purchase_order_return_transport');
                    $imagePath = $imageData['filePath'];
                    // $imageUrl = $imageData['fileName'];
                    $imageUrl = $imageData['imageURL'];
                }

                if (isset($transport_tracking['transport_tracking_id'][$track_key]) && $transport_tracking['transport_tracking_id'][$track_key] != null) {
                    if (in_array($transport_tracking['transport_tracking_id'][$track_key], $portt_details->pluck('id')->toArray())) {
                        $transport_trackings = TransportTracking::findOrFail($transport_tracking['transport_tracking_id'][$track_key]);
                    }
                } else {
                    $transport_trackings = new TransportTracking();
                }
                $transport_trackings->product_transfer_id = $product_transfer_id;
                $transport_trackings->transport_type_id = $transport_tracking['transport_type_id'][$track_key];
                $transport_trackings->transport_name = $transport_tracking['transport_name'][$track_key];
                $transport_trackings->transport_number = $transport_tracking['transport_number'][$track_key];
                $transport_trackings->departure_datetime = $transport_tracking['departure_datetime'][$track_key];
                $transport_trackings->arriving_datetime = $transport_tracking['arriving_datetime'][$track_key];
                $transport_trackings->from_location = $transport_tracking['from_location'][$track_key];
                $transport_trackings->to_location = $transport_tracking['to_location'][$track_key];
                if ($imageUrl != null) {
                    $transport_trackings->file = $imageUrl;
                    $transport_trackings->file_path = $imagePath;
                }
                $transport_trackings->save();
            }
        }
        DB::commit();

        if ($request->submission_type == 1) {
            return redirect()->route('admin.product-transfer.index')->with('success', 'Product Transfer Updated Successfully');
        } elseif ($request->submission_type == 2) {
            return back()->with('success', 'Product Transfer Updated Successfully');
        }
        // } catch (\Exception $e) {
        //     Log::error($e);
        //     DB::rollback();
        //     return back()->with('danger', 'Product Transfer Updated Fail.');
        // }
    }

    protected function handleWarehouseStockUpdate($box_no, $product_id, $quantity, $from_warehouse_id = null)
    {
        $warehouse_stock_detail_exists = WarehouseStockUpdate::where([
            ['warehouse_id', $from_warehouse_id],
            ['product_id', $product_id],
            ['status', 1],
        ])->orderBy('id', 'DESC')->first();

        $warehouse_stock_detail = new WarehouseStockUpdate();
        $warehouse_stock_detail->warehouse_id = $from_warehouse_id;
        $warehouse_stock_detail->product_id = $product_id;
        $warehouse_stock_detail->stock_update_on = now();
        $warehouse_stock_detail->existing_stock = $warehouse_stock_detail_exists ? $warehouse_stock_detail_exists->total_stock : 0;
        $warehouse_stock_detail->adding_stock = $quantity;
        $warehouse_stock_detail->total_stock = $warehouse_stock_detail->existing_stock + $quantity;
        $warehouse_stock_detail->status = 1;
        $warehouse_stock_detail->box_number = $box_no;
        $warehouse_stock_detail->save();

        // Update warehouse inventory
        $warehouse_inventory = WarehouseInventoryDetail::where([
            ['warehouse_id', $from_warehouse_id],
            ['product_id', $product_id],
            ['status', 1],
        ])->first();

        if (!$warehouse_inventory) {
            $warehouse_inventory = new WarehouseInventoryDetail();
            $warehouse_inventory->warehouse_id = $from_warehouse_id;
            $warehouse_inventory->product_id = $product_id;
        }

        $warehouse_inventory->weight += $quantity;
        $warehouse_inventory->status = 1;
        $warehouse_inventory->save();
    }

    protected function handleStoreStockUpdate($store_id, $product_id, $quantity, $from_warehouse_id, $product_transfer_id)
    {
        $store_stock_detail_exists = StoreStockUpdate::where([
            ['store_id', $store_id],
            ['product_id', $product_id],
            ['status', 1],
        ])->orderBy('id', 'DESC')->first();

        $store_stock_detail = new StoreStockUpdate();
        $store_stock_detail->from_warehouse_id = $from_warehouse_id;
        $store_stock_detail->store_id = $store_id;
        $store_stock_detail->product_id = $product_id;
        $store_stock_detail->reference_id = $product_transfer_id;
        $store_stock_detail->reference_table = 6; //6 Product Transfer table
        $store_stock_detail->stock_update_on = Carbon::now();
        $store_stock_detail->existing_stock = $store_stock_detail_exists ? $store_stock_detail_exists->total_stock : 0;
        $store_stock_detail->adding_stock = $quantity;
        $store_stock_detail->total_stock = $store_stock_detail_exists ? $store_stock_detail_exists->total_stock + $quantity : $quantity;
        $store_stock_detail->status = 1;
        $store_stock_detail->save();

        $store_inventory = StoreInventoryDetail::where('store_id', $store_id)
            ->where('product_id', $product_id)
            ->where('status', 1)
            ->first();

        if (!$store_inventory) {
            $store_inventory = new StoreInventoryDetail();
            $store_inventory->store_id = $store_id;
            $store_inventory->product_id = $product_id;
        }
        Log::info("from store to to store_stock_detail");
        Log::info($store_stock_detail);
        $store_inventory->weight += $quantity;
        $store_inventory->status = 1;
        $store_inventory->save();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function product_overview($id)
    {
        $data['product_transfer'] = ProductTransfer::findOrFail($id);
        return $data;
    }

    public function producttrans_data($id)
    {
        $data = $common_data = $this->product_overview($id);
        $data['product_transfer_details'] = ProductTransferDetail::where('product_transfer_id', $id)->orderBy('id', 'desc')->paginate(10);
        return view('pages.product.product_transfer.producttrans_table', $data);
    }

    public function transport_product_data($id)
    {
        $data = $common_data = $this->product_overview($id);
        $data['product_transport_trackings'] = TransportTracking::where('product_transfer_id', $id)->orderBy('id', 'desc')->paginate(10);
        return view('pages.product.product_transfer.transport_details', $data);
    }

    public function expences_product_data($id)
    {
        $data = $common_data = $this->product_overview($id);
        $data['product_expences'] = ProductTransferExpense::select('product_transfer_expenses.ie_amount', 'product_transfer_expenses.is_billable', 'income_expense_types.id as type_id', 'income_expense_types.name as type_name')
            ->leftJoin('income_expense_types', 'product_transfer_expenses.income_expense_id', '=', 'income_expense_types.id')
            ->where('product_transfer_id', $id)
            ->orderBy('product_transfer_expenses.id', 'desc')
            ->paginate(10);
        return view('pages.product.product_transfer.expences_details', $data);
    }

    public function payment_product_data($id)
    {
        $data = $common_data = $this->product_overview($id);

        $data['product_payments'] = PaymentTransaction::select('payment_transactions.*', 'payment_types.id as type_id', 'payment_types.payment_type as type_name')
            ->leftJoin('payment_types', 'payment_transactions.payment_type_id', '=', 'payment_types.id')
            ->where([
                ['reference_id', '=', $id],
                ['transaction_type', '=', 1],
            ])->orderBy('payment_transactions.id', 'desc')
            ->paginate(10);
        return view('pages.product.product_transfer.payment_details', $data);
    }
}
