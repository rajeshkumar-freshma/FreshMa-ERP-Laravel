<?php

namespace App\Http\Controllers\Api\Store;

use App\Core\CommonComponent;
use App\Http\Controllers\Controller;
use App\Models\CashPaidToOffice;
use App\Models\CashRegister;
use App\Models\CashRegisterTransaction;
use App\Models\CashRegisterTransactionDocument;
use App\Models\Denomination;
use App\Models\FishCutting;
use App\Models\IncomeExpenseTransaction;
use App\Models\PaymentTransaction;
use App\Models\PaymentType;
use App\Models\SalesOrder;
use App\Models\SalesOrderReturn;
use App\Models\Spoilage;
use App\Models\StaffAttendance;
use App\Models\StoreIndentRequest;
use App\Models\StoreStockUpdate;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CashRegisterController extends Controller
{
    public function cashregisterchecks(Request $request)
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
        if ($request->date != null) {
            $cashregister_date = $request->date;
        } else {
            $cashregister_date = Carbon::today()->format('Y-m-d');
        }
        $store_id = $request->store_id;
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
            } else {
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
                    ->where('is_opened', 1)
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
            // if ($cashregister_from_date != null && $cashregister_to_date != null) {
            //     $query->whereBetween('delivered_date', [$cashregister_from_date, $cashregister_to_date]);
            // } else {
            //     $query->whereBetween('delivered_date', [$cashregister_from_date, $cashregister_to_date]);
            // }
            if ($store_id != null) {
                $query->where('store_id', $store_id);
            }
        })
            ->whereIn('payment_status', [1, 3])
            // ->whereIn('status', [1, 4, 8])
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

        return response()->json([
            'status' => 200,
            'cash_register' => $cash_register,
            'data' => $data,
            'credit_sales_amount' => $credit_sales_amount,
            'whole_sales_amount' => $whole_sales_amount,
            'cancel_bill_amount' => $cancel_bill_amount,
            'message' => 'Cash Registers Fetched successfully.',
        ]);
        // } catch (\Exception $e) {
        //     Log::error($e);
        //     return response()->json([
        //         'status' => 400,
        //         'message' => 'Data not found.',
        //     ]);
        // }
    }

    public function storesaleschart(Request $request)
    {
        $data['sales_chart'] = [];
        if ($request->date != null) {
            $cashregister_date = $request->date;
        } else {
            $cashregister_date = Carbon::today()->format('Y-m-d');
        }

        $store_id = $request->store_id;
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

        if ($cash_register != null) {
            $data['sales_chart'] = CashRegisterTransaction::where(function ($query) use ($cashregister_date, $store_id) {
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
                ->groupBy('payment_category')
                ->get()
                ->map(function ($data) {
                    $data['category_name'] = (isset($data->payment_category) && $data->payment_category != null) ? config('app.payment_category')[$data->payment_category - 1]['name'] : null;
                    return $data;
                });
        }

        $creditSale = SalesOrder::where(function ($query) use ($cashregister_date, $store_id) {
            if ($cashregister_date != null) {
                $query->whereDate('delivered_date', Carbon::parse($cashregister_date)->format('Y-m-d 00:00:00'));
            } else {
                $query->whereDate('delivered_date', Carbon::today()->format('Y-m-d 00:00:00'));
            }
            if ($store_id != null) {
                $query->where('store_id', $store_id);
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
                $query->where('from_store_id', $store_id);
            }
        })
            ->where('return_from', 2)
            ->select(DB::raw('COALESCE(SUM(total_amount), 0) as total_amount'))
            ->first();

        $salesreturn['payment_category'] = 'Return Bill';
        $salesreturn['category_name'] = 'Return Bill';

        $count = count($data['sales_chart']);

        $data['sales_chart'][$count++] = $creditSale;
        $data['sales_chart'][$count] = $salesreturn;

        $chart_label = [];
        $chart_value = [];
        foreach ($data['sales_chart'] as $key => $payment_detail) {
            if ($payment_detail->category_name && $payment_detail->total_amount) {
                $chart_label[] = $payment_detail->category_name;
                $chart_value[] = $payment_detail->total_amount;
            }
        }
        // If no values are found, set defaults
        // if (empty($chart_label) && empty($chart_value)) {
        //     $chart_label = [0];
        //     $chart_value = [0];
        // }
        return response()->json([
            'status' => 200,
            'chart_label' => $chart_label,
            'chart_value' => $chart_value,
            'message' => 'Sales Chart Fetched successfully.',
        ]);
    }

    public function cashregisterlist(Request $request)
    {
        try {
            if ($request->date != null) {
                $cashregister_date = $request->date;
            } else {
                $cashregister_date = Carbon::today()->format('Y-m-d');
            }
            $store_id = $request->store_id;
            $cash_register = CashRegister::where(function ($query) use ($cashregister_date, $store_id) {
                if ($cashregister_date != null) {
                    $query->whereDate('created_at', Carbon::parse($cashregister_date)->format('Y-m-d 00:00:00'));
                } else {
                    $query->whereDate('created_at', Carbon::today()->format('Y-m-d 00:00:00'));
                }
                if ($store_id != null) {
                    $query->where('store_id', $store_id);
                }
            })
                ->with('cash_register_transactions')
                ->paginate(20);

            return response()->json([
                'status' => 200,
                'data' => $cash_register,
                'message' => 'Cash Registers Fetched successfully.',
            ]);
        } catch (\Exception $e) {
            Log::error($e);
            return response()->json([
                'status' => 400,
                'message' => 'Data not found.',
            ]);
        }
    }

    public function cashregistertransactionlist(Request $request)
    {
        // try {
        if ($request->date != null) {
            $transaction_datetime = $request->date;
        } else {
            $transaction_datetime = Carbon::today()->format('Y-m-d');
        }
        $store_id = $request->store_id;
        $payment_category = $request->payment_category;

        $payment_types = PaymentType::where('payment_category', $payment_category)
            ->with('cash_register_transactions', function ($query) use ($transaction_datetime, $store_id) {
                $query->where(function ($query) use ($transaction_datetime, $store_id) {
                    if ($transaction_datetime != null) {
                        $query->whereDate('transaction_datetime', Carbon::parse($transaction_datetime)->format('Y-m-d 00:00:00'));
                    } else {
                        $query->whereDate('transaction_datetime', Carbon::today()->format('Y-m-d 00:00:00'));
                    }
                    if ($store_id != null) {
                        $query->where('store_id', $store_id);
                    }
                })
                    ->where('cash_register_transactions.status', 1);
            })
            ->active()
            ->select('payment_types.id', 'payment_type', 'payment_category')
            ->paginate(20)
            ->through(function ($data) {
                $payment_types['id'] = $data->id;
                $payment_types['payment_type'] = $data->payment_type;
                $payment_types['payment_category'] = $data->payment_category;
                $payment_types['amount'] = $data->cash_register_transactions->sum('amount');
                return $payment_types;
            });

        $transaction_documents = CashRegisterTransactionDocument::where(function ($query) use ($transaction_datetime, $payment_category, $store_id) {
            if ($transaction_datetime != null) {
                $query->whereBetween('attachment_date', [Carbon::parse($transaction_datetime)->startOfDay(), Carbon::parse($transaction_datetime)->endOfDay()]);
            } else {
                $query->whereBetween('attachment_date', [Carbon::today()->startOfDay(), Carbon::today()->endOfDay()]);
            }
            if ($payment_category != null) {
                $query->where('payment_category_id', $payment_category);
            }
            if ($store_id != null) {
                $query->where('store_id', $store_id);
            }
        })
            ->get();

        // Fetch Cash Register
        $cashRegister = CashRegister::whereDate('open_close_time', $transaction_datetime)
            ->when($store_id, function ($query, $store_id) {
                return $query->where('store_id', $store_id);
            })
            ->where('status', 1)
            ->orderBy('open_close_time', 'DESC')
            ->with([
                'cash_register_transactions' => function ($query) {
                    $query->orderBy('id', 'DESC')->limit(1);
                },
                'cash_register_transactions.denominations',
            ])
            ->first();
        $store_opened = 0;
        return response()->json([
            'status' => 200,
            'payment_types' => $payment_types,
            'transaction_documents' => $transaction_documents,
            'cash_register' => $cashRegister,
            'message' => 'Cash Register Transactions Stored successfully.',
        ]);
        // } catch (\Exception $e) {
        //     Log::error($e);
        //     return response()->json([
        //         'status' => 400,
        //         'message' => 'Data not found.',
        //     ]);
        // }
    }

    // public function cashregisterstore(Request $request)
    // {
    //     DB::beginTransaction();
    //     try {
    //         $store_id = $request->store_id;
    //         $paymentType = PaymentType::fisrt();
    //         if ($paymentType) {
    //             $payment_type_id = $paymentType->id; // 1 means cash on hand
    //         } else {
    //             return response()->json([
    //                 'status' => 400,
    //                 'message' => "Pls Create At least one payment method or contact with Your administrator",
    //             ]);
    //         }
    //         if ($request->is_opened == 0) {
    //             $cash_register = CashRegister::whereIn('id', function ($query) use ($store_id) {
    //                 $query->select(DB::raw('max(id) as id'))->from('cash_registers')
    //                     ->where('is_opened', 1)
    //                     ->where(function ($query) use ($store_id) {
    //                         if ($store_id != null) {
    //                             $query->where('store_id', $store_id);
    //                         }
    //                         $query->groupBy('status');
    //                     });
    //             })
    //                 ->where('status', 1)
    //                 ->with('cash_register_transactions')
    //                 ->orderBy('id', 'DESC')
    //                 ->first();

    //             if ($request->date != null) {
    //                 $cashregister_date = $request->date;
    //             } else {
    //                 $cashregister_date = Carbon::today()->format('Y-m-d');
    //             }

    //             if ($cash_register != null) {
    //                 $cashregistertransaction = CashRegisterTransaction::where('cash_register_id', $cash_register->id)->sum('amount');
    //                 $opening_register_cost = $cash_register->total_amount;
    //             } else {
    //                 $opening_register_cost = 0;
    //                 $cashregistertransaction = 0;
    //             }

    //             $cash_paid_to_office = CashPaidToOffice::where(function ($query) use ($cashregister_date) {
    //                 if ($cashregister_date != null) {
    //                     $dateformatwithtime = CommonComponent::dateformatwithtime($cashregister_date, $cashregister_date);
    //                     $query->whereBetween('created_at', $dateformatwithtime);
    //                 }
    //             })
    //                 ->where([['store_id', $store_id], ['status', 1]])
    //                 ->sum('amount');

    //             $income_expense_amount = IncomeExpenseTransaction::where(function ($query) use ($cashregister_date) {
    //                 if ($cashregister_date != null) {
    //                     $dateformatwithtime = CommonComponent::dateformatwithtime($cashregister_date, $cashregister_date);
    //                     $query->whereBetween('transaction_datetime', $dateformatwithtime);
    //                 }
    //             })
    //                 ->where([['store_id', $store_id], ['status', 1]])
    //                 ->whereIn('payment_status', [1, 3])
    //                 ->sum('total_amount');

    //             $amount = $request->amount;
    //             $total_amount = $request->total_amount;
    //         } else {
    //             $amount = $request->amount;
    //             $total_amount = $request->total_amount;
    //         }
    //         $cash_register = new CashRegister();
    //         $cash_register->store_id = $request->store_id;
    //         $cash_register->is_opened = $request->is_opened;
    //         $cash_register->amount = $amount;
    //         $cash_register->add_dedect_amount = $request->add_dedect_amount;
    //         $cash_register->total_amount = $total_amount;
    //         $cash_register->transaction_type = $request->transaction_type;
    //         $cash_register->open_close_time = $request->open_close_time;
    //         $cash_register->verified_by = $request->verified_by;
    //         $cash_register->save();

    //         CashRegisterTransaction::where([['store_id', $request->store_id], ['type', 1]])->where('payment_type_id', 1)->whereBetween('transaction_datetime', [Carbon::parse($request->open_close_time)->format('Y-m-d 00:00:00'), Carbon::parse($request->open_close_time)->format('Y-m-d 23:59:59')])->update(['status' => 0]);
    //         $cash_register_transaction = new CashRegisterTransaction();
    //         $cash_register_transaction->cash_register_id = $cash_register->id;
    //         $cash_register_transaction->store_id = $request->store_id;
    //         $cash_register_transaction->payment_type_id = $payment_type_id;
    //         $cash_register_transaction->amount = $total_amount;
    //         $cash_register_transaction->transaction_datetime = Carbon::now();
    //         $cash_register_transaction->type = 1; // Credit
    //         $cash_register_transaction->save();

    //         $cash_register_id = $cash_register->id;
    //         // to store cash sale details in denominations table

    //         // if (isset($request->denominations) && count($request->denominations) > 0 && is_array($request->denominations)) {
    //         //     foreach ($request->denominations as $key => $denomination) {
    //         //         $cash_denominations = new Denomination();
    //         //         $cash_denominations->store_id = $request->store_id;
    //         //         $cash_denominations->cash_register_id = $cash_register_id;
    //         //         $cash_denominations->denomination_id = $key;
    //         //         $cash_denominations->denomination_value = $denomination;
    //         //         $cash_denominations->amount = (int) $request->amount;
    //         //         $cash_denominations->total_amount = $request->total_amount;
    //         //         $cash_denominations->save();
    //         //     }
    //         // }

    //         DB::commit();
    //         return response()->json([
    //             'status' => 200,
    //             'message' => 'Cash Register Stored successfully.',
    //         ]);
    //     } catch (\Exception $e) {
    //         Log::error($e);
    //         DB::rollback();
    //         return response()->json([
    //             'status' => 400,
    //             'message' => $e->getMessage,
    //         ]);
    //     }
    // }

    public function cashregisterstore(Request $request)
    {
        DB::beginTransaction();

        try {
            // Validate the request
            // $validatedData = $request->validate([
            //     'store_id' => 'required|integer|exists:stores,id',
            //     'is_opened' => 'required|boolean',
            //     'amount' => 'required',
            //     'total_amount' => 'required',
            //     'add_dedect_amount' => 'nullable',
            //     'transaction_type' => 'required|integer',
            //     'open_close_time' => 'required|date_format:Y-m-d H:i:s',
            //     'verified_by' => 'nullable|string',
            //     'date' => 'nullable|date_format:Y-m-d',
            //     'denominations' => 'nullable|array',
            // ]);

            // Get the payment type (assuming the first one is the default)
            $paymentType = PaymentType::first();
            if (!$paymentType) {
                return response()->json([
                    'status' => 400,
                    'message' => "Please create at least one payment method or contact your administrator.",
                ]);
            }
            $payment_type_id = $paymentType->id;

            // If the register is closed, handle the transactions and amounts
            if ($request->is_opened == 0) {
                $store_id = $request->store_id;
                $cashregister_date = $request->date ?? Carbon::today()->format('Y-m-d');

                // Get the most recent open cash register
                $cash_register = CashRegister::whereIn('id', function ($query) use ($store_id) {
                    $query->select(DB::raw('max(id) as id'))
                        ->from('cash_registers')
                        ->where('is_opened', 1)
                        ->when($store_id, function ($query, $store_id) {
                            $query->where('store_id', $store_id);
                        })
                        ->groupBy('status');
                })
                    ->where('status', 1)
                    ->with('cash_register_transactions')
                    ->orderBy('id', 'DESC')
                    ->first();

                // Calculate the required amounts
                $cashregistertransaction = $cash_register ?
                    CashRegisterTransaction::where('cash_register_id', $cash_register->id)->sum('amount') : 0;
                $opening_register_cost = $cash_register ? $cash_register->total_amount : 0;

                // Calculate cash paid to office
                $cash_paid_to_office = CashPaidToOffice::where('store_id', $store_id)
                    ->where('status', 1)
                    ->whereDate('created_at', $cashregister_date)
                    ->sum('amount');

                // Calculate income and expenses
                $income_expense_amount = IncomeExpenseTransaction::where('store_id', $store_id)
                    ->where('status', 1)
                    ->whereIn('payment_status', [1, 3])
                    ->whereDate('transaction_datetime', $cashregister_date)
                    ->sum('total_amount');

                // Set amounts based on request
                $amount = $request->amount;
                $total_amount = $request->total_amount;
            } else {
                // If the register is open, set amounts directly from the request
                $amount = $request->amount;
                $total_amount = $request->total_amount;
            }

            // Save new cash register entry
            $cash_register = new CashRegister();
            $cash_register->store_id = $request->store_id;
            $cash_register->is_opened = $request->is_opened;
            $cash_register->amount = $amount;
            $cash_register->add_dedect_amount = $request->add_dedect_amount ?? 0;
            $cash_register->total_amount = $total_amount;
            $cash_register->transaction_type = $request->transaction_type;
            $cash_register->open_close_time = $request->open_close_time;
            $cash_register->verified_by = $request->verified_by;
            $cash_register->save();

            // Update status for cash register transactions
            CashRegisterTransaction::where('store_id', $request->store_id)
                ->where('type', 1)
                ->where('payment_type_id', $payment_type_id)
                ->whereBetween('transaction_datetime', [
                    Carbon::parse($request->open_close_time)->startOfDay(),
                    Carbon::parse($request->open_close_time)->endOfDay(),
                ])
                ->update(['status' => 0]);

            // Save cash register transaction
            $cash_register_transaction = new CashRegisterTransaction();
            $cash_register_transaction->cash_register_id = $cash_register->id;
            $cash_register_transaction->store_id = $request->store_id;
            $cash_register_transaction->payment_type_id = $payment_type_id;
            $cash_register_transaction->amount = $total_amount;
            $cash_register_transaction->transaction_datetime = Carbon::now();
            $cash_register_transaction->type = 1; // Credit
            $cash_register_transaction->save();

            // // Save denominations if provided

            DB::commit();
            return response()->json([
                'status' => 200,
                'message' => 'Cash Register stored successfully.',
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Cash Register Store Error: ' . $e->getMessage());
            return response()->json([
                'status' => 400,
                'message' => 'An error occurred: ' . $e->getMessage(),
            ]);
        }
    }

    public function cashregistertransactionstore(Request $request)
    {
        DB::beginTransaction();
        // try {
        $payment_details = json_decode($request->payment_details);
        $payment_type_ids = array_column($payment_details, 'payment_type_id');
        CashRegisterTransaction::where([['store_id', $request->store_id], ['type', 1]])->whereIn('payment_type_id', $payment_type_ids)->whereBetween('transaction_datetime', [Carbon::now()->format('Y-m-d 00:00:00'), Carbon::now()->format('Y-m-d 23:59:59')])->update(['status' => 0]);
        foreach ($payment_details as $key => $payment_detail) {
            $cash_register_transaction = new CashRegisterTransaction();
            $cash_register_transaction->store_id = $request->store_id;
            $cash_register_transaction->cash_register_id = $request->cash_register_id;
            $cash_register_transaction->payment_type_id = $payment_detail->payment_type_id;
            $cash_register_transaction->amount = $payment_detail->amount;
            $cash_register_transaction->transaction_datetime = Carbon::now();
            $cash_register_transaction->type = 1; // Credit
            $cash_register_transaction->note = $request->note;
            $cash_register_transaction->save();
        }

        // Expense Docs Delete
        if (isset($request->deleted_transaction_doc_ids) && is_string($request->deleted_transaction_doc_ids)) {
            $deleted_transaction_doc_ids = json_decode($request->deleted_transaction_doc_ids, true);

            if (is_array($deleted_transaction_doc_ids) && count($deleted_transaction_doc_ids) > 0) {
                foreach ($deleted_transaction_doc_ids as $value) {
                    if ($value) {
                        $crtd = CashRegisterTransactionDocument::findOrFail($value);
                        CommonComponent::s3BucketFileDelete($crtd->file, $crtd->file_path);

                        $crtd->delete();
                    }
                }
            }
        }

        Log::info($request->transaction_documents);
        if (isset($request->transaction_documents) && count($request->transaction_documents) > 0 && $request->file('transaction_documents')) {
            foreach ($request->file('transaction_documents') as $key => $value) {
                if ($value) {
                    $imagePath = null;
                    $imageUrl = null;
                    $imageData = CommonComponent::s3BucketFileUpload($value, 'cash_register_transaction_document');
                    $imagePath = $imageData['filePath'];
                    // $imageUrl = $imageData['fileName'];
                    $imageUrl = $imageData['imageURL'];

                    $purchase_order_docs = new CashRegisterTransactionDocument();
                    $purchase_order_docs->store_id = $request->store_id;
                    $purchase_order_docs->payment_category_id = $request->payment_category;
                    $purchase_order_docs->attachment_date = $request->attachment_date ? $request->attachment_date : Carbon::now()->format('Y-m-d');
                    $purchase_order_docs->file = @$imageUrl;
                    $purchase_order_docs->file_path = @$imagePath;
                    $purchase_order_docs->save();
                }
            }
        }
        if ($request->has('denomination_details')) {
            // Decode the JSON string into an associative array
            $denominationDetails = json_decode($request->denomination_details, true);
            Log::info("denominationDetails");
            Log::info($denominationDetails);
            // Check if the decoding was successful and the result is an array
            if (is_array($denominationDetails)) {
                foreach ($denominationDetails as $denomination) {
                    // Convert count and amount to integers
                    $count = (int) $denomination['count'];
                    $value = (int) $denomination['denomination_value'];
                    $amountPerUnit = (int) $denomination['amount'];
                    $denomination_type_id = $denomination['denomination_id'];

                    // Calculate the total amount for this denomination
                    $totalAmount = $count * $value;

                    // Create a new entry in the Denomination model
                    Denomination::create([
                        'store_id' => $request->store_id,
                        'cash_register_id' => $request->cash_register_id,
                        'cash_register_transaction_id' => $cash_register_transaction->id,
                        'denomination_id' => $denomination_type_id,
                        'denomination_value' => $value, // Assuming you need to store the value per unit
                        'amount' => $amountPerUnit, // Store the total amount here
                        'total_amount' => $totalAmount, // If you need to store the same amount in total_amount
                        'count' => $count, // If you need to store the same amount in total_amount
                    ]);
                }
            } else {
                return response()->json([
                    'status' => 400,
                    'message' => 'Failed to decode denomination_details or result is not an array.',
                ]);
            }
        }

        DB::commit();
        return response()->json([
            'status' => 200,
            'message' => 'Cash Registers Stored successfully.',
        ]);
        // } catch (\Exception $e) {
        //     DB::rollback();
        //     Log::error($e);
        //     return response()->json([
        //         'status' => 400,
        //         'message' => 'Cash Register Transactions Store Failed.',
        //     ]);
        // }
    }

    public function storeClosingList(Request $request)
    {
        $today = now()->format('Y-m-d');
        $store_id = $request->store_id;

        $cashSales = CashRegister::where('store_id', $store_id)->whereDate('created_at', $today)->exists();
        $spoilage = Spoilage::where('from_store_id', $store_id)->whereDate('created_at', $today)->exists();
        $expense = IncomeExpenseTransaction::where('store_id', $store_id)->whereDate('created_at', $today)->exists();
        $stockUpdate = StoreStockUpdate::where('store_id', $store_id)->whereDate('created_at', $today)->exists();
        $fishCutting = FishCutting::where('store_id', $store_id)->whereDate('created_at', $today)->exists();
        $indentRequest = StoreIndentRequest::where('store_id', $store_id)->whereDate('created_at', $today)->exists();
        $creditSales = SalesOrder::where('store_id', $store_id)
            ->whereDate('created_at', $today)
            ->whereIn('payment_status', [2, 3])
            // ->where('status', 1)
            ->exists();
        $attendanceExists = StaffAttendance::whereDate('created_at', $today)->exists();

        $data = [];
        $store_closing_datas = [
            'Cash Sales',
            'spoilage',
            'Expense',
            'Stock Update',
            'Fish Cutting',
            'Indent Request',
            'Credit Sales',
            'Attendance',
        ];

        foreach ($store_closing_datas as $key => $store_closing_data) {
            $data[$key]['store_data'] = $store_closing_data;

            switch ($store_closing_data) {
                case 'Cash Sales':
                    $data[$key]['checked'] = $cashSales;
                    break;
                case 'spoilage':
                    $data[$key]['checked'] = $spoilage;
                    break;
                case 'Expense':
                    $data[$key]['checked'] = $expense;
                    break;
                case 'Stock Update':
                    $data[$key]['checked'] = $stockUpdate;
                    break;
                case 'Fish Cutting':
                    $data[$key]['checked'] = $fishCutting;
                    break;
                case 'Indent Request':
                    $data[$key]['checked'] = $indentRequest;
                    break;
                case 'Credit Sales':
                    $data[$key]['checked'] = $creditSales;
                    break;
                case 'Attendance':
                    $data[$key]['checked'] = $attendanceExists;
                    break;
                default:
                    $data[$key]['checked'] = false;
                    break;
            }
        }

        return response()->json([
            'status' => 200,
            'message' => 'Data checked successfully.',
            'data' => $data,
        ]);
    }

    public function dailycheckliststore(Request $request)
    {
        DB::beginTransaction();
        // try {
        $payment_details = json_decode($request->payment_details);
        foreach ($payment_details as $key => $payment_detail) {
            $cash_register_transaction = new CashRegisterTransaction();
            $cash_register_transaction->store_id = $request->store_id;
            $cash_register_transaction->cash_register_id = $request->cash_register_id;
            $cash_register_transaction->payment_type_id = $payment_detail->payment_type_id;
            $cash_register_transaction->amount = $payment_detail->amount;
            $cash_register_transaction->transaction_datetime = Carbon::now();
            $cash_register_transaction->type = 1; // Credit
            $cash_register_transaction->note = $request->note;
            $cash_register_transaction->save();
        }

        DB::commit();
        return response()->json([
            'status' => 200,
            'message' => 'Daily Check list Successfully updated.',
        ]);
    }
}
