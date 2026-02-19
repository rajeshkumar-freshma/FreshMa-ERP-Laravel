<?php

namespace App\Http\Controllers\Api\supplier\IndentRequest;

use App\Core\CommonComponent;
use App\Http\Controllers\Controller;
use App\Models\PaymentTransaction;
use App\Models\Product;
use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderAction;
use App\Models\PurchaseOrderDetail;
use App\Models\WarehouseIndentRequest;
use App\Models\WarehouseIndentRequestAction;
use App\Models\WarehouseIndentRequestDetail;
use App\Models\WarehouseInventoryDetail;
use App\Models\WarehouseStockUpdate;
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
            $supplier_id = Auth::user()->id;
            $request_code = $request->request_code;
            $date = $request->date;
            $status = $request->status;
            $purchaselists = WarehouseIndentRequest::where(function ($query) use ($warehouse_id, $supplier_id, $request_code, $date, $status) {
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
                if ($date != null) {
                    $query->whereDate('request_date', $date);
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

    public function warehouseindentrequestupdate(Request $request)
    {
        DB::beginTransaction();
        // try {
        $warehouse_indent_id = $request->warehouse_indent_id;
        $warehouse_indent_request = WarehouseIndentRequest::findOrfail($warehouse_indent_id);
        $warehouse_indent_request->warehouse_id = $request->warehouse_id;
        $warehouse_indent_request->supplier_id = $request->supplier_id;
        $warehouse_indent_request->request_date = $request->request_date;
        $warehouse_indent_request->expected_date = $request->expected_date;
        $warehouse_indent_request->status = $request->status;
        $warehouse_indent_request->total_amount = $request->total_amount;
        $warehouse_indent_request->remarks = $request->remarks;
        $warehouse_indent_request->save();

        if ($request->status == 7) {
            $data = PurchaseOrder::where('warehouse_ir_id', $warehouse_indent_id)
                ->orderBy('id', 'DESC')
                ->first();
            if ($data != null) {
                $indent_request = PurchaseOrder::findOrfail($data->id);
            } else {
                $indent_request = new PurchaseOrder();
                $purchase_order_number = CommonComponent::invoice_no('purchase_order');
                $indent_request->purchase_order_number = $purchase_order_number;
            }

            $imagePath = NUll;
            $imageUrl = NUll;
            if ($request->hasFile('file')) {
                $fileDeleted = CommonComponent::s3BucketFileDelete($indent_request->file, $indent_request->file_path);

                $imageData = CommonComponent::s3BucketFileUpload($request->file, 'purchase_order');
                $imagePath = $imageData['filePath'];
                // $imageUrl = $imageData['fileName'];
                $imageUrl = $imageData['imageURL'];
            }

            $indent_request->warehouse_id = $warehouse_indent_request->warehouse_id;
            $indent_request->supplier_id = $warehouse_indent_request->supplier_id;
            $indent_request->warehouse_ir_id = $warehouse_indent_request->id;
            $indent_request->delivery_date = $warehouse_indent_request->expected_date;
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

            if (isset($request->deleted_ids) && count(json_decode($request->deleted_ids)) > 0) {
                WarehouseIndentRequestDetail::whereIn('id', json_decode($request->deleted_ids))->update([
                    'status' => 0
                ]);
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
                        $indent_request_detail->given_quantity = @$product->quantity;
                        $indent_request_detail->per_unit_price = @$product->amount;
                        $indent_request_detail->amount = @$product->sub_total;
                        $indent_request_detail->sub_total = @$product->sub_total;
                        $indent_request_detail->save();

                        if (isset($product->id)) {
                            $indent_request_detail = WarehouseIndentRequestDetail::findOrFail($product->id);
                        } else {
                            $indent_request_detail = new WarehouseIndentRequestDetail();
                            $indent_request_detail->added_by_requestor = 1;
                            $indent_request_detail->warehouse_ir_id = $warehouse_indent_request->id;
                        }
                        $indent_request_detail->product_id = $product_data->id;
                        $indent_request_detail->sku_code = $product_data->sku_code;
                        $indent_request_detail->name = $product_data->name;
                        $indent_request_detail->unit_id = @$product->unit_id;
                        $indent_request_detail->given_quantity = @$product->quantity;
                        $indent_request_detail->amount = @$product->amount;
                        $indent_request_detail->sub_total = @$product->sub_total;
                        $indent_request_detail->remarks = @$product->remarks;
                        $indent_request_detail->save();
                    }
                }
            }

            $request_action = new PurchaseOrderAction();
            $request_action->purchase_order_id = $indent_request->id;
            $request_action->status = $request->status;
            $request_action->action_date = Carbon::now();
            $request_action->remarks = "Purchase Order Adding " . $request->remarks;
            $request_action->save();
        } else {
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
                        $indent_request_detail->warehouse_ir_id = $warehouse_indent_request->id;
                        $indent_request_detail->product_id = $product_data->id;
                        $indent_request_detail->sku_code = $product_data->sku_code;
                        $indent_request_detail->name = $product_data->name;
                        $indent_request_detail->unit_id = @$product->unit_id;
                        $indent_request_detail->request_quantity = @$product->quantity;
                        $indent_request_detail->amount = @$product->amount;
                        $indent_request_detail->sub_total = @$product->sub_total;
                        $indent_request_detail->remarks = @$product->remarks;
                        $indent_request_detail->added_by_requestor = 1;
                        $indent_request_detail->save();
                    }
                }
            }

            $request_action = new WarehouseIndentRequestAction();
            $request_action->warehouse_ir_id = $warehouse_indent_request->id;
            $request_action->status = $request->status;
            $request_action->action_date = Carbon::now();
            $request_action->remarks = $request->remarks;
            $request_action->save();
        }

        DB::commit();
        return response()->json([
            'status' => 200,
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
