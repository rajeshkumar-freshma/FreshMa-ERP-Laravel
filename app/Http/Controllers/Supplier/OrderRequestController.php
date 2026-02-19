<?php

namespace App\Http\Controllers\Supplier;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\DataTables\IndentRequest\OrderRequestDataTable;
use App\Models\Unit;
use App\Models\Product;
use App\Models\WarehouseIndentRequest;
use App\Models\WarehouseIndentRequestDetail;
use App\Models\TransportType;
use App\Models\TransportTracking;
use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderDetail;
use App\Models\PurchaseOrderAction;
use App\Models\TaxRate;
use App\Models\Vendor;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Log;

class OrderRequestController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(OrderRequestDataTable $dataTable)
    {
        return $dataTable->render('supplier.order_request.index');
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
        $data['units'] = Unit::active()->get();
        $data['tax_rates'] = TaxRate::active()->get();
        return view('supplier.order_request.create', $data);
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
        //
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
        $data['indent_request'] = WarehouseIndentRequest::findOrfail($id);
        return view('supplier.order_request.edit', $data);
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

        $warehouse_request = WarehouseIndentRequest::findOrfail($id);

        $data = PurchaseOrder::where('warehouse_ir_id', $id)
            ->orderBy('id', 'DESC')
            ->first();
        if ($data != null) {
            $indent_request = PurchaseOrder::findOrfail($data->id);
        } else {
            $indent_request = new PurchaseOrder();
            $indent_request->purchase_order_number = date('YmdHis');
        }

        if ($request->hasFile('file')) {
            $fileDeleted = commoncomponent()->s3BucketFileDelete($indent_request->file, $indent_request->file_path);

            $imageData = commoncomponent()->s3BucketFileUpload($request->file, 'purchase_order');
            $imagePath = $imageData['filePath'];
            // $imageUrl = $imageData['fileName'];
            $imageUrl = $imageData['imageURL'];
        }

        $indent_request->warehouse_id = $warehouse_request->warehouse_id;
        $indent_request->supplier_id = $warehouse_request->supplier_id;
        $indent_request->warehouse_ir_id = $warehouse_request->id;
        $indent_request->delivery_date = $request->delivery_date;
        // $indent_request->no_of_days_can_be_use = $request->no_of_days_can_be_use;

        $indent_request->status = $request->status;
        $indent_request->total_request_quantity = $request->total_request_quantity;
        $indent_request->total = $request->total_amount;
        $indent_request->sub_total = $request->total_amount;
        $indent_request->remarks = $request->remarks;
        if ($imageUrl != null) {
            $indent_request->file = $imageUrl;
            $indent_request->file_path = $imagePath;
        }
        $indent_request->save();

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
                $indent_request_detail->request_quantity = @$products['quantity'][$key];
                $indent_request_detail->given_quantity = @$products['given_quantity'][$key];
                $indent_request_detail->amount = @$products['amount'][$key];
                $indent_request_detail->sub_total = @$products['sub_total'][$key];
                $indent_request_detail->save();
            }
        }

        $request_action = new PurchaseOrderAction();
        $request_action->purchase_order_id = $indent_request->id;
        $request_action->status = $request->status;
        $request_action->action_date = Carbon::now();
        $request_action->remarks = $request->remarks;
        $request_action->save();

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
                        $imageData = commoncomponent()->s3BucketFileUpload($transport_tracking['transport_tracking_file'][$track_key], 'purchase_order_transport');
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

        DB::commit();

        if ($request->submission_type == 1) {
            return redirect()
                ->route('order-request.index')
                ->with('success', 'Order Request Updated Successfully');
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
}
