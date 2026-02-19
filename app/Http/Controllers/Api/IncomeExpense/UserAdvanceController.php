<?php

namespace App\Http\Controllers\Api\IncomeExpense;

use App\Core\CommonComponent;
use App\Http\Controllers\Controller;
use App\Models\PaymentTransaction;
use App\Models\PurchaseSalesDocument;
use App\Models\UserAdvance;
use App\Models\UserAdvanceHistory;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class UserAdvanceController extends Controller
{
    public function useradvancelist(Request $request)
    {
        DB::beginTransaction();
        try {
            $user_id = $request->user_id;
            $transaction_type = $request->transaction_type;
            $type = $request->type;
            $from_date = $request->from_date;
            $to_date = $request->to_date;
            $user_advance_history = UserAdvanceHistory::where(function ($query) use ($user_id, $transaction_type, $type, $from_date, $to_date) {
                if ($user_id != null) {
                    $query->where('user_id', $user_id);
                }
                if ($transaction_type != null) {
                    $query->where('transaction_type', $transaction_type);
                }
                if ($type != null) {
                    $query->where('type', $type);
                }
                if ($from_date != null && $to_date != null) {
                    $date_format = CommonComponent::dateformatwithtime($from_date, $to_date);
                    $query->whereBetween('created_at', $date_format);
                }
            })
                ->with('advance_transactions')
                ->get();

            return response()->json([
                'status' => 200,
                'user_advance_history' => $user_advance_history,
                'message' => 'Advance History fetched successfully.',
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

    public function useradvancestore(Request $request)
    {
        DB::beginTransaction();
        // try {
        $user_advance = UserAdvance::where([['user_id', $request->user_id]])->first();
        if ($user_advance == null) {
            $user_advance = new UserAdvance();
        }
        $user_advance->user_id = $request->user_id;
        $user_advance->type = 1; // Credit
        $user_advance->amount = $request->amount;
        $user_advance->total_amount = @$user_advance->total_amount + $request->amount;
        $user_advance->save();

        $advancehistory = new UserAdvanceHistory();
        $advancehistory->user_id = $request->user_id;;
        $advancehistory->transaction_type = 1; // Purchase
        $advancehistory->reference_id = NULL;
        $advancehistory->type = 1; // Credit
        $advancehistory->amount = $request->amount;
        $advancehistory->save();

        $payment_transaction = new PaymentTransaction();
        $payment_transaction->transaction_number = CommonComponent::invoice_no('payment_transaction'); // Auto Generate
        $payment_transaction->transaction_type = 6; // Advance
        $payment_transaction->type = 2; // Debit
        $payment_transaction->reference_id = $advancehistory->id;
        $payment_transaction->payment_type_id = (int)$request->payment_type_id;
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
}
