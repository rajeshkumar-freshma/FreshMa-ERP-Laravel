<?php

namespace App\Http\Controllers\Admin\Sales;

use App\Core\CommonComponent;
use App\Http\Controllers\Controller;
use DataTables;
use Illuminate\Http\Request;
use App\Models\LiveSalesBill;
use App\Models\MachineData;
use App\Models\MachineSalesBill;
use App\Models\SalesOrder;
use App\Models\SalesOrderDetail;
use App\Exports\MachineExport;
use App\Models\Store;
use App\Models\Warehouse;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;
use Log;

class SalesReportController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $date_filter_added = false;
        // Retrieve the input values from the request

        $data['machine_details'] = MachineData::get();
        $data['warehouse'] = Warehouse::get();
        $data['stores'] = Store::get();
        if ($request->ajax()) {
            $store_id = $request->input('store_id');
            $from_date = $request->input('from_date');
            $to_date = $request->input('to_date');
            $machine_id = $request->input('machine_id');
            $warehouse_id = $request->input('warehouse_id');
            // Log the received data to ensure it's being received correctly

            Log::info("from_date: " . $from_date);
            Log::info("to_date: " . $to_date);
            if ($from_date != null && $to_date  != null) {
                $date_filter_added = true;
                $from_date = Carbon::parse($from_date)->format('Y-m-d 00:00:00');
                $to_date = Carbon::parse($to_date)->format('Y-m-d 23:59:59');
            } else {
                $from_date = '';
                $to_date = '';
            }

            // return $live_sale_bill_datas = MachineData::whereHas('live_sales_bills', function ($q) use ($request, $from_date, $to_date) {
            //     return $q->where(function ($q) use ($request, $from_date, $to_date) {
            //         if ($request->machine_id != null) {
            //             $q->where('live_sales_bills.MachineName', $request->machine_id);
            //         }
            //         if ($from_date != null && $to_date != null) {
            //             $q->where([['live_sales_bills.ItemsaleDateTime', '>=', $from_date], ['live_sales_bills.ItemsaleDateTime', '<=', $to_date]]);
            //         }
            //         return $q;
            //     });
            // })
            //     ->with('live_sales_bills')
            //     ->toSql();
            // ->get();
            // Log::info($live_sale_bill_datas);
            $sales_order_datas = SalesOrder::where(function ($query) use ($store_id, $from_date, $to_date, $machine_id, $warehouse_id) {
                if ($store_id != null) {
                    Log::info("store_id: " . $store_id);
                    $query->where('store_id', $store_id);
                }
                if ($machine_id != null) {
                    $query->where('machine_id', $machine_id);
                }
                if ($warehouse_id != null) {
                    $query->where('warehouse_id', $warehouse_id);
                }
                if ($from_date != null && $to_date != null) {
                    $query->whereBetween('delivered_date', [$from_date, $to_date]);
                }
            })
                ->select('id', 'bill_no', 'machine_id', 'status', 'payment_status')
                ->get()
                ->groupBy('machine_id');
            // Log::info("sales order datas:" . $sales_order_datas);
            // ->map(function($item, $key) {
            //     Log::info($item->pluck('MachineName')->toArray());
            //     $detailsData = LiveSalesBill::sale_bill_datas($item->pluck('billNo')->toArray(), $item->pluck('MachineName')->toArray());
            //     return [
            //         'MachineName' => MachineData::findOrFail($key),
            //         'order_count' => count($detailsData),
            //         'order_amount' => $detailsData->sum('price')
            //     ];
            // });

            $count = 0;
            $loop_count = 1;
            $datas = [];
            if (count($sales_order_datas) > 0) {
                foreach ($sales_order_datas as $key => $sales_order_data) {
                    Log::info($key);
                    if (isset($key) && !empty($key)) {
                        $machine_details = MachineData::findOrFail($key);
                        if ($machine_details != null) {
                            Log::info('Sales order after processing');
                            Log::info($sales_order_data->pluck('id')->toArray());
                            $detailsData = SalesOrder::select('total_amount')->whereIn('id', $sales_order_data->pluck('id')->toArray())->get();
                            $datas[$count]['id'] = $loop_count++;
                            $datas[$count]['machine_name'] = $machine_details->MachineName;
                            $datas[$count]['machine_id'] = $machine_details->id;
                            $datas[$count]['store_id'] = $machine_details->store_id;
                            $datas[$count]['machine_name'] = $machine_details->MachineName;
                            $datas[$count]['order_count'] = count($sales_order_data->pluck('id')->toArray());
                            $datas[$count]['created_at'] = CommonComponent::getCreatedAtFormat($machine_details['created_at']);
                            if ($detailsData !== null) {
                                $datas[$count]['total_order_amount'] = $detailsData->sum('total');
                            } else {
                                $datas[$count]['total_order_amount'] = 'N/A';
                            }

                            $count++;
                        }
                    }
                }
            }

            return DataTables::of($datas)
                ->rawColumns(['store_id','machine_name', 'order_count', 'total_order_amount','created_at', 'action'])
                ->editColumn('id', function ($datas) {
                    return @$datas['id'];
                })
                ->addColumn('machine_id', function ($datas) {
                    return @$datas['machine_id'];
                })
                ->addColumn('order_count', function ($datas) {
                    return @$datas['order_count'];
                })
                ->addColumn('total_order_amount', function ($datas) {
                    return number_format((@$datas['total_order_amount']), 2);
                })
                ->addColumn('created_at', function ($datas) {
                    return @$datas['created_at'];
                })
                ->addColumn('action', function ($datas) use ($date_filter_added, $from_date, $to_date) {
                    if ($date_filter_added) {
                        $query_string = '?machine_id=' . @$datas['machine_id'] . '&from_date=' . $from_date . '&to_date=' . $to_date;
                    } else {
                        $query_string = '?machine_id=' . @$datas['machine_id'];
                    }

                    return view('pages.report.sales_report._action-menu', compact('datas', 'query_string'));
                })
                ->make(true);
        }
        return view('pages.report.sales_report.report', $data);
    }

