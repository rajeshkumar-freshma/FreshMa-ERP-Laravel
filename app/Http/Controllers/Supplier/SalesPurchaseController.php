<?php

namespace App\Http\Controllers\Supplier;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\DataTables\Supplier\SalesPurchaseDataTable;
use App\Models\PurchaseOrderAction;
use App\Models\TransportType;
use App\Models\Unit;
use App\Models\PurchaseOrder;
use App\Models\TaxRate;
use App\Models\Warehouse;
use App\Models\Store;
use App\Models\Supplier;
use App\Models\WarehouseIndentRequest;
use App\Models\IncomeExpenseType;
use App\Models\Product;
use App\Models\PurchaseOrderDetail;
use App\Models\TransportTracking;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SalesPurchaseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(SalesPurchaseDataTable $dataTable)
    {
        return $dataTable->render('supplier.sale_order.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('supplier.sale_order.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $data['transport_types'] = TransportType::active()->get();
        $data['units'] = Unit::active()->get();
        $data['indent_request'] = PurchaseOrder::findOrfail($id);

        $data['tax_rates'] = TaxRate::active()->get();

        $user_warehouse = Auth::user()->user_warehouse();
        $data['warehouses'] = Warehouse::select('id', 'name', 'code')
            ->whereIn('id', $user_warehouse)
            ->active()
            ->get();
        $data['stores'] = Store::select('id', 'store_name', 'store_code')
            ->active()
            ->get();
        $data['suppliers'] = Supplier::select('id', 'first_name', 'last_name', 'user_code')
            ->active()
            ->get();

        $data['warehouse_indent_requests'] = WarehouseIndentRequest::select('id', 'request_code')
            ->get();

        $data['expense_types'] = IncomeExpenseType::active()->where('type', 2)->get();

        return view('supplier.sale_order.view', $data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data['transport_types'] = TransportType::active()->get();
        $data['units'] = Unit::active()->get();
        $data['indent_request'] = PurchaseOrder::findOrfail($id);

        $data['tax_rates'] = TaxRate::active()->get();

        $user_warehouse = Auth::user()->user_warehouse();
        $data['warehouses'] = Warehouse::select('id', 'name', 'code')
            ->active()
            ->whereIn('id', $user_warehouse)
            ->get();
        $data['stores'] = Store::select('id', 'store_name', 'store_code')
            ->active()
            ->get();
        $data['suppliers'] = Supplier::select('id', 'first_name', 'last_name', 'user_code')
            ->active()
            ->get();

        $data['warehouse_indent_requests'] = WarehouseIndentRequest::select('id', 'request_code')
            ->get();

        return view('supplier.sale_order.edit', $data);
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
        // return $request;
        DB::beginTransaction();
        // try {
        $imagePath = null;
        $imageUrl = null;


        $indent_request = PurchaseOrder::findOrfail($id);

        if ($request->hasFile('file')) {
            $fileDeleted = commoncomponent()->s3BucketFileDelete($indent_request->file, $indent_request->file_path);

            $imageData = commoncomponent()->s3BucketFileUpload($request->file, 'purchase_order');
            $imagePath = $imageData['filePath'];
            // $imageUrl = $imageData['fileName'];
            $imageUrl = $imageData['imageURL'];
        }

        // $warehouse_request = WarehouseIndentRequest::findOrFail($request->warehouse_ir_id);
        // $indent_request->warehouse_id = $warehouse_request->warehouse_id;
        // $indent_request->supplier_id = $warehouse_request->supplier_id;
        // $indent_request->warehouse_ir_id = $warehouse_request->id;
        // $indent_request->delivery_date = $request->delivery_date;
        // $indent_request->no_of_days_can_be_use = $request->no_of_days_can_be_use;

        $indent_request->status = $request->status;
        $indent_request->total_request_quantity = $request->total_request_quantity;
        $indent_request->total_tax = $request->total_tax;
        $indent_request->discount_amount = $request->discount_amount;
        $indent_request->sub_total = $request->sub_total;
        $indent_request->total = $request->total;
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

        $request_old_ids = [];
        foreach ($request->products['product_id'] as $store_key => $value) {
            if ($request->products['id'][$store_key] != null) {
                $request_old_ids[] = $request->products['id'][$store_key];
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

        $products = $request->products;
        if (count($products) > 0) {
            foreach ($products['product_id'] as $key => $product) {
                $product_data = Product::findOrfail($products['product_id'][$key]);
                if (isset($products['id'][$key])) {
                    if (in_array($products['id'][$key], $exists_indent_product->pluck('id')->toArray())) {
                        $indent_request_detail = PurchaseOrderDetail::findOrFail($products['id'][$key]);
                    }
                    // else {
                    //     $indent_request_detail = new PurchaseOrderDetail();
                    //     $indent_request_detail->purchase_order_id = $indent_request->id;
                    //     $indent_request_detail->added_by_requestor = 2;
                    // }
                } else {
                    $indent_request_detail = new PurchaseOrderDetail();
                    $indent_request_detail->purchase_order_id = $indent_request->id;
                    $indent_request_detail->added_by_requestor = 2;
                }
                $indent_request_detail->product_id = $product_data->id;
                $indent_request_detail->sku_code = $product_data->sku_code;
                $indent_request_detail->name = $product_data->name;
                $indent_request_detail->unit_id = @$products['unit_id'][$key];
                $indent_request_detail->is_inc_exp_billable = @$products['is_inc_exp_billable'][$key];
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
                $indent_request_detail->remarks = @$products['remarks'][$key];
                $indent_request_detail->save();
            }
        }

        $request_action = new PurchaseOrderAction();
        $request_action->purchase_order_id = $indent_request->id;
        $request_action->status = $request->status;
        $request_action->action_date = Carbon::now();
        $request_action->remarks = $request->remarks;
        $request_action->save();


        if ($request->transport_type_id != null) {
            $imagePath = null;
            $imageUrl = null;
            if ($request->hasFile('transport_tracking_file')) {
                $imageData = commoncomponent()->s3BucketFileUpload($request->transport_tracking_file, 'purchase_order_transport');
                $imagePath = $imageData['filePath'];
                // $imageUrl = $imageData['fileName'];
                $imageUrl = $imageData['imageURL'];
            }
            if ($request->transport_tracking_id != null) {
                $transport_tracking = TransportTracking::findOrfail($request->transport_tracking_id);
            } else {
                $transport_tracking = new TransportTracking();
                $transport_tracking->warehouse_ir_id = $indent_request->id;
            }
            $transport_tracking->transport_type_id = $request->transport_type_id;
            $transport_tracking->transport_name = $request->transport_name;
            $transport_tracking->transport_number = $request->transport_number;
            $transport_tracking->departure_datetime = $request->departure_datetime;
            $transport_tracking->arriving_datetime = $request->arriving_datetime;
            $transport_tracking->from_location = $request->from_location;
            $transport_tracking->to_location = $request->to_location;
            $transport_tracking->phone_number = $request->phone_number;
            if ($imageUrl != null) {
                $transport_tracking->file = $imageUrl;
                $transport_tracking->file_path = $imagePath;
            }
            $transport_tracking->save();
        }

        DB::commit();

        if ($request->submission_type == 1) {
            return redirect()->route('order-request.index')->with('success', 'Order Request Updated Successfully');
        } elseif ($request->submission_type == 2) {
            return back()->with('success', 'Order Request Updated Successfully');
        }
        // } catch (\Exception $e) {
        //     Log::error($e);
        //     DB::rollback();
        //     return back()->withInput()->with('error', 'Order Request Updated Fail');
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
}
