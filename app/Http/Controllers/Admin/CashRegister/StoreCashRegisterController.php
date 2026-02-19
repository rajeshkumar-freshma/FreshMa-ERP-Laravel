<?php

namespace App\Http\Controllers\Admin\CashRegister;

use App\Core\CommonComponent;
use App\DataTables\StoreCashRegister\CashRegisterDataTable;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCashRegister\CashRegisterFormRequest;
use App\Models\Admin;
use App\Models\CashPaidToOffice;
use App\Models\CashRegister;
use App\Models\CashRegisterTransaction;
use App\Models\Department;
use App\Models\PaymentTransaction;
use App\Models\PaymentType;
use App\Models\SalesOrder;
use App\Models\Store;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Log;

class StoreCashRegisterController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(CashRegisterDataTable $dataTable)
    {
        $data['store'] = Store::all();
        return $dataTable->render('pages.store_cash_register.cash_register.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($id)
    {
        $data = $this->commonCashRegisterLogic($id);
        $data['store_id'] = $id;
        $data['store'] = Store::all();
        $data['admin'] = Admin::all();

        return view('pages.store_cash_register.cash_register.create', $data);
        // } catch (\Exception $e) {
        //     Log::error($e);
        //     return response()->json([
        //         'status' => 400,
        //         'message' => 'Data not found.',
        //     ]);
        // }
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CashRegisterFormRequest $request)
    {
        DB::beginTransaction();
        try {
            $store_id = $request->store_id;
            // Assuming $request->open_close_time is in the format 'Y-m-d' (e.g., '2024-02-15')
            $requestOpenCloseTime = $request->open_close_time;
            $currentDateTime = Carbon::now()->format('H:i:s'); // Get current time in 'H:i:s' format

            // Concatenate the current time with the existing date
            $combinedDateTime = $requestOpenCloseTime . ' ' . $currentDateTime;
            if ($request->is_opened == 1) {
                $cash_register = CashRegister::whereIn('id', function ($query) use ($store_id) {
                    $query->select(DB::raw('max(id) as id'))->from('cash_registers')
                        ->where('is_opened', 1)
                        ->where(function ($query) use ($store_id) {
                            if ($store_id != null) {
                                $query->where('store_id', $store_id);
                            }
                            $query->groupBy('status');
                        });
                })
                    ->where('status', 1)
                    ->with('cash_register_transactions')
                    ->orderBy('id', 'DESC')
                    ->first();

                if ($request->open_close_time != null) {
                    $cashregister_date = $request->open_close_time;
                } else {
                    $cashregister_date = Carbon::today()->format('Y-m-d');
                }

                if ($cash_register != null) {
                    $cashregistertransaction = CashRegisterTransaction::where('cash_register_id', $cash_register->id)->sum('amount');
                    $opening_register_cost = $cash_register->total_amount;
                } else {
                    $opening_register_cost = 0;
                    $cashregistertransaction = 0;
                }

                // $cash_paid_to_office = CashPaidToOffice::where(function ($query) use ($cashregister_date) {
                //     if ($cashregister_date != null) {
                //         $dateformatwithtime = CommonComponent::dateformatwithtime($cashregister_date, $cashregister_date);
                //         $query->whereBetween('created_at', $dateformatwithtime);
                //     }
                // })
                //     ->where([['store_id', $store_id], ['status', 1]])
                //     ->sum('amount');

                // $income_expense_amount = IncomeExpenseTransaction::where(function ($query) use ($cashregister_date) {
                //     if ($cashregister_date != null) {
                //         $dateformatwithtime = CommonComponent::dateformatwithtime($cashregister_date, $cashregister_date);
                //         $query->whereBetween('transaction_datetime', $dateformatwithtime);
                //     }
                // })
                //     ->where([['store_id', $store_id], ['status', 1]])
                //     ->whereIn('payment_status', [1, 3])
                //     ->sum('total_amount');

                $amount = $request->amount;
                $total_amount = $request->total_amount;
            } else {
                $amount = $request->amount;
                $total_amount = $request->total_amount;
            }
            $paymentType = PaymentType::first();
            $cash_register = new CashRegister();
            $cash_register->store_id = $request->store_id;
            $cash_register->is_opened = $request->is_opened;   //open or closed 
            $cash_register->amount = $amount;
            $cash_register->add_dedect_amount = $request->add_dedect_amount;
            $cash_register->total_amount = $total_amount;
            $cash_register->transaction_type = $request->transaction_type;
            $cash_register->open_close_time = $combinedDateTime;
            $cash_register->verified_by = $request->verified_by;   //manager or partner or super admin
            $cash_register->save();

            CashRegisterTransaction::where([['store_id', $request->store_id], ['type', 1]])->where('payment_type_id', 1)->whereBetween('transaction_datetime', [Carbon::parse($request->open_close_time)->format('Y-m-d 00:00:00'), Carbon::parse($request->open_close_time)->format('Y-m-d 23:59:59')])->update(['status' => 0]);
            $cash_register_transaction = new CashRegisterTransaction();
            $cash_register_transaction->cash_register_id = $cash_register->id;
            $cash_register_transaction->store_id = $request->store_id;
            if ($paymentType !== null) {
                $cash_register_transaction->payment_type_id = $paymentType->id;
            }
            $cash_register_transaction->amount = $total_amount;
            $cash_register_transaction->transaction_datetime = Carbon::now();
            $cash_register_transaction->type = 1; // Credit
            $cash_register_transaction->save();

            DB::commit();
            if ($request->submission_type == 1) {
                return redirect()->route('admin.cash-register.index')->with('success', 'Cash Register Successfully');
            } elseif ($request->submission_type == 2) {
                return back()->with('success', 'Cash Register Store Successfully');
            }
        } catch (\Exception $e) {
            Log::error($e);
            return back()->withInput()->with('error', 'Cash register Stored Fail');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {
            $data['cash_registers_data'] = CashRegister::findOrFail($id);
            return view('pages.store_cash_register.cash_register.view', $data);
        } catch (\Exception $e) {
            Log::error($e);
            return response()->json([
                'status' => 400,
                'message' => 'Sorry, Something went Wrong',
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
        $data['cash_registers'] = CashRegister::findOrFail($id);
        $commonData = $this->commonCashRegisterLogic($data['cash_registers']->store_id);

        // Merge the existing data with the common data
        $data = array_merge($data, $commonData);
        $data['store'] = Store::all();
        $data['admin'] = Admin::all();
        return view('pages.store_cash_register.cash_register.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        DB::beginTransaction();
        try {
            $store_id = $request->store_id;
            if ($request->is_opened == 1) {
                $cash_register = CashRegister::whereIn('id', function ($query) use ($store_id) {
                    $query->select(DB::raw('max(id) as id'))->from('cash_registers')
                        ->where('is_opened', 1)
                        ->where(function ($query) use ($store_id) {
                            if ($store_id != null) {
                                $query->where('store_id', $store_id);
                            }
                            $query->groupBy('status');
                        });
                })
                    ->where('status', 1)
                    ->with('cash_register_transactions')
                    ->orderBy('id', 'DESC')
                    ->first();

                if ($request->open_close_time != null) {
                    $cashregister_date = $request->open_close_time;
                } else {
                    $cashregister_date = Carbon::today()->format('Y-m-d');
                }

                if ($cash_register != null) {
                    $cashregistertransaction = CashRegisterTransaction::where('cash_register_id', $cash_register->id)->sum('amount');
                    $opening_register_cost = $cash_register->total_amount;
                } else {
                    $opening_register_cost = 0;
                    $cashregistertransaction = 0;
                }

                // $cash_paid_to_office = CashPaidToOffice::where(function ($query) use ($cashregister_date) {
                //     if ($cashregister_date != null) {
                //         $dateformatwithtime = CommonComponent::dateformatwithtime($cashregister_date, $cashregister_date);
                //         $query->whereBetween('created_at', $dateformatwithtime);
                //     }
                // })
                //     ->where([['store_id', $store_id], ['status', 1]])
                //     ->sum('amount');

                // $income_expense_amount = IncomeExpenseTransaction::where(function ($query) use ($cashregister_date) {
                //     if ($cashregister_date != null) {
                //         $dateformatwithtime = CommonComponent::dateformatwithtime($cashregister_date, $cashregister_date);
                //         $query->whereBetween('transaction_datetime', $dateformatwithtime);
                //     }
                // })
                //     ->where([['store_id', $store_id], ['status', 1]])
                //     ->whereIn('payment_status', [1, 3])
                //     ->sum('total_amount');

                $amount = $request->amount;
                $total_amount = $request->total_amount;
            } else {
                $amount = $request->amount;
                $total_amount = $request->total_amount;
            }
            $cash_register = CashRegister::where('id', $id)->update([
                'is_opened' => $request->is_opened,
                'amount' => $amount,
                'add_dedect_amount' => $request->add_dedect_amount,
                'total_amount' => $total_amount,
                'transaction_type' => $request->transaction_type,
                'open_close_time' => $request->open_close_time,
                'verified_by' => $request->verified_by,
            ]);
            $paymentType = PaymentType::first();

            CashRegisterTransaction::where([['store_id', $request->store_id], ['type', 1]])->where('payment_type_id', 1)->whereBetween('transaction_datetime', [Carbon::parse($request->open_close_time)->format('Y-m-d 00:00:00'), Carbon::parse($request->open_close_time)->format('Y-m-d 23:59:59')])->update(['status' => 0]);
            $cash_register_transaction = new CashRegisterTransaction();
            $cash_register_transaction->cash_register_id = $id;
            $cash_register_transaction->store_id = $request->store_id;

            if ($paymentType !== null) {
                $cash_register_transaction->payment_type_id = $paymentType->id;
            }
            $cash_register_transaction->amount = $total_amount;
            $cash_register_transaction->transaction_datetime = Carbon::now();
            $cash_register_transaction->type = 1; // Credit
            $cash_register_transaction->save();

            DB::commit();

            return redirect()->route('admin.cash-register.index')->with('success', 'Cash register Updated Successfully');
        } catch (\Exception $e) {
            Log::error($e);
            return back()->withInput()->with('error', 'Cash register Updated Fail');
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
            Department::findOrFail($id)->delete();

            return redirect()->route('admin.cash-register.index')->with('success', 'Cash register Deleted Successfully');
        } catch (\Exception $e) {
            Log::error($e);
            return response()->json([
                'status' => 400,
                'message' => 'Sorry, Something went Wrong',
            ]);
        }
    }

    public function getPayerDetails(Request $request)
    {
        try {
            $store_id = $request->store_id;
            $store_employees = Admin::when($request, function ($query) use ($store_id) {
                $query->LeftJoin('partnership_details', function ($join) use ($store_id) {
                    $join->on('partnership_details.partner_id', 'admins.id')->whereNull('partnership_details.deleted_at')
                        ->where([['partnership_details.store_id', $store_id], ['partnership_details.status', 1]]);
                })
                    ->Orwhere([['partnership_details.store_id', $store_id], ['partnership_details.status', 1]]);

                $query->LeftJoin('staff_store_mappings', function ($join) use ($store_id) {
                    $join->on('staff_store_mappings.staff_id', 'admins.id')->whereNull('staff_store_mappings.deleted_at')
                        ->where([['staff_store_mappings.store_id', $store_id], ['staff_store_mappings.status', 1]]);
                })
                    ->Orwhere([['staff_store_mappings.store_id', $store_id], ['staff_store_mappings.status', 1]]);
            })
                ->distinct('admins.id')
                ->select('admins.*')
                ->get();
            Log::info('store_employees');
            Log::info($store_employees);
            return response()->json([
                'status' => 200,
                'data' => $store_employees,
            ]);
        } catch (\Exception $e) {
            Log::error($e);
            return response()->json([
                'status' => 400,
                'message' => 'Sorry, Something went Wrong',
            ]);
        }
    }

    public function commonCashRegisterLogic($id)
    {
        // try {
            $data['is_opened'] = 0;
            $data['total_amount'] = 0;
            $data['open_close_amount'] = 0;
            $data['total_sale_amount'] = 0;
            $data['total_pending_amount'] = 0;
            $data['total_expense_amount'] = 0;
            $data['today_received_credit_amount'] = 0;
            $data['payment_details'] = [];
            
           $cashregister_date = Carbon::today()->format('Y-m-d');
            $store_id = $id;
              $cash_register = CashRegister::where(function ($query) use ($cashregister_date, $store_id) {
                if ($cashregister_date != null) {
                    $query->whereDate('open_close_time', Carbon::parse($cashregister_date)->format('Y-m-d 00:00:00'));
                } else {
                    $query->whereDate('open_close_time', Carbon::today()->format('Y-m-d 00:00:00'));
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
                        $query->whereDate('transaction_datetime', Carbon::today()->format('Y-m-d 00:00:00'));
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
                    $cashregister_from_date = Carbon::parse($cashregister_date)->startOfDay();
                    $cashregister_to_date = Carbon::parse($cash_register->open_close_time)->format('Y-m-d 23:59:59');
                } else if($cash_register->is_opened == 2) {
                    $cash_register_open = CashRegister::where(function ($query) use ($cashregister_date, $store_id) {
                        if ($cashregister_date != null) {
                            $query->whereDate('open_close_time', Carbon::parse($cashregister_date)->format('Y-m-d 00:00:00'));
                        } else {
                            $query->whereDate('open_close_time', Carbon::today()->format('Y-m-d 00:00:00'));
                        }
                        if ($store_id != null) {
                            $query->where('store_id', $store_id);
                        }
                    })
                        ->where('status', 1)
                        ->where('is_opened', 2)
                        ->select('open_close_time')
                        ->orderBy('id', 'DESC')
                        ->first();
    
                    $cashregister_from_date = Carbon::parse($cashregister_date)->startOfDay();
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
                $join->on('income_expense_transactions.id', '=', 'payment_transactions.reference_id')
                    ->where('store_id', $store_id)
                    ->where('transaction_type', 5)
                    ->where('type', 2);
            })
                ->whereBetween('income_expense_transactions.transaction_datetime', [$cashregister_from_date, $cashregister_to_date])
                ->selectRaw('COALESCE(SUM(payment_transactions.amount), 0) as total_amount')
                ->first()->total_amount;
    
            $last_closing_balance = CashRegister::whereIn('id', function ($query) use ($store_id, $store_opened) {
                $query->select(DB::raw('max(id) as id'))->from('cash_registers')
                    ->where(function ($query) use ($store_id, $store_opened) {
                        if ($store_id != null) {
                            $query->where('store_id', $store_id);
                        }

                    })
                    ->where('status', 1);
            })
                ->where('status', 1)
                ->orderBy('id', 'DESC')
                ->sum('total_amount');
    
            $total_sale_amount = SalesOrder::where(function ($query) use ($cashregister_from_date, $cashregister_to_date, $store_id) {
                if ($store_id != null) {
                    $query->where('store_id', $store_id);
                }
            })
                ->whereIn('payment_status', [1, 3])
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
                $betweendate = CommonComponent::dateformatwithtime(Carbon::today()->format('Y-m-d'), Carbon::today()->format('Y-m-d'));
                $query->whereNotBetween('delivered_date', $betweendate);
                if ($store_id != null) {
                    $query->where('store_id', $store_id);
                }
            })
    
                ->Join('payment_transactions', function ($join) {
                    $join->on('payment_transactions.reference_id', 'sales_orders.id')->where('payment_transactions.status', 1)->where('payment_transactions.type', 1)->where('transaction_type', 2)->whereDate('transaction_datetime', Carbon::today()->format('Y-m-d'))->whereNull('payment_transactions.deleted_at');
                })
                ->select(DB::raw('COALESCE(SUM(payment_transactions.amount), 0) as total_amount'))
                ->first()->total_amount;
    
            $data['open_close_amount'] = $last_closing_balance;
            if ($cash_register != null && $cash_register->is_opened == 1) {
                $data['is_opened'] = 1;
                $data['total_amount'] = ($data['payment_details']->sum('total_amount') ?? 0) - ($todaysalesreturnexpense ?? 0) - ($incomeexpenseexpense ?? 0) - ($cash_paid_to_office ?? 0);
                // $data['total_amount'] = (count($data['payment_details']) > 0 ? $data['payment_details']->sum('total_amount') : 0) - ($todaysalesreturnexpense + $incomeexpenseexpense) - $cash_paid_to_office;
                $data['total_sale_amount'] = $total_sale_amount;
                $data['total_pending_amount'] = $credit_amount;
                $data['cash_paid_to_office'] = $cash_paid_to_office;
                $data['total_expense_amount'] = $todaysalesreturnexpense + $incomeexpenseexpense;
                $data['today_received_credit_amount'] = $previous_credit_collect_amount;
                $data['cash_register'] = $cash_register;
            } else if ($cash_register != null && $cash_register->is_opened == 2) {
                $data['is_opened'] = 0;
                $data['total_amount'] = count($data['payment_details']) > 0 ? $data['payment_details']->sum('total_amount') : 0;
                $data['total_sale_amount'] = $total_sale_amount;
                $data['total_pending_amount'] = $credit_amount;
                $data['cash_paid_to_office'] = $cash_paid_to_office;
                $data['total_expense_amount'] = $todaysalesreturnexpense + $incomeexpenseexpense;
                $data['today_received_credit_amount'] = $previous_credit_collect_amount;
                $data['cash_register'] = $cash_register;
            } else {
                $data['is_opened'] = 0;
                $data['total_amount'] = count($data['payment_details']) > 0 ? $data['payment_details']->sum('total_amount') : 0;
                $data['total_sale_amount'] = $total_sale_amount;
                $data['total_pending_amount'] = $credit_amount;
                $data['cash_paid_to_office'] = $cash_paid_to_office;
                $data['total_expense_amount'] = $todaysalesreturnexpense + $incomeexpenseexpense;
                $data['today_received_credit_amount'] = $previous_credit_collect_amount;
                $data['cash_register'] = $cash_register;
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

        return $data;
    }
}
