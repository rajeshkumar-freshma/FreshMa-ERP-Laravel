<?php

namespace App\Http\Controllers\Admin\Report;

use App\Core\CommonComponent;
use App\Http\Controllers\Controller;
use App\Models\FishCutting;
use App\Models\FishCuttingDetail;
use App\Models\Product;
use App\Models\ProductTransferDetail;
use App\Models\SalesOrderDetail;
use App\Models\SpoilageProductDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Store;
use App\Models\store_stock_updates;
use App\Models\StoreInventoryDetail;
use App\Models\StoreStockDailyUpdate;
use App\Models\StoreStockUpdate;
use Carbon\Carbon;
use View;

class DailystoreReportController extends Controller
{
    public function dailystorereportdata(Request $request)
    {
        $paginate = $request->paginate ?? config('app.paginate');
        $store_id = $request->store_id;
        $from_date = Carbon::parse($request->from_date)->format('Y-m-d 00:00:00');
        $to_date = Carbon::parse($request->to_date)->format('Y-m-d 23:59:59');

        $data['stores'] = Store::get();
        $data['products'] = [];
        if (isset($store_id) && !empty($store_id)) {
            $data['products'] = Product::where('status', '1')->select(
                'products.id',
                'products.name',
                'products.slug',
                'products.sku_code',
                'products.hsn_code'
            )
                ->paginate($paginate)
                ->through(function ($data) use ($store_id, $from_date, $to_date) {
                    $query_data = $this->getreportdetail($data->id, $store_id, $from_date, $to_date);
                    //    $data->total_stock = $query_data;
                    $data->initial_stock = $query_data['initial_stock'];
                    $data->transfer_quantity_in = $query_data['transfer_quantity_in'];
                    $data->transfer_quantity_out = $query_data['transfer_quantity_out'];
                    $data->transfer_quantity_from_another_store = $query_data['transfer_quantity_from_another_store'];
                    $data->sales_order_quantity = $query_data['sales_order_quantity'];
                    $data->sales_order_sub_total = $query_data['sales_order_sub_total'];
                    $data->slice = $query_data['slice'];
                    $data->head = $query_data['head'];
                    $data->tail = $query_data['tail'];
                    $data->wastage = $query_data['wastage'];
                    $data->eggs = $query_data['eggs'];
                    $data->cutting_fish_weight = $query_data['cutting_fish_weight'];
                    $data->total_wastage = $query_data['total_wastage'];
                    $data->spoilage_egg_quantity = $query_data['spoilage_egg_quantity'];
                    $data->spoilage_product_quantity = $query_data['spoilage_product_quantity'];
                    $data->store_inventory_weight = $query_data['store_inventory_weight'];
                    $data->closing_stock = $query_data['closing_stock'];
                    $data->final_stock = $query_data['final_stock'];
                    return $data;
                });
        }

        $data['store_id'] = $request->store_id;
        $data['from_date'] = $request->from_date;
        $data['to_date'] = $request->to_date;
        return $this->renderView($data);
    }

