<?php

namespace App\Http\Controllers\Admin\Report;

use App\DataTables\Report\SalesOrderReportDataTable;
use App\Http\Controllers\Controller;
use App\Models\Store;
use Illuminate\Http\Request;

class SalesOrderReportController extends Controller
{
    public function salesOrderReportData(SalesOrderReportDataTable $dataTable)
    {
        $data['stores'] = Store::all();
        return $dataTable->render('pages.report.sales_order_report.index', $data);
    }
}
