<?php

namespace App\Http\Controllers\Admin\SupplierPayment;

use App\Core\CommonComponent;
use App\DataTables\SupplierBulkPayment\SupplierBulkPaymentDataTable;
use App\Http\Controllers\Controller;
use App\Http\Requests\SupplierBulkPayment\SupplierBulkPaymentFormRequest;
use App\Models\PaymentTransaction;
use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderMultiTransaction;
use App\Models\Supplier;
use App\Models\User;
use App\Models\UserAdvance;
use App\Models\UserAdvanceHistory;
use Carbon\Exceptions\Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Log;

class SupplierBulkPaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(SupplierBulkPaymentDataTable $dataTable)
    {
        return $dataTable->render('pages.supplier_bulk_payment.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $data['supplier'] = Supplier::all();
        return view('pages.supplier_bulk_payment.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(SupplierBulkPaymentFormRequest $request)
    {
        // return $request->all();
        $supplierId = $request->supplier_id;
        $totalPayingAmount = $request->amount ?? 0;
        $advanceAmount = $request->user_advance_amount;
        $pendingAmount = $request->pending_amount;

        if ($request->advance_amount_included == 1) {
            $totalPayingAmount += $advanceAmount;
        }

        $purchaseOrderIds = $request->purchase_order_id;
        if (!is_null($purchaseOrderIds) && is_array($purchaseOrderIds) && count($purchaseOrderIds) > 0) {
            foreach ($purchaseOrderIds as $purchaseOrderId) {
                if ($totalPayingAmount > 0) {
                    $purchaseOrderDetails = PurchaseOrder::findOrFail($purchaseOrderId);
                    $dueAmount = $purchaseOrderDetails->total - $purchaseOrderDetails->purchase_order_transactions->sum('amount');

                    if ($totalPayingAmount > $pendingAmount) {
                        $debitAmount = min($dueAmount, $advanceAmount);
                        $this->userAdvanceCreditDebit(1, $purchaseOrderId, $supplierId, $debitAmount, 2);
                        $advanceAmount -= $debitAmount;
                    }

                    $paidAmount = min($dueAmount, $totalPayingAmount);

                    $paymentTransaction = new PaymentTransaction([
                        'transaction_number' => CommonComponent::invoice_no('payment_transaction'),
                        'transaction_type' => 1,
                        'type' => 2,
                        'reference_id' => $purchaseOrderId,
                        'payment_type_id' => 1,
                        'amount' => $paidAmount,
                        'transaction_datetime' => now(),
                        'status' => 1,
                    ]);

                    $paymentTransaction->save();

                    $this->updatePurchaseOrderStatus($purchaseOrderDetails, $request->payment_status);

                    $totalPayingAmount -= $paidAmount;
                }
            }

            // if ($totalPayingAmount > 0) {
            //     $this->userAdvanceCreditDebit(null, null, $supplierId, $totalPayingAmount, 1);
            // }

            $this->createPurchaseOrderMultiTransaction($request);
        }

        return redirect()->route('admin.supplier-bulk-payment.index')->with('success', 'Supplier Payment Stored Successfully');
    }

    private function updatePurchaseOrderStatus($purchaseOrderDetails, $paymentStatus)
    {
        $paidAmount = $purchaseOrderDetails->purchase_order_transactions->sum('amount');
        $totalAmount = $purchaseOrderDetails->total;

        if ($paidAmount == 0) {
            $purchaseOrderDetails->payment_status = $paymentStatus ?? 2; // UnPaid
        } elseif ($paidAmount >= $totalAmount) {
            $purchaseOrderDetails->payment_status = $paymentStatus ?? 1; // Paid
        } else {
            $purchaseOrderDetails->payment_status = $paymentStatus ?? 3; // Pending
        }

        $purchaseOrderDetails->save();
    }

    private function createPurchaseOrderMultiTransaction($request)
    {
        Log::info("entred the multiple transactions created ");
        Log::info($request);
        $multiTransaction = new PurchaseOrderMultiTransaction([
            'purchase_order_id' => json_encode($request->purchase_order_id),
            'supplier_id' => $request->supplier_id,
            'amount' => $request->amount,
            'advance_amount_included' => $request->advance_amount_included,
            'advance_amount' => $request->user_advance_amount,
            'payment_type_id' => 1,
            'transaction_date' => $request->transaction_date,
            'remarks' => $request->remarks,
        ]);

        return $multiTransaction->save();
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $data['purchaseBulkPaymentTransaction'] = PurchaseOrderMultiTransaction::find($id);

        // Convert JSON-encoded array to a PHP array
        $purchaseOrderIds = json_decode($data['purchaseBulkPaymentTransaction']->purchase_order_id);

        $data['purchase_orders'] = PurchaseOrder::whereIn('id', $purchaseOrderIds)
            ->get();
        $data['supplier'] = Supplier::all();
        return view('pages.supplier_bulk_payment.show', $data)->with($purchaseOrderIds);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $data['supplier'] = Supplier::all();
        $data['purchase_order_multi_transactions'] = PurchaseOrderMultiTransaction::find($id);
        // // Convert purchase_order_id string to array
        // $purchase_order_ids_json = $data['purchase_order_multi_transactions']->purchase_order_id;
        // $purchase_order_ids = json_decode($purchase_order_ids_json, true); // Decode JSON string

        // // Check if decoding was successful
        // if ($purchase_order_ids === null) {
        //     // Handle error, JSON decoding failed
        //     // You can return an error response or handle it based on your application's logic
        // }

        // // Now, you can explode the IDs if needed
        // // $purchase_order_ids = explode(',', $purchase_order_ids);

        // // Fetch purchase orders
        // $data['purchase_orders'] = PurchaseOrder::whereIn('id', $purchase_order_ids)->get();
        // Decode JSON string to get purchase order IDs
        $data['purchase_orders'] = PurchaseOrder::all();
        $purchase_order_ids_json = $data['purchase_order_multi_transactions']->purchase_order_id;
        $data['purchase_order_ids'] = json_decode($purchase_order_ids_json, true);

        // Fetch purchase orders
        $data['purchase_orders'] = PurchaseOrder::all();




        return view('pages.supplier_bulk_payment.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     */
    /**
     * Update the specified resource in storage.
     */
    public function update(SupplierBulkPaymentFormRequest $request, $id)
    {

        $supplierId = $request->supplier_id;
        $totalPayingAmount = $request->amount ?? 0;
        $advanceAmount = $request->user_advance_amount;
        $pendingAmount = $request->pending_amount;
        if ($request->advance_amount_included == 1) {
            $totalPayingAmount += $advanceAmount;
        }
        $multiTransaction = PurchaseOrderMultiTransaction::findOrFail($id);

        $multiTransaction->supplier_id = $request->supplier_id;
        $multiTransaction->purchase_order_id = json_encode($request->purchase_order_id);
        $multiTransaction->amount = $request->amount ?? 0;
        $multiTransaction->advance_amount_included = $request->advance_amount_included;
        $multiTransaction->advance_amount = $request->user_advance_amount ?? 0;
        $multiTransaction->payment_type_id = 1;
        $multiTransaction->transaction_date = $request->transaction_date;
        $multiTransaction->remarks = $request->remarks;

        $multiTransaction->save();

        $purchaseOrderIds = $request->purchase_order_id;
        if (!is_null($purchaseOrderIds) && is_array($purchaseOrderIds) && count($purchaseOrderIds) > 0) {
            foreach ($purchaseOrderIds as $purchaseOrderId) {
                if ($totalPayingAmount > 0) {
                    $purchaseOrderDetails = PurchaseOrder::findOrFail($purchaseOrderId);
                    $dueAmount = $purchaseOrderDetails->total - $purchaseOrderDetails->purchase_order_transactions->sum('amount');

                    if ($totalPayingAmount > $pendingAmount) {
                        $debitAmount = min($dueAmount, $advanceAmount);
                        $this->userAdvanceCreditDebit(1, $purchaseOrderId, $supplierId, $debitAmount, 2);
                        $advanceAmount -= $debitAmount;
                    }

                    $paidAmount = min($dueAmount, $totalPayingAmount);

                    $paymentTransaction = new PaymentTransaction([
                        'transaction_number' => CommonComponent::invoice_no('payment_transaction'),
                        'transaction_type' => 1,
                        'type' => 2,
                        'reference_id' => $purchaseOrderId,
                        'payment_type_id' => 1,
                        'amount' => $paidAmount,
                        'transaction_datetime' => now(),
                        'status' => 1,
                    ]);

                    $paymentTransaction->save();

                    $this->updatePurchaseOrderStatus($purchaseOrderDetails, $request->payment_status);

                    $totalPayingAmount -= $paidAmount;
                }
            }

            // if ($totalPayingAmount > 0) {
            //     $this->userAdvanceCreditDebit(null, null, $supplierId, $totalPayingAmount, 1);
            // }
        }

        return redirect()->route('admin.supplier-bulk-payment.index')->with('success', 'Supplier Payment Updated Successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function userAdvanceCreditDebit($referrence_table, $referrence_id, $supplier_id, $amount, $creditdebit)
    {
        DB::beginTransaction();
        Log::info("user advance table storage the transactions for user");
        Log::info($referrence_table);
        Log::info($referrence_id);
        Log::info($supplier_id);
        Log::info($amount);
        Log::info($creditdebit);
        if ($amount > 0) {
            $user_advance = UserAdvance::where('user_id', $supplier_id)->firstOrNew();
            Log::info($user_advance);

            $user_advance->type = $creditdebit; // Credit or Debit
            $user_advance->amount += $creditdebit == 1 ? $amount : -$amount;
            $user_advance->total_amount += $creditdebit == 1 ? $amount : -$amount;
            $user_advance->save();

            $advancehistory = new UserAdvanceHistory([
                'user_id' => $supplier_id,
                'transaction_type' => $referrence_table ?? '', // Purchase or other reference type
                'reference_id' => $referrence_id,
                'type' => $creditdebit, // Credit or Debit
                'amount' => $amount,
            ]);

            $advancehistory->save();

            DB::commit();
        }
    }

    private function fetchSupplierPurchaseOrders(Request $request, $paymentStatus)
    {
        try {
            $supplierId = $request->supplier_id;
            Log::info("Supplier ID: $supplierId");

            if (isset($supplierId) && $supplierId !== null) {
                $userAdvances = UserAdvance::where('user_id', $supplierId)
                    ->where('status', 1)
                    ->where('type', 1)
                    ->get();

                $purchaseOrders = PurchaseOrder::where('supplier_id', $supplierId)
                    ->whereIn('payment_status', $paymentStatus)
                    ->with('purchase_order_transactions')
                    ->get();

                Log::info('User Advances:', $userAdvances->toArray());
                Log::info('Purchase Orders:', $purchaseOrders->toArray());

                return response()->json([
                    'status' => 200,
                    'message' => 'Data Fetched Successfully',
                    'data' => [
                        'user_advances' => $userAdvances,
                        'purchase_orders' => $purchaseOrders,
                    ],
                ]);
            }

            return response()->json([
                'status' => 400,
                'message' => 'Invalid or missing supplier_id',
            ]);
        } catch (\Exception $e) {
            Log::error($e);
            return response()->json([
                'status' => 400,
                'message' => 'Sorry, Something went wrong',
            ]);
        }
    }

    public function supplierPurchaseOrders(Request $request)
    {
        // For purchase orders without status 1
        return $this->fetchSupplierPurchaseOrders($request, [2, 3]);
    }

    public function supplierPurchaseOrdersEdit(Request $request)
    {
        // For purchase orders with status 1
        return $this->fetchSupplierPurchaseOrders($request, [1, 2, 3]);
    }
}
