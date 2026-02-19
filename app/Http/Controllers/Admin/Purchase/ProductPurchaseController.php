<?php

namespace App\Http\Controllers\Admin\Purchase;

use App\Core\CommonComponent;
use App\Http\Controllers\Controller;
use App\Models\PurchaseOrderBoxNumber;
use App\Models\PurchaseBoxNumberHistory;
use Illuminate\Http\Request;
use App\DataTables\Purchase\ProductPurchaseDataTable;
use App\Http\Requests\Purchase\ProductPurchaseFormRequest;
use App\Models\PurchaseOrderAction;
use App\Models\TransportType;
use App\Models\Unit;
use App\Models\PurchaseOrder;
use App\Models\TaxRate;
use App\Models\Warehouse;
use App\Models\Store;
use App\Models\Supplier;
use App\Models\IncomeExpenseType;
use App\Models\Product;
use App\Models\PurchaseOrderDetail;
use App\Models\WarehouseIndentRequest;
use App\Models\PurchaseOrderExpense;
use App\Models\TransportTracking;
use App\Models\WarehouseStockUpdate;
use App\Models\WarehouseInventoryDetail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use App\Http\Requests\IndentRequest\StoreIndentFormRequest;
use App\Models\PaymentTransaction;
use App\Models\PaymentTransactionDocument;
use App\Models\PaymentType;
use Illuminate\Support\Facades\Auth;
use App\Mail\PurchaseOrderCreateEmail;
use App\Mail\PurchaseOrderInvoiceEmail;
use Mail;

class ProductPurchaseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(ProductPurchaseDataTable $dataTable, Request $request)
    {
        $data['supplier_id'] = $request->supplier_id;
        Log::info($request->supplier_id); //comes from supplier wise purchse orders report via
        return $dataTable->render('pages.purchase.purchase_order.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data['transport_types'] = TransportType::active()->get();
        $data['expense_types'] = IncomeExpenseType::active()->where('type', 2)->get();
        $data['units'] = Unit::active()->get();

        $data['tax_rates'] = TaxRate::active()->get();
        $data['warehouses'] = Warehouse::select('id', 'name', 'code')
            ->active()
            ->get();
        $data['stores'] = Store::select('id', 'store_name', 'store_code')
            ->active()
            ->get();
        $data['suppliers'] = Supplier::active()
            ->get();
        $data['warehouse_indent_requests'] = WarehouseIndentRequest::select('id', 'request_code')
            ->get();
        $data['products'] = Product::where('status', 1)
            ->get();

        $data['payment_types'] = PaymentType::where('status', 1)
            ->where('payment_category', '!=', 2)
            ->orWhereIn('store_id', Auth::user()->user_stores())
            ->get();

        return view('pages.purchase.purchase_order.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ProductPurchaseFormRequest $request)
    {
        // return $request->all();
        DB::beginTransaction();
        try {
        $imagePath = null;
        $imageUrl = null;

        $total_expense_amount = array_sum($request->input('expense.expense_amount'));

        $indent_request = new PurchaseOrder();

        if ($request->hasFile('file')) {
            // $fileDeleted = CommonComponent::s3BucketFileDelete($indent_request->file, $indent_request->file_path);

            $imageData = CommonComponent::s3BucketFileUpload($request->file, 'purchase_order');
            $imagePath = $imageData['filePath'];
            // $imageUrl = $imageData['fileName'];
            $imageUrl = $imageData['imageURL'];
        }

        $indent_request->purchase_order_number = $request->purchase_order_number;
        $indent_request->warehouse_id = $request->warehouse_id;
        $indent_request->supplier_id = $request->supplier_id;
        $indent_request->warehouse_ir_id = $request->warehouse_ir_id;
        $indent_request->delivery_date = $request->delivery_date ?? '';
        // $indent_request->no_of_days_can_be_use = $request->no_of_days_can_be_use;

        $indent_request->status = $request->status;
        $indent_request->is_inc_exp_billable_for_all = $request->is_inc_exp_billable_for_all;
        $indent_request->total_request_quantity = $request->total_request_quantity;
        $indent_request->total_tax = $request->total_tax;
        $indent_request->discount_type = $request->discount_type;
        $indent_request->discount_percentage = $request->discount_percentage;
        $indent_request->discount_amount = $request->discount_amount;
        $indent_request->sub_total = $request->total_amount;
        $indent_request->adjustment_amount = $request->adjustment_amount;
        $indent_request->total = $request->total_amount;
        $indent_request->total_expense_amount = $total_expense_amount;
        $indent_request->total_expense_billable_amount = $request->total_expense_billable_amount_val;
        $indent_request->remarks = $request->remarks;
        if ($imageUrl != null) {
            $indent_request->file = $imageUrl;
            $indent_request->file_path = $imagePath;
        }
        $indent_request->save();

        if ($request->warehouse_ir_id != null) {
            $warehouse_request = WarehouseIndentRequest::findOrFail($request->warehouse_ir_id);
            $warehouse_request->status = $request->status;
            $warehouse_request->save();
        }


        // Product Details store
        $products = $request->products;
        if (!is_null($products) && is_array($products) && count($products) > 0) {
            foreach ($products['product_id'] as $key => $product) {
                $product_data = Product::findOrfail($products['product_id'][$key]);
                $indent_request_detail = new PurchaseOrderDetail();
                $indent_request_detail->purchase_order_id = $indent_request->id;
                $indent_request_detail->added_by_supplier = 0;
                $indent_request_detail->product_id = $product_data->id;
                $indent_request_detail->sku_code = $product_data->sku_code;
                $indent_request_detail->name = $product_data->name;
                $indent_request_detail->unit_id = @$products['unit_id'][$key];
                $indent_request_detail->per_unit_price = @$products['per_unit_price'][$key];
                $indent_request_detail->is_inc_exp_billable = $request->is_inc_exp_billable_for_all;
                $indent_request_detail->inc_exp_amount = @$products['inc_exp_amount'][$key];
                $indent_request_detail->request_quantity = @$products['quantity'][$key];
                $indent_request_detail->given_quantity = @$products['given_quantity'][$key];
                $indent_request_detail->amount = @$products['amount'][$key];
                $indent_request_detail->tax_id = @$products['tax_id'][$key];
                $indent_request_detail->tax_value = @$products['tax_value'][$key];
                $indent_request_detail->discount_type = @$products['discount_type'][$key];
                $indent_request_detail->discount_percentage = @$products['discount_percentage'][$key];
                $indent_request_detail->discount_amount = @$products['discount_amount'][$key];
                $indent_request_detail->sub_total = @$products['sub_total'][$key];
                // $indent_request_detail->remarks = @$products['remarks'][$key];
                $indent_request_detail->save();




                if (($request->stock_verified == '1') && ($request->status == '10')) {
                    $warehouse_stock_detail_exists = WarehouseStockUpdate::where([['warehouse_id', $request->warehouse_id], ['product_id', $product_data->id], ['status', 1]])->first();
                    // if($warehouse_stock_detail == null) {
                    $warehouse_stock_detail = new WarehouseStockUpdate();
                    $warehouse_stock_detail->warehouse_id = $request->warehouse_id;
                    $warehouse_stock_detail->product_id = $product_data->id;
                    // }
                    $warehouse_stock_detail->stock_update_on = Carbon::now();
                    $warehouse_stock_detail->existing_stock = (isset($warehouse_stock_detail_exists) && $warehouse_stock_detail_exists->total_stock != null) ? $warehouse_stock_detail_exists->total_stock : 0;
                    $warehouse_stock_detail->adding_stock = @$products['given_quantity'][$key];
                    $warehouse_stock_detail->total_stock = (isset($warehouse_stock_detail_exists) && $warehouse_stock_detail_exists->total_stock != null) ? $warehouse_stock_detail_exists->total_stock + @$products['given_quantity'][$key] : @$products['given_quantity'][$key];
                    $warehouse_stock_detail->status = 1;
                    $warehouse_stock_detail->stock_verified = $request->stock_verified;
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

        // Expense Details store
        $expense = $request->expense;
        if (count($expense) > 0 && $expense['expense_type_id'][0] != null) {
            foreach ($expense['expense_type_id'] as $expense_key => $exp) {
                $purchase_expense = new PurchaseOrderExpense();
                $purchase_expense->purchase_order_id = $indent_request->id;
                $purchase_expense->income_expense_id = @$expense['expense_type_id'][$expense_key];
                $purchase_expense->ie_amount = @$expense['expense_amount'][$expense_key];
                $purchase_expense->is_billable = @$expense['is_billable'][$expense_key];
                $purchase_expense->save();
            }
        }

        $request_action = new PurchaseOrderAction();
        $request_action->purchase_order_id = $indent_request->id;
        $request_action->status = $request->status;
        $request_action->action_date = Carbon::now();
        $request_action->remarks = $request->remarks;
        $request_action->save();

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
                $transport_trackings->purchase_order_id = $indent_request->id;
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
        Log::info("payment_details");
        $payment_details = $request->payment_details;
        Log::info($payment_details);
        if (count($payment_details) > 0 && $payment_details['payment_type_id'][0] != null) {
        Log::info("payment_details is coming");
            foreach ($payment_details['payment_type_id'] as $payment_key => $payment) {
                $payment_transaction = new PaymentTransaction();
                $payment_transaction->transaction_number = CommonComponent::invoice_no('payment_transaction'); // Auto Generate
                $payment_transaction->transaction_type = 1; // Purchase Order
                $payment_transaction->type = 2; // Debit
                $payment_transaction->reference_id = $indent_request->id;
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

            $purchase_order_details = PurchaseOrder::with('purchase_order_transactions')->findOrFail($indent_request->id);

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
        }

        DB::commit();

        if ($request->submission_type == 1) {
            return redirect()->route('admin.purchase-order.index')->with('success', 'Purchase Order Stored Successfully');
        } elseif ($request->submission_type == 2) {
            return back()->with('success', 'Purchase Order Stored Successfully');
        }

        } catch (\Exception $e) {
            Log::error($e);
            DB::rollback();
            return back()->withInput()->with('error', 'Purchase Order Stored Fail');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $data = $common_data = $this->purchase_overview($id);
        $data['purchase_action'] = PurchaseOrderAction::where('purchase_order_id', $id)->orderBy('id', 'desc')->paginate(10);
        return view('pages.purchase.purchase_order.show', $data);
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
        if (str_contains($previousUrl, 'purchase-credit')) {
            $data['route'] = 1;
        } elseif (str_contains($previousUrl, 'purchase-order')) {
            $data['route'] = 2;
        } else {
            $data['route'] = 0;
        }


        $data['transport_types'] = TransportType::active()->get();
        $data['expense_types'] = IncomeExpenseType::active()->where('type', 2)->get();
        $data['units'] = Unit::active()->get();
        $data['indent_request'] = PurchaseOrder::findOrfail($id);

        $data['tax_rates'] = TaxRate::active()->get();
        $data['warehouses'] = Warehouse::select('id', 'name', 'code')
            ->active()
            ->get();
        $data['stores'] = Store::select('id', 'store_name', 'store_code')
            ->active()
            ->get();
        $data['suppliers'] = Supplier::active()
            ->get();
        $data['warehouse_indent_requests'] = WarehouseIndentRequest::select('id', 'request_code')
            ->get();
        $data['payment_types'] = PaymentType::where('status', 1)
            ->where('payment_category', '!=', 2)
            ->orWhereIn('store_id', Auth::user()->user_stores())
            ->get();
        return view('pages.purchase.purchase_order.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(ProductPurchaseFormRequest $request, $id)
    {

        DB::beginTransaction();
        try {
        $imagePath = null;
        $imageUrl = null;

        $total_expense_amount = array_sum($request->input('expense.expense_amount'));

        $indent_request = PurchaseOrder::findOrfail($id);

        if ($request->hasFile('file')) {
            $fileDeleted = CommonComponent::s3BucketFileDelete($indent_request->file, $indent_request->file_path);

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
        // $indent_request->no_of_days_can_be_use = $request->no_of_days_can_be_use;

        $indent_request->status = $request->status;
        $indent_request->is_inc_exp_billable_for_all = $request->is_inc_exp_billable_for_all;
        $indent_request->total_request_quantity = $request->total_request_quantity;
        $indent_request->total_tax = $request->total_tax;
        $indent_request->discount_type = $request->discount_type;
        $indent_request->discount_percentage = $request->discount_percentage;
        $indent_request->discount_amount = $request->discount_amount;
        $indent_request->sub_total = $request->total_amount;
        $indent_request->adjustment_amount = $request->adjustment_amount;
        $indent_request->total = $request->total_amount;
        $indent_request->total_expense_amount = $total_expense_amount;
        $indent_request->total_expense_billable_amount = $request->total_expense_billable_amount_val;
        $indent_request->remarks = $request->remarks;
        if ($imageUrl != null) {
            $indent_request->file = $imageUrl;
            $indent_request->file_path = $imagePath;
        }
        $indent_request->save();




        $request_old_ids = [];
        if (isset($request->products['product_id']) && is_array($request->products['product_id'])) {
            foreach ($request->products['product_id'] as $store_key => $value) {
                if (isset($request->products['id'][$store_key]) && $request->products['id'][$store_key] != null) {
                    $request_old_ids[] = $request->products['id'][$store_key];
                }
            }
        }

        $exists_indent_product = PurchaseOrderDetail::where('purchase_order_id', $id)->get();
        if (count($exists_indent_product) > 0) {
            foreach ($exists_indent_product as $exists_key => $value) {
                if (!in_array($value->id, $request_old_ids)) {
                    PurchaseOrderDetail::where('id', $value->id)->delete();
                }
            }
        }

        // if ($request->stock_verified == 1) {
        //     // Box number Stored

        //     $weight = $indent_request->give_qunatity;
        //     $product_id = $indent_request->product_id;
        //     $BoxNumber_existing = PurchaseOrderBoxNumber::where([['purchase_order_id', $indent_request->purchase_order_number], ['product_id', $product_id], ['status', 1]])->orderBy('id', 'DESC')->first();
        //     // $BoxNumber_existing = PurchaseOrderBoxNumber::where('purchase_order_id', $indent_request->purchase_order_number)->first();
        //     $BoxNumber = new PurchaseOrderBoxNumber();

        //     // if (is_null($BoxNumber)) {
        //     //     $BoxNumber = new PurchaseOrderBoxNumber();
        //     // }
        //     $BoxNumber->purchase_order_id = $indent_request->id;
        //     $BoxNumber->product_id = $product_id;
        //     $BoxNumber->date = $indent_request->delivery_date;
        //     $BoxNumber->existing_stock = ($BoxNumber_existing != null && $BoxNumber_existing->total_stock != null) ? $BoxNumber_existing->total_stock : 0;
        //     $BoxNumber->adding_stock = @$weight;
        //     $BoxNumber->total_stock = ($BoxNumber_existing != null && $BoxNumber_existing->total_stock != null) ? $BoxNumber_existing->total_stock + @$weight : @$weight;
        //     $BoxNumber->save();
        // }

        $products = $request->products;
        if (!is_null($products) && is_array($products) && count($products) > 0) {
            foreach ($products['product_id'] as $key => $product) {
                $product_data = Product::findOrfail($products['product_id'][$key]);
                if (isset($products['id'][$key])) {
                    if (in_array($products['id'][$key], $exists_indent_product->pluck('id')->toArray())) {
                        $indent_request_detail = PurchaseOrderDetail::findOrFail($products['id'][$key]);
                    }
                    // else {
                    //     $indent_request_detail = new PurchaseOrderDetail();
                    //     $indent_request_detail->purchase_order_id = $indent_request->id;
                    //     $indent_request_detail->added_by_supplier = 2;
                    // }
                } else {
                    $indent_request_detail = new PurchaseOrderDetail();
                    $indent_request_detail->purchase_order_id = $indent_request->id;
                    $indent_request_detail->added_by_supplier = 2;
                }
                $indent_request_detail->product_id = $product_data->id;
                $indent_request_detail->sku_code = $product_data->sku_code;
                $indent_request_detail->name = $product_data->name;
                $indent_request_detail->unit_id = @$products['unit_id'][$key];
                $indent_request_detail->per_unit_price = @$products['per_unit_price'][$key];
                $indent_request_detail->is_inc_exp_billable = $request->is_inc_exp_billable_for_all;
                $indent_request_detail->inc_exp_amount = @$products['inc_exp_amount'][$key];
                $indent_request_detail->request_quantity = @$products['quantity'][$key];
                $indent_request_detail->given_quantity = @$products['given_quantity'][$key];
                $indent_request_detail->amount = @$products['amount'][$key];
                $indent_request_detail->tax_id = @$products['tax_id'][$key];
                $indent_request_detail->tax_value = @$products['tax_value'][$key];
                $indent_request_detail->discount_type = @$products['discount_type'][$key];
                $indent_request_detail->discount_percentage = @$products['discount_percentage'][$key];
                $indent_request_detail->discount_amount = @$products['discount_amount'][$key];
                $indent_request_detail->sub_total = @$products['sub_total'][$key];
                // $indent_request_detail->remarks = @$products['remarks'][$key];
                $indent_request_detail->save();

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
                   // // old insert
                    // $warehouse_stock_detail->existing_stock = $warehouse_stock_detail->total_stock != null ? $warehouse_stock_detail->total_stock : 0;
                    // $warehouse_stock_detail->adding_stock = @$products['given_quantity'][$key];
                    // $warehouse_stock_detail->total_stock = $warehouse_stock_detail->total_stock != null ? $warehouse_stock_detail->total_stock + @$products['given_quantity'][$key] : @$products['given_quantity'][$key];
                    $warehouse_stock_detail->existing_stock = $existing_stock;
                    $warehouse_stock_detail->adding_stock = $adding_stock;
                    $warehouse_stock_detail->total_stock = $total_stock;
                    $warehouse_stock_detail->status = 1;
                    $warehouse_stock_detail->stock_verified = $request->stock_verified;
                    $warehouse_stock_detail->box_number = $request->box_number != null ? $request->box_number : $warehouse_stock_detail->box_number;
                    $warehouse_stock_detail->save();
// return $warehouse_stock_detail;


                    // Box number Histories Stored
                    // $BoxNumberHistoy = PurchaseBoxNumberHistory::where([['purchase_box_id', $BoxNumber->id], ['status', 1]])->first();
                    // if ($warehouse_stock_detail == null) {
                    //     $BoxNumberHistoy = new PurchaseBoxNumberHistory();
                    //     $BoxNumberHistoy->purchase_box_id = $BoxNumber->id;
                    // }

                    // $expression = 'subtraction';

                    // if ($expression == 'addition') {
                    //     $type = 1;
                    //     $quantity = +$weight;
                    // } else if ($expression == 'subtraction') {
                    //     $quantity = -$weight;
                    // }
                    // $BoxNumberHistoy = new PurchaseBoxNumberHistory();
                    // $BoxNumberHistoy->product_id = @$products['product_id'][$key];
                    // $BoxNumberHistoy->quantity = @$quantity;
                    // $BoxNumberHistoy->type = $type == 1 ? 1 : 0;    // 1is additions 0 is subtraction
                    // $BoxNumberHistoy->box_no = @$products['box_no'][$key];
                    // $BoxNumberHistoy->save();


                    $warehouse_inventory = WarehouseInventoryDetail::where([['warehouse_id', $request->warehouse_id], ['product_id', $product_data->id], ['status', 1]])->first();
                    if($equal_check_adding) { // check if previous and current quantity are same
                        $inventory_weight = @$warehouse_inventory->weight;
                    } elseif($greater_check_adding) { // check if  current quantity are greater than previous quantity
                        $inventory_weight = @$warehouse_inventory->weight + $difference_stock ;
                    } else {
                        $inventory_weight = @$warehouse_inventory->weight + $difference_stock;
                    }

                    if ($warehouse_inventory == null) {
                        $warehouse_inventory = new WarehouseInventoryDetail();
                        $warehouse_inventory->warehouse_id = $request->warehouse_id;
                        $warehouse_inventory->product_id = $product_data->id;
                    }
                    $warehouse_inventory->weight = $inventory_weight;
                    // // old insert
                    // $warehouse_inventory->weight = @$warehouse_inventory->weight + $warehouse_stock_detail->adding_stock;
                    $warehouse_inventory->status = 1;
                    $warehouse_inventory->save();
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

        $poe_details = PurchaseOrderExpense::where('purchase_order_id', $id)->get();
        if (count($poe_details) > 0) {
            foreach ($poe_details as $exists_key => $value) {
                if (!in_array($value->id, $request_old_expense_ids)) {
                    PurchaseOrderExpense::where('id', $value->id)->delete();
                }
            }
        }

        $expense = $request->expense;
        if (count($expense) > 0 && count($expense['expense_type_id']) > 0) {
            foreach ($expense['expense_type_id'] as $expense_key => $exp) {
                if ($expense['expense_type_id'][$expense_key] != null) {
                    if (isset($expense['expense_id'][$expense_key]) && $expense['expense_id'][$expense_key] != null) {
                        if (in_array($expense['expense_id'][$expense_key], $poe_details->pluck('id')->toArray())) {
                            $purchase_expense = PurchaseOrderExpense::findOrFail($expense['expense_id'][$expense_key]);
                        }
                        // else {
                        //     $purchase_expense = new PurchaseOrderExpense();
                        //     $purchase_expense->purchase_order_id = $indent_request->id;
                        // }
                    } else {
                        $purchase_expense = new PurchaseOrderExpense();
                        $purchase_expense->purchase_order_id = $indent_request->id;
                    }
                    $purchase_expense->income_expense_id = @$expense['expense_type_id'][$expense_key];
                    $purchase_expense->ie_amount = @$expense['expense_amount'][$expense_key];
                    $purchase_expense->is_billable = @$expense['is_billable'][$expense_key];
                    $purchase_expense->save();
                }
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
        Log::info("payment_details");
        // Payment Transaction Details store
        $payment_details = $request->payment_details;
        Log::info($payment_details);
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
                        $payment_transaction->transaction_type = 1; // Purchase Order
                        $payment_transaction->type = 2; // Debit
                        $payment_transaction->reference_id = $indent_request->id;
                    }
                    $payment_transaction->payment_type_id = @$payment_details['payment_type_id'][$payment_key];
                    $payment_transaction->amount = @$payment_details['transaction_amount'][$payment_key];
                    $payment_transaction->transaction_datetime = @$payment_details['transaction_datetime'][$payment_key];
                    $payment_transaction->note = @$payment_details['remark'][$payment_key];
                    $payment_transaction->status = 1;
                    $payment_transaction->save();
                }

                // Send email using the dynamically set configurations
                // Mail::queue(new PurchaseOrderInvoiceEmail($payment_transaction->id));

                if (isset($payment_details['payment_transaction_documents'][$payment_key]) && count($payment_details['payment_transaction_documents']) > 0) {
                    CommonComponent::payment_transaction_documents(($payment_details['payment_transaction_documents'][$payment_key]), 1, $payment_transaction->id); // 1=> Purchase Document
                }
            }

            $purchase_order_details = PurchaseOrder::with('purchase_order_transactions')->findOrFail($indent_request->id);

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
        }

        $ptt_details = TransportTracking::where('purchase_order_id', $id)->get();
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
                        //     $transport_trackings->purchase_order_id = $indent_request->id;
                        // }
                    } else {
                        $transport_trackings = new TransportTracking();
                        $transport_trackings->purchase_order_id = $indent_request->id;
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

        $request_action = new PurchaseOrderAction();
        $request_action->purchase_order_id = $indent_request->id;
        $request_action->status = $request->status;
        $request_action->action_date = Carbon::now();
        $request_action->remarks = $request->remarks;
        $request_action->save();

        DB::commit();
        if ($request->route == 1) {
            return redirect()
                ->route('admin.purchase-credit.index')
                ->with('success', 'purchase Updated Successfully');
        } elseif ($request->route == 2) {
            return redirect()
                ->route('admin.purchase-order.index')
                ->with('success', 'purchase Updated Successfully');
        } elseif ($request->submission_type == 1) {
            return redirect()
                ->route('admin.purchase-order.index')
                ->with('success', 'Purchase Order Updated Successfully');
        } elseif ($request->submission_type == 2) {
            return back()->with('success', 'Purchase Order Updated Successfully');
        }

        } catch (\Exception $e) {
            Log::error($e);
            DB::rollback();
            return back()->withInput()->with('error', 'Purchase Order Updated Fail');
        }
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

    public function itemdetailrender(Request $request)
    {
        $data['product'] = $request->product;
        $data['unit'] = $request->product;
        $data['quantity'] = $request->product;
        $data['amount'] = $request->product;
        $data['tax'] = $request->product;
        $data['discount'] = $request->product;
        $data['sub_total'] = $request->product;
        return view('pages.purchase.product.itemrender', $data)->render();
    }

    // purchase Details get
    public function purchase_overview($id)
    {
        $data['purchase'] = PurchaseOrder::findOrFail($id);
        return $data;
    }

    public function product_data($id)
    {
        $data = $common_data = $this->purchase_overview($id);
        $data['purchase_details'] = PurchaseOrderDetail::where('purchase_order_id', $id)->orderBy('id', 'desc')->paginate(10);
        return view('pages.purchase.purchase_order.product_table', $data);
    }

    public function transport_data($id)
    {
        $data = $common_data = $this->purchase_overview($id);
        $data['transport_trackings'] = TransportTracking::where('purchase_order_id', $id)->orderBy('id', 'desc')->paginate(10);
        return view('pages.purchase.purchase_order.transport_details', $data);
    }

    public function expences_data($id)
    {
        $data = $common_data = $this->purchase_overview($id);
        $data['purchases_expences'] = PurchaseOrderExpense::select('purchase_order_expenses.ie_amount', 'income_expense_types.id as type_id', 'income_expense_types.name as type_name')
            ->leftJoin('income_expense_types', 'purchase_order_expenses.income_expense_id', '=', 'income_expense_types.id')
            ->where('purchase_order_id', $id)
            ->orderBy('purchase_order_expenses.id', 'desc')
            ->paginate(10);
        return view('pages.purchase.purchase_order.expences', $data);
    }

    public function payment_data($id)
    {
        // dd($id);
        $data = $common_data = $this->purchase_overview($id);

        $data['purchases_payments'] = PaymentTransaction::select('payment_transactions.*', 'payment_types.id as type_id', 'payment_types.payment_type as type_name')
            ->leftJoin('payment_types', 'payment_transactions.payment_type_id', '=', 'payment_types.id')
            ->where([
                ['reference_id', '=', $id],
                ['transaction_type', '=', 1],
            ])->orderBy('payment_transactions.id', 'desc')
            ->paginate(10);
        return view('pages.purchase.purchase_order.payment_details', $data);
    }

    public function productPinMapping($id)
    {
        $data['purchase_orders_details'] = PurchaseOrder::with('purchase_order_product_details')->find($id);

        $data['products'] = Product::where('status', 1)
            ->get();


        return view('pages.purchase.pin_mapping.product_pin_mapping', $data);
    }

    public function pinMapping(Request $request, $id)
    {
        // Validate input
        $request->validate([
            'product_pin_details' => 'required|array',
            'product_pin_details.box_product_id' => 'required|array',
            'product_pin_details.box_no' => 'required|array',
            'product_pin_details.box_weight' => 'required|array',
        ]);

        try {
            $product_pin_details = $request->product_pin_details;
            $date = Carbon::now();
            foreach ($product_pin_details['box_product_id'] as $key => $details) {
                $product_id = $product_pin_details['box_product_id'][$key];
                $box_no = $product_pin_details['box_no'][$key];
                $weight = $product_pin_details['box_weight'][$key] ?? 0;

                // Check if box number exists
                $existingBoxNumber = PurchaseOrderBoxNumber::where('purchase_order_id', $id)
                    ->where('product_id', $product_id)
                    ->where('box_no', $box_no)
                    ->orderBy('id', 'DESC')
                    ->first();

                if ($existingBoxNumber !== null) {
                    $existingBoxNumber->delete();
                    return response()->json([
                        'status' => 400,
                        'message' => "This Product is already Stored Pin No $box_no. Please Use Another Pin",
                    ]);
                }

                // Create new box number
                // $newBoxNumber = new PurchaseOrderBoxNumber();
                // $newBoxNumber->purchase_order_id = $id;
                // $newBoxNumber->product_id = $product_id;
                // $newBoxNumber->box_no = $box_no;
                // $newBoxNumber->quantity = $weight;
                // $newBoxNumber->date = now();
                // $newBoxNumber->existing_stock = $existingBoxNumber ? $existingBoxNumber->total_stock : 0;
                // $newBoxNumber->adding_stock = $weight;
                // $newBoxNumber->total_stock = $existingBoxNumber ? $existingBoxNumber->total_stock + $weight : $weight;
                // $newBoxNumber->save();

                // // Log box number history
                // $boxNumberHistory = new PurchaseBoxNumberHistory();
                // $boxNumberHistory->product_id = $product_id;
                // $boxNumberHistory->quantity = $weight;
                // $boxNumberHistory->type = 1; // 1 is addition
                // $boxNumberHistory->box_no = $box_no;
                // $boxNumberHistory->date = now();
                // $boxNumberHistory->save();
                // Update or create new PurchaseOrderBoxNumber
                $data['newBoxNumber'] = PurchaseOrderBoxNumber::updateOrCreate(
                    [
                        'purchase_order_id' => $id,
                        'product_id' => $product_id,
                        'box_no' => $box_no
                    ],
                    [
                        'quantity' => $weight,
                        'date' => $date,
                        'existing_stock' => $existingBoxNumber ? $existingBoxNumber->total_stock : 0,
                        'adding_stock' => $weight,
                        'total_stock' => $existingBoxNumber ? $existingBoxNumber->total_stock + $weight : $weight
                    ]
                );

                // Update or create new PurchaseBoxNumberHistory
                $data['boxNumberHistory'] = PurchaseBoxNumberHistory::updateOrCreate(
                    [
                        'product_id' => $product_id,
                        'box_no' => $box_no,
                        'type' => 1
                    ], // Assuming type 1 is for addition
                    [
                        'quantity' => $weight,
                        'date' => $date
                    ]
                );
            }

            return redirect()->route('admin.purchase-order.index')
                ->with('success', 'Purchase Product Pin Mapping Successfully');
        } catch (\Exception $e) {
            // Handle exceptions
            return redirect()->back()->with('error', 'An error occurred. Please try again.');
        }
    }
    // public function productMappingView()
    // {
    //     // try {

    //     $data['products'] = Product::with([
    //         'product_pin_mapping_histories'
    //     ])->get();

    //     // $data['products'] = Product::orderBy('id', 'ASC')->get();
    //     return view('pages.purchase.pin_mapping.product_pin_mapping_view', $data);
    //     // } catch (\Exception $e) {
    //     //     // Handle exceptions
    //     //     return redirect()->back()->with('error', 'An error occurred. Please try again.');
    //     // }
    // }

}
