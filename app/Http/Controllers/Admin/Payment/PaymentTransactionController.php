<?php

namespace App\Http\Controllers\Admin\Payment;

use App\DataTables\Payment\PaymentTransactionsReportDataTable;
use App\Http\Controllers\Controller;
use App\Mail\PurchaseOrderInvoiceEmail;
use App\Models\Admin;
use App\Models\Helper;
use App\Models\PaymentTransaction;
use App\Models\PaymentType;
use App\Models\Product;
use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderDetail;
use App\Models\SalesOrder;
use App\Models\SalesOrderDetail;
use App\Models\Staff;
use App\Models\Store;
use App\Models\Supplier;
use App\Models\SystemSetting;
use App\Models\SystemSiteSetting;
use App\Models\TaxRate;
use App\Models\UserAdvance;
use Illuminate\Http\Request;
use Log;
use Storage;
use Barryvdh\DomPDF\Facade\Pdf as PDF;
use Http;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Mail;
use Str;

// use Barryvdh\Snappy\Facades\SnappyPdf as PDF;

class PaymentTransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(PaymentTransactionsReportDataTable $dataTable, Request $request)
    {
        $supplierId = $request->supplier_id;

        // Retrieve payment transactions with associated purchase orders filtered by supplier ID
        // return $paymentTransactions = PaymentTransaction::where('transaction_type', 1)->where('type', 2)->with(
        //     'new_purchase_order'
        // )->groupBy('reference_id')
        // ->get();
        $data['paymentTypes'] = PaymentType::get();
        return $dataTable->render('pages.payment.transactions_report.report', $data);
    }

    public function invoice(Request $request, $id)
    {
        try {
            $data = [];

            $paymentTransaction = PaymentTransaction::with('user')->findOrFail($id);
            $paymentType = PaymentType::all();
            $data['systemSetting    '] = SystemSiteSetting::first();
            $data['payment_transactions_details'] = $paymentTransaction;
            $data['payment_types'] = $paymentType;

            $transactionType = $paymentTransaction->transaction_type;
            $referenceId = $paymentTransaction->reference_id;
            if ($referenceId !== null) {
                switch ($transactionType) {
                    case 1:
                        $data['purchaseOrder'] = PurchaseOrder::with('supplier')->find($referenceId);
                        // $firebaseToken = User::where('id', $indent_request->vendor_id)
                        //     ->pluck('fcm_token')
                        //     ->first();

                        // if (!$data['purchaseOrder']) {
                        //     abort(404, 'Purchase order not found');
                        // }
                        // $orderNo = $data['purchaseOrder']->purchase_order_number;

                        // // $id = Crypt::encrypt($data['purchaseOrder']->id);
                        // $id = $data['purchaseOrder']->id;
                        // // $firebaseToken = Admin::where('id', $data['purchaseOrder']->supplier->id ?? '')
                        // //     ->pluck('fcm_token')
                        // //     ->first();
                        // // $firebaseToken = $firebaseToken;
                        // // if ($firebaseToken != null) {
                        // //     Log::info("user_firebaseToken");
                        // //     Log::info($firebaseToken);
                        // //     $title = $orderNo;
                        // //     $content = 'Freshma - Order has been placed and your order ID is ' . $title;
                        // //     Helper::sendPushNotification($firebaseToken, $title, $content);
                        // // }
                        // $tiny_url = Http::get('https://tinyurl.com/api-create.php', [
                        //     'url' => route('purchase-invoice', $id, '/view'),
                        // ])->throw()->body();
                        // $content = [
                        //     'tiny_url' => $tiny_url,
                        //     'order_no' => $orderNo,
                        //     'customer_name' => Str::ucfirst($data['purchaseOrder']->supplier->first_name . ' ' . $data['purchaseOrder']->supplier->last_name),
                        // ];
                        // $email = $data['purchaseOrder']->supplier->email;
                        // Log::info("email");
                        // Log::info($email);
                        // Log::info($orderNo);
                        // Log::info($content);
                        // Helper::sendMail('PO-CON', $email, $orderNo, $content);
                        // Return view
                        return view('download.purchase.invoice', $data);
                    case 2:
                        $data['salesOrder'] = SalesOrder::with('vendor')->find($referenceId);
                        // if (!$data['salesOrder']) {
                        //     abort(404, 'Sales order not found');
                        // }

                        // $orderNo = $data['salesOrder']->invoice_number;
                        // // User-Notification
                        // // $firebaseToken = Admin::where('id', 3)
                        // //     ->pluck('fcm_token')
                        // //     ->first();
                        // // $firebaseToken = $firebaseToken;
                        // // if ($firebaseToken != null) {
                        // //     Log::info("user_firebaseToken");
                        // //     Log::info($firebaseToken);
                        // //     $title = $orderNo;
                        // //     $content = 'Freshma - Order has been placed and your order ID is ' . $title;
                        // //     Helper::sendPushNotification($firebaseToken, $title, $content);
                        // // }
                        // // // $id = Crypt::encrypt($data['salesOrder']->id);
                        // $id = $data['salesOrder']->id;
                        // $tiny_url = Http::get('https://tinyurl.com/api-create.php', [
                        //     'url' => route('sales-invoice', $id, '/view'),
                        // ])->throw()->body();
                        // $content = [
                        //     'tiny_url' => $tiny_url,
                        //     'order_no' => $orderNo,
                        //     'customer_name' => isset($data['salesOrder']->vendor)
                        //         ? Str::ucfirst($data['salesOrder']->vendor->first_name ?? '') . ' ' . Str::ucfirst($data['salesOrder']->vendor->last_name ?? '')
                        //         : 'Unknown Vendor',
                        // ];


                        // $email = $data['salesOrder']->vendor->email; // Replace with recipient email address
                        // Log::info("mail seding to the all datas");
                        // Log::info($email);
                        // Log::info($orderNo);
                        // Log::info($content);
                        // Log::info("mail seding to the all datas");
                        // // Send email using the dynamically set configurations
                        // // Mail::queue(new SalesOrderInvoiceEmail(['data['salesOrder']' => $data['salesOrder']]));
                        // Helper::sendMail('SO-CON', $email, $orderNo, $content);
                        // Log::info("mail seding");
                        // // // Mail::queue(new SalesOrderInvoiceEmail($data));
                        // // Return view
                        return view('download.sales.invoice', $data);
                    case 4:
                        $data['store'] = Store::findOrFail($referenceId);

                        // Send email using the dynamically set configurations
                        // Mail::queue(new PurchaseOrderInvoiceEmail($data));
                        return view('download.store.invoice', $data);
                    case 6:
                        $data['userAdvance'] = UserAdvance::findOrFail($referenceId);

                        // Send email using the dynamically set configurations
                        // Mail::queue(new PurchaseOrderInvoiceEmail($data));
                        return view('download.useradvance.invoice', $data);
                    default:
                        // Handle unknown transaction_type
                        return response()->view('errors.404', [], 404);
                }
            }
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $exception) {
            // Handle the case where the record is not found
            return response()->view('errors.404', [], 404);
        }
    }
    // public function invoice1(Request $request, $id)
    // {
    //     try {
    //         $data = [];

    //         $paymentTransaction = PaymentTransaction::with('user')->findOrFail($id);
    //         $paymentType = PaymentType::all();
    //         $data['payment_transactions_details'] = $paymentTransaction;
    //         $data['payment_types'] = $paymentType;
    //         $referenceId = $paymentTransaction->reference_id;
    //         $transactionType = $paymentTransaction->transaction_type;
    //         if ($referenceId !== null) {
    //             switch ($transactionType) {
    //                 case 1:
    //                     $data['purchaseOrder'] = PurchaseOrder::findOrFail($referenceId);
    //                     return view('download.purchase.invoice1', $data);
    //                 case 2:
    //                     $data['salesOrder'] = SalesOrder::find($referenceId);
    //                     return view('download.sales.invoice1', $data);
    //                 case 4:
    //                     $data['store'] = Store::findOrFail($referenceId);
    //                     return view('download.store.invoice', $data);
    //                 case 6:
    //                     $data['userAdvance'] = UserAdvance::findOrFail($referenceId);
    //                     return view('download.useradvance.invoice', $data);
    //                 default:
    //                     // Handle unknown transaction_type
    //                     return response()->view('errors.404', [], 404);
    //             }
    //         }
    //     } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $exception) {
    //         // Handle the case where the record is not found
    //         return response()->view('errors.404', [], 404);
    //     }
    // }
    public function invoice1(Request $request, $id)
    {
        try {
            $data = [];
            $paymentTransaction = PaymentTransaction::with('user')->findOrFail($id);
            $paymentType = PaymentType::all();
            $data['payment_transactions_details'] = $paymentTransaction;
            $data['payment_types'] = $paymentType;
            $referenceId = $paymentTransaction->reference_id;
            $transactionType = $paymentTransaction->transaction_type;
            if ($referenceId !== null) {
                switch ($transactionType) {
                    case 1:
                        $data['purchaseOrder'] = PurchaseOrder::with('user_details', 'purchase_order_product_details', 'paymentTransactions')->findOrFail($referenceId);
                        $fileName = 'purchase order Invoice1';
                        $pdf = view('pdf_download_files.purchase_invoice1_pdf', $data)->render();
                        // return    Helper::downloadInvoice($pdf, $fileName);
                        return view('pdf_download_files.purchase_invoice1_pdf', $data);
                    case 2:
                        $data['salesOrder'] = SalesOrder::find($referenceId);
                        $pdf = view('pdf_download_files.sales_invoice1_pdf', $data)->render();
                        $fileName = 'sales order Invoice1';
                        // return   Helper::downloadInvoice($pdf, $fileName);
                        return view('pdf_download_files.sales_invoice1_pdf', $data);

                    case 4:
                        $data['store'] = Store::findOrFail($referenceId);
                        $pdf = view('pdf_download_files.store_invoice_pdf', $data)->render();
                        $fileName = 'store Invoice';
                        // return    Helper::downloadInvoice($pdf, $fileName);
                        return view('pdf_download_files.store_invoice_pdf', $data);
                    case 6:
                        $data['userAdvance'] = UserAdvance::findOrFail($referenceId);
                        $pdf = view('pdf_download_files.useradvance_pdf', $data)->render();
                        $fileName = 'userAdvance Invoice';
                        // return   Helper::downloadInvoice($pdf, $fileName);
                        return view('pdf_download_files.useradvance_pdf', $data);
                    default:
                        // Handle unknown transaction_type
                        return response()->view('errors.404', [], 404);
                }
            }
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $exception) {
            // Handle the case where the record is not found
            return response()->view('errors.404', [], 404);
        }
    }
    public function receipt(Request $request, $id)
    {
        $data['payment_transaction_details'] = PaymentTransaction::find($id);
        $data['invoiceTo'] = PurchaseOrder::findOrFail($data['payment_transaction_details']->reference_id);
        return view('download.purchase.receipt', $data);
    }
}
