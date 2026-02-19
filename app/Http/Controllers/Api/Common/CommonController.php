<?php

namespace App\Http\Controllers\Api\Common;

use App\Core\CommonComponent;
use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\AdminNotification;
use App\Models\Category;
use App\Models\City;
use App\Models\IncomeExpenseType;
use App\Models\PaymentTransaction;
use App\Models\PaymentType;
use App\Models\Product;
use App\Models\PurchaseOrder;
use App\Models\PurchaseSalesDocument;
use App\Models\SalesOrder;
use App\Models\SalesOrderMultiTransaction;
use App\Models\SalesOrderReturn;
use App\Models\StaffAdvance;
use App\Models\StaffAdvanceHistory;
use App\Models\State;
use App\Models\Store;
use App\Models\Supplier;
use App\Models\TransportType;
use App\Models\Unit;
use App\Models\User;
use App\Models\UserAdvance;
use App\Models\UserAdvanceHistory;
use App\Models\UserAppMenuMapping;
use App\Models\UserNotification;
use App\Models\Vendor;
use App\Models\Warehouse;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class CommonController extends Controller
{
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

    public function warehouselist(Request $request)
    {
        $warehouse_name = $request->warehouse_name;
        $user_warehouse = Auth::user()->user_warehouse();

        $warehouse = Warehouse::where(function ($query) use ($warehouse_name) {
            if ($warehouse_name != null) {
                $query->where('name', 'LIKE', '%' . $warehouse_name . '%')->orWhere('code', 'LIKE', '%' . $warehouse_name . '%');
            }
        })
            ->whereIn('id', $user_warehouse)
            ->where('status', 1)
            ->get();

        $all_warehouses = Warehouse::where(function ($query) use ($warehouse_name) {
            if ($warehouse_name != null) {
                $query->where('name', 'LIKE', '%' . $warehouse_name . '%')->orWhere('code', 'LIKE', '%' . $warehouse_name . '%');
            }
        })
            ->whereIn('id', $user_warehouse)
            ->where('status', 1)
            ->get();

        return response()->json([
            'status' => 200,
            'data' => $warehouse,
            'all_warehouses' => $all_warehouses,
            'message' => 'Warehouse fetched successfully.',
        ]);
    }

    public function storeslist(Request $request)
    {
        $store_name = $request->store_name;
        $user_stores = Auth::user()->user_stores();
        $stores = Store::where(function ($query) use ($store_name) {
            if ($store_name != null) {
                $query->where('store_name', 'LIKE', '%' . $store_name . '%')->orWhere('store_code', 'LIKE', '%' . $store_name . '%');
            }
        })
            ->where('status', 1)
            ->whereIn('id', $user_stores)->get();

        $all_stores = Store::where(function ($query) use ($store_name) {
            if ($store_name != null) {
                $query->where('store_name', 'LIKE', '%' . $store_name . '%')->orWhere('store_code', 'LIKE', '%' . $store_name . '%');
            }
        })
            ->where('status', 1)
            ->get();

        return response()->json([
            'status' => 200,
            'data' => $stores,
            'all_stores' => $all_stores,
            'message' => 'Stores fetched successfully.',
        ]);
    }

    public function customerlist(Request $request)
    {
        try {
            $customer_name = $request->customer_name;
            $vendors = Vendor::where(function ($query) use ($customer_name) {
                if ($customer_name != null) {
                    $query->where('first_name', 'LIKE', '%' . $customer_name . '%')->orWhere('last_name', 'LIKE', '%' . $customer_name . '%')->orWhere('phone_number', 'LIKE', '%' . $customer_name . '%');
                }
            })
                ->select('id', 'first_name', 'last_name', 'user_code', 'user_type', 'phone_number', 'status')
                ->orderBy('id', 'DESC')
                ->paginate(15);

            return response()->json([
                'status' => 200,
                'datas' => $vendors,
                'message' => 'Customer fetched successfully.',
            ]);
        } catch (\Exception $e) {
            Log::error($e);
            return response()->json([
                'status' => 400,
                'message' => 'Data not found.',
            ]);
        }
    }

    public function customerDetails(Request $request)
    {
        // try {
        // $validator = Validator::make($request->all(), [
        //     'customer_id' => 'required|integer',
        // ], [
        //     'customer_id' => 'Customer Id required.'
        // ]);

        // if ($validator->fails()) {
        //     return response()->json(['errors' => $validator->errors()->all()]);
        // }

        // $customer = Vendor::where('id', $request->customer_id)->first();

        // $user_advance = UserAdvance::where([['user_id', $request->customer_id], ['type', 1]])->orderByDesc('id')->first();

        // $purchase_order_query = SalesOrder::where([['vendor_id', $request->customer_id], ['status', '!=', config('app.returned_status')]])->orderByDesc('id')
        //     ->with(['vendor' => function ($query) {
        //         $query->select('id', 'first_name', 'last_name', 'user_code', 'user_type', 'status');
        //     }])
        //     ->with(['warehouse' => function ($query) {
        //         $query->select('id', 'name', 'code');
        //     }])
        //     ->with(['store' => function ($query) {
        //         $query->select('id', 'store_name', 'store_code', 'phone_number');
        //     }])
        //     ->with(['created_by_details' => function ($query) {
        //         $query->select('id', 'first_name', 'last_name', 'user_type', 'status');
        //     }])
        //     ->select('id', 'invoice_number', 'warehouse_id', 'store_id', 'vendor_id', 'status', 'delivered_date', 'payment_status', 'created_by', 'total_amount');

        // $purchase_order_amount = $purchase_order_query->sum('total_amount');
        // $purchase_orders = $purchase_order_query->limit(5)->get();

        // $return_order_query = SalesOrderReturn::where([['from_vendor_id', $request->customer_id], ['return_from', 2]])->orderByDesc('id') // 2 => vendor
        //     ->with(['from_vendor' => function ($query) {
        //         $query->select('id', 'first_name', 'last_name', 'user_code', 'user_type', 'status');
        //     }])
        //     ->with(['from_store' => function ($query) {
        //         $query->select('id', 'store_name', 'store_code', 'phone_number');
        //     }])
        //     ->with(['to_warehouse' => function ($query) {
        //         $query->select('id', 'name', 'code');
        //     }])
        //     ->with(['created_by_details' => function ($query) {
        //         $query->select('id', 'first_name', 'last_name', 'user_type', 'status');
        //     }])
        //     ->select('id', 'sales_order_return_number', 'to_warehouse_id', 'from_store_id', 'from_vendor_id', 'status', 'return_date', 'created_by', 'total_amount');

        // $return_order_amount = $return_order_query->sum('total_amount');
        // $return_orders = $return_order_query->limit(5)->get();

        // return response()->json([
        //     'status' => 200,
        //     'datas' => $customer,
        //     'purchase_order_amount' => @$purchase_order_amount,
        //     'return_order_amount' => @$return_order_amount,
        //     'advance_amount' => @$user_advance->total_amount,
        //     'purchase_orders' => $purchase_orders,
        //     'return_orders' => $return_orders,
        //     'message' => 'Customer Details fetched successfully.',
        // ]);
        $validator = Validator::make($request->all(), [
            'customer_id' => 'required|integer',
        ], [
            'customer_id.required' => 'Customer Id is required.',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->all()]);
        }

        $customer = Vendor::where('id', $request->customer_id)->first();

        $user_advance = UserAdvance::where([
            ['user_id', $request->customer_id],
            // ['type', 1]
        ])->orderByDesc('id')->first();

        // Purchase Orders
        $purchase_order_query = SalesOrder::where([
            ['vendor_id', $request->customer_id],
            ['status', '!=', config('app.returned_status')],
        ])->orderByDesc('id')
            ->with([
                'vendor' => function ($query) {
                    $query->select('id', 'first_name', 'last_name', 'user_code', 'user_type', 'status');
                },
                'warehouse' => function ($query) {
                    $query->select('id', 'name', 'code');
                },
                'store' => function ($query) {
                    $query->select('id', 'store_name', 'store_code', 'phone_number');
                },
                'created_by_details' => function ($query) {
                    $query->select('id', 'first_name', 'last_name', 'user_type', 'status');
                },
            ])->select('id', 'invoice_number', 'warehouse_id', 'store_id', 'vendor_id', 'status', 'delivered_date', 'payment_status', 'created_by', 'total_amount');

        $purchase_orders = $purchase_order_query->limit(5)->get();
        // Calculate amounts
        $purchase_order_ids = $purchase_orders->pluck('id')->toArray();

        $sales_order_transactions = PaymentTransaction::where('transaction_type', 2)
            ->whereIn('reference_id', $purchase_order_ids)
            ->with('payment_type_details', 'payment_transaction_documents')
            ->get();

        // Calculate paid amount
        $paid_amount = $sales_order_transactions->sum('amount');

        // Calculate purchase order amount
        $purchase_order_amount = $purchase_order_query->sum('total_amount');

        // Calculate received amount
        $received_amount = $purchase_orders->where('payment_status', 1)->sum('total_amount');

        // Calculate due amount
        $due_amount = $purchase_order_amount - $paid_amount;
        $due_amount = $due_amount > 0 ? $due_amount : 0;
        // Return Orders
        $return_order_query = SalesOrderReturn::where([
            ['from_vendor_id', $request->customer_id],
            ['return_from', 2], // 2 => vendor
        ])->orderByDesc('id')
            ->with([
                'from_vendor' => function ($query) {
                    $query->select('id', 'first_name', 'last_name', 'user_code', 'user_type', 'status');
                },
                'from_store' => function ($query) {
                    $query->select('id', 'store_name', 'store_code', 'phone_number');
                },
                'to_warehouse' => function ($query) {
                    $query->select('id', 'name', 'code');
                },
                'created_by_details' => function ($query) {
                    $query->select('id', 'first_name', 'last_name', 'user_type', 'status');
                },
            ])->select('id', 'sales_order_return_number', 'to_warehouse_id', 'from_store_id', 'from_vendor_id', 'status', 'return_date', 'created_by', 'total_amount');

        $return_orders = $return_order_query->limit(5)->get();
        $return_order_amount = $return_order_query->sum('total_amount');

        return response()->json([
            'status' => 200,
            'datas' => $customer,
            'purchase_order_amount' => $purchase_order_amount,
            'received_amount' => $paid_amount, // before only after completed payment sales order payment only gets but now the how many payments done agints the sales order that amount getted
            'due_amount' => $due_amount,
            'return_order_amount' => $return_order_amount,
            'advance_amount' => $user_advance ? $user_advance->total_amount : 0,
            'purchase_orders' => $purchase_orders,
            'return_orders' => $return_orders,
            'message' => 'Customer Details fetched successfully.',
        ]);
        // } catch (\Exception $e) {
        //     Log::error($e);
        //     return response()->json([
        //         'status' => 400,
        //         'message' => 'Data not found.',
        //     ]);
        // }
    }

    public function customerSaleslist(Request $request)
    {
        // try {

        $sales_order_count = SalesOrder::whereIn('payment_status', config('app.purchase_ordered_status'))->count();
        $sales_received_count = SalesOrder::whereIn('payment_status', config('app.purchase_received_status'))->count();

        if ($request->warehouse_id != null) {
            $warehouse_id = array($request->warehouse_id);
        } else {
            $warehouse_id = Auth::user()->user_warehouse();
        }
        $customer_id = $request->customer_id;
        $invoice_number = $request->invoice_number;
        $status = $request->status;
        if ($request->payment_status == 4) { // Credit Sale
            $payment_status = [2, 3];
        } elseif ($request->payment_status != null) {
            $payment_status = array($request->payment_status);
        } else {
            $payment_status = [1, 2, 3];
        }
        $from_date = $request->from_date;
        $to_date = $request->to_date;
        $saleslists = SalesOrder::where(function ($query) use ($warehouse_id, $customer_id, $invoice_number, $from_date, $to_date, $status, $payment_status) {
            if (count($warehouse_id) > 0) {
                $query->whereIn('warehouse_id', $warehouse_id);
            }
            if ($customer_id != null) {
                $query->where('vendor_id', $customer_id);
            }
            if ($status != null) {
                $query->where('status', $status);
            }
            if ($invoice_number != null) {
                $query->where('invoice_number', 'LIKE', '%' . $invoice_number . '%');
            }
            if ($from_date != null && $to_date != null) {
                $dateformatwithtime = CommonComponent::dateformatwithtime($from_date, $to_date);
                $query->whereBetween('delivered_date', $dateformatwithtime);
            }
            if ($payment_status != null) {
                $query->whereIn('payment_status', $payment_status);
            }
        })
            ->with(['vendor:id,first_name,last_name,user_type,status'])
            ->with(['warehouse:id,name,code'])
            ->with(['created_by_details:id,first_name,last_name,user_type,status'])
            ->with(['sales_order_transactions']) // Ensure this line is added for eager loading
            ->select('id', 'invoice_number', 'warehouse_id', 'vendor_id', 'status', 'delivered_date', 'payment_status', 'created_by', 'total_amount')
            ->orderBy('id', 'DESC')
            ->paginate(15);

        // Transform and calculate paid_amount, pending_amount
        // $saleslists->getCollection()->transform(function ($salesOrder) {
        //     $paid_amount = $salesOrder->sales_order_transactions->sum('amount');
        //     $salesOrder->paid_amount = $paid_amount;
        //     $salesOrder->pending_amount = $salesOrder->total_amount - $paid_amount;
        //     return $salesOrder;
        // });
        // ->through(function ($saleslists) {
        //     $paid_amount = $saleslists->sales_order_transactions->sum('amount');
        //     Log::info("paid_amountpaid_amount");
        //     Log::info($saleslists);
        //     $saleslists['paid_amount'] = $paid_amount;
        //     $saleslists['pending_amount'] = $saleslists->total_amount - $paid_amount;
        //     Log::info("saleslists['pending_amount']");
        //     Log::info($saleslists['pending_amount']);
        //     return $saleslists;
        //      // Calculate paid_amount and pending_amount for each sales order

        // });

        // Transform and calculate paid_amount, pending_amount
        $saleslists->getCollection()->transform(function ($salesOrder) {
            $paid_amount = $salesOrder->sales_order_transactions->sum('amount');
            $salesOrder->paid_amount = $paid_amount;
            $salesOrder->pending_amount = $salesOrder->total_amount - $paid_amount;
            return $salesOrder;
        });
        return response()->json([
            'status' => 200,
            'sales_order_count' => $sales_order_count,
            'sales_received_count' => $sales_received_count,
            'saleslists' => $saleslists,
            'message' => 'Customer fetched successfully.',
        ]);
        // } catch (\Exception $e) {
        //     Log::error($e);
        //     return response()->json([
        //         'status' => 400,
        //         'message' => 'Data not found.',
        //     ]);
        // }
    }

    public function multipleSalesOrderpaymentUpdate(Request $request)
    {
        DB::beginTransaction();
        try {
            $customer_id = $request->customer_id;
            $total_paying_amount = $request->amount != null ? $request->amount : 0;
            $advance_amount = $request->advance_amount;
            if ($request->advance_amount_included == 1) {
                $total_paying_amount = $request->amount + $request->advance_amount;
            }
            $sales_order_ids = $request->sales_order_id;
            foreach ($sales_order_ids as $key => $sales_order_id) {
                if ($total_paying_amount > 0) {
                    $sales_order_details = SalesOrder::findOrFail($sales_order_id);
                    $paid_amount = $sales_order_details->sales_order_transactions->sum('amount');
                    $total_amount = $sales_order_details->total_amount;
                    $due_amount = $total_amount - $paid_amount;
                    if ($advance_amount > 0) {
                        if ($due_amount >= $advance_amount) {
                            $this->useradvancecreditdebit(2, $sales_order_id, $customer_id, $advance_amount, 2); // 2 => referrence table, 2=>debit
                            $advance_amount = 0;
                        } else {
                            $advance_amount = $advance_amount - $due_amount;
                            $this->useradvancecreditdebit(2, $sales_order_id, $customer_id, $due_amount, 2); // 2 => referrence table, 2=>debit
                        }
                    }

                    if ($due_amount >= $total_paying_amount) {
                        $paid_amount = $total_paying_amount;
                        $total_paying_amount = 0;
                    } else {
                        $total_paying_amount = $total_paying_amount - $due_amount;
                        $paid_amount = $due_amount;
                    }

                    $payment_transaction = new PaymentTransaction();
                    $payment_transaction->transaction_number = CommonComponent::invoice_no('payment_transaction'); // Auto Generate
                    $payment_transaction->transaction_type = 2; // Sales Order
                    $payment_transaction->type = 1; // Credit
                    $payment_transaction->reference_id = $sales_order_id;
                    $payment_transaction->payment_type_id = $request->payment_type;
                    $payment_transaction->amount = $paid_amount;
                    $payment_transaction->transaction_datetime = Carbon::now();
                    $payment_transaction->status = 1;
                    $payment_transaction->save();

                    // Payment Transaction Docs Store
                    if (isset($request->payment_transaction_documents) && count($request->payment_transaction_documents) > 0 && $request->file('payment_transaction_documents')) {
                        CommonComponent::payment_transaction_documents($request->file('payment_transaction_documents'), 1, $payment_transaction->id); // 1=> Purchase Document
                    }

                    // $total_paying_amount -= $due_amount;
                    $sales_order_details = SalesOrder::findOrFail($sales_order_id);
                    $paid_amount = $sales_order_details->sales_order_transactions->sum('amount');
                    $total_amount = $sales_order_details->total_amount;
                    if ($paid_amount == 0) {
                        $sales_order_details->payment_status = (isset($request->payment_status) && ($request->payment_status != null)) ? $request->payment_status : 2; // UnPaid
                        $sales_order_details->save();
                    } else if ($paid_amount >= $total_amount) {
                        $sales_order_details->payment_status = (isset($request->payment_status) && ($request->payment_status != null)) ? $request->payment_status : 1; // Paid
                        $sales_order_details->save();
                    } else if ($paid_amount < $total_amount) {
                        $sales_order_details->payment_status = (isset($request->payment_status) && ($request->payment_status != null)) ? $request->payment_status : 3; // Pending
                        $sales_order_details->save();
                    }
                }
            }

            // if ($total_paying_amount > 0) {
            //     $this->useradvancecreditdebit(NULL, NULL, $customer_id, $total_paying_amount, 1); // 1 => referrence table, 1=>credit
            // }

            $multi_transaction = new SalesOrderMultiTransaction();
            $multi_transaction->sales_order_id = json_encode($request->sales_order_id);
            $multi_transaction->customer_id = $request->customer_id;
            $multi_transaction->amount = $request->amount;
            $multi_transaction->advance_amount_included = $request->advance_amount_included;
            $multi_transaction->advance_amount = $request->advance_amount;
            $multi_transaction->payment_type_id = $request->payment_type;
            $multi_transaction->transaction_date = $request->transaction_date;
            $multi_transaction->remarks = $request->remarks;
            $multi_transaction->save();

            DB::commit();
            return response()->json([
                'status' => 200,
                'message' => 'Transaction Stored successfully.',
            ]);
        } catch (\Exception $e) {
            Log::error($e);
            DB::rollback();
            return response()->json([
                'status' => 400,
                'message' => 'There are some Technical Issue, Kindly contact Admin.',
            ]);
        }
    }

    public function useradvancecreditdebit($referrence_table, $referrence_id, $customer_id, $amount, $creditdebit)
    {
        DB::beginTransaction();
        if ($amount > 0) {
            $user_advance = UserAdvance::where([['user_id', $customer_id]])->first();
            if ($user_advance == null) {
                $user_advance = new UserAdvance();
            }
            $user_advance->user_id = $customer_id;
            $user_advance->type = $creditdebit; // Credit is 1 and 2 is debit
            $user_advance->amount = $amount;
            if ($creditdebit == 1) {
                $user_advance->total_amount = @$user_advance->total_amount + $amount;
            } else {
                $user_advance->total_amount = @$user_advance->total_amount - $amount;
            }
            $user_advance->save();

            $advancehistory = new UserAdvanceHistory();
            $advancehistory->user_id = $customer_id;
            $advancehistory->transaction_type = $referrence_table; // Sales
            $advancehistory->reference_id = $referrence_id;
            $advancehistory->type = $creditdebit; // Credit is 1 and 2 is debit
            $advancehistory->amount = $amount;
            $advancehistory->save();

            DB::commit();
        }
    }

    public function customerAdvanceStore(Request $request)
    {
        DB::beginTransaction();
        // try {
        $user_advance = UserAdvance::where([['user_id', $request->customer_id]])->first();
        if ($user_advance == null) {
            $user_advance = new UserAdvance();
        }
        $user_advance->user_id = $request->customer_id;
        $user_advance->type = 1; // Credit
        $user_advance->amount = $request->amount;
        $user_advance->total_amount = @$user_advance->total_amount + $request->amount;
        $user_advance->save();

        $advancehistory = new UserAdvanceHistory();
        $advancehistory->user_id = $request->customer_id;
        $advancehistory->transaction_type = 2; // Sales
        $advancehistory->reference_id = null;
        $advancehistory->type = 1; // Credit
        $advancehistory->amount = $request->amount;
        $advancehistory->save();

        $payment_transaction = new PaymentTransaction();
        $payment_transaction->transaction_number = CommonComponent::invoice_no('payment_transaction'); // Auto Generate
        $payment_transaction->transaction_type = 6; // Advance
        $payment_transaction->type = 2; // Debit
        $payment_transaction->reference_id = $advancehistory->id;
        $payment_transaction->payment_type_id = (int) $request->payment_type_id;
        $payment_transaction->amount = $request->amount;
        $payment_transaction->transaction_datetime = $request->transaction_datetime;
        $payment_transaction->status = 1;
        $payment_transaction->note = @$request->note;
        $payment_transaction->save();

        if (isset($request->payment_transaction_documents) && count($request->payment_transaction_documents) > 0 && $request->file('payment_transaction_documents')) {
            CommonComponent::payment_transaction_documents($request->file('payment_transaction_documents'), 5, $payment_transaction->id); // 3 => User Advance
        }

        // Transaport Tracking Docs Store
        if (isset($request->user_advance_images) && count($request->user_advance_images) > 0 && $request->file('user_advance_images')) {
            foreach ($request->file('user_advance_images') as $key => $value) {
                if ($value) {
                    $imagePath = null;
                    $imageUrl = null;
                    $imageData = CommonComponent::s3BucketFileUpload($value, 'user_advance_docs');
                    $imagePath = $imageData['filePath'];
                    // $imageUrl = $imageData['fileName'];
                    $imageUrl = $imageData['imageURL'];

                    $purchase_order_docs = new PurchaseSalesDocument();
                    $purchase_order_docs->type = 6; // User Advance
                    $purchase_order_docs->reference_id = $advancehistory->id;
                    $purchase_order_docs->document_type = 1; // Expense (User Advance)
                    $purchase_order_docs->file = @$imageUrl;
                    $purchase_order_docs->file_path = @$imagePath;
                    $purchase_order_docs->save();
                }
            }
        }

        // $content = 'Rs ' . $request->amount . ' added successfully';
        // Helper::sendPushToNotification($content);

        DB::commit();

        return response()->json([
            'status' => 200,
            'message' => 'Advance Amount Added successfully.',
        ]);
        // } catch (\Exception $e) {
        //     Log::error($e);
        //     DB::rollback();
        //     return response()->json([
        //         'status' => 400,
        //         'message' => 'Data Stored Fail.',
        //     ]);
        // }
    }

    public function staffAdvanceList(Request $request)
    {
        try {
            $staff_id = $request->staff_id;
            $name = $request->name;
            $staffAdvance = StaffAdvance::where(function ($query) use ($staff_id, $name) {
                if ($name != null) {
                    $query->where('first_name', 'LIKE', '%' . $name . '%')->orWhere('last_name', 'LIKE', '%' . $name . '%');
                }
                if ($staff_id != null) {
                    $query->where('staff_id', $staff_id);
                }
            })
                ->orderBy('id', 'DESC')
                ->paginate(15);

            return response()->json([
                'status' => 200,
                'datas' => $staffAdvance,
                'message' => 'Staff fetched successfully.',
            ]);
        } catch (\Exception $e) {
            Log::error($e);
            return response()->json([
                'status' => 400,
                'message' => 'Data not found.',
            ]);
        }
    }

    public function staffAdvanceListView(Request $request)
    {
        // try {
        $staff_id = $request->staff_id;
        $staffAdvance = StaffAdvanceHistory::where(function ($query) use ($staff_id) {
            if ($staff_id != null) {
                $query->where('staff_id', $staff_id);
            }
        })
            ->with(['payment_type'])
            ->orderBy('id', 'DESC')
            ->paginate(15);

        return response()->json([
            'status' => 200,
            'datas' => $staffAdvance,
            'message' => 'Staff fetched successfully.',
        ]);
        // } catch (\Exception $e) {
        //     Log::error($e);
        //     return response()->json([
        //         'status' => 400,
        //         'message' => 'Data not found.',
        //     ]);
        // }
    }

    public function staffAdvanceStore(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'amount' => 'required|numeric|min:0.01',
            'payment_type_id' => 'required|exists:payment_types,id',
            'transaction_datetime' => 'required|date',
            'note' => 'nullable|string|max:255',
        ], [
            'amount.required' => 'The amount is required.',
            'amount.numeric' => 'The amount must be a number.',
            'amount.min' => 'The amount must be at least 0.01.',
            'payment_type_id.required' => 'The payment type ID is required.',
            'payment_type_id.exists' => 'The selected payment type ID is invalid.',
            'transaction_datetime.required' => 'The transaction date and time is required.',
            'transaction_datetime.date' => 'The date must be a valid date.',
            'status.integer' => 'The status must be an integer.',
            'status.in' => 'The status must be either 0 or 1.',
            'note.string' => 'The note must be a string.',
            'note.max' => 'The note may not be greater than 255 characters.',
        ]
        );

        if ($validator->fails()) {
            return response()->json(['message' => $validator->errors()->all()]);
        }

        $user_advance = StaffAdvance::where([['staff_id', $request->staff_id]])->first();
        if ($user_advance == null) {
            $user_advance = new StaffAdvance();
        }
        $user_advance->staff_id = $request->staff_id;
        $user_advance->type = 1; // Credit
        $user_advance->amount = $request->amount;
        $user_advance->payment_type_id = $request->payment_type_id;
        $user_advance->date = $request->transaction_datetime;
        $user_advance->status = $request->status ?? 1;
        $user_advance->note = $request->note ?? null;
        $user_advance->total_amount = @$user_advance->total_amount + $request->amount;
        $user_advance->save();

        $advancehistory = new StaffAdvanceHistory();
        $advancehistory->staff_id = $request->staff_id;
        $advancehistory->staff_advance_id = $user_advance->id;
        $advancehistory->type = 1; // Credit
        $advancehistory->amount = $request->amount;
        $advancehistory->payment_type_id = $request->payment_type_id;
        $advancehistory->status = $request->status ?? 1;
        $advancehistory->note = $request->note ?? null;

        if ($request->hasFile('staff_advance_documents')) {
            $imageData = CommonComponent::s3BucketFileUpload($request->staff_advance_documents, 'staff_advance_doc');
            $imagePath = $imageData['filePath'];
            // $imageUrl = $imageData['fileName'];
            $imageUrl = $imageData['imageURL'];

            if ($imageUrl !== null) {
                $advancehistory->file = $imageUrl;
                $advancehistory->file_path = $imagePath;
            }
        }
        $advancehistory->save();

        $payment_transaction = new PaymentTransaction();
        $payment_transaction->transaction_number = CommonComponent::invoice_no('payment_transaction'); // Auto Generate
        $payment_transaction->transaction_type = 6; // Advance
        $payment_transaction->type = 2; // Debit
        $payment_transaction->reference_id = $advancehistory->id;
        $payment_transaction->payment_type_id = (int) $request->payment_type_id;
        $payment_transaction->amount = $request->amount;
        $payment_transaction->transaction_datetime = $request->transaction_datetime;
        $payment_transaction->status = 1;
        $payment_transaction->note = @$request->notes;
        $payment_transaction->save();

        if (isset($request->staff_advance_doc) && count($request->staff_advance_doc) > 0 && $request->file('staff_advance_doc')) {
            CommonComponent::payment_transaction_documents($request->file('staff_advance_doc'), 5, $payment_transaction->id); // 3 => User Advance
        }

        // $content = 'Rs ' . $request->amount . ' added successfully';
        // Helper::sendPushToNotification($content);

        DB::commit();

        return response()->json([
            'status' => 200,
            'message' => 'Advance Amount Added successfully.',
        ]);

    }

    public function staffAdvanceEdit(Request $request)
    {
        try {
            $staff_id = $request->transaction_id;

            $staffAdvance = StaffAdvance::with('staff_adavance_history')->findOrFail($staff_id);

            return response()->json([
                'status' => 200,
                'data' => $staffAdvance,
                'message' => 'Payment Transaction Updated Successfully.',
            ]);
        } catch (\Exception $e) {
            Log::error($e);
            DB::rollback();
            return response()->json([
                'status' => 400,
                'message' => 'Data not found.',
            ]);
        }
    }

    public function staffAdvanceUpdate(Request $request)
    {
        $id = $request->id;
        $user_advance = StaffAdvance::updateOrCreate(
            ['id' => $id],
            [
                'staff_id' => $request->user_id,
                'type' => 1, // Credit
                'amount' => $request->amount,
                'date' => $request->date,
                'status' => $request->status,
                'note' => $request->notes,
            ]
        );

        $advancehistory = StaffAdvanceHistory::updateOrCreate(
            ['staff_advance_id' => $user_advance->id],
            [
                'staff_id' => $request->user_id,
                'type' => 1, // Credit
                'amount' => $request->amount,
                'status' => $request->status,
                'note' => $request->notes,
            ]
        );

        if ($request->hasFile('staff_advance_documents')) {
            $imageData = CommonComponent::s3BucketFileUpload($request->staff_advance_documents, 'staff_advance_doc');
            $imagePath = $imageData['filePath'];
            $imageUrl = $imageData['imageURL'];

            if ($imageUrl !== null) {
                $advancehistory->file = $imageUrl;
                $advancehistory->file_path = $imagePath;
                $advancehistory->save();
            }
        }

        // Create payment transaction
        $payment_transaction = new PaymentTransaction();
        $payment_transaction->transaction_number = CommonComponent::invoice_no('payment_transaction');
        $payment_transaction->transaction_type = 6; // Advance
        $payment_transaction->type = 2; // Debit
        $payment_transaction->reference_id = $advancehistory->id;
        $payment_transaction->payment_type_id = (int) $request->payment_type_id;
        $payment_transaction->amount = $request->amount;
        $payment_transaction->transaction_datetime = $request->transaction_datetime;
        $payment_transaction->status = 1;
        $payment_transaction->note = $request->notes;
        $payment_transaction->save();

        // Upload transaction documents if provided
        if ($request->hasFile('staff_advance_doc')) {
            CommonComponent::payment_transaction_documents($request->file('staff_advance_doc'), 5, $payment_transaction->id);
        }
        DB::commit();
        return response()->json([
            'status' => 200,
            'message' => 'Advance Amount Added successfully.',
        ]);

    }

    public function supplierlist(Request $request)
    {
        try {
            $supplier_name = $request->supplier_name;
            $suppliers = User::supplier()
                ->where(function ($query) use ($supplier_name) {
                    if ($supplier_name != null) {
                        $query
                            ->where('first_name', 'LIKE', '%' . $supplier_name . '%')
                            ->orWhere('last_name', 'LIKE', '%' . $supplier_name . '%')
                            ->orWhere('phone_number', 'LIKE', '%' . $supplier_name . '%');
                    }
                })
                ->select('id', 'first_name', 'last_name', 'user_code', 'user_type', 'phone_number', 'status')
                ->orderBy('id', 'DESC')
                ->paginate(15);

            return response()->json([
                'status' => 200,
                'datas' => $suppliers,
                'message' => 'Supplier fetched successfully.',
            ]);
        } catch (\Exception $e) {
            Log::error($e);
            return response()->json([
                'status' => 400,
                'message' => 'Data not found.',
            ]);
        }
    }

    public function supplierDetails(Request $request)
    {
        // try {
        $validator = Validator::make($request->all(), [
            'supplier_id' => 'required|integer',
        ], [
            'supplier_id' => 'Supplier Id required.',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->all()]);
        }

        $supplier_details = Supplier::where('id', $request->supplier_id)->first();

        $user_advance = UserAdvance::where([['user_id', $request->supplier_id], ['type', 1]])->orderByDesc('id')->first();
        $user_advance = $user_advance ? $user_advance : UserAdvance::where([['user_id', $request->supplier_id], ['type', 2]])->orderByDesc('id')->first();
        $purchase_orders = PurchaseOrder::whereIn('status', config('app.purchase_received_status'))->where('supplier_id', $request->supplier_id)->orderByDesc('id')
            ->with([
                'supplier' => function ($query) {
                    $query->select('id', 'first_name', 'last_name', 'user_type', 'status');
                },
            ])
            ->with([
                'warehouse' => function ($query) {
                    $query->select('id', 'name', 'code');
                },
            ])
            ->with([
                'created_by_details' => function ($query) {
                    $query->select('id', 'first_name', 'last_name', 'user_type', 'status');
                },
            ])
            ->with('purchase_order_transactions')
            ->select('id', 'purchase_order_number', 'warehouse_id', 'supplier_id', 'status', 'delivery_date', 'payment_status', 'created_by', 'total')
            ->limit(5)
            ->get();
        $pending_order_payments = PurchaseOrder::where('supplier_id', $request->supplier_id)
            ->where('supplier_id', $request->supplier_id)
            ->where('payment_status', '!=', 1)
            ->with('purchase_order_transactions')
            ->get();

        // Initialize variables
        $totalOrderPayments = 0.0;
        $totalPaidAmount = 0.0;

        // Calculate total order payments and total paid amount
        foreach ($pending_order_payments as $order) {
            // Convert total amount to float and add to total order payments
            $totalOrderPayments += (float) $order->total;

            // Calculate sum of payment transactions for this order
            foreach ($order->purchase_order_transactions as $transaction) {
                // Convert amount to float and add to total paid amount
                $totalPaidAmount += (float) $transaction['amount'];
            }
        }

        // Calculate pending order payments
        $pendingOrderPayments = $totalOrderPayments - $totalPaidAmount;

        // Prepare response
        $pending_order_payment[] = [
            "total_order_payments" => $totalOrderPayments,
            "paid_order_payments" => $totalPaidAmount,
            "due_amount" => $pendingOrderPayments,
        ];
        // $pending_order_payments = PurchaseOrder::with('purchase_order_transactions')
        //     ->LeftJoin('payment_transactions', function ($join) {
        //         $join->on('payment_transactions.reference_id', 'purchase_orders.id')->where([['transaction_type', 1], ['type', 2]])->whereNull('payment_transactions.deleted_at');
        //     })
        //     ->where('supplier_id', $request->supplier_id)
        //     ->where('payment_status', '!=', 1)
        // // ->whereIn('purchase_orders.status', config('app.purchase_received_status'))
        //     ->select('purchase_orders.*', 'purchase_orders.id as id')
        //     ->select(
        //         DB::raw('SUM(total) as total_order_amount'),
        //         DB::raw('SUM(amount) as total_paid_amount'),
        //         DB::raw('(SUM(total)-SUM(amount)) as due_amount'))
        //     ->get();
        return response()->json([
            'status' => 200,
            'datas' => $supplier_details,
            'advance_amount' => @$user_advance->total_amount,
            'purchase_orders' => $purchase_orders,
            'pending_order_payments' => $pending_order_payment,
            'message' => 'Supplier Details fetched successfully.',
        ]);
        // } catch (\Exception $e) {
        //     Log::error($e);
        //     return response()->json([
        //         'status' => 400,
        //         'message' => 'Data not found.',
        //     ]);
        // }
    }

    public function adminlist(Request $request)
    {
        try {
            $name = $request->name;
            $admin = Admin::where([['status', 1], ['user_type', 1]])
                ->where(function ($query) use ($name) {
                    if ($name != null) {
                        $query
                            ->where('first_name', 'LIKE', '%' . $name . '%')
                            ->orWhere('last_name', 'LIKE', '%' . $name . '%')
                            ->orWhere('phone_number', 'LIKE', '%' . $name . '%');
                    }
                })
                ->select('id', 'first_name', 'last_name', 'user_type', 'phone_number', 'status')
                ->orderBy('id', 'DESC')
                ->paginate(15);

            return response()->json([
                'status' => 200,
                'datas' => $admin,
                'message' => 'Admin fetched successfully.',
            ]);
        } catch (\Exception $e) {
            Log::error($e);
            return response()->json([
                'status' => 400,
                'message' => 'Data not found.',
            ]);
        }
    }

    public function storemanagerpartnerlist(Request $request)
    {
        // try {
        $name = $request->name;
        $store_id = $request->store_id;
        $user_type = $request->user_type;
        $store_employees = Admin::when($request, function ($query) use ($store_id, $user_type, $name) {
            if ($user_type == 1 || $user_type == 2) {
                $query->LeftJoin('admin_store_mappings', function ($join) use ($store_id) {
                    $join->on('admin_store_mappings.admin_id', 'admins.id')->whereNull('admin_store_mappings.deleted_at')
                        ->where([['admin_store_mappings.store_id', $store_id], ['admin_store_mappings.status', 1]]);
                })
                    ->Orwhere([['admin_store_mappings.store_id', $store_id], ['admin_store_mappings.status', 1]]);
            }
            if ($user_type == 3) {
                $query->LeftJoin('partnership_details', function ($join) use ($store_id) {
                    $join->on('partnership_details.partner_id', 'admins.id')->whereNull('partnership_details.deleted_at')
                        ->where([['partnership_details.store_id', $store_id], ['partnership_details.status', 1]]);
                })
                    ->Orwhere([['partnership_details.store_id', $store_id], ['partnership_details.status', 1]]);
            }
            if ($user_type == 4) {
                $query->LeftJoin('staff_store_mappings', function ($join) use ($store_id) {
                    $join->on('staff_store_mappings.staff_id', 'admins.id')->whereNull('staff_store_mappings.deleted_at')
                        ->where([['staff_store_mappings.store_id', $store_id], ['staff_store_mappings.status', 1]]);
                })
                    ->Orwhere([['staff_store_mappings.store_id', $store_id], ['staff_store_mappings.status', 1]]);
            }
            if ($user_type == null) {
                $query->LeftJoin('admin_store_mappings', function ($join) use ($store_id) {
                    $join->on('admin_store_mappings.admin_id', 'admins.id')->whereNull('admin_store_mappings.deleted_at')
                        ->where([['admin_store_mappings.store_id', $store_id], ['admin_store_mappings.status', 1]]);
                })
                    ->Orwhere([['admin_store_mappings.store_id', $store_id], ['admin_store_mappings.status', 1]]);

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
            }
        })
            ->when($request, function ($query) use ($name) {
                if ($name != null) {
                    $query->where('first_name', 'LIKE', '%' . $name . '%')->orWhere('last_name', 'LIKE', '%' . $name . '%')->orWhere('phone_number', 'LIKE', '%' . $name . '%');
                }
            })
        // ->Orwhere([['user_type', 1]])
            ->distinct('admins.id')
            ->select('admins.*')
            ->paginate(20);

        return response()->json([
            'status' => 200,
            'store_employees' => $store_employees,
            'message' => 'Cash Paid Details fetched successfully.',
        ]);
        // } catch (\Exception $e) {
        //     Log::error($e);
        //     return response()->json([
        //         'status' => 400,
        //         'message' => 'Data not found.',
        //     ]);
        // }
    }

    public function fishCuttingProductList(Request $request)
    {
        try {
            // Validate request if needed (example)
            // $request->validate([
            //     'product_name' => 'string|max:255', // Example validation rules
            // ]);

            $product_name = $request->product_name;

            // Query products with eager loading relationships
            // $products = FishCuttingProductMap::with(['product' => function ($query) use ($product_name) {
            //     $query->where(function ($query) use ($product_name) {
            //         if ($product_name != null) {
            //             $query
            //                 ->where('name', 'LIKE', '%' . $product_name . '%')
            //                 ->orWhere('slug', 'LIKE', '%' . $product_name . '%')
            //                 ->orWhere('sku_code', 'LIKE', '%' . $product_name . '%');
            //         }
            //     });
            // }])
            $products = Product::whereHas('fish_cutting_grouped_products')
            // ->active();
                ->where(function ($query) use ($product_name) {
                    if ($product_name != null) {
                        $query
                            ->where('name', 'LIKE', '%' . $product_name . '%')
                            ->orWhere('slug', 'LIKE', '%' . $product_name . '%')
                            ->orWhere('sku_code', 'LIKE', '%' . $product_name . '%');
                    }
                })
                ->with('product_category:id,product_id,category_id', 'product_category.category:id,name,parent_id,slug,is_featured')
                ->with('unit:id,unit_name,unit_short_code,allow_decimal,operator,operation_value')
                ->orderBy('id', 'ASC')
                ->paginate(20);
            // ->with([
            //     'product_category' => function ($query) {
            //         $query->select('id', 'product_id', 'category_id')
            //             ->with('category:id,name,parent_id,slug,is_featured');
            //     },
            //     'unit:id,unit_name,unit_short_code,allow_decimal,operator,operation_value'
            // ])->get();
            // ->orderBy('id', 'ASC')
            // ->paginate(100);

            // Return JSON response
            return response()->json([
                'status' => 200,
                'datas' => $products, // Changed 'datas' to 'data' for consistency
                'message' => 'Products fetched successfully.',
            ]);
        } catch (\Exception $e) {
            Log::error($e);
            return response()->json([
                'status' => 400,
                'message' => 'Data not found.', // Consider revising this message based on specific error handling needs
            ]);
        }
    }

    public function productlist(Request $request)
    {
        // try {
        $product_name = $request->product_name;
        $date = $request->date;
        $can_proceed_last_date = $request->can_proceed_last_date;
        $currentDate = Carbon::now()->toDateString(); // Get current date in 'Y-m-d' format
        $storeId = $request->has('store_id');

        if ($request->has('list_type') == 'FishCuttingList') {

            $products = Product::whereHas('fish_cutting_grouped_products')
                ->where(function ($query) use ($product_name) {
                    if ($product_name != null) {
                        $query
                            ->where('name', 'LIKE', '%' . $product_name . '%')
                            ->orWhere('slug', 'LIKE', '%' . $product_name . '%')
                            ->orWhere('sku_code', 'LIKE', '%' . $product_name . '%');
                    }
                })
                ->LeftJoin('product_price_histories', function ($join) use ($storeId, $date, $can_proceed_last_date) {
                    if (Auth::user()->user_type == 1) {
                        if ($date != null && $can_proceed_last_date == 0) {
                            $join->on('product_price_histories.product_id', 'products.id')->whereNull('product_price_histories.deleted_at')
                                ->whereIn('product_price_histories.id', function ($query) use ($date) {
                                    $query->selectRaw('max(id) as id')->from('product_price_histories')->whereDate('product_price_histories.price_update_date', $date)->where('status', 1)->groupBy('product_id');
                                });
                        } else {
                            $join->on('product_price_histories.product_id', 'products.id')->whereNull('product_price_histories.deleted_at')
                                ->whereIn('product_price_histories.id', function ($query) {
                                    $query->selectRaw('max(id) as id')->from('product_price_histories')->where('status', 1)->groupBy('product_id');
                                });
                        }
                    } else {
                        if ($date != null) {
                            $join->on('product_price_histories.product_id', 'products.id')->whereNull('product_price_histories.deleted_at')
                                ->whereIn('product_price_histories.id', function ($query) use ($date) {
                                    $query->selectRaw('max(id) as id')->from('product_price_histories')->whereDate('product_price_histories.price_update_date', $date)->where('status', 1)->groupBy('product_id');
                                });
                        } else {
                            $join->on('product_price_histories.product_id', 'products.id')->whereNull('product_price_histories.deleted_at')
                                ->whereIn('product_price_histories.id', function ($query) {
                                    $query->selectRaw('max(id) as id')->from('product_price_histories')->where('status', 1)->groupBy('product_id');
                                });
                        }
                    }
                })
                ->select('products.id', 'name', 'sku_code', 'unit_id', DB::raw('(CASE WHEN price is NULL THEN price ELSE price END) as price'))

                ->with('product_category:id,product_id,category_id', 'product_category.category:id,name,parent_id,slug,is_featured')
                ->with('unit:id,unit_name,unit_short_code,allow_decimal,operator,operation_value')
                ->where('products.status', 1)
                ->orderBy('id', 'ASC')
                ->paginate(20);

        } else {
            // Fetch products that are mapped in FishCuttingProductMap
            $products = Product::where('products.status', 1)
            // ->whereExists(function ($query) {
            //     $query->select(DB::raw(1))
            //         ->from('fish_cutting_product_maps')
            //         ->whereColumn('products.id', 'fish_cutting_product_maps.main_product_id');
            // })
                ->where(function ($query) use ($product_name) {
                    if ($product_name != null) {
                        $query
                            ->where('name', 'LIKE', '%' . $product_name . '%')
                            ->orWhere('slug', 'LIKE', '%' . $product_name . '%')
                            ->orWhere('sku_code', 'LIKE', '%' . $product_name . '%');
                    }
                })
                ->LeftJoin('product_price_histories', function ($join) use ($storeId, $date, $can_proceed_last_date) {
                    if (Auth::user()->user_type == 1) {
                        if ($date != null && $can_proceed_last_date == 0) {
                            $join->on('product_price_histories.product_id', 'products.id')->whereNull('product_price_histories.deleted_at')
                                ->whereIn('product_price_histories.id', function ($query) use ($date) {
                                    $query->selectRaw('max(id) as id')->from('product_price_histories')->whereDate('product_price_histories.price_update_date', $date)->where('status', 1)->groupBy('product_id');
                                });
                        } else {
                            $join->on('product_price_histories.product_id', 'products.id')->whereNull('product_price_histories.deleted_at')
                                ->whereIn('product_price_histories.id', function ($query) {
                                    $query->selectRaw('max(id) as id')->from('product_price_histories')->where('status', 1)->groupBy('product_id');
                                });
                        }
                    } else {
                        if ($date != null) {
                            $join->on('product_price_histories.product_id', 'products.id')->whereNull('product_price_histories.deleted_at')
                                ->whereIn('product_price_histories.id', function ($query) use ($date) {
                                    $query->selectRaw('max(id) as id')->from('product_price_histories')->whereDate('product_price_histories.price_update_date', $date)->where('status', 1)->groupBy('product_id');
                                });
                        } else {
                            $join->on('product_price_histories.product_id', 'products.id')->whereNull('product_price_histories.deleted_at')
                                ->whereIn('product_price_histories.id', function ($query) {
                                    $query->selectRaw('max(id) as id')->from('product_price_histories')->where('status', 1)->groupBy('product_id');
                                });
                        }
                    }
                })
                ->select('products.id', 'name', 'sku_code', 'unit_id', DB::raw('(CASE WHEN price is NULL THEN price ELSE price END) as price'))

                ->with('product_category:id,product_id,category_id', 'product_category.category:id,name,parent_id,slug,is_featured')
                ->with('unit:id,unit_name,unit_short_code,allow_decimal,operator,operation_value')
                ->orderBy('id', 'ASC')
                ->paginate(20);
        }
        if ($products !== null) {
            return response()->json([
                'status' => 200,
                'datas' => $products,
                'message' => 'Product fetched successfully.',
            ]);
        } else {
            return response()->json([
                'status' => 400,
                'message' => 'There is no products pls create that.',
            ]);
        }

        // } catch (\Exception $e) {
        //     Log::error($e);
        //     return response()->json([
        //         'status' => 400,
        //         'message' => 'Data not found.',
        //     ]);
        // }
    }

    public function categorylist(Request $request)
    {
        try {
            $category_name = $request->category_name;
            $customers = Category::active()
                ->where(function ($query) use ($category_name) {
                    if ($category_name != null) {
                        $query->where('name', 'LIKE', '%' . $category_name . '%')->orWhere('slug', 'LIKE', '%' . $category_name . '%');
                    }
                })
                ->select('id', 'name', 'parent_id', 'slug', 'position', 'is_featured', 'status')
                ->orderBy('id', 'DESC')
                ->paginate(15);

            return response()->json([
                'status' => 200,
                'datas' => $customers,
                'message' => 'Category fetched successfully.',
            ]);
        } catch (\Exception $e) {
            Log::error($e);
            return response()->json([
                'status' => 400,
                'message' => 'Data not found.',
            ]);
        }
    }

    public function getinvoicecode(Request $request)
    {
        // try {
        $type = $request->type;
        $prefix = $request->prefix;
        $invoice_no = CommonComponent::invoice_no($type, $prefix);
        return response()->json([
            'status' => 200,
            'invoice_no' => $invoice_no,
            'message' => 'Invoice Number Fetched successfully.',
        ]);
        // } catch (\Exception $e) {
        //     Log::error($e);
        //     return response()->json([
        //         'status' => 400,
        //         'message' => 'Data not found.',
        //     ]);
        // }
    }

    public function dropdownlist(Request $request)
    {
        try {
            $data['unit_details'] = Unit::where('status', 1)->get();
            Log::info("data['unit_details']");
            Log::info($data['unit_details']);
            $data['expense_details'] = IncomeExpenseType::where('status', 1)->get();
            $data['transport_types'] = TransportType::where('status', 1)->get();

            if (Auth::user()->user_type == 1) {
                $data['payment_types'] = PaymentType::where('status', 1)
                    ->get();
            } elseif (Auth::user()->user_type == 2 || Auth::user()->user_type == 4) {
                $data['payment_types'] = PaymentType::orWhereIn('store_id', Auth::user()->user_stores())
                    ->where('status', 1)
                    ->get();
            }

            $data['purchase_status'] = config('app.purchase_status');
            $data['payment_status'] = config('app.payment_status');
            $data['attendance_type'] = config('app.attendance_type');
            return response()->json([
                'status' => 200,
                'data' => $data,
                'message' => 'Data Fetched successfully.',
            ]);
        } catch (\Exception $e) {
            Log::error($e);
            return response()->json([
                'status' => 400,
                'message' => 'Data not found.',
            ]);
        }
    }

    public function appmenu()
    {
        // try {
        Log::info("App menu mappings found");
        // Determine the authenticated user's ID and type
        $user = Auth::user();
        // $supplier = Auth::guard('supplier')->user();
        Log::info("admin login");
        Log::info($user);
        // Log::info($supplier);
        // Initialize variables for menu data
        $bottom_menu = null;
        $sidebar_menu = null;

        // Fetch admin menu if an Admin is logged in
        if ($user->user_type == 1) {
            Log::info("admin is logged in");
            $bottom_menu = UserAppMenuMapping::where('admin_id', $user->id)->where('admin_type', 1)
                ->where('status', 1)
                ->where('menu_type', 1)
                ->pluck('app_menu_json')
                ->first();

            $sidebar_menu = UserAppMenuMapping::where('admin_id', $user->id)->where('admin_type', 1)
                ->where('status', 1)
                ->where('menu_type', 2)
                ->pluck('app_menu_json')
                ->first();
        }
        // Fetch supplier menu if a Supplier is logged in
        elseif ($user->user_type) {
            Log::info("supplier is logged in");
            $bottom_menu = UserAppMenuMapping::where('admin_id', $user->id)->where('admin_type', 2)
                ->where('status', 1)
                ->where('menu_type', 1)
                ->pluck('app_menu_json')
                ->first();

            $sidebar_menu = UserAppMenuMapping::where('admin_id', $user->id)->where('admin_type', 2)
                ->where('status', 1)
                ->where('menu_type', 2)
                ->pluck('app_menu_json')
                ->first();
        }

        // Fetch default menu data if specific menu data is not found
        if (!$bottom_menu) {
            $bottom_data = UserAppMenuMapping::where('status', 1)
                ->where('menu_type', 1)
                ->first();
            $bottom_menu = $bottom_data ? $bottom_data->app_menu_json : null;
        }

        if (!$sidebar_menu) {
            $sidebar_data = UserAppMenuMapping::where('status', 1)
                ->where('menu_type', 2)
                ->first();
            $sidebar_menu = $sidebar_data ? $sidebar_data->app_menu_json : null;
        }

        return response()->json([
            'status' => 200,
            'bottom_menu' => $bottom_menu,
            'sidebar_menu' => $sidebar_menu,
            'message' => 'Menu Fetched successfully.',
        ]);
        // } catch (\Exception $e) {
        //     Log::error("Error fetching menu: " . $e->getMessage());
        //     return response()->json([
        //         'status' => 400,
        //         'message' => 'Failed to fetch menu data.',
        //     ]);
        // }
    }

    public function notification_list(Request $request)
    {
        if (Auth::guard('admin')->check()) {
            $user = Admin::where('id', Auth::guard('admin')->user()->id)->first();
            return $user ? AdminNotification::where('user_id', $user->id)->latest()->paginate(30) : null;
        } else if (Auth::guard('api')->check()) {
            $user = Admin::where('id', Auth::user()->id)->first();
            return $user ? AdminNotification::where('user_id', $user->id)->latest()->paginate(30) : null;
        } else if (Auth::guard('supplier')->check()) {
            $user = User::where('id', Auth::user()->id)->first();
            return $user ? UserNotification::where('user_id', $user->id)->latest()->paginate(30) : null;
        } else {
            return null;
        }
    }
}
