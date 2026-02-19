<?php

namespace App\Http\Controllers\Admin\Accounting;

use App\DataTables\Report\TransactionsReportDataTable;
use App\Http\Controllers\Controller;
use App\Models\Account;
use App\Models\Transaction;

class TransactionReportController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    public function index(TransactionsReportDataTable $dataTable)
    {
        $data['accounts'] = Account::get();
        $data['transactions'] = Transaction::get();
        return $dataTable->render('pages.report.bank_transactions_report.report', $data);
    }
    // public function transactions_report(Request $request)
    // {
    //     $data['accounts'] = Account::get();
    //     $data['transactions'] = Transaction::get();

    //     if ($request->ajax()) {
    //         // Initialize variables
    //         $dateFilterAdded = false;
    //         $fromDate = '';
    //         $toDate = '';

    //         if ($request->filled('from_date') && $request->filled('to_date')) {
    //             $dateFilterAdded = true;
    //             $fromDate = CarbonImmutable::parse($request->from_date)->startOfDay();
    //             $toDate = CarbonImmutable::parse($request->to_date)->endOfDay();
    //         }

    //         $transactionData = $this->filterTransactions($request, $fromDate, $toDate);

    //         $loopCount = 1;
    //         $datas = [];

    //         foreach ($transactionData as $key => $transaction) {
    //             $data = [
    //                 'id' => $loopCount++,
    //                 'transaction_date' => $transaction->transaction_date,
    //                 'notes' => $transaction->notes,
    //                 'debit' => ($transaction->transaction_type == 0) ? number_format($transaction->transaction_amount, 2) : '',
    //                 'credit' => ($transaction->transaction_type != 0) ? number_format($transaction->transaction_amount, 2) : '',
    //                 'available_balance' => number_format($transaction->available_balance, 2),
    //             ];

    //             $datas[] = $data;
    //         }

    //         return DataTables::of($datas)
    //             ->rawColumns(['transaction_date', 'notes', 'debit', 'credit', 'available_balance'])
    //             ->editColumn('id', function ($data) {
    //                 return @$data['id'];
    //             })
    //             ->addColumn('transaction_date', function ($data) {
    //                 return commoncomponent()->getDateFormat(@$data['transaction_date']);
    //             })
    //             ->addColumn('notes', function ($data) {
    //                 return @$data['notes'];
    //             })
    //             // ->buttons([
    //             //     Button::make('excel'),
    //             //     Button::make('csv'),
    //             //     Button::make('pdf'),
    //             //     Button::make('print'),
    //             //     Button::make('reset'),
    //             //     Button::make('reload')
    //             // ])
    //             ->make(true);
    //     }

    //     return view('pages.report.bank_transactions_report.report', $data);
    // }

    // private function filterTransactions($request, $fromDate, $toDate)
    // {
    //     return Transaction::where(function ($query) use ($request, $fromDate, $toDate) {
    //         if ($request->filled('bank_id')) {
    //             $query->where('transaction_account_id', $request->bank_id);
    //         }
    //         if ($fromDate != '' && $toDate != '') {
    //             $query->whereBetween('transaction_date', [$fromDate, $toDate]);
    //         }
    //     })->get();
    // }

}
