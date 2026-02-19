<?php

namespace App\Http\Controllers\Admin\Report;

use App\DataTables\Report\SupplierWisePurchaseOrderReportDataTable;
use App\Http\Controllers\Controller;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;

class SupplierWisePurchaseReportController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function SupplierWisePurchaseReport(SupplierWisePurchaseOrderReportDataTable $dataTable, Request $request)
    {
        $today = Carbon::now()->toDateString();

        $data['from_date'] = $request->from_date;
        $data['supplier_id'] = $request->supplier_id;
        Log::info($request->supplier_id);
        $data['to_date'] = $request->to_date;
        $data['supplier'] = Supplier::all();
        // $data['salesOrdersData'] = $this->salesOrder($today, $from_date, $to_date);
        // $data['purchaseOrdersData'] = $this->purchaseOrder($today, $from_date, $to_date);
        // $data['salesOrdersReturnsData'] = $this->salesOrderReturn($today, $from_date, $to_date);
        // $incomeExpenseQuery = IncomeExpenseTransaction::where('payment_status', 1)->where('status', 1);
        // $data['incomesData'] = $this->incomes($incomeExpenseQuery, $today, $from_date, $to_date);
        // $data['expensesData'] = $this->expenses($incomeExpenseQuery, $today, $from_date, $to_date);

        return $dataTable->render('pages.report.supplier_wise_purchase_report.index',$data);
    }

}
