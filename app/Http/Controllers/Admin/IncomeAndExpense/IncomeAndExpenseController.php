<?php

namespace App\Http\Controllers\Admin\IncomeAndExpense;

use App\Core\CommonComponent;
use App\DataTables\IncomeAndExpense\IncomeAndExpenseDataTable;
use App\Http\Controllers\Controller;
use App\Http\Requests\IncomeAndExpense\IncomeAndExpenseFormRequest;
use App\Models\IncomeExpenseDocument;
use App\Models\IncomeExpenseTransaction;
use App\Models\IncomeExpenseTransactionDetail;
use App\Models\IncomeExpenseType;
use App\Models\PaymentTransaction;
use App\Models\PaymentTransactionDocument;
use App\Models\PaymentType;
use App\Models\Store;
use App\Models\Warehouse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class IncomeAndExpenseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(IncomeAndExpenseDataTable $dataTable)
    {
        return $dataTable->render('pages.income_and_expense.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $incomeExpenseTypeId = $request->incomeExpenseTypeId;
        if ($incomeExpenseTypeId !== null) {
            $data['expense_types'] = IncomeExpenseType::active()
                ->where('type', $incomeExpenseTypeId)
                ->get();
            return response()->json([
                'status' => 200,
                'data' => $data['expense_types'],
            ]);
        } else {
            $data['expense_types'] = '';
        }
        $data['stores'] = Store::all();
        $data['warehouse'] = Warehouse::all();
        $data['payment_types'] = PaymentType::where('status', 1)->get();
        $data['expense_types'] = IncomeExpenseType::active()->get();
        return view('pages.income_and_expense.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(IncomeAndExpenseFormRequest $request)
    {
        DB::beginTransaction();
        // try {
        // return $request->all();

        $today = Carbon::now();
        $income_expense = new IncomeExpenseTransaction();
        $income_expense->expense_invoice_number = (string) $request->income_expense_invoice_number;

        // $income_expense->warehouse_id = $request->warehouse_id ?? null;
        // $income_expense->store_id = $request->store_id ?? null;
        $income_expense->warehouse_id = $request->related_to == 1 ? null : $request->warehouse_id;
        $income_expense->store_id = $request->related_to == 1 ? $request->store_id : null;
        $income_expense->income_expense_type_id = $request->income_expense_type_id ?? '';
        $income_expense->transaction_datetime = $request->date . ' ' . date('H:i:s');
        $income_expense->related_to = $request->related_to;
        $income_expense->sub_total = $request->sub_total ?? 0;
        $income_expense->reference_id = $request->reference_id ?? '';
        $income_expense->adjustment_amount = $request->adjustment_amount ?? 0;
        $income_expense->total_amount = $request->total_expense_amount_display_val;
        $income_expense->status = 1;
        $income_expense->is_notification_send_to_user = $request->is_notification_send_to_user ?? "";
        $income_expense->remarks = $request->remarks ?? '';
        $income_expense->payment_status = 3; // Unpaid

        $income_expense->save();
        // Expense Income Details Stored
        $income_expense_items = $request->expense;

        if (isset($income_expense_items) && is_array($income_expense_items) && count($income_expense_items['expense_type_id']) > 0) {
            foreach ($income_expense_items['expense_type_id'] as $key => $income_expense_detail) {
                // Check if expense_type_id is not null before saving
                if (!is_null($income_expense_items['expense_type_id'][$key])) {
                    $transaction_details = new IncomeExpenseTransactionDetail();
                    $transaction_details->ie_transaction_id = $income_expense->id;
                    $transaction_details->ie_type_id = $income_expense_items['expense_type_id'][$key];
                    $transaction_details->others_name = 'No name';
                    $transaction_details->employee_id = "";
                    $transaction_details->amount = $income_expense_items['expense_amount'][$key];
                    $transaction_details->remarks = @$income_expense_items['remarks'][$key];
                    $transaction_details->save();
                } else {
                    // Handle the case where expense_type_id is null (optional)
                    // You can log an error message or perform other actions as needed
                    // Log::error('Expense type ID is null for key ' . $key);
                }
            }
        }

        // Expense Docs Store
        if ($request->hasFile('file')) {
            // foreach ($request->file('file') as $key => $value) {
            $imageData = CommonComponent::s3BucketFileUpload($request->file, 'income_expense_doc');
            $imagePath = $imageData['filePath'];
            // $imageUrl = $imageData['fileName'];
            $imageUrl = $imageData['imageURL'];

            $income_expense_document = new IncomeExpenseDocument();
            $income_expense_document->type = $income_expense->income_expense_type_id; // Expense
            $income_expense_document->reference_id = $income_expense->id;
            if ($imageUrl !== null) {
                $income_expense_document->file = $imageUrl;
                $income_expense_document->file_path = $imagePath;
            }
            $income_expense_document->save();
            // }
        }
        // Payment Transaction Details store
        $payment_details = $request->payment_details;
        if (count($payment_details) > 0 && $payment_details['payment_type_id'][0] != null) {
            foreach ($payment_details['payment_type_id'] as $payment_key => $payment) {
                $payment_transaction = new PaymentTransaction();
                $payment_transaction->transaction_number = $income_expense->expense_invoice_number; // Auto Generate
                $payment_transaction->transaction_type = $income_expense->income_expense_type_id == 1 ? 8 : 5; //  8 income ,5 expense
                $payment_transaction->type = $income_expense->income_expense_type_id == 1 ? 1 : 2;
                $payment_transaction->reference_id = $income_expense->id;
                $payment_transaction->payment_type_id = @$payment_details['payment_type_id'][$payment_key];
                $payment_transaction->amount = @$payment_details['transaction_amount'][$payment_key];
                $payment_transaction->transaction_datetime = @$payment_details['transaction_datetime'][$payment_key];
                $payment_transaction->note = @$payment_details['remark'][$payment_key];
                $payment_transaction->status = 1;
                $payment_transaction->save();

                if (isset($payment_details['payment_transaction_documents'][$payment_key]) && count($payment_details['payment_transaction_documents']) > 0) {
                    CommonComponent::payment_transaction_documents(($payment_details['payment_transaction_documents'][$payment_key]), 1, $payment_transaction->id);
                }
            }

            $income_expense_details = IncomeExpenseTransaction::with('incomeExpensePaymentTransaction')->find($income_expense->id);

            $paid_amount = $income_expense_details->incomeExpensePaymentTransaction->sum('amount');

            $total_amount = $income_expense_details->total_amount;
            Log::info("paid_amount");
            Log::info($paid_amount);
            Log::info("total_amount");
            Log::info($total_amount);

            if ($paid_amount == 0) {
                $income_expense_details->payment_status = 2; // UnPaid
                $income_expense_details->save();
            } else if ($paid_amount < $total_amount) {
                $income_expense_details->payment_status = 3; // Pending // Partially paid
                $income_expense_details->save();
            } else if ($paid_amount >= $total_amount) {
                $income_expense_details->payment_status = 1; // Paid
                $income_expense_details->save();
            }

        }

        DB::commit();

        if ($request->submission_type == 1) {
            return redirect()->route('admin.income-and-expense.index')->with('success', 'Income And Expense Stored Successfully');
        } elseif ($request->submission_type == 2) {
            return back()->with('success', 'Income And Expense Stored Successfully');
        }
        // } catch (\Exception $e) {
        //     // Log the error
        //     Log::error($e);

        //     // Redirect back with input and error message
        //     return back()->withInput()->with('error', 'Income And Expense Creation Failed');
        // }
    }

    /**
     * Display the specified resource.
     */
    public function show(IncomeAndExpenseDataTable $dataTable, $id)
    {
        try {
            $data['incomeExpenseData'] = IncomeExpenseTransaction::with('incomeExpensePaymentTransaction.payment_type_details')->findOrFail($id);
            $data['dataTable'] = $dataTable;
            return view('pages.income_and_expense.show', $data);
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
     */
    public function edit($id)
    {

        $data['income_and_expense_data'] = IncomeExpenseTransaction::with('income_expense_details', 'expense_documents', 'incomeExpensePaymentTransaction')->find($id);
        $data['payment_types'] = PaymentType::all();
        $data['expense_types'] = IncomeExpenseType::all();
        // // Extract income and expense type ID
        // $incomeExpenseTypeId = $data['income_and_expense_data']->income_expense_type_id;

        // // Retrieve expense types based on income and expense type ID
        // $data['expense_types'] = [];

        // if ($incomeExpenseTypeId !== null) {
        //     $typeCondition = ($incomeExpenseTypeId == 1) ? 1 : 2;

        //     $data['expense_types'] = IncomeExpenseType::active()
        //         ->where('type', $typeCondition)
        //         ->get();
        // }
        $data['stores'] = Store::all();
        $data['warehouse'] = Warehouse::all();
        return view('pages.income_and_expense.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(IncomeAndExpenseFormRequest $request, $id)
    {
        DB::beginTransaction();
        // try {
        // Validate the request if needed
        // $this->validate($request, [...]);
        // return $request->all();

        // Find the income expense record or create a new one if it doesn't exist
        $incomeExpense = IncomeExpenseTransaction::updateOrCreate(
            ['id' => $id],
            $this->prepareIncomeExpenseData($request)
        );

        $paymentDetails = $request->payment_details;

        // Collect existing payment IDs from the request
        $request_old_payment_ids = [];
        if (isset($paymentDetails['payment_type_id']) && count($paymentDetails['payment_type_id']) > 0) {
            foreach ($paymentDetails['payment_type_id'] as $pay_key => $value) {
                if (isset($paymentDetails['payment_id'][$pay_key]) && $paymentDetails['payment_id'][$pay_key] != null) {
                    $request_old_payment_ids[] = $paymentDetails['payment_id'][$pay_key];
                }
            }
        }

        // Retrieve existing payment transactions for the current incomeExpense
        $poe_details = PaymentTransaction::where('reference_id', $incomeExpense->id)->get();

        // Delete payment transactions that are no longer in the request
        if ($poe_details->count() > 0) {
            foreach ($poe_details as $value) {
                if (!in_array($value->id, $request_old_payment_ids)) {
                    PaymentTransactionDocument::where('reference_id', $value->id)->delete();
                    PaymentTransaction::where('id', $value->id)->delete();
                }
            }
        }

        // Process payment details from the request
        if (isset($paymentDetails) && count($paymentDetails) > 0) {
            foreach ($paymentDetails['payment_type_id'] as $payment_key => $payment) {
                if ($payment != null) {
                    $payment_id = $paymentDetails['payment_id'][$payment_key] ?? null;

                    // Check if it's an existing payment or a new one
                    if ($payment_id && in_array($payment_id, $poe_details->pluck('id')->toArray())) {
                        $payment_transaction = PaymentTransaction::findOrFail($payment_id);
                        // Update existing payment transaction
                        Log::info("Old Updated");
                    } else {
                        // Create new payment transaction
                        $payment_transaction = new PaymentTransaction();
                        $payment_transaction->transaction_number = CommonComponent::invoice_no('payment_transaction'); // Auto Generate
                        $payment_transaction->transaction_type = $incomeExpense->income_expense_type_id == 1 ? 8 : 5; //  8 income ,5 expense
                        $payment_transaction->type = $incomeExpense->income_expense_type_id == 1 ? 1 : 2; //2 debit 1 credit
                        $payment_transaction->reference_id = $incomeExpense->id;
                        Log::info("Newly Created");
                    }

                    // Update payment transaction details
                    $payment_transaction->payment_type_id = $payment;
                    $payment_transaction->amount = $paymentDetails['transaction_amount'][$payment_key] ?? 0;
                    $payment_transaction->transaction_datetime = $paymentDetails['transaction_datetime'][$payment_key] ?? null;
                    $payment_transaction->note = $paymentDetails['remark'][$payment_key] ?? null;
                    $payment_transaction->status = 1; // Assuming '1' means active or completed
                    $payment_transaction->save();

                    Log::info("Payment Transaction Details");
                    Log::info($payment_transaction);

                    // Handle payment transaction documents
                    if (isset($paymentDetails['payment_transaction_documents'][$payment_key]) && count($paymentDetails['payment_transaction_documents']) > 0) {
                        CommonComponent::payment_transaction_documents($paymentDetails['payment_transaction_documents'][$payment_key], 1, $payment_transaction->id); // 1=> Purchase Document
                    }
                }
                $income_expense_details = IncomeExpenseTransaction::with('incomeExpensePaymentTransaction')->find($incomeExpense->id);

                $paid_amount = $income_expense_details->incomeExpensePaymentTransaction->sum('amount');

                $total_amount = $income_expense_details->total_amount;
                Log::info("paid_amount");
                Log::info($paid_amount);
                Log::info("total_amount");
                Log::info($total_amount);

                if ($paid_amount == 0) {
                    $income_expense_details->payment_status = 2; // UnPaid
                    $income_expense_details->save();
                } else if ($paid_amount < $total_amount) {
                    $income_expense_details->payment_status = 3; // Pending // Partially paid
                    $income_expense_details->save();
                } else if ($paid_amount >= $total_amount) {
                    $income_expense_details->payment_status = 1; // Paid
                    $income_expense_details->save();
                }
            }
        }

        // Update or create income expense details
        $this->updateOrCreateIncomeExpenseDetails($incomeExpense, $request->expense);

        // Update or create income expense document
        $this->updateOrCreateIncomeExpenseDocument($incomeExpense, $request->file('file'));

        // Update or create payment transactions
        // $this->updateOrCreatePaymentTransactions($incomeExpense, $paymentDetails,$id);

        // Update payment status
        // $this->updatePaymentStatus($incomeExpense);

        // Commit the transaction
        DB::commit();

        // Handle redirection based on submission_type
        if ($request->submission_type == 1) {
            return redirect()->route('admin.income-and-expense.index')->with('success', 'Income And Expense Update Successfully');
        } elseif ($request->submission_type == 2) {
            return back()->with('success', 'Income And Expense Update Successfully');
        }
        // } catch (\Exception $e) {
        //     // Log the error
        //     Log::error($e);

        //     // Rollback the transaction
        //     DB::rollback();

        //     // Redirect back with input and error message
        //     return back()->withInput()->with('error', 'Income And Expense Update Failed');
        // }
    }

    // Helper method to prepare income expense data
    private function prepareIncomeExpenseData(Request $request)
    {
        return [
            'expense_invoice_number' => (string) $request->income_expense_invoice_number,
            'warehouse_id' => $request->related_to == 1 ? null : $request->warehouse_id,
            'store_id' => $request->related_to == 1 ? $request->store_id : null,
            'income_expense_type_id' => $request->income_expense_type_id ?? '',
            'transaction_datetime' => $request->date . ' ' . date('H:i:s'),
            'related_to' => $request->related_to,
            'reference_id' => $request->reference_id ?? '',
            'sub_total' => $request->sub_total ?? 0,
            'adjustment_amount' => $request->adjustment_amount ?? 0,
            'total_amount' => $request->total_expense_amount_display_val,
            'status' => 1,
            'is_notification_send_to_user' => $request->is_notification_send_to_user ?? "",
            'remarks' => $request->remarks ?? '',
            'payment_status' => 3, // Pending
        ];
    }

    private function updatePaymentStatus(IncomeExpenseTransaction $incomeExpense)
    {
        $paidAmount = $incomeExpense->incomeExpensePaymentTransaction->sum('amount');
        $totalAmount = $incomeExpense->total_amount;
        Log::info("Update payment status");
        Log::info($paidAmount);
        Log::info($totalAmount);
        if ($paidAmount == 0) {
            $incomeExpense->payment_status = 2; // Unpaid
        } elseif ($paidAmount < $totalAmount) {
            $incomeExpense->payment_status = 3; // Pending/Partially Paid
        } elseif ($paidAmount >= $totalAmount) {
            $incomeExpense->payment_status = 1; // Paid
        }

        $incomeExpense->save();
    }

    // Helper method to update or create income expense details
    private function updateOrCreateIncomeExpenseDetails(IncomeExpenseTransaction $incomeExpense, $expenseData)
    {

        if (isset($expenseData) && is_array($expenseData) && count($expenseData['expense_id']) > 0) {
            foreach ($expenseData['expense_id'] as $key => $expenseId) {
                // Check if ie_type_id is provided and not null
                if (isset($expenseData['expense_type_id'][$key]) && $expenseData['expense_type_id'][$key] !== null) {
                    $incomeExpenseDetailData = [
                        'others_name' => @$expenseData['others_name'][$key] ?? 'No name',
                        'employee_id' => @$expenseData['employee_id'][$key] ?? "",
                        'amount' => $expenseData['expense_amount'][$key],
                        'remarks' => @$expenseData['remarks'][$key],
                    ];

                    // Find the income expense detail or create a new one if it doesn't exist
                    $incomeExpenseDetail = IncomeExpenseTransactionDetail::updateOrCreate(
                        [
                            'id' => $expenseId,
                            'ie_transaction_id' => $incomeExpense->id,
                            'ie_type_id' => $expenseData['expense_type_id'][$key],
                        ],
                        $incomeExpenseDetailData
                    );
                } else {
                    // Handle the case where ie_type_id is null
                    // You might log an error, throw an exception, or handle it in some other way
                    // For example:
                    // throw new Exception('ie_type_id is null');
                    // or
                    // Log::error('ie_type_id is null for expense ID: ' . $expenseId);
                }
            }
        }
    }

    // Helper method to update or create income expense document
    private function updateOrCreateIncomeExpenseDocument(IncomeExpenseTransaction $incomeExpense, $file)
    {
        if ($file) {
            $imageData = CommonComponent::s3BucketFileUpload($file, 'income_expense_doc');
            $imagePath = $imageData['filePath'];
            $imageUrl = $imageData['imageURL'];

            // Find the income expense document or create a new one if it doesn't exist
            $incomeExpenseDocument = IncomeExpenseDocument::updateOrCreate(
                ['refernce_id' => $incomeExpense->id, 'type' => $incomeExpense->income_expense_type_id],
                ['file' => $imageUrl ?? '', 'file_path' => $imagePath ?? '']
            );
        }
    }

    // Helper method to update or create payment transactions
    private function updateOrCreatePaymentTransactions(IncomeExpenseTransaction $incomeExpense, $paymentDetails, $id)
    {
        $request_old_payment_ids = [];
        if (isset($paymentDetails['payment_type_id']) && count($paymentDetails['payment_type_id']) > 0) {
            foreach ($paymentDetails['payment_type_id'] as $pay_key => $value) {
                if (isset($paymentDetails['payment_id'][$pay_key]) && $paymentDetails['payment_id'][$pay_key] != null) {
                    $request_old_payment_ids[] = $paymentDetails['payment_id'][$pay_key];
                }
            }
        }
        $poe_details = PaymentTransaction::where('reference_id', $incomeExpense->id)->get();
        if (count($poe_details) > 0) {
            foreach ($poe_details as $exists_key => $value) {
                if (!in_array($value->id, $request_old_payment_ids)) {
                    PaymentTransactionDocument::where('reference_id', $value->id)->delete();
                    PaymentTransaction::where('id', $value->id)->delete();
                }
            }
        }
        // Payment Transaction Details store
        if (count($paymentDetails) > 0 && $paymentDetails['payment_type_id'] != null) {
            foreach ($paymentDetails['payment_type_id'] as $payment_key => $payment) {
                if ($paymentDetails['payment_type_id'][$payment_key] != null) {
                    if (isset($paymentDetails['payment_id'][$payment_key]) && $paymentDetails['payment_id'][$payment_key] != null) {
                        if (in_array($paymentDetails['payment_id'][$payment_key], $poe_details->pluck('id')->toArray())) {
                            $payment_transaction = PaymentTransaction::findOrFail($paymentDetails['payment_id'][$payment_key]);
                        }
                    } else {
                        $payment_transaction = new PaymentTransaction();
                        $payment_transaction->transaction_number = CommonComponent::invoice_no('payment_transaction'); // Auto Generate
                        $payment_transaction->transaction_type = 1; // Purchase Order
                        $payment_transaction->type = 2; // Debit
                        $payment_transaction->reference_id = $incomeExpense->id;
                    }
                    $payment_transaction->payment_type_id = @$paymentDetails['payment_type_id'][$payment_key];
                    $payment_transaction->amount = @$paymentDetails['transaction_amount'][$payment_key];
                    $payment_transaction->transaction_datetime = @$paymentDetails['transaction_datetime'][$payment_key];
                    $payment_transaction->note = @$paymentDetails['remark'][$payment_key];
                    $payment_transaction->status = 1;
                    $payment_transaction->save();
                }

                if (isset($paymentDetails['payment_transaction_documents'][$payment_key]) && count($paymentDetails['payment_transaction_documents']) > 0) {
                    CommonComponent::payment_transaction_documents(($paymentDetails['payment_transaction_documents'][$payment_key]), 1, $payment_transaction->id); // 1=> Purchase Document
                }
            }
            $income_expense_details = IncomeExpenseTransaction::with([
                'incomeExpensePaymentTransaction' => function ($query) {
                    $query->where('transaction_type', '=', '6');
                },
            ])->find($id);

            $paid_amount = $income_expense_details->incomeExpensePaymentTransaction->sum('amount');

            $total_amount = $income_expense_details->total_amount;

            if ($paid_amount == 0) {
                $income_expense_details->payment_status = 3; // UnPaid
                $income_expense_details->save();
            } else if ($paid_amount < $total_amount) {
                $income_expense_details->payment_status = 2; // Pending/Partially Paid
                $income_expense_details->save();
            } else if ($paid_amount >= $total_amount) {
                $income_expense_details->payment_status = 1; // Paid
                $income_expense_details->save();
            }
        }
    }

    // public function update(Request $request, string $id)
    // {
    //     DB::beginTransaction();
    //     // try {
    //     //    return $request->all();
    //     $store_data = Store::findOrfail($request->store_id);

    //     // $income_expense = new IncomeExpenseTransaction();
    //     $income_expense = IncomeExpenseTransaction::updateOrCreate(
    //         [
    //             'id' => $id,
    //         ],
    //         [
    //             'expense_invoice_number' => $request->income_expense_invoice_number,
    //             'warehouse_id' => $store_data != null ? $store_data->warehouse_id : null,
    //             'store_id' => $request->store_id,
    //             'income_expense_type_id' => $request->income_expense_type_id,
    //             'transaction_datetime' => $request->transaction_datetime . ' ' . date('H:i:s'),
    //             'related_to' => $request->related_to,
    //             'reference_id' => $request->reference_id ?? '',
    //             'sub_total' => $request->sub_total ?? '',
    //             'adjustment_amount' => $request->adjustment_amount ?? '',
    //             'total_amount' => $request->total_expense_amount_display_val,
    //             'status' => 1,
    //             'is_notification_send_to_user' => $request->is_notification_send_to_user ?? "",
    //             'remarks' => $request->remarks ?? "",
    //         ]
    //     );

    //     $income_expense_items = $request->expense;

    //     if (isset($income_expense_items) && count($income_expense_items['expense_id']) > 0) {
    //         foreach ($income_expense_items['expense_id'] as $key => $expense_id) {
    //             $transaction_details = IncomeExpenseTransactionDetail::updateOrCreate(
    //                 [
    //                     'id' => $expense_id,
    //                     'ie_transaction_id' => $income_expense->id,
    //                     'ie_type_id' => $income_expense_items['expense_type_id'][$key],
    //                 ],
    //                 [
    //                     'others_name' => @$income_expense_items['others_name'][$key] ?? '',
    //                     'employee_id' => @$income_expense_items['employee_id'][$key] ?? "",
    //                     'amount' => $income_expense_items['expense_amount'][$key],
    //                     'remarks' => @$income_expense_items['remarks'][$key],
    //                 ]
    //             );
    //         }
    //     }
    //     if ($request->hasFile('file')) {
    //         $imageData = CommonComponent::s3BucketFileUpload($request->file('file'), 'income_expense_doc');
    //         $imagePath = $imageData['filePath'];
    //         $imageUrl = $imageData['imageURL'];

    //         $income_expense_document = IncomeExpenseDocument::updateOrCreate(
    //             [
    //                 'refernce_id' => $income_expense->id,
    //                 'type' => $income_expense->income_expense_type_id,
    //             ],
    //             [
    //                 'file' => $imageUrl ?? '',
    //                 'file_path' => $imagePath ?? '',
    //             ]
    //         );
    //     }
    //     // Payment Details store
    //     // return 1;
    //     $request_old_payment_ids = [];
    //     if (isset($request->payment_details['payment_type_id']) && count($request->payment_details['payment_type_id']) > 0) {
    //         foreach ($request->payment_details['payment_type_id'] as $pay_key => $value) {
    //             if (isset($request->payment_details['payment_id'][$pay_key]) && $request->payment_details['payment_id'][$pay_key] != null) {
    //                 $request_old_payment_ids[] = $request->payment_details['payment_id'][$pay_key];
    //             }
    //         }
    //     }
    //     $poe_details = PaymentTransaction::where('reference_id', $income_expense->id)->get();
    //     if (count($poe_details) > 0) {
    //         foreach ($poe_details as $exists_key => $value) {
    //             if (!in_array($value->id, $request_old_payment_ids)) {
    //                 PaymentTransactionDocument::where('reference_id', $value->id)->delete();
    //                 PaymentTransaction::where('id', $value->id)->delete();
    //             }
    //         }
    //     }
    //     // Payment Transaction Details store
    //     $payment_details = $request->payment_details;
    //     if (count($payment_details) > 0 && $payment_details['payment_type_id'] != null) {
    //         foreach ($payment_details['payment_type_id'] as $payment_key => $payment) {
    //             if ($payment_details['payment_type_id'][$payment_key] != null) {
    //                 if (isset($payment_details['payment_id'][$payment_key]) && $payment_details['payment_id'][$payment_key] != null) {
    //                     if (in_array($payment_details['payment_id'][$payment_key], $poe_details->pluck('id')->toArray())) {
    //                         $payment_transaction = PaymentTransaction::findOrFail($payment_details['payment_id'][$payment_key]);
    //                     }
    //                 } else {
    //                     $payment_transaction = new PaymentTransaction();
    //                     $payment_transaction->transaction_number = CommonComponent::invoice_no('payment_transaction'); // Auto Generate
    //                     $payment_transaction->transaction_type = 1; // Purchase Order
    //                     $payment_transaction->type = 2; // Debit
    //                     $payment_transaction->reference_id = $income_expense->id;
    //                 }
    //                 $payment_transaction->payment_type_id = @$payment_details['payment_type_id'][$payment_key];
    //                 $payment_transaction->amount = @$payment_details['transaction_amount'][$payment_key];
    //                 $payment_transaction->transaction_datetime = @$payment_details['transaction_datetime'][$payment_key];
    //                 $payment_transaction->note = @$payment_details['remark'][$payment_key];
    //                 $payment_transaction->status = 1;
    //                 $payment_transaction->save();
    //             }

    //             if (isset($payment_details['payment_transaction_documents'][$payment_key]) && count($payment_details['payment_transaction_documents']) > 0) {
    //                 CommonComponent::payment_transaction_documents(($payment_details['payment_transaction_documents'][$payment_key]), 1, $payment_transaction->id); // 1=> Purchase Document
    //             }
    //         }
    //         $income_expense_details = IncomeExpenseTransaction::with([
    //             'incomeExpensePaymentTransaction' => function ($query) {
    //                 $query->where('transaction_type', '=', '6');
    //             }
    //         ])->find($id);

    //         $paid_amount = $income_expense_details->incomeExpensePaymentTransaction->sum('amount');

    //         $total_amount = $income_expense_details->total_amount;

    //         if ($paid_amount == 0) {
    //             $income_expense_details->payment_status =  3; // UnPaid
    //             $income_expense_details->save();
    //         } else if ($paid_amount < $total_amount) {
    //             $income_expense_details->payment_status =  2; // Pending/Partially Paid
    //             $income_expense_details->save();
    //         } else if ($paid_amount >= $total_amount) {
    //             $income_expense_details->payment_status =  1; // Paid
    //             $income_expense_details->save();
    //         }
    //     }

    //     DB::commit();
    //     //     if ($request->submission_type == 1) {
    //     //         return redirect()->route('admin.income-and-expense.index')->with('success', 'Income And Expense Update Successfully');
    //     //     } elseif ($request->submission_type == 2) {
    //     //         return back()->with('success', 'Income And Expense Update Successfully');
    //     //     }
    //     // } catch (\Exception $e) {
    //     //     // Log the error
    //     //     Log::error($e);

    //     //     // Redirect back with input and error message
    //     //     return back()->withInput()->with('error', 'Income And Expense Update Failed');
    //     // }
    // }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function incomeAndExpenseType(Request $request)
    {
        try {
            $incomeExpenseTypeId = $request->incomeExpenseTypeId;

            if ($incomeExpenseTypeId == 1) {
                // Income category Types
                $data['expense_types'] = IncomeExpenseType::where('type', 1)->where('status', 1)->get();
            } else {
                // Expense category Types
                $data['expense_types'] = IncomeExpenseType::where('type', 2)->where('status', 1)->get();
            }

            return response()->json([
                'status' => 200,
                'message' => 'Category Data Fetched Successfully',
                'data' => $data['expense_types'],
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 500, // Internal Server Error
                'message' => 'An error occurred while fetching category data.',
                'error' => $e->getMessage(),
            ]);
        }
    }
}
