<?php

namespace App\Http\Controllers\Api\Report;

use App\Core\CommonComponent;
use App\Exports\StoreStockExport;
use App\Http\Controllers\Controller;
use App\Jobs\StoreStockExportJob;
use App\Models\CashRegisterTransaction;
use App\Models\Product;
use App\Models\ProductTransfer;
use App\Models\SalesOrder;
use App\Models\SalesOrderReturn;
use App\Models\Store;
use App\Models\StoreIndentRequest;
use App\Models\StoreIndentRequestDetail;
use App\Models\VendorIndentRequestDetail;
use App\Models\Warehouse;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;

class ReportController extends Controller
{
    public function store_stock_report(Request $request)
    {
        Log::info("entered controller");
        // try {

        if ($request->pagination == 'all') {
            $pagination = Product::count();
        } else {
            $pagination = $request->pagination ?? 30;
        }

        $from_date = $request->from_date;
        $to_date = $request->to_date;
        if ($request->store_id != null) {
            $store_id = array($request->store_id);
            $stores = Store::whereIn('id', $store_id)->get();
        } else {
            $store_id = Auth::user()->user_stores();
            $stores = Store::whereIn('id', $store_id)->get();
        }

        // Bus::batch([
        //     new StoreStockExportJob($from_date, $to_date, $store_id),
        // ])->dispatch();

        $products = Product::select('id', 'name', 'sku_code', 'unit_id', 'image')->with('unit')->paginate($pagination);
        $widthArray = [180, 170, 170, 170, 170, 170];
        $stock_datas = [];
        $headers = ['Items'];
        foreach ($stores as $keys => $store) {
            $headers[] = $store->store_name;
        }

        foreach ($products as $key => $product) {
            $stock_datas[$key][] = $product->name;
            foreach ($stores as $keys => $store) {
                $value = Product::productstockdetailsforreport($product->id, $store->id, $from_date, $to_date, $store->store_name, @$product->unit->unit_short_code);
                $stock_datas[$key][] = $value;
            }
        }

        return response()->json([
            'status' => 200,
            "headers" => $headers,
            "widthArray" => $widthArray,
            "data" => $stock_datas,
            'message' => 'Data fetched Successfully.',
        ]);
        // return $this->paginate($stock_datas, 10);
        // return Excel::download(new StoreStockExport($from_date, $to_date,$store_id), 'user.csv');

        // } catch (\Throwable $th) {
        //     return response()->json([
        //         'status' => 400,
        //         'error' => $th->getMessage(),
        //     ]);
        // }
    }

