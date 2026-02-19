<?php

namespace App\Http\Controllers\Admin\Report;

use App\Http\Controllers\Controller;
use App\Models\IncomeExpenseTransaction;
use App\Models\PurchaseOrder;
use App\Models\SalesOrder;
use App\Models\SalesOrderReturn;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ProfitAndLossReportController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function profitAndLoss(Request $request)
    {
        $today = Carbon::now()->toDateString();
        $from_date = $request->from_date;
        $to_date = $request->to_date;
        $data['salesOrdersData'] = $this->salesOrder($from_date, $to_date);
        $data['purchaseOrdersData'] = $this->purchaseOrder($from_date, $to_date);
        $data['salesOrdersReturnsData'] = $this->salesOrderReturn($today, $from_date, $to_date);
        $incomeExpenseQuery = IncomeExpenseTransaction::where('payment_status', 1)->where('status', 1);
        $data['incomesData'] = $this->incomes($incomeExpenseQuery, $today, $from_date, $to_date);
        $data['expensesData'] = $this->expenses($incomeExpenseQuery, $today, $from_date, $to_date);
        return view('pages.report.profit_and_loss.index', $data);
    }

    private function salesOrder($from_date, $to_date)
    {
        $query = SalesOrder::where('status', 8)->where('payment_status', 1);


        if ($from_date && $to_date) {
            $query->whereBetween('delivered_date', [$from_date, $to_date]);
        } else {
            $query->where('delivered_date', 'LIKE', "%$from_date%");
        }

        $salesOrders = $query->with([
            'sales_order_transactions' => function ($query) use ($from_date) {
                $query->select(
                    'reference_id',
                    DB::raw('COALESCE(SUM(amount), 0) as salesAmount')
                )->groupBy('reference_id');
            }
        ])->get();
        // Calculate total sales amount
        $totalSalesAmount = 0;
        foreach ($salesOrders as $order) {
            $totalSalesAmount += $order->sales_order_transactions->sum('salesAmount');
        }

        $count = $salesOrders->count();
        Log::info('salesOrders');
        Log::info($salesOrders);

        // $totalSalesAmount = $salesOrders->sum('salesAmount');
        // $count = $salesOrders->count();

        return [
            'totalSalesAmount' => $totalSalesAmount,
            'count' => $count,
        ];
    }

    private function purchaseOrder($from_date, $to_date)
    {
        $query = PurchaseOrder::where('payment_status', 1);
        if ($from_date && $to_date) {
            $query->whereBetween('delivery_date', [$from_date, $to_date]);
        } else {
            $query->where('delivery_date', 'LIKE', "%$from_date%");
        }
        $purchaseOrders = $query
            ->with([
                'purchase_order_transactions' => function ($query) {
                    $query->select(
                        'reference_id',
                        DB::raw('COALESCE(SUM(amount), 0) as purchaseAmount')
                    )->groupBy('reference_id');
                }
            ])
            ->get();

        $totalPurchaseAmount = 0;
        foreach ($purchaseOrders as $order) {
            $totalPurchaseAmount += $order->purchase_order_transactions->sum('purchaseAmount');
        }
        // $totalPurchaseAmount = $purchaseOrders->sum('purchaseAmount');
        $count = $purchaseOrders->count();
        return [
            'totalPurchaseAmount' => $totalPurchaseAmount,
            'count' => $count,
        ];
    }

    private function salesOrderReturn($today, $from_date, $to_date)
    {
        $query = SalesOrderReturn::where('is_active', 1)->where('status', 8);
        if ($from_date && $to_date) {
            $query->whereBetween('return_date', [$from_date, $to_date]);
        } else {
            $query->where('return_date', 'LIKE', "%$from_date%");
        }
        $salesOrdersReturns = $query
            ->with(
                'sales_return_transactions',
                function ($query) {
                    $query->select(
                        'reference_id',
                        DB::raw('COALESCE(SUM(amount), 0) as salesReturnsAmount')
                    )->groupBy('reference_id');
                }
            )
            ->get();
        $totalSalesReturnsAmount = 0;
        foreach ($salesOrdersReturns as $order) {
            $totalSalesReturnsAmount += $order->sales_return_transactions->sum('salesReturnsAmount');
        }

        // $totalSalesReturnsAmount = $salesOrdersReturns->sum('salesReturnsAmount');
        $count = $salesOrdersReturns->count();

        return [
            'totalSalesReturnsAmount' => $totalSalesReturnsAmount,
            'count' => $count,
        ];
    }

    private function incomes($incomeExpenseQuery, $today, $from_date, $to_date)
    {
        $query = $incomeExpenseQuery;

        if ($from_date && $to_date) {
            $query->whereBetween('transaction_datetime', [$from_date, $to_date]);
        } else {
            $query->where('transaction_datetime', 'LIKE', "%$from_date%");
        }
        $incomeData = $query
            ->with('incomeExpensePaymentTransaction', function ($query) {
                $query->select(
                    'reference_id',
                    DB::raw('COALESCE(SUM(amount), 0) as incomeAmount'),
                )
                    ->groupBy('reference_id');
            })->get();
        $incomesAmount = 0;
        foreach ($incomeData as $order) {
            $incomesAmount += $order->incomeExpensePaymentTransaction->sum('incomeAmount');
        }
        // $incomesAmount = $incomeData->sum('incomeAmount');
        $count = $incomeData->count();

        return [
            'incomesAmount' => $incomesAmount,
            'count' => $count,
        ];
    }

    private function expenses($incomeExpenseQuery, $today, $from_date, $to_date)
    {
        $query = $incomeExpenseQuery;

        if ($from_date && $to_date) {
            $query->whereBetween('transaction_datetime', [$from_date, $to_date]);
        } else {
            $query->where('transaction_datetime', 'LIKE', "%$from_date%");
        }

        $expenseData = $query
            ->with('payment_transactions', function ($query) {
                $query->select(
                    'reference_id',
                    DB::raw('COALESCE(SUM(amount), 0) as expenseAmount'),
                )
                    ->groupBy('reference_id');
            })->get();

        $expenseAmount = 0;
        foreach ($expenseData as $order) {
            $expenseAmount += $order->payment_transactions->sum('expenseAmount');
        }
        // $expenseAmount = $expenseData->sum('expenseAmount');
        $count = $expenseData->count();
        return [
            'expenseAmount' => $expenseAmount,
            'count' => $count,
        ];
    }
}
