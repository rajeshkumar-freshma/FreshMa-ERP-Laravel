<?php

namespace App\Http\Controllers\Admin\Product;

use App\DataTables\Product\DailyFishProductPriceUpdateDataTable;
use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\Product;
use App\Models\ProductPrice;
use App\Models\ProductPriceHistory;
use App\Models\Store;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class DailyFishProductPriceUpdateController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(DailyFishProductPriceUpdateDataTable $dataTable)
    {
        $data['store'] = Store::all();
        $data['products'] = Product::all();
        return $dataTable->render('pages.product.daily_fish_price_update.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $data['stores'] = Store::all();
        $data['employees'] = Admin::all();

        $can_proceed_previous_date = $request->can_proceed_previous_date ?? 0;
        $store_id = $request->store_id;

        if ($can_proceed_previous_date == 1) {
            $date = null;
        } else {
            $date = $request->price_updated_date;
        }

        if ($store_id !== null && $date !== null && $can_proceed_previous_date == 0 && ($store_id !== 'all')) {
            $data['product_price_details'] = Product::leftJoin('product_prices', function ($join) use ($store_id, $date) {
                $join->on('product_prices.product_id', 'products.id')
                    ->whereIn('product_prices.id', function ($query) use ($store_id, $date) {
                        $query->selectRaw('MAX(id) as id')
                            ->from('product_prices')
                            ->whereBetween('product_prices.price_update_date', [
                                Carbon::parse($date)->startOfDay()->toDateTimeString(),
                                Carbon::parse($date)->endOfDay()->toDateTimeString(),
                            ])
                            ->where('product_prices.store_id', $store_id)
                            ->groupBy('product_prices.product_id');
                    })
                    ->where('store_id', $store_id);
            })
                ->select(
                    'products.id',
                    'name',
                    'slug',
                    'sku_code',
                    'hsn_code',
                    DB::raw('COALESCE(price, 0) as price')
                )
                ->get();
        } elseif ($can_proceed_previous_date == 1 && $store_id !== null && $date === null && ($store_id !== 'all')) {
            $data['product_price_details'] = Product::leftJoin('product_price_histories', function ($join) use ($store_id) {
                $join->on('product_price_histories.product_id', 'products.id')
                    ->whereIn('product_price_histories.id', function ($query) use ($store_id) {
                        $query->selectRaw('MAX(id) as id')
                            ->from('product_price_histories')
                            ->where('product_price_histories.store_id', $store_id)
                            ->groupBy('product_price_histories.product_id');
                    })
                    ->where('store_id', $store_id);
            })
                ->select(
                    'products.id',
                    'name',
                    'slug',
                    'sku_code',
                    'hsn_code',
                    DB::raw('COALESCE(price, 0) as price')
                )
                ->orderBy('id', 'ASC')
                ->get();
        } elseif ($can_proceed_previous_date == 1 && $store_id !== null && $date === null && $store_id === 'all') {

            $store_id = Store::where('status', 1)->pluck('id')->toArray();
            $data['product_price_details'] = Product::leftJoin('product_price_histories', function ($join) use ($store_id) {
                $join->on('product_price_histories.product_id', 'products.id')
                    ->whereIn('product_price_histories.id', function ($query) use ($store_id) {
                        $query->selectRaw('MAX(id) as id')
                            ->from('product_price_histories')
                            ->whereIn('product_price_histories.store_id', $store_id)
                            ->groupBy('product_price_histories.product_id');
                    })
                    ->whereIn('store_id', $store_id);
            })
                ->select(
                    'products.id',
                    'name',
                    'slug',
                    'sku_code',
                    'hsn_code',
                    DB::raw('COALESCE(price, 0) as price')
                )
                ->orderBy('id', 'ASC')
                ->get();
            $store_id = 'all';
        } elseif ($store_id !== null && $date !== null && $can_proceed_previous_date == 0 && $store_id === 'all') {
            $store_id = Store::where('status', 1)->pluck('id')->toArray();
            $data['product_price_details'] = Product::leftJoin('product_prices', function ($join) use ($store_id, $date) {
                $join->on('product_prices.product_id', 'products.id')
                    ->whereIn('product_prices.id', function ($query) use ($store_id, $date) {
                        $query->selectRaw('MAX(id) as id')
                            ->from('product_prices')
                            ->whereBetween('product_prices.price_update_date', [
                                Carbon::parse($date)->startOfDay()->toDateTimeString(),
                                Carbon::parse($date)->endOfDay()->toDateTimeString(),
                            ])
                            ->whereIn('product_prices.store_id', $store_id)
                            ->groupBy('product_prices.product_id');
                    })
                    ->whereIn('store_id', $store_id);
            })
                ->select(
                    'products.id',
                    'name',
                    'slug',
                    'sku_code',
                    'hsn_code',
                    DB::raw('COALESCE(price, 0) as price')
                )
                ->get();
            $store_id = 'all';
        }

        $data['store_id'] = $store_id;
        $data['price_updated_date'] = $date;

        return view('pages.product.daily_fish_price_update.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     */
    // public function store(DailyFishPriceUpdateFormRequest $request)
    // public function store(Request $request)
    // {
    //     DB::beginTransaction();
    //     try {

    //         // $products = [];
    //         // $prices = [];

    //         // foreach ($requestData['products'] as $key => $productId) {
    //         //     $price = $requestData['prices'][$key];
    //         //     if (!empty($price)) {
    //         //         $products[] = $productId;
    //         //         $prices[] = $price;
    //         //     }

    //         // }
    //         $store_id = $request->update_store_id;
    //         $price_updated_at = $request->price_updated_on;
    //         $products = $request->products;
    //         $prices = $request->prices;
    //         // return $products;

    //         if (count($products) > 0) {
    //             foreach ($products as $key => $product) {
    //                 // foreach ($machine_datas as $key => $machine_data) {
    //                 $product_id = $product;
    //                 $amount = $prices[$key] ?? 0;

    //                 $product_price_checks = ProductPrice::where([['store_id', $store_id], ['product_id', $product_id]])->whereBetween('product_prices.price_update_date', [
    //                     Carbon::parse($price_updated_at)->startOfDay()->toDateTimeString(),
    //                     Carbon::parse($price_updated_at)->endOfDay()->toDateTimeString()
    //                 ])->first();

    //                 if ($product_price_checks == null) {
    //                     Log::info("Product Price Insert entered Start");
    //                     $product_branch_prices = new ProductPrice();
    //                     $product_branch_prices->product_id = $product_id;
    //                     $product_branch_prices->store_id = $store_id ?? null;
    //                     $product_branch_prices->price = $amount;
    //                     $product_branch_prices->price_update_date = Carbon::now()->toDateTimeString();
    //                     $product_branch_prices->status = 1;
    //                     $product_branch_prices->created_by = Auth::user()->id;
    //                     $product_branch_prices->updated_by = Auth::user()->id;
    //                     $product_branch_prices->save();
    //                     Log::info("Product Price Insert entered End");
    //                 } else {
    //                     $product_branch_prices = $product_price_checks;
    //                     $product_branch_prices->price = $amount;
    //                     // $product_branch_prices->price_update_date = date('Y-m-d');
    //                     $product_branch_prices->save();
    //                 }

    //                 $product_branch_price_history = new ProductPriceHistory();
    //                 $product_branch_price_history->product_id = $product_branch_prices->product_id;
    //                 $product_branch_price_history->store_id = $product_branch_prices->store_id;
    //                 $product_branch_price_history->price = $product_branch_prices->price;
    //                 $product_branch_price_history->price_update_date = Carbon::parse($product_branch_prices->price_update_date)->toDateTimeString();
    //                 $product_branch_price_history->status = $product_branch_prices->status;
    //                 $product_branch_price_history->created_by = $product_branch_prices->created_by;
    //                 $product_branch_price_history->updated_by = $product_branch_prices->updated_by;
    //                 $product_branch_price_history->save();
    //             }
    //         }

    //         DB::commit();
    //         if ($request->submission_type == 1) {
    //             return redirect()->route('admin.fish-price-update.index')->with('success', 'Daily Fish Price Stored Successfully');
    //         } elseif ($request->submission_type == 2) {
    //             return back()->with('success', 'Daily Fish Price Stored Successfully');
    //         }
    //     } catch (\Exception $e) {
    //         Log::error($e);
    //         DB::rollback();
    //         return back()->with('danger', 'Sales Order Return Stored Fail.');
    //     }
    // }
    // public function store(Request $request)
    // {
    //     // DB::beginTransaction();
    //     // try {
    //     // return $request;
    //     $store_id = $request->update_store_id;
    //     $price_updated_at = $request->price_updated_on;
    //     $products = $request->products;
    //     $prices = $request->prices;

    //     if (count($products) > 0) {
    //         if ($store_id !== 'all') {
    //             foreach ($products as $key => $product) {
    //                 $product_id = $product;
    //                 $amount = $prices[$key] ?? 0;

    //                 // Check if a price update for the same product and store on the same date exists
    //                 $product_price_checks = ProductPrice::where([
    //                     ['store_id', $store_id],
    //                     ['product_id', $product_id],
    //                 ])->whereDate('price_update_date', Carbon::parse($price_updated_at)->toDateString())->first();
    //                 if (is_null($product_price_checks)) {
    //                     // If no price update exists, insert a new price record
    //                     $product_branch_prices = new ProductPrice();
    //                     $product_branch_prices->product_id = $product_id;
    //                     $product_branch_prices->store_id = $store_id ?? null;
    //                     $product_branch_prices->price = $amount;
    //                     $product_branch_prices->price_update_date = Carbon::now()->toDateTimeString();
    //                     $product_branch_prices->status = 1;
    //                     $product_branch_prices->created_by = Auth::user()->id;
    //                     $product_branch_prices->updated_by = Auth::user()->id;
    //                     $product_branch_prices->save();
    //                 } else {

    //                     // If a price update exists, update the existing record
    //                     $product_branch_prices = $product_price_checks;
    //                     $product_branch_prices->price = $amount;
    //                     $product_branch_prices->save();
    //                 }

    //                 // Store the price update history
    //                 $product_branch_price_history = new ProductPriceHistory();
    //                 $product_branch_price_history->product_id = $product_branch_prices->product_id;
    //                 $product_branch_price_history->store_id = $product_branch_prices->store_id;
    //                 $product_branch_price_history->price = $product_branch_prices->price;
    //                 $product_branch_price_history->price_update_date = $product_branch_prices->price_update_date;
    //                 $product_branch_price_history->status = $product_branch_prices->status;
    //                 $product_branch_price_history->created_by = $product_branch_prices->created_by;
    //                 $product_branch_price_history->updated_by = $product_branch_prices->updated_by;
    //                 $product_branch_price_history->save();
    //             }
    //         } elseif ($store_id == 'all') {
    //             $store_ids = Store::where('status', 1)->pluck('id')->toArray();

    //             foreach ($products as $key => $product) {
    //                 foreach ($store_ids as $store_id) {
    //                     $product_id = $product;
    //                     $amount = $prices[$key] ?? 0;

    //                     // Check if a price update for the same product and store on the same date exists
    //                     $product_price_checks = ProductPrice::where([
    //                         ['store_id', $store_id],
    //                         ['product_id', $product_id],
    //                     ])->whereDate('price_update_date', Carbon::parse($price_updated_at)->toDateString())->first();

    //                     if ($product_price_checks == null) {
    //                         // If no price update exists, insert a new price record
    //                         $product_branch_prices = new ProductPrice();
    //                         $product_branch_prices->product_id = $product_id;
    //                         $product_branch_prices->store_id = $store_id ?? null;
    //                         $product_branch_prices->price = $amount;
    //                         $product_branch_prices->price_update_date = Carbon::now()->toDateTimeString();
    //                         $product_branch_prices->status = 1;
    //                         $product_branch_prices->created_by = Auth::user()->id;
    //                         $product_branch_prices->updated_by = Auth::user()->id;
    //                         $product_branch_prices->save();
    //                     } else {
    //                         // If a price update exists, update the existing record
    //                         $product_branch_prices = $product_price_checks;
    //                         $product_branch_prices->price = $amount;
    //                         $product_branch_prices->save();
    //                     }

    //                     // Store the price update history
    //                     $product_branch_price_history = new ProductPriceHistory();
    //                     $product_branch_price_history->product_id = $product_branch_prices->product_id;
    //                     $product_branch_price_history->store_id = $product_branch_prices->store_id;
    //                     $product_branch_price_history->price = $product_branch_prices->price;
    //                     $product_branch_price_history->price_update_date = $product_branch_prices->price_update_date;
    //                     $product_branch_price_history->status = $product_branch_prices->status;
    //                     $product_branch_price_history->created_by = $product_branch_prices->created_by;
    //                     $product_branch_price_history->updated_by = $product_branch_prices->updated_by;
    //                     $product_branch_price_history->save();
    //                 }
    //             }
    //         }
    //     }

    //     DB::commit();
    //     // ProductPriceUpdateJob::dispatch($product_id)->delay(now()->addMinutes(0));
    //     if ($request->submission_type == 1) {
    //         return redirect()->route('admin.fish-price-update.index')->with('success', 'Daily Fish Price Stored Successfully');
    //     } elseif ($request->submission_type == 2) {
    //         return back()->with('success', 'Daily Fish Price Stored Successfully');
    //     }
    //     // } catch (\Exception $e) {
    //     //     Log::error($e);
    //     //     DB::rollback();
    //     //     return back()->with('danger', 'Sales Order Return Stored Fail.');
    //     // }
    // }
    public function store(Request $request)
    {
        DB::beginTransaction();
        try {
            $storeId = $request->update_store_id;
            $priceUpdatedAt = $request->price_updated_on;
            $products = $request->products;
            $prices = $request->prices;
    
            if (empty($products)) {
                DB::commit();
                return redirect()->back()->with('info', 'No products provided.');
            }
    
            $priceUpdateDate = Carbon::parse($priceUpdatedAt)->toDateString();
            $userId = Auth::user()->id;
    
            // Determine store IDs
            $storeIds = $storeId === 'all'
                ? Store::where('status', 1)->pluck('id')->toArray()
                : [$storeId];
    
            foreach ($products as $key => $productId) {
                $amount = $prices[$key] ?? 0;
    
                foreach ($storeIds as $storeId) {
                    // Check if the price record exists
                    $productPrice = ProductPrice::where([
                        ['store_id', $storeId],
                        ['product_id', $productId],
                    ])->whereDate('price_update_date', $priceUpdateDate)
                      ->first();
    
                    if ($productPrice) {
                        // Update existing price record
                        $productPrice->price = $amount;
                        $productPrice->updated_by = $userId;
                        $productPrice->save();
                    } else {
                        // Insert new price record
                        $productPrice = new ProductPrice();
                        $productPrice->product_id = $productId;
                        $productPrice->store_id = $storeId;
                        $productPrice->price = $amount;
                        $productPrice->price_update_date = $priceUpdateDate;
                        $productPrice->status = 1;
                        $productPrice->created_by = $userId;
                        $productPrice->updated_by = $userId;
                        $productPrice->save();
                    }
    
                    // Always store the price update history
                    $productPriceHistory = new ProductPriceHistory();
                    $productPriceHistory->product_id = $productPrice->product_id;
                    $productPriceHistory->store_id = $productPrice->store_id;
                    $productPriceHistory->price = $productPrice->price;
                    $productPriceHistory->price_update_date = $productPrice->price_update_date;
                    $productPriceHistory->status = $productPrice->status;
                    $productPriceHistory->created_by = $productPrice->created_by;
                    $productPriceHistory->updated_by = $productPrice->updated_by;
                    $productPriceHistory->save();
                }
            }
    
            DB::commit();
    
            $message = 'Daily Fish Price Stored Successfully';
            if ($request->submission_type == 1) {
                return redirect()->route('admin.fish-price-update.index')->with('success', $message);
            } elseif ($request->submission_type == 2) {
                return back()->with('success', $message);
            }
    
        } catch (\Exception $e) {
            DB::rollback();
            Log::error($e);
            return back()->with('danger', 'An error occurred while storing the prices.');
        }
    }
    

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $data['daily_fish_price_dates'] = ProductPrice::find($id);
        return view('pages.product.daily_fish_price_update.show', $data);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request, $id)
    {
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
