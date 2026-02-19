<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\CashRegisterTransaction;
use App\Models\IncomeExpenseTransaction;
use App\Models\ProductTransfer;
use App\Models\PurchaseOrder;
use App\Models\SalesOrder;
use App\Models\SalesOrderReturn;
use App\Models\Store;
use App\Models\Warehouse;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    public function dashboard()
    {
        $admin = Admin::where('id', Auth::guard('api')->user()->id)->first();

        // $sales_order = [];
        // foreach($store_ids as $key => $store_id) {
        //    $sales_order[$key]['store_id'] = $store_id;
        //    $sales_order[$key]['store_name'] = Store::find($store_id)->store_name;
        //    $sales_order[$key] ['sub_total']= SalesOrder::where('store_id', $store_id)->groupby('store_id')->sum('sub_total');
        // }

        // $product_transfer_ids = ProductTransfer::whereIn('to_store_id', $store_ids)->pluck('id')->toArray();
        // $purchase_order = ProductTransferDetail::with('product')->whereIn('product_transfer_id', $product_transfer_ids)->get();
        // $income_expenses = IncomeExpenseTransaction::with('income_expense_types')->whereIn('store_id', $store_ids)->get();

        $store_id = Auth::user()->user_stores();

        $cashregister_date = Carbon::today()->format('Y-m-d');
        $data['payment_details'] = CashRegisterTransaction::where(function ($query) use ($cashregister_date, $store_id) {
            if ($cashregister_date != null) {
                $query->whereDate('transaction_datetime', Carbon::parse($cashregister_date)->format('Y-m-d 00:00:00'));
            } else {
                $query->whereDate('transaction_datetime', Carbon::today()->format('Y-m-d 00:00:00'));
            }
            if ($store_id != null) {
                $query->whereIn('cash_register_transactions.store_id', $store_id);
            }
        })
            ->where([['cash_register_transactions.status', 1]])
            ->join('payment_types', function ($join) {
                $join->on('cash_register_transactions.payment_type_id', 'payment_types.id');
            })
            ->select('payment_category')
            ->selectRaw(DB::raw('SUM(amount) as total_amount'))
            ->groupBy('payment_category')
            ->get()
            ->map(function ($data) {
                $data['category_name'] = (isset($data->payment_category) && $data->payment_category != null) ? config('app.payment_category')[$data->payment_category - 1]['name'] : null;
                return $data;
            });

        $creditSale = SalesOrder::where(function ($query) use ($cashregister_date, $store_id) {
            if ($cashregister_date != null) {
                $query->whereDate('delivered_date', Carbon::parse($cashregister_date)->format('Y-m-d 00:00:00'));
            } else {
                $query->whereDate('delivered_date', Carbon::today()->format('Y-m-d 00:00:00'));
            }
            if ($store_id != null) {
                $query->whereIn('store_id', $store_id);
            }
        })
            ->select(DB::raw('COALESCE(SUM(total_amount), 0) as total_amount'))
            ->first();
        $creditSale['payment_category'] = 'Credit Sale';
        $creditSale['category_name'] = 'Credit Sale';

        $salesreturn = SalesOrderReturn::where(function ($query) use ($cashregister_date, $store_id) {
            if ($cashregister_date != null) {
                $query->whereDate('return_date', Carbon::parse($cashregister_date)->format('Y-m-d 00:00:00'));
            } else {
                $query->whereDate('return_date', Carbon::today()->format('Y-m-d 00:00:00'));
            }
            if ($store_id != null) {
                $query->whereIn('from_store_id', $store_id);
            }
        })
            ->where('return_from', 2)
            ->select(DB::raw('COALESCE(SUM(total_amount), 0) as total_amount'))
            ->first();
        $salesreturn['payment_category'] = 'Return Bill';
        $salesreturn['category_name'] = 'Return Bill';

        $count = $data['payment_details']->count();

        $data['payment_details'][$count++] = $creditSale;
        $data['payment_details'][$count] = $salesreturn;

        // Chart data for dashboard

        $startDate = Carbon::now()->subMonths(12);
        $endDate = Carbon::now();

        // Sales Orders

        $sales_orders = SalesOrder::where('payment_status', 1);
        $data['sales_orders_total_amount'] = $sales_orders->sum('total_amount');
        $data['sales_orders_count'] = $sales_orders->count();

        // Purchase Orders

         $purchase_orders = PurchaseOrder::where('payment_status', 1);
         $data['purchase_orders_total_amount'] = $purchase_orders->sum('total');
         $data['purchase_orders_count'] = $purchase_orders->count();

        // User Counts

        $data['customer_count'] = User::where('user_type', 1)->count();
        $data['supplier_count'] = User::where('user_type', 2)->count();

        // Monthly Orders
        $purchase_order['months'] = $sales_order['months'] = $incomeexpense['months'] = [];
        $purchase_order['count'] = $sales_order['count'] = $income['amount'] = $expense['amount'] = [];
        $expense = [];
        $income = [];
        $startDate = Carbon::now(); // Start from the current date

        for ($i = 0; $i < 12; $i++) {
            $date = $startDate->copy()->subMonths($i); // Subtract months to go backwards
            $formattedMonth = $date->format('M Y'); // Format as "Aug 2023"

            $year = $date->format('Y');
            $month = $date->format('m');

            // Create Carbon instances for the start and end of the month
            $IEstartOfMonth = Carbon::createFromDate($year, $month, 1)->startOfMonth();
            $IEendOfMonth = Carbon::createFromDate($year, $month, 1)->endOfMonth();

            // Format the dates as strings
            $startOfMonthString = $IEstartOfMonth->toDateString(); // e.g., '2023-07-01'
            $endOfMonthString = $IEendOfMonth->toDateString(); // e.g., '2023-07-31'

            // Store formatted month names
            $purchase_order['months'][$i] = $formattedMonth;
            $sales_order['months'][$i] = $formattedMonth;
            $incomeexpense['months'][$i] = $formattedMonth;

            // Query for purchase order count, sales order count, and amounts
            $purchase_order['count'][$i] = PurchaseOrder::whereBetween('delivery_date', [$startOfMonthString, $endOfMonthString])->where('payment_status', 1)->count();
            $sales_order['count'][$i] = SalesOrder::whereBetween('delivered_date', [$startOfMonthString, $endOfMonthString])->where('payment_status', 1)->count();

            $purchase_order['amount'][$i] = PurchaseOrder::whereBetween('delivery_date', [$startOfMonthString, $endOfMonthString])->where('payment_status', 1)->sum('total');
            $sales_order['amount'][$i] = SalesOrder::whereBetween('delivered_date', [$startOfMonthString, $endOfMonthString])->where('payment_status', 1)->sum('total_amount');

            // $income['amount'][] = IncomeExpenseTransaction::whereBetween('transaction_datetime', [$startOfMonthString, $endOfMonthString])->where('income_expense_type_id', 1)->where('status', 1)->sum('total_amount');
            // $expense['amount'][] = IncomeExpenseTransaction::whereBetween('transaction_datetime', [$startOfMonthString, $endOfMonthString])->where('income_expense_type_id', 2)->where('status', 1)->sum('total_amount');
        }

        // Branch Wiase Report

        $branchWiseSalesOrdersCount = $this->branchWiseSalesOrders();
        $branchWisePurchaseOrderCount = $this->branchWisePurchaseOrder();
        $branchwiseIncomeAndExpenseData = $this->branchwiseIncomeAndExpenseData();
        // Pass the result to the view

         $data['storeWiseSalesOrdersCount'] = $branchWiseSalesOrdersCount;
         $data['storeWiseProductTransferCount'] = $branchWisePurchaseOrderCount;
         $data['branchwiseIncomeAndExpenseData'] = $branchwiseIncomeAndExpenseData;

        return response()->json([
            'status' => 200,
            'data' => "Dashboard",
            'message' => 'Welcome Mr.' . $admin->name,
            'sales_order' => $sales_order,
            'purchase_order' => $purchase_order,
            'incomeexpense' => $incomeexpense,
            'expense' => $expense,
            'income' => $income,
            'data' => $data,

        ]);
    }

    public function branchwiseIncomeAndExpenseData()
    {
        $stores = Store::all();
        $branchwiseIncomeAndExpenseData = [];
        $totalExpenseAmount = 0;
        $totalIncomeAmount = 0;
        foreach ($stores as $key => $store) {
            $store_id = $store->id;
            $storeName = $store->store_name;

            $totalIncome = IncomeExpenseTransaction::where('store_id', $store_id)
                ->where('income_expense_type_id', 1)
                ->where('status', 1)
                ->sum('total_amount');

            $totalExpense = IncomeExpenseTransaction::where('store_id', $store_id)
                ->where('income_expense_type_id', 2)
                ->where('status', 1)
                ->sum('total_amount');

            $branchwiseIncomeAndExpenseData[$key]['branch_name'] = $storeName;
            $branchwiseIncomeAndExpenseData[$key]['totalIncome'] = $totalIncome;
            $branchwiseIncomeAndExpenseData[$key]['total_order_amount'] = $totalExpense;
            $totalIncomeAmount = (int) $totalIncome + (int) $totalIncomeAmount;
            $totalExpenseAmount = (int) $totalExpense + (int) $totalExpenseAmount;
        }
        $branchwiseIncomeAndExpenseData['total_income'] = $totalIncomeAmount;
        $branchwiseIncomeAndExpenseData['total_expense'] = $totalExpenseAmount;
        // Return branch-wise income and expense data
        return $branchwiseIncomeAndExpenseData;
    }

    public function branchWisePurchaseOrder()
    {
        $warehouseWiseProductTransferCount = [];
        $totalSalesOrder = 0;
        $warehouses = Warehouse::all();
        foreach ($warehouses as $key => $warehouse) {
            $warehouse_id = $warehouse->id;
            $warehouseName = $warehouse->name;

            $warehouseWiseCount = PurchaseOrder::where('warehouse_id', $warehouse_id)->where('payment_status', 1)->sum('total');

            $warehouseWiseProductTransferCount[$key]['branch_name'] = $warehouseName;
            $warehouseWiseProductTransferCount[$key]['total_order_amount'] = $warehouseWiseCount;
            $totalSalesOrder = (int) $warehouseWiseCount + (int) $totalSalesOrder;

        }
        $warehouseWiseProductTransferCount['totalAmount'] = $totalSalesOrder;

        // Return store-wise Product Transfer count
        return $warehouseWiseProductTransferCount;
    }

    public function branchWiseSalesOrders()
    {
        $storeWiseSalesOrdersCount = [];

        $stores = Store::all();
        $totalSalesOrder = 0;
        // $totalSalesOrderAmount = 0;

        foreach ($stores as $key => $store) {
            $store_id = $store->id;
            $storeName = $store->store_name;

            $storeWise = SalesOrder::where('store_id', $store_id)
                ->where('payment_status', 1);
                // ->where('status', 8);

            $storeWiseAmount = $storeWise->sum('total_amount');
            // $storeWiseCount =  $storeWise->count();

            $storeWiseSalesOrdersCount[$key]['branch_name'] = $storeName;
            $storeWiseSalesOrdersCount[$key]['total_order_amount'] = $storeWiseAmount;

            $totalSalesOrder = (int) $storeWiseAmount + (int) $totalSalesOrder;
            // $totalSalesOrderAmount = (int) $storeWiseAmount + (int) $totalSalesOrderAmount ;
        }
        $storeWiseSalesOrdersCount['total_sales_orders'] = $totalSalesOrder;
        // $storeWiseSalesOrdersCount['total_sales_orders_amount'] = $totalSalesOrderAmount;
        // Return store-wise sales orders count
        return $storeWiseSalesOrdersCount;
    }
}
