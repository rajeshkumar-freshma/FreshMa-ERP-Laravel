<?php

namespace App\Http\Controllers\Admin\Sales;

use App\Core\CommonComponent;
use App\DataTables\Sales\SalesOrderDataTable;
use App\Http\Controllers\Controller;
use App\Models\FishCuttingProductMap;
use App\Models\IncomeExpenseType;
use App\Models\PaymentTransaction;
use App\Models\PaymentTransactionDocument;
use App\Models\PaymentType;
use App\Models\Product;
use App\Models\SaleOrderAction;
use App\Models\SalesExpense;
use App\Models\SalesOrder;
use App\Models\SalesOrderDetail;
use App\Models\SalesOrderReturn;
use App\Models\SalesOrderReturnDetail;
use App\Models\Store;
use App\Models\StoreInventoryDetail;
use App\Models\StoreStockUpdate;
use App\Models\TaxRate;
use App\Models\TransportTracking;
use App\Models\TransportType;
use App\Models\Unit;
use App\Models\Vendor;
use App\Models\VendorIndentRequest;
use App\Models\VendorIndentRequestAction;
use App\Models\VendorIndentRequestDetail;
use App\Models\Warehouse;
use App\Models\WarehouseInventoryDetail;
use App\Models\WarehouseStockUpdate;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Validator;

class SalesOrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(SalesOrderDataTable $dataTable, Request $request)
    {
        $data['stores'] = Store::get();
        //these values comes from daily sales report view page via

        $data['store_id'] = $request->store_id; // Replace with the actual value
        $data['delivered_date'] = $request->delivered_date; // Replace with the actual value

        return $dataTable->render('pages.sales.sales_order.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data['vendors'] = Vendor::select('id', 'first_name', 'last_name', 'user_code')
            ->active()
            ->get();
        $data['stores'] = Store::select('id', 'store_name', 'store_code')
            ->active()
            ->get();
        $data['warehouses'] = Warehouse::select('id', 'name', 'code')
            ->active()
            ->get();
        $data['units'] = Unit::active()->get();
        $data['tax_rates'] = TaxRate::active()->get();
        $data['vendor_indent_requests'] = VendorIndentRequest::select('id', 'vendor_id', 'request_code')->get();
        $data['transport_types'] = TransportType::active()->get();
        $data['expense_types'] = IncomeExpenseType::active()
            ->where('type', 2)
            ->get();
        $data['payment_types'] = PaymentType::where('status', 1)
            ->where('payment_category', '!=', 2)
            ->orWhereIn('store_id', Auth::user()->user_stores())
            ->get();
        return view('pages.sales.sales_order.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // return $request;
        DB::beginTransaction();
        // try {
        $validator = Validator::make($request->all(), [
            'invoice_number' => 'required|max:191|min:3',
            'vendor_indent_request_id' => 'nullable|integer',
            'delivered_date' => 'required|date',
            'warehouse_ir_id' => 'nullable|integer',
            'vendor_id' => 'nullable|integer',
            'status' => 'required|integer',
            'total_request_quantity' => 'required',
            'remarks' => 'nullable|max:191|min:3',
            'file.*' => 'nullable|mimes:' . config('app.attachmentfiletype') . '|max:' . config('app.attachmentfilesize'),
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        if ($request->warehouse_id &&  $request->store_id) {
            return back()->with("error" , "Please select either warehouse or store, not both");
        }

        $imagePath = null;
        $imageUrl = null;

        if ($request->hasFile('file')) {
            $imageData = CommonComponent::s3BucketFileUpload($request->file, 'sales_order');
            $imagePath = $imageData['filePath'];
            // $imageUrl = $imageData['fileName'];
            $imageUrl = $imageData['imageURL'];
        }

        $indent_request = new SalesOrder();
        $indent_request->sales_from = 2;
        $indent_request->sales_type = 2;
        $indent_request->warehouse_id = $request->warehouse_id;
        $indent_request->store_id = $request->store_id;
        $indent_request->vendor_id = $request->vendor_id;
        $indent_request->quatation_id = $request->quatation_id;
        $indent_request->invoice_number = $request->invoice_number;
        $indent_request->delivered_date = $request->delivered_date;
        $indent_request->status = $request->status;
        $indent_request->total_request_quantity = $request->total_request_quantity;
        $indent_request->total_given_quantity = $request->total_given_quantity;
        $indent_request->sub_total = $request->sub_total_amount;
        $indent_request->total_expense_amount = $request->total_expense_amount;
        $indent_request->total_commission_amount = $request->total_commission_amount;
        $indent_request->total_amount = $request->total_amount;
        $indent_request->remarks = $request->remarks;
        $indent_request->is_inc_exp_billable_for_all = $request->is_inc_exp_billable_for_all;
        if ($imageUrl != null) {
            $indent_request->file = $imageUrl;
            $indent_request->file_path = $imagePath;
        }
        $indent_request->discount_type = $request->discount_type;
        $indent_request->discount_percentage = $request->discount_percentage;
        $indent_request->discount_amount = $request->discount_amount;
        $indent_request->adjustment_amount = $request->adjustment_amount;
        $indent_request->save();

        $products = $request->products;
        if (is_array($products) && count($products) > 0) {
            foreach ($products['product_id'] as $key => $product) {
                $product_data = Product::findOrfail($products['product_id'][$key]);
                $indent_request_detail = new SalesOrderDetail();
                $indent_request_detail->sales_order_id = $indent_request->id;
                $indent_request_detail->product_id = $product_data->id;
                $indent_request_detail->sku_code = $product_data->sku_code;
                $indent_request_detail->name = $product_data->name;
                $indent_request_detail->is_inc_exp_billable = $request->is_inc_exp_billable_for_all;
                $indent_request_detail->unit_id = $products['unit_id'][$key];
                $indent_request_detail->request_quantity = $products['quantity'][$key];
                $indent_request_detail->given_quantity = $products['given_quantity'][$key];
                $indent_request_detail->amount = $products['amount'][$key];
                $indent_request_detail->per_unit_price = @$products['per_unit_price'][$key];
                $indent_request_detail->tax_id = $products['tax_id'][$key];
                $indent_request_detail->tax_value = $products['tax_value'][$key];
                $indent_request_detail->discount_type = $products['discount_type'][$key];
                $indent_request_detail->discount_amount = $products['discount_amount'][$key] != null ? $products['discount_amount'][$key] : 0;
                $indent_request_detail->discount_percentage = $products['discount_percentage'][$key] != null ? $products['discount_percentage'][$key] : 0;
                $indent_request_detail->sub_total = $products['sub_total'][$key];
                $indent_request_detail->commission_percentage = $products['commission_percentage'][$key];
                $indent_request_detail->commission_amount = $products['commission_amount'][$key];
                $indent_request_detail->expense_amount = $products['inc_exp_amount'][$key];
                $indent_request_detail->total = $products['total'][$key];
                $indent_request_detail->save();

                $quantity = $indent_request_detail->given_quantity;
                if ($request->status == 10 && $request->warehouse_id != null) {
                    $warehouse_stock_detail_exists = WarehouseStockUpdate::where([['warehouse_id', $indent_request->warehouse_id], ['product_id', $product_data->id], ['status', 1]])->orderBy('id', 'DESC')->first();
                    $warehouse_stock_detail = new WarehouseStockUpdate();
                    $warehouse_stock_detail->warehouse_id = $indent_request->warehouse_id;
                    $warehouse_stock_detail->product_id = $product_data->id;
                    $warehouse_stock_detail->stock_update_on = Carbon::now();
                    $warehouse_stock_detail->existing_stock = $warehouse_stock_detail_exists ? $warehouse_stock_detail_exists->total_stock : 0;
                    $warehouse_stock_detail->adding_stock = 0;
                    $warehouse_stock_detail->total_stock = $warehouse_stock_detail->existing_stock - $quantity;
                    $warehouse_stock_detail->status = 1;
                    $warehouse_stock_detail->box_number = 1;
                    $warehouse_stock_detail->save();

                    $warehouse_inventory = WarehouseInventoryDetail::where([['warehouse_id', $indent_request->warehouse_id], ['product_id', $product_data->id], ['status', 1]])->first();
                    if ($warehouse_inventory == null) {
                        $warehouse_inventory = new WarehouseInventoryDetail();
                        $warehouse_inventory->warehouse_id = $indent_request->warehouse_id;
                        $warehouse_inventory->product_id = $product_data->id;
                    }
                    $warehouse_inventory->weight = @$warehouse_inventory->weight - @$quantity;
                    $warehouse_inventory->status = 1;
                    $warehouse_inventory->save();
                }
                if ($request->status == 10 && $request->store_id != null) {
                    $store_stock_detail_exists = StoreStockUpdate::where([['store_id', $indent_request->store_id], ['product_id', $product_data->id], ['status', 1]])->orderBy('id', 'DESC')->first();
                    $store_stock_detail = new StoreStockUpdate();
                    $store_stock_detail->store_id = $indent_request->store_id;
                    $store_stock_detail->product_id = $product_data->id;
                    $store_stock_detail->reference_id = $indent_request->id;
                    $store_stock_detail->reference_table = 2; //Sales order table
                    $store_stock_detail->stock_update_on = Carbon::now();
                    $store_stock_detail->existing_stock = ($store_stock_detail_exists != null && $store_stock_detail_exists->total_stock != null) ? $store_stock_detail_exists->total_stock : 0;
                    $store_stock_detail->adding_stock = @$quantity;
                    $store_stock_detail->total_stock = ($store_stock_detail_exists != null && $store_stock_detail_exists->total_stock != null) ? $store_stock_detail_exists->total_stock - @$quantity : @$quantity;
                    $store_stock_detail->status = 1;
                    $store_stock_detail->save();

                    // $store_stock_detail = StoreStockUpdate::where([['store_id', $indent_request->store_id], ['product_id', $product_data->id], ['status', 1]])->orderBy('id', 'DESC')->first();
                    $store_inventory = StoreInventoryDetail::where([['store_id', $indent_request->store_id], ['product_id', $product_data->id], ['status', 1]])->first();
                    if ($store_inventory == null) {
                        $store_inventory = new StoreInventoryDetail();
                        $store_inventory->store_id = $indent_request->store_id;
                        $store_inventory->product_id = $product_data->id;
                    }
                    $store_inventory->weight = @$store_inventory->weight - @$quantity;
                    $store_inventory->status = 1;
                    $store_inventory->save();
                }
            }
        }

        /* $payment_transaction = new PaymentTransaction();
        $payment_transaction->transaction_number = CommonComponent::invoice_no('payment_transaction'); // Auto Generate
        $payment_transaction->transaction_type = 2; // Sales Order
        $payment_transaction->type = 1; // Credit
        $payment_transaction->reference_id = $indent_request->id;
        $payment_transaction->amount = $request->paid_amount ? $request->paid_amount : $indent_request->total_amount;
        $payment_transaction->transaction_datetime = Carbon::now();
        $payment_transaction->status = 1;
        $payment_transaction->save();

        if (isset($request->payment_transaction_documents) && count($request->payment_transaction_documents) > 0 && $request->file('payment_transaction_documents')) {
        CommonComponent::payment_transaction_documents($request->file('payment_transaction_documents'), 2, $payment_transaction->id); // 2 => Sales Order
        } */

        $request_action = new SaleOrderAction();
        $request_action->sales_order_id = $indent_request->id;
        $request_action->status = $request->status;
        $request_action->action_date = Carbon::now();
        $request_action->remarks = $request->remarks;
        $request_action->save();

        // Expense Details store
        $total_expense_amount = 0;
        $total_expense_billable_amount = 0;
        $expense = $request->expense;
        if (count($expense) > 0 && $expense['expense_type_id'][0] != null) {
            foreach ($expense['expense_type_id'] as $expense_key => $exp) {
                $purchase_expense = new SalesExpense();
                $purchase_expense->sales_order_id = $indent_request->id;
                $purchase_expense->income_expense_id = @$expense['expense_type_id'][$expense_key];
                $purchase_expense->ie_amount = @$expense['expense_amount'][$expense_key];
                $purchase_expense->is_billable = @$expense['is_billable'][$expense_key];
                if (@$expense['is_billable'][$expense_key] == 1) {
                    $total_expense_billable_amount += @$expense['expense_amount'][$expense_key];
                }
                $total_expense_amount += @$expense['expense_amount'][$expense_key];
                $purchase_expense->save();
            }

            $indent_request->total_expense_billable_amount = $total_expense_billable_amount;
            $indent_request->total_expense_amount = $total_expense_amount;
            $indent_request->save();
        }

        $transport_tracking = $request->transport_tracking;
        if (count($transport_tracking) > 0) {
            foreach ($transport_tracking['transport_type_id'] as $track_key => $exp) {
                $imagePath = null;
                $imageUrl = null;
                if ($request->hasFile('transport_tracking.transport_tracking_file.' . $track_key)) {
                    $imageData = CommonComponent::s3BucketFileUpload($transport_tracking['transport_tracking_file'][$track_key], 'purchase_order_transport');
                    $imagePath = $imageData['filePath'];
                    // $imageUrl = $imageData['fileName'];
                    $imageUrl = $imageData['imageURL'];
                }

                $transport_trackings = new TransportTracking();
                $transport_trackings->sales_order_id = $indent_request->id;
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
                $payment_transaction->transaction_type = 2; // Sales Order
                $payment_transaction->type = 1; // Credit
                $payment_transaction->reference_id = $indent_request->id;
                $payment_transaction->payment_type_id = @$payment_details['payment_type_id'][$payment_key];
                $payment_transaction->amount = @$payment_details['transaction_amount'][$payment_key];
                $payment_transaction->transaction_datetime = @$payment_details['transaction_datetime'][$payment_key];
                $payment_transaction->note = @$payment_details['remark'][$payment_key];
                $payment_transaction->status = 1;
                $payment_transaction->save();

                if (isset($payment_details['payment_transaction_documents'][$payment_key]) && count($payment_details['payment_transaction_documents']) > 0) {
                    CommonComponent::payment_transaction_documents(($payment_details['payment_transaction_documents'][$payment_key]), 2, $payment_transaction->id); // 1=> Sales Document
                }
            }

            $sales_order_details = SalesOrder::with('sales_order_transactions')->findOrFail($indent_request->id);

            $paid_amount = $sales_order_details->sales_order_transactions->sum('amount');
            $total_amount = $sales_order_details->total_amount;
            Log::info("paid_amountpaid_amountpaid_amount");
            Log::info($paid_amount);
            Log::info("total_amount");
            Log::info($total_amount);

            if ($paid_amount == 0) {
                $sales_order_details->payment_status = $request->payment_status != null ? $request->payment_status : 2; // UnPaid
                $sales_order_details->save();
            } else if ($paid_amount < $total_amount) {
                $sales_order_details->payment_status = $request->payment_status != null ? $request->payment_status : 3; // Pending
                $sales_order_details->save();
            } else if ($paid_amount >= $total_amount) {
                $sales_order_details->payment_status = $request->payment_status != null ? $request->payment_status : 1; // Paid
                $sales_order_details->save();
            }
        }

        DB::commit();

        if ($request->submission_type == 1) {
            return redirect()
                ->route('admin.sales-order.index')
                ->with('success', 'Sale Stored Successfully');
        } elseif ($request->submission_type == 2) {
            return back()->with('success', 'Sale Stored Successfully');
        }
        // } catch (\Exception $e) {
        //     Log::error($e);
        //     DB::rollback();
        //     return back()
        //         ->withInput()
        //         ->with('error', 'Sales Stored Fail');
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
        $data = $common_data = $this->sales_overview($id);
        return view('pages.sales.sales_order.show', $data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $previousUrl = URL::previous();
        // Retrieve the previous route name
        if (str_contains($previousUrl, 'sales-credit')) {
            $data['route'] = 1;
        } elseif (str_contains($previousUrl, 'sales-order')) {
            $data['route'] = 2;
        } else {
            $data['route'] = 0;
        }
        $data['vendors'] = Vendor::select('id', 'first_name', 'last_name', 'user_code')
            ->active()
            ->get();
        $data['stores'] = Store::select('id', 'store_name', 'store_code')
            ->active()
            ->get();
        $data['warehouses'] = Warehouse::select('id', 'name', 'code')
            ->active()
            ->get();
        $data['indent_request'] = SalesOrder::findOrfail($id);
        $data['units'] = Unit::active()->get();
        $data['tax_rates'] = TaxRate::active()->get();
        $data['vendor_indent_requests'] = VendorIndentRequest::select('id', 'vendor_id', 'request_code')->get();
        $data['sales_order'] = SalesOrder::findOrfail($id);
        $data['transport_types'] = TransportType::active()->get();
        $data['expense_types'] = IncomeExpenseType::active()
            ->where('type', 2)
            ->get();
        $data['payment_types'] = PaymentType::where('status', 1)
            ->where('payment_category', '!=', 2)
            ->orWhereIn('store_id', Auth::user()->user_stores())
            ->get();
        return view('pages.sales.sales_order.edit', $data);
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
        // return $request->all();
        DB::beginTransaction();
        // try {
        $validator = Validator::make($request->all(), [
            'invoice_number' => 'required|max:191|min:3',
            'vendor_indent_request_id' => 'nullable|integer',
            'delivered_date' => 'required|date',
            'warehouse_ir_id' => 'nullable|integer',
            'warehouse_id' => 'required|integer',
            'store_id' => 'required|integer',
            'vendor_id' => 'nullable|integer',
            'status' => 'required|integer',
            'total_request_quantity' => 'required',
            'remarks' => 'nullable|max:191|min:3',
            'file.*' => 'nullable|mimes:' . config('app.attachmentfiletype') . '|max:' . config('app.attachmentfilesize'),
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        $imagePath = null;
        $imageUrl = null;

        if ($request->hasFile('file')) {
            $imageData = CommonComponent::s3BucketFileUpload($request->file, 'vendor_sales');
            $imagePath = $imageData['filePath'];
            // $imageUrl = $imageData['fileName'];
            $imageUrl = $imageData['imageURL'];
        }

        $store_data = Store::find($request->store_id);

        $indent_request = SalesOrder::findOrfail($id);
        $indent_request->warehouse_id = $request->warehouse_id;
        $indent_request->store_id = $request->store_id;
        $indent_request->vendor_id = $request->vendor_id;
        $indent_request->quatation_id = $request->quatation_id;
        $indent_request->invoice_number = $request->invoice_number;
        $indent_request->delivered_date = $request->delivered_date;
        $indent_request->status = $request->status;
        $indent_request->total_request_quantity = $request->total_request_quantity;
        $indent_request->total_given_quantity = $request->total_given_quantity;
        $indent_request->sub_total = $request->sub_total_amount;
        $indent_request->total_expense_amount = $request->total_expense_amount;
        $indent_request->total_commission_amount = $request->total_commission_amount;
        $indent_request->total_amount = $request->total_amount;
        $indent_request->remarks = $request->remarks;
        $indent_request->is_inc_exp_billable_for_all = $request->is_inc_exp_billable_for_all;
        if ($imageUrl != null) {
            $indent_request->file = $imageUrl;
            $indent_request->file_path = $imagePath;
        }
        $indent_request->discount_type = $request->discount_type;
        $indent_request->discount_percentage = $request->discount_percentage;
        $indent_request->discount_amount = $request->discount_amount;
        $indent_request->adjustment_amount = $request->adjustment_amount;
        $indent_request->save();
        $request_old_ids = [];
        if (isset($request->products['product_id']) && is_array($request->products['product_id'])) {
            foreach ($request->products['product_id'] as $store_key => $value) {
                if (isset($request->products['id'][$store_key]) && $request->products['id'][$store_key] != null) {
                    $request_old_ids[] = $request->products['id'][$store_key];
                }
            }
        }

        $exists_indent_product = SalesOrderDetail::where('sales_order_id', $id)->get();
        if (count($exists_indent_product) > 0) {
            foreach ($exists_indent_product as $exists_key => $value) {
                if (!in_array($value->id, $request_old_ids)) {
                    SalesOrderDetail::where('id', $value->id)->delete();
                }
            }
        }

        $products = $request->products;
        if (!is_null($products) && is_array($products) && count($products) > 0) {
            foreach ($products['product_id'] as $key => $product) {
                $product_data = Product::findOrfail($products['product_id'][$key]);
                $old_weight = 0;
                if (isset($products['id'][$key])) {
                    if (in_array($products['id'][$key], $exists_indent_product->pluck('id')->toArray())) {
                        $indent_request_detail = SalesOrderDetail::findOrFail($products['id'][$key]);
                        $old_weight = $indent_request_detail->given_quantity;
                    }
                    // else {
                    //     $indent_request_detail = new SalesOrderDetail();
                    //     $indent_request_detail->sales_order_id = $indent_request->id;
                    // }
                } else {
                    $indent_request_detail = new SalesOrderDetail();
                    $indent_request_detail->sales_order_id = $indent_request->id;
                }

                $indent_request_detail->product_id = $product_data->id;
                $indent_request_detail->sku_code = $product_data->sku_code;
                $indent_request_detail->name = $product_data->name;
                $indent_request_detail->is_inc_exp_billable = $request->is_inc_exp_billable_for_all;
                $indent_request_detail->unit_id = $products['unit_id'][$key];
                $indent_request_detail->request_quantity = $products['quantity'][$key];
                $indent_request_detail->given_quantity = $products['given_quantity'][$key];
                $indent_request_detail->amount = $products['amount'][$key];
                $indent_request_detail->per_unit_price = @$products['per_unit_price'][$key];
                $indent_request_detail->tax_id = $products['tax_id'][$key];
                $indent_request_detail->tax_value = $products['tax_value'][$key];
                $indent_request_detail->discount_type = $products['discount_type'][$key];
                $indent_request_detail->discount_amount = $products['discount_amount'][$key] != null ? $products['discount_amount'][$key] : 0;
                $indent_request_detail->discount_percentage = $products['discount_percentage'][$key] != null ? $products['discount_percentage'][$key] : 0;
                $indent_request_detail->sub_total = $products['sub_total'][$key];
                $indent_request_detail->commission_percentage = $products['commission_percentage'][$key];
                $indent_request_detail->commission_amount = $products['commission_amount'][$key];
                $indent_request_detail->expense_amount = $products['inc_exp_amount'][$key];
                $indent_request_detail->total = $products['total'][$key];
                $indent_request_detail->is_inc_exp_billable = isset($request->is_inc_exp_billable_for_all) ? $request->is_inc_exp_billable_for_all : 0;
                $indent_request_detail->save();

                // $stock_update_weight = $indent_request_detail->given_quantity - $old_weight;

                // $not_equal_qnty = $indent_request_detail->given_quantity !== $old_weight;
                // if ($not_equal_qnty) {

                //     $sales_order_return = new SalesOrderReturn();
                //     $sales_order_return->sales_order_return_number = $request->sales_order_return_number;
                //     $sales_order_return->return_from = $request->return_from;
                //     $sales_order_return->to_warehouse_id = $request->to_warehouse_id;
                //     if ($indent_request->id != null && $indent_request->id != 'null') {
                //         $sales_details = SalesOrder::select('warehouse_id', 'store_id', 'vendor_id')->find($indent_request->id);
                //         $sales_order_return->from_vendor_id = $sales_details->vendor_id;
                //         $sales_order_return->from_store_id = $sales_details->store_id;
                //     }
                //     if ($request->store_id != null &&$store_id != 'null') {
                //         $sales_order_return->from_store_id =$store_id;
                //     }
                //     if ($request->vendor_id != null &&$vendor_id != 'null') {
                //         $sales_order_return->from_vendor_id =$vendor_id;
                //     }
                //     $sales_order_return->sales_order_id = $indent_request->id;
                //     $sales_order_return->return_type = 2;
                //     $sales_order_return->return_date = $request->return_date;
                //     $sales_order_return->sub_total = $request->sub_total;
                //     $sales_order_return->round_off_amount = $request->round_off_amount;
                //     $sales_order_return->adjustment_amount = $request->adjustment_amount;
                //     $sales_order_return->total_amount = $request->total_amount;
                //     $sales_order_return->payment_status = $request->payment_status != null ? $request->payment_status : 2; // Unpaid
                //     $sales_order_return->status = 1;
                //     $sales_order_return->is_same_day_return = $request->is_same_day_return;
                //     $sales_order_return->save();
                //     return $sales_order_return;
                //     // Product Details store
                //     $products = $request->products;
                //     $sales_order_return_product_detail = new SalesOrderReturnDetail();
                //     $sales_order_return_product_detail->sales_order_return_id = $sales_order_return->id;
                //     $sales_order_return_product_detail->product_id = $product_data->id;
                //     $sales_order_return_product_detail->sku_code = $product_data->sku_code;
                //     $sales_order_return_product_detail->name = $product_data->name;
                //     $sales_order_return_product_detail->per_unit_price = @$product->amount;
                //     $sales_order_return_product_detail->quantity = $stock_update_weight;
                //     $sales_order_return_product_detail->unit_id = @$product->unit_id;
                //     $sales_order_return_product_detail->total = (isset($product->total) && $product->total != null) ? $product->total : @$product->sub_total;
                //     $sales_order_return_product_detail->status = $request->status;
                //     // $sales_order_return_product_detail->save();

                // $quantity = $indent_request_detail->given_quantity;
                // if ($request->status == 10) {
                //     $warehouse_stock_detail_exists = WarehouseStockUpdate::where([['warehouse_id', $indent_request->warehouse_id], ['product_id', $product_data->id], ['status', 1]])->orderBy('id', 'DESC')->first();
                //     $warehouse_stock_detail = new WarehouseStockUpdate();
                //     $warehouse_stock_detail->warehouse_id = $indent_request->warehouse_id;
                //     $warehouse_stock_detail->product_id = $product_data->id;
                //     $warehouse_stock_detail->stock_update_on = Carbon::now();
                //     $warehouse_stock_detail->existing_stock = $warehouse_stock_detail_exists ? $warehouse_stock_detail_exists->total_stock : 0;
                //     $warehouse_stock_detail->adding_stock = 0;
                //     $warehouse_stock_detail->total_stock = $warehouse_stock_detail->existing_stock - $quantity;
                //     $warehouse_stock_detail->status = 1;
                //     $warehouse_stock_detail->box_number = 1;
                //     $warehouse_stock_detail->save();

                //     $warehouse_inventory = WarehouseInventoryDetail::where([['warehouse_id', $indent_request->warehouse_id], ['product_id', $product_data->id], ['status', 1]])->first();
                //     if ($warehouse_inventory == null) {
                //         $warehouse_inventory = new StoreInventoryDetail();
                //         $warehouse_inventory->store_id = $indent_request->store_id;
                //         $warehouse_inventory->product_id = $product_data->id;
                //     }
                //     $warehouse_inventory->weight = @$warehouse_inventory->weight-@$quantity;
                //     $warehouse_inventory->status = 1;
                //     $warehouse_inventory->save();

                //     if ($request->store_id != null && $request->store_id != "null") {
                //         $store_stock_detail_exists = StoreStockUpdate::where([['store_id', $indent_request->store_id], ['product_id', $product_data->id], ['status', 1]])->orderBy('id', 'DESC')->first();
                //         $store_stock_detail = new StoreStockUpdate();
                //         $store_stock_detail->store_id = $indent_request->store_id;
                //         $store_stock_detail->product_id = $product_data->id;
                //         $store_stock_detail->reference_id = $indent_request->id;
                //         $store_stock_detail->reference_table = 2; //Sales order table
                //         $store_stock_detail->stock_update_on = Carbon::now();
                //         $store_stock_detail->existing_stock = ($store_stock_detail_exists != null && $store_stock_detail_exists->total_stock != null) ? $store_stock_detail_exists->total_stock : 0;
                //         $store_stock_detail->adding_stock = @$quantity;
                //         $store_stock_detail->total_stock = ($store_stock_detail_exists != null && $store_stock_detail_exists->total_stock != null) ? $store_stock_detail_exists->total_stock+@$quantity : @$quantity;
                //         $store_stock_detail->status = 1;
                //         $store_stock_detail->save();

                //         $store_stock_detail = StoreStockUpdate::where([['store_id', $indent_request->store_id], ['product_id', $product_data->id], ['status', 1]])->orderBy('id', 'DESC')->first();
                //         $store_inventory = StoreInventoryDetail::where([['store_id', $indent_request->store_id], ['product_id', $product_data->id], ['status', 1]])->first();
                //         if ($store_inventory == null) {
                //             $store_inventory = new StoreInventoryDetail();
                //             $store_inventory->store_id = $indent_request->store_id;
                //             $store_inventory->product_id = $product_data->id;
                //         }
                //         $store_inventory->weight = @$store_inventory->weight+@$quantity;
                //         $store_inventory->status = 1;
                //         $store_inventory->save();
                //     }
                // }
                $stock_update_weight = $old_weight - $indent_request_detail->given_quantity;
                $unit_price = $indent_request_detail->amount / $indent_request_detail->given_quantity;
                $per_product_amt = $unit_price * $stock_update_weight;

                $not_equal_qnty = $indent_request_detail->given_quantity < $old_weight;

                if ($not_equal_qnty) {
                    $sales_order_return = new SalesOrderReturn();
                    $sales_order_return->sales_order_return_number = CommonComponent::invoice_no('sales_order_return');
                    $sales_order_return->return_from = $request->vendor_id; // 1 store return 2 is vendor return
                    $sales_order_return->to_warehouse_id = $request->warehouse_id;

                    if (!is_null($indent_request->id) && $indent_request->id !== 'null') {
                        $sales_details = SalesOrder::select('warehouse_id', 'store_id', 'vendor_id')->find($indent_request->id);
                        $sales_order_return->from_vendor_id = $sales_details->vendor_id;
                        $sales_order_return->from_store_id = $sales_details->store_id;
                    }

                    $sales_order_return->from_store_id = $request->store_id ?? $sales_order_return->from_store_id;
                    $sales_order_return->from_vendor_id = $request->vendor_id ?? $sales_order_return->from_vendor_id;
                    $sales_order_return->sales_order_id = $indent_request->id;
                    $sales_order_return->return_type = 2;
                    $sales_order_return->return_date = Carbon::now();
                    $sales_order_return->sub_total = $per_product_amt;
                    $sales_order_return->round_off_amount = $request->round_off_amount;
                    $sales_order_return->adjustment_amount = $request->adjustment_amount;
                    $sales_order_return->total_amount = $request->total_amount;
                    $sales_order_return->payment_status = $request->payment_status ?? 2; // Unpaid
                    $sales_order_return->status = $request->status;
                    $sales_order_return->is_same_day_return = $request->is_same_day_return;
                    $sales_order_return->save();
                    // return $sales_order_return;
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
                    $sales_order_return_product_detail->total = $product->total ?? $product->sub_total ?? 0;
                    $sales_order_return_product_detail->status = $request->status;
                    $sales_order_return_product_detail->save();

                    // if (($sales_order_return_product_detail->status == 10 && $sales_order_return->status == 10) && (!is_null($request->store_id) && $request->store_id !== 'null')) {
                    //     $stock_update_weight = $sales_order_return_product_detail->quantity + $old_weight;
                    //     $warehouse_stock_detail_exists = WarehouseStockUpdate::where([['warehouse_id', $request->to_warehouse_id], ['product_id', $product_data->id], ['status', 1]])->first();
                    //     $warehouse_stock_detail = new WarehouseStockUpdate();
                    //     $warehouse_stock_detail->warehouse_id = $request->to_warehouse_id;
                    //     $warehouse_stock_detail->product_id = $product_data->id;
                    //     $warehouse_stock_detail->stock_update_on = Carbon::now();
                    //     $warehouse_stock_detail->existing_stock = $warehouse_stock_detail_exists->total_stock ?? 0;
                    //     $warehouse_stock_detail->adding_stock = $stock_update_weight;
                    //     $warehouse_stock_detail->total_stock = $warehouse_stock_detail_exists->total_stock ?? 0 + $stock_update_weight;
                    //     $warehouse_stock_detail->status = 1;
                    //     $warehouse_stock_detail->box_number = $request->box_number ?? 1;
                    //     $warehouse_stock_detail->save();

                    //     $warehouse_inventory = WarehouseInventoryDetail::where([['warehouse_id', $request->to_warehouse_id], ['product_id', $product_data->id], ['status', 1]])->first() ?? new WarehouseInventoryDetail();
                    //     $warehouse_inventory->warehouse_id = $request->to_warehouse_id;
                    //     $warehouse_inventory->product_id = $product_data->id;
                    //     $warehouse_inventory->weight = $warehouse_inventory->weight ?? 0 + $warehouse_stock_detail->adding_stock;
                    //     $warehouse_inventory->status = 1;
                    //     $warehouse_inventory->save();
                    // }

                    //vendor(customer) return order,if the status is "received & verified" update the return stock to StoreStockUpdate and StoreInventoryDetail
                    if (($stock_update_weight != 0) && ($sales_order_return_product_detail->status == 10 && $sales_order_return->status == 10) && (!is_null($request->vendor_id) && $request->vendor_id !== 'null')) {

                        $fishcutting = FishCuttingProductMap::where('main_product_id', $product_data->id)->orderbyDesc('id')->first();
                        if ($fishcutting != null) {
                            $grouped_products = json_decode($fishcutting->grouped_product);
                            $quantity = 0;
                            foreach ($grouped_products as $key => $grouped_product) {
                                $quantity = ($stock_update_weight * $grouped_product->percentage) / 100;

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
                            $store_inventory->weight = @$store_inventory->weight+@$stock_update_weight;
                            $store_inventory->status = 1;
                            $store_inventory->save();
                            // return $store_inventory;
                        }
                    }

                }

            }
        }

        /* $payment_transaction = new PaymentTransaction();
        $payment_transaction->transaction_number = CommonComponent::invoice_no('payment_transaction'); // Auto Generate
        $payment_transaction->transaction_type = 2;  // Sales Order
        $payment_transaction->type = 1; // Credit
        $payment_transaction->reference_id = $indent_request->id;
        $payment_transaction->amount = $request->paid_amount ? $request->paid_amount : $indent_request->total_amount;
        $payment_transaction->transaction_datetime = Carbon::now();
        $payment_transaction->status = 1; // Active
        $payment_transaction->save();

        if (isset($request->payment_transaction_documents) && count($request->payment_transaction_documents) > 0 && $request->file('payment_transaction_documents')) {
        CommonComponent::payment_transaction_documents($request->file('payment_transaction_documents'), 2, $payment_transaction->id); // 2 => Sales Order
        } */

        $request_action = new SaleOrderAction();
        $request_action->sales_order_id = $indent_request->id;
        $request_action->status = $request->status;
        $request_action->action_date = Carbon::now();
        $request_action->remarks = $request->remarks;
        $request_action->save();

        // Expense Details store
        $request_old_expense_ids = [];
        foreach ($request->expense['expense_type_id'] as $exp_key => $value) {
            if ($request->expense['expense_id'][$exp_key] != null) {
                $request_old_expense_ids[] = $request->expense['expense_id'][$exp_key];
            }
        }

        $s_e_details = SalesExpense::where('sales_order_id', $id)->get();
        if (count($s_e_details) > 0) {
            foreach ($s_e_details as $exists_key => $value) {
                if (!in_array($value->id, $request_old_expense_ids)) {
                    SalesExpense::where('id', $value->id)->delete();
                }
            }
        }

        $expense = $request->expense;
        if (count($expense) > 0 && $expense['expense_type_id'][0] != null) {
            foreach ($expense['expense_type_id'] as $expense_key => $exp) {
                if (isset($expense['expense_id'][$expense_key]) && $expense['expense_id'][$expense_key] != null) {
                    if (in_array($expense['expense_id'][$expense_key], $s_e_details->pluck('id')->toArray())) {
                        $sales_expense = SalesExpense::findOrFail($expense['expense_id'][$expense_key]);
                    }
                    // else {
                    //     $sales_expense = new SalesExpense();
                    //     $sales_expense->sales_order_id = $indent_request->id;
                    // }
                } else {
                    $sales_expense = new SalesExpense();
                    $sales_expense->sales_order_id = $indent_request->id;
                }
                $sales_expense->income_expense_id = @$expense['expense_type_id'][$expense_key];
                $sales_expense->ie_amount = @$expense['expense_amount'][$expense_key];
                $sales_expense->is_billable = @$expense['is_billable'][$expense_key] != null ? @$expense['is_billable'][$expense_key] : 0;
                $sales_expense->save();
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

        if (count($payment_details) > 0 && !empty($payment_details['payment_type_id'])) {
            foreach ($payment_details['payment_type_id'] as $payment_key => $payment_type_id) {
                // Check if payment type ID is present
                if ($payment_type_id !== null) {
                    // Initialize or find the PaymentTransaction object
                    $payment_transaction = null;

                    if (isset($payment_details['payment_id'][$payment_key]) && !empty($payment_details['payment_id'][$payment_key])) {
                        // Find existing payment transaction
                        if (in_array($payment_details['payment_id'][$payment_key], $poe_details->pluck('id')->toArray())) {
                            $payment_transaction = PaymentTransaction::findOrFail($payment_details['payment_id'][$payment_key]);
                        }
                    }

                    if ($payment_transaction === null) {
                        // Create a new payment transaction if not found
                        $payment_transaction = new PaymentTransaction();
                        $payment_transaction->transaction_number = CommonComponent::invoice_no('payment_transaction'); // Auto Generate
                        $payment_transaction->transaction_type = 2; // Sales Order
                        $payment_transaction->type = 1; // Credit
                        $payment_transaction->reference_id = $indent_request->id;
                    }

                    // Update payment transaction details
                    $payment_transaction->payment_type_id = $payment_type_id;
                    $payment_transaction->amount = @$payment_details['transaction_amount'][$payment_key] ?? 0;
                    $payment_transaction->transaction_datetime = @$payment_details['transaction_datetime'][$payment_key] ?? now();
                    $payment_transaction->note = @$payment_details['remark'][$payment_key] ?? '';
                    $payment_transaction->status = 1;
                    $payment_transaction->save();

                    Log::info("payment_transaction");
                    Log::info($payment_transaction);

                    // Handle payment transaction documents
                    if (isset($payment_details['payment_transaction_documents'][$payment_key]) && !empty($payment_details['payment_transaction_documents'][$payment_key])) {
                        CommonComponent::payment_transaction_documents(
                            $payment_details['payment_transaction_documents'][$payment_key],
                            2,
                            $payment_transaction->id// 1=> Sales Document
                        );
                    }
                }
            }

            // Update sales order payment status
            $sales_order_details = SalesOrder::with('sales_order_transactions')->findOrFail($indent_request->id);

            $paid_amount = $sales_order_details->sales_order_transactions->sum('amount');
            $total_amount = $sales_order_details->total_amount;
            Log::info("paid_amountpaid_amountpaid_amount");
            Log::info($paid_amount);
            Log::info("total_amount");
            Log::info($total_amount);

            if ($paid_amount == 0) {
                $sales_order_details->payment_status = $request->payment_status ?? 2; // UnPaid
            } else if ($paid_amount < $total_amount) {
                $sales_order_details->payment_status = $request->payment_status ?? 3; // Pending/partialy paid
            } else {
                $sales_order_details->payment_status = $request->payment_status ?? 1; // Paid
            }

            $sales_order_details->save();
        }

        $ptt_details = TransportTracking::where('sales_order_id', $id)->get();
        $request_old_ptt_ids = [];
        if (isset($request->transport_tracking['transport_type_id']) && count($request->transport_tracking['transport_type_id']) > 0) {
            foreach ($request->transport_tracking['transport_type_id'] as $exp_key => $value) {
                if ($request->transport_tracking['transport_tracking_id'][$exp_key] != null) {
                    $request_old_ptt_ids[] = $request->transport_tracking['transport_tracking_id'][$exp_key];
                }
            }
        }

        if (count($ptt_details) > 0) {
            foreach ($ptt_details as $exists_key => $value) {
                if (!in_array($value->id, $request_old_ptt_ids)) {
                    TransportTracking::where('id', $value->id)->delete();
                }
            }
        }

        $transport_tracking = $request->transport_tracking;
        if (count($transport_tracking) > 0) {
            foreach ($transport_tracking['transport_type_id'] as $track_key => $exp) {
                if (isset($transport_tracking['transport_type_id'][$track_key]) && $transport_tracking['transport_type_id'][$track_key] != null && $transport_tracking['transport_type_id'][$track_key] != "null") {
                    $imagePath = null;
                    $imageUrl = null;
                    if ($request->hasFile('transport_tracking.transport_tracking_file.' . $track_key)) {
                        $imageData = CommonComponent::s3BucketFileUpload($transport_tracking['transport_tracking_file'][$track_key], 'purchase_order_transport');
                        $imagePath = $imageData['filePath'];
                        // $imageUrl = $imageData['fileName'];
                        $imageUrl = $imageData['imageURL'];
                    }

                    if (isset($transport_tracking['transport_tracking_id'][$track_key]) && $transport_tracking['transport_tracking_id'][$track_key] != null) {
                        if (in_array($transport_tracking['transport_tracking_id'][$track_key], $ptt_details->pluck('id')->toArray())) {
                            $transport_trackings = TransportTracking::findOrFail($transport_tracking['transport_tracking_id'][$track_key]);
                        }
                        // else {
                        //     $transport_trackings = new TransportTracking();
                        //     $transport_trackings->sales_order_id = $indent_request->id;
                        // }
                    } else {
                        $transport_trackings = new TransportTracking();
                        $transport_trackings->sales_order_id = $indent_request->id;
                    }

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

        if ($request->vendor_indent_request_id != null && $request->status == 7) {
            $vendor_request = VendorIndentRequest::findOrfail($request->vendor_indent_request_id);
            $vendor_request->status = $request->status;
            $vendor_request->save();
            $exists_indent_product = VendorIndentRequestDetail::where('vendor_indent_request_id', $request->vendor_indent_request_id)->get();
            $products = $request->products;
            if (count($products) > 0) {
                foreach ($products['product_id'] as $key => $product) {
                    $product_data = Product::findOrfail($products['product_id'][$key]);
                    if (isset($products['product_id'][$key])) {
                        if (in_array($products['product_id'][$key], $exists_indent_product->pluck('product_id')->toArray())) {
                            $indent_request_detail = VendorIndentRequestDetail::where([['product_id', $products['product_id'][$key]], ['vendor_indent_request_id', $request->vendor_indent_request_id]])->first();
                        }
                        // else {
                        //     $indent_request_detail = new VendorIndentRequestDetail();
                        //     $indent_request_detail->vendor_indent_request_id = $request->vendor_indent_request_id;
                        //     $indent_request_detail->added_by_requestor = 1;
                        // }
                    } else {
                        $indent_request_detail = new VendorIndentRequestDetail();
                        $indent_request_detail->vendor_indent_request_id = $request->vendor_indent_request_id;
                        $indent_request_detail->added_by_requestor = 1;
                    }

                    $amount_with_expense = $products['amount'][$key] + $products['inc_exp_amount'][$key];
                    $amount = ((($amount_with_expense) * $products['commission_percentage'][$key]) / 100) + $amount_with_expense;

                    $indent_request_detail->product_id = $product_data->id;
                    $indent_request_detail->sku_code = $product_data->sku_code;
                    $indent_request_detail->name = $product_data->name;
                    $indent_request_detail->unit_id = $products['unit_id'][$key];
                    $indent_request_detail->request_quantity = $products['quantity'][$key];
                    $indent_request_detail->given_quantity = $products['given_quantity'][$key];
                    $indent_request_detail->amount = $amount;
                    $indent_request_detail->per_unit_price = @$products['per_unit_price'][$key];
                    $indent_request_detail->tax_id = $products['tax_id'][$key];
                    $indent_request_detail->tax_value = $products['tax_value'][$key];
                    $indent_request_detail->discount_type = $products['discount_type'][$key];
                    $indent_request_detail->discount_amount = $products['discount_amount'][$key] != null ? $products['discount_amount'][$key] : 0;
                    $indent_request_detail->discount_percentage = $products['discount_percentage'][$key] != null ? $products['discount_percentage'][$key] : 0;
                    $indent_request_detail->expense_amount = $products['inc_exp_amount'][$key];
                    $indent_request_detail->sub_total = $products['sub_total'][$key];

                    $indent_request_detail->save();
                }
            }

            $request_action = new VendorIndentRequestAction();
            $request_action->vendor_indent_request_id = $request->vendor_indent_request_id;
            $request_action->status = $request->status;
            $request_action->action_date = Carbon::now();
            $request_action->remarks = $request->remarks;
            $request_action->save();
        }

        DB::commit();

        if ($request->route == 1) {
            return redirect()
                ->route('admin.sales-credit.index')
                ->with('success', 'Sales Updated Successfully');
        } else {
            return redirect()
                ->route('admin.sales-order.index')
                ->with('success', 'Sales Updated Successfully');
        }

        // } catch (\Exception $e) {
        //     Log::error($e);
        //     DB::rollback();
        //     return back()->withInput()->with('error', 'Sales Updated Fail');
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

    public function sales_overview($id)
    {
        $data['sales'] = SalesOrder::findOrFail($id);
        return $data;
    }

    public function productsales_data($id)
    {
        $data = $common_data = $this->sales_overview($id);
        $data['sales_details'] = SalesOrderDetail::where('sales_order_id', $id)->orderBy('id', 'desc')->paginate(10);
        return view('pages.sales.sales_order.sales_product', $data);
    }

    public function transportsales_data($id)
    {
        $data = $common_data = $this->sales_overview($id);
        $data['sales_transport_trackings'] = TransportTracking::where('sales_order_id', $id)->orderBy('id', 'desc')->paginate(10);
        return view('pages.sales.sales_order.transport_details', $data);
    }

    public function salesexpences_data($id)
    {
        $data = $common_data = $this->sales_overview($id);
        $data['sales_expences'] = SalesExpense::select('sales_expenses.ie_amount', 'sales_expenses.is_billable', 'income_expense_types.id as type_id', 'income_expense_types.name as type_name')
            ->leftJoin('income_expense_types', 'sales_expenses.income_expense_id', '=', 'income_expense_types.id')
            ->where('sales_order_id', $id)
            ->orderBy('sales_expenses.id', 'desc')
            ->paginate(10);
        return view('pages.sales.sales_order.expences', $data);
    }

    public function salespayment_data($id)
    {
        $data = $common_data = $this->sales_overview($id);

        $data['sales_payments'] = PaymentTransaction::select('payment_transactions.*', 'payment_types.id as type_id', 'payment_types.payment_type as type_name')
            ->leftJoin('payment_types', 'payment_transactions.payment_type_id', '=', 'payment_types.id')
            ->where([
                ['reference_id', '=', $id],
                ['transaction_type', '=', 2],
            ])->orderBy('payment_transactions.id', 'desc')
            ->paginate(10);
        return view('pages.sales.sales_order.payment_details', $data);
    }
}
