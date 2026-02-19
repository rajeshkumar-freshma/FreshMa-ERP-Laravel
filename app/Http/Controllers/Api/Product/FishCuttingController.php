<?php

namespace App\Http\Controllers\Api\Product;

use App\Http\Controllers\Controller;
use App\Models\FishCutting;
use App\Models\FishCuttingDetail;
use App\Models\FishCuttingProductMap;
use App\Models\StoreInventoryDetail;
use App\Models\StoreStockUpdate;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class FishCuttingController extends Controller
{
    public function fishcuttinglist(Request $request)
    {
        // try {
        $store_id = $request->store_id;
        $product_id = $request->product_id;
        $weight = $request->weight;
        $from_date = $request->from_date;
        $to_date = $request->to_date;

        // Query to fetch FishCutting records with conditions
        $fishCuttings = FishCutting::where(function ($query) use ($store_id, $product_id, $weight, $from_date, $to_date) {
            if ($store_id) {
                $query->where('store_id', $store_id);
            }
            if ($product_id) {
                $query->where('product_id', $product_id);
            }
            if ($weight) {
                $query->where('weight', $weight);
            }
            if ($from_date && $to_date) {
                $query->whereBetween('cutting_date', [
                    Carbon::parse($from_date)->startOfDay(),
                    Carbon::parse($to_date)->endOfDay(),
                ]);
            }
        })
        // ->with(['store:id,store_name,store_code,phone_number']) // Eager load store details
        // ->with(['product:id,name,sku_code']) // Eager load product details
        // ->with(['fish_cutting_details_many:id,fish_cutting_id,product_id,slice,head,tail,eggs,wastage']) // Eager load 'fish_cutting_details_many' with selected fields
            ->orderBy('id', 'DESC')
            ->get();

        // Transform the collection to retrieve only the required fields
        $transformedData = [];
        if ($fishCuttings != null) {

            foreach ($fishCuttings as $fishcutting) {
                $product = $fishcutting->product;
                $groupedProduct = $product ? $product->fish_cutting_grouped : null;
                $wastagePercentage = $groupedProduct ? $groupedProduct->wastage_percentage : 0;
                $wastageValue = number_format(($fishcutting->weight * $wastagePercentage) / 100, 3);
                $transformedFishcutting = [
                    'id' => $fishcutting->id,
                    "product_id" => $fishcutting->product_id,
                    "store_id" => $fishcutting->store_id,
                    "weight" => $fishcutting->weight,
                    "wastage_percentage" => $fishcutting->wastage_percentage,
                    "cutter_id" => $fishcutting->cutter_id,
                    "cutting_date" => $fishcutting->cutting_date,
                    "status" => $fishcutting->status,
                    "created_by" => $fishcutting->created_by,
                    "updated_by" => $fishcutting->updated_by,
                    "remarks" => $fishcutting->remarks,
                    "created_at" => $fishcutting->created_at,
                    "updated_at" => $fishcutting->updated_at,
                    "deleted_at" => $fishcutting->deleted_at,
                    'store' => [
                        'id' => $fishcutting->store->id,
                        'store_name' => $fishcutting->store->store_name,
                        'store_code' => $fishcutting->store->store_code,
                        'phone_number' => $fishcutting->store->phone_number,
                    ],
                    'product' => [
                        'id' => $fishcutting->product->id,
                        'name' => $fishcutting->product->name,
                        'sku_code' => $fishcutting->product->sku_code,
                    ],
                    'wastage' => [
                        'value' => number_format($wastageValue, 3),
                    ],
                    'fish_cutting_details_many' => [],
                ];

                foreach ($fishcutting->fish_cutting_details_many as $detail) {
                    Log::info("detaildetaildetail");
                    Log::info($detail);
                    // Initialize detail array with common fields
                    $transformedDetail = [
                        'product_name' => $detail->product->name,
                    ];

                    // Determine the type and value based on non-null columns
                    if ($detail->slice != null && $detail->slice > 0.000) {
                        $transformedDetail['value'] = $detail->slice;
                        $transformedDetail['type'] = 'slice';
                    } elseif ($detail->head != null && $detail->head > 0.000) {
                        $transformedDetail['value'] = $detail->head;
                        $transformedDetail['type'] = 'head';
                    } elseif ($detail->tail != null && $detail->tail > 0.000) {
                        $transformedDetail['value'] = $detail->tail;
                        $transformedDetail['type'] = 'tail';
                    } elseif ($detail->eggs != null && $detail->eggs > 0.000) {
                        $transformedDetail['value'] = $detail->eggs;
                        $transformedDetail['type'] = 'eggs';
                    } elseif ($detail->wastage != null && $detail->wastage > 0.000) {
                        $transformedDetail['value'] = $detail->wastage;
                        $transformedDetail['type'] = 'wastage';
                    }

                    // Add the transformed detail to the fishcutting
                    $transformedFishcutting['fish_cutting_details_many'][] = $transformedDetail;
                }

                // Add the transformed fishcutting to the result array
                $transformedData[] = $transformedFishcutting;
            }

            // Return JSON response with the transformed data
            return response()->json([
                'status' => 200,
                'datas' => $transformedData,
                'message' => 'Fish Cutting Details fetched successfully.',
            ]);
        } else {
            return response()->json([
                'status' => 200,
                'message' => 'There is no fish Product and also no product fish cutting mapping',
            ]);
        }
        // } catch (\Exception $e) {
        //     Log::error($e);
        //     return response()->json([
        //         'status' => 400,
        //         'message' => 'Data not found.',
        //     ]);
        // }
    }

    public function fishcuttingstore(Request $request)
    {
        DB::beginTransaction();
        try {
            $fishcutting = new FishCutting();
            $fishcutting->weight = $request->weight;
            $fishcutting->product_id = $request->product_id;
            $fishcutting->store_id = $request->store_id;
            $fishcutting->cutting_date = $request->cutting_date;
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
                $store_inventory = StoreInventoryDetail::where([['store_id', $store_id], ['product_id', $product_id], ['status', 1]])->orderBy('id', 'DESC')->first();

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

                        $quantitys = (float) $quantity;
                        $percentage = (float) $grouped_product->percentage;

                        Log::info("Before Calculation - Quantity: " . $quantitys . ", Percentage: " . $percentage);

                        $group_quantity = (($quantitys * $percentage) / 100) * -1;

                        Log::info("Calculated group_quantity: " . $group_quantity);
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
                            $store_inventory = StoreInventoryDetail::where([['store_id', $store_id], ['product_id', $grouped_product->product_id], ['status', 1]])->orderBy('id', 'DESC')->first();
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
            return response()->json([
                'status' => 200,
                'message' => 'Data Stored successfully.',
            ]);
        } catch (\Exception $e) {
            Log::error($e);
            DB::rollback();
            return response()->json([
                'status' => 400,
                'message' => 'There are some Technical Issue, Kindly contact Admin.',
            ]);
        }
    }

    public function fishcuttingupdate(Request $request)
    {
        DB::beginTransaction();
        try {
            $fishcutting = FishCutting::findOrFail($request->fishcutting_id);
            $old_weight = $fishcutting->weight;

            $fishcutting->weight = $request->weight;
            $fishcutting->product_id = $fishcutting->product_id;
            $fishcutting->store_id = $request->store_id;
            $fishcutting->cutting_date = $request->cutting_date;
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
                   // Determine the group quantity
                    if ($old_weight === $request->weight) {
                        $group_quantitys = $old_weight;
                    } elseif ($old_weight < $request->weight) {
                        $group_quantitys = $request->weight;
                    } else { // $old_weight > $request->weight
                        $group_quantitys = $request->weight;
                    }

                    foreach ($grouped_products as $key => $grouped_product) {
                        Log::info('grouped_productgrouped_productgrouped_product');
                        Log::info($group_quantitys);
                        $fish_cutting_data = FishCuttingDetail::where([['fish_cutting_id', $fishcutting->id], ['product_id', $grouped_product->product_id]])->orderBy('id', 'DESC')->first();
                        Log::info('fish_cutting_datafish_cutting_datafish_cutting_data');
                        Log::info($fish_cutting_data);
                        Log::info($group_quantitys);
                        if ($fish_cutting_data == null) {
                            $fish_cutting_data = new FishCuttingDetail();
                        }
                        $fish_cutting_data->fish_cutting_id = $fishcutting->id;
                        $fish_cutting_data->product_id = $grouped_product->product_id;
                        $fish_cutting_data->status = 1;
                        $fish_cutting_data->remarks = $request->remarks;

                        $group_quantity = (($group_quantitys * $grouped_product->percentage) / 100);
                        $quantity = $group_quantity;

                        if ($grouped_product->type == 1) { //'slice'
                            Log::info('slice');
                            Log::info($group_quantity);
                            $fish_cutting_data->slice = $group_quantity;
                        } else if ($grouped_product->type == 2) { //'head'|
                            Log::info('head');
                            Log::info($group_quantity);
                            $fish_cutting_data->head = $group_quantity;
                        } else if ($grouped_product->type == 3) { //'tail'
                            Log::info('tail');
                            Log::info($group_quantity);
                            $fish_cutting_data->tail = $group_quantity;
                        } else if ($grouped_product->type == 4) { //'eggs'
                            Log::info('eggs');
                            Log::info($group_quantity);
                            $fish_cutting_data->eggs = $group_quantity;
                        }
                        $fish_cutting_data->save();

                        Log::info('after fish_cutting_data');
                        Log::info($fish_cutting_data);
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

                    // $fish_cutting_data->save();
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

            return response()->json([
                'status' => 200,
                'message' => 'Data Stored successfully.',
            ]);
        } catch (\Exception $e) {
            Log::error($e);
            DB::rollback();
            return response()->json([
                'status' => 400,
                'message' => 'There are some Technical Issue, Kindly contact Admin.',
            ]);
        }
    }

}
