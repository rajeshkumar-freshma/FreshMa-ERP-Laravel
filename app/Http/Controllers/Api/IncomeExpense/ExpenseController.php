<?php

namespace App\Http\Controllers\Api\IncomeExpense;

use App\Core\CommonComponent;
use App\Http\Controllers\Controller;
use App\Models\IncomeExpenseDocument;
use App\Models\IncomeExpenseTransaction;
use App\Models\IncomeExpenseTransactionDetail;
use App\Models\PaymentTransaction;
use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderExpense;
use App\Models\Store;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ExpenseController extends Controller
{
    public function expenselist(Request $request)
    {
        $invoice_number = $request->invoice_number;
        $payment_status = $request->payment_status !== null ? [$request->payment_status] : [1, 2, 3];

        if ($request->payment_status == 4) { // Credit Sale
            $payment_status = [2, 3];
        }

        $from_date = $request->from_date;
        $to_date = $request->to_date;
        $store_id = $request->store_id !== null ? [$request->store_id] : Auth::user()->user_stores();

        $income_expense_query = IncomeExpenseTransaction::where(function ($query) use ($invoice_number, $store_id, $payment_status, $from_date, $to_date) {
            if ($invoice_number !== null) {
                $query->where('expense_invoice_number', 'LIKE', '%' . $invoice_number . '%')
                    ->orWhereHas('income_expense_types', function ($query) use ($invoice_number) {
                        $query->Where('name', 'LIKE', '%' . $invoice_number . '%');
                    });
            }
            if ($store_id !== null) {
                $query->whereIn('store_id', $store_id);
            }
            if ($payment_status != null) {
                $query->whereIn('payment_status', $payment_status);
            }
            if ($from_date !== null && $to_date !== null) {
                $dateformatwithtime = CommonComponent::dateformatwithtime($from_date, $to_date);
                $query->whereBetween('transaction_datetime', $dateformatwithtime);
            }
        })
            ->with(['warehouse:id,name,code', 'store:id,store_name,store_code,phone_number,gst_number', 'income_expense_details:id,ie_transaction_id,ie_type_id,amount', 'income_expense_types:id,name,type,status'])
            ->where('status', 1)
            ->orderBy('id', 'DESC');

        $income_expense_count = $income_expense_query->count();
        $total_amount = $income_expense_query->sum('total_amount');
        $income_expenses = $income_expense_query->paginate(15);

        return response()->json([
            'status' => 200,
            'income_expense_count' => $income_expense_count,
            'total_amount' => $total_amount,
            'datas' => $income_expenses,
            'message' => 'Income Expense fetched successfully.',
        ]);
    }

    public function expensedetails(Request $request)
    {
        $income_expense_transaction_id = $request->income_expense_transaction_id;

        $income_expense_transactions = IncomeExpenseTransaction::with('warehouse:id,name,code', 'store:id,store_name,store_code,phone_number,gst_number')->findOrFail($income_expense_transaction_id);

        $income_expense_transaction_details = IncomeExpenseTransactionDetail::where('ie_transaction_id', $income_expense_transaction_id)->with('income_expense_type:id,name,type')->get();

        $income_expense_docs = IncomeExpenseDocument::where([['type', 2], ['reference_id', $income_expense_transaction_id]])->get();

        $payment_transactions = PaymentTransaction::where([['transaction_type', 5], ['reference_id', $income_expense_transaction_id]])->with('payment_type_details', 'payment_transaction_documents')->get();

        $paid_amount = $payment_transactions->sum('amount');
        $total_amount = $income_expense_transactions->total_amount;
        $due_amount = ($total_amount - $paid_amount);
        $due_amount = $due_amount > 0 ? $due_amount : 0;

        return response()->json([
            'status' => 200,
            'datas' => $income_expense_transactions,
            'income_expense_transaction_details' => $income_expense_transaction_details,
            'income_expense_docs' => $income_expense_docs,
            'payment_transactions' => $payment_transactions,
            'paid_amount' => round($paid_amount, 2),
            'total_amount' => round($total_amount, 2),
            'due_amount' => round($due_amount, 2),
            'message' => 'Income Expense Details fetched successfully.',
        ]);
    }

    public function expensestore(Request $request)
    {
        DB::beginTransaction();
        // try {
        Log::info("expensestore");
        Log::info($request->all());
        $store_data = Store::findOrfail($request->store_id);
        if (isset($request->income_expense_details)) {
            $income_expense_details = json_decode($request->income_expense_details);
            if (count($income_expense_details) > 0) {
                foreach ($income_expense_details as $key => $income_expense_detail) {
                    $expense_number = CommonComponent::invoice_no('store_expense');
                    Log::info('Expense number');
                    Log::info($expense_number);
                    $income_expense = new IncomeExpenseTransaction();
                    $income_expense->expense_invoice_number = $expense_number;
                    $income_expense->warehouse_id = $store_data != null ? $store_data->warehouse_id : null;
                    $income_expense->store_id = $request->store_id;
                    $income_expense->income_expense_type_id = $income_expense_detail->income_expense_type_id;
                    $income_expense->transaction_datetime = $request->transaction_datetime;
                    $income_expense->related_to = $request->related_to;
                    $income_expense->reference_id = $request->reference_id;
                    $income_expense->sub_total = $request->sub_total;
                    $income_expense->adjustment_amount = $request->adjustment_amount;
                    $income_expense->total_amount = $request->total_amount;
                    $income_expense->status = 1;
                    $income_expense->is_notification_send_to_user = $request->is_notification_send_to_user;
                    $income_expense->remarks = $request->remarks;
                    $income_expense->payment_status = 2; // UnPaid because first create there is no payment module so
                    $income_expense->save();
                }
            }
        }
        if (isset($request->income_expense_details)) {
            $income_expense_details = json_decode($request->income_expense_details);
            if (count($income_expense_details) > 0) {
                foreach ($income_expense_details as $key => $income_expense_detail) {
                    $transaction_details = new IncomeExpenseTransactionDetail();
                    $transaction_details->ie_transaction_id = $income_expense->id;
                    $transaction_details->ie_type_id = $income_expense_detail->income_expense_type_id;
                    $transaction_details->others_name = @$income_expense_detail->others_name;
                    $transaction_details->employee_id = @$income_expense_detail->employee_id;
                    $transaction_details->amount = $income_expense_detail->amount;
                    $transaction_details->remarks = @$income_expense_detail->remarks;
                    $transaction_details->save();
                }
            }
        }

        // Expense Docs Store
        if (isset($request->expense_documents) && count($request->expense_documents) > 0 && $request->file('expense_documents')) {
            foreach ($request->file('expense_documents') as $key => $value) {
                if ($value) {
                    $imageData = CommonComponent::s3BucketFileUpload($value, 'store_expense_docs');
                    $imagePath = $imageData['filePath'];
                    // $imageUrl = $imageData['fileName'];
                    $imageUrl = $imageData['imageURL'];

                    $purchase_order_docs = new IncomeExpenseDocument();
                    $purchase_order_docs->type = 2; // Expense
                    $purchase_order_docs->reference_id = $income_expense->id;
                    $purchase_order_docs->file = @$imageUrl;
                    $purchase_order_docs->file_path = @$imagePath;
                    $purchase_order_docs->save();
                }
            }
        }

        if ($request->related_to == 2) {
            $purchase_order_id = $request->reference_id;
            $purchase_order = PurchaseOrder::findOrFail($purchase_order_id);
            $purchase_order->total_expense_amount = $request->total_amount;
            $purchase_order->total_expense_billable_amount = $request->total_amount;
            $purchase_order->save();
            if (isset($request->income_expense_details)) {
                $income_expense_details = json_decode($request->income_expense_details);
                if (count($income_expense_details) > 0) {
                    foreach ($income_expense_details as $key => $income_expense_detail) {
                        $purchase_expense = new PurchaseOrderExpense();
                        $purchase_expense->purchase_order_id = $purchase_order_id;
                        $purchase_expense->income_expense_id = $income_expense_detail->income_expense_type_id;
                        $purchase_expense->ie_amount = @$income_expense_detail->amount;
                        $purchase_expense->is_billable = @$request->is_billable;
                        $purchase_expense->save();
                    }
                }
            }
        }

        DB::commit();

        return response()->json([
            'status' => 200,
            'datas' => $income_expense,
            'message' => 'Data Stored successfully.',
        ]);
        // } catch (\Exception $e) {
        //     Log::error($e);
        //     DB::rollback();
        //     return response()->json([
        //         'status' => 400,
        //         'message' => 'There are some Technical Issue, Kindly contact Admin.',
        //     ]);
        // }
    }

    public function expenseupdate(Request $request)
    {
        DB::beginTransaction();
        // try {
        $income_expense_transaction_id = $request->income_expense_transaction_id;
        $store_data = Store::findOrfail($request->store_id);

        $income_expense = IncomeExpenseTransaction::findOrFail($income_expense_transaction_id);
        $income_expense->warehouse_id = $store_data != null ? $store_data->warehouse_id : null;
        $income_expense->store_id = $request->store_id;
        $income_expense->transaction_datetime = $request->transaction_datetime;
        $income_expense->related_to = $request->related_to;
        $income_expense->reference_id = $request->reference_id;
        $income_expense->sub_total = $request->sub_total;
        $income_expense->adjustment_amount = $request->adjustment_amount;
        $income_expense->total_amount = $request->total_amount;
        $income_expense->status = 1;
        $income_expense->is_notification_send_to_user = $request->is_notification_send_to_user;
        $income_expense->remarks = $request->remarks;
        $income_expense->payment_status = 2; // UnPaid because first create there is no payment module so
        $income_expense->save();

        if (isset($request->income_expense_deleted_ids) && count(json_decode($request->income_expense_deleted_ids)) > 0) {
            IncomeExpenseTransactionDetail::destroy(json_decode($request->income_expense_deleted_ids));
        }

        if (isset($request->income_expense_details)) {
            $income_expense_details = json_decode($request->income_expense_details);
            if (count($income_expense_details) > 0) {
                foreach ($income_expense_details as $key => $income_expense_detail) {
                    if (isset($income_expense_detail->id)) {
                        $transaction_details = IncomeExpenseTransactionDetail::findOrFail($income_expense_detail->id);
                    } else {
                        $transaction_details = new IncomeExpenseTransactionDetail();
                    }
                    $transaction_details->ie_transaction_id = $income_expense->id;
                    $transaction_details->ie_type_id = $income_expense_detail->income_expense_type_id;
                    $transaction_details->others_name = @$income_expense_detail->others_name;
                    $transaction_details->employee_id = @$income_expense_detail->employee_id;
                    $transaction_details->amount = $income_expense_detail->amount;
                    $transaction_details->remarks = @$income_expense_detail->remarks;
                    $transaction_details->save();
                }
            }
        }

        // Expense Docs Delete
        if (isset($request->deleted_expense_doc_ids) && count(json_decode($request->deleted_expense_doc_ids)) > 0) {
            $deleted_expense_doc_ids = json_decode($request->deleted_expense_doc_ids);
            foreach ($deleted_expense_doc_ids as $key => $value) {
                if ($value) {
                    $expense_docs = IncomeExpenseDocument::findOrFail($value);
                    CommonComponent::s3BucketFileDelete($expense_docs->file, $expense_docs->file_path);

                    $expense_docs->delete();
                }
            }
        }

        // Expense Docs Store
        if (isset($request->expense_documents) && count($request->expense_documents) > 0 && $request->file('expense_documents')) {
            foreach ($request->file('expense_documents') as $key => $value) {
                if ($value) {
                    $imageData = CommonComponent::s3BucketFileUpload($value, 'store_expense_docs');
                    $imagePath = $imageData['filePath'];
                    // $imageUrl = $imageData['fileName'];
                    $imageUrl = $imageData['imageURL'];

                    $purchase_order_docs = new IncomeExpenseDocument();
                    $purchase_order_docs->type = 2; // Expense
                    $purchase_order_docs->reference_id = $income_expense->id;
                    $purchase_order_docs->file = @$imageUrl;
                    $purchase_order_docs->file_path = @$imagePath;
                    $purchase_order_docs->save();
                }
            }
        }

        // Purchase Order Expense
        if ($request->related_to == 2) {
            $purchase_order_id = $request->reference_id;
            $purchase_order = PurchaseOrder::findOrFail($purchase_order_id);
            $purchase_order->total_expense_amount = $request->total_amount;
            $purchase_order->total_expense_billable_amount = $request->total_amount;
            $purchase_order->save();
            if (isset($request->income_expense_details)) {
                $income_expense_details = json_decode($request->income_expense_details);
                if (count($income_expense_details) > 0) {
                    foreach ($income_expense_details as $key => $income_expense_detail) {
                        $purchase_expense = new PurchaseOrderExpense();
                        $purchase_expense->purchase_order_id = $purchase_order_id;
                        $purchase_expense->income_expense_id = $income_expense_detail->income_expense_type_id;
                        $purchase_expense->ie_amount = @$income_expense_detail->amount;
                        $purchase_expense->is_billable = @$request->is_billable;
                        $purchase_expense->save();
                    }
                }
            }
        }

        DB::commit();

        return response()->json([
            'status' => 200,
            'datas' => $income_expense,
            'message' => 'Data Updated successfully.',
        ]);
        // } catch (\Exception $e) {
        //     Log::error($e);
        //     DB::rollback();
        //     return response()->json([
        //         'status' => 400,
        //         'message' => 'There are some Technical Issue, Kindly contact Admin.',
        //     ]);
        // }
    }

    public function expensepaymenttransactions(Request $request)
    {
        DB::beginTransaction();
        // try {
        $income_expense_transaction_id = $request->income_expense_transaction_id;

        if (isset($request->payment_details)) {
            $payment_details = json_decode($request->payment_details);
            foreach ($payment_details as $key => $payment_detail) {
                $payment_transaction = new PaymentTransaction();
                $payment_transaction->transaction_number = CommonComponent::invoice_no('payment_transaction'); // Auto Generate
                $payment_transaction->transaction_type = 5; // Expense
                $payment_transaction->type = 2; // Debit
                $payment_transaction->reference_id = $income_expense_transaction_id;
                $payment_transaction->payment_type_id = (int) $payment_detail->payment_type_id;
                $payment_transaction->amount = $payment_detail->amount;
                $payment_transaction->transaction_datetime = $payment_detail->transaction_datetime;
                $payment_transaction->status = 1;
                $payment_transaction->note = @$payment_detail->note;
                $payment_transaction->save();

                if (isset($request->payment_transaction_documents) && count($request->payment_transaction_documents) > 0 && $request->file('payment_transaction_documents')) {
                    CommonComponent::payment_transaction_documents($request->file('payment_transaction_documents'), 3, $payment_transaction->id); // 3 => Expense
                }
            }
        }

        $income_expense_details = IncomeExpenseTransaction::with('payment_transactions')->findOrFail($income_expense_transaction_id);

        $paid_amount = $income_expense_details->payment_transactions->sum('amount');

        $total_amount = $income_expense_details->total_amount;

        if ($paid_amount == 0) {
            $income_expense_details->payment_status = $request->payment_status != null ? $request->payment_status : 2; // UnPaid
            $income_expense_details->save();
        } else if ($paid_amount < $total_amount) {
            $income_expense_details->payment_status = $request->payment_status != null ? $request->payment_status : 3; // Pending
            $income_expense_details->save();
        } else if ($paid_amount >= $total_amount) {
            $income_expense_details->payment_status = $request->payment_status != null ? $request->payment_status : 1; // UnPaid
            $income_expense_details->save();
        }

        DB::commit();
        return response()->json([
            'status' => 200,
            'data' => $income_expense_details,
            'message' => 'Transaction Stored successfully.',
        ]);
        // } catch (\Exception $e) {
        //     Log::error($e);
        //     DB::rollback();
        //     return response()->json([
        //         'status' => 400,
        //         'message' => 'Data not found.',
        //     ]);
        // }
    }

    public function expensepaymentstatusupdate(Request $request)
    {
        DB::beginTransaction();
        // try {
        $income_expense_transaction_id = $request->income_expense_transaction_id;

        $income_expense_detail = IncomeExpenseTransaction::findOrFail($income_expense_transaction_id);

        PaymentTransaction::where([['reference_id', $income_expense_transaction_id], ['transaction_type', 5]])->delete();

        if ($request->payment_status != null && $income_expense_detail != null) {
            $income_expense_detail->payment_status = $request->payment_status;
            $income_expense_detail->save();
        }
        DB::commit();

        return response()->json([
            'status' => 200,
            'data' => $income_expense_detail,
            'message' => 'Payment Status Updated Successfully.',
        ]);
        // } catch (\Exception $e) {
        //     Log::error($e);
        //     DB::rollback();
        //     return response()->json([
        //         'status' => 400,
        //         'message' => 'Data not found.',
        //     ]);
        // }
    }

    public function expensepaymenttransactionedit(Request $request)
    {
        try {
            $transaction_id = $request->transaction_id;

            $payment_transaction = PaymentTransaction::with('payment_transaction_documents')->findOrFail($transaction_id);

            return response()->json([
                'status' => 200,
                'data' => $payment_transaction,
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

    public function expensepaymenttransactionupdate(Request $request)
    {
        DB::beginTransaction();
        // try {
        $transaction_id = $request->transaction_id;

        if (isset($request->payment_details)) {
            $payment_details = json_decode($request->payment_details);
            foreach ($payment_details as $key => $payment_detail) {
                $payment_transaction = PaymentTransaction::findOrFail($transaction_id);
                $payment_transaction->payment_type_id = (int) $payment_detail->payment_type_id;
                $payment_transaction->amount = $payment_detail->amount;
                $payment_transaction->transaction_datetime = $payment_detail->transaction_datetime;
                $payment_transaction->status = 1;
                $payment_transaction->note = @$payment_detail->note;
                $payment_transaction->save();

                if (isset($request->payment_transaction_documents) && count($request->payment_transaction_documents) > 0 && $request->file('payment_transaction_documents')) {
                    CommonComponent::payment_transaction_documents($request->file('payment_transaction_documents'), 3, $payment_transaction->id); // 3 => Expense
                }
            }
        }

        // $income_expense_detail = IncomeExpenseTransaction::findOrFail($payment_transaction->reference_id);
        // $income_expense_detail->total_amount = $income_expense_detail->total_amount + $total_amount;
        // $income_expense_detail->save();

        $income_expense_details = IncomeExpenseTransaction::with('payment_transactions')->findOrFail($payment_transaction->reference_id);

        $paid_amount = $income_expense_details->payment_transactions->sum('amount');

        $total_amount = $income_expense_details->total_amount;

        if ($paid_amount == 0) {
            $income_expense_details->payment_status = $request->payment_status != null ? $request->payment_status : 2; // UnPaid
            $income_expense_details->save();
        } else if ($paid_amount < $total_amount) {
            $income_expense_details->payment_status = $request->payment_status != null ? $request->payment_status : 3; // Pending
            $income_expense_details->save();
        } else if ($paid_amount >= $total_amount) {
            $income_expense_details->payment_status = $request->payment_status != null ? $request->payment_status : 1; // UnPaid
            $income_expense_details->save();
        }
        DB::commit();
        return response()->json([
            'status' => 200,
            'data' => $income_expense_details,
            'message' => 'Transaction Updated successfully.',
        ]);
        // } catch (\Exception $e) {
        //     Log::error($e);
        //     DB::rollback();
        //     return response()->json([
        //         'status' => 400,
        //         'message' => 'Data not found.',
        //     ]);
        // }
    }

    public function expensepaymenttransactiondelete(Request $request)
    {
        try {
            $transaction_id = $request->transaction_id;
            $income_expense_transaction_id = $request->income_expense_transaction_id;

            PaymentTransaction::destroy($transaction_id);

            $income_expense_detail = IncomeExpenseTransaction::findOrFail($income_expense_transaction_id);

            $income_expense_details = IncomeExpenseTransaction::with('payment_transactions')->findOrFail($income_expense_transaction_id);

            $paid_amount = $income_expense_details->payment_transactions->sum('amount');

            $total_amount = $income_expense_details->total_amount;

            if ($paid_amount == 0) {
                $income_expense_details->payment_status = $request->payment_status != null ? $request->payment_status : 2; // UnPaid
                $income_expense_details->save();
            } else if ($paid_amount < $total_amount) {
                $income_expense_details->payment_status = $request->payment_status != null ? $request->payment_status : 3; // Pending
                $income_expense_details->save();
            } else if ($paid_amount >= $total_amount) {
                $income_expense_details->payment_status = $request->payment_status != null ? $request->payment_status : 1; // UnPaid
                $income_expense_details->save();
            }

            return response()->json([
                'status' => 200,
                'datas' => $income_expense_detail,
                'message' => 'Payment Transaction Deleted Successfully.',
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

    public function searchExpense(Request $request)
    {
        try {
            if ($request->expense_invoice_number != null) {
                $this->validate($request, [
                    'expense_invoice_number' => 'required',
                ]);
                $expense = IncomeExpenseTransaction::where('expense_invoice_number', 'LIKE', '%' . $request->expense_invoice_number . '%')->select('income_expense_transactions.*')->get();
                Log::info("json_encode(expense)");
                Log::info(json_encode($expense));
                return response()->json(['is_success' => true, 'message' => 'Expense Fetched Successfully', 'Expenses' => $expense]);
            }
            return response()->json(['is_success' => false, 'message' => 'Expense Invoice Number is Must']);
        } catch (\Throwable $e) {
            return response()->json([
                'status' => 400,
                'message' => $e->getMessage(),
            ]);
        }
    }
}
