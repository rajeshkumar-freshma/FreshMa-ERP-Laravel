<?php

namespace App\Jobs;

use App\Core\CommonComponent;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

use App\Models\Product;
use App\Models\Unit;
use Log;
use Illuminate\Support\Facades\DB;

class ProductStore implements ShouldQueue
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
        Log::info('Product Job Executing');
        DB::beginTransaction();
        try {
            $productList = $this->fetchEaseTable('PLU_MC');
            Log::info($productList);
            foreach ($productList as $key => $product) {
                $externalCode = $this->resolveExternalCode($product);
                $slug = CommonComponent::slugCreate($product->pluname.'-'.$externalCode, null);
                Log::info("product->pluname");
                Log::info($product->pluname);

                $ERPProductData = Product::where('hsn_code', $externalCode)
                    ->orWhere('sku_code', $externalCode)
                    ->first();
                Log::info("ERPProductData");
                Log::info($ERPProductData);
                if($product->uom == 'WEIGH') {
                    $unit = Unit::where('unit_short_code', 'Kg')->first();
                    if($unit == null) {
                        $unit = $this->storeunit('Kilogram', 'Kg', 'Kilo', 2, '*', 1, 1, 1, 1);
                    }
                } else if($product->uom == 'PCS') {
                    $unit = Unit::where('unit_short_code', 'Pcs')->first();
                    if($unit == null) {
                        $unit = $this->storeunit('Pieces', 'Pcs', 'Pieces', 2, '*', 1, 1, 1, 1);
                    }
                }
                if ($ERPProductData == null) {
                    Log::info("ERPProductData NULL");
                    Log::info("EssaProductData Value");
                    $product_store = new Product();
                    $product_store->name = $product->pluname;
                    $product_store->slug = $slug;
                    $product_store->sku_code = $externalCode;
                    $product_store->hsn_code = $externalCode;
                    $product_store->unit_id = $unit->id;
                    $product_store->status = 1;
                    $product_store->created_by = 1;
                    $product_store->updated_by = 1;
                    $product_store->save();
                } else {
                    $ERPProductData->name = $product->pluname;
                    $ERPProductData->slug = $slug;
                    $ERPProductData->sku_code = $externalCode;
                    $ERPProductData->hsn_code = $externalCode;
                    $ERPProductData->unit_id = $unit->id;
                    $ERPProductData->status = 1;
                    $ERPProductData->updated_by = 1;
                    $ERPProductData->save();
                }
            }
            DB::commit();

            Log::info('Product Job completed');
        } catch (\Exception $e) {
            Log::error($e);
            DB::rollback();
        }
    }

    protected function resolveExternalCode($product): string
    {
        if (!empty($product->plucode)) {
            return (string) $product->plucode;
        }

        return (string) $product->pluno;
    }

    public function storeunit($unit_name, $unit_short_code, $base_unit, $allow_decimal, $operator, $operation_value, $status, $created_by, $updated_by)
    {
        $unit = new Unit();
        $unit->unit_name = $unit_name;
        $unit->unit_short_code = $unit_short_code;
        $unit->base_unit = $base_unit;
        $unit->allow_decimal = $allow_decimal;
        $unit->operator = $operator;
        $unit->operation_value = $operation_value;
        $unit->status = $status;
        $unit->created_by = $created_by;
        $unit->updated_by = $updated_by;
        $unit->save();

        return $unit;
    }

    /**
     * Fetch data from the EASSE SQL Server. In testing or when the connection
     * is disabled, return an empty collection to avoid external dependency.
     */
    protected function fetchEaseTable($table)
    {
        if (app()->environment('testing') || env('SQLSRV_EASE_MOCK', app()->environment('testing'))) {
            return collect();
        }

        return DB::connection('sqlsrv_ease')->table($table)->get();
    }
}
