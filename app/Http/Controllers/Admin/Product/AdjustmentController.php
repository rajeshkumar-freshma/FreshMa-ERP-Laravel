<?php

namespace App\Http\Controllers\Admin\Product;

use App\Core\CommonComponent;
use App\DataTables\Product\AdjustmentDataTable;
use App\Http\Controllers\Controller;
use App\Http\Requests\Product\AdjustmentFormRequest;
use App\Models\Adjustment;
use App\Models\AdjustmentDetail;
use App\Models\Product;
use App\Models\Store;
use App\Models\StoreInventoryDetail;
use App\Models\StoreStockUpdate;
use App\Models\Warehouse;
use App\Models\WarehouseInventoryDetail;
use App\Models\WarehouseStockUpdate;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;


class AdjustmentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(AdjustmentDataTable $dataTable)
    {
        return $dataTable->render('pages.product.adjustment.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data['stores'] = Store::select('id', 'store_name', 'store_code')->active()->get();
        $data['warehouses'] = Warehouse::select('id', 'name', 'code')->active()->get();
        return view('pages.product.adjustment.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(AdjustmentFormRequest $request)
    {

        // return $request->status;
        DB::beginTransaction();
        // try {
        $imagePath = null;
        $imageUrl = null;

        if ($request->hasFile('file')) {
            $imageData = CommonComponent::s3BucketFileUpload($request->file, 'adjustment');
            $imagePath = $imageData['filePath'];
            // $imageUrl = $imageData['fileName'];
            $imageUrl = $imageData['imageURL'];
        }

        $adjustment = new Adjustment();
        $adjustment->warehouse_id = $request->warehouse_id;
        $adjustment->store_id = $request->store_id;
        $adjustment->adjustment_track_number = $request->adjustment_track_number;
        $adjustment->adjustment_date = $request->adjustment_date;
        $adjustment->status = $request->status;
        $adjustment->total_request_quantity = $request->total_request_quantity;
        $adjustment->remarks = $request->remarks;
        if ($imageUrl != null) {
            $adjustment->file = $imageUrl;
            $adjustment->file_path = $imagePath;
        }
        $adjustment->save();

        $products = $request->products;
        // if ($products && is_array($products) && count($products) > 0) {
        //     foreach ($products['product_id'] as $key => $product) {
        //         $product_data = Product::findOrfail($products['product_id'][$key]);
        //         if ($request->status == 3) {
        //             $adjustment_detail = new AdjustmentDetail();
        //             $adjustment_detail->adjustment_id = $adjustment->id;
        //             $adjustment_detail->product_id = $product_data->id;
        //             $adjustment_detail->sku_code = $product_data->sku_code;
        //             $adjustment_detail->name = $product_data->name;
        //             $adjustment_detail->type = @$products['type'][$key];
        //             $adjustment_detail->quantity = @$products['quantity'][$key];
        //             $adjustment_detail->remarks = @$products['remarks'][$key];
        //             $adjustment_detail->status = 1;
        //             $adjustment_detail->save();
        //             if ($request->warehouse_id != null) {
        //                 $warehouse_stock_detail_exists = WarehouseStockUpdate::where([['warehouse_id', $request->warehouse_id], ['product_id', $product_data->id], ['status', 1]])->orderBy('id', 'DESC')->first();
        //                 $warehouse_stock_detail = new WarehouseStockUpdate();
        //                 $warehouse_stock_detail->warehouse_id = $request->warehouse_id;
        //                 $warehouse_stock_detail->product_id = $product_data->id;
        //                 $warehouse_stock_detail->stock_update_on = Carbon::now();
        //                 $warehouse_stock_detail->existing_stock = ($warehouse_stock_detail_exists != null && $warehouse_stock_detail_exists->total_stock != null) ? $warehouse_stock_detail_exists->total_stock : 0;
        //                 if ($products['type'][$key] == 1) {
        //                     $warehouse_stock_detail->adding_stock = $products['quantity'][$key];
        //                     $warehouse_stock_detail->total_stock = ($warehouse_stock_detail_exists != null && $warehouse_stock_detail_exists->total_stock != null) ? $warehouse_stock_detail_exists->total_stock + $products['quantity'][$key] : $products['quantity'][$key];
        //                     $warehouse_stock_detail->remarks = 'Stock incresed. ' . @$products['remarks'][$key];
        //                 } else {
        //                     $warehouse_stock_detail->adding_stock = -$products['quantity'][$key];
        //                     $warehouse_stock_detail->total_stock = ($warehouse_stock_detail_exists != null && $warehouse_stock_detail_exists->total_stock != null) ? $warehouse_stock_detail_exists->total_stock - $products['quantity'][$key] : $products['quantity'][$key];
        //                     $warehouse_stock_detail->remarks = 'Stock decreased. ' . @$products['remarks'][$key];
        //                 }
        //                 $warehouse_stock_detail->status = 1;
        //                 $warehouse_stock_detail->box_number = $request->box_number != null ? $request->box_number : $warehouse_stock_detail->box_number;
        //                 $warehouse_stock_detail->save();

        //                 $warehouse_inventory = WarehouseInventoryDetail::where([['warehouse_id', $request->warehouse_id], ['product_id', $product_data->id], ['status', 1]])->orderBy('id', 'DESC')->first();
        //                 if ($warehouse_inventory == null) {
        //                     $warehouse_inventory = new WarehouseInventoryDetail();
        //                     $warehouse_inventory->warehouse_id = $request->warehouse_id;
        //                     $warehouse_inventory->product_id = $product_data->id;
        //                 }

        //                 if ($products['type'][$key] == 1) {
        //                     $warehouse_inventory->weight = @$warehouse_inventory->weight + $warehouse_stock_detail->adding_stock;
        //                 } else {
        //                     $warehouse_inventory->weight = @$warehouse_inventory->weight + $warehouse_stock_detail->adding_stock;
        //                 }

        //                 $warehouse_inventory->status = 1;
        //                 $warehouse_inventory->save();
        //             }

        //             if ($request->store_id) {
        //                 $store_stock_detail_exists = StoreStockUpdate::where([['store_id', $request->store_id], ['product_id', $product_data->id], ['status', 1]])->orderBy('id', 'DESC')->first();
        //                 $store_stock_detail = new StoreStockUpdate();
        //                 $store_stock_detail->from_warehouse_id = $request->warehouse_id;
        //                 $store_stock_detail->store_id = $request->store_id;
        //                 $store_stock_detail->reference_id = $adjustment->id;
        //                 $store_stock_detail->reference_table = 9; //9 Adjustment Table
        //                 $store_stock_detail->product_id = $product_data->id;
        //                 $store_stock_detail->stock_update_on = Carbon::now();
        //                 $store_stock_detail->existing_stock = ($store_stock_detail_exists != null && $store_stock_detail_exists->total_stock != null) ? $store_stock_detail_exists->total_stock : 0;
        //                 $store_stock_detail->adding_stock = @$products['quantity'][$key];
        //                 if ($products['type'][$key] == 1) {
        //                     $store_stock_detail->total_stock = ($store_stock_detail_exists != null && $store_stock_detail_exists->total_stock != null) ? $store_stock_detail_exists->total_stock+@$products['quantity'][$key] : @$products['quantity'][$key];
        //                     $store_stock_detail->remarks = 'Stock incresed. ' . @$products['remarks'][$key];
        //                 } else {
        //                     $store_stock_detail->total_stock = ($store_stock_detail_exists != null && $store_stock_detail_exists->total_stock != null) ? $store_stock_detail_exists->total_stock-@$products['quantity'][$key] : @$products['quantity'][$key];
        //                     $warehouse_stock_detail->remarks = 'Stock decreased. ' . @$products['remarks'][$key];
        //                 }
        //                 $store_stock_detail->status = 1;
        //                 $store_stock_detail->save();

        //                 $store_inventory = StoreInventoryDetail::where([['store_id', $request->store_id], ['product_id', $product_data->id], ['status', 1]])->orderBy('id', 'DESC')->first();
        //                 if ($store_inventory == null) {
        //                     $store_inventory = new StoreInventoryDetail();
        //                     $store_inventory->store_id = $request->store_id;
        //                     $store_inventory->product_id = $product_data->id;
        //                 }
        //                 $store_inventory->weight = @$store_inventory->weight + $store_stock_detail->adding_stock;
        //                 $store_inventory->status = 1;
        //                 $store_inventory->save();
        //             }
        //         }
        //     }
        // }



        if ($products && is_array($products) && count($products) > 0) {
            foreach ($products['product_id'] as $key => $productId) {
                $product = Product::findOrFail($productId);
                $quantity = @$products['quantity'][$key];
                $type = @$products['type'][$key];
                $remarks = @$products['remarks'][$key];
                // Create adjustment detail
                $adjustmentDetail = new AdjustmentDetail();
                $adjustmentDetail->adjustment_id = $adjustment->id;
                $adjustmentDetail->product_id = $product->id;
                $adjustmentDetail->sku_code = $product->sku_code;
                $adjustmentDetail->name = $product->name;
                $adjustmentDetail->type = $type;
                $adjustmentDetail->quantity = $quantity;
                $adjustmentDetail->remarks = $remarks;
                $adjustmentDetail->status = 1;
                $adjustmentDetail->save();

                if ($request->status == 3) {
                    Log::info("enter if status 3");
                    // Handle warehouse stock update
                    if ($request->warehouse_id) {
                        $warehouseStockDetail = WarehouseStockUpdate::where([
                            ['warehouse_id', $request->warehouse_id],
                            ['product_id', $product->id],
                            ['status', 1],
                        ])->orderBy('id', 'DESC')->first();

                        $warehouseStockDetail = $warehouseStockDetail ?: new WarehouseStockUpdate();
                        $warehouseStockDetail->warehouse_id = $request->warehouse_id;
                        $warehouseStockDetail->product_id = $product->id;
                        $warehouseStockDetail->stock_update_on = Carbon::now();
                        $warehouseStockDetail->existing_stock = $warehouseStockDetail->total_stock ?? 0;

                        if ($type == 1) {
                            // Stock increased
                            $warehouseStockDetail->adding_stock = $quantity;
                            $warehouseStockDetail->total_stock = ($warehouseStockDetail->total_stock ?? 0) + $quantity;
                            $warehouseStockDetail->remarks = 'Stock increased. ' . $remarks;
                        } else {
                            // Stock decreased
                            $warehouseStockDetail->adding_stock = -$quantity;
                            $warehouseStockDetail->total_stock = ($warehouseStockDetail->total_stock ?? 0) - $quantity;
                            $warehouseStockDetail->remarks = 'Stock decreased. ' . $remarks;
                        }

                        $warehouseStockDetail->status = 1;
                        $warehouseStockDetail->box_number = $request->box_number ?? $warehouseStockDetail->box_number;
                        $warehouseStockDetail->save();

                        // Update warehouse inventory
                        $warehouseInventory = WarehouseInventoryDetail::where([
                            ['warehouse_id', $request->warehouse_id],
                            ['product_id', $product->id],
                            ['status', 1],
                        ])->orderBy('id', 'DESC')->first();

                        $warehouseInventory = $warehouseInventory ?: new WarehouseInventoryDetail();
                        $warehouseInventory->warehouse_id = $request->warehouse_id;
                        $warehouseInventory->product_id = $product->id;
                        $warehouseInventory->weight = ($warehouseInventory->weight ?? 0) + $warehouseStockDetail->adding_stock;
                        $warehouseInventory->status = 1;
                        $warehouseInventory->save();
                    }

                    // Handle store stock update
                    if ($request->store_id) {
                        $storeStockDetail = StoreStockUpdate::where([
                            ['store_id', $request->store_id],
                            ['product_id', $product->id],
                            ['status', 1],
                        ])->orderBy('id', 'DESC')->first();

                        $storeStockDetail = $storeStockDetail ?: new StoreStockUpdate();
                        $storeStockDetail->from_warehouse_id = $request->warehouse_id;
                        $storeStockDetail->store_id = $request->store_id;
                        $storeStockDetail->reference_id = $adjustment->id;
                        $storeStockDetail->reference_table = 9; // 9 = Adjustment Table
                        $storeStockDetail->product_id = $product->id;
                        $storeStockDetail->stock_update_on = Carbon::now();
                        $storeStockDetail->existing_stock = $storeStockDetail->total_stock ?? 0;

                        if ($type == 1) {
                            // Stock increased
                            $storeStockDetail->adding_stock = $quantity;
                            $storeStockDetail->total_stock = ($storeStockDetail->total_stock ?? 0) + $quantity;
                            $storeStockDetail->remarks = 'Stock increased. ' . $remarks;
                        } else {
                            // Stock decreased
                            $storeStockDetail->adding_stock = -$quantity;
                            $storeStockDetail->total_stock = ($storeStockDetail->total_stock ?? 0) - $quantity;
                            $storeStockDetail->remarks = 'Stock decreased. ' . $remarks;
                        }

                        $storeStockDetail->status = 1;
                        $storeStockDetail->save();

                        // Update store inventory
                        $storeInventory = StoreInventoryDetail::where([
                            ['store_id', $request->store_id],
                            ['product_id', $product->id],
                            ['status', 1],
                        ])->orderBy('id', 'DESC')->first();

                        $storeInventory = $storeInventory ?: new StoreInventoryDetail();
                        $storeInventory->store_id = $request->store_id;
                        $storeInventory->product_id = $product->id;
                        $storeInventory->weight = ($storeInventory->weight ?? 0) + $storeStockDetail->adding_stock;
                        $storeInventory->status = 1;
                        $storeInventory->save();
                    }
                }
            }
        }

        DB::commit();

        if ($request->submission_type == 1) {
            return redirect()->route('admin.adjustment.index')->with('success', 'Adjustment Stored Successfully');
        } elseif ($request->submission_type == 2) {
            return back()->with('success', 'Adjustment Stored Successfully');
        }
        // } catch (\Exception $e) {
        //     Log::error($e);
        //     DB::rollback();
        //     return back()->withInput()->with('error', 'Adjustment Stored Fail');
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
        $data['stores'] = Store::select('id', 'store_name', 'store_code')->active()->get();
        $data['warehouses'] = Warehouse::select('id', 'name', 'code')->active()->get();
        $data['adjustment'] = Adjustment::findOrfail($id);
        return view('pages.product.adjustment.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(AdjustmentFormRequest $request, $id)
    {
        DB::beginTransaction();
        // try {
        $imagePath = null;
        $imageUrl = null;

        $adjustment = Adjustment::findOrFail($id);

        if ($request->hasFile('file')) {
            $fileDeleted = CommonComponent::s3BucketFileDelete($adjustment->file, $adjustment->file_path);

            $imageData = CommonComponent::s3BucketFileUpload($request->file, 'adjustment');
            $imagePath = $imageData['filePath'];
            // $imageUrl = $imageData['fileName'];
            $imageUrl = $imageData['imageURL'];
        }

        $adjustment->warehouse_id = $request->warehouse_id;
        $adjustment->store_id = $request->store_id;
        $adjustment->adjustment_track_number = $request->adjustment_track_number;
        $adjustment->adjustment_date = $request->adjustment_date;
        $adjustment->status = $request->status;
        $adjustment->total_request_quantity = $request->total_request_quantity;
        $adjustment->remarks = $request->remarks;
        if ($imageUrl != null) {
            $adjustment->file = $imageUrl;
            $adjustment->file_path = $imagePath;
        }
        $adjustment->save();

        $request_old_ids = [];
        foreach ($request->products['product_id'] as $store_key => $value) {
            if ($request->products['id'][$store_key] != null) {
                $request_old_ids[] = $request->products['id'][$store_key];
            }
        }

        $exists_indent_product = AdjustmentDetail::where('adjustment_id', $id)->get();
        if (count($exists_indent_product) > 0) {
            foreach ($exists_indent_product as $exists_key => $value) {
                if (!in_array($value->id, $request_old_ids)) {
                    AdjustmentDetail::where('id', $value->id)->delete();
                }
            }
        }

        $products = $request->products;

        // if ($products && is_array($products) && count($products) > 0) {
        //     foreach ($products['product_id'] as $key => $product) {
        //         $product_data = Product::findOrfail($products['product_id'][$key]);

        //         if ($request->status == 3) {
        //             $old_weight = 0;
        //             if (isset($products['id'][$key])) {
        //                 if (in_array($products['id'][$key], $exists_indent_product->pluck('id')->toArray())) {
        //                     $adjustment_detail = AdjustmentDetail::findOrFail($products['id'][$key]);
        //                     $old_weight = $adjustment_detail->quantity;
        //                 }
        //                 // else {
        //                 //     $adjustment_detail = new AdjustmentDetail();
        //                 //     $adjustment_detail->adjustment_id = $adjustment->id;
        //                 // }
        //             } else {
        //                 $adjustment_detail = new AdjustmentDetail();
        //                 $adjustment_detail->adjustment_id = $adjustment->id;
        //             }
        //             if ($products['type'][$key] == 1) {
        //                 $quantity = $old_weight + $products['quantity'][$key];
        //             } else {
        //                 $quantity = $old_weight - $products['quantity'][$key];
        //             }
        //             $adjustment_detail->product_id = $product_data->id;
        //             $adjustment_detail->sku_code = $product_data->sku_code;
        //             $adjustment_detail->name = $product_data->name;
        //             $adjustment_detail->type = $products['type'][$key];
        //             $adjustment_detail->quantity = $quantity;
        //             $adjustment_detail->remarks = $products['remarks'][$key] ?? '';
        //             $adjustment_detail->status = 1;
        //             $adjustment_detail->save();
        //             if ($request->warehouse_id) {
        //                 $warehouse_stock_detail_exists = WarehouseStockUpdate::where([['warehouse_id', $request->warehouse_id], ['product_id', $product_data->id], ['status', 1]])->orderBy('id', 'DESC')->first();
        //                 $warehouse_stock_detail = new WarehouseStockUpdate();
        //                 $warehouse_stock_detail->warehouse_id = $request->warehouse_id;
        //                 $warehouse_stock_detail->product_id = $product_data->id;
        //                 $warehouse_stock_detail->stock_update_on = Carbon::now();
        //                 $warehouse_stock_detail->existing_stock = ($warehouse_stock_detail_exists != null && $warehouse_stock_detail_exists->total_stock != null) ? $warehouse_stock_detail_exists->total_stock : 0;
        //                 if ($products['type'][$key] == 1) {
        //                     $warehouse_stock_detail->adding_stock = $products['quantity'][$key];
        //                     $warehouse_stock_detail->total_stock = ($warehouse_stock_detail_exists != null && $warehouse_stock_detail_exists->total_stock != null) ? $warehouse_stock_detail->total_stock + $products['quantity'][$key] : $products['quantity'][$key];
        //                     $warehouse_stock_detail->remarks = 'Stock incresed. ' . @$products['remarks'][$key];
        //                 } else {
        //                     $warehouse_stock_detail->adding_stock = -$products['quantity'][$key];
        //                     $warehouse_stock_detail->total_stock = ($warehouse_stock_detail_exists != null && $warehouse_stock_detail_exists->total_stock != null) ? $warehouse_stock_detail->total_stock - $products['quantity'][$key] : $products['quantity'][$key];
        //                     $warehouse_stock_detail->remarks = 'Stock decreased. ' . @$products['remarks'][$key];
        //                 }
        //                 $warehouse_stock_detail->status = 1;
        //                 $warehouse_stock_detail->box_number = $request->box_number != null ? $request->box_number : $warehouse_stock_detail->box_number;
        //                 $warehouse_stock_detail->save();

        //                 $warehouse_inventory = WarehouseInventoryDetail::where([['warehouse_id', $request->warehouse_id], ['product_id', $product_data->id], ['status', 1]])->orderBy('id', 'DESC')->first();
        //                 if ($warehouse_inventory == null) {
        //                     $warehouse_inventory = new WarehouseInventoryDetail();
        //                     $warehouse_inventory->warehouse_id = $request->warehouse_id;
        //                     $warehouse_inventory->product_id = $product_data->id;
        //                 }

        //                 if ($products['type'][$key] == 1) {
        //                     $warehouse_inventory->weight = @$warehouse_inventory->weight + $warehouse_stock_detail->adding_stock;
        //                 } else {
        //                     $warehouse_inventory->weight = @$warehouse_inventory->weight + $warehouse_stock_detail->adding_stock;
        //                 }

        //                 $warehouse_inventory->status = 1;
        //                 $warehouse_inventory->save();
        //             }

        //             if ($request->store_id) {
        //                 $store_stock_detail_exists = StoreStockUpdate::where([['store_id', $request->store_id], ['product_id', $product_data->id], ['status', 1]])->orderBy('id', 'DESC')->first();
        //                 $store_stock_detail = new StoreStockUpdate();
        //                 $store_stock_detail->from_warehouse_id = $request->warehouse_id;
        //                 $store_stock_detail->store_id = $request->store_id;
        //                 $store_stock_detail->reference_id = $adjustment->id;
        //                 $store_stock_detail->reference_table = 9; //9 Adjustment Table
        //                 $store_stock_detail->product_id = $product_data->id;
        //                 $store_stock_detail->stock_update_on = Carbon::now();
        //                 $store_stock_detail->existing_stock = ($store_stock_detail_exists != null && $store_stock_detail_exists->total_stock != null) ? $store_stock_detail_exists->total_stock : 0;
        //                 if ($products['type'][$key] == 1) {
        //                     $store_stock_detail->adding_stock = $products['quantity'][$key];
        //                     $store_stock_detail->total_stock = ($store_stock_detail_exists != null && $store_stock_detail_exists->total_stock != null) ? $store_stock_detail_exists->total_stock + $products['quantity'][$key] : $products['quantity'][$key];
        //                     $store_stock_detail->remarks = 'Stock incresed. ' . @$products['remarks'][$key];
        //                 } else {
        //                     $store_stock_detail->adding_stock = -$products['quantity'][$key];
        //                     $store_stock_detail->total_stock = ($store_stock_detail_exists != null && $store_stock_detail_exists->total_stock != null) ? $store_stock_detail_exists->total_stock - $products['quantity'][$key] : $products['quantity'][$key];
        //                     $store_stock_detail->remarks = 'Stock decreased. ' . @$products['remarks'][$key];
        //                 }
        //                 $store_stock_detail->status = 1;
        //                 $store_stock_detail->save();

        //                 $store_inventory = StoreInventoryDetail::where([['store_id', $request->store_id], ['product_id', $product_data->id], ['status', 1]])->orderBy('id', 'DESC')->first();
        //                 if ($store_inventory == null) {
        //                     $store_inventory = new StoreInventoryDetail();
        //                     $store_inventory->store_id = $request->store_id;
        //                     $store_inventory->product_id = $product_data->id;
        //                 }
        //                 if ($products['type'][$key] == 1) {
        //                     $store_inventory->weight = @$store_inventory->weight + $store_stock_detail->adding_stock;
        //                 } else {
        //                     $store_inventory->weight = @$store_inventory->weight + $store_stock_detail->adding_stock;
        //                 }
        //                 $store_inventory->status = 1;
        //                 $store_inventory->save();
        //             }
        //         }
        //     }
        // }
        if ($products && is_array($products) && count($products) > 0) {
            foreach ($products['product_id'] as $key => $productId) {
                $product = Product::findOrFail($productId);
                $quantity = @$products['quantity'][$key];
                $type = @$products['type'][$key];
                $remarks = @$products['remarks'][$key] ?? '';

                // Initialize adjustment detail and old quantity
                $adjustmentDetail = null;
                $oldQuantity = 0;

                // Check if an adjustment detail already exists
                if (isset($products['id'][$key])) {
                    if (in_array($products['id'][$key], $exists_indent_product->pluck('id')->toArray())) {
                        $adjustmentDetail = AdjustmentDetail::findOrFail($products['id'][$key]);
                        $oldQuantity = $adjustmentDetail->quantity;

                        // Remove old quantity from total if type is subtraction
                        if ($type == 1) {
                            $quantityChange = $quantity - $oldQuantity; // Calculate the difference
                        } else {
                            $quantityChange = $oldQuantity - $quantity; // Calculate the difference
                        }

                        // Update the adjustment detail with new values
                        $adjustmentDetail->quantity = $quantity;
                    }
                }

                // Create new adjustment detail if not found
                if ($adjustmentDetail === null) {
                    $adjustmentDetail = new AdjustmentDetail();
                    $adjustmentDetail->adjustment_id = $adjustment->id;
                    $adjustmentDetail->product_id = $product->id;
                    $adjustmentDetail->sku_code = $product->sku_code;
                    $adjustmentDetail->name = $product->name;
                    $adjustmentDetail->status = 1;
                    $adjustmentDetail->quantity = $quantity;
                }

                // Set common properties
                $adjustmentDetail->type = $type;
                $adjustmentDetail->remarks = $remarks;
                $adjustmentDetail->save();

                // Handle warehouse and store stock updates only if status is 3
                if ($request->status == 3) {
                    if ($request->warehouse_id) {
                        // Warehouse stock update logic
                        $warehouseStockDetail = WarehouseStockUpdate::where([
                            ['warehouse_id', $request->warehouse_id],
                            ['product_id', $product->id],
                            ['status', 1],
                        ])->orderBy('id', 'DESC')->first();

                        $warehouseStockDetail = $warehouseStockDetail ?: new WarehouseStockUpdate();
                        $warehouseStockDetail->warehouse_id = $request->warehouse_id;
                        $warehouseStockDetail->product_id = $product->id;
                        $warehouseStockDetail->stock_update_on = Carbon::now();
                        $warehouseStockDetail->existing_stock = $warehouseStockDetail->total_stock ?? 0;

                        if ($type == 1) {
                            $warehouseStockDetail->adding_stock = $quantity;
                            $warehouseStockDetail->total_stock = ($warehouseStockDetail->total_stock ?? 0) + $quantity;
                            $warehouseStockDetail->remarks = 'Stock increased. ' . $remarks;
                        } else {
                            $warehouseStockDetail->adding_stock = -$quantity;
                            $warehouseStockDetail->total_stock = ($warehouseStockDetail->total_stock ?? 0) - $quantity;
                            $warehouseStockDetail->remarks = 'Stock decreased. ' . $remarks;
                        }

                        $warehouseStockDetail->status = 1;
                        $warehouseStockDetail->box_number = $request->box_number ?? $warehouseStockDetail->box_number;
                        $warehouseStockDetail->save();

                        // Warehouse inventory update
                        $warehouseInventory = WarehouseInventoryDetail::where([
                            ['warehouse_id', $request->warehouse_id],
                            ['product_id', $product->id],
                            ['status', 1],
                        ])->orderBy('id', 'DESC')->first();

                        $warehouseInventory = $warehouseInventory ?: new WarehouseInventoryDetail();
                        $warehouseInventory->warehouse_id = $request->warehouse_id;
                        $warehouseInventory->product_id = $product->id;
                        $warehouseInventory->weight = ($warehouseInventory->weight ?? 0) + $warehouseStockDetail->adding_stock;
                        $warehouseInventory->status = 1;
                        $warehouseInventory->save();
                    }

                    if ($request->store_id) {
                        // Store stock update logic
                        $storeStockDetail = StoreStockUpdate::where([
                            ['store_id', $request->store_id],
                            ['product_id', $product->id],
                            ['status', 1],
                        ])->orderBy('id', 'DESC')->first();

                        $storeStockDetail = $storeStockDetail ?: new StoreStockUpdate();
                        $storeStockDetail->from_warehouse_id = $request->warehouse_id;
                        $storeStockDetail->store_id = $request->store_id;
                        $storeStockDetail->reference_id = $adjustment->id;
                        $storeStockDetail->reference_table = 9; // 9 = Adjustment Table
                        $storeStockDetail->product_id = $product->id;
                        $storeStockDetail->stock_update_on = Carbon::now();
                        $storeStockDetail->existing_stock = $storeStockDetail->total_stock ?? 0;

                        if ($type == 1) {
                            $storeStockDetail->adding_stock = $quantity;
                            $storeStockDetail->total_stock = ($storeStockDetail->total_stock ?? 0) + $quantity;
                            $storeStockDetail->remarks = 'Stock increased. ' . $remarks;
                        } else {
                            $storeStockDetail->adding_stock = -$quantity;
                            $storeStockDetail->total_stock = ($storeStockDetail->total_stock ?? 0) - $quantity;
                            $storeStockDetail->remarks = 'Stock decreased. ' . $remarks;
                        }

                        $storeStockDetail->status = 1;
                        $storeStockDetail->save();

                        // Store inventory update
                        $storeInventory = StoreInventoryDetail::where([
                            ['store_id', $request->store_id],
                            ['product_id', $product->id],
                            ['status', 1],
                        ])->orderBy('id', 'DESC')->first();

                        $storeInventory = $storeInventory ?: new StoreInventoryDetail();
                        $storeInventory->store_id = $request->store_id;
                        $storeInventory->product_id = $product->id;
                        $storeInventory->weight = ($storeInventory->weight ?? 0) + $storeStockDetail->adding_stock;
                        $storeInventory->status = 1;
                        $storeInventory->save();
                    }
                }
            }
        }

        DB::commit();

        return redirect()->route('admin.adjustment.index')->with('success', 'Adjustment Updated Successfully');
        // } catch (\Exception $e) {
        //     Log::error($e);
        //     DB::rollback();
        //     return back()->withInput()->with('error', 'Adjustment Updated Fail');
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
