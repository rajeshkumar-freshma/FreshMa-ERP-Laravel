<?php

namespace App\Http\Controllers\Api\IndentRequest;

use App\Core\CommonComponent;
use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\WarehouseIndentRequest;
use App\Models\WarehouseIndentRequestAction;
use App\Models\WarehouseIndentRequestDetail;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class WarehouseIndentRequestController extends Controller
{
    public function warehouseindentrequestlist(Request $request)
    {
        try {
            $purchase_ordered_count = WarehouseIndentRequest::whereIn('status', config('app.purchase_ordered_status'))->count();
            $purchase_received_count = WarehouseIndentRequest::whereIn('status', config('app.purchase_received_status'))->count();

            if ($request->warehouse_id != null) {
                $warehouse_id = array($request->warehouse_id);
            } else {
                $warehouse_id = Auth::user()->user_warehouse();
            }
            $supplier_id = $request->supplier_id;
            $request_code = $request->request_code;
            $status = $request->status;
            $from_date = $request->from_date;
            $to_date = $request->to_date;

            $purchaselists = WarehouseIndentRequest::where(function ($query) use ($warehouse_id, $supplier_id, $request_code, $from_date, $to_date, $status) {
                if (count($warehouse_id) > 0) {
                    $query->whereIn('warehouse_id', $warehouse_id);
                }
                if ($supplier_id != null) {
                    $query->where('supplier_id', $supplier_id);
                }
                if ($status != null) {
                    $query->where('status', $status);
                }
                if ($request_code != null) {
                    $query->where('request_code', 'LIKE', '%' . $request_code . '%');
                }
                if ($from_date != null && $to_date != null) {
                    $dateformatwithtime = CommonComponent::dateformatwithtime($from_date, $to_date);
                    $query->whereBetween('request_date', $dateformatwithtime);
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
                ->select('id', 'warehouse_id', 'supplier_id', 'request_code', 'status', 'request_date', 'expected_date', 'total_amount', 'total_request_quantity', 'discount_amount', 'created_by')
                ->orderBy('id', 'DESC')
                ->paginate(15);

            return response()->json([
                'status' => 200,
                'purchase_ordered_count' => $purchase_ordered_count,
                'purchase_received_count' => $purchase_received_count,
                'purchaselists' => $purchaselists,
                'message' => 'Vendor/Customer fetched successfully.',
            ]);
        } catch (\Exception $e) {
            Log::error($e);
            return response()->json([
                'status' => 400,
                'message' => 'Data not found.',
            ]);
        }
    }

    public function warehouseindentrequestdetails(Request $request)
    {
        // try {
        $warehouse_indent_id = $request->warehouse_indent_id;
        $purchasedetails = WarehouseIndentRequest::with(['supplier' => function ($query) use ($warehouse_indent_id) {
            $query->select('id', 'first_name', 'last_name', 'user_type', 'status');
        }])
            ->with(['warehouse' => function ($query) {
                $query->select('id', 'name', 'code');
            }])
            ->with(['created_by_details' => function ($query) {
                $query->select('id', 'first_name', 'last_name', 'user_type', 'status');
            }])
            ->findOrFail($warehouse_indent_id);

        $purchaseproductdetails = WarehouseIndentRequestDetail::where('warehouse_ir_id', $warehouse_indent_id)->with('unit_details:id,unit_name,unit_short_code,allow_decimal,operator,operation_value')->get();

        return response()->json([
            'status' => 200,
            'datas' => $purchasedetails,
            'productdetails' => $purchaseproductdetails,
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

    public function warehouseindentrequeststore(Request $request)
    {
        DB::beginTransaction();
        // try {
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
        $indent_request->request_code = $request->request_code;
        $indent_request->request_date = $request->request_date;
        $indent_request->expected_date = $request->expected_date;
        $indent_request->status = 1;
        $indent_request->total_amount = $request->total_amount;
        $indent_request->remarks = $request->remarks;
        if ($imageUrl != null) {
            $indent_request->file = $imageUrl;
            $indent_request->file_path = $imagePath;
        }
        $indent_request->save();

        if (isset($request->products)) {
            Log::info($request->products);
            $products = json_decode($request->products);
            if (count($products) > 0) {
                foreach ($products as $key => $product) {
                    $product_data = Product::findOrfail($product->product_id);
                    $indent_request_detail = new WarehouseIndentRequestDetail();
                    $indent_request_detail->warehouse_ir_id = $indent_request->id;
                    $indent_request_detail->product_id = $product_data->id;
                    $indent_request_detail->sku_code = $product_data->sku_code;
                    $indent_request_detail->name = $product_data->name;
                    $indent_request_detail->unit_id = @$product->unit_id;
                    $indent_request_detail->request_quantity = @$product->quantity;
                    $indent_request_detail->given_quantity = @$product->quantity;
                    $indent_request_detail->amount = @$product->amount;
                    $indent_request_detail->sub_total = @$product->sub_total;
                    $indent_request_detail->remarks = @$product->remarks;
                    $indent_request_detail->added_by_requestor = 1;
                    $indent_request_detail->status = 1;
                    $indent_request_detail->save();
                }
            }
        }

        $request_action = new WarehouseIndentRequestAction();
        $request_action->warehouse_ir_id = $indent_request->id;
        $request_action->status = 1;
        $request_action->action_date = Carbon::now();
        $request_action->remarks = $request->remarks;
        $request_action->save();
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

    public function warehouseindentrequestedit(Request $request)
    {
        // try {
        $warehouse_indent_id = $request->warehouse_indent_id;

        $warehouse_indent_requests = WarehouseIndentRequest::with(['supplier' => function ($query) use ($warehouse_indent_id) {
            $query->select('id', 'first_name', 'last_name', 'user_type', 'status');
        }])
            ->with(['warehouse' => function ($query) {
                $query->select('id', 'name', 'code');
            }])
            ->with(['created_by_details' => function ($query) {
                $query->select('id', 'first_name', 'last_name', 'user_type', 'status');
            }])
            ->findOrFail($request->warehouse_indent_id);

        $warehouse_product_details = WarehouseIndentRequestDetail::where('warehouse_ir_id', $warehouse_indent_id)->get();

        return response()->json([
            'status' => 200,
            'warehouse_indent_requests' => $warehouse_indent_requests,
            'warehouse_product_details' => $warehouse_product_details,
            'message' => 'Warehouse Request fetched successfully.',
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

    public function warehouseindentrequestupdate(Request $request)
    {
        DB::beginTransaction();
        // try {
        $imagePath = null;
        $imageUrl = null;

        if ($request->hasFile('file')) {
            $imageData = commoncomponent::s3BucketFileUpload($request->file, 'warehouse_indent');
            $imagePath = $imageData['filePath'];
            // $imageUrl = $imageData['fileName'];
            $imageUrl = $imageData['imageURL'];
        }

        $warehouse_indent_id = $request->warehouse_indent_id;
        $indent_request = WarehouseIndentRequest::findOrFail($warehouse_indent_id);
        $indent_request->warehouse_id = $request->warehouse_id;
        $indent_request->supplier_id = $request->supplier_id;
        $indent_request->request_date = $request->request_date;
        $indent_request->expected_date = $request->expected_date;
        $indent_request->status = $request->status;
        $indent_request->total_amount = $request->total_amount;
        $indent_request->remarks = $request->remarks;
        if ($imageUrl != null) {
            $indent_request->file = $imageUrl;
            $indent_request->file_path = $imagePath;
        }
        $indent_request->save();

        if (isset($request->deleted_ids) && count(json_decode($request->deleted_ids)) > 0) {
            WarehouseIndentRequestDetail::destroy($request->deleted_ids);
        }

        if (isset($request->products)) {
            Log::info($request->products);
            $products = json_decode($request->products);
            if (count($products) > 0) {
                foreach ($products as $key => $product) {
                    $product_data = Product::findOrfail($product->product_id);
                    if (isset($product->id)) {
                        $indent_request_detail = WarehouseIndentRequestDetail::findOrFail($product->id);
                    } else {
                        $indent_request_detail = new WarehouseIndentRequestDetail();
                    }
                    $indent_request_detail->warehouse_ir_id = $indent_request->id;
                    $indent_request_detail->product_id = $product_data->id;
                    $indent_request_detail->sku_code = $product_data->sku_code;
                    $indent_request_detail->name = $product_data->name;
                    $indent_request_detail->unit_id = @$product->unit_id;
                    $indent_request_detail->request_quantity = @$product->quantity;
                    $indent_request_detail->given_quantity = @$product->quantity;
                    $indent_request_detail->amount = @$product->amount;
                    $indent_request_detail->sub_total = @$product->sub_total;
                    $indent_request_detail->remarks = @$product->remarks;
                    $indent_request_detail->added_by_requestor = 1;
                    $indent_request_detail->status = 1;
                    $indent_request_detail->save();
                }
            }
        }

        $request_action = new WarehouseIndentRequestAction();
        $request_action->warehouse_ir_id = $indent_request->id;
        $request_action->status = $request->status;
        $request_action->action_date = Carbon::now();
        $request_action->remarks = $request->remarks;
        $request_action->save();
        DB::commit();

        return response()->json([
            'status' => 200,
            'datas' => $indent_request,
            'message' => 'Data Updated successfully.',
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
}
