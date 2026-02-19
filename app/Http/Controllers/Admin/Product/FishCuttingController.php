<?php

namespace App\Http\Controllers\Admin\Product;

use App\DataTables\Product\FishCuttingDataTable;
use App\Http\Controllers\Controller;
use App\Http\Requests\Product\FishCuttingFormRequest;
use App\Models\FishCutting;
use App\Models\FishCuttingDetail;
use App\Models\FishCuttingProductMap;
use App\Models\Product;
use App\Models\Store;
use App\Models\StoreInventoryDetail;
use App\Models\StoreStockUpdate;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class FishCuttingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(FishCuttingDataTable $dataTable)
    {
        return $dataTable->render('pages.product.fish_cutting.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $data['products'] = Product::orderBy('id', 'ASC')->get();
        $data['stores'] = Store::orderBy('id', 'ASC')->get();
        return view('pages.product.fish_cutting.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(FishCuttingFormRequest $request)
    {
        DB::beginTransaction();
        try {
            $fishcutting = new FishCutting();
            $fishcutting->weight = $request->weight;
            $fishcutting->product_id = $request->product_id;
            $fishcutting->store_id = $request->store_id;
            $fishcutting->cutting_date = $request->cutting_date;
            $fishcutting->status = $request->status;
            $fishcutting->remarks = $request->remarks;
            $fishcutting->save();

            // CommonComponent::fishcuttingcalculation('subtraction', $request->weight, $request->store_id, $request->product_id);

            $expression = 'subtraction';
            $weight = $request->weight;
            $store_id = $request->store_id;
            $product_id = $request->product_id;

            if ($expression == 'addition') {
                $quantity = +$weight;
            } else if ($expression == 'subtraction') {
                $quantity = -$weight;
            }

            $fishcutting_details = FishCuttingProductMap::where('main_product_id', $product_id)->orderbyDesc('id')->first();

            if ($fishcutting_details != null) {
                if ($quantity != 0) {
                    $store_stock_detail_exists = StoreStockUpdate::where([['store_id', $store_id], ['product_id', $product_id], ['status', 1]])->orderBy('id', 'DESC')->first();
                    $store_stock_detail = new StoreStockUpdate();
                    $store_stock_detail->from_warehouse_id = 1;
                    $store_stock_detail->store_id = $store_id;
                    $store_stock_detail->product_id = $product_id;
                    $store_stock_detail->reference_id = $fishcutting->id;
                    $store_stock_detail->reference_table = 10; //10 Fish Cutting table
                    $store_stock_detail->stock_update_on = Carbon::now();
                    $store_stock_detail->existing_stock = ($store_stock_detail_exists != null && $store_stock_detail_exists->total_stock != null) ? $store_stock_detail_exists->total_stock : 0;
                    $store_stock_detail->adding_stock = @$quantity;
                    $store_stock_detail->total_stock = ($store_stock_detail_exists != null && $store_stock_detail_exists->total_stock != null) ? $store_stock_detail_exists->total_stock+@$quantity : @$quantity;
                    $store_stock_detail->status = 1;
                    $store_stock_detail->save();
                }

                // $store_stock_detail = StoreStockUpdate::where([['store_id', $store_id], ['product_id', $product_id], ['status', 1]])->orderBy('id', 'DESC')->first();
                $store_inventory = StoreInventoryDetail::where([['store_id', $store_id], ['product_id', $product_id], ['status', 1]])->first();

                if ($store_inventory == null) {
                    $store_inventory = new StoreInventoryDetail();
                    $store_inventory->store_id = $store_id;
                    $store_inventory->product_id = $product_id;
                }
                $store_inventory->weight = @$store_inventory->weight+@$quantity;
                $store_inventory->status = 1;
                $store_inventory->save();
                $grouped_products = ($fishcutting_details != null && $fishcutting_details->grouped_product != null) ? json_decode($fishcutting_details->grouped_product) : [];
                if (count($grouped_products) > 0) {
                    foreach ($grouped_products as $key => $grouped_product) {
                        $fish_cutting_data = FishCuttingDetail::where([['fish_cutting_id', $fishcutting->id], ['product_id', $grouped_product->product_id]])->orderBy('id', 'DESC')->first();
                        if ($fish_cutting_data == null) {
                            $fish_cutting_data = new FishCuttingDetail();
                        }
                        $fish_cutting_data->fish_cutting_id = $fishcutting->id;
                        $fish_cutting_data->product_id = $grouped_product->product_id;
                        $fish_cutting_data->status = 1;
                        $fish_cutting_data->remarks = $request->remarks;

                        $group_quantity = (($quantity * $grouped_product->percentage) / 100) * -1;

                        if ($grouped_product->type == '1') { // slice
                            $fish_cutting_data->slice = $group_quantity;
                        } else if ($grouped_product->type == '2') { // head
                            $fish_cutting_data->head = $group_quantity;
                        } else if ($grouped_product->type == '3') { // tail
                            $fish_cutting_data->tail = $group_quantity;
                        } else if ($grouped_product->type == '4') { // eggs
                            $fish_cutting_data->eggs = $group_quantity;
                        }
                        $fish_cutting_data->save();

                        if ($store_id != null && $store_id != "null") {
                            if ($group_quantity != 0) {
                                $store_stock_detail_exists = StoreStockUpdate::where([['store_id', $store_id], ['product_id', $grouped_product->product_id], ['status', 1]])->orderBy('id', 'DESC')->first();
                                $store_stock_detail = new StoreStockUpdate();
                                $store_stock_detail->store_id = $store_id;
                                $store_stock_detail->product_id = $grouped_product->product_id;
                                $store_stock_detail->stock_update_on = Carbon::now();
                                $store_stock_detail->reference_id = $fishcutting->id;
                                $store_stock_detail->reference_table = 10; //10 Fish Cutting table
                                $store_stock_detail->existing_stock = ($store_stock_detail_exists != null && $store_stock_detail_exists->total_stock != null) ? $store_stock_detail_exists->total_stock : 0;
                                $store_stock_detail->adding_stock = @$group_quantity;
                                $store_stock_detail->total_stock = ($store_stock_detail_exists != null && $store_stock_detail_exists->total_stock != null) ? $store_stock_detail_exists->total_stock+@$group_quantity : @$group_quantity;
                                $store_stock_detail->status = 1;
                                $store_stock_detail->remarks = "This is added/Sub from Fish cutting.";
                                $store_stock_detail->save();
                            }
                            $store_inventory = StoreInventoryDetail::where([['store_id', $store_id], ['product_id', $grouped_product->product_id], ['status', 1]])->first();
                            if ($store_inventory == null) {
                                $store_inventory = new StoreInventoryDetail();
                                $store_inventory->store_id = $store_id;
                                $store_inventory->product_id = $grouped_product->product_id;
                            }
                            $store_inventory->weight = @$store_inventory->weight+@$group_quantity;
                            $store_inventory->status = 1;
                            $store_inventory->save();
                        }
                    }
                }
            } else {
                $quantity = -$request->weight;
                if ($request->store_id != null && $request->store_id != "null") {
                    if ($quantity != 0) {
                        $store_stock_detail_exists = StoreStockUpdate::where([['store_id', $request->store_id], ['product_id', $request->product_id], ['status', 1]])->orderBy('id', 'DESC')->first();
                        $store_stock_detail = new StoreStockUpdate();
                        $store_stock_detail->store_id = $request->store_id;
                        $store_stock_detail->product_id = $request->product_id;
                        $store_stock_detail->reference_id = $fishcutting->id;
                        $store_stock_detail->reference_table = 10; //10 Fish Cutting table
                        $store_stock_detail->stock_update_on = Carbon::now();
                        $store_stock_detail->existing_stock = ($store_stock_detail_exists != null && $store_stock_detail_exists->total_stock != null) ? $store_stock_detail_exists->total_stock : 0;
                        $store_stock_detail->adding_stock = @$quantity;
                        $store_stock_detail->total_stock = ($store_stock_detail_exists != null && $store_stock_detail_exists->total_stock != null) ? $store_stock_detail_exists->total_stock+@$quantity : @$quantity;
                        $store_stock_detail->status = 1;
                        $store_stock_detail->save();
                    }
                    $store_stock_detail = StoreStockUpdate::where([['store_id', $request->store_id], ['product_id', $request->product_id], ['status', 1]])->orderBy('id', 'DESC')->first();
                    $store_inventory = StoreInventoryDetail::where([['store_id', $request->store_id], ['product_id', $request->product_id], ['status', 1]])->first();
                    if ($store_inventory == null) {
                        $store_inventory = new StoreInventoryDetail();
                        $store_inventory->store_id = $request->store_id;
                        $store_inventory->product_id = $request->product_id;
                    }
                    $store_inventory->weight = @$store_inventory->weight+@$quantity;
                    $store_inventory->status = 1;
                    $store_inventory->save();
                }
            }
            DB::commit();

            if ($request->submission_type == 1) {
                return redirect()
                    ->route('admin.fish-cutting.index')
                    ->with('success', 'Fish Cutting Successfully');
            } elseif ($request->submission_type == 2) {
                return back()->with('success', 'Fish Cutting Successfully');
            }
        } catch (\Exception $e) {
            Log::error($e);
            DB::rollback();
            return back()->withInput()->with('error', 'Fish Cutting Fail');
        }
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
        $data['fish_cutting_data'] = FishCutting::find($id);
        $data['products'] = Product::orderBy('id', 'ASC')->get();
        $data['stores'] = Store::orderBy('id', 'ASC')->get();
        return view('pages.product.fish_cutting.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(FishCuttingFormRequest $request, $id)
    {
        DB::beginTransaction();
        // try {
        $fishcutting = FishCutting::findOrFail($id);
        $old_weight = $fishcutting->weight;

        $fishcutting->weight = $request->weight;
        $fishcutting->product_id = $request->product_id;
        $fishcutting->store_id = $request->store_id;
        $fishcutting->cutting_date = $request->cutting_date;
        $fishcutting->status = $request->status;
        $fishcutting->save();

        $fishcutting_details = FishCuttingProductMap::where('main_product_id', $fishcutting->product_id)->orderbyDesc('id')->first();
        if ($fishcutting_details != null) {
            $grouped_products = ($fishcutting_details != null && $fishcutting_details->grouped_product != null) ? json_decode($fishcutting_details->grouped_product) : [];
            $new_weight = $request->weight - $old_weight;
            $quantity = $new_weight;
            if ($request->store_id != null && $request->store_id != "null") {
                if ($quantity != 0) {
                    $store_stock_detail_exists = StoreStockUpdate::where([['store_id', $request->store_id], ['product_id', $fishcutting->product_id], ['status', 1]])->orderBy('id', 'DESC')->first();
                    $store_stock_detail = new StoreStockUpdate();
                    $store_stock_detail->store_id = $request->store_id;
                    $store_stock_detail->product_id = $fishcutting->product_id;
                    $store_stock_detail->reference_id = $fishcutting->id;
                    $store_stock_detail->reference_table = 10; //10 Fish Cutting table
                    $store_stock_detail->stock_update_on = Carbon::now();
                    $store_stock_detail->existing_stock = ($store_stock_detail_exists != null && $store_stock_detail_exists->total_stock != null) ? $store_stock_detail_exists->total_stock : 0;
                    $store_stock_detail->adding_stock = @$quantity;
                    $store_stock_detail->total_stock = ($store_stock_detail_exists != null && $store_stock_detail_exists->total_stock != null) ? $store_stock_detail_exists->total_stock-@$quantity : @$quantity;
                    $store_stock_detail->status = 1;
                    $store_stock_detail->save();

                    $store_stock_detail = StoreStockUpdate::where([['store_id', $request->store_id], ['product_id', $fishcutting->product_id], ['status', 1]])->orderBy('id', 'DESC')->first();
                    $store_inventory = StoreInventoryDetail::where([['store_id', $request->store_id], ['product_id', $fishcutting->product_id], ['status', 1]])->first();
                    if ($store_inventory == null) {
                        $store_inventory = new StoreInventoryDetail();
                        $store_inventory->store_id = $request->store_id;
                        $store_inventory->product_id = $fishcutting->product_id;
                    }
                    $store_inventory->weight = @$store_inventory->weight-@$quantity;
                    $store_inventory->status = 1;
                    $store_inventory->save();
                }
            }

            if (count($grouped_products) > 0) {
                foreach ($grouped_products as $key => $grouped_product) {
                    $fish_cutting_data = FishCuttingDetail::where([['fish_cutting_id', $fishcutting->id], ['product_id', $grouped_product->product_id]])->orderBy('id', 'DESC')->first();
                    if ($fish_cutting_data == null) {
                        $fish_cutting_data = new FishCuttingDetail();
                    }
                    $fish_cutting_data->fish_cutting_id = $fishcutting->id;
                    $fish_cutting_data->product_id = $grouped_product->product_id;
                    $fish_cutting_data->status = 1;
                    $fish_cutting_data->remarks = $request->remarks;
                    $group_quantity = (($new_weight * $grouped_product->percentage) / 100);
                    $quantity = $group_quantity;

                    if ($grouped_product->type == 'slice') {
                        $fish_cutting_data->slice = $group_quantity;
                    } else if ($grouped_product->type == 'head') {
                        $fish_cutting_data->head = $group_quantity;
                    } else if ($grouped_product->type == 'tail') {
                        $fish_cutting_data->tail = $group_quantity;
                    } else if ($grouped_product->type == 'eggs') {
                        $fish_cutting_data->eggs = $group_quantity;
                    }
                    $fish_cutting_data->save();

                    if ($request->store_id != null && $request->store_id != "null") {
                        if ($quantity != 0) {
                            $store_stock_detail_exists = StoreStockUpdate::where([['store_id', $request->store_id], ['product_id', $grouped_product->product_id], ['status', 1]])->orderBy('id', 'DESC')->first();
                            $store_stock_detail = new StoreStockUpdate();
                            $store_stock_detail->store_id = $request->store_id;
                            $store_stock_detail->product_id = $grouped_product->product_id;
                            $store_stock_detail->reference_id = $fishcutting->id;
                            $store_stock_detail->reference_table = 10; //10 Fish Cutting table
                            $store_stock_detail->stock_update_on = Carbon::now();
                            $store_stock_detail->existing_stock = ($store_stock_detail_exists != null && $store_stock_detail_exists->total_stock != null) ? $store_stock_detail_exists->total_stock : 0;
                            $store_stock_detail->adding_stock = @$quantity;
                            $store_stock_detail->total_stock = ($store_stock_detail_exists != null && $store_stock_detail_exists->total_stock != null) ? $store_stock_detail_exists->total_stock+@$quantity : @$quantity;
                            $store_stock_detail->status = 1;
                            $store_stock_detail->save();

                            $store_stock_detail = StoreStockUpdate::where([['store_id', $request->store_id], ['product_id', $grouped_product->product_id], ['status', 1]])->orderBy('id', 'DESC')->first();
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

                $fish_cutting_data->save();
            }
        } else {
            $new_weight = $request->weight - $old_weight;
            $quantity = $new_weight;

            if ($request->store_id != null && $request->store_id != "null") {
                if ($quantity != 0) {
                    $store_stock_detail_exists = StoreStockUpdate::where([['store_id', $request->store_id], ['product_id', $request->product_id], ['status', 1]])->orderBy('id', 'DESC')->first();
                    $store_stock_detail = new StoreStockUpdate();
                    $store_stock_detail->store_id = $request->store_id;
                    $store_stock_detail->product_id = $request->product_id;
                    $store_stock_detail->reference_id = $fishcutting->id;
                    $store_stock_detail->reference_table = 10; //10 Fish Cutting table
                    $store_stock_detail->stock_update_on = Carbon::now();
                    $store_stock_detail->existing_stock = ($store_stock_detail_exists != null && $store_stock_detail_exists->total_stock != null) ? $store_stock_detail_exists->total_stock : 0;
                    $store_stock_detail->adding_stock = @$quantity;
                    $store_stock_detail->total_stock = ($store_stock_detail_exists != null && $store_stock_detail_exists->total_stock != null) ? $store_stock_detail_exists->total_stock+@$quantity : @$quantity;
                    $store_stock_detail->status = 1;
                    $store_stock_detail->save();
                }
                $store_stock_detail = StoreStockUpdate::where([['store_id', $request->store_id], ['product_id', $request->product_id], ['status', 1]])->orderBy('id', 'DESC')->first();
                $store_inventory = StoreInventoryDetail::where([['store_id', $request->store_id], ['product_id', $request->product_id], ['status', 1]])->first();
                if ($store_inventory == null) {
                    $store_inventory = new StoreInventoryDetail();
                    $store_inventory->store_id = $request->store_id;
                    $store_inventory->product_id = $request->product_id;
                }
                $store_inventory->weight = @$store_inventory->weight+@$quantity;
                $store_inventory->status = 1;
                $store_inventory->save();
            }
        }
        DB::commit();

        if ($request->submission_type == 1) {
            return redirect()
                ->route('admin.fish-cutting.index')
                ->with('success', 'Fish Cutting Update Successfully');
        } elseif ($request->submission_type == 2) {
            return back()->with('success', 'Fish Cutting Update Successfully');
        }
        // } catch (\Exception $e) {
        //     Log::error($e);
        //     DB::rollback();
        //     return back()->withInput()->with('error', 'Fish Cutting Fail');
        // }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
