<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CashRegisterTransaction;
use App\Models\City;
use App\Models\IncomeExpenseTransaction;
use App\Models\Product;
use App\Models\ProductTransfer;
use App\Models\PurchaseOrder;
use App\Models\SalesOrder;
use App\Models\SalesOrderReturn;
use App\Models\State;
use App\Models\Store;
use App\Models\StoreIndentRequest;
use App\Models\TaxRate;
use App\Models\Unit;
use App\Models\User;
use App\Models\VendorIndentRequest;
use App\Models\WarehouseIndentRequest;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use \Carbon\Carbon;

class HomeController extends Controller
{
    public function home(Request $request)
    {

        if ($request->store_id != null) {
            $store_id = array($request->store_id);
        } else {
            $store_id = Auth::user()->user_stores();
        }

        if ($request->date != null) {
            $cashregister_date = $request->date;
        } else {
            $cashregister_date = Carbon::today()->format('Y-m-d');
        }

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

        $sales_orders = SalesOrder::where('payment_status', 1)->where('status', 8);
        $data['sales_orders_total_amount'] = $sales_orders->sum('total_amount');
        $data['sales_orders_count'] = $sales_orders->count();

        // Purchase Orders

        $Pruchase_orders = PurchaseOrder::where('payment_status', 1);
        $data['Pruchase_orders_total_amount'] = $Pruchase_orders->sum('total');
        $data['Pruchase_orders_count'] = $Pruchase_orders->count();

        // User Counts

        $data['customer_count'] = User::where('user_type', 1)->count();
        $data['supplier_count'] = User::where('user_type', 2)->count();

        // Monthly Orders
        $purchase_order['months'] = $sales_order['months'] = $incomeexpense['months'] = [];
        $purchase_order['count'] = $sales_order['count'] = $income['amount'] = $Expense['amount'] = [];

        for ($i = 1; $i <= 12; $i++) {
            $date = $startDate->copy()->addMonths($i);
            $month = $date->format('m');
            $year = $date->format('Y');
            $formattedMonth = $date->format('M Y');

            $purchase_order['months'][] = $formattedMonth;
            $sales_order['months'][] = $formattedMonth;
            $incomeexpense['months'][] = $formattedMonth;

            $purchase_order['count'][] = PurchaseOrder::whereMonth('delivery_date', $month)
                ->whereYear('delivery_date', $year)->where('payment_status', 1)->count();
            $sales_order['count'][] = SalesOrder::whereMonth('delivered_date', $month)
                ->whereYear('delivered_date', $year)->where('payment_status', 1)->where('status', 8)->count();

            // Accumulate income and expense for each month
            $income['amount'][] = IncomeExpenseTransaction::whereMonth('transaction_datetime', $month)
                ->whereYear('transaction_datetime', $year)->where('income_expense_type_id', 1)->where('status', 1)->sum('total_amount');
            $expense['amount'][] = IncomeExpenseTransaction::whereMonth('transaction_datetime', $month)
                ->whereYear('transaction_datetime', $year)->where('income_expense_type_id', 2)->where('status', 1)->sum('total_amount');
        }
        // Branch Wiase Report

        $branchWiseSalesOrdersCount = $this->branchWiseSalesOrders();
        $branchWiseProductTransferCount = $this->branchWiseProductTransfer();
        $branchwiseIncomeAndExpenseData = $this->branchwiseIncomeAndExpenseData();
        // Pass the result to the view

        $data['storeWiseSalesOrdersCount'] = $branchWiseSalesOrdersCount;
        $data['storeWiseProductTransferCount'] = $branchWiseProductTransferCount;
        $data['branchwiseIncomeAndExpenseData'] = $branchwiseIncomeAndExpenseData;
        return view('pages.dashboards.index', $data, compact('purchase_order', 'sales_order', 'income', 'expense', 'incomeexpense'));
    }

    public function branchWiseSalesOrders()
    {
        $storeWiseSalesOrdersCount = [];

        $stores = Store::all();
        foreach ($stores as $store) {
            $store_id = $store->id;
            $storeName = $store->store_name;

            $storeWiseCount = SalesOrder::where('store_id', $store_id)
                ->where('payment_status', 1)
                ->where('status', 8)
                ->count();

            $storeWiseSalesOrdersCount[$storeName] = $storeWiseCount;
        }

        // Return store-wise sales orders count
        return $storeWiseSalesOrdersCount;
    }

    public function branchWiseProductTransfer()
    {
        $storeWiseProductTransferCount = [];

        $stores = Store::all();
        foreach ($stores as $store) {
            $store_id = $store->id;
            $storeName = $store->store_name;

            $storeWiseCount = ProductTransfer::where('from_store_id', $store_id)
                ->count();
            $storeWiseProductTransferCount[$storeName] = $storeWiseCount;
        }

        // Return store-wise Product Transfer count
        return $storeWiseProductTransferCount;
    }

