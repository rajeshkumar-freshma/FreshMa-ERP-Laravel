<?php

namespace App\Http\Controllers\Admin\IndentRequest;

use App\Core\CommonComponent;
use App\DataTables\IndentRequest\WarehouseIndentRequestDataTable;
use App\Http\Controllers\Controller;
use App\Http\Requests\IndentRequest\WarehouseIndentFormRequest;
use App\Models\Product;
use App\Models\Store;
use App\Models\StoreIndentRequest;
use App\Models\StoreIndentRequestAction;
use App\Models\StoreIndentRequestDetail;
use App\Models\Supplier;
use App\Models\TaxRate;
use App\Models\TransportType;
use App\Models\Unit;
use App\Models\Vendor;
use App\Models\VendorIndentRequest;
use App\Models\VendorIndentRequestAction;
use App\Models\VendorIndentRequestDetail;
use App\Models\Warehouse;
use App\Models\WarehouseIndentRequest;
use App\Models\WarehouseIndentRequestAction;
use App\Models\WarehouseInventoryDetail;
use App\Models\WarehouseStockUpdate;
use App\Models\WarehouseIndentRequestDetail;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class WarehouseIndentRequestController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(WarehouseIndentRequestDataTable $dataTable)
    {
        return $dataTable->render('pages.indent_request.warehouse.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $data = [];
        $data['storeindentDatas'] = [];
        $data['vendorindentDatas'] = [];
        $data['overall_requests'] = [];

        if (count($request->all()) > 0) {
            $filter_warehouse_id = $request->filter_warehouse_id;
            $filter_store_id = $request->filter_store_id;
            $filter_vendor_id = $request->filter_vendor_id;
            $filter_status = $request->filter_status;
            $from_date = $request->from_date;
            $to_date = $request->to_date;
            $filter_store_indent_request_id = isset($request->filter_store_indent_request_id) ? $request->filter_store_indent_request_id : [];
            $filter_vendor_indent_request_id = isset($request->filter_vendor_indent_request_id) ? $request->filter_vendor_indent_request_id : [];

            $filtered_datas = $this->overall_data_filter($filter_warehouse_id, $filter_store_id, $filter_vendor_id, $filter_status, $from_date, $to_date, $filter_store_indent_request_id, $filter_vendor_indent_request_id);

            $data['storeindentDatas'] = $filtered_datas['storeindentrequestdata'];
            $data['vendorindentDatas'] = $filtered_datas['vendorindentrequestdata'];
            $data['overall_requests'] = $filtered_datas['overalldata'];
        }

        $data['vendors'] = Vendor::select('id', 'first_name', 'last_name', 'user_code')
            ->active()
            ->get();
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

        $data['store_indent_requests'] = StoreIndentRequest::select('id', 'store_id', 'request_code')->whereNotIn('status', [4, 5, 6])->get();
        $data['vendor_indent_requests'] = VendorIndentRequest::select('id', 'vendor_id', 'request_code')->whereNotIn('status', [4, 5, 6])->get();
        $data['transport_types'] = TransportType::where('status', 1)->get();
        return view('pages.indent_request.warehouse.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(WarehouseIndentFormRequest $request)
    {
        DB::beginTransaction();
        try {
            // return $request->all();
            $imagePath = null;
            $imageUrl = null;

            if ($request->hasFile('file')) {
                $imageData = CommonComponent::s3BucketFileUpload($request->file, 'warehouse_indent');
                $imagePath = $imageData['filePath'];
                // $imageUrl = $imageData['fileName'];
                $imageUrl = $imageData['imageURL'];
            }

            $indent_request = new WarehouseIndentRequest();
            $indent_request->warehouse_id = $request->warehouse_id;
            $indent_request->supplier_id = $request->supplier_id;
            $indent_request->request_code = $request->wir_code;
            $indent_request->request_date = $request->request_date;
            $indent_request->expected_date = $request->expected_date;
            $indent_request->status = $request->status;
            $indent_request->total_request_quantity = $request->total_request_quantity;
            $indent_request->total_amount = $request->total_amount;
            $indent_request->remarks = $request->remarks;
            if ($imageUrl != null) {
                $indent_request->file = $imageUrl;
                $indent_request->file_path = $imagePath;
            }
            $indent_request->save();

            $products = $request->products;
            if (!is_null($products) && is_array($products) && count($products) > 0) {
                foreach ($products['product_id'] as $key => $product) {
                    $product_data = Product::findOrfail($products['product_id'][$key]);
                    $indent_request_detail = new WarehouseIndentRequestDetail();
                    $indent_request_detail->warehouse_ir_id = $indent_request->id;
                    $indent_request_detail->product_id = $product_data->id;
                    $indent_request_detail->sku_code = $product_data->sku_code;
                    $indent_request_detail->name = $product_data->name;
                    $indent_request_detail->unit_id = @$products['unit_id'][$key];
                    $indent_request_detail->request_quantity = @$products['quantity'][$key];
                    $indent_request_detail->amount = @$products['amount'][$key];
                    $indent_request_detail->sub_total = @$products['sub_total'][$key];
                    $indent_request_detail->remarks = @$products['remarks'][$key];
                    $indent_request_detail->added_by_requestor = 1;
                    $indent_request_detail->save();

                   if ($request->status == 10) {
                    $quantity = @$products['quantity'][$key];
                    $warehouse_stock_detail_exists = WarehouseStockUpdate::where([['warehouse_id', $indent_request->warehouse_id], ['product_id', $product_data->id], ['status', 1]])->orderBy('id', 'DESC')->first();
                    $warehouse_stock_detail = new WarehouseStockUpdate();
                    $warehouse_stock_detail->warehouse_id = $indent_request->warehouse_id;
                    $warehouse_stock_detail->product_id = $product_data->id;
                    $warehouse_stock_detail->stock_update_on = Carbon::now();
                    $warehouse_stock_detail->existing_stock = $warehouse_stock_detail_exists ? $warehouse_stock_detail_exists->total_stock : 0;
                    $warehouse_stock_detail->adding_stock = 0;
                    $warehouse_stock_detail->total_stock = ($warehouse_stock_detail_exists != null && $warehouse_stock_detail_exists->total_stock != null) ? $warehouse_stock_detail_exists->total_stock + $quantity : $quantity;
                    $warehouse_stock_detail->status = 1;
                    $warehouse_stock_detail->box_number = 1;
                    // return $warehouse_stock_detail;
                    $warehouse_stock_detail->save();

                    $warehouse_inventory = WarehouseInventoryDetail::where([['warehouse_id', $indent_request->warehouse_id], ['product_id', $product_data->id], ['status', 1]])->first();
                    if ($warehouse_inventory == null) {
                        $warehouse_inventory = new WarehouseInventoryDetail();
                        $warehouse_inventory->warehouse_id = $indent_request->warehouse_id;
                        $warehouse_inventory->product_id = $product_data->id;
                    }
                    $warehouse_inventory->weight = @$warehouse_inventory->weight + $quantity;
                    $warehouse_inventory->status = 1;
                    // return $warehouse_inventory;
                    $warehouse_inventory->save();

                    }
                }
            }

            $request_action = new WarehouseIndentRequestAction();
            $request_action->warehouse_ir_id = $indent_request->id;
            $request_action->status = $request->status;
            $request_action->action_date = Carbon::now();
            $request_action->remarks = $request->remarks;
            $request_action->save();

            // if($request->transport_type_id!=null) {
            //     $imagePath = null;
            //     $imageUrl = null;
            //     if ($request->hasFile('transport_tracking_file')) {
            //         $imageData = CommonComponent::s3BucketFileUpload($request->transport_tracking_file, 'warehouse_indent_transport');
            //         $imagePath = $imageData['filePath'];
            //         $imageUrl = $imageData['imageURL'];
            //     }

            //     $transport_tracking = new TransportTracking();
            //     $transport_tracking->warehouse_ir_id = $indent_request->id;
            //     $transport_tracking->transport_type_id = $request->transport_type_id;
            //     $transport_tracking->transport_name = $request->transport_name;
            //     $transport_tracking->transport_number = $request->transport_number;
            //     $transport_tracking->departure_datetime = $request->departure_datetime;
            //     $transport_tracking->arriving_datetime = $request->arriving_datetime;
            //     if ($imageUrl != null) {
            //         $transport_tracking->file = $imageUrl;
            //         $transport_tracking->file_path = $imagePath;
            //     }
            //     $transport_tracking->save();
            // }

            DB::commit();

            if ($request->submission_type == 1) {
                return redirect()->route('admin.warehouse-indent-request.index')->with('success', 'Warehouse Indent Request Stored Successfully');
            } elseif ($request->submission_type == 2) {
                return back()->with('success', 'Warehouse Indent Request Stored Successfully');
            }
        } catch (\Exception $e) {
            Log::error($e);
            DB::rollback();
            return back()->withInput()->with('error', 'Warehouse Indent Request Stored Fail');
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
        $data = [];
        $data['storeindentDatas'] = [];
        $data['vendorindentDatas'] = [];
        $data['overall_requests'] = [];

        $data['vendors'] = Vendor::select('id', 'first_name', 'last_name', 'user_code')
            ->active()
            ->get();
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

        $data['store_indent_requests'] = StoreIndentRequest::select('id', 'store_id', 'request_code')->whereNotIn('status', [4, 5, 6])->get();
        $data['vendor_indent_requests'] = VendorIndentRequest::select('id', 'vendor_id', 'request_code')->whereNotIn('status', [4, 5, 6])->get();
        $data['transport_types'] = TransportType::where('status', 1)->get();

        $data['warehouse_indent_request'] = WarehouseIndentRequest::findOrfail($id);
        return view('pages.indent_request.warehouse.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(WarehouseIndentFormRequest $request, $id)
    {
        DB::beginTransaction();
        // try {
        $imagePath = null;
        $imageUrl = null;

        $indent_request = WarehouseIndentRequest::findOrfail($id);
        if ($request->hasFile('file')) {
            $fileDeleted = CommonComponent::s3BucketFileDelete($indent_request->file, $indent_request->file_path);

            $imageData = CommonComponent::s3BucketFileUpload($request->file, 'warehouse_indent');
            $imagePath = $imageData['filePath'];
            // $imageUrl = $imageData['fileName'];
            $imageUrl = $imageData['imageURL'];
        }

        $indent_request->warehouse_id = $request->warehouse_id;
        $indent_request->supplier_id = $request->supplier_id;
        $indent_request->request_code = $request->wir_code;
        $indent_request->request_date = $request->request_date;
        $indent_request->expected_date = $request->expected_date;
        $indent_request->status = $request->status;
        $indent_request->total_request_quantity = $request->total_request_quantity;
        $indent_request->total_amount = $request->total_amount;
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

        $exists_indent_product = WarehouseIndentRequestDetail::where('warehouse_ir_id', $id)->get();
        if (count($exists_indent_product) > 0) {
            foreach ($exists_indent_product as $exists_key => $value) {
                if (!in_array($value->id, $request_old_ids)) {
                    WarehouseIndentRequestDetail::where('id', $value->id)->delete();
                }
            }
        }

        $products = $request->products;
        if (!is_null($products) && is_array($products) && count($products) > 0) {
            foreach ($products['product_id'] as $key => $product) {
                $product_data = Product::findOrfail($products['product_id'][$key]);
                if (isset($products['id'][$key])) {
                    if (in_array($products['id'][$key], $exists_indent_product->pluck('id')->toArray())) {
                        $indent_request_detail = WarehouseIndentRequestDetail::findOrFail($products['id'][$key]);
                    }
                    // else {
                    //     $indent_request_detail = new WarehouseIndentRequestDetail();
                    //     $indent_request_detail->warehouse_ir_id = $indent_request->id;
                    // }
                } else {
                    $indent_request_detail = new WarehouseIndentRequestDetail();
                    $indent_request_detail->warehouse_ir_id = $indent_request->id;
                }
                $indent_request_detail->product_id = $product_data->id;
                $indent_request_detail->sku_code = $product_data->sku_code;
                $indent_request_detail->name = $product_data->name;
                $indent_request_detail->unit_id = @$products['unit_id'][$key];
                $indent_request_detail->request_quantity = @$products['quantity'][$key];
                $indent_request_detail->amount = @$products['amount'][$key];
                $indent_request_detail->sub_total = @$products['sub_total'][$key];
                $indent_request_detail->remarks = @$products['remarks'][$key];
                $indent_request_detail->added_by_requestor = 1;
                $indent_request_detail->save();
            }
        }

        $request_action = new WarehouseIndentRequestAction();
        $request_action->warehouse_ir_id = $indent_request->id;
        $request_action->status = $request->status;
        $request_action->action_date = Carbon::now();
        $request_action->remarks = $request->remarks;
        $request_action->save();

        // if($request->transport_type_id!=null) {
        //     $imagePath = null;
        //     $imageUrl = null;
        //     if ($request->hasFile('transport_tracking_file')) {
        //         $imageData = CommonComponent::s3BucketFileUpload($request->transport_tracking_file, 'warehouse_indent_transport');
        //         $imagePath = $imageData['filePath'];
        //         // $imageUrl = $imageData['fileName'];
        //         $imageUrl = $imageData['imageURL'];
        //     }
        //     if($request->transport_tracking_id!=null) {
        //         $transport_tracking = TransportTracking::findOrfail($request->transport_tracking_id);
        //     } else {
        //         $transport_tracking = new TransportTracking();
        //         $transport_tracking->warehouse_ir_id = $indent_request->id;
        //     }
        //     $transport_tracking->transport_type_id = $request->transport_type_id;
        //     $transport_tracking->transport_name = $request->transport_name;
        //     $transport_tracking->transport_number = $request->transport_number;
        //     $transport_tracking->departure_datetime = $request->departure_datetime;
        //     $transport_tracking->arriving_datetime = $request->arriving_datetime;
        //     if ($imageUrl != null) {
        //         $transport_tracking->file = $imageUrl;
        //         $transport_tracking->file_path = $imagePath;
        //     }
        //     $transport_tracking->save();
        // }

        DB::commit();

        if ($request->submission_type == 1) {
            return redirect()->route('admin.warehouse-indent-request.index')->with('success', 'Warehouse Indent Request Updated Successfully');
        } elseif ($request->submission_type == 2) {
            return back()->with('success', 'Warehouse Indent Request Updated Successfully');
        }
        // } catch (\Exception $e) {
        //     Log::error($e);
        //     DB::rollback();
        //     return back()->withInput()->with('error', 'Warehouse Indent Request Updated Fail');
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
        try {
            WarehouseIndentRequestDetail::where('warehouse_ir_id', $id)->delete();
            $indent_request = WarehouseIndentRequest::findOrFail($id);

            $indent_request->delete();
            return response()->json([
                'status' => 200,
                'message' => 'Indent request Deleted Successfully.',
            ]);
        } catch (\Exception $e) {
            Log::error($e);
            return response()->json([
                'status' => 400,
                'message' => 'Sorry, Something went Wrong',
            ]);
        }
    }

    public function indent_request_status_change(Request $request)
    {
        $dataupdate = false;
        if ($request->type == 'store_indent') {
            StoreIndentRequestDetail::where('id', $request->id)->update([
                'status' => $request->status,
            ]);
            $dataupdate = true;

            $request_action = new StoreIndentRequestAction();
            $request_action->store_indent_request_id = $request->id;
            $request_action->status = $request->status;
            $request_action->action_date = Carbon::now();
            $request_action->remarks = $request->remarks;
            $request_action->save();
        } elseif ($request->type == 'vendor_indent') {
            VendorIndentRequestDetail::where('id', $request->id)->update([
                'status' => $request->status,
            ]);
            $dataupdate = true;

            $request_action = new VendorIndentRequestAction();
            $request_action->vendor_indent_request_id = $request->id;
            $request_action->status = $request->status;
            $request_action->action_date = Carbon::now();
            $request_action->remarks = $request->remarks;
            $request_action->save();
        }

        if ($dataupdate == true) {
            $status = view('pages.partials.statuslabel', ['indent_status' => $request->status])->render();
        }

        $filter_warehouse_id = $request->filter_warehouse_id;
        $filter_store_id = $request->filter_store_id;
        $filter_vendor_id = $request->filter_vendor_id;
        $filter_status = $request->filter_status;
        $from_date = $request->from_date;
        $to_date = $request->to_date;
        $filter_store_indent_request_id = $request->filter_store_indent_request_id;
        $filter_vendor_indent_request_id = $request->filter_vendor_indent_request_id;

        $overall_requests = $this->overall_data_filter($filter_warehouse_id, $filter_store_id, $filter_vendor_id, $filter_status, $from_date, $to_date, $filter_store_indent_request_id, $filter_vendor_indent_request_id);

        $filtered_data = view('pages.indent_request.warehouse.overall_request', ['overall_requests' => $overall_requests])->render();

        return response()->json(['status' => 200, 'message' => 'Status Updated successfully', 'update_status' => $status, 'overall_data' => $filtered_data]);
    }

    public function overall_data_filter($filter_warehouse_id, $filter_store_id, $filter_vendor_id, $filter_status, $from_date, $to_date, $filter_store_indent_request_id, $filter_vendor_indent_request_id)
    {
        $storeoverall = StoreIndentRequestDetail::with('store_indent_request')
            ->whereHas('store_indent_request', function ($query) use ($filter_store_indent_request_id, $filter_warehouse_id, $filter_store_id, $filter_status, $from_date, $to_date) {
                if ($filter_warehouse_id != null) {
                    $query->where('warehouse_id', $filter_warehouse_id);
                }
                if ($filter_store_id != null) {
                    $query->where('store_id', $filter_store_id);
                }
                if ($filter_status != null) {
                    $query->where('status', $filter_status);
                }
                if ($from_date != null && $from_date != null) {
                    $query->whereBetween('expected_date', [$from_date, $to_date]);
                }
                if (count($filter_store_indent_request_id) > 0) {
                    $query->whereIn('id', $filter_store_indent_request_id);
                }
            })
            ->select('id', 'product_id', 'unit_id', 'request_quantity', 'status', 'store_indent_request_id');

        $storeoverallquery = $storeoverall;

        $vendoroverall = VendorIndentRequestDetail::whereHas('vendor_indent_request', function ($query) use ($filter_vendor_indent_request_id, $filter_vendor_id, $filter_status, $from_date, $to_date) {
            // if ($filter_vendor_id != null) {
            //     $query->where('vendor_indent_requests.vendor_id ', $filter_vendor_id);
            // }
            if ($filter_status != null) {
                $query->where('status', $filter_status);
            }
            if ($from_date != null && $from_date != null) {
                $query->whereBetween('expected_date', [$from_date, $to_date]);
            }
            if (count($filter_vendor_indent_request_id) > 0) {
                $query->whereIn('id', $filter_vendor_indent_request_id);
            }
        })
            ->with('vendor_indent_request')
            ->select('id', 'product_id', 'unit_id', 'request_quantity', 'status', 'vendor_indent_request_id');

        $vendoroverallquery = $vendoroverall;

        $data['storeindentrequestdata'] = $storeoverallquery->get()
            ->map(function ($data) {
                $data['store_name'] = $data->store_indent_request->store_data->store_name;
                return $data;
            })
            ->groupBy('store_name');

        $data['vendorindentrequestdata'] = $vendoroverallquery
            ->get()
            ->map(function ($data) {
                $data['vendor_name'] = $data->vendor_indent_request->vendor_data->first_name . $data->vendor_indent_request->vendor_data->last_name;
                return $data;
            })
            ->groupBy('vendor_name');

        $combinedQuery = $storeoverall->unionAll($vendoroverall);

        $data['overalldata'] = DB::table(DB::raw("({$combinedQuery->toSql()}) AS subQuery"))
            ->mergeBindings($combinedQuery->getQuery())
            ->join('products', function ($query) {
                $query->on('products.id', 'subQuery.product_id');
            })
            ->join('units', function ($query) {
                $query->on('units.id', 'subQuery.unit_id');
            })
            ->select('subQuery.id', 'subQuery.product_id', 'subQuery.unit_id', 'request_quantity', 'subQuery.status', 'products.id as products_id', 'products.name', 'units.id as units_id', 'units.unit_name', 'units.unit_short_code')
            ->selectRaw(DB::raw('COUNT(product_id) as row_count'))
            ->selectRaw(DB::raw('(SUM(CASE WHEN subQuery.status NOT IN (4,6) THEN (request_quantity) ELSE 0 END)) as quantity'))
            ->groupBy('subQuery.product_id')
            ->groupBy('subQuery.unit_id')
            ->get();

        return $data;
    }
}
