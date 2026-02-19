<?php

namespace App\Http\Controllers\Admin\Product;

use App\Core\CommonComponent;
use App\DataTables\Product\BulkProductTransferDataTable;
use App\Http\Controllers\Controller;
use App\Models\IncomeExpenseType;
use App\Models\Product;
use App\Models\ProductBulkTransfer;
use App\Models\ProductBulkTransferDetail;
use App\Models\ProductBulkTransferHistory;
use App\Models\ProductTransfer;
use App\Models\ProductTransferDetail;
use App\Models\Store;
use App\Models\StoreInventoryDetail;
use App\Models\StoreStockUpdate;
use App\Models\TransportType;
use App\Models\Unit;
use App\Models\Warehouse;
use App\Models\WarehouseInventoryDetail;
use App\Models\WarehouseStockUpdate;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class BulkProductTransferController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(BulkProductTransferDataTable $dataTable)
    {
        // return DB::table('warehouses')->get();
        return $dataTable->render('pages.product.bulk_product_transfer.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $data['warehouses'] = DB::table('warehouses')->get();
        $data['products'] = Product::get();
        $data['stores'] = Store::get();
        $data['transport_types'] = TransportType::active()->get();
        $data['expense_types'] = IncomeExpenseType::active()->get();
        $data['units'] = Unit::active()->get();
        return view('pages.product.bulk_product_transfer.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        DB::beginTransaction();
        // try {
        $converted_to_distribution = 0;
        if ($request->submission_type == 'distribution_submit' && $request->status == "10") {
            $converted_to_distribution = 1;
            $storequantity  = $request->transfer_product['quantity'];

            foreach ($storequantity as $store_id => $products) {
                $insertMainTable = false;
                foreach ($products as $product_id => $product_quantity) {
                    if ($product_quantity != null) {
                        if ($insertMainTable == false) {
                            $product_transfer = new ProductTransfer();
                             $transfer_order_number = CommonComponent::invoice_no('redistribution');
                            $product_transfer->transfer_order_number = $transfer_order_number;
                            $product_transfer->transfer_from = 1;
                            $product_transfer->transfer_to = 2;
                            $product_transfer->from_warehouse_id = $request->from_warehouse_id;
                            $product_transfer->to_store_id = $store_id;
                            $product_transfer->tap_id = Auth::user()->id; // transfer authorized person
                            $product_transfer->transfer_created_date = $request->transfer_created_date;
                            $product_transfer->transfer_received_date = $request->transfer_received_date;
                            $product_transfer->status = $request->status;
                            $product_transfer->remarks = $request->remarks;
                            $product_transfer->is_notification_send_to_admin = 1;
                            $product_transfer->save();
                            $insertMainTable = true;
                        }
                        $product_data = Product::find($product_id);
                        $product_bulk_transfer_details = new ProductTransferDetail();
                        $product_bulk_transfer_details->product_transfer_id = $product_transfer->id;
                        $product_bulk_transfer_details->product_id = $product_data->id;
                        $product_bulk_transfer_details->sku_code = $product_data->sku_code;
                        $product_bulk_transfer_details->name = $product_data->name;
                        $product_bulk_transfer_details->request_quantity = @$product_quantity;
                        $product_bulk_transfer_details->given_quantity = @$product_quantity;
                        $product_bulk_transfer_details->is_inc_exp_billable = 0;
                        $product_bulk_transfer_details->save();

                        $quantity = @$product_quantity;
                        if ($request->from_warehouse_id != null && $request->from_warehouse_id != "null") {
                            if ($quantity != 0) {
                                $warehouse_stock_detail_exists = WarehouseStockUpdate::where([['warehouse_id', $request->from_warehouse_id], ['product_id', $product_data->id], ['status', 1]])->orderBy('id', 'DESC')->first();
                                $warehouse_stock_detail = new WarehouseStockUpdate();
                                $warehouse_stock_detail->warehouse_id = $request->from_warehouse_id;
                                $warehouse_stock_detail->product_id = $product_data->id;
                                $warehouse_stock_detail->stock_update_on = Carbon::now();
                                $warehouse_stock_detail->existing_stock = ($warehouse_stock_detail_exists != null && $warehouse_stock_detail_exists->total_stock != null) ? $warehouse_stock_detail_exists->total_stock : 0;
                                $warehouse_stock_detail->adding_stock = 0;
                                $warehouse_stock_detail->total_stock = ($warehouse_stock_detail_exists != null && $warehouse_stock_detail_exists->total_stock != null) ? $warehouse_stock_detail_exists->total_stock + $quantity : $quantity;
                                $warehouse_stock_detail->status = 1;
                                $warehouse_stock_detail->box_number = $request->box_number != null ? $request->box_number : 1;
                                $warehouse_stock_detail->save();
                            }
                            $warehouse_inventory = WarehouseInventoryDetail::where([['warehouse_id', $request->from_warehouse_id], ['product_id', $product_data->id], ['status', 1]])->first();
                            if ($warehouse_inventory == null) {
                                $warehouse_inventory = new WarehouseInventoryDetail();
                                $warehouse_inventory->warehouse_id = $request->from_warehouse_id;
                                $warehouse_inventory->product_id = $product_data->id;
                            }
                            $warehouse_inventory->weight = @$warehouse_inventory->weight - $quantity;
                            $warehouse_inventory->status = 1;
                            $warehouse_inventory->save();
                        }

                        if ($store_id != null && $store_id != "null") {
                            if ($product_quantity != 0) {
                                $store_stock_detail_exists = StoreStockUpdate::where([['store_id', $store_id], ['product_id', $product_data->id], ['status', 1]])->orderBy('id', 'DESC')->first();
                                $store_stock_detail = new StoreStockUpdate();
                                $store_stock_detail->from_warehouse_id = NULL;
                                $store_stock_detail->store_id = $store_id;
                                $store_stock_detail->product_id = $product_data->id;
                                $store_stock_detail->reference_id = $product_transfer->id;
                                $store_stock_detail->reference_table = 11; //11 Bulk product transfer table
                                $store_stock_detail->stock_update_on = Carbon::now();
                                $store_stock_detail->existing_stock = ($store_stock_detail_exists != null && $store_stock_detail_exists->total_stock != null) ? $store_stock_detail_exists->total_stock : 0;
                                $store_stock_detail->adding_stock = @$product_quantity;
                                $store_stock_detail->total_stock = ($store_stock_detail_exists != null && $store_stock_detail_exists->total_stock != null) ? $store_stock_detail_exists->total_stock + @$product_quantity : @$product_quantity;
                                $store_stock_detail->status = 1;
                                $store_stock_detail->save();
                            }

                            $store_stock_detail = StoreStockUpdate::where([['store_id', $store_id], ['product_id', $product_data->id], ['status', 1]])->orderBy('id', 'DESC')->first();
                            $store_inventory = StoreInventoryDetail::where([['store_id', $store_id], ['product_id', $product_data->id], ['status', 1]])->first();
                            if ($store_inventory == null) {
                                $store_inventory = new StoreInventoryDetail();
                                $store_inventory->store_id = $store_id;
                                $store_inventory->product_id = $product_data->id;
                            }
                            $store_inventory->weight = @$store_inventory->weight + @$product_quantity;
                            $store_inventory->status = 1;
                            $store_inventory->save();
                        }
                    }
                    DB::commit();
                }
            }
        }
        $product_bulk_transfer = new ProductBulkTransfer();
        $product_bulk_transfer->transfer_order_number = $request->transfer_order_number;
        $product_bulk_transfer->from_warehouse_id = $request->from_warehouse_id;
        $product_bulk_transfer->transfer_created_date = $request->transfer_created_date;
        $product_bulk_transfer->transfer_received_date = $request->transfer_received_date;
        $product_bulk_transfer->status = $request->status;
        $product_bulk_transfer->remarks = $request->remarks;
        $product_bulk_transfer->converted_to_distribution = $converted_to_distribution;
        $product_bulk_transfer->save();

        $newArray = [];
        $storequantity  = $request->transfer_product['quantity'];
        foreach ($storequantity as $store_id => $products) {
            foreach ($products as $product_id => $quantity) {
                if ($quantity != null) {
                    $product_data = Product::find($product_id);
                    $product_bulk_transfer_details = new ProductBulkTransferDetail();
                    // if($product_bulk_transfer_details->given_quantity != $quantity) {
                    $product_bulk_transfer_details->product_bulk_transfer_id = $product_bulk_transfer->id;
                    $product_bulk_transfer_details->product_id = $product_id;
                    $product_bulk_transfer_details->store_id = $store_id;
                    $product_bulk_transfer_details->name = $product_data->name;
                    $product_bulk_transfer_details->sku_code = $product_data->sku_code;
                    $product_bulk_transfer_details->request_quantity = $quantity;
                    $product_bulk_transfer_details->given_quantity = $quantity;
                    $product_bulk_transfer_details->status = $request->status;
                    $product_bulk_transfer_details->remarks = $request->remarks;
                    $product_bulk_transfer_details->save();

                    array_push($newArray, $product_bulk_transfer_details);
                    // }
                }
            }
        }

        $ProductBulkTransferHistory = new ProductBulkTransferHistory();
        $ProductBulkTransferHistory->product_bulk_transfer_id = $product_bulk_transfer->id;
        $ProductBulkTransferHistory->transfer_created_date = $request->transfer_created_date;
        $ProductBulkTransferHistory->product_bulk_transfer_data = json_encode($product_bulk_transfer);
        $ProductBulkTransferHistory->product_bulk_transfer_details_data = json_encode($newArray);
        $ProductBulkTransferHistory->save();

        DB::commit();

        if ($request->submission_type == 1 || $request->submission_type == 'distribution_submit') {
            return redirect()->route('admin.bulk-product-transfer.index')->with('success', 'Bulk Product Transfer Stored Successfully.');
        } elseif ($request->submission_type == 2) {
            return back()->with('success', 'Bulk Product Transfer Stored Successfully');
        } else {
            return redirect()->route('admin.bulk-product-transfer.index')->with('success', 'Bulk Product Transfer Stored Successfully.');
        }
        // } catch (\Exception $e) {
        //     Log::error($e);
        //     DB::rollback();
        //     return back()->with('danger', 'Bulk Product Transfer Stored Fail.');
        // }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $data['product_bulk_transfer'] = ProductBulkTransfer::findOrFail($id);
        $data['warehouses'] = Warehouse::get();
        $data['products'] = Product::get();
        $data['stores'] = Store::get();
        $data['transport_types'] = TransportType::active()->get();
        $data['expense_types'] = IncomeExpenseType::active()->get();
        $data['units'] = Unit::active()->get();
        return view('pages.product.bulk_product_transfer.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        DB::beginTransaction();
        // try {
        $converted_to_distribution = 0;
        if ($request->submission_type == 'distribution_submit') {
            $converted_to_distribution = 1;
            $storequantity  = $request->transfer_product['quantity'];
            foreach ($storequantity as $store_id => $products) {
                $insertMainTable = false;
                foreach ($products as $product_id => $product_quantity) {
                    if ($product_quantity != null) {
                        if ($insertMainTable == false) {
                            $product_transfer = new ProductTransfer();
                            $transfer_order_number = CommonComponent::invoice_no('redistribution_prefix');
                            $product_transfer->transfer_order_number = $transfer_order_number;
                            $product_transfer->transfer_from = 1;
                            $product_transfer->transfer_to = 2;
                            $product_transfer->from_warehouse_id = $request->from_warehouse_id;
                            $product_transfer->to_store_id = $store_id;
                            $product_transfer->tap_id = Auth::user()->id; // transfer authorized person
                            $product_transfer->transfer_created_date = $request->transfer_created_date;
                            $product_transfer->transfer_received_date = $request->transfer_received_date;
                            $product_transfer->status = $request->status;
                            $product_transfer->remarks = $request->remarks;
                            $product_transfer->is_notification_send_to_admin = 1;
                            $product_transfer->save();
                            $insertMainTable = true;
                        }
                        $product_data = Product::find($product_id);
                        $product_bulk_transfer_details = new ProductTransferDetail();
                        $product_bulk_transfer_details->product_transfer_id = $product_transfer->id;
                        $product_bulk_transfer_details->product_id = $product_data->id;
                        $product_bulk_transfer_details->sku_code = $product_data->sku_code;
                        $product_bulk_transfer_details->name = $product_data->name;
                        $product_bulk_transfer_details->request_quantity = @$product_quantity;
                        $product_bulk_transfer_details->given_quantity = @$product_quantity;
                        $product_bulk_transfer_details->is_inc_exp_billable = 0;
                        $product_bulk_transfer_details->save();

                        $quantity = -@$product_quantity;
                        if ($request->from_warehouse_id != null && $request->from_warehouse_id != "null") {
                            if ($quantity != 0) {
                                $warehouse_stock_detail_exists = WarehouseStockUpdate::where([['warehouse_id', $request->from_warehouse_id], ['product_id', $product_data->id], ['status', 1]])->orderBy('id', 'DESC')->first();
                                $warehouse_stock_detail = new WarehouseStockUpdate();
                                $warehouse_stock_detail->warehouse_id = $request->from_warehouse_id;
                                $warehouse_stock_detail->product_id = $product_data->id;
                                $warehouse_stock_detail->stock_update_on = Carbon::now();
                                $warehouse_stock_detail->existing_stock = ($warehouse_stock_detail_exists != null && $warehouse_stock_detail_exists->total_stock != null) ? $warehouse_stock_detail_exists->total_stock : 0;
                                $warehouse_stock_detail->adding_stock = @$quantity;
                                $warehouse_stock_detail->total_stock = ($warehouse_stock_detail_exists != null && $warehouse_stock_detail_exists->total_stock != null) ? $warehouse_stock_detail_exists->total_stock + @$quantity : @$quantity;
                                $warehouse_stock_detail->status = 1;
                                $warehouse_stock_detail->box_number = $request->box_number != null ? $request->box_number : 1;
                                $warehouse_stock_detail->save();
                            }

                            $warehouse_inventory = WarehouseInventoryDetail::where([['warehouse_id', $request->from_warehouse_id], ['product_id', $product_data->id], ['status', 1]])->first();
                            if ($warehouse_inventory == null) {
                                $warehouse_inventory = new WarehouseInventoryDetail();
                                $warehouse_inventory->warehouse_id = $request->from_warehouse_id;
                                $warehouse_inventory->product_id = $product_data->id;
                            }
                            $warehouse_inventory->weight = @$warehouse_inventory->weight + @$quantity;
                            $warehouse_inventory->status = 1;
                            $warehouse_inventory->save();
                        }

                        if ($store_id != null && $store_id != "null") {
                            if ($product_quantity != 0) {
                                $store_stock_detail_exists = StoreStockUpdate::where([['store_id', $store_id], ['product_id', $product_data->id], ['status', 1]])->orderBy('id', 'DESC')->first();
                                $store_stock_detail = new StoreStockUpdate();
                                $store_stock_detail->from_warehouse_id = NULL;
                                $store_stock_detail->store_id = $store_id;
                                $store_stock_detail->product_id = $product_data->id;
                                $store_stock_detail->reference_id = $product_transfer->id;
                                $store_stock_detail->reference_table = 11; //11 Bulk product transfer table
                                $store_stock_detail->stock_update_on = Carbon::now();
                                $store_stock_detail->existing_stock = ($store_stock_detail_exists != null && $store_stock_detail_exists->total_stock != null) ? $store_stock_detail_exists->total_stock : 0;
                                $store_stock_detail->adding_stock = @$product_quantity;
                                $store_stock_detail->total_stock = ($store_stock_detail_exists != null && $store_stock_detail_exists->total_stock != null) ? $store_stock_detail_exists->total_stock + @$product_quantity : @$product_quantity;
                                $store_stock_detail->status = 1;
                                $store_stock_detail->save();
                            }

                            $store_stock_detail = StoreStockUpdate::where([['store_id', $store_id], ['product_id', $product_data->id], ['status', 1]])->orderBy('id', 'DESC')->first();
                            $store_inventory = StoreInventoryDetail::where([['store_id', $store_id], ['product_id', $product_data->id], ['status', 1]])->first();
                            if ($store_inventory == null) {
                                $store_inventory = new StoreInventoryDetail();
                                $store_inventory->store_id = $store_id;
                                $store_inventory->product_id = $product_data->id;
                            }
                            $store_inventory->weight = @$store_inventory->weight + @$product_quantity;
                            $store_inventory->status = 1;
                            $store_inventory->save();
                        }
                    }
                }
            }
        }

        $product_bulk_transfer = ProductBulkTransfer::findOrFail($id);
        $product_bulk_transfer->transfer_order_number = $request->transfer_order_number;
        $product_bulk_transfer->from_warehouse_id = $request->from_warehouse_id;
        $product_bulk_transfer->transfer_created_date = $request->transfer_created_date;
        $product_bulk_transfer->transfer_received_date = $request->transfer_received_date;
        $product_bulk_transfer->status = $request->status;
        $product_bulk_transfer->remarks = $request->remarks;
        $product_bulk_transfer->converted_to_distribution = $converted_to_distribution;
        $product_bulk_transfer->save();

        $newArray = [];
        $storequantity  = $request->transfer_product['quantity'];
        foreach ($storequantity as $store_id => $products) {
            foreach ($products as $product_id => $quantity) {
                if ($quantity != null) {
                    $product_data = Product::find($product_id);
                    $product_bulk_transfer_details = ProductBulkTransferDetail::where([['product_bulk_transfer_id', $product_bulk_transfer->id], ['product_id', $product_id], ['store_id', $product_id]])->first();
                    if ($product_bulk_transfer_details == null) {
                        $product_bulk_transfer_details = new ProductBulkTransferDetail();
                    }
                    // if($product_bulk_transfer_details->given_quantity != $quantity) {
                    $product_bulk_transfer_details->product_bulk_transfer_id = $product_bulk_transfer->id;
                    $product_bulk_transfer_details->product_id = $product_id;
                    $product_bulk_transfer_details->store_id = $store_id;
                    $product_bulk_transfer_details->name = $product_data->name;
                    $product_bulk_transfer_details->sku_code = $product_data->sku_code;
                    $product_bulk_transfer_details->request_quantity = $quantity;
                    $product_bulk_transfer_details->given_quantity = $quantity;
                    $product_bulk_transfer_details->status = $request->status;
                    $product_bulk_transfer_details->remarks = $request->remarks;
                    $product_bulk_transfer_details->save();

                    array_push($newArray, $product_bulk_transfer_details);
                    // }
                }
            }
        }

        // $products  = $request->transfer_product['product_id'];
        // foreach ($products as $key => $value) {
        //     $storequantity  = $request->transfer_product['quantity'][$value];
        //     foreach ($storequantity as $keys => $quantity) {
        //         if ($quantity != null) {
        //             $product_data = Product::find($value);
        //             $product_bulk_transfer_details = ProductBulkTransferDetail::where([['product_bulk_transfer_id', $product_bulk_transfer->id], ['product_id', $value], ['store_id', $keys]])->first();
        //             if ($product_bulk_transfer_details == null) {
        //                 $product_bulk_transfer_details = new ProductBulkTransferDetail();
        //             }
        //             // if($product_bulk_transfer_details->given_quantity != $quantity) {
        //                 $product_bulk_transfer_details->product_bulk_transfer_id = $product_bulk_transfer->id;
        //                 $product_bulk_transfer_details->product_id = $value;
        //                 $product_bulk_transfer_details->store_id = $keys;
        //                 $product_bulk_transfer_details->name = $product_data->name;
        //                 $product_bulk_transfer_details->sku_code = $product_data->sku_code;
        //                 $product_bulk_transfer_details->request_quantity = $quantity;
        //                 $product_bulk_transfer_details->given_quantity = $quantity;
        //                 $product_bulk_transfer_details->status = $request->status;
        //                 $product_bulk_transfer_details->remarks = $request->remarks;
        //                 $product_bulk_transfer_details->save();

        //                 array_push($newArray, $product_bulk_transfer_details);
        //             // }
        //         }
        //     }
        // }

        $ProductBulkTransferHistory = new ProductBulkTransferHistory();
        $ProductBulkTransferHistory->product_bulk_transfer_id = $product_bulk_transfer->id;
        $ProductBulkTransferHistory->transfer_created_date = $request->transfer_created_date;
        $ProductBulkTransferHistory->product_bulk_transfer_data = json_encode($product_bulk_transfer);
        $ProductBulkTransferHistory->product_bulk_transfer_details_data = json_encode($newArray);
        $ProductBulkTransferHistory->save();

        DB::commit();

        return redirect()->route('admin.bulk-product-transfer.index')->with('success', 'Bulk Product Transfer Updated Successfully.');
        // } catch (\Exception $e) {
        //     Log::error($e);
        //     DB::rollback();
        //     return back()->with('danger', 'Bulk Product Transfer Updated Failed.');
        // }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function getbulktransferproducts(Request $request)
    {
        $data['from_warehouse_id'] = $request->from_warehouse_id;
        $data['transfer_created_date'] = $request->transfer_created_date;
        $data['products'] = Product::get();
        $data['stores'] = Store::get();
        return view('pages.product.bulk_product_transfer.product_list', $data)->render();
    }
}