    public function paginate($items, $perPage)
    {
        try {
            $pageStart = \Request::get('page', 1);
            // Start displaying items from this number;
            $offSet = $pageStart * $perPage - $perPage;

            // Get only the items you need using array_slice
            $itemsForCurrentPage = array_slice($items, $offSet, $perPage, true);

            return new LengthAwarePaginator(array_values($itemsForCurrentPage), count($items), $perPage, Paginator::resolveCurrentPage(), ['path' => Paginator::resolveCurrentPath()]);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 400,
                'message' => $th->getMessage(),
            ]);
        }
    }

    public function stockdistributereport(Request $request)
    {
        $from_date = $request->from_date;
        $to_date = $request->to_date;
        if ($request->store_id != null) {
            $store_id = array($request->store_id);
            $stores = Store::whereIn('id', $store_id)->get();
        } else {
            $store_id = Auth::user()->user_stores();
            $stores = Store::whereIn('id', $store_id)->get();
        }

        $user_warehouse = Auth::user()->user_warehouse();
        $warehouses = Warehouse::whereIn('id', function ($query) use ($store_id, $user_warehouse) {
            $query->from('stores')->select('warehouse_id')->whereIn('id', $store_id)->whereIn('warehouse_id', $user_warehouse)->groupBy('warehouse_id')->get();
        })
            ->where('status', 1)
            ->get();

        $products = Product::select('id', 'name', 'sku_code', 'unit_id', 'image')->with('unit')->get();

        foreach ($products as $key => $product) {
            $stock_datas[$key][] = $product->name;
            foreach ($warehouses as $keys => $warehouse) {
                foreach ($stores as $keys => $store) {
                    $value = Product::productstockdetailsforreport($product->id, $store->id, $from_date, $to_date, $store->store_name, @$product->unit->unit_short_code);
                    // $explodeValue = explode(",", $value);
                    $stock_datas[$key][] = $value;
                    // $stock_datas[$key][] = "Opening Stock : " . $explodeValue[0] . "; Closing Stock : " . $explodeValue[1];
                }
            }
        }

        return $product_transfer = ProductTransfer::Join('product_transfer_details', function ($join) {
            $join->on('product_transfers.id', 'product_transfer_details.product_transfer_id');
        })
            ->where(function ($query) use ($from_date, $to_date) {
                if ($from_date != null && $to_date != null) {
                    $dateformatwithtime = CommonComponent::dateformatwithtime($from_date, $to_date);
                    $query->where('transfer_created_date', $dateformatwithtime);
                }
            })
            ->where(function ($query) use ($store_id) {
                if (count($store_id) > 0) {
                    $query->whereIn('from_store_id', $store_id)
                        ->orWhereIn('to_store_id', $store_id);
                }
            })
            ->get();
    }

    public function productsalereport(Request $request)
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

        return response()->json([
            'status' => 200,
            "chart_data" => $data['payment_details'],
            'message' => 'Data fetched Successfully.',
        ]);
    }

    public function productwisesalereport(Request $request)
    {
        if ($request->pagination == 'all') {
            $pagination = Product::count();
        } else {
            $pagination = $request->pagination ?? 30;
        }
        $product_id = $request->product_id;
        $products = Product::where(function ($query) use ($product_id) {
            if ($product_id != null) {
                $query->where('id', $product_id);
            }
        })
            ->select('id', 'name', 'sku_code')
            ->paginate($pagination);

        foreach ($products as $key => $product) {
            $product['product_sale_datas'] = $product->product_sale_datas;
        }

        $widthArray = [180, 170, 170, 170];
        $widthArray = ["Id", "Product Name", "Sku Code", "Product Sale Details"];

        return response()->json([
            'status' => 200,
            "data" => $products,
            "widthArray" => $widthArray,
            'message' => 'Data fetched Successfully.',
        ]);
    }

    public function productwisereport(Request $request)
    {
        Log::info("entered controller");
        // try {

        $from_date = $request->from_date;
        $to_date = $request->to_date;
        if ($request->store_id != null) {
            $store_id = array($request->store_id);
        } else {
            $store_id = Auth::user()->user_stores();
        }

        $products = Product::select('id', 'name', 'sku_code', 'unit_id', 'image')->with('unit')->paginate(30);
        $widthArray = [180, 170, 170, 170, 170, 170];
        $stock_datas = [];
        $headers = ['Items', 'Product Name', 'Unit', 'Rate', 'Amount'];

        foreach ($products as $key => $product) {
            $stock_datas[$key][] = $product->id;
            $stock_datas[$key][] = $product->name;
            foreach ($headers as $keys => $header) {
                $unitArray = [];
                $rateArray = [];
                $amountArray = [];
                $values = Product::product_wise_sale_datas($product->id, $store_id, $from_date, $to_date);
                foreach ($values as $key3 => $data) {
                    $unitArray[] = $data->total_unit . '(' . $data->sale_count . ')';
                    $rateArray[] = $data->per_unit_price;
                    $amountArray[] = $data->total_amount;
                }
                if ($keys == 2) {
                    $stock_datas[$key][] = $unitArray;
                }
                if ($keys == 3) {
                    $stock_datas[$key][] = $rateArray;
                }
                if ($keys == 4) {
                    $stock_datas[$key][] = $amountArray;
                }
            }
        }

        return response()->json([
            'status' => 200,
            "headers" => $headers,
            "widthArray" => $widthArray,
            "data" => $stock_datas,
            'message' => 'Data fetched Successfully.',
        ]);
    }

    public function commonreportdata(Request $request)
    {
        if ($request->report_id == 10) {
            return $this->productwiseindentrequestdata($request);
        } elseif ($request->report_id == 15) {
            return $this->store_stock_report($request);
        } else if ($request->report_id == 16) {
            return $this->productwisesalereport($request);
        } else {
            return response()->json([
                'status' => 400,
                'message' => 'Please Choose Report'
            ]);
        }
        // if ((!empty($id) && $id != "")) {
        //     $data = CommonComponent::arraypositionconversion(config('app.report_types'));
        //     return $function_name = $data[$id]['func_name'].'()';
        //     return $this->$data[$id]['func_name'];
        //     return (!empty($data[$id]) && $data[$id] != "") ? $this->$data[$id]['func_name'] : $this->$data[1]['func_name'];
        // } else {
        //     return response()->json([
        //         'status' =>400,
        //         'message' => 'Please Choose Report'
        //     ]);
        // }
    }

    public function productwiseindentrequestdata($request)
    {
        $from_date = $request->from_date;
        $to_date = $request->to_date;
        if ($request->store_id != null) {
            $store_id = array($request->store_id);
            $stores = Store::whereIn('id', $store_id)->get();
        } else {
            $store_id = Auth::user()->user_stores();
            $stores = Store::whereIn('id', $store_id)->get();
        }

        $products = Product::select('id', 'name', 'sku_code', 'unit_id', 'image')->with('unit')->get();
        $widthArray = [180, 170, 170, 170, 170, 170];
        $indent_request_data = [];
        $headers = ['Fish'];

        foreach ($stores as $keys => $store) {
            $headers[] = $store->store_name;
        }
        array_push($headers, 'Customer/Vendort');
        array_push($headers, 'Total');
        foreach ($products as $key => $product) {
            $total = 0;
            $indent_request_data[$key][] = $product->name;
            foreach ($stores as $keys => $store) {
                $store_id = $store->id;
                $store_indent_data = StoreIndentRequestDetail::whereHas('store_indent_request', function ($query) use ($store_id, $from_date, $to_date) {
                    if ($store_id != null) {
                        $query->where('store_id', $store_id);
                    }
                    if ($from_date != null && $to_date != null) {
                        $query->whereBetween('expected_date', [$from_date, $to_date]);
                    }
                })
                    ->where('product_id', $product->id)
                    ->select(DB::raw('COALESCE(request_quantity, 0) as request_quantity'))
                    ->first();
                $indent_request_data[$key][] = $store_indent_data != null ? $store_indent_data->request_quantity : '-';
                $total += $store_indent_data != null ? $store_indent_data->request_quantity : 0;
            }
            $vendor_indent_data = VendorIndentRequestDetail::whereHas('vendor_indent_request', function ($query) use ($store_id, $from_date, $to_date) {
                if ($from_date != null && $to_date != null) {
                    $query->whereBetween('expected_date', [$from_date, $to_date]);
                }
            })
                ->where('product_id', $product->id)
                ->select(DB::raw('COALESCE(request_quantity, 0) as request_quantity'))
                ->first();

            $customer_req_quantity = $vendor_indent_data != null ? $vendor_indent_data->request_quantity : 0;

            $indent_request_data[$key][] = $customer_req_quantity;
            $indent_request_data[$key][] = number_format($total + $customer_req_quantity, 3);
        }

        return response()->json([
            'status' => 200,
            "headers" => $headers,
            "widthArray" => $widthArray,
            "data" => $indent_request_data,
            'message' => 'Data fetched Successfully.',
        ]);
    }
}