//Method for to getting search results for Machine Sales Report
    public function machineSalesExport(Request $request) {

        $data['machine_details'] = MachineData::get();
        $data['warehouse'] = Warehouse::get();
        $data['stores'] = Store::get();

        $machine_id = $request->machine_id;
        $warehouse_id = $request->warehouse_id;
        $store_id = $request->store_id;
        $from_date = $request->from_date;
        $to_date = $request->to_date;

        $data['machine_id'] = $machine_id;
        $data['warehouse_id'] = $warehouse_id;
        $data['store_id'] = $store_id;
        $data['from_date'] = $from_date;
        $data['to_date'] = $to_date;

        $sales_order_datas = SalesOrder::where(function ($query) use ($store_id, $from_date, $to_date, $machine_id, $warehouse_id) {
            if ($store_id != null) {
                Log::info("store_id: " . $store_id);
                $query->where('store_id', $store_id);
            }
            if ($machine_id != null) {
                $query->where('machine_id', $machine_id);
            }
            if ($warehouse_id != null) {
                $query->where('warehouse_id', $warehouse_id);
            }
            if ($from_date != null && $to_date != null) {
                $query->whereBetween('delivered_date', [$from_date, $to_date]);
            }
        })->select('id', 'bill_no', 'machine_id', 'status', 'payment_status')
            ->get()
            ->groupBy('machine_id');

            $count = 0;
            $loop_count = 1;
            $datas = [];
            if (count($sales_order_datas) > 0) {
                foreach ($sales_order_datas as $key => $sales_order_data) {
                    Log::info($key);
                    if (isset($key) && !empty($key)) {
                        $machine_details = MachineData::findOrFail($key);
                        if ($machine_details != null) {
                            Log::info('Sales order after processing');
                            Log::info($sales_order_data->pluck('id')->toArray());
                            $detailsData = SalesOrder::select('total_amount')->whereIn('id', $sales_order_data->pluck('id')->toArray())->get();
                            $datas[$count]['id'] = $loop_count++;
                            $datas[$count]['machine_name'] = $machine_details->MachineName;
                            $datas[$count]['machine_id'] = $machine_details->id;
                            $datas[$count]['order_count'] = count($sales_order_data->pluck('id')->toArray());
                            if ($detailsData->isNotEmpty()) {
                                $datas[$count]['total_order_amount'] = $detailsData->sum('total_amount');
                            } else {
                                $datas[$count]['total_order_amount'] = 'N/A'; // or any other default value
                            }

                            $count++;
                        }
                    }
                }
            }

        return Excel::download(new MachineExport($datas), 'machinesalesreport.xlsx');
    }


}
