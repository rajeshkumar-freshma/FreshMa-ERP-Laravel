<?php

namespace App\Http\Controllers\Admin\Returns;

use App\DataTables\Returns\PurchaseOrderReturnDataTable;
use App\Http\Controllers\Controller;
use App\Http\Requests\Returns\PurchaseReturnFormRequest;
use App\Models\IncomeExpenseType;
use App\Models\Product;
use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderReturn;
use App\Models\PurchaseOrderReturnDetail;
use App\Models\PurchaseOrderReturnExpense;
use App\Models\SalesOrderReturnExpense;
use App\Models\Supplier;
use App\Models\TransportTracking;
use App\Models\TransportType;
use App\Models\PaymentTransaction;
use App\Models\PaymentType;
use App\Models\Unit;
use App\Models\Warehouse;
use App\Models\WarehouseInventoryDetail;
use App\Models\WarehouseStockUpdate;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Core\CommonComponent;

class PurchaseReturnController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(PurchaseOrderReturnDataTable $dataTable)
    {
        return $dataTable->render('pages.return.purchase_return.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $data['warehouses'] = Warehouse::get();
        $data['suppliers'] = Supplier::active()->get();
        $data['units'] = Unit::active()->get();
        $data['transport_types'] = TransportType::active()->get();
        $data['expense_types'] = IncomeExpenseType::active()->get();
        $data['payment_types'] = PaymentType::where('status', 1)
            ->where('payment_category', '!=', 2)
            ->orWhereIn('store_id', Auth::user()->user_stores())
            ->get();
        return view('pages.return.purchase_return.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(PurchaseReturnFormRequest $request)
    {

        DB::beginTransaction();
        try {

            $from_warehouse_id = $request->from_warehouse_id;
            $request_status = $request->status;
            $request_box_number = $request->box_number;

        $purchase_order_return = new PurchaseOrderReturn();
        $purchase_order_return->purchase_order_return_number = $request->purchase_order_return_number;
        $purchase_order_return->from_warehouse_id = $request->from_warehouse_id;
        $purchase_order_return->to_supplier_id = $request->to_supplier_id;
        if ($request->purchase_order_id != null && $request->purchase_order_id != 'null') {
            $sales_order = PurchaseOrder::select('id', 'delivery_date')->find($request->purchase_order_id);
            $purchase_order_return->purchase_order_id = $request->purchase_order_id;
            $purchase_order_return->ordered_date = $sales_order->delivery_date;
        }
        $purchase_order_return->return_date = $request->return_date;
        $purchase_order_return->return_type = $request->return_type != null ? $request->return_type : 1;
        $purchase_order_return->sub_total = $request->sub_total_amount != null ? $request->sub_total_amount : 0;
        $purchase_order_return->total_amount = $request->total_amount != null ? $request->total_amount : 0;
        $purchase_order_return->remarks = $request->remarks;
        $purchase_order_return->status = $request_status ?? 1;
        $purchase_order_return->save();

        // Product Details store
        $products = $request->products;
        if (count($products) > 0) {
            foreach ($products['product_id'] as $key => $product) {
                $product_data = Product::findOrfail($products['product_id'][$key]);
                $purchase_order_return_product_detail = new PurchaseOrderReturnDetail();
                $purchase_order_return_product_detail->purchase_order_return_id = $purchase_order_return->id;
                $purchase_order_return_product_detail->product_id = $product_data->id;
                $purchase_order_return_product_detail->sku_code = $product_data->sku_code;
                $purchase_order_return_product_detail->name = $product_data->name;
                $purchase_order_return_product_detail->per_unit_price = @$products['per_unit_price'][$key];
                $purchase_order_return_product_detail->quantity = @$products['quantity'][$key];
                $purchase_order_return_product_detail->unit_id = @$products['unit_id'][$key];
                $purchase_order_return_product_detail->total = @$products['sub_total'][$key];
                $purchase_order_return_product_detail->status = 1;
                $purchase_order_return_product_detail->save();


                $warehouse_stock_detail = WarehouseStockUpdate::where([['warehouse_id', $from_warehouse_id], ['product_id', $product_data->id], ['status', 1]])->orderBy('id', 'DESC')->latest()->first();

                if ($request_status == '11') {
                    $previous_adding_stock = @$warehouse_stock_detail->adding_stock;
                    $previous_existing_stock = @$warehouse_stock_detail->existing_stock;
                    $previous_total_stock = @$warehouse_stock_detail->total_stock;

                    $given_quantity = $purchase_order_return_product_detail->quantity;

                    $equal_check_adding = $previous_adding_stock ? $previous_adding_stock == $given_quantity : false;
                    $greater_check_adding = $previous_adding_stock ? $previous_adding_stock < $given_quantity: false;

                    if($equal_check_adding) {  // check if previous and current quantity are same
                        Log::info('entered equal condition');
                        $difference_stock = $previous_total_stock - $given_quantity;
                        $adding_stock = - $given_quantity;
                        $existing_stock = $previous_total_stock ?? 0;
                        $total_stock = $difference_stock;
                    } elseif($greater_check_adding) {
                        Log::info('entered greater condition');  // check if  current quantity are greater than previous quantity
                        $difference_stock = $previous_total_stock - $given_quantity;
                        $adding_stock = - $given_quantity;
                        $existing_stock = $previous_total_stock ?? 0;
                        $total_stock = $difference_stock;
                    } else {
                        Log::info('entered else condition');
                        $difference_stock = $previous_total_stock - $given_quantity;
                        $adding_stock = - $given_quantity;
                        $existing_stock = $previous_total_stock ?? 0;
                        $total_stock = $difference_stock;
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
                    $warehouse_stock_detail->stock_verified = $request_status == '11' ? 1 : 0;
                    $warehouse_stock_detail->box_number = $request_box_number != null ? $request_box_number : $warehouse_stock_detail->box_number;
                    $warehouse_stock_detail->save();

                    $warehouse_inventory = WarehouseInventoryDetail::where([['warehouse_id', $from_warehouse_id], ['product_id', $product_data->id], ['status', 1]])->latest()->first();
                    if($equal_check_adding) { // check if previous and current quantity are same
                        $inventory_weight = $difference_stock;
                    } elseif($greater_check_adding) { // check if  current quantity are greater than previous quantity
                        $inventory_weight = $difference_stock ;
                    } else {
                        $inventory_weight =  $difference_stock;
                    }

                    if ($warehouse_inventory == null) {
                        $warehouse_inventory = new WarehouseInventoryDetail();
                        $warehouse_inventory->warehouse_id = $from_warehouse_id;
                        $warehouse_inventory->product_id = $product_data->id;
                    }
                    $warehouse_inventory->weight = $inventory_weight;
                    $warehouse_inventory->status = 1;
                    $warehouse_inventory->save();
                }
            }
        }

        // Expense Details store
        $total_expense_amount = 0;
        $total_expense_billable_amount = 0;
        $expense = $request->expense;
        if (count($expense) > 0 && $expense['expense_type_id'][0] != null) {
            foreach ($expense['expense_type_id'] as $expense_key => $exp) {
                $purchase_order_return_expense = new PurchaseOrderReturnExpense();
                $purchase_order_return_expense->purchase_order_return_id = $purchase_order_return->id;
                $purchase_order_return_expense->income_expense_id = @$expense['expense_type_id'][$expense_key];
                $purchase_order_return_expense->ie_amount = @$expense['expense_amount'][$expense_key];
                // $purchase_order_return_expense->is_billable = @$expense['is_billable'][$expense_key];
                if (@$expense['is_billable'][$expense_key] == 1) {
                    $total_expense_billable_amount += @$expense['expense_amount'][$expense_key];
                }
                $total_expense_amount += @$expense['expense_amount'][$expense_key];
                $purchase_order_return_expense->save();
            }
            $purchase_order_return = PurchaseOrderReturn::findOrFail($purchase_order_return->id);
            $purchase_order_return->total_expense_billable_amount = $total_expense_billable_amount;
            $purchase_order_return->total_expense_amount = $total_expense_amount;
            $purchase_order_return->save();
        }

        $transport_tracking = $request->transport_tracking;
        if (count($transport_tracking) > 0) {
            foreach ($transport_tracking['transport_type_id'] as $track_key => $exp) {
                if ($transport_tracking['transport_type_id'][$track_key]) {
                    $imagePath = null;
                    $imageUrl = null;
                    if ($request->hasFile('transport_tracking.transport_tracking_file.' . $track_key)) {
                        $imageData = CommonComponent::s3BucketFileUpload($transport_tracking['transport_tracking_file'][$track_key], 'purchase_order_return_transport');
                        $imagePath = $imageData['filePath'];
                        // $imageUrl = $imageData['fileName'];
                        $imageUrl = $imageData['imageURL'];
                    }

                    $transport_trackings = new TransportTracking();
                    $transport_trackings->purchase_order_return_id = $purchase_order_return->id;
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
        DB::commit();

        if ($request->submission_type == 1) {
            return redirect()->route('admin.purchase-return.index')->with('success', 'Purchase Order Return Stored Successfully');
        } elseif ($request->submission_type == 2) {
            return back()->with('success', 'Purchase Order Return Stored Successfully');
        }
        } catch (\Exception $e) {
            Log::error($e);
            DB::rollback();
            return back()->with('danger', 'Sales Order Return Stored Fail.');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $data = $common_data = $this->purchaseReturnOverView($id);
            return view('pages.return.purchase_return.view', $data);
        } catch (\Exception $e) {
            Log::error($e);
            DB::rollback();
            return back()->with('danger', 'Purchase Order Return View Fail.');
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $data['purchase_order'] = null;
        $data['sales_invoice_number'] = null;
        $data['purchase_order_return'] = PurchaseOrderReturn::find($id);
        if ($data['purchase_order_return']->purchase_order_id != null) {
            $data['purchase_order'] = PurchaseOrder::select('id', 'purchase_order_number')->find($data['purchase_order_return']->purchase_order_id);
        }
        $data['warehouses'] = Warehouse::get();
        $data['suppliers'] = Supplier::active()->get();
        $data['transport_types'] = TransportType::active()->get();
        $data['expense_types'] = IncomeExpenseType::active()->get();
        $data['units'] = Unit::active()->get();
        $data['payment_types'] = PaymentType::where('status', 1)
            ->where('payment_category', '!=', 2)
            ->orWhereIn('store_id', Auth::user()->user_stores())
            ->get();
        return view('pages.return.purchase_return.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(PurchaseReturnFormRequest $request, $id)
    {

        DB::beginTransaction();
        try {
            $from_warehouse_id = $request->from_warehouse_id;
            $request_status = $request->status;
            $request_box_number = $request->box_number;
        $purchase_order_return = PurchaseOrderReturn::find($id);
        $purchase_order_return->from_warehouse_id = $request->from_warehouse_id;
        $purchase_order_return->purchase_order_return_number = $request->purchase_order_return_number;
        $purchase_order_return->to_supplier_id = $request->to_supplier_id;
        if ($request->purchase_order_id != null && $request->purchase_order_id != 'null') {
            $sales_order = PurchaseOrder::select('id', 'delivery_date')->find($request->purchase_order_id);
            $purchase_order_return->purchase_order_id = $request->purchase_order_id;
            $purchase_order_return->ordered_date = $sales_order->delivery_date;
        }
        $purchase_order_return->return_date = $request->return_date;
        $purchase_order_return->return_type = $request->return_type != null ? $request->return_type : 1;
        $purchase_order_return->sub_total = $request->sub_total_amount != null ? $request->sub_total_amount : 0;
        $purchase_order_return->total_amount = $request->total_amount != null ? $request->total_amount : 0;
        $purchase_order_return->remarks = $request->remarks;
        $purchase_order_return->status = $request_status;
        $purchase_order_return->save();

        $request_old_ids = [];
        foreach ($request->products['product_id'] as $check_key => $value) {
            if ($request->products['id'][$check_key] != null) {
                $request_old_ids[] = $request->products['id'][$check_key];
            }
        }

        $exists_purchase_return_product = PurchaseOrderReturnDetail::where('purchase_order_return_id', $id)->get();
        if (count($exists_purchase_return_product) > 0) {
            foreach ($exists_purchase_return_product as $exists_key => $value) {
                if (!in_array($value->id, $request_old_ids)) {
                    PurchaseOrderReturnDetail::where('id', $value->id)->delete();
                }
            }
        }

        // Product Details store
        $products = $request->products;
        if (count($products) > 0) {
            foreach ($products['product_id'] as $key => $product) {
                $product_data = Product::findOrfail($products['product_id'][$key]);
                $old_weight = 0;
                if (isset($products['id'][$key])) {
                    if (in_array($products['id'][$key], $exists_purchase_return_product->pluck('id')->toArray())) {
                        $purchase_order_return_product_detail = PurchaseOrderReturnDetail::findOrFail($products['id'][$key]);
                        $old_weight = $purchase_order_return_product_detail->quantity;
                    }
                    // else {
                    //     $purchase_order_return_product_detail = new PurchaseOrderReturnDetail();
                    //     $purchase_order_return_product_detail->purchase_order_return_id = $purchase_order_return->id;
                    // }
                } else {
                    $purchase_order_return_product_detail = new PurchaseOrderReturnDetail();
                    $purchase_order_return_product_detail->purchase_order_return_id = $purchase_order_return->id;
                }
                $purchase_order_return_product_detail->purchase_order_return_id = $purchase_order_return->id;
                $purchase_order_return_product_detail->product_id = $product_data->id;
                $purchase_order_return_product_detail->sku_code = $product_data->sku_code;
                $purchase_order_return_product_detail->name = $product_data->name;
                $purchase_order_return_product_detail->per_unit_price = @$products['per_unit_price'][$key];
                $purchase_order_return_product_detail->quantity = @$products['quantity'][$key];
                $purchase_order_return_product_detail->unit_id = @$products['unit_id'][$key];
                $purchase_order_return_product_detail->total = @$products['sub_total'][$key];
                $purchase_order_return_product_detail->status = 1;
                $purchase_order_return_product_detail->save();



                $warehouse_stock_detail = WarehouseStockUpdate::where([['warehouse_id', $from_warehouse_id], ['product_id', $product_data->id], ['status', 1]])->first();

                if ($request_status == '11') {
                    $previous_adding_stock = @$warehouse_stock_detail->adding_stock;
                    $previous_existing_stock = @$warehouse_stock_detail->existing_stock;
                    $previous_total_stock = @$warehouse_stock_detail->total_stock;

                    $given_quantity = $purchase_order_return_product_detail->quantity;

                    $equal_check_adding = $previous_adding_stock ? $previous_adding_stock == $given_quantity : false;
                    $greater_check_adding = $previous_adding_stock ? $previous_adding_stock < $given_quantity: false;

                    if($equal_check_adding) {  // check if previous and current quantity are same
                        Log::info('entered equal condition');
                        $difference_stock = $previous_total_stock - $given_quantity;
                        $adding_stock = - $given_quantity;
                        $existing_stock = $previous_total_stock ?? 0;
                        $total_stock = $difference_stock;
                    } elseif($greater_check_adding) {
                        Log::info('entered greater condition');  // check if  current quantity are greater than previous quantity
                        $difference_stock = $previous_total_stock - $given_quantity;
                        $adding_stock = - $given_quantity;
                        $existing_stock = $previous_total_stock ?? 0;
                        $total_stock = $difference_stock;
                    } else {
                        Log::info('entered else condition');
                        $difference_stock = $previous_total_stock - $given_quantity;
                        $adding_stock = - $given_quantity;
                        $existing_stock = $previous_total_stock ?? 0;
                        $total_stock = $difference_stock;
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
                    $warehouse_stock_detail->stock_verified = $request_status == '11' ? 1 : 0;
                    $warehouse_stock_detail->box_number = $request_box_number != null ? $request_box_number : $warehouse_stock_detail->box_number;
                    $warehouse_stock_detail->save();

                    $warehouse_inventory = WarehouseInventoryDetail::where([['warehouse_id', $from_warehouse_id], ['product_id', $product_data->id], ['status', 1]])->first();
                    if($equal_check_adding) { // check if previous and current quantity are same
                        $inventory_weight = $difference_stock;
                    } elseif($greater_check_adding) { // check if  current quantity are greater than previous quantity
                        $inventory_weight = $difference_stock ;
                    } else {
                        $inventory_weight =  $difference_stock;
                    }

                    if ($warehouse_inventory == null) {
                        $warehouse_inventory = new WarehouseInventoryDetail();
                        $warehouse_inventory->warehouse_id = $from_warehouse_id;
                        $warehouse_inventory->product_id = $product_data->id;
                    }
                    $warehouse_inventory->weight = $inventory_weight;
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

        $pore_details = PurchaseOrderReturnExpense::where('purchase_order_return_id', $id)->get();
        if (count($pore_details) > 0) {
            foreach ($pore_details as $exists_key => $value) {
                if (!in_array($value->id, $request_old_expense_ids)) {
                    PurchaseOrderReturnExpense::where('id', $value->id)->delete();
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
                    if (in_array($expense['expense_id'][$expense_key], $pore_details->pluck('id')->toArray())) {
                        $purchase_order_return_expense = PurchaseOrderReturnExpense::findOrFail($expense['expense_id'][$expense_key]);
                    }
                    // else {
                    //     $purchase_order_return_expense = new PurchaseOrderReturnExpense();
                    // }
                } else {
                    $purchase_order_return_expense = new PurchaseOrderReturnExpense();
                }
                $purchase_order_return_expense->purchase_order_return_id = $purchase_order_return->id;
                $purchase_order_return_expense->income_expense_id = @$expense['expense_type_id'][$expense_key];
                $purchase_order_return_expense->ie_amount = @$expense['expense_amount'][$expense_key];
                // $purchase_order_return_expense->is_billable = @$expense['is_billable'][$expense_key];
                if (@$expense['is_billable'][$expense_key] == 1) {
                    $total_expense_billable_amount += @$expense['expense_amount'][$expense_key];
                }
                $total_expense_amount += @$expense['expense_amount'][$expense_key];
                $purchase_order_return_expense->save();
            }
            $purchase_order_return = PurchaseOrderReturn::findOrFail($purchase_order_return->id);
            $purchase_order_return->total_expense_billable_amount = $total_expense_billable_amount;
            $purchase_order_return->total_expense_amount = $total_expense_amount;
            $purchase_order_return->save();
        }

        $portt_details = TransportTracking::where('purchase_order_return_id', $id)->get();
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
                if ($transport_tracking['transport_type_id'][$track_key]) {
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
                            Log::info('Transport if condition update ');
                        }
                        //  else {
                        //     $transport_trackings = new TransportTracking();
                        //     Log::info('Transport if else condition create');
                        // }
                    } else {
                        $transport_trackings = new TransportTracking();
                        Log::info('Transport else condition create');
                    }
                    $transport_trackings->purchase_order_return_id = $purchase_order_return->id;
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
        DB::commit();

        if ($request->submission_type == 1) {
            return redirect()->route('admin.purchase-return.index')->with('success', 'Purchase Order Return Updated Successfully');
        } elseif ($request->submission_type == 2) {
            return back()->with('success', 'Purchase Order Return Updated Successfully');
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

    public function get_purchase_order(Request $request)
    {
        $response = array();
        if (isset($request['term']['term']) && !empty($request['term']['term'])) {
            $sales_order = PurchaseOrder::where(function ($query) use ($request) {
                $query->where('purchase_order_number', 'LIKE', "%{$request['term']['term']}%");
            })
                ->get();
            $sales_order->each(function ($order) use (&$response) {
                $response[] = array(
                    "id" => $order->id,
                    "text" => $order->purchase_order_number
                );
            });
        }

        return response()->json($response);
    }

    public function purchaseReturnOverView($id)
    {
        $data['purchase_return'] = PurchaseOrderReturn::findOrFail($id);
        return $data;
    }

    public function PurchaseProductReturnData($id)
    {
        $data = $common_data = $this->purchaseReturnOverView($id);
        $data['purchase_orders_return_details'] = PurchaseOrderReturnDetail::where('purchase_order_return_id', $id)->orderBy('id', 'desc')->paginate(10);
        return view('pages.return.purchase_return.purchase_return_product', $data);
    }

    public function PurchaseTransportReturnData($id)
    {
        $data = $common_data = $this->purchaseReturnOverView($id);
        $data['purchase_return_transport_trackings'] = TransportTracking::where('purchase_order_return_id', $id)->orderBy('id', 'desc')->paginate(10);
        return view('pages.return.purchase_return.transport_details', $data);
    }

    public function purchasereturnExpensesData($id)
    {
        $data = $common_data = $this->purchaseReturnOverView($id);
        $data['purchase_return_expenses'] = PurchaseOrderReturnExpense::select('purchase_order_return_expenses.ie_amount', 'income_expense_types.id as type_id', 'income_expense_types.name as type_name')
            ->leftJoin('income_expense_types', 'purchase_order_return_expenses.income_expense_id', '=', 'income_expense_types.id')
            ->where('purchase_order_return_id', $id)
            ->orderBy('purchase_order_return_expenses.id', 'desc')
            ->paginate(10);
        return view('pages.return.purchase_return.expences', $data);
    }

    public function PurchasereturnPaymentData($id)
    {
        // dd($id);
        $data = $common_data = $this->purchaseReturnOverView($id);

        $data['purchase_return_payments'] = PaymentTransaction::select('payment_transactions.*', 'payment_types.id as type_id', 'payment_types.payment_type as type_name')
            ->leftJoin('payment_types', 'payment_transactions.payment_type_id', '=', 'payment_types.id')
            ->where([
                ['reference_id', '=', $id],
                ['transaction_type', '=', 2],
            ])->orderBy('payment_transactions.id', 'desc')
            ->paginate(10);
        return view('pages.return.purchase_return.payment_details', $data);
    }
}
