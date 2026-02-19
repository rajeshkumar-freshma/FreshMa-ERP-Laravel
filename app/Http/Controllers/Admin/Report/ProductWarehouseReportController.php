<?php

namespace App\Http\Controllers\Admin\Report;

use App\DataTables\Report\StoreStockReportDataTable;
use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Store;
use App\Models\Warehouse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Request as FacadesRequest;

class ProductWarehouseReportController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function productWarehouseReportData(Request $request)
    {
        // $query = Warehouse::with([
        //     'warehouse_inventory_details' => function ($subQuery) {
        //         $subQuery->select(
        //             'warehouse_id',
        //             'product_id',
        //             DB::raw('SUM(weight) as weight') // Changed 'weight' to 'total_weight'
        //         )->groupBy('warehouse_id', 'product_id');
        //     }
        // ]);
        // $warehouses = Warehouse::with(['warehouse_inventory_details' => function ($query) {
        //     $query->select(
        //         'warehouse_id',
        //         'product_id',
        //         DB::raw('SUM(weight) as total_weight') // Calculating total weight
        //     )->groupBy('warehouse_id', 'product_id');
        // }])->get();
        $data['products'] = Product::orderBy('id', 'asc')->get();
        // $data['products'] = Product::with([
        //     'warehouseInventoryDetails' => function ($subQuery) {
        //         $subQuery->select(
        //             'warehouse_id',
        //             'product_id',
        //             DB::raw('COALESCE(sum(weight),0) as weight'),
        //         )->groupBy('warehouse_id', 'product_id');
        //     }
        // ])->orderBy('id')->get();
        $data['warehouses'] = Warehouse::all();
        // $data['warehouseProductsData'] = $warehouses; // Assuming $query is correctly defined

        // Pass data to the view
        return view('pages.report.product_warehouse_report.index', $data);
    }
}
