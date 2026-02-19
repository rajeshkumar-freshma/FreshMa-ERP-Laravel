<?php

namespace App\Http\Controllers\Admin\Store;

use App\Core\CommonComponent;
use App\DataTables\Store\DailyStoreStockUpdateDataTable;
use App\Http\Controllers\Controller;
use App\Http\Requests\Store\StoreStockDailyUpdateFormRequest;
use App\Models\Adjustment;
use App\Models\AdjustmentDetail;
use App\Models\Admin;
use App\Models\MisMatchingAdjustment;
use App\Models\MisMatchingAdjustmentDetail;
use App\Models\Product;
use App\Models\Staff;
use App\Models\Store;
use App\Models\StoreInventoryDetail;
use App\Models\StoreStockDailyUpdate;
use App\Models\StoreStockUpdate;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;

class DailyStoreStockUpdateController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(DailyStoreStockUpdateDataTable $dataTable)
    {
        $data['store'] = Store::all();
        $data['products'] = Product::all();
        return $dataTable->render('pages.store.daily_stock_updated.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $data['stores'] = Store::all();
        $data['employees'] = Admin::all();
        $store_id = $request->store_id;
        $product_name = $request->product_name;
        $date = $request->stock_updated_date;
        if ($store_id !== null && $date !== null) {
            $data['product_stock_details'] = Product::where(function ($query) use ($product_name) {
                if ($product_name != null) {
                    $query->where('name', 'LIKE', '%' . $product_name . '%');
                }
            })
                ->LeftJoin('store_stock_daily_updates', function ($join) use ($store_id, $date) {
                    $join->on('store_stock_daily_updates.product_id', 'products.id')
                        ->whereIn('store_stock_daily_updates.id', function ($query) use ($store_id, $date) {
                            $query->selectRaw('MAX(id) as id')
                                ->from('store_stock_daily_updates')
                                ->whereBetween('stock_update_on', [Carbon::parse($date)->format('Y-m-d 00:00:00'), Carbon::parse($date)->format('Y-m-d 23:59:59')])
                                ->where('store_id', $store_id)
                                ->groupBy('product_id');
                        })
                        ->where('store_stock_daily_updates.store_id', $store_id);
                })
                ->LeftJoin('store_stock_updates', function ($join) use ($store_id, $date) {
                    $join->on('store_stock_updates.product_id', 'products.id')
                        ->whereIn('store_stock_updates.id', function ($query) use ($store_id, $date) {
                            $query->selectRaw('MAX(id) as id')
                                ->from('store_stock_updates')
                                ->whereBetween('stock_update_on', [Carbon::parse($date)->format('Y-m-d 00:00:00'), Carbon::parse($date)->format('Y-m-d 23:59:59')])
                                ->where('store_id', $store_id)
                                ->groupBy('product_id');
                        })
                        ->where('store_stock_updates.store_id', $store_id);
                })
                ->select(
                    'products.id',
                    'name',
                    'slug',
                    'sku_code',
                    'hsn_code',
                    DB::raw('COALESCE(closing_stock, 0) as currentstock'),  // Use COALESCE to handle null values
                    DB::raw('COALESCE(opening_stock, 0) as openingstock'),  // Use COALESCE to handle null values
                    DB::raw('COALESCE(usage_stock, 0) as usagestock'),  // Use COALESCE to handle null values
                    DB::raw('COALESCE(store_stock_updates.total_stock, 0) as exactstock')  // Use COALESCE to handle null values
                )
                ->get();
            // $data['product_stock_details'] = Product::leftJoin('store_stock_daily_updates', function ($join) use ($store_id, $date) {
            //     $join->on('store_stock_daily_updates.product_id', 'products.id')
            //         ->whereIn('store_stock_daily_updates.id', function ($query) use ($store_id, $date) {
            //             $query->selectRaw('MAX(id) as id')
            //                 ->from('store_stock_daily_updates')
            //                 ->whereBetween('stock_update_on', [Carbon::parse($date)->format('Y-m-d 00:00:00'), Carbon::parse($date)->format('Y-m-d 23:59:59')])
            //                 ->where('store_id', $store_id)
            //                 ->groupBy('product_id');
            //         })
            //         ->where('store_stock_daily_updates.store_id', $store_id);
            // })->leftJoin('store_stock_updates', function ($join) use ($store_id, $date) {
            //     $join->on('store_stock_updates.product_id', 'products.id')
            //         ->whereIn('store_stock_updates.id', function ($query) use ($store_id, $date) {
            //             $query->selectRaw('MAX(store_stock_updates.id) as id')
            //                 ->whereBetween('stock_update_on', [Carbon::parse($date)->startOfDay(), Carbon::parse($date)->endOfDay()])
            //                 ->where('store_id', $store_id)
            //                 ->groupBy('product_id');
            //         })
            //         ->where('store_stock_updates.store_id', $store_id);
            // })
            //     ->select(
            //         'products.id',
            //         'name',
            //         'slug',
            //         'sku_code',
            //         'hsn_code',
            //         DB::raw('COALESCE(closing_stock, 0) as currentstock'),  // Use COALESCE to handle null values
            //         DB::raw('COALESCE(opening_stock, 0) as openingstock'),  // Use COALESCE to handle null values
            //         DB::raw('COALESCE(usage_stock, 0) as usagestock'),  // Use COALESCE to handle null values
            //         DB::raw('COALESCE(store_stock_updates.total_stock, 0) as exactstock')  // Use COALESCE to handle null values
            //     )
            //     ->get();
        }



        $data['store_id'] = $store_id;
        $data['stock_updated_date'] = $date;
        return view('pages.store.daily_stock_updated.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     */
    // public function store(Request $request)
    // {
    //     DB::beginTransaction();

    //     // try {
    //     // return $request->all();
    //     $openingStocks = $request->input('openingstock');
    //     $closingStocks = $request->input('closingstock');
    //     $storeId = $request->input('update_store_id');
    //     $stockUpdatedDate = $request->input('stock_updated_on');
    //     $employeeId = $request->input('employee_id');
    //     $reasons = $request->input('reasons');
    //     $products = $request->input('products');
    //     $exactStockWeight = $request->input('exact_stock_weight');
    //     $submitType = $request->input('submit_type');
    //     $validProductData = [];

    //     // foreach ($products as $key => $productId) {
    //     //     $openingStock = $openingStocks[$key] ?? null;
    //     //     $closingStock = $closingStocks[$key] ?? null;

    //     //     // Check for non-null opening or closing stock values
    //     //     if ($openingStock !== null || $closingStock !== null) {
    //     //         // Store valid product data
    //     //         $validProductData[] = [
    //     //             'productId' => $productId,
    //     //             'openingStock' => $openingStock,
    //     //             'closingStock' => $closingStock,
    //     //             'key' => $key,
    //     //         ];
    //     //     }
    //     // }
    //     // return $validProductData;
    //     // foreach ($validProductData as $data) {
    //     //     Log::info($data);
    //     //     return $data;
    //     // }
    //     // Calculate $difference outside of the loop
    //     // Check if Adjustment entry exists for the current store_id and date
    //     // Calculate $difference outside of the loop
    //     $difference = 0;

    //     if ($submitType == 2) {
    //         $existingAdjustment = Adjustment::where([
    //             'store_id' => $storeId,
    //             'adjustment_date' => $stockUpdatedDate,
    //         ])->first();

    //         $existingMisMatchingAdjustment = MisMatchingAdjustment::where([
    //             'store_id' => $storeId,
    //             'adjustment_date' => $stockUpdatedDate,
    //             'employee_id' => $employeeId,
    //         ])->first();

    //         if (!$existingAdjustment) {
    //             // Create Adjustment entry only if it doesn't exist
    //             $adjustmentTrackNumber = CommonComponent::invoice_no('adjustment');
    //             $adjustmentData = Adjustment::create([
    //                 'adjustment_track_number' => $adjustmentTrackNumber,
    //                 'warehouse_id' => 1,
    //                 'store_id' => $storeId,
    //                 'authorized_person' => $employeeId ?? '',
    //                 'adjustment_date' => $stockUpdatedDate,
    //                 'total_request_quantity' => $difference,
    //                 'status' => 1,
    //                 'remarks' => $reasons ?? '',
    //             ]);
    //         } else {
    //             // Use the existing Adjustment entry
    //             $adjustmentData = $existingAdjustment;
    //         }

    //         if (!$existingMisMatchingAdjustment && $existingMisMatchingAdjustment !== null) {
    //             // Create MisMatchingAdjustment
    //             $misMatchingAdjustmentTrackNumber = CommonComponent::invoice_no('mis_matching_adjustment');
    //             $misMatchingAdjustment = MisMatchingAdjustment::create([
    //                 'tracking_number' => $misMatchingAdjustmentTrackNumber,
    //                 'store_id' => $storeId,
    //                 'employee_id' => $employeeId ?? '',
    //                 'adjustment_date' => $stockUpdatedDate,
    //                 'status' => 1,
    //                 'remarks' => $reasons ?? '',
    //             ]);
    //         } else {
    //             $misMatchingAdjustment = $existingMisMatchingAdjustment;
    //         }
    //     }


    //     // foreach ($validProductData as $data) {
    //     foreach ($products as $key => $productId) {
    //         $openingStock = $openingStocks[$key] ?? 0;
    //         $closingStock = $closingStocks[$key] ?? 0;
    //         // $difference = abs($closingStocks[0] - $exactStockWeight[0]); // Assuming these are single values
    //         $difference = abs($closingStock - $openingStock); // Assuming these are single values
    //         if ($storeId != null && $storeId != "null") {
    //             $store_stock_detail = StoreStockDailyUpdate::updateOrCreate(
    //                 [
    //                     'store_id' => $storeId,
    //                     'product_id' => $productId,
    //                     'stock_update_on' => Carbon::parse($stockUpdatedDate)->format('Y-m-d'),
    //                 ],
    //                 [
    //                     'opening_stock' => $openingStock,
    //                     'closing_stock' => $closingStock,
    //                     'usage_stock' => max(0, $openingStock - $closingStock), // Calculate usage_stock
    //                     'status' => 1,
    //                 ]
    //             );

    //             $store_stock_detail_exists = StoreStockUpdate::where([
    //                 ['store_id', $store_stock_detail->store_id],
    //                 ['product_id', $productId],
    //                 ['status', 1]
    //             ])->orderBy('id', 'DESC')->first();



    //             $product_data = Product::findOrFail($productId);
    //             $inventoryDetails = StoreInventoryDetail::where([
    //                 'product_id' => $product_data->id,
    //                 'store_id' => $storeId,
    //             ])->orderByDesc('id')->first();
    //             return $productId;

    //             if ($submitType == 2 && $difference !== 0 && $inventoryDetails !== null) {
    //                 // Update inventory weight
    //                 $inventoryDetails->weight += ($closingStock > $exactStockWeight[$key]) ? -$difference : $difference;
    //                 $inventoryDetails->save();
    //                 $store_stock_update = new StoreStockUpdate();
    //                 $store_stock_update->from_warehouse_id = 1;
    //                 $store_stock_update->store_id = $storeId;
    //                 $store_stock_update->product_id = $productId;
    //                 $store_stock_update->stock_update_on = $stockUpdatedDate;
    //                 $store_stock_update->existing_stock = ($store_stock_detail_exists != null && $store_stock_detail_exists->total_stock != null) ? $store_stock_detail_exists->total_stock : 0;
    //                 $store_stock_update->adding_stock = $openingStock ?? $closingStock;
    //                 $store_stock_update->total_stock = ($store_stock_detail_exists != null && $store_stock_detail_exists->total_stock != null) ? $store_stock_detail_exists->total_stock + $openingStock : $closingStock;
    //                 $store_stock_update->status = 1;
    //                 $store_stock_update->save();
    //                 // Update store stock
    //                 $store_stock_update->adding_stock = $difference;
    //                 $store_stock_update->total_stock += ($closingStock > $exactStockWeight[$key]) ? -$difference : $difference;
    //                 $store_stock_update->save();

    //                 // Create adjustment data
    //                 // $adjustmentData = Adjustment::create([
    //                 //     'adjustment_track_number' => $adjustment_track_number,
    //                 //     'warehouse_id' => 1,
    //                 //     'store_id' => $store_stock_update->store_id,
    //                 //     'authorized_person' => $employeeId ?? '',
    //                 //     'adjustment_date' => $stockUpdatedDate,
    //                 //     'total_request_quantity' => $stockUpdatedDate,
    //                 //     'status' => 1,
    //                 //     'remarks' => $reasons ?? '',
    //                 // ]);

    //                 $adjustmentDataProductDetails = AdjustmentDetail::create([
    //                     'adjustment_id' => $adjustmentData->id,
    //                     'product_id' => $product_data->id,
    //                     'sku_code' => $product_data->sku_code,
    //                     'name' => $product_data->name ?? '',
    //                     'type' => ($store_stock_update->total_stock > $closingStock) ? 2 : (($store_stock_update->total_stock > $openingStock) ? 1 : null),
    //                     'quantity' => $difference,
    //                     'stock' => $adjustmentData->total_request_quantity,
    //                     'status' => 1,
    //                     'remarks' => $reasons ?? '',
    //                     'type' => ($closingStock > $exactStockWeight[$key]) ? 2 : 1, // Type 2 for subtraction, Type 1 for addition
    //                 ]);



    //                 // Create MisMatchingAdjustmentDetail
    //                 MisMatchingAdjustmentDetail::create([
    //                     'mis_matching_adjustment_id' => $misMatchingAdjustment->id,
    //                     'product_id' => $product_data->id,
    //                     'sku_code' => $product_data->sku_code,
    //                     'quantity' => $difference,
    //                     'type' => ($closingStock > $exactStockWeight[$key]) ? 2 : 1, // Type 2 for subtraction, Type 1 for addition
    //                 ]);
    //             }
    //         }
    //     }

    //     DB::commit();

    //     return redirect()
    //         ->route('admin.daily-stock-update.index')
    //         ->with('success', 'Store Stock Stored Successfully');
    //     // } catch (\Exception $e) {
    //     //     Log::error($e);
    //     //     DB::rollback();
    //     //     return response()->json([
    //     //         'status' => 400,
    //     //         'message' => 'Data not found.',
    //     //     ]);
    //     // }
    // }
    public function store(Request $request)
    {
        DB::beginTransaction();

        // try {
        // return $request->all();
        $openingStocks = $request->input('openingstock');
        $closingStocks = $request->input('closingstock');
        $storeId = $request->input('update_store_id');
        $stockUpdatedDate = $request->input('stock_updated_on');
        $employeeId = $request->input('employee_id');
        $reasons = $request->input('reasons');
        $products = $request->input('products');
        $exactStockWeight = $request->input('exact_stock_weight');
        $submitType = $request->input('submit_type');

        $adjustmentData = null;
        $misMatchingAdjustment = null;

        if ($submitType == 2) {

            $adjustmentData = Adjustment::firstOrCreate([
                'store_id' => $storeId,
                'adjustment_date' => $stockUpdatedDate,
            ], [
                'adjustment_track_number' => CommonComponent::invoice_no('adjustment'),
                'warehouse_id' => 1,
                'authorized_person' => $employeeId ?? '',
                'total_request_quantity' => 0,
                'status' => 1,
                'remarks' => $reasons ?? '',
            ]);

            $misMatchingAdjustment = MisMatchingAdjustment::firstOrCreate([
                'store_id' => $storeId,
                'adjustment_date' => $stockUpdatedDate,
                'employee_id' => $employeeId,
            ], [
                'tracking_number' => CommonComponent::invoice_no('mis_matching_adjustment'),
                'status' => 1,
                'remarks' => $reasons ?? '',
            ]);
        }
        foreach ($products as $key => $productId) {
            $openingStock = $openingStocks[$key] ?? 0;
            $closingStock = $closingStocks[$key] ?? 0;
            $difference = abs($closingStock - $openingStock);

            if ($storeId != null && $storeId != "null") {

                $store_stock_detail = StoreStockDailyUpdate::updateOrCreate(
                    [
                        'store_id' => $storeId,
                        'product_id' => $productId,
                        'stock_update_on' => Carbon::parse($stockUpdatedDate)->format('Y-m-d'),
                    ],
                    [
                        'opening_stock' => $openingStock,
                        'closing_stock' => $closingStock,
                        'usage_stock' => max(0, $openingStock - $closingStock),
                        'status' => 1,
                    ]
                );
                $product_data = Product::findOrFail($productId);
                $inventoryDetails = StoreInventoryDetail::firstOrNew([
                    'product_id' => $productId,
                    'store_id' => $storeId,
                ]);
                $inventoryDetails->weight += ($closingStock > $exactStockWeight[$key]) ? -$difference : $difference;
                // Check if $exactStockWeight[$key] is set to avoid errors
                // if (isset($exactStockWeight[$key])) {
                //     // Calculate the difference between closing stock and exact stock weight
                //     $difference = abs($closingStock - $exactStockWeight[$key]);

                //     // Determine the adjustment based on the comparison between closing stock and exact stock weight
                //     $adjustment = ($closingStock > $exactStockWeight[$key]) ? -$difference : $difference;

                //     // Apply the adjustment to inventory weight
                //     $inventoryDetails->weight += $adjustment;

                //     // Save only if there's a change in weight
                //     if ($adjustment != 0) {
                //         $inventoryDetails->save();
                //     }
                // } else {
                //     // Handle case where $exactStockWeight[$key] is not set (optional)
                //     // Log an error or handle it according to your application's logic
                //     // For example:
                //     // Log::error("Exact stock weight not available for key: $key");
                // }

                $inventoryDetails->save();

                if ($submitType == 2 && $difference !== 0) {

                    $store_stock_update = new StoreStockUpdate();
                    $store_stock_update->from_warehouse_id = 1;
                    $store_stock_update->store_id = $storeId;
                    $store_stock_update->product_id = $productId;
                    $store_stock_detail->reference_id = $store_stock_detail->id;
                    $store_stock_detail->reference_table = 12; //10 Product Transfer table 
                    $store_stock_update->stock_update_on = $stockUpdatedDate;
                    $store_stock_update->existing_stock = $store_stock_detail->total_stock ?? 0;
                    $store_stock_update->adding_stock = $openingStock ?? $closingStock;
                    $store_stock_update->total_stock = ($store_stock_detail->total_stock ?? 0) + $openingStock;
                    $store_stock_update->status = 1;
                    $store_stock_update->save();

                    $store_stock_update->adding_stock = $difference;
                    $store_stock_update->total_stock += ($closingStock > $exactStockWeight[$key]) ? -$difference : $difference;
                    $store_stock_update->save();

                    $adjustmentDetail = AdjustmentDetail::create([
                        'adjustment_id' => $adjustmentData->id,
                        'product_id' => $productId,
                        'sku_code' => $product_data->sku_code,
                        'name' => $product_data->name ?? '',
                        'type' => ($closingStock > $exactStockWeight[$key]) ? 2 : 1,
                        'quantity' => $difference,
                        'stock' => $adjustmentData->total_request_quantity,
                        'status' => 1,
                        'remarks' => $reasons ?? '',
                    ]);

                    MisMatchingAdjustmentDetail::create([
                        'mis_matching_adjustment_id' => $misMatchingAdjustment->id,
                        'product_id' => $productId,
                        'sku_code' => $product_data->sku_code,
                        'quantity' => $difference,
                        'type' => ($closingStock > $exactStockWeight[$key]) ? 2 : 1,
                    ]);
                }
            }
        }

        DB::commit();

        return redirect()
            ->route('admin.daily-stock-update.index')
            ->with('success', 'Store Stock Stored Successfully');
        // } catch (\Exception $e) {
        //     Log::error($e);
        //     DB::rollback();
        //     return response()->json([
        //         'status' => 400,
        //         'message' => 'An error occurred while storing store stock.',
        //     ]);
        // }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $data['store_stock_detail'] = StoreStockDailyUpdate::find($id);
        return view('pages.store.daily_stock_updated.show', $data);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request, $id)
    {
        $data['store_stock_detail'] = StoreStockDailyUpdate::find($id);
        $data['mis_matching_adjustment'] = MisMatchingAdjustment::where([
            'store_id' => $data['store_stock_detail']->store_id,
            'adjustment_date' => $data['store_stock_detail']->stock_update_on,
        ])->first();
        $data['stores'] = Store::all();
        $data['employees'] = Admin::all();

        $store_id = $data['store_stock_detail']->store_id;
        $date = $data['store_stock_detail']->stock_update_on;

        // $data['product_stock_details'] = Product::leftJoin('store_stock_daily_updates', function ($join) use ($store_id, $date) {
        //     $join->on('store_stock_daily_updates.product_id', 'products.id')
        //         ->whereIn('store_stock_daily_updates.id', function ($query) use ($store_id, $date) {
        //             $query->selectRaw('MAX(id) as id')
        //                 ->from('store_stock_daily_updates')
        //                 ->whereBetween('stock_update_on', [Carbon::parse($date)->format('Y-m-d 00:00:00'), Carbon::parse($date)->format('Y-m-d 23:59:59')])
        //                 ->where('store_id', $store_id)
        //                 ->groupBy('product_id');
        //         })
        //         ->where('store_id', $store_id);
        // })->leftJoin('store_stock_updates', function ($join) use ($store_id, $date) {
        //     $join->on('store_stock_updates.product_id', 'products.id')
        //         ->whereIn('store_stock_updates.id', function ($query) use ($store_id, $date) {
        //             $query->selectRaw('MAX(store_stock_updates.id) as id')
        //                 ->whereBetween('store_stock_updates.stock_update_on', [
        //                     Carbon::parse($date)->startOfDay(),
        //                     Carbon::parse($date)->endOfDay()
        //                 ])
        //                 ->where('store_stock_updates.store_id', $store_id)
        //                 ->groupBy('store_stock_updates.product_id');
        //         });
        // })
        //     ->select(
        //         'products.id',
        //         'name',
        //         'slug',
        //         'sku_code',
        //         'hsn_code',
        //         DB::raw('COALESCE(closing_stock, 0) as currentstock'),  // Use COALESCE to handle null values
        //         DB::raw('COALESCE(opening_stock, 0) as openingstock'),  // Use COALESCE to handle null values
        //         DB::raw('COALESCE(usage_stock, 0) as usagestock'),  // Use COALESCE to handle null values
        //         DB::raw('COALESCE(store_stock_updates.total_stock, 0) as exactstock')  // Use COALESCE to handle null values
        //     )
        //     ->with([
        //         'store_inventory_details:id,product_id,weight',  // Specify the desired columns
        //     ])
        //     ->get();
        $data['product_stock_details'] = Product::leftJoin('store_stock_daily_updates', function ($join) use ($store_id, $date) {
            $join->on('store_stock_daily_updates.product_id', 'products.id')
                ->whereBetween('store_stock_daily_updates.stock_update_on', [Carbon::parse($date)->format('Y-m-d 00:00:00'), Carbon::parse($date)->format('Y-m-d 23:59:59')])
                ->where('store_stock_daily_updates.store_id', $store_id);
        })->leftJoin('store_stock_updates', function ($join) use ($store_id, $date) {
            $join->on('store_stock_updates.product_id', 'products.id')
                ->whereBetween('store_stock_updates.stock_update_on', [Carbon::parse($date)->startOfDay(), Carbon::parse($date)->endOfDay()])
                ->where('store_stock_updates.store_id', $store_id);
        })
            ->select(
                'products.id',
                'name',
                'slug',
                'sku_code',
                'hsn_code',
                DB::raw('COALESCE(store_stock_daily_updates.closing_stock, 0) as currentstock'),  // Use COALESCE to handle null values
                DB::raw('COALESCE(store_stock_daily_updates.opening_stock, 0) as openingstock'),  // Use COALESCE to handle null values
                DB::raw('COALESCE(store_stock_daily_updates.usage_stock, 0) as usagestock'),  // Use COALESCE to handle null values
                DB::raw('COALESCE(store_stock_updates.total_stock, 0) as exactstock')  // Use COALESCE to handle null values
            )
            ->with([
                'store_inventory_details:id,product_id,weight',  // Specify the desired columns
            ])
            ->where('store_stock_daily_updates.store_id', $store_id) // Additional WHERE condition to filter null values
            ->orWhere('store_stock_updates.store_id', $store_id) // Additional WHERE condition to filter null values
            ->get();



        $data['store_id'] = $store_id;
        $data['stock_updated_date'] = $date;

        return view('pages.store.daily_stock_updated.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        DB::beginTransaction();

        // try {
        // return $request->all();
        $openingStocks = $request->input('openingstock');
        $closingStocks = $request->input('closingstock');
        $storeId = $request->input('update_store_id');
        $stockUpdatedDate = $request->input('stock_updated_on');
        $employeeId = $request->input('employee_id');
        $reasons = $request->input('reasons');
        $products = $request->input('products');
        $exactStockWeight = $request->input('exact_stock_weight');
        $submitType = $request->input('submit_type');

        $difference = 0;
        if ($submitType == 2) {
            $existingAdjustment = Adjustment::where([
                'store_id' => $storeId,
                'adjustment_date' => $stockUpdatedDate,
            ])->first();
            $existingMisMatchingadjustment = MisMatchingAdjustment::where([
                'store_id' => $storeId,
                'adjustment_date' => $stockUpdatedDate,
                'employee_id' => $employeeId,
            ])->first();
            if (!$existingAdjustment) {

                $adjustmentData = Adjustment::updateOrCreate(
                    [
                        // 'adjustment_track_number' => $adjustment_track_number, // You may want to generate a unique tracking number
                        'warehouse_id' => 1,
                        'store_id' => $storeId,
                        'authorized_person' => $employeeId ?? '',
                        'adjustment_date' => $stockUpdatedDate,
                        'total_request_quantity' => $difference,
                        'status' => 1,
                        'remarks' => $reasons ?? '',
                    ]
                );
            } else {
                // Use the existing Adjustment entry
                $adjustmentData = $existingAdjustment;
            }
            if (!$existingMisMatchingadjustment) {
                // Create or update mis-matching adjustment data
                $misMatchingAdjustment = MisMatchingAdjustment::updateOrCreate(
                    [
                        // 'tracking_number' => $mis_matching_adjustment_track_number, // You may want to generate a unique tracking number
                        'store_id' => $storeId,
                        'employee_id' => $employeeId ?? '',
                        'adjustment_date' => $stockUpdatedDate,
                        'status' => 1,
                        'remarks' => $reasons ?? '',
                    ]
                );
            } else {
                $misMatchingadjustment = $existingMisMatchingadjustment;
            }
        }
        foreach ($products as $key => $data) {
            $openingStock = $openingStocks[$key] ?? 0;
            $closingStock = $closingStocks[$key] ?? 0;

            // $difference = abs($closingStocks[0] - $exactStockWeight[0]); // Assuming these are single values
            $difference = abs($closingStock - $openingStock); // Assuming these are single values
            if ($storeId != null && $storeId != "null") {
                $productId = $data;
                $openingStock = $openingStocks[$key] ?? 0;
                $closingStock = $closingStocks[$key] ?? 0;

                if ($storeId != null && $storeId != "null") {
                    $store_stock_detail = StoreStockDailyUpdate::findOrNew($id);
                    $product_data = Product::findOrFail($productId);
                    $inventoryDetails = StoreInventoryDetail::where([
                        'product_id' => $product_data->id,
                        'store_id' => $storeId,
                    ])->orderByDesc('id')->first();

                    if ($submitType == 2 && $difference !== 0) {
                        // Update inventory weight
                        $inventoryDetails->weight += ($closingStock > $exactStockWeight[$key]) ? -$difference : $difference;
                        $inventoryDetails->save();
                        // Update store stock
                        $store_stock_detail->opening_stock = $openingStock;
                        $store_stock_detail->closing_stock = $closingStock;
                        $store_stock_detail->usage_stock = max(0, $openingStock - $closingStock);
                        $store_stock_detail->status = 1;
                        $store_stock_detail->save();

                        // Create or update store stock update
                        $store_stock_update = StoreStockUpdate::updateOrCreate(
                            [
                                'store_id' => $storeId,
                                'product_id' => $productId,
                                'stock_update_on' => Carbon::parse($stockUpdatedDate)->format('Y-m-d'),
                            ],
                            [
                                'from_warehouse_id' => 1,
                                'existing_stock' => $inventoryDetails->weight,
                                'adding_stock' => $difference,
                                'reference_id' => $store_stock_detail->id,
                                'reference_table' => 12, //12 Daily Stock Update table 
                                'total_stock' => $inventoryDetails->weight + $difference,
                                'status' => 1,
                            ]
                        );
                        $adjustment_track_number = CommonComponent::invoice_no('adjustment');
                        $mis_matching_adjustment_track_number = CommonComponent::invoice_no('mis_matching_adjustment');
                        // Create or update adjustment data

                        // Create or update adjustment detail
                        AdjustmentDetail::updateOrCreate(
                            [
                                'adjustment_id' => $adjustmentData->id,
                                'product_id' => $product_data->id,
                            ],
                            [
                                'sku_code' => $product_data->sku_code,
                                'name' => $product_data->name ?? '',
                                'type' => ($store_stock_update->total_stock > $closingStock) ? 2 : (($store_stock_update->total_stock > $openingStock) ? 1 : null),
                                'quantity' => $difference,
                                'stock' => $adjustmentData->total_request_quantity,
                                'status' => 1,
                                'remarks' => $reasons ?? '',
                            ]
                        );

                        // Create or update mis-matching adjustment data
                        $misMatchingAdjustment = MisMatchingAdjustment::updateOrCreate(
                            [
                                'tracking_number' => $mis_matching_adjustment_track_number, // You may want to generate a unique tracking number
                                'store_id' => $store_stock_update->store_id,
                                'employee_id' => $employeeId ?? '',
                                'adjustment_date' => $stockUpdatedDate,
                                'status' => 1,
                                'remarks' => $reasons ?? '',
                            ]
                        );

                        // Create or update mis-matching adjustment detail
                        MisMatchingAdjustmentDetail::updateOrCreate(
                            [
                                'mis_matching_adjustment_id' => $misMatchingAdjustment->id,
                                'product_id' => $product_data->id,
                            ],
                            [
                                'sku_code' => $product_data->sku_code,
                                'quantity' => $difference,
                                'type' => ($closingStock > $exactStockWeight[$key]) ? 2 : 1,
                            ]
                        );
                    }
                }
            }
        }

        DB::commit();

        return redirect()
            ->route('admin.daily-stock-update.index')
            ->with('success', 'Store Stock Update Successfully');
        // } catch (\Exception $e) {
        //     Log::error($e);
        //     DB::rollback();
        //     return response()->json([
        //         'status' => 400,
        //         'message' => 'Data not found.',
        //     ]);
        // }
    }
    //     public function update(Request $request, $id)
    // {
    //     DB::beginTransaction();

    //     try {
    //         $openingStocks = $request->input('openingstock');
    //         $closingStocks = $request->input('closingstock');
    //         $storeId = $request->input('store_store_id');
    //         $stockUpdatedDate = $request->input('stock_updated_on');
    //         $employeeId = $request->input('employee_id');
    //         $reasons = $request->input('reasons');
    //         $products = $request->input('products');
    //         $exactStockWeight = $request->input('exact_stock_weight');
    //         $submitType = $request->input('submit_type');

    //         $difference = 0;
    //         if ($submitType == 2) {
    //             $existingAdjustment = Adjustment::where([
    //                 'store_id' => $storeId,
    //                 'adjustment_date' => $stockUpdatedDate,
    //             ])->first();
    //             $existingMisMatchingadjustment = MisMatchingAdjustment::where([
    //                 'store_id' => $storeId,
    //                 'adjustment_date' => $stockUpdatedDate,
    //                 'employee_id' => $employeeId,
    //             ])->first();

    //             if (!$existingAdjustment) {
    //                 // Create adjustment if not exist
    //                 $adjustmentData = Adjustment::create([
    //                     'store_id' => $storeId,
    //                     'adjustment_date' => $stockUpdatedDate,
    //                     'adjustment_track_number' => CommonComponent::invoice_no('adjustment'),
    //                     'warehouse_id' => 1,
    //                     'authorized_person' => $employeeId ?? '',
    //                     'total_request_quantity' => $difference,
    //                     'status' => 1,
    //                     'remarks' => $reasons ?? '',
    //                 ]);
    //             } else {
    //                 // Use the existing Adjustment entry
    //                 $adjustmentData = $existingAdjustment;
    //             }

    //             if (!$existingMisMatchingadjustment) {
    //                 // Create mis-matching adjustment if not exist
    //                 $misMatchingAdjustment = MisMatchingAdjustment::create([
    //                     'store_id' => $storeId,
    //                     'adjustment_date' => $stockUpdatedDate,
    //                     'employee_id' => $employeeId ?? '',
    //                     'tracking_number' => CommonComponent::invoice_no('mis_matching_adjustment'),
    //                     'status' => 1,
    //                     'remarks' => $reasons ?? '',
    //                 ]);
    //             } else {
    //                 $misMatchingadjustment = $existingMisMatchingadjustment;
    //             }
    //         }

    //         foreach ($products as $key => $data) {
    //             $openingStock = $openingStocks[$key] ?? 0;
    //             $closingStock = $closingStocks[$key] ?? 0;

    //             // Calculate difference
    //             $difference = abs($closingStock - $openingStock);

    //             if ($storeId != null && $storeId != "null") {
    //                 $productId = $data;

    //                 // Update or create store stock detail
    //                 $store_stock_detail = StoreStockDailyUpdate::updateOrCreate(
    //                     [
    //                         'store_id' => $storeId,
    //                         'product_id' => $productId,
    //                         'stock_update_on' => Carbon::parse($stockUpdatedDate)->format('Y-m-d'),
    //                     ],
    //                     [
    //                         'opening_stock' => $openingStock,
    //                         'closing_stock' => $closingStock,
    //                         'usage_stock' => max(0, $openingStock - $closingStock),
    //                         'status' => 1,
    //                     ]
    //                 );

    //                 // Fetch product data
    //                 $product_data = Product::findOrFail($productId);

    //                 // Update or create inventory details
    //                 $inventoryDetails = StoreInventoryDetail::updateOrCreate(
    //                     [
    //                         'product_id' => $productId,
    //                         'store_id' => $storeId,
    //                     ],
    //                     [
    //                         'weight' => ($closingStock > $exactStockWeight[$key]) ? -$difference : $difference,
    //                     ]
    //                 );

    //                 if ($submitType == 2 && $difference !== 0) {
    //                     // Update store stock update
    //                     $store_stock_update = StoreStockUpdate::updateOrCreate(
    //                         [
    //                             'store_id' => $storeId,
    //                             'product_id' => $productId,
    //                             'stock_update_on' => Carbon::parse($stockUpdatedDate)->format('Y-m-d'),
    //                         ],
    //                         [
    //                             'from_warehouse_id' => 1,
    //                             'existing_stock' => $inventoryDetails->weight,
    //                             'adding_stock' => $difference,
    //                             'total_stock' => $inventoryDetails->weight + $difference,
    //                             'status' => 1,
    //                         ]
    //                     );

    //                     // Create adjustment detail
    //                     AdjustmentDetail::create([
    //                         'adjustment_id' => $adjustmentData->id,
    //                         'product_id' => $productId,
    //                         'sku_code' => $product_data->sku_code,
    //                         'name' => $product_data->name ?? '',
    //                         'type' => ($closingStock > $exactStockWeight[$key]) ? 2 : 1,
    //                         'quantity' => $difference,
    //                         'stock' => $adjustmentData->total_request_quantity,
    //                         'status' => 1,
    //                         'remarks' => $reasons ?? '',
    //                     ]);

    //                     // Create mis-matching adjustment detail
    //                     MisMatchingAdjustmentDetail::create([
    //                         'mis_matching_adjustment_id' => $misMatchingAdjustment->id,
    //                         'product_id' => $productId,
    //                         'sku_code' => $product_data->sku_code,
    //                         'quantity' => $difference,
    //                         'type' => ($closingStock > $exactStockWeight[$key]) ? 2 : 1,
    //                     ]);
    //                 }
    //             }
    //         }

    //         DB::commit();

    //         return redirect()
    //             ->route('admin.daily-stock-update.index')
    //             ->with('success', 'Store Stock Update Successfully');
    //     } catch (\Exception $e) {
    //         Log::error($e);
    //         DB::rollback();
    //         return response()->json([
    //             'status' => 400,
    //             'message' => 'Data not found.',
    //         ]);
    //     }
    // }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function actualStock($id)
    {
        $data = $common_data = $this->sales_overview($id);
        $data['sales_details'] = StoreStockDailyUpdate::where('sales_order_id', $id)->orderBy('id', 'desc')->paginate(10);
        return view('pages.store.daily_stock_update.sales_product', $data);
    }

    public function productInventoryDetails($id)
    {
        $data = $common_data = $this->sales_overview($id);
        $data['sales_details'] = StoreStockDailyUpdate::where('sales_order_id', $id)->orderBy('id', 'desc')->paginate(10);
        return view('pages.store.daily_stock_update.sales_product', $data);
    }
}
