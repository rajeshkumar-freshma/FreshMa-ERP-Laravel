<?php

namespace App\Http\Controllers\Admin\Report;

use App\DataTables\Report\SupplierReportDataTable;
use App\Http\Controllers\Controller;
use App\Models\PaymentType;
use App\Models\Supplier;
use App\Models\User;
use Illuminate\Http\Request;

class SupplierReportController extends Controller
{
    public function index(SupplierReportDataTable $dataTable)
    {
        $data['suppliers'] = User::all();
        return $dataTable->render('pages.report.supplier_report.index',$data);
    }
}
