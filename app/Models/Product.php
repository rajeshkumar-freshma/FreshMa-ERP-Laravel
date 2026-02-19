<?php

namespace App\Models;

use App\Core\CommonComponent;
use App\Core\Traits\SpatieLogsActivity;
use Auth;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class Product extends Model
{
    protected $appends = ['image_full_url'];

    use HasFactory, SoftDeletes, SpatieLogsActivity;

    protected $guarded = [];

    /* Default data store Container */
    protected static function booted()
    {
        static::creating(function ($data) {
            if (Auth::guard('admin')->check()) {
                $data->created_by = Auth::user()->id;
                $data->updated_by = Auth::user()->id;
            } else if (Auth::guard('api')->check()) {
                $data->created_by = Auth::user()->id;
                $data->updated_by = Auth::user()->id;
            } else {
                $data->created_by = 1;
                $data->updated_by = 1;
            }
        });

        static::updating(function ($data) {
            if (Auth::guard('admin')->check()) {
                $data->updated_by = Auth::user()->id;
            } else if (Auth::guard('api')->check()) {
                $data->updated_by = Auth::user()->id;
            } else {
                $data->updated_by = 1;
            }
        });
    }

    /* Relationship Container */
    public function created_by_details()
    {
        return $this->hasOne(Admin::class, 'id', 'created_by');
    }

    public function category()
    {
        return $this->hasOne(Category::class, 'id', 'category_id');
    }

    public function item_type()
    {
        return $this->hasOne(ItemType::class, 'id', 'item_type_id');
    }

    public function unit()
    {
        return $this->hasOne(Unit::class, 'id', 'unit_id');
    }

    public function tax_rate()
    {
        return $this->hasOne(TaxRate::class, 'id', 'tax_id');
    }

    public function product_category()
    {
        return $this->hasMany(ProductCategory::class, 'product_id', 'id');
    }
    public function product_pin_mapping_datas()
    {
        return $this->hasMany(PurchaseOrderBoxNumber::class, 'product_id', 'id');
    }
    public function product_pin_mapping_histories()
    {
        return $this->hasMany(PurchaseBoxNumberHistory::class, 'product_id', 'id');
    }

    public function product_image()
    {
        return $this->hasMany(ProductImage::class, 'product_id', 'id');
    }

    public function product_price()
    {
        return $this->hasOne(ProductPrice::class, 'product_id', 'id')->latestOfMany();
    }

    public function product_price_current_date()
    {
        return $this->hasMany(ProductPrice::class, 'product_id', 'id');
    }

    public function storeStockInventory()
    {
        return $this->hasMany(StoreInventoryDetail::class, 'product_id', 'id');
    }

    public static function storeproductstockdetails($product_id, $store_id, $date)
    {
        return StoreStockUpdate::where(function ($query) use ($date) {
            if ($date != null) {
                $query->whereBetween('stock_update_on', [Carbon::parse($date)->startOfDay(), Carbon::parse($date)->endOfDay()]);
            }
        })
            ->where('product_id', $product_id)
            ->where('store_id', $store_id)
            ->orderBy('id', 'DESC')
            ->selectRaw('COALESCE((CASE WHEN total_stock is NOT NULL THEN total_stock ELSE 0 END),0) as openingstock')
            ->first();
    }

    public static function productstockdetails($product_id, $store_id, $from_date, $to_date, $store_name, $product_short_code)
    {
        $openingstock = StoreStockUpdate::where(function ($query) use ($from_date, $to_date) {
            if ($from_date != null && $to_date != null) {
                $dateformatwithtime = CommonComponent::dateformatwithtime($from_date, $to_date);
                $query->whereBetween('stock_update_on', $dateformatwithtime);
            }
        })
            ->where('product_id', $product_id)
            ->where('store_id', $store_id)
            ->select('id', DB::raw('COALESCE(total_stock, 0) as openingstock'))
            ->orderByDesc('id')
            ->first();

        if ($openingstock === null) {
            $openingstockValue = 0;
        } else {
            $openingstockValue = $openingstock->openingstock;
        }

        $closingstock = StoreInventoryDetail::where('product_id', $product_id)
            ->where('store_id', $store_id)
            ->select(DB::raw('COALESCE(weight, 0) as closingstock'))
            ->first();

        if ($closingstock === null) {
            $closingstockValue = 0;
        } else {
            $closingstockValue = $closingstock->closingstock;
        }

        // return "Opening Stock : " . $openingstockValue . ", Closing Stock : " . $closingstockValue;
        // return $openingstockValue.','.$closingstockValue;
        $data['openingstock'] = $openingstockValue . " " . $product_short_code;
        $data['closingstock'] = $closingstockValue . " " . $product_short_code;
        $data['usage_stock'] = ($openingstockValue - $closingstockValue) . " " . $product_short_code;
        // $data['store_name'] = $store_name;

        return $data;
    }

    public static function productstockdetailsforreport($product_id, $store_id, $from_date, $to_date, $store_name, $product_short_code)
    {
        $openingstock = StoreStockUpdate::where(function ($query) use ($from_date, $to_date) {
            if ($from_date != null && $to_date != null) {
                $dateformatwithtime = CommonComponent::dateformatwithtime($from_date, $to_date);
                $query->whereBetween('stock_update_on', $dateformatwithtime);
            }
        })
            ->where('product_id', $product_id)
            ->where('store_id', $store_id)
            ->select('id', DB::raw('COALESCE(total_stock, 0) as openingstock'))
            ->orderByDesc('id')
            ->first();

        if ($openingstock === null) {
            $openingstockValue = 0;
        } else {
            $openingstockValue = $openingstock->openingstock;
        }

        $closingstock = StoreInventoryDetail::where('product_id', $product_id)
            ->where('store_id', $store_id)
            ->select(DB::raw('COALESCE(weight, 0) as closingstock'))
            ->first();

        if ($closingstock === null) {
            $closingstockValue = 0;
        } else {
            $closingstockValue = $closingstock->closingstock;
        }

        return "Opening Stock : " . $openingstockValue . " " . $product_short_code . ", Closing Stock : " . $closingstockValue . " " . $product_short_code;
        // return $openingstockValue.','.$closingstockValue;
        // $data['openingstock'] = $openingstockValue;
        // $data['closingstock'] = $closingstockValue;
        // $data['store_name'] = $store_name;

        // return $data;
    }

    public function getImageFullUrlAttribute()
    {
        return CommonComponent::getImageFullUrlPath($this->image, $this->image_path);
    }

    public function scopeActive($q)
    {
        return $q->where('status', 1);
    }

    public function product_sale_datas()
    {
        // return $this->hasMany(SalesOrderDetail::class, 'product_id', 'id');
        return $this->hasMany(SalesOrderDetail::class, 'product_id', 'id')->select(DB::raw('COALESCE(sum(given_quantity),0) as total_unit'), DB::raw('COALESCE(sum(total), 0) as total_amount'), DB::raw('COALESCE((per_unit_price), 0) as per_unit_price'), DB::raw('count(per_unit_price) as sale_count'))->groupBy(['sales_order_details.product_id', 'sales_order_details.per_unit_price']);
    }
    public function product_wise_purchase_datas()
    {
        $currentDate = now()->toDateString();
        Log::info("currentDate");
        Log::info($currentDate);

        return $this->hasMany(PurchaseOrderDetail::class, 'product_id', 'id')
            ->whereDate('purchase_order_details.created_at', $currentDate)
            ->select(
                DB::raw('COALESCE(sum(purchase_order_details.given_quantity), 0) as total_unit'),
                DB::raw('COALESCE(sum(purchase_order_details.sub_total), 0) as total_amount'),
                DB::raw('COALESCE(sum(purchase_order_details.per_unit_price), 0) as total_per_unit_price'),
                DB::raw('count(purchase_order_details.per_unit_price) as purchase_count'),
            )
            ->groupBy(['purchase_order_details.product_id']);
    }

    public static function product_wise_sale_datas($product_id, $store_ids, $from_date, $to_date)
    {
        return $datas = SalesOrderDetail::where('product_id', $product_id)->with('sales_order', function ($query) use ($store_ids, $from_date, $to_date) {
            if (count($store_ids) > 0) {
                $query->whereIn('store_id', $store_ids);
            }

            if ($from_date != null && $to_date != null) {
                $dateformatwithtime = CommonComponent::dateformatwithtime($from_date, $to_date);
                $query->whereBetween('delivered_date', $dateformatwithtime);
            }
        })
            ->select(DB::raw('COALESCE(sum(given_quantity),0) as total_unit'), DB::raw('COALESCE(sum(total), 0) as total_amount'), DB::raw('COALESCE((per_unit_price), 0) as per_unit_price'), DB::raw('count(per_unit_price) as sale_count'))
            ->groupBy(['sales_order_details.product_id', 'sales_order_details.per_unit_price'])
            ->get();

        // foreach ($datas as $key => $data) {
        //     return $data;
        // }
        // return $this->hasMany(SalesOrderDetail::class, 'product_id', 'id');
        // return $this->hasMany(SalesOrderDetail::class, 'product_id', 'id')->select(DB::raw('COALESCE(sum(given_quantity),0) as total_unit'),  DB::raw('COALESCE(sum(total), 0) as total_amount'), DB::raw('COALESCE((per_unit_price), 0) as per_unit_price'), DB::raw('count(per_unit_price) as sale_count'))->groupBy(['sales_order_details.product_id', 'sales_order_details.per_unit_price']);
    }

    public function fish_cutting()
    {
        return $this->hasOne(FishCutting::class, 'product_id', 'id');
    }

    public function fish_cutting_grouped_products()
    {
        return $this->hasOne(FishCuttingProductMap::class, 'main_product_id', 'products.id');
    }
    public function fish_cutting_grouped()
    {
        return $this->hasOne(FishCuttingProductMap::class, 'main_product_id', 'id');
    }
    public function api_product_list_unit()
    {
        return $this->hasOne(Unit::class, 'id', 'products.unit_id');
    }

    public function store_inventory_details()
    {
        return $this->hasMany(StoreInventoryDetail::class);
    }

    // public function fish_cutting_details()
    // {
    //     return $this->hasOne(FishCuttingDetail::class, 'fish_cutting_id', 'id')
    //         ->whereNotNull('slice_percentage')
    //         ->whereNotNull('head_percentage')
    //         ->whereNotNull('tail_percentage')
    //         ->whereNotNull('eggs_percentage')
    //         ->whereNotNull('wastage_percentage');
    // }
    public function storeIndentRequestDetails()
    {
        return $this->hasMany(StoreIndentRequestDetail::class, 'product_id', 'id');
    }
    public function vendorIndentRequestDetails()
    {
        return $this->hasMany(VendorIndentRequestDetail::class, 'product_id', 'id');
    }

    public function warehouseInventoryDetails()
    {
        return $this->hasMany(WarehouseInventoryDetail::class, 'product_id', 'id');
    }
}
