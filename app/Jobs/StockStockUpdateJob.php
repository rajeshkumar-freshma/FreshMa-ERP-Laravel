<?php

namespace App\Jobs;

use App\Models\Product;
use App\Models\Store;
use App\Models\StoreInventoryDetail;
use App\Models\StoreStockUpdate;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class StockStockUpdateJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Log::info('Stock Update Job Started');
        DB::beginTransaction();
        try {
            $productList = Product::where('status', 1)->get();
            $storeList = Store::where('status', 1)->get();

            foreach ($productList as $key => $product) {
                foreach ($storeList as $key => $store) {
                    $quantity = 0;
                    $store_stock_detail_exists = StoreStockUpdate::where([['store_id', $store->id], ['product_id', $product->id], ['status', 1]])->orderBy('id', 'DESC')->first();
                    $store_stock_detail = new StoreStockUpdate();
                    $store_stock_detail->store_id = $store->id;
                    $store_stock_detail->product_id = $product->id;
                    $store_stock_detail->stock_update_on = Carbon::now();
                    $store_stock_detail->existing_stock = ($store_stock_detail_exists != null && $store_stock_detail_exists->total_stock != null) ? $store_stock_detail_exists->total_stock : 0;
                    $store_stock_detail->adding_stock = @$quantity;
                    $store_stock_detail->total_stock = ($store_stock_detail_exists != null && $store_stock_detail_exists->total_stock != null) ? $store_stock_detail_exists->total_stock + @$quantity : @$quantity;
                    $store_stock_detail->status = 1;
                    $store_stock_detail->save();

                    $store_stock_detail = StoreStockUpdate::where([['store_id', $store->id], ['product_id', $product->id], ['status', 1]])->orderBy('id', 'DESC')->first();
                    $store_inventory = StoreInventoryDetail::where([['store_id', $store->id], ['product_id', $product->id]])->first();
                    if ($store_inventory == null) {
                        $store_inventory = new StoreInventoryDetail();
                        $store_inventory->store_id = $store->id;
                        $store_inventory->product_id = $product->id;
                    }
                    $store_inventory->weight = @$store_inventory->weight + @$store_stock_detail->adding_stock;
                    $store_inventory->status = 1;
                    $store_inventory->save();
                }
            }
            DB::commit();

            Log::info('Stock Update Job completed');
        } catch (\Exception $e) {
            Log::error($e);
            DB::rollback();
        }
    }
}
