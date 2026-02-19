<?php

namespace App\Http\Controllers\Admin\Report;

use App\DataTables\Report\ProductSaleReportDataTable;
use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Store;
use App\Models\Warehouse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use URL;

class ProductwiseReportController extends Controller
{

    public function productwisesalereport(ProductSaleReportDataTable $dataTable,Request $request)
    {
        // return $previousUrl = URL::current();
        $data['products'] = Product::all();
        $data['warehouse'] = Warehouse::get();
        $data['stores'] = Store::get();
        $data['from_date'] = $request->from_date;
        $data['to_date'] = $request->from_date;

        return $dataTable->render('pages.report.product_sale_report.index', $data);
    }

    // public function productwisesalereport(Request $request) {
    //     if($request->pagination == 'all') {
    //         $pagination = Product::count();
    //     } else {
    //         $pagination = $request->pagination;
    //     }
    //     $data['products'] = Product::select('id','name','sku_code')->paginate($pagination);
    //     return view('pages.report.productwisesalereport', $data);
    //     // return $products = Product::LeftJoin('sales_order_details', function($join) {
    //     //     $join->on('sales_order_details.product_id', 'products.id');
    //     // })
    //     // ->select('products.id', 'products.name', 'products.sku_code', DB::raw('COALESCE(sum(given_quantity),0) as total_unit'),  DB::raw('COALESCE(sum(total), 0) as total_amount'), DB::raw('COALESCE((per_unit_price), 0) as per_unit_price'), DB::raw('count(per_unit_price) as sale_count'))
    //     // ->groupBy(['sales_order_details.product_id', 'sales_order_details.per_unit_price'])
    //     // ->paginate(25);

    //     // return $products->groupBy('name');
    // }

    public function getProductList(Request $request)
    {
        $response = array();
        if (isset($request['term']['term']) && !empty($request['term']['term'])) {
            $products = Product::where('name', 'LIKE', "%{$request['term']['term']}%")->where('status', 1)->get();
            $products->each(function ($user) use (&$response) {
                $response[] = array(
                    "id" => $user->id,
                    "text" => $user->name
                );
            });
        }

        return response()->json($response);
    }
}
