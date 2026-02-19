<?php

namespace App\Http\Controllers\Admin\Master;

use App\Core\CommonComponent;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\DataTables\Master\StoreDataTable;
use App\Http\Requests\Master\StoreFormRequest;
use App\Models\CashPaidToOffice;
use App\Models\CashRegister;
use App\Models\CashRegisterTransaction;
use App\Models\Store;
use App\Models\Warehouse;
use App\Models\Country;
use App\Models\PaymentTransaction;
use App\Models\SalesOrder;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class StoreController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(StoreDataTable $dataTable)
    {
        return $dataTable->render('pages.master.store.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data['warehouses'] = Warehouse::where('status', 1)->get();
        $data['countries'] = Country::where('status', 1)->get();
        return view('pages.master.store.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreFormRequest $request)
    {
        // try {
        $slug = commoncomponent::slugCreate($request->store_name, $request->slug);

        $store = new Store();
        $store->store_name = $request->store_name;
        $store->slug = $slug;
        $store->store_code = $request->store_code;
        $store->warehouse_id = $request->warehouse_id;
        $store->phone_number = $request->phone_number;
        $store->email = $request->email;
        $store->start_date = $request->start_date;
        $store->gst_number = $request->gst_number;
        $store->address = $request->address;
        $store->city_id = $request->city_id;
        $store->state_id = $request->state_id;
        $store->country_id = $request->country_id;
        $store->pincode = $request->pincode;
        $store->longitude = $request->longitude;
        $store->latitude = $request->latitude;
        $store->direction = $request->direction;
        $store->status = $request->status;
        $store->save();

        if ($request->submission_type == 1) {
            return redirect()->route('admin.store.index')->with('success', 'Store Store Successfully');
        } elseif ($request->submission_type == 2) {
            return back()->with('success', 'Store Store Successfully');
        }
        // } catch (\Exception $e) {
        //     Log::error($e);
        //     return back()->withInput()->with('error', 'Store Updated Fail');
        // }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {
        try {
            $data['warehouses'] = Warehouse::where('status', 1)->get();
            $data['countries'] = Country::where('status', 1)->get();
            $data['stores'] = Store::findOrFail($id);

            $data['is_opened'] = 0;
            $data['total_amount'] = 0;
            $data['open_close_amount'] = 0;
            $data['total_sale_amount'] = 0;
            $data['total_pending_amount'] = 0;
            $data['total_expense_amount'] = 0;
            $data['today_received_credit_amount'] = 0;
            $data['payment_details'] = [];
            if ($request->date != null) {
                $cashregister_date = $request->date;
            } else {
                $cashregister_date = Carbon::now()->format('Y-m-d');
            }
            $store_id = $request->store_id ?? $id;
            $cash_register = CashRegister::where(function ($query) use ($cashregister_date, $store_id) {
                if ($cashregister_date != null) {
                    $query->whereDate('open_close_time', Carbon::parse($cashregister_date)->format('Y-m-d 00:00:00'));
                } else {
                    $query->whereDate('open_close_time', Carbon::now()->format('Y-m-d 00:00:00'));
                }
                if ($store_id != null) {
                    $query->where('store_id', $store_id);
                }
            })
                ->where('status', 1)
                ->with('cash_register_transactions')
                ->orderBy('id', 'DESC')
                ->first();

            $store_opened = 0;
            if ($cash_register != null) {
                $data['payment_details'] = CashRegisterTransaction::where(function ($query) use ($cashregister_date, $store_id) {
                    if ($cashregister_date != null) {
                        $query->whereDate('transaction_datetime', Carbon::parse($cashregister_date)->format('Y-m-d 00:00:00'));
                    } else {
                        $query->whereDate('transaction_datetime', Carbon::now()->format('Y-m-d 00:00:00'));
                    }
                    if ($store_id != null) {
                        $query->where('cash_register_transactions.store_id', $store_id);
                    }
                })
                    ->where('cash_register_id', $cash_register->id)
                    ->where([['cash_register_transactions.status', 1]])
                    ->join('payment_types', function ($join) {
                        $join->on('cash_register_transactions.payment_type_id', 'payment_types.id');
                    })
                    ->select('payment_category')
                    ->selectRaw(DB::raw('SUM(amount) as total_amount'))
                    ->selectRaw(DB::raw('SUM(CASE WHEN payment_type_id = 1 THEN 1 ELSE NULL END) as sub_title'))
                    // ->selectRaw('SUM(total_amount) as total_sale_amount')
                    ->groupBy('payment_category')
                    ->get()
                    ->map(function ($data) {
                        $data['category_name'] = (isset($data->payment_category) && $data->payment_category != null) ? config('app.payment_category')[$data->payment_category - 1]['name'] : null;
                        return $data;
                    });

                $store_opened = 1;
                if ($cash_register->is_opened == 1) {
                    $cashregister_from_date = $cash_register->open_close_time;
                    $cashregister_to_date = Carbon::parse($cash_register->open_close_time)->format('Y-m-d 23:59:59');
                } else {
                    $cash_register_open = CashRegister::where(function ($query) use ($cashregister_date, $store_id) {
                        if ($cashregister_date != null) {
                            $query->whereDate('open_close_time', Carbon::parse($cashregister_date)->format('Y-m-d 00:00:00'));
                        } else {
                            $query->whereDate('open_close_time', Carbon::now()->format('Y-m-d 00:00:00'));
                        }
                        if ($store_id != null) {
                            $query->where('store_id', $store_id);
                        }
                    })
                        ->where('status', 1)
                        ->where('is_opened', 1)
                        ->select('open_close_time')
                        ->orderBy('id', 'DESC')
                        ->first();

                    $cashregister_from_date = $cash_register_open->open_close_time;
                    $cashregister_to_date = Carbon::parse($cash_register_open->open_close_time)->endOfDay();
                }
            } else {
                $cashregister_from_date = Carbon::parse($cashregister_date)->startOfDay();
                $cashregister_to_date = Carbon::parse($cashregister_date)->endOfDay();
            }

            $todaysalesreturnexpense = PaymentTransaction::Join('sales_order_returns', function ($join) use ($store_id) {
                $join->on('sales_order_returns.id', 'payment_transactions.reference_id')->where('from_store_id', $store_id)->where('transaction_type', 3)->where('type', 2);
            })
                ->where(function ($query) use ($cashregister_from_date, $cashregister_to_date) {
                    if ($cashregister_from_date != null && $cashregister_to_date != null) {
                        $query->whereBetween('sales_order_returns.return_date', [$cashregister_from_date, $cashregister_to_date]);
                    } else {
                        $query->whereBetween('sales_order_returns.return_date', [$cashregister_from_date, $cashregister_to_date]);
                    };
                })
                ->select(DB::raw('coalesce(SUM(total_amount),0) as total_amount'))->first()->total_amount;

            $incomeexpenseexpense = PaymentTransaction::join('income_expense_transactions', function ($join) use ($store_id) {
                $join->on('income_expense_transactions.id', 'payment_transactions.reference_id')->where('store_id', $store_id)->where('transaction_type', 5)->where('type', 2);
            })
                ->where(function ($query) use ($cashregister_from_date, $cashregister_to_date) {
                    if ($cashregister_from_date != null && $cashregister_to_date != null) {
                        $query->whereBetween('income_expense_transactions.transaction_datetime', [$cashregister_from_date, $cashregister_to_date]);
                    } else {
                        $query->whereBetween('income_expense_transactions.transaction_datetime', [$cashregister_from_date, $cashregister_to_date]);
                    };
                })
                ->select(DB::raw('coalesce(SUM(total_amount),0) as total_amount'))->first()->total_amount;

            $last_closing_balance = CashRegister::whereIn('id', function ($query) use ($store_id, $store_opened) {
                $query->select(DB::raw('max(id) as id'))->from('cash_registers')
                    ->where(function ($query) use ($store_id, $store_opened) {
                        if ($store_id != null) {
                            $query->where('store_id', $store_id);
                        }
                        // if ($store_opened == 0) {
                        //     $query->where('is_opened', 0);
                        // } else if ($store_opened == 1) {
                        //     $query->where('is_opened', 1);
                        // }
                    })
                    ->where('status', 1);
            })
                ->where('status', 1)
                ->orderBy('id', 'DESC')
                ->sum('total_amount');

            $total_sale_amount = SalesOrder::where(function ($query) use ($cashregister_from_date, $cashregister_to_date, $store_id) {
                if ($cashregister_from_date != null && $cashregister_to_date != null) {
                    $query->whereBetween('delivered_date', [$cashregister_from_date, $cashregister_to_date]);
                } else {
                    $query->whereBetween('delivered_date', [$cashregister_from_date, $cashregister_to_date]);
                }
                if ($store_id != null) {
                    $query->where('store_id', $store_id);
                }
            })
                ->whereIn('payment_status', [1,3])
                ->whereIn('status', [1,4,8])
                // ->whereIn('status', config('app.purchase_cancelled_status'))
                ->select(DB::raw('COALESCE(SUM(total_amount), 0) as total_amount'))
                ->first()->total_amount;

            $total_pending_amount = SalesOrder::where(function ($query) use ($cashregister_from_date, $cashregister_to_date, $store_id) {
                if ($cashregister_from_date != null && $cashregister_to_date != null) {
                    $query->whereBetween('delivered_date', [$cashregister_from_date, $cashregister_to_date]);
                } else {
                    $query->whereBetween('delivered_date', [$cashregister_from_date, $cashregister_to_date]);
                }
                if ($store_id != null) {
                    $query->where('store_id', $store_id);
                }
            })
                ->where('payment_status', '!=', 1)
                ->select(DB::raw('COALESCE(SUM(total_amount), 0) as total_amount'))
                ->first()->total_amount;

            $credit_paid_amount = SalesOrder::Join('payment_transactions', function ($join) {
                $join->on('payment_transactions.reference_id', 'sales_orders.id')->where('transaction_type', 2)->where('type', 1)->whereNull('payment_transactions.deleted_at');
            })
                ->where(function ($query) use ($cashregister_from_date, $cashregister_to_date, $store_id) {
                    if ($cashregister_from_date != null && $cashregister_to_date != null) {
                        $query->whereBetween('delivered_date', [$cashregister_from_date, $cashregister_to_date]);
                    } else {
                        $query->whereBetween('delivered_date', [$cashregister_from_date, $cashregister_to_date]);
                    }
                    if ($store_id != null) {
                        $query->where('store_id', $store_id);
                    }
                })
                ->where('payment_status', '!=', 1)
                ->select(DB::raw('COALESCE(SUM(amount), 0) as paid_amount'))
                ->first()->paid_amount;

            // $credit_amount = $total_pending_amount - $credit_paid_amount;
            $credit_amount = $total_pending_amount;
            $cash_paid_to_office = CashPaidToOffice::where(function ($query) use ($cashregister_from_date, $cashregister_to_date) {
                if ($cashregister_from_date != null && $cashregister_to_date != null) {
                    // $dateformatwithtime = CommonComponent::dateformatwithtime($cashregister_from_date, $cashregister_to_date);
                    $query->whereBetween('created_at', [$cashregister_from_date, $cashregister_to_date]);
                }
            })
                ->where([['store_id', $store_id], ['status', 1]])
                ->sum('amount');

            $previous_credit_collect_amount = SalesOrder::where(function ($query) use ($cashregister_from_date, $cashregister_to_date, $store_id) {
                $betweendate = CommonComponent::dateformatwithtime(Carbon::now()->format('Y-m-d'), Carbon::now()->format('Y-m-d'));
                $query->whereNotBetween('delivered_date', $betweendate);
                if ($store_id != null) {
                    $query->where('store_id', $store_id);
                }
            })
                ->Join('payment_transactions', function ($join) {
                    $join->on('payment_transactions.reference_id', 'sales_orders.id')->where('payment_transactions.status', 1)->where('payment_transactions.type', 1)->where('transaction_type', 2)->whereDate('transaction_datetime', Carbon::now()->format('Y-m-d'))->whereNull('payment_transactions.deleted_at');
                })
                ->select(DB::raw('COALESCE(SUM(payment_transactions.amount), 0) as total_amount'))
                ->first()->total_amount;

            $data['open_close_amount'] = $last_closing_balance;
            if ($cash_register != null && $cash_register->is_opened == 1) {
                $data['is_opened'] = 1;
                $data['total_amount'] = (count($data['payment_details']) > 0 ? $data['payment_details']->sum('total_amount') : 0) - ($todaysalesreturnexpense + $incomeexpenseexpense) - $cash_paid_to_office;
                $data['total_sale_amount'] = $total_sale_amount;
                $data['total_pending_amount'] = $credit_amount;
                $data['cash_paid_to_office'] = $cash_paid_to_office;
                $data['total_expense_amount'] = $todaysalesreturnexpense + $incomeexpenseexpense;
                $data['today_received_credit_amount'] = $previous_credit_collect_amount;
            } else if ($cash_register != null && $cash_register->is_opened == 2) {
                $data['is_opened'] = 0;
                $data['total_amount'] = count($data['payment_details']) > 0 ? $data['payment_details']->sum('total_amount') : 0;
                $data['total_sale_amount'] = $total_sale_amount;
                $data['total_pending_amount'] = $credit_amount;
                $data['cash_paid_to_office'] = $cash_paid_to_office;
                $data['total_expense_amount'] = $todaysalesreturnexpense + $incomeexpenseexpense;
                $data['today_received_credit_amount'] = $previous_credit_collect_amount;
            } else {
                $data['is_opened'] = 0;
                $data['total_amount'] = count($data['payment_details']) > 0 ? $data['payment_details']->sum('total_amount') : 0;
                $data['total_sale_amount'] = $total_sale_amount;
                $data['total_pending_amount'] = $credit_amount;
                $data['cash_paid_to_office'] = $cash_paid_to_office;
                $data['total_expense_amount'] = $todaysalesreturnexpense + $incomeexpenseexpense;
                $data['today_received_credit_amount'] = $previous_credit_collect_amount;
            }
      // // original code
        //   $credit_sales_amount = SalesOrder::whereIn('payment_status', [2, 3])->whereIn('status', [8])->where('store_id', $store_id)->whereBetween('delivered_date', [Carbon::parse($cashregister_date)->format('Y-m-d 00:00:00'), Carbon::parse($cashregister_date)->format('Y-m-d 23:59:59')])->sum('total_amount');
        //   $whole_sales_amount = SalesOrder::whereIn('payment_status', [1, 2, 3])->where('store_id', $store_id)->whereBetween('delivered_date', [Carbon::parse($cashregister_date)->format('Y-m-d 00:00:00'), Carbon::parse($cashregister_date)->format('Y-m-d 23:59:59')])->sum('total_amount');
        //   $cancel_bill_amount = SalesOrder::whereIn('status', [4])->where('store_id', $store_id)->whereBetween('delivered_date', [Carbon::parse($cashregister_date)->format('Y-m-d 00:00:00'), Carbon::parse($cashregister_date)->format('Y-m-d 23:59:59')])->sum('total_amount');
            $credit_sales_amount = SalesOrder::where(function ($query) use ($cashregister_from_date, $cashregister_to_date, $store_id) {
                if ($cashregister_from_date != null && $cashregister_to_date != null) {
                    $query->whereBetween('delivered_date', [$cashregister_from_date, $cashregister_to_date]);
                } else {
                    $query->whereBetween('delivered_date', [$cashregister_from_date, $cashregister_to_date]);
                }
                if ($store_id != null) {
                    $query->where('store_id', $store_id);
                }
            })
                ->where('payment_status', '!=', 1)
                ->select(DB::raw('COALESCE(SUM(total_amount), 0) as total_amount'))
                ->first()->total_amount;

            $whole_sales_amount = SalesOrder::whereIn('payment_status', [2, 3])->whereIn('status', [8])->where('store_id', $store_id)->sum('total_amount');
            $cancel_bill_amount = SalesOrder::whereIn('status', [4])->where('store_id', $store_id)->sum('total_amount');
            $data['payment_category'] = config('app.payment_category');

            return view('pages.master.store.show', $data);
        } catch (\Exception $e) {
            Log::error($e);
            return response()->json([
                'status' => 400,
                'message' => 'Sorry, Something went Wrong'
            ]);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data['store'] = Store::findOrfail($id);
        $data['warehouses'] = Warehouse::where('status', 1)->get();
        $data['countries'] = Country::where('status', 1)->get();
        return view('pages.master.store.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(StoreFormRequest $request, $id)
    {
        try {
            $slug = commoncomponent::slugCreate($request->store_name, $request->slug);

            $store = Store::findOrFail($id);
            $store->store_name = $request->store_name;
            $store->slug = $slug;
            $store->store_code = $request->store_code;
            $store->warehouse_id = $request->warehouse_id;
            $store->phone_number = $request->phone_number;
            $store->email = $request->email;
            $store->start_date = $request->start_date;
            $store->gst_number = $request->gst_number;
            $store->address = $request->address;
            $store->city_id = $request->city_id;
            $store->state_id = $request->state_id;
            $store->country_id = $request->country_id;
            $store->pincode = $request->pincode;
            $store->longitude = $request->longitude;
            $store->latitude = $request->latitude;
            $store->direction = $request->direction;
            $store->status = $request->status;
            $store->save();

            return redirect()->route('admin.store.index')->with('success', 'Store Updated Successfully');
        } catch (\Exception $e) {
            Log::error($e);
            return back()->withInput()->with('error', 'Store Updated Fail');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            Store::findOrFail($id)->delete();

            return response()->json([
                'status' => 200,
                'message' => 'Store Deleted Successfully.'
            ]);
        } catch (\Exception $e) {
            Log::error($e);
            return response()->json([
                'status' => 400,
                'message' => 'Sorry, Something went Wrong'
            ]);
        }
    }
}
