<?php

namespace App\Http\Controllers\Api\Product;

use App\Core\CommonComponent;
use App\Http\Controllers\Controller;
use App\Models\MachineData;
use App\Models\Product;
use App\Models\ProductPrice;
use App\Models\ProductPriceHistory;
use App\Models\ProductTransfer;
use App\Models\StoreInventoryDetail;
use App\Models\StoreStockDailyUpdate;
use App\Models\StoreStockUpdate;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ProductController extends Controller
{
    public function productpricelist(Request $request)
    {
        try {
            if ($request->store_id != null) {
                $store_id = array($request->store_id);
            } else {
                $store_id = Auth::user()->user_stores();
            }
            $product_name = $request->prodcut_name;
            $date = $request->date;
            $can_proceed_last_date = $request->can_proceed_last_date;

            $product_prices = Product::where(function ($query) use ($product_name) {
                $query->where('name', 'LIKE', '%' . $product_name . '%')
                    ->orWhere('sku_code', 'LIKE', '%' . $product_name . '%');
            })
                ->LeftJoin('product_price_histories', function ($join) use ($store_id, $date, $can_proceed_last_date) {
                    if (Auth::user()->user_type == 1) {
                        if ($date != null && $can_proceed_last_date == 0) {
                            $join->on('product_price_histories.product_id', 'products.id')->whereNull('product_price_histories.deleted_at')
                                ->whereIn('product_price_histories.id', function ($query) use ($date) {
                                    $query->selectRaw('max(id) as id')->from('product_price_histories')->whereDate('product_price_histories.price_update_date', $date)->where('status', 1)->groupBy('product_id');
                                });
                        } else {
                            $join->on('product_price_histories.product_id', 'products.id')->whereNull('product_price_histories.deleted_at')
                                ->whereIn('product_price_histories.id', function ($query) {
                                    $query->selectRaw('max(id) as id')->from('product_price_histories')->where('status', 1)->groupBy('product_id');
                                });
                        }
                    } else {
                        if ($date != null) {
                            $join->on('product_price_histories.product_id', 'products.id')->whereNull('product_price_histories.deleted_at')
                                ->whereIn('product_price_histories.id', function ($query) use ($store_id, $date) {
                                    $query->selectRaw('max(id) as id')->from('product_price_histories')->whereDate('product_price_histories.price_update_date', $date)->whereIn('store_id', $store_id)->where('status', 1)->groupBy('product_id');
                                });
                        } else {
                            $join->on('product_price_histories.product_id', 'products.id')->whereNull('product_price_histories.deleted_at')
                                ->whereIn('product_price_histories.id', function ($query) use ($store_id) {
                                    $query->selectRaw('max(id) as id')->from('product_price_histories')->whereIn('store_id', $store_id)->where('status', 1)->groupBy('product_id');
                                });
                        }
                    }
                })
                ->select('products.id', 'name', 'sku_code', DB::raw('(CASE WHEN price is NULL THEN price ELSE price END) as price'))
                ->orderby('id', 'ASC')
                ->paginate(30);

            $today_price_details = ProductPriceHistory::where(function ($query) use ($store_id, $date) {
                if ($date != null) {
                    $query->whereDate('price_update_date', $date);
                }
            })
                ->where('status', 1)
                ->groupBy('product_id')
                ->count();

            $is_today_price_updated = $today_price_details > 0 ? 1 : 0;
            return response()->json([
                'status' => 200,
                'datas' => $product_prices,
                'is_today_price_updated' => $is_today_price_updated,
                'message' => 'Product Price fetched successfully.',
            ]);
        } catch (\Exception $e) {
            Log::error($e);
            DB::rollback();
            return response()->json([
                'status' => 400,
                'message' => 'Data not found.',
            ]);
        }
    }

    public function productpriceupdate(Request $request)
    {
        DB::beginTransaction();
        try {
            if (Auth::user()->user_type == 1) {
                $machine_datas = MachineData::get();
            } else {
                $machine_datas = MachineData::where('store_id', $request->store_id)->get();
            }

            $can_proceed_last_date = $request->can_proceed_last_date;
            if ($request->store_id != null) {
                $store_id = array($request->store_id);
            } else {
                $store_id = Auth::user()->user_stores();
            }

            $user_input_product = json_decode($request->products);
            if (isset($request->products) && $can_proceed_last_date == 0) {
                $products = json_decode($request->products);
            } else {
                // new
                /** */
                $product_ids = [];
                foreach ($user_input_product as $user_input_products) {
                    $product_ids[] = $user_input_products->product_id;
                }
                /** */
                $products = Product::LeftJoin('product_price_histories', function ($join) use ($store_id, $can_proceed_last_date) {
                    if (Auth::user()->user_type == 1) {
                        $join->on('product_price_histories.product_id', 'products.id')->whereNull('product_price_histories.deleted_at')
                            ->whereIn('product_price_histories.id', function ($query) {
                                $query->selectRaw('max(id) as id')->from('product_price_histories')->where('status', 1)->groupBy('product_id');
                            });
                    } else {
                        $join->on('product_price_histories.product_id', 'products.id')->whereNull('product_price_histories.deleted_at')
                            ->whereIn('product_price_histories.id', function ($query) use ($store_id) {
                                $query->selectRaw('max(id) as id')->from('product_price_histories')->whereIn('store_id', $store_id)->where('status', 1)->groupBy('product_id');
                            });
                    }
                })
                    ->select('products.id', 'name', DB::raw('(CASE WHEN price is NULL THEN price ELSE price END) as price'))
                    ->orderby('id', 'ASC')
                    ->whereIn('products.id', $product_ids) // new
                    ->get();
            }

            if (count($products) > 0) {
                foreach ($products as $key => $product) {
                    foreach ($machine_datas as $key => $machine_data) {
                        if ($can_proceed_last_date == 1) {
                            $user_input_object = collect($user_input_product)->first(function ($user_product) use ($product) {
                                return $user_product->product_id == $product->id;
                            });

                            if ($user_input_object) {
                                // User inputing object
                                $product_id = $user_input_object->product_id;
                                $amount = $user_input_object->amount;
                            } else {
                                // previous date object
                                $product_id = $product->id;
                                $amount = $product->price;
                            }
                        } else {
                            // User inputing object
                            $product_id = $product->product_id;
                            $amount = $product->amount;
                        }

                        $product_price_checks = ProductPrice::where([['store_id', $machine_data->Slno], ['product_id', $product_id]])->first();
                        if (Auth::user()->user_type == 1) {
                            if ($product_price_checks == null) {
                                $product_branch_prices = new ProductPrice();
                                $product_branch_prices->product_id = $product_id;
                                $product_branch_prices->store_id = $machine_data->Slno != null ? $machine_data->Slno : $machine_data->id;
                                $product_branch_prices->price = $amount;
                                $product_branch_prices->price_update_date = date('Y-m-d');
                                $product_branch_prices->status = 1;
                                $product_branch_prices->created_by = Auth::user()->id;
                                $product_branch_prices->updated_by = Auth::user()->id;
                                $product_branch_prices->save();
                            } else {
                                $product_branch_prices = $product_price_checks;
                                $product_branch_prices->price = $amount;
                                $product_branch_prices->price_update_date = date('Y-m-d');
                                $product_branch_prices->save();
                            }

                            $product_branch_price_history = new ProductPriceHistory();
                            $product_branch_price_history->product_id = $product_branch_prices->product_id;
                            $product_branch_price_history->store_id = $product_branch_prices->store_id;
                            $product_branch_price_history->price = $product_branch_prices->price;
                            $product_branch_price_history->price_update_date = $product_branch_prices->price_update_date;
                            $product_branch_price_history->status = $product_branch_prices->status;
                            $product_branch_price_history->created_by = $product_branch_prices->created_by;
                            $product_branch_price_history->updated_by = $product_branch_prices->updated_by;
                            $product_branch_price_history->save();
                        } else {
                            $product_branch_price_history = new ProductPriceHistory();
                            $product_branch_price_history->product_id = $product_id;
                            $product_branch_price_history->store_id = $machine_data->Slno != null ? $machine_data->Slno : $machine_data->id;
                            $product_branch_price_history->price = $amount;
                            $product_branch_price_history->price_update_date = date('Y-m-d');
                            $product_branch_price_history->status = 1;
                            $product_branch_price_history->created_by = Auth::user()->id;
                            $product_branch_price_history->updated_by = Auth::user()->id;
                            $product_branch_price_history->save();
                        }
                    }
                }
            }

            DB::commit();
            return response()->json([
                'status' => 200,
                'message' => 'Product Price Updated Successfully.',
            ]);
        } catch (\Exception $e) {
            Log::error($e);
            DB::rollback();
            return response()->json([
                'status' => 400,
                'message' => 'Data not found.',
            ]);
        }
    }

    public function storeproductstock(Request $request)
    {
        // try {
        $store_id = $request->store_id;
        $date = $request->date;
        $product_name = $request->prodcut_name;

        /* if (Auth::user()->user_type == 1) {
        $product_stock_details = Product::where(function ($query) use ($product_name) {
        if ($product_name != null) {
        $query->where('name', 'LIKE', '%' . $product_name . '%');
        }
        })
        ->LeftJoin('store_inventory_details', function ($join) use ($store_id) {
        $join->on('store_inventory_details.product_id', 'products.id')->where('store_id', $store_id);
        })
        ->select('products.id', 'name', 'slug', 'sku_code', 'hsn_code', 'weight as currentstock')
        ->paginate(20)
        ->through(function ($product_stock_details) use ($store_id, $date) {
        $stock_details = Product::storeproductstockdetails($product_stock_details->id, $store_id, $date);
        $product_stock_details['openingstock'] = $stock_details != null ? $stock_details->openingstock : 0.00;
        $product_stock_details['usage_stock'] = ($stock_details != null ? $stock_details->openingstock : 0.00) - $product_stock_details->currentstock;
        return $product_stock_details;
        });
        } else { */

        $dateStart = Carbon::parse($date)->startOfDay()->format('Y-m-d H:i:s');
        $dateEnd = Carbon::parse($date)->endOfDay()->format('Y-m-d H:i:s');

        $product_stock_details = Product::when($product_name, function ($query, $product_name) {
            return $query->where('products.name', 'LIKE', '%' . $product_name . '%');
        })
        // ->leftJoin('store_stock_daily_updates as s1', function ($join) use ($dateStart,$dateEnd,$store_id) {
        //     $join->on('products.id', '=', 's1.product_id')
        //         ->where('store_id', $store_id)
        //         ->whereBetween('s1.stock_update_on',[$dateStart,$dateEnd]);
        // })
        // ->leftJoinSub(
        //     StoreStockDailyUpdate::select('store_id', 'product_id', DB::raw('MAX(stock_update_on) as last_record_date'))
        //         ->groupBy('store_id', 'product_id'),
        //     's2',
        //     function ($join) {
        //         $join->on('products.id', '=', 's2.product_id')
        //              ->on('s1.store_id', '=', 's2.store_id')
        //              ->on('s1.stock_update_on', '=', 's2.last_record_date');
        //     }
        // )
        // ->leftJoin('store_stock_daily_updates as ssdu', function ($join) use ($store_id) {
        //     $join->on('ssdu.product_id', 'products.id')
        //         ->whereIn('ssdu.id', function ($query) use ($store_id) {
        //             $query->selectRaw('MAX(ssdu1.id) as id')
        //                 ->from('store_stock_daily_updates as ssdu1')
        //                 ->where('ssdu1.store_id', $store_id)
        //                 ->groupBy('ssdu1.product_id');
        //         })
        //         ->where('ssdu.store_id', $store_id);
        // })
        // ->leftJoin('store_stock_daily_updates as ssdu', function ($join) use ($store_id) {
        //     $join->on('ssdu.product_id', 'products.id')
        //         ->whereIn('ssdu.id', function ($query) use ($store_id) {
        //             $query->selectRaw('MAX(ssdu1.id) as id')
        //                 ->from('store_stock_daily_updates as ssdu1')
        //                 ->where('ssdu1.store_id', $store_id)
        //                 ->groupBy('ssdu1.product_id');
        //         })
        //         ->where('ssdu.store_id', $store_id);
        // })
        // ->leftJoin('store_stock_daily_updates as ssdu', function ($join) use ($store_id, $dateStart, $dateEnd) {
        //     $join->on('ssdu.product_id', 'products.id')
        //         ->where(function ($query) use ($store_id, $dateStart, $dateEnd) {
        //             $query->whereBetween('ssdu.stock_update_on', [$dateStart, $dateEnd])
        //                   ->where('ssdu.store_id', $store_id);
        //         })
        //         ->orWhere(function ($query) use ($store_id) {
        //             $query->whereIn('ssdu.id', function ($subquery) use ($store_id) {
        //                 $subquery->selectRaw('MAX(ssdu1.id) as id')
        //                          ->from('store_stock_daily_updates as ssdu1')
        //                          ->where('ssdu1.store_id', $store_id)
        //                          ->groupBy('ssdu1.product_id');
        //             })
        //             ->where('ssdu.store_id', $store_id);
        //         })
        //         ->select('opening_stock');
        // })
            ->leftJoin('store_stock_daily_updates as ssdu', function ($join) use ($store_id, $dateStart, $dateEnd) {
                $join->on('ssdu.product_id', 'products.id')
                    ->whereIn('ssdu.id', function ($query) use ($store_id, $dateStart, $dateEnd) {
                        $query->selectRaw('MAX(ssdu1.id) as id')
                            ->from('store_stock_daily_updates as ssdu1')
                        // ->whereBetween('stock_update_on', [$dateStart, $dateEnd])
                            ->where('ssdu1.store_id', $store_id)
                            ->groupBy('ssdu1.product_id');
                    })
                    ->where('ssdu.store_id', $store_id)
                    ->select('ssdu.opening_stock');
            })
            ->leftJoin('store_stock_daily_updates as ssdu2', function ($join) use ($store_id, $dateStart, $dateEnd) {
                $join->on('ssdu2.product_id', '=', 'products.id')
                    ->where('ssdu2.store_id', '=', $store_id)
                    ->whereBetween('ssdu2.stock_update_on', [$dateStart, $dateEnd])
                    ->select('closing_stock');
            })
        // ->leftJoin('store_stock_updates as ssu', function ($join) use ($store_id, $dateStart, $dateEnd) {
        //     $join->on('ssu.product_id', 'products.id')
        //         ->where(function ($query) use ($store_id, $dateStart, $dateEnd) {
        //             $query->whereBetween('ssu.stock_update_on', [$dateStart, $dateEnd])
        //                   ->where('ssu.store_id', $store_id);
        //         })
        //         ->orWhere(function ($query) use ($store_id) {
        //             $query->whereIn('ssu.id', function ($subquery) use ($store_id) {
        //                 $subquery->selectRaw('MAX(ssu1.id) as id')
        //                          ->from('store_stock_updates as ssu1')
        //                          ->where('ssu1.store_id', $store_id)
        //                          ->groupBy('ssu1.product_id');
        //             })
        //             ->where('ssu.store_id', $store_id);
        //         })
        //         ->select('total_stock');
        // })
            ->leftJoin('store_stock_updates as sst1', function ($join) use ($store_id, $dateStart, $dateEnd) {
                $join->on('sst1.product_id', 'products.id')
                    ->whereIn('sst1.id', function ($query) use ($store_id, $dateStart, $dateEnd) {
                        $query->selectRaw('MAX(sst.id) as id')
                            ->from('store_stock_updates as sst')
                            ->where('sst.store_id', $store_id)
                        // ->whereBetween('s1.stock_update_on',[$dateStart,$dateEnd])
                            ->groupBy('sst.product_id');
                    })
                    ->where('sst1.store_id', $store_id);
            })
        // ->select('products.*', DB::raw('COALESCE(s1.quantity, 0) as stock_quantity'))
        // ->get();
        // ->leftJoin('store_stock_daily_updates', function ($join) use ($store_id, $dateStart, $dateEnd) {
        //     $join->on('store_stock_daily_updates.product_id', 'products.id')
        //         ->whereIn('store_stock_daily_updates.id', function ($query) use ($store_id, $dateStart, $dateEnd) {
        //             $query->selectRaw('MAX(id) as id')
        //                 ->from('store_stock_daily_updates')
        //                 ->whereBetween('stock_update_on', [$dateStart, $dateEnd])
        //                 ->where('store_id', $store_id)
        //                 ->groupBy('product_id');
        //         })
        //         ->where('store_id', $store_id);
        // })
        // ->leftJoin('store_indent_request_details', function($join) use ($store_id, $dateStart, $dateEnd) {
        //     $join->on('store_indent_request_details.product_id', 'products.id')
        //         ->leftJoin('store_indent_requests', 'store_indent_request_details.store_indent_request_id', '=', 'store_indent_requests.id')
        //         ->whereIn('store_indent_request_details.id', function ($query) use ($store_id, $dateStart, $dateEnd) {
        //             $query->selectRaw('MAX(store_indent_request_details.id) as id')
        //                 ->from('store_indent_request_details')
        //                 // ->leftJoin('store_indent_requests', 'store_indent_request_details.store_indent_request_id', '=', 'store_indent_requests.id')
        //                 ->whereBetween('store_indent_requests.expected_date', [$dateStart, $dateEnd])
        //                 ->where('store_indent_requests.store_id', $store_id)
        //                 ->groupBy('store_indent_request_details.product_id');
        //         })
        //         ->where('store_indent_requests.store_id', $store_id);
        // })
            ->select('products.id', 'products.name', 'slug', 'products.sku_code', 'hsn_code', 'ssdu.opening_stock', 'ssdu2.closing_stock', 'total_stock')
            ->paginate(20)
            ->through(function ($product) {
                $product->opening_stock = $product->opening_stock;
                $product->closing_stock = $product->closing_stock;
                $product->total_stock = $product->total_stock;
                return $product;
            });

        // foreach ($product_stock_details as $product) {
        //     $openingstock = $product->currentstock + $product->given_quantity;
        //     $product->openingstock = $openingstock;
        // }
        return response()->json([
            'status' => 200,
            'datas' => $product_stock_details,
            'message' => 'Store Stock fetched successfully.',
        ]);

        // } catch (\Exception $e) {
        //     Log::error($e);
        //     DB::rollback();
        //     return response()->json([
        //         'status' => 400,
        //         'message' => 'Data not found.',
        //     ]);
        // }
    }

    public function storeproductstockhistory(Request $request)
    {
        try {
            $product_id = $request->product_id;
            $store_id = $request->store_id;
            $date = $request->date;

            $product = Product::select('id', 'name', 'slug')->find($product_id);

            $product_stock_details = StoreStockUpdate::where(function ($query) use ($product_id, $store_id, $date) {
                if ($date != null) {
                    $query->whereDate('stock_update_on', $date);
                }
                if ($product_id != null) {
                    $query->where('product_id', $product_id);
                }
                if ($store_id != null) {
                    $query->where('store_id', $store_id);
                }
            })
                ->with('warehouse_details:id,name,code', 'store_details:id,store_name,store_code,phone_number,gst_number', 'product_details:id,name,slug,sku_code')
                ->paginate(20);

            return response()->json([
                'status' => 200,
                'product' => $product,
                'product_stock_details' => $product_stock_details,
                'message' => 'Product Price fetched successfully.',
            ]);
        } catch (\Exception $e) {
            Log::error($e);
            DB::rollback();
            return response()->json([
                'status' => 400,
                'message' => 'Data not found.',
            ]);
        }
    }

    public function storestockadjustment(Request $request)
    {
        try {
            $quantity = $request->adjustment_weight;
            $store_stock_detail_exists = StoreStockUpdate::where([['store_id', $request->store_id], ['product_id', $request->product_id], ['status', 1]])->orderBy('id', 'DESC')->first();
            $store_stock_detail = new StoreStockUpdate();
            $store_stock_detail->store_id = $request->store_id;
            $store_stock_detail->product_id = $request->product_id;
            $store_stock_detail->stock_update_on = Carbon::now();
            $store_stock_detail->existing_stock = ($store_stock_detail_exists != null && $store_stock_detail_exists->total_stock != null) ? $store_stock_detail_exists->total_stock : 0;
            $store_stock_detail->adding_stock = @$quantity;
            $store_stock_detail->total_stock = ($store_stock_detail_exists != null && $store_stock_detail_exists->total_stock != null) ? $store_stock_detail_exists->total_stock+@$quantity : @$quantity;
            $store_stock_detail->status = 1;
            $store_stock_detail->save();

            $store_stock_detail = StoreStockUpdate::where([['store_id', $request->store_id], ['product_id', $request->product_id], ['status', 1]])->orderBy('id', 'DESC')->first();
            $store_inventory = StoreInventoryDetail::where([['store_id', $request->store_id], ['product_id', $request->product_id]])->first();
            if ($store_inventory == null) {
                $store_inventory = new StoreInventoryDetail();
                $store_inventory->store_id = $request->store_id;
                $store_inventory->product_id = $request->product_id;
            }
            $store_inventory->weight = @$store_inventory->weight+@$store_stock_detail->adding_stock;
            $store_inventory->status = 1;
            $store_inventory->save();

            return response()->json([
                'status' => 200,
                'message' => 'Quantity Updated successfully.',
            ]);
        } catch (\Exception $e) {
            Log::error($e);
            DB::rollback();
            return response()->json([
                'status' => 400,
                'message' => 'Data not found.',
            ]);
        }
    }

    public function storeproductstockupdate(Request $request)
    {
        try {
            if (isset($request->products)) {
                $products = json_decode($request->products);
                if (count($products) > 0) {
                    $dateStart = Carbon::parse($request->stock_update_on)->startOfDay()->format('Y-m-d H:i:s');
                    $dateEnd = Carbon::parse($request->stock_update_on)->endOfDay()->format('Y-m-d H:i:s');
                    foreach ($products as $key => $product) {
                        if ($request->store_id != null && $request->store_id != "null") {
                            /* if (Auth::user()->user_type == 1) {
                            if ($request->is_open == 1) {
                            $quantity = $product->opening_stock;
                            } else {
                            $quantity = -$product->closing_stock;
                            }
                            if ($quantity != 0) {
                            $store_stock_detail_exists = StoreStockUpdate::where([['store_id', $request->store_id], ['product_id', $product->product_id], ['status', 1]])->orderBy('id', 'DESC')->first();
                            $store_stock_detail = new StoreStockUpdate();
                            $store_stock_detail->store_id = $request->store_id;
                            $store_stock_detail->product_id = $product->product_id;
                            $store_stock_detail->stock_update_on = $request->stock_update_on;
                            $store_stock_detail->existing_stock = ($store_stock_detail_exists != null && $store_stock_detail_exists->total_stock != null) ? $store_stock_detail_exists->total_stock : 0;
                            $store_stock_detail->adding_stock = @$quantity;
                            $store_stock_detail->total_stock = ($store_stock_detail_exists != null && $store_stock_detail_exists->total_stock != null) ? $store_stock_detail_exists->total_stock + @$quantity : @$quantity;
                            $store_stock_detail->status = 1;
                            $store_stock_detail->save();

                            $store_stock_detail = StoreStockUpdate::where([['store_id', $request->store_id], ['product_id', $product->product_id], ['status', 1]])->orderBy('id', 'DESC')->first();
                            $store_inventory = StoreInventoryDetail::where([['store_id', $request->store_id], ['product_id', $product->product_id]])->first();
                            if ($store_inventory == null) {
                            $store_inventory = new StoreInventoryDetail();
                            $store_inventory->store_id = $request->store_id;
                            $store_inventory->product_id = $product->product_id;
                            }
                            $store_inventory->weight = @$store_inventory->weight + @$store_stock_detail->adding_stock;
                            $store_inventory->status = 1;
                            $store_inventory->save();
                            }
                            } else { */
                            $store_stock_detail = StoreStockDailyUpdate::where([['store_id', $request->store_id], ['product_id', $product->product_id]])->whereBetween('stock_update_on', [$dateStart, $dateEnd])->orderBy('id', 'DESC')->first();
                            $product_data = Product::findOrfail($product->product_id);
                            $opening_stock = 0;
                            $closing_stock = $product->closing_stock;
                            if ($store_stock_detail == null) {
                                $store_stock_detail = new StoreStockDailyUpdate();
                                $store_stock_detail->store_id = $request->store_id;
                            }
                            $store_stock_detail->product_id = $product_data->id;
                            $store_stock_detail->stock_update_on = $request->stock_update_on;
                            if ($request->is_open == 1) {
                                $store_stock_detail->opening_stock = @$opening_stock;

                            } else {
                                $store_stock_detail->closing_stock = @$closing_stock;
                                $store_stock_detail->usage_stock = $store_stock_detail->closing_stock ? $store_stock_detail->opening_stock - $store_stock_detail->closing_stock : 0;
                            }
                            $store_stock_detail->status = 1;
                            $store_stock_detail->save();

                            /* } */
                        }
                    }
                    return response()->json([
                        'status' => 200,
                        'message' => 'Quantity Updated successfully.',
                    ]);
                }
            }
            return response()->json([
                'status' => 200,
                'message' => 'Kindly choose products.',
            ]);
        } catch (\Exception $e) {
            Log::error($e);
            DB::rollback();
            return response()->json([
                'status' => 400,
                'message' => 'Data not found.',
            ]);
        }
    }

    public function todayreceivedstock(Request $request)
    {
        $store_id = $request->store_id;
        $date = $request->date;

        return $product_transfer = ProductTransfer::where(function ($query) use ($store_id) {
            $query->where(function ($query) use ($store_id) {
                if ($store_id != null) {
                    $query->whereNotNull('from_warehouse_id')->where('to_store_id', $store_id);
                }
            })
                ->Orwhere(function ($query) use ($store_id) {
                    if ($store_id != null) {
                        $query->OrwhereNotNull('from_store_id')->where('to_store_id', $store_id);
                    }
                });
        })
            ->where(function ($query) use ($date) {
                if ($date != null) {
                    $datebetween = CommonComponent::dateformatwithtime($date, $date);
                    $query->whereBetween('transfer_created_date', $datebetween);
                }
            })
            ->with(
                'product_details',
                'product_details.product:id,name,slug,sku_code,hsn_code',
                'product_details.unit_details:id,unit_name,unit_short_code,allow_decimal,operator,operation_value',
                'from_warehouse:id,name,slug,code',
                'to_warehouse:id,name,slug,code',
                'from_store:id,store_name,slug,store_code',
                'to_store:id,store_name,slug,store_code'
            )
            ->get();
    }
}
