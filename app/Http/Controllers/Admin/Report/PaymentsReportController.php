<?php

namespace App\Http\Controllers\Admin\Report;

use App\DataTables\PaymentType\PaymentsReportDataTable;
use App\Http\Controllers\Controller;
use App\Models\PaymentTransaction;
use App\Models\PaymentType;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class PaymentsReportController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function paymentsReport(PaymentsReportDataTable $dataTable, Request $request)
    {
        // Get the start and end dates based on user input or default to the current month
        $startDate = $request->input('from_date');
        $endDate = $request->input('to_date');
        $data['purchaseTransactions'] = $this->getTransactionData(1, $startDate, $endDate);
        $data['salesTransactions'] = $this->getTransactionData(2, $startDate, $endDate);
        $data['incomeExpense'] = $this->getTransactionData([5, 6], $startDate, $endDate);
        $data['from_date'] = $startDate;
        $data['to_date'] = $endDate;
        // dd($data);
        return $dataTable->render('pages.report.payments_report.index', $data);
    }

    protected function getTransactionData($transactionTypes, $startDate, $endDate)
    {
        $currentMonth = Carbon::now()->format('Y-m');

        return PaymentType::with([
            'paymentTransactions' => function ($query) use ($transactionTypes, $startDate, $endDate, $currentMonth) {
                $query->whereIn('transaction_type', (array) $transactionTypes)
                    ->select(
                        'payment_type_id',
                        DB::raw('COALESCE(SUM(CASE WHEN type = 1 THEN amount END), 0) as credit_sum'),
                        DB::raw('COALESCE(SUM(CASE WHEN type = 2 THEN amount END), 0) as debit_sum'),
                        DB::raw('COALESCE(SUM(CASE WHEN type = 1 AND transaction_type IN (6) THEN amount END), 0) as income_sum'),
                        DB::raw('COALESCE(SUM(CASE WHEN type = 2 AND transaction_type IN (5) THEN amount END), 0) as expense_sum')
                    );

                if ($startDate && $endDate) {
                    $query->whereBetween('transaction_datetime', [$startDate, $endDate]);
                } else {
                    $query->where('transaction_datetime', 'LIKE', "%$currentMonth%");
                }
                $query->groupBy('payment_type_id');
            }
        ])->get();
    }


}
