<?php

namespace App\Http\Controllers\Admin\Report;

use App\DataTables\Report\ProductWiseIndentRequestReportDataTable;
use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Store;
use App\Models\StoreIndentRequestDetail;
use App\Models\VendorIndentRequestDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ProductWiseIndentRequestReportController extends Controller
{
    public function productwiseindentrequestreport(ProductWiseIndentRequestReportDataTable $dataTable,Request $request)
    {
        $data['products'] = Product::where('status', 1)->get();
        $data['product_id'] = $request->product_id;
        Log::info("product id");
        Log::info($data['product_id']);
        $data['from_date'] = $request->from_date;
        $data['to_date'] = $request->to_date;
        return $dataTable->render('pages.report.product_wise_indent_request_report.index', $data);
    }

    public function productwiseindentrequestdata(Request $request)
    {
        Log::info("Product wise indent request");
        $from_date = $request->from_date;
        $store_id = $request->store_id;
        $to_date = $request->to_date;
        Log::info("1");

        $stores = Store::all();
        $products = Product::all();
        Log::info("2");
        $indent_request_data = [];
        Log::info("3");
        foreach ($products as $key => $product) {
            $total = 0;
            Log::info("4");
            foreach ($stores as $keys => $store) {
                Log::info("5");
                $store_id = $store->id;
                $store_indent_data = StoreIndentRequestDetail::whereHas('store_indent_request', function ($query) use ($from_date, $to_date, $store_id) {

                    if ($from_date != null && $to_date != null) {
                        $query->whereBetween('expected_date', [$from_date, $to_date]);
                    }
                    if ($store_id != null) {
                        $query->where('store_id', $store_id);
                    }
                })
                    ->where('product_id', $product->id)
                    ->select(DB::raw('COALESCE(request_quantity, 0) as request_quantity'))
                    ->first();
                $indent_request_data[$key][] = $store_indent_data != null ? $store_indent_data->request_quantity : '-';
                $total += $store_indent_data != null ? $store_indent_data->request_quantity : 0;
                break;
            }
            $vendor_indent_data = VendorIndentRequestDetail::whereHas('vendor_indent_request', function ($query) use ($from_date, $to_date) {
                if ($from_date != null && $to_date != null) {
                    $query->whereBetween('expected_date', [$from_date, $to_date]);
                }
            })
                ->where('product_id', $product->id)
                ->select(DB::raw('COALESCE(request_quantity, 0) as request_quantity'))
                ->first();

            $customer_req_quantity = $vendor_indent_data != null ? $vendor_indent_data->request_quantity : 0;

            $indent_request_data[$key][] = $customer_req_quantity;
            break;
        }
        return $indent_request_data;
    }
}