    public function getreportdetail($product_id, $store_id, $from_date, $to_date)
    {
        $initial_stock = StoreStockUpdate::whereIn('id', function ($query) use ($store_id, $from_date, $to_date) {
            $query->selectRaw('MIN(id)')
                ->from('store_stock_updates')
                ->whereBetween('stock_update_on', [Carbon::parse($from_date), Carbon::parse($to_date)])
                ->where('store_id', $store_id)
                ->groupBy('product_id');
        })
            ->where('product_id', $product_id)
            ->select(DB::raw('COALESCE(total_stock, 0) as total_stock'))
            ->first();


        $data['initial_stock'] = $initial_stock?->total_stock != null ? $initial_stock->total_stock : 0;

        $sales_order = SalesOrderDetail::Join('sales_orders as so', function ($join) use ($store_id, $from_date, $to_date) {
            $join->on('so.id', '=', 'sales_order_id')
                ->where('so.store_id', '=', $store_id)
                ->whereBetween('so.delivered_date', [Carbon::parse($from_date), Carbon::parse($to_date)]);
        })
            ->where('product_id', $product_id)
            ->select(
                DB::raw('COALESCE(SUM(given_quantity),0 ) as sales_order_quantity'),
                DB::raw('COALESCE(SUM(sales_order_details.sub_total),0 ) as sub_total')
            )
            ->first();

        $data['sales_order_quantity'] = $sales_order?->sales_order_quantity != null ? $sales_order?->sales_order_quantity : 0;
        $data['sales_order_sub_total'] = $sales_order?->sub_total != null ? $sales_order?->sub_total : 0;

        $data['transfer_quantity_in'] = ProductTransferDetail::Join('product_transfers as pt1', function ($join) use ($store_id, $from_date, $to_date) {
            $join->on('pt1.id', '=', 'product_transfer_id')
                ->whereNotNull('pt1.from_warehouse_id')
                ->where('pt1.to_store_id', '=', $store_id)
                ->whereBetween('pt1.transfer_created_date', [Carbon::parse($from_date), Carbon::parse($to_date)]);
        })
            ->where('product_id', $product_id)
            ->select(DB::raw('COALESCE(SUM(given_quantity), 0) as transfer_quantity_in'))
            ->first()?->transfer_quantity_in;

        $data['transfer_quantity_from_another_store'] = ProductTransferDetail::Join('product_transfers as pt1', function ($join) use ($store_id, $from_date, $to_date) {
            $join->on('pt1.id', '=', 'product_transfer_id')
                ->whereNotNull('pt1.from_store_id')
                ->where('pt1.to_store_id', '=', $store_id)
                ->whereBetween('pt1.transfer_created_date', [Carbon::parse($from_date), Carbon::parse($to_date)]);
        })
            ->where('product_id', $product_id)
            ->select(DB::raw('COALESCE(SUM(given_quantity), 0) as transfer_quantity_in_from_store'))
            ->first()?->transfer_quantity_in_from_store;

        $data['transfer_quantity_out'] = ProductTransferDetail::Join('product_transfers as pt1', function ($join) use ($store_id, $from_date, $to_date) {
            $join->on('pt1.id', '=', 'product_transfer_id')
                ->whereNotNull('pt1.to_store_id')
                ->where('pt1.from_store_id', '=', $store_id)
                ->whereBetween('pt1.transfer_created_date', [Carbon::parse($from_date), Carbon::parse($to_date)]);
        })
            ->where('product_id', $product_id)
            ->select(DB::raw('COALESCE(SUM(given_quantity), 0) as transfer_quantity_out'))
            ->first()?->transfer_quantity_out;

            $fish_cutting_data = FishCuttingDetail::Join('fish_cuttings', function ($join) use ($store_id, $from_date, $to_date) {
                $join->on('fish_cuttings.id', '=', 'fish_cutting_details.fish_cutting_id')
                    ->where('fish_cuttings.store_id', '=', $store_id)
                    ->whereBetween('fish_cuttings.cutting_date', [Carbon::parse($from_date), Carbon::parse($to_date)]);
            })
                ->where('fish_cutting_details.product_id', $product_id)
                ->select(
                    DB::raw('COALESCE(SUM(fish_cutting_details.slice), 0) as slice'),
                    DB::raw('COALESCE(SUM(fish_cutting_details.wastage), 0) as wastage'),
                    DB::raw('COALESCE(SUM(fish_cutting_details.head), 0) as head'),
                    DB::raw('COALESCE(SUM(fish_cutting_details.tail), 0) as tail'),
                    DB::raw('COALESCE(SUM(fish_cutting_details.eggs), 0) as eggs')
                )
                ->first();
        // dd($fish_cutting_data);
        $data['slice'] = $fish_cutting_data?->slice != null ? $fish_cutting_data?->slice : 0;
        $data['wastage'] = $fish_cutting_data?->wastage != null ? $fish_cutting_data?->wastage : 0;
        $data['head'] = $fish_cutting_data?->head != null ? $fish_cutting_data?->head : 0;
        $data['tail'] = $fish_cutting_data?->tail != null ? $fish_cutting_data?->tail : 0;
        $data['eggs'] = $fish_cutting_data?->eggs != null ? $fish_cutting_data?->eggs : 0;

        $fish_cutting = FishCutting::Join('fish_cutting_details', function ($join) {
            $join->on('fish_cutting_details.fish_cutting_id', '=', 'fish_cuttings.id');
        })
            ->where('store_id', '=', $store_id)
            ->whereBetween('cutting_date', [Carbon::parse($from_date), Carbon::parse($to_date)])
            ->where('fish_cuttings.product_id', $product_id)
            ->select(
                DB::raw('COALESCE(SUM(weight), 0) as cutting_fish_weight'),
                DB::raw('COALESCE(SUM(wastage), 0) as total_wastage')
            )
            ->first();

        $data['cutting_fish_weight'] = $fish_cutting?->cutting_fish_weight != null ? $fish_cutting?->cutting_fish_weight : 0;
        $data['total_wastage'] = $fish_cutting?->total_wastage != null ? $fish_cutting?->total_wastage : 0;

        $data['store_inventory_weight'] = StoreInventoryDetail::where('store_inventory_details.store_id', '=', $store_id)
            ->whereBetween('store_inventory_details.created_at', [Carbon::parse($from_date), Carbon::parse($to_date)])
            ->where('product_id', $product_id)
            ->select(DB::raw('COALESCE(SUM(weight), 0) as weight'))
            ->first()?->weight;

        $spoilage = SpoilageProductDetail::Join('spoilages', function ($join) use ($store_id, $from_date, $to_date) {
            $join->on('spoilages.id', '=', 'spoilage_product_details.spoilage_id')
                ->whereBetween('spoilages.spoilage_date', [Carbon::parse($from_date), Carbon::parse($to_date)]);
        })
            ->where('product_id', $product_id)
            ->selectRaw('SUM(CASE WHEN spoilage_product_details.name LIKE ? THEN spoilage_product_details.quantity ELSE 0 END) as spoilage_egg_quantity', ['%egg%'])
            ->selectRaw('SUM(CASE WHEN spoilage_product_details.name NOT LIKE ? THEN spoilage_product_details.quantity ELSE 0 END) as spoilage_product_quantity', ['%egg%'])
            ->first();

        $data['spoilage_egg_quantity'] = $spoilage?->spoilage_egg_quantity != null ? $spoilage?->spoilage_egg_quantity : 0;
        $data['spoilage_product_quantity'] = $spoilage?->spoilage_product_quantity != null ? $spoilage?->spoilage_product_quantity : 0;

        $staff_stock_update = StoreStockDailyUpdate::where('product_id', $product_id)
            ->where('store_stock_daily_updates.store_id', '=', $store_id)
            ->whereNotNull('store_stock_daily_updates.closing_stock')
            ->whereBetween('store_stock_daily_updates.stock_update_on', [Carbon::parse($from_date), Carbon::parse($to_date)])
            ->select(DB::raw('COALESCE(SUM(closing_stock), 0) as closing_stock'))
            ->first();

        $data['closing_stock'] = $staff_stock_update?->closing_stock != null ? $staff_stock_update?->closing_stock : 0;

        $final_stock = StoreStockUpdate::whereIn('id', function ($query) use ($store_id, $from_date, $to_date) {
            $query->selectRaw('MAX(id)')
                ->from('store_stock_updates')
                ->whereBetween('stock_update_on', [Carbon::parse($from_date), Carbon::parse($to_date)])
                ->where('store_id', $store_id)
                ->groupBy('product_id');
        })
            ->where('product_id', $product_id)
            ->select(DB::raw('COALESCE(total_stock, 0) as total_stock'))
            ->first();

        $data['final_stock'] = $final_stock?->total_stock != null ? $final_stock->total_stock : 0;
        return $data;

    }

