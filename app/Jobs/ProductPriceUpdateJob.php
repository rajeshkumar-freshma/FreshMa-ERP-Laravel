<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

use App\Models\MachineData;
use App\Models\Product;
use App\Models\ProductPrice;
use App\Models\ProductPriceHistory;
use Log;
use Illuminate\Support\Facades\DB;

class ProductPriceUpdateJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Log::info('Product Price Update Job Executing');
        DB::beginTransaction();
        try {
                $productList = Product::get();

                foreach ($productList as $key => $product) {
                    $machine_datas = MachineData::get();
                    foreach ($machine_datas as $key => $machine_data) {
                        $product_price_checks = ProductPrice::where([['store_id', $machine_data->Slno], ['product_id', $product->id]])->orderByDesc('id')->first();
                        // $product_price_checks = ProductPrice::where([['store_id', $machine_data->Slno],['product_id', $product->id], ['price_update_date', date('Y-m-d')]])->first();

                        $live_product_data = DB::connection('sqlsrv_ease')->table('PLU_MC')->where('pluno', $product->id)->first();
                        if ($product_price_checks == null) {
                            // ProductPrice::where([['store_id', $machine_data->Slno],['product_id', $product->id]])->delete();
                            $produxt_branch_prices = new ProductPrice();
                            $produxt_branch_prices->product_id = $product->id;
                            $produxt_branch_prices->store_id = $machine_data->Slno != null ? $machine_data->Slno : $machine_data->id;
                            $produxt_branch_prices->price = (!empty($live_product_data)) ? $live_product_data->unitprice : 0;
                            $produxt_branch_prices->price_update_date = date('Y-m-d');
                            $produxt_branch_prices->status = 1;
                            $produxt_branch_prices->created_by = 1;
                            $produxt_branch_prices->updated_by = 1;
                            $produxt_branch_prices->save();
                        } else {
                            $produxt_branch_prices = $product_price_checks;
                            $produxt_branch_prices->price = (!empty($live_product_data)) ? $live_product_data->unitprice : 0;
                            $produxt_branch_prices->price_update_date = date('Y-m-d');
                            $produxt_branch_prices->save();
                        }

                        $produxt_branch_price_history = new ProductPriceHistory();
                        $produxt_branch_price_history->product_id = $produxt_branch_prices->product_id;
                        $produxt_branch_price_history->store_id = $produxt_branch_prices->store_id;
                        $produxt_branch_price_history->price = $produxt_branch_prices->price;
                        $produxt_branch_price_history->price_update_date = $produxt_branch_prices->price_update_date;
                        $produxt_branch_price_history->status = $produxt_branch_prices->status;
                        $produxt_branch_price_history->created_by = $produxt_branch_prices->created_by;
                        $produxt_branch_price_history->updated_by = $produxt_branch_prices->updated_by;
                        $produxt_branch_price_history->save();
                    }
                }

            DB::commit();
            Log::info('Product Price Update Job completed');
        } catch (\Exception $e) {
            Log::error($e);
            DB::rollback();
        }
    }
}
