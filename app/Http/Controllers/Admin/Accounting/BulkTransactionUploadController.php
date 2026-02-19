<?php

namespace App\Http\Controllers\Admin\Accounting;

use App\Http\Controllers\Controller;
use App\Http\Requests\Accounting\BulkTransactionUploadFormRequest;
use App\Imports\TransactionImport;
use App\Models\Account;
use Excel;
use Log;

class BulkTransactionUploadController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    public function index()
    {
        $data['accounts'] = Account::get();
        return view('pages.accounting.bulk_transactions_upload.index', $data);
    }

    // public function upload(BulkTransactionUploadFormRequest $request)
    // {
    //     try {
    //         $file = $request->file('file');
    //         $bankId = $request->bank_id; // Assuming bank_id is passed in the request

    //         ImportExcelJob::dispatch($file, $bankId)
    //             ->onQueue('default')
    //             ->chain([
    //                 function ($job) {
    //                     Log::info("Excel file queued for import");
    //                 }
    //             ]);

    //         return redirect()->route('admin.upload-transactions.index')
    //             ->with('success', 'Transaction Import Successfully Queued for Import');
    //     } catch (\Exception $e) {
    //         Log::error('Exception during file upload: ' . $e->getMessage());
    //         return back()->withInput()->with('error', 'Transaction Upload Failed');
    //     }
    // }

    public function upload(BulkTransactionUploadFormRequest $request)
    {
        // try {
        $file = $request->file('file');

        Excel::import(new TransactionImport($request->bank_id), $file);
        Log::info("Uploaded successfully");
        return redirect()->route('admin.upload-transactions.index')->with('success', 'Transaction Import Successfully Uploaded');
        // } catch (\Exception $e) {
        //     Log::error('Exception during file upload: ' . $e->getMessage());
        //     return back()->withInput()->with('error', 'Transaction Upload Failed');
        // }
    }
}
