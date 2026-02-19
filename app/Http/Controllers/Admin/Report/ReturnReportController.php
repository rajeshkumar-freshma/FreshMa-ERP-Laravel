<?php

namespace App\Http\Controllers\Admin\Report;

use App\DataTables\Report\ReturnReportDataTable;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ReturnReportController extends Controller
{
    public function SupplierDatas(ReturnReportDataTable $dataTable)
    {
        return $dataTable->render('pages.payment.supplier_report.report');
    }
}