    public function branchwiseIncomeAndExpenseData()
    {
        $stores = Store::all();
        $branchwiseIncomeAndExpenseData = [];

        foreach ($stores as $store) {
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

            $branchwiseIncomeAndExpenseData[$storeName] = [
                'totalIncome' => $totalIncome,
                'totalExpense' => $totalExpense,
            ];
        }

        // Return branch-wise income and expense data
        return $branchwiseIncomeAndExpenseData;
    }

    public function getstate(Request $request)
    {
        $states = State::where("country_id", $request->country_id)->where('status', 1)
            ->get(["name", "id"]);
        return response()->json(['status' => 200, 'states' => $states]);
    }

    public function getcity(Request $request)
    {
        $cities = City::where("state_id", $request->state_id)->where('status', 1)
            ->get(["name", "id"]);
        return response()->json(['status' => 200, 'cities' => $cities]);
    }

    public function autocomplete(Request $request)
    {
        $products = Product::where("name", 'LIKE', '%' . $request->name . '%')->where('status', 1)
            ->get(["name", "sku_code", "id"]);

        $i = 0;
        $getdata = [];
        foreach ($products as $res) {
            $data['label'] = $res->sku_code == null ? $res->name : $res->name . '-' . $res->sku_code;
            $data['value'] = $res->id;
            $getdata[$i++] = $data;
        }

        return response()->json(['status' => 200, 'data' => $getdata]);
    }

    public function getproductdetails(Request $request)
    {
        $data['product'] = Product::findOrfail($request->id);
        $data['units'] = Unit::active()->get();
        $data['tax_rates'] = TaxRate::active()->get();
        $data['count'] = $request->count;
        $data['amountdisplay'] = $request->amountdisplay;
        $data['subtotaldisplay'] = $request->subtotaldisplay;
        $data['given_quantity_display'] = $request->given_quantity_display;
        $data['taxanddiscountdisplay'] = $request->taxanddiscountdisplay;
        $data['unit_display'] = $request->unit_display;
        $data['type_display'] = $request->type_display;
        $data['expense_display'] = $request->expense_display;
        $data['commission_and_expense_display'] = $request->commission_and_expense_display;
        $data['vendor_percentage'] = $request->vendor_percentage;
        $data['is_editable'] = $request->is_editable;
        $data['unit_id'] = $request->unit_id;
        $data['quantity'] = $request->quantity;
        return view('pages.partials.product_search.itemrender', $data)->render();
    }

    public function get_indent_request_product_details(Request $request)
    {
        if ($request->request_type == 'vendor_indent_requests') {
            $indent_request = VendorIndentRequest::findOrfail($request->indent_request_id);
            $data['indent_request_details'] = $indent_request->vendor_indent_product_details;
            $data['is_editable'] = $request->is_editable;
        }

        if ($request->request_type == 'store_indent_requests') {
            $indent_request = StoreIndentRequest::findOrfail($request->indent_request_id);
            $data['indent_request_details'] = $indent_request->store_indent_product_details;
            $data['is_editable'] = 1;
        }

        if ($request->request_type == 'warehouse_indent_requests') {
            $indent_request = WarehouseIndentRequest::findOrfail($request->indent_request_id);
            $data['indent_request_details'] = $indent_request->warehouse_indent_product_details;
            $data['is_editable'] = 1;
        }

        if ($request->request_type == 'sales_order') {
            $indent_request = SalesOrder::findOrfail($request->indent_request_id);
            $data['indent_request_details'] = $indent_request->product_details;
            $data['is_editable'] = 1;
        }

        if ($request->request_type == 'purchase_order') {
            $indent_request = PurchaseOrder::findOrfail($request->indent_request_id);
            $data['indent_request_details'] = $indent_request->purchase_order_product_details;
            $data['is_editable'] = 1;
        }

        $data['units'] = Unit::active()->get();
        $data['tax_rates'] = TaxRate::active()->get();
        $data['count'] = $request->count;
        $data['amountdisplay'] = $request->amountdisplay;
        $data['subtotaldisplay'] = $request->subtotaldisplay;
        $data['given_quantity_display'] = $request->given_quantity_display;
        $data['taxanddiscountdisplay'] = $request->taxanddiscountdisplay;
        $data['unit_display'] = $request->unit_display;
        $data['type_display'] = $request->type_display;
        $data['expense_display'] = $request->expense_display;
        $data['commission_and_expense_display'] = $request->commission_and_expense_display;
        $data['vendor_percentage'] = $request->vendor_percentage;
        $data['unit_id'] = $request->unit_id;
        $data['quantity'] = $request->quantity;
        return view('pages.partials.product_search.multi_item_render', $data)->render();
    }
}
