<?php

namespace App\Http\Controllers\Admin\Returns;

use App\DataTables\Returns\SalesOrderReturnDataTable;
use App\Http\Controllers\Controller;
use App\Models\IncomeExpenseType;
use App\Models\Product;
use App\Models\PurchaseOrderAction;
use App\Models\SalesOrderReturnExpense;
use App\Models\SalesOrder;
use App\Models\SalesOrderDetail;
use App\Models\SalesOrderReturn;
use App\Models\SalesOrderReturnDetail;
use App\Models\Store;
use App\Models\TaxRate;
use App\Models\TransportTracking;
use App\Models\TransportType;
use App\Models\Unit;
use App\Models\Warehouse;
use App\Models\WarehouseInventoryDetail;
use App\Models\WarehouseStockUpdate;
use App\Models\PaymentType;
use App\Models\PaymentTransaction;
use App\Models\PaymentTransactionDocument;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Core\CommonComponent;
use App\Http\Requests\Returns\SalesReturnFormRequest;
use App\Models\SalesExpense;
use App\Models\StoreStockUpdate;
use App\Models\StoreInventoryDetail;
use App\Models\FishCuttingProductMap;


class SalesReturnController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(SalesOrderReturnDataTable $dataTable)
    {
        // return SalesOrderReturn::with('expense_details')->get();
        return $dataTable->render('pages.return.sales_return.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $data['sales_orders'] = SalesOrder::get();
        $data['warehouses'] = Warehouse::get();
        $data['stores'] = Store::get();
        $data['transport_types'] = TransportType::active()->get();
        $data['expense_types'] = IncomeExpenseType::active()->get();
        $data['payment_types'] = PaymentType::where('status', 1)
            ->where('payment_category', '!=', 2)
            ->orWhereIn('store_id', Auth::user()->user_stores())
            ->get();
        $data['units'] = Unit::active()->get();
        return view('pages.return.sales_return.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(SalesReturnFormRequest $request)
    {
        DB::beginTransaction();
        // try {SOR-2400008
        // return $request->all();


        if ($request->to_store_id && $request->to_warehouse_id) {
            return redirect()->back()->with('error', "Kindly select a warehouse or store");
        }

        $total_amount = array_sum($request->input('products.amount'));

        $sales_order_return = new SalesOrderReturn();
        $sales_order_return->sales_order_return_number = $request->sales_order_return_number;
        $sales_order_return->return_from = $request->return_from;
        $sales_order_return->to_warehouse_id = $request->to_warehouse_id;
        if ($request->from_store_id != null && $request->from_store_id != 'null') {
            $sales_order_return->from_store_id = $request->from_store_id;
        }
        if ($request->sales_order_id != null && $request->sales_order_id != 'null') {
            $sales_order = SalesOrder::select('id', 'vendor_id')->find($request->sales_order_id);
            $sales_order_return->sales_order_id = $request->sales_order_id;
            $sales_order_return->from_vendor_id = $sales_order->vendor_id;
        }
        $sales_order_return->return_date = $request->return_date;
        $sales_order_return->sub_total = $total_amount ?? 0;
        $sales_order_return->total_amount = $total_amount ?? 0;
        $sales_order_return->remarks = $request->remarks;
        $sales_order_return->status = $request->status;
        $sales_order_return->save();

        // Product Details store
        $products = $request->products;
        if (!is_null($products) && is_array($products) && count($products) > 0) {
            foreach ($products['product_id'] as $key => $product) {
                $product_data = Product::findOrfail($products['product_id'][$key]);
                $sales_order_return_product_detail = new SalesOrderReturnDetail();
                $sales_order_return_product_detail->sales_order_return_id = $sales_order_return->id;
                $sales_order_return_product_detail->product_id = $product_data->id;
                $sales_order_return_product_detail->sku_code = $product_data->sku_code;
                $sales_order_return_product_detail->name = $product_data->name;
                $sales_order_return_product_detail->per_unit_price = @$products['per_unit_price'][$key];
                $sales_order_return_product_detail->quantity = @$products['quantity'][$key];
                $sales_order_return_product_detail->unit_id = @$products['unit_id'][$key];
                $sales_order_return_product_detail->total = @$products['sub_total'][$key];
                $sales_order_return_product_detail->save();

                $quantity = $sales_order_return_product_detail->quantity;
                Log::info($sales_order_return->id);

                if ($request->status == 10) {
                    if($request->from_store_id != null && $request->from_store_id != "null"){
                        $warehouse_stock_detail_exists = WarehouseStockUpdate::where([['warehouse_id', $sales_order_return->to_warehouse_id], ['product_id', $product_data->id], ['status', 1]])->orderBy('id', 'DESC')->first();
                        $warehouse_stock_detail = new WarehouseStockUpdate();
                        $warehouse_stock_detail->warehouse_id = $sales_order_return->to_warehouse_id;
                        $warehouse_stock_detail->product_id =$product_data->id;
                        $warehouse_stock_detail->stock_update_on = Carbon::now();
                        $warehouse_stock_detail->existing_stock = $warehouse_stock_detail_exists ? $warehouse_stock_detail_exists->total_stock : 0;
                        $warehouse_stock_detail->adding_stock = @$quantity;
                        $warehouse_stock_detail->total_stock = $warehouse_stock_detail->existing_stock - $quantity;
                        $warehouse_stock_detail->status = 1;
                        $warehouse_stock_detail->box_number = 1;
                        $warehouse_stock_detail->save();

                        $warehouse_inventory = WarehouseInventoryDetail::where([['warehouse_id', $sales_order_return->to_warehouse_id],['product_id', $product_data->id],['status', 1]])->first();
                        if ($warehouse_inventory == null) {
                            $warehouse_inventory = new StoreInventoryDetail();
                            $warehouse_inventory->store_id = $sales_order_return->from_store_id;
                            $warehouse_inventory->product_id = $product_data->id;
                        }
                        $warehouse_inventory->weight = @$warehouse_inventory->weight + @$quantity;
                        $warehouse_inventory->status = 1;
                        $warehouse_inventory->save();

                        $store_stock_detail_exists = StoreStockUpdate::where([['store_id', $sales_order_return->from_store_id], ['product_id', $product_data->id], ['status', 1]])->orderBy('id', 'DESC')->first();
                        $store_stock_detail = new StoreStockUpdate();
                        $store_stock_detail->store_id = $sales_order_return->from_store_id;
                        $store_stock_detail->product_id = $product_data->id;
                        $store_stock_detail->reference_id = $sales_order_return->id;
                        $store_stock_detail->reference_table = 2; //Sales order table
                        $store_stock_detail->stock_update_on = Carbon::now();
                        $store_stock_detail->existing_stock = ($store_stock_detail_exists != null && $store_stock_detail_exists->total_stock != null) ? $store_stock_detail_exists->total_stock : 0;
                        $store_stock_detail->adding_stock = 0;
                        $store_stock_detail->total_stock = ($store_stock_detail_exists != null && $store_stock_detail_exists->total_stock != null) ? $store_stock_detail_exists->total_stock + @$quantity : @$quantity;
                        $store_stock_detail->status = 1;
                        $store_stock_detail->save();

                        $store_stock_detail = StoreStockUpdate::where([['store_id', $sales_order_return->from_store_id], ['product_id', $product_data->id], ['status', 1]])->orderBy('id', 'DESC')->first();
                        $store_inventory = StoreInventoryDetail::where([['store_id', $sales_order_return->from_store_id], ['product_id', $product_data->id], ['status', 1]])->first();
                        if ($store_inventory == null) {
                            $store_inventory = new StoreInventoryDetail();
                            $store_inventory->store_id = $sales_order_return->from_store_id;
                            $store_inventory->product_id = $product_data->id;
                        }
                        $store_inventory->weight = @$store_inventory->weight - @$quantity;
                        $store_inventory->status = 1;
                        $store_inventory->save();
                    } else {
                        if($request->to_store_id){
                            // dd($sales_order_return->from_store_id);
                            $store_stock_detail_exists = StoreStockUpdate::where([['store_id', $sales_order_return->from_store_id], ['product_id', $product_data->id], ['status', 1]])->orderBy('id', 'DESC')->first();
                            $store_stock_detail = new StoreStockUpdate();
                            $store_stock_detail->store_id = $sales_order_return->to_store_id;
                            $store_stock_detail->product_id = $product_data->id;
                            $store_stock_detail->reference_id = $sales_order_return->id;
                            $store_stock_detail->reference_table = 2; //Sales order table
                            $store_stock_detail->stock_update_on = Carbon::now();
                            $store_stock_detail->existing_stock = ($store_stock_detail_exists != null && $store_stock_detail_exists->total_stock != null) ? $store_stock_detail_exists->total_stock : 0;
                            $store_stock_detail->adding_stock = @$quantity;
                            $store_stock_detail->total_stock = ($store_stock_detail_exists != null && $store_stock_detail_exists->total_stock != null) ? $store_stock_detail_exists->total_stock + @$quantity : @$quantity;
                            $store_stock_detail->status = 1;
                            // return $store_stock_detail;
                            $store_stock_detail->save();

                            $store_stock_detail = StoreStockUpdate::where([['store_id', $sales_order_return->to_store_id], ['product_id', $product_data->id], ['status', 1]])->orderBy('id', 'DESC')->first();
                            $store_inventory = StoreInventoryDetail::where([['store_id', $sales_order_return->to_store_id], ['product_id', $product_data->id], ['status', 1]])->first();
                            if ($store_inventory == null) {
                                $store_inventory = new StoreInventoryDetail();
                                $store_inventory->store_id = $sales_order_return->to_store_id;
                                $store_inventory->product_id = $product_data->id;
                            }
                            $store_inventory->weight = @$store_inventory->weight + @$quantity;
                            $store_inventory->status = 1;
                            $store_inventory->save();
                        } else{
                            $warehouse_stock_detail_exists = WarehouseStockUpdate::where([['warehouse_id', $sales_order_return->to_warehouse_id], ['product_id', $product_data->id], ['status', 1]])->orderBy('id', 'DESC')->first();
                            $warehouse_stock_detail = new WarehouseStockUpdate();
                            $warehouse_stock_detail->warehouse_id = $sales_order_return->to_warehouse_id;
                            $warehouse_stock_detail->product_id =$product_data->id;
                            $warehouse_stock_detail->stock_update_on = Carbon::now();
                            $warehouse_stock_detail->existing_stock = $warehouse_stock_detail_exists ? $warehouse_stock_detail_exists->total_stock : 0;
                            $warehouse_stock_detail->adding_stock = @$quantity;
                            $warehouse_stock_detail->total_stock = $warehouse_stock_detail->existing_stock - $quantity;
                            $warehouse_stock_detail->status = 1;
                            $warehouse_stock_detail->box_number = 1;
                            $warehouse_stock_detail->save();

                            $warehouse_inventory = WarehouseInventoryDetail::where([['warehouse_id', $sales_order_return->to_warehouse_id],['product_id', $product_data->id],['status', 1]])->first();
                            if ($warehouse_inventory == null) {
                                $warehouse_inventory = new StoreInventoryDetail();
                                $warehouse_inventory->store_id = $sales_order_return->from_store_id;
                                $warehouse_inventory->product_id = $product_data->id;
                            }
                            $warehouse_inventory->weight = @$warehouse_inventory->weight + @$quantity;
                            $warehouse_inventory->status = 1;
                            $warehouse_inventory->save();
                        }
                    }
                }

            }
        }

        // Expense Details store
        $total_expense_amount = 0;
        $total_expense_billable_amount = 0;
        $expense = $request->expense;
        $expense['expense_type_id'];
        if (count($expense) > 0 && $expense['expense_type_id'][0] != null) {
            foreach ($expense['expense_type_id'] as $expense_key => $exp) {
                $sales_order_return_expense = new SalesOrderReturnExpense();
                $sales_order_return_expense->sales_order_return_id = $sales_order_return->id;
                $sales_order_return_expense->income_expense_id = @$expense['expense_type_id'][$expense_key];
                $sales_order_return_expense->ie_amount = @$expense['expense_amount'][$expense_key];
                $sales_order_return_expense->is_billable = @$expense['is_billable'][$expense_key] != null ? @$expense['is_billable'][$expense_key] : 0;
                if (@$expense['is_billable'][$expense_key] == 1) {
                    $total_expense_billable_amount += @$expense['expense_amount'][$expense_key];
                }
                $total_expense_amount += @$expense['expense_amount'][$expense_key];
                $sales_order_return_expense->save();
            }
            $sales_order_return = SalesOrderReturn::findOrFail($sales_order_return->id);
            $sales_order_return->total_expense_billable_amount = $total_expense_billable_amount;
            $sales_order_return->total_expense_amount = $total_expense_amount;
            $sales_order_return->save();
        }

        $transport_tracking = $request->transport_tracking;
        if (count($transport_tracking) > 0) {
            foreach ($transport_tracking['transport_type_id'] as $track_key => $exp) {
                if ($transport_tracking['transport_type_id'][$track_key]) {
                    $imagePath = null;
                    $imageUrl = null;
                    if ($request->hasFile('transport_tracking.transport_tracking_file.' . $track_key)) {
                        $imageData = CommonComponent::s3BucketFileUpload($transport_tracking['transport_tracking_file'][$track_key], 'sales_order_return_transport');
                        $imagePath = $imageData['filePath'];
                        // $imageUrl = $imageData['fileName'];
                        $imageUrl = $imageData['imageURL'];
                    }

                    $transport_trackings = new TransportTracking();
                    $transport_trackings->sales_order_return_id = $sales_order_return->id;
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
        }

        // Payment Transaction Details store
        $payment_details = $request->payment_details;
        if (count($payment_details) > 0 && $payment_details['payment_type_id'][0] != null) {
            foreach ($payment_details['payment_type_id'] as $payment_key => $payment) {
                $payment_transaction = new PaymentTransaction();
                $payment_transaction->transaction_number = CommonComponent::invoice_no('payment_transaction'); // Auto Generate
                $payment_transaction->transaction_type = 3; // retrun Order
                $payment_transaction->type = 2; // Debit
                $payment_transaction->reference_id = $sales_order_return->id;
                $payment_transaction->payment_type_id = @$payment_details['payment_type_id'][$payment_key];
                $payment_transaction->amount = @$payment_details['transaction_amount'][$payment_key];
                $payment_transaction->transaction_datetime = @$payment_details['transaction_datetime'][$payment_key];
                $payment_transaction->note = @$payment_details['remark'][$payment_key];
                $payment_transaction->status = 1;
                $payment_transaction->save();

                if (isset($payment_details['payment_transaction_documents'][$payment_key]) && count($payment_details['payment_transaction_documents']) > 0) {
                    CommonComponent::payment_transaction_documents(($payment_details['payment_transaction_documents'][$payment_key]), 1, $payment_transaction->id); // 1=> Purchase Document
                }
            }
            // Log::info($sales_order_return->id);
            $sales_retrun_order_details = SalesOrderReturn::with('sales_return_transactions')->findOrFail($sales_order_return->id);
            // Log::info($sales_retrun_order_details);
            $paid_amount = $sales_retrun_order_details->sales_return_transactions->sum('amount');

            $total_amount = $sales_retrun_order_details->sub_total;

            if ($paid_amount == 0) {
                Log::info("paid_amount = 0");

                $sales_retrun_order_details->payment_status = $request->payment_status != null ? $request->payment_status : 2; // UnPaid
                $sales_retrun_order_details->save();
            } else if ($paid_amount < $total_amount) {
                Log::info("paid_amount < total_amount");

                $sales_retrun_order_details->payment_status = $request->payment_status != null ? $request->payment_status : 3; // Pending
                $sales_retrun_order_details->save();
            } else if ($paid_amount >= $total_amount) {
                Log::info("paid_amount >= total_amount");

                $sales_retrun_order_details->payment_status = $request->payment_status != null ? $request->payment_status : 1; // Paid
                $sales_retrun_order_details->save();
            }
        }

        DB::commit();

        if ($request->submission_type == 1) {
            return redirect()->route('admin.sales-return.index')->with('success', 'Sales Order Return Stored Successfully');
        } elseif ($request->submission_type == 2) {
            return back()->with('success', 'Sales Order Return Stored Successfully');
        }
        // } catch (\Exception $e) {
        //     Log::error($e);
        //     DB::rollback();
        //     return back()->with('danger', 'Sales Order Return Stored Fail.');
        // }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $data = $common_data = $this->sales_return_overview($id);
            return view('pages.return.sales_return.view', $data);
        } catch (\Exception $e) {
            Log::error($e);
            DB::rollback();
            return back()->with('danger', 'Sales Order Return View Fail.');
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $data['sales_order'] = null;
        $data['sales_invoice_number'] = null;
        $data['sales_order_return'] = SalesOrderReturn::find($id);
        if ($data['sales_order_return']->sales_order_id != null) {
            $data['sales_order'] = SalesOrder::select('id', 'vendor_id', 'invoice_number')->find($data['sales_order_return']->sales_order_id);
        }
        $data['warehouses'] = Warehouse::get();
        $data['stores'] = Store::get();
        $data['transport_types'] = TransportType::active()->get();
        $data['expense_types'] = IncomeExpenseType::active()->get();
        $data['units'] = Unit::active()->get();
        $data['indent_request'] = SalesOrderReturn::findOrfail($id);
        $data['payment_types'] = PaymentType::where('status', 1)
            ->where('payment_category', '!=', 2)
            ->orWhereIn('store_id', Auth::user()->user_stores())
            ->get();
        return view('pages.return.sales_return.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(SalesReturnFormRequest $request, $id)
    {
        // return $request;

        DB::beginTransaction();
        try {

            if ($request->to_store_id && $request->to_warehouse_id) {
                return redirect()->back()->with('error', "Kindly select a warehouse or store");
            }

            $sales_order_return = SalesOrderReturn::findOrFail($id);
            $sales_order_return->sales_order_return_number = $request->sales_order_return_number;
            $sales_order_return->return_from = $request->return_from;
            $sales_order_return->to_warehouse_id = $request->to_warehouse_id;
            if ($request->from_store_id != null && $request->from_store_id != 'null') {
                $sales_order_return->from_store_id = $request->from_store_id;
            }
            if ($request->sales_order_id != null && $request->sales_order_id != 'null') {
                $sales_order = SalesOrder::select('id', 'vendor_id')->find($request->sales_order_id);
                $sales_order_return->sales_order_id = $request->sales_order_id;
                $sales_order_return->from_vendor_id = $sales_order->vendor_id;
            }
            $sales_order_return->sales_order_id = $request->sales_order_id;
            $sales_order_return->return_date = $request->return_date;
            $sales_order_return->sub_total = $request->sub_total_amount != null ? $request->sub_total_amount : 0;
            $sales_order_return->total_amount = $request->total_amount != null ? $request->total_amount : 0;
            $sales_order_return->remarks = $request->remarks;
            $sales_order_return->status = $request->status;
            $sales_order_return->save();

            $request_old_ids = [];
            if (isset($request->products['id']) && is_array($request->products['id'])) {
                foreach ($request->products['product_id'] as $check_key => $value) {
                    if (isset($request->products['id'][$check_key]) && $request->products['id'][$check_key] !== null) {
                        $request_old_ids[] = $request->products['id'][$check_key];
                    }
                }
            }



            $exists_sale_return_product = SalesOrderReturnDetail::where('sales_order_return_id', $id)->get();
            if (count($exists_sale_return_product) > 0) {
                foreach ($exists_sale_return_product as $exists_key => $value) {
                    if (!in_array($value->id, $request_old_ids)) {
                        SalesOrderReturnDetail::where('id', $value->id)->delete();
                    }
                }
            }

            // Product Details store
            $products = $request->products;
            if (!is_null($products) && is_array($products) && count($products) > 0) {
                foreach ($products['product_id'] as $key => $product) {
                    $product_data = Product::findOrfail($products['product_id'][$key]);
                    $old_weight = 0;
                    if (isset($products['id'][$key])) {
                        if (in_array($products['id'][$key], $exists_sale_return_product->pluck('id')->toArray())) {
                            $sales_order_return_product_detail = SalesOrderReturnDetail::findOrFail($products['id'][$key]);
                            $old_weight = $sales_order_return_product_detail->quantity;
                        }
                        // else {
                        //     $sales_order_return_product_detail = new SalesOrderReturnDetail();
                        //     $sales_order_return_product_detail->sales_order_return_id = $sales_order_return->id;
                        // }
                    } else {
                        $sales_order_return_product_detail = new SalesOrderReturnDetail();
                        $sales_order_return_product_detail->sales_order_return_id = $sales_order_return->id;
                    }
                    $sales_order_return_product_detail->sales_order_return_id = $sales_order_return->id;
                    $sales_order_return_product_detail->product_id = $product_data->id;
                    $sales_order_return_product_detail->sku_code = $product_data->sku_code;
                    $sales_order_return_product_detail->name = $product_data->name;
                    $sales_order_return_product_detail->per_unit_price = @$products['per_unit_price'][$key];
                    $sales_order_return_product_detail->quantity = @$products['quantity'][$key];
                    $sales_order_return_product_detail->unit_id = @$products['unit_id'][$key];
                    $sales_order_return_product_detail->total = @$products['sub_total'][$key];
                    $sales_order_return_product_detail->status = $request->status;
                    $sales_order_return_product_detail->save();

                    // if ($request->stock_verified) {
                    if($sales_order_return_product_detail->status == 10 && $sales_order_return->status == 10){
                        $stock_update_weight = $sales_order_return_product_detail->quantity + $old_weight;
                        $warehouse_stock_detail_exists = WarehouseStockUpdate::where([['warehouse_id', $request->to_warehouse_id], ['product_id', $product_data->id], ['status', 1]])->first();
                        // if($warehouse_stock_detail == null) {
                        $warehouse_stock_detail = new WarehouseStockUpdate();
                        $warehouse_stock_detail->warehouse_id = $request->to_warehouse_id;
                        $warehouse_stock_detail->product_id = $product_data->id;
                        // }
                        $warehouse_stock_detail->stock_update_on = Carbon::now();
                        $warehouse_stock_detail->existing_stock = (isset($warehouse_stock_detail_exists) && $warehouse_stock_detail_exists->total_stock != null) ? $warehouse_stock_detail_exists->total_stock : 0;
                        $warehouse_stock_detail->adding_stock = $stock_update_weight;
                        $warehouse_stock_detail->total_stock = (isset($warehouse_stock_detail_exists) && $warehouse_stock_detail_exists->total_stock != null) ? $warehouse_stock_detail_exists->total_stock + @$stock_update_weight : @$stock_update_weight;
                        $warehouse_stock_detail->status = 1;
                        $warehouse_stock_detail->box_number = $request->box_number != null ? $request->box_number : 1;
                        $warehouse_stock_detail->save();

                        $warehouse_inventory = WarehouseInventoryDetail::where([['warehouse_id', $request->to_warehouse_id], ['product_id', $product_data->id], ['status', 1]])->first();
                        if ($warehouse_inventory == null) {
                            $warehouse_inventory = new WarehouseInventoryDetail();
                            $warehouse_inventory->warehouse_id = $request->to_warehouse_id;
                            $warehouse_inventory->product_id = $product_data->id;
                        }
                        $warehouse_inventory->weight = @$warehouse_inventory->weight + $warehouse_stock_detail->adding_stock;
                        $warehouse_inventory->status = 1;
                        // $warehouse_inventory->save();
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

            $poe_details = SalesOrderReturnExpense::where('sales_order_return_id', $id)->get();
            if (count($poe_details) > 0) {
                foreach ($poe_details as $exists_key => $value) {
                    if (!in_array($value->id, $request_old_expense_ids)) {
                        SalesOrderReturnExpense::where('id', $value->id)->delete();
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
                        if (in_array($expense['expense_id'][$expense_key], $poe_details->pluck('id')->toArray())) {
                            $sales_order_return_expense = SalesOrderReturnExpense::findOrFail($expense['expense_id'][$expense_key]);
                        }
                        // else {
                        //     $sales_order_return_expense = new SalesOrderReturnExpense();
                        // }
                    } else {
                        $sales_order_return_expense = new SalesOrderReturnExpense();
                    }
                    if (@$expense['is_billable'][$expense_key] == 1) {
                        $total_expense_billable_amount += @$expense['expense_amount'][$expense_key];
                    }
                    $total_expense_amount += @$expense['expense_amount'][$expense_key];

                    $sales_order_return_expense->sales_order_return_id = $sales_order_return->id;
                    $sales_order_return_expense->income_expense_id = @$expense['expense_type_id'][$expense_key];
                    $sales_order_return_expense->ie_amount = @$expense['expense_amount'][$expense_key];
                    $sales_order_return_expense->total_expense_billable_amount = @$expense['expense_amount'][$expense_key];
                    $sales_order_return_expense->is_billable = @$expense['is_billable'][$expense_key] != null ? @$expense['is_billable'][$expense_key] : 0;
                    $sales_order_return_expense->save();
                }
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

            $poe_details = PaymentTransaction::where('reference_id', $sales_order_return->id)->get();
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
                            $payment_transaction->transaction_type = 3; // Sales Retrun
                            $payment_transaction->type = 2; // Debit
                            $payment_transaction->reference_id = $sales_order_return->id;
                        }
                        $payment_transaction->payment_type_id = @$payment_details['payment_type_id'][$payment_key];
                        $payment_transaction->amount = @$payment_details['transaction_amount'][$payment_key];
                        $payment_transaction->transaction_datetime = @$payment_details['transaction_datetime'][$payment_key];
                        $payment_transaction->note = @$payment_details['remark'][$payment_key];
                        $payment_transaction->status = 1;
                        $payment_transaction->save();
                    }

                    if (isset($payment_details['payment_transaction_documents'][$payment_key]) && count($payment_details['payment_transaction_documents']) > 0) {
                        CommonComponent::payment_transaction_documents(($payment_details['payment_transaction_documents'][$payment_key]), 2, $payment_transaction->id); // 1=> Sales Document
                    }
                }

                $sales_retrun_order_details = SalesOrderReturn::with('sales_return_transactions')->findOrFail($sales_order_return->id);

                $paid_amount = $sales_retrun_order_details->sales_return_transactions->sum('amount');

                $total_amount = $sales_retrun_order_details->total;

                if ($paid_amount == 0) {
                    $sales_retrun_order_details->payment_status = $request->payment_status != null ? $request->payment_status : 2; // UnPaid
                    $sales_retrun_order_details->save();
                } else if ($paid_amount < $total_amount) {
                    $sales_retrun_order_details->payment_status = $request->payment_status != null ? $request->payment_status : 3; // Pending
                    $sales_retrun_order_details->save();
                } else if ($paid_amount >= $total_amount) {
                    $sales_retrun_order_details->payment_status = $request->payment_status != null ? $request->payment_status : 1; // Paid
                    $sales_retrun_order_details->save();
                }
            }

            $sott_details = TransportTracking::where('sales_order_return_id', $id)->get();
            $request_old_sott_ids = [];
            if (isset($request->transport_tracking['transport_type_id']) && count($request->transport_tracking['transport_type_id']) > 0) {
                foreach ($request->transport_tracking['transport_type_id'] as $exp_key => $value) {
                    if ($request->transport_tracking['transport_tracking_id'][$exp_key] != null) {
                        $request_old_sott_ids[] = $request->transport_tracking['transport_tracking_id'][$exp_key];
                    }
                }
            }

            if (count($sott_details) > 0) {
                foreach ($sott_details as $exists_key => $value) {
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
                        $imageData = CommonComponent::s3BucketFileUpload($transport_tracking['transport_tracking_file'][$track_key], 'sales_order_return_transport');
                        $imagePath = $imageData['filePath'];
                        // $imageUrl = $imageData['fileName'];
                        $imageUrl = $imageData['imageURL'];
                    }

                    if (isset($transport_tracking['transport_tracking_id'][$track_key]) && $transport_tracking['transport_tracking_id'][$track_key] != null) {
                        if (in_array($transport_tracking['transport_tracking_id'][$track_key], $sott_details->pluck('id')->toArray())) {
                            $transport_trackings = TransportTracking::findOrFail($transport_tracking['transport_tracking_id'][$track_key]);
                        }
                        // else {
                        //     $transport_trackings = new TransportTracking();
                        // }
                    } else {
                        $transport_trackings = new TransportTracking();
                    }
                    $transport_trackings->sales_order_return_id = $sales_order_return->id;
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
                return redirect()->route('admin.sales-return.index')->with('success', 'Sales Order Return Updated Successfully');
            } elseif ($request->submission_type == 2) {
                return back()->with('success', 'Sales Order Return Updated Successfully');
            }
        } catch (\Exception $e) {
            Log::error($e);
            DB::rollback();
            return back()->with('danger', 'Sales Order Return Updated Fail.');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function get_sales_order(Request $request)
    {
        $response = array();
        if (isset($request['term']['term']) && !empty($request['term']['term'])) {
            $sales_order = SalesOrder::where(function ($query) use ($request) {
                $query->where('invoice_number', 'LIKE', "%{$request['term']['term']}%");
            })
                ->get();
            $sales_order->each(function ($order) use (&$response) {
                $response[] = array(
                    "id" => $order->id,
                    "text" => $order->invoice_number
                );
            });
        }

        return response()->json($response);
    }

    public function sales_return_overview($id)
    {
        $data['sales_return'] = SalesOrderReturn::findOrFail($id);
        return $data;
    }

    public function product_sales_return_data($id)
    {
        $data = $common_data = $this->sales_return_overview($id);
        $data['sales_order_return_details'] = SalesOrderReturnDetail::where('sales_order_return_id', $id)->orderBy('id', 'desc')->paginate(10);
        return view('pages.return.sales_return.sales_return_product', $data);
    }

    public function sales_transport_return_data($id)
    {
        $data = $common_data = $this->sales_return_overview($id);
        $data['sales_return_transport_trackings'] = TransportTracking::where('sales_order_return_id', $id)->orderBy('id', 'desc')->paginate(10);
        return view('pages.return.sales_return.transport_details', $data);
    }

    public function sales_return_expences_data($id)
    {
        $data = $common_data = $this->sales_return_overview($id);
        $data['sales_return_expenses'] = SalesOrderReturnExpense::select('sales_order_return_expenses.ie_amount', 'sales_order_return_expenses.is_billable', 'income_expense_types.id as type_id', 'income_expense_types.name as type_name')
            ->leftJoin('income_expense_types', 'sales_order_return_expenses.income_expense_id', '=', 'income_expense_types.id')
            ->where('sales_order_return_id', $id)
            ->orderBy('sales_order_return_expenses.id', 'desc')
            ->paginate(10);
        return view('pages.return.sales_return.expences', $data);
    }

    public function sales_return_payment_data($id)
    {
        // dd($id);
        $data = $common_data = $this->sales_return_overview($id);

        $data['sales_return_payments'] = PaymentTransaction::select('payment_transactions.*', 'payment_types.id as type_id', 'payment_types.payment_type as type_name')
            ->leftJoin('payment_types', 'payment_transactions.payment_type_id', '=', 'payment_types.id')
            ->where([
                ['reference_id', '=', $id],
                ['transaction_type', '=', 2],
            ])->orderBy('payment_transactions.id', 'desc')
            ->paginate(10);
        return view('pages.return.sales_return.payment_details', $data);
    }
}