    /* public function dailystorereportdata(Request $request)
    {
        $store_id = $request->store_id;
        $from_date = Carbon::parse($request->from_date)->format('Y-m-d 00:00:00');
        $to_date = Carbon::parse($request->to_date)->format('Y-m-d 23:59:59');

        if (isset($store_id) && !empty($store_id)) {
            $data['stores'] = Store::get();

            $data['products'] = Product::leftJoin('store_stock_updates as ssu', function ($join) use ($store_id, $from_date, $to_date) {
                $join->on('ssu.product_id', '=', 'products.id')
                    ->whereIn('ssu.id', function ($query) use ($store_id, $from_date, $to_date) {
                        $query->selectRaw('MAX(id)')
                            ->from('store_stock_updates')
                            ->whereBetween('store_stock_updates.stock_update_on', [Carbon::parse($from_date), Carbon::parse($to_date)])
                            ->where('store_id', $store_id)
                            ->groupBy('product_id');
                    })
                    ->where('ssu.store_id', $store_id);
            })
            ->leftJoin('product_transfer_details as ptd1', function ($join) use ($store_id, $from_date, $to_date) {
                $join->on('ptd1.product_id', '=', 'products.id')->whereNotNull('ptd1.product_transfer_id');

                $join->Join('product_transfers as pt1', function ($join) use ($store_id, $from_date, $to_date) {
                    $join->on('pt1.id', '=', 'ptd1.product_transfer_id')
                    ->whereNotNull('pt1.from_warehouse_id')
                    ->where('pt1.to_store_id', '=', $store_id)
                    ->whereBetween('pt1.transfer_created_date', [Carbon::parse($from_date), Carbon::parse($to_date)]);
                });
            })
                ->leftJoin('product_transfer_details as ptd2', function ($join) use ($store_id, $from_date, $to_date) {
                    $join->on('ptd2.product_id', '=', 'products.id')->whereNotNull('ptd2.product_transfer_id');

                    $join->Join('product_transfers as pt2', function ($join) use ($store_id, $from_date, $to_date) {
                        $join->on('pt2.id', '=', 'ptd2.product_transfer_id')
                        ->whereNotNull('pt2.to_store_id')
                        ->where('pt2.from_store_id', '=', $store_id)
                        ->whereBetween('pt2.transfer_created_date', [Carbon::parse($from_date), Carbon::parse($to_date)]);
                    });
                })
                ->leftJoin('sales_order_details as sod', function ($join) use ($store_id, $from_date, $to_date) {
                    $join->on('sod.product_id', '=', 'products.id');
                    $join->Join('sales_orders as so', function ($join) use ($store_id, $from_date, $to_date) {
                        $join->on('so.id', '=', 'sod.sales_order_id')
                            ->where('so.store_id', '=', $store_id)
                            ->whereBetween('so.delivered_date', [Carbon::parse($from_date), Carbon::parse($to_date)]);
                    });
                })
                ->leftJoin('fish_cutting_details', function ($join) use ($store_id, $from_date, $to_date) {
                    $join->on('fish_cutting_details.product_id', '=', 'products.id');
                    $join->Join('fish_cuttings', function ($join) use ($store_id, $from_date, $to_date) {
                        $join->on('fish_cuttings.id', '=', 'fish_cutting_details.fish_cutting_id')
                            ->where('fish_cuttings.store_id', '=', $store_id)
                            ->whereBetween('fish_cuttings.cutting_date', [Carbon::parse($from_date), Carbon::parse($to_date)]);
                    });
                })
                ->leftJoin('spoilage_product_details', function ($join) use ($store_id, $from_date, $to_date) {
                    $join->on('spoilage_product_details.product_id', '=', 'products.id');
                    $join->Join('spoilages', function ($join) use ($store_id, $from_date, $to_date) {
                        $join->on('spoilages.id', '=', 'spoilage_product_details.spoilage_id')
                            ->whereBetween('spoilages.spoilage_date', [Carbon::parse($from_date), Carbon::parse($to_date)]);
                    });
                })
                ->leftJoin('product_transfer_details as ptd3', function ($join) use ($store_id, $from_date, $to_date) {
                    $join->on('ptd3.product_id', '=', 'products.id')->whereNotNull('ptd3.product_transfer_id');

                    $join->Join('product_transfers as pt3', function ($join) use ($store_id, $from_date, $to_date) {
                        $join->on('pt3.id', '=', 'ptd3.product_transfer_id')
                        ->whereNotNull('pt3.to_store_id')
                        ->where('pt3.from_store_id', '=', $store_id)
                        ->whereBetween('pt3.transfer_created_date', [Carbon::parse($from_date), Carbon::parse($to_date)]);
                    });
                })
                ->leftJoin('store_inventory_details', function ($join) use ($store_id, $from_date, $to_date) {
                    $join->on('store_inventory_details.product_id', '=', 'products.id')
                        ->where('store_inventory_details.store_id', '=', $store_id)
                        ->whereBetween('store_inventory_details.created_at', [Carbon::parse($from_date), Carbon::parse($to_date)]);
                })
                ->leftJoin('store_stock_daily_updates', function ($join) use ($store_id, $from_date, $to_date) {
                    $join->on('store_stock_daily_updates.product_id', '=', 'products.id')
                        ->where('store_stock_daily_updates.store_id', '=', $store_id)
                        ->whereNotNull('store_stock_daily_updates.closing_stock')
                        ->whereBetween('store_stock_daily_updates.stock_update_on', [Carbon::parse($from_date), Carbon::parse($to_date)])
                        ->orderByDesc('store_stock_daily_updates.closing_stock'); // Assuming stock_update_on is the column for date
                })
                ->LeftJoin('store_stock_updates as ssu2', function ($join) use ($store_id, $from_date, $to_date) {
                    $join->on('ssu2.product_id', 'products.id')
                        ->whereIn('ssu2.id', function ($query) use ($store_id, $from_date, $to_date) {
                            $query->selectRaw('MAX(id) as id')->from('store_stock_updates')->whereBetween('store_stock_updates.stock_update_on', [Carbon::parse($from_date)->format('Y-m-d 00:00:00'), Carbon::parse($to_date)->format('Y-m-d 23:59:59')])->where('store_stock_updates.store_id', $store_id)->groupBy('product_id');
                        })
                        ->where('ssu2.store_id', $store_id);
                })
                ->where('products.deleted_at', null)
                ->select(
                    'products.id',
                    'products.name',
                    'products.slug',
                    'products.sku_code',
                    'products.hsn_code',
                    DB::raw('COALESCE(ssu.total_stock, 0) as total_stock'),
                    DB::raw('COALESCE(ptd1.given_quantity, 0) as transfer_quantity_in'),
                    DB::raw('COALESCE(ptd2.given_quantity, 0) as transfer_quantity_out'),
                    DB::raw('COALESCE(SUM(sod.given_quantity),0 ) as sales_order_quantity'),
                    DB::raw('COALESCE(SUM(sod.sub_total), 0) as sales_order_sub_total'),
                    DB::raw('COALESCE(SUM(fish_cutting_details.slice), 0) as fish_weight'),
                    DB::raw('COALESCE(SUM(fish_cutting_details.wastage), 0) as wastage'),
                    DB::raw('COALESCE(SUM(fish_cutting_details.head), 0) as head'),
                    DB::raw('COALESCE(SUM(fish_cutting_details.tail), 0) as tail'),
                    DB::raw('COALESCE(SUM(fish_cutting_details.eggs), 0) as eggs'),
                    // DB::raw('COALESCE(spoilage_product_details.name, "-") as spoilage_product_name'),
                    DB::raw('CASE WHEN spoilage_product_details.name NOT LIKE "%egg%" THEN spoilage_product_details.quantity ELSE "0.00" END as spoilage_product_name'),
                    DB::raw('CASE WHEN spoilage_product_details.name LIKE "%egg%" THEN spoilage_product_details.quantity ELSE "0.00" END as spoilage_product_eggname'),
                    DB::raw('COALESCE(ptd3.given_quantity, 0) as transfer_quantity_another'),
                    DB::raw('COALESCE(store_inventory_details.weight, 0) as store_inventory_weight'),
                    DB::raw('COALESCE(store_stock_daily_updates.closing_stock, 0) as closing_stock'),
                    DB::raw('COALESCE((ssu2.total_stock),0) as final_stock'),
                )
                ->groupBy('products.id', 'products.name', 'products.slug', 'products.sku_code', 'products.hsn_code')
                ->paginate(200);
        } else {
            return back()->with('danger', 'Please choose Store');
        }
        $data['store_id'] = $request->store_id;
        $data['from_date'] = $request->from_date;
        $data['to_date'] = $request->to_date;

         return $this->renderView($data);
    } */

    private function renderView($data)
    {
        return view('pages.report.daily_store_report.daily_store_report', $data);
    }
}
