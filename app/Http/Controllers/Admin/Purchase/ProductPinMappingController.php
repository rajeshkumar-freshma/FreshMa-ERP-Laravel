<?php

namespace App\Http\Controllers\Admin\Purchase;

use App\DataTables\Purchase\ProductPinMappingDataTable;
use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductPinMappingController extends Controller
{

    public function productpinMappingView(ProductPinMappingDataTable $dataTable)
    {
        $data['products'] = Product::all();
        return $dataTable->render('pages.purchase.pin_mapping.index', $data);
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


}
