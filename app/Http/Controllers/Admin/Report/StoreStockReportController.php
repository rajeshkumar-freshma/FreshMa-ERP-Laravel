<?php

namespace App\Http\Controllers\Admin\Report;

use App\DataTables\Report\StoreStockReportDataTable;
use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductTransferDetail;
use App\Models\Store;
use App\Models\FishCutting;
use App\Models\FishCuttingDetail;
use App\Models\FishCuttingProductMap;
use App\Models\StoreStockUpdate;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Request as FacadesRequest;

class StoreStockReportController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function storeStockReportData(Request $request)
    {
        // return StoreStockUpdate::whereDate('stock_update_on', Carbon::now())->get();
        // $query = Store::with([
        //     'store_stock_update' => function ($subQuery) {
        //         $subQuery->select(
        //             'store_id',
        //             'product_id',
        //             DB::raw('SUM(total_stock) as total_stock_sum')
        //         )->groupBy('store_id', 'product_id');
        //     }
        // ]);

        // // Apply filters
        // if ($request->has('from_date') && $request->has('to_date')) {
        //     $from_date = $request->from_date;
        //     $to_date = $request->to_date;
        //     $query->whereHas('store_stock_update', function ($subQuery) use ($from_date, $to_date) {
        //         $subQuery->whereBetween('created_at', [$from_date, $to_date]);
        //     });
        // }

        // if ($request->has('store_id')) {
        //     $store_id = $request->store_id;
        //     $query->where('id', $store_id);
        // }
        $data['products'] = Product::orderBy('id', 'asc')->get();
        $query = Store::with(['store_stock_update_inventory_details' => function ($subQuery) {
            $subQuery->select(
                'store_id',
                'product_id',
                DB::raw('SUM(weight) as total_stock_sum')
            )->groupBy('store_id', 'product_id');
        }]);


        $data['stores'] = $query->get(); // Get the result of the query

        return view('pages.report.store_stock_report.index', $data);



        // return view('pages.report.store_stock_report.index', $data)->with('from_date', 'store_id');
    }
}
