<?php

namespace App\Http\Controllers\Admin\Report;

use App\DataTables\Report\DailySalesReportDataTable;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Carbon;
use App\Models\Product;
use App\Models\SalesOrder;
use App\Models\Warehouse;
use Illuminate\Support\Facades\DB;

class DailySalesReportController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function dailySalesReport(DailySalesReportDataTable $dataTable)
    {
        $data['products'] = Product::orderBy('id', 'ASC')->get();
        $data['warehouse'] = Warehouse::orderBy('id', 'ASC')->get();
        return $dataTable->render('pages.report.daily_sales_report.index', $data);
    }
    public function show($id)
    {
        $data['salesOrder'] = SalesOrder::groupBy('store_id','id','total_amount','delivered_date','bill_no')->where('sales_orders.id', $id)
            ->select(
                'sales_orders.id',
                'sales_orders.store_id',
                'sales_orders.total_amount',
                'sales_orders.bill_no',
                'sales_orders.delivered_date',
                DB::raw('count(sales_orders.id) as total_count'),
            )
            ->get(); // Assuming SalesOrder is the model for daily sales reports

        return view('pages.report.daily_sales_report.view', $data);
    }
}
