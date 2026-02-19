<?php

namespace App\DataTables\Report;

use App\Models\Account;
use App\Models\Product;
use App\Models\SalesOrderDetail;
use App\Models\Transaction;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class TransactionsReportDataTable extends DataTable
{
    /**
     * Build DataTable class.
     *
     * @param QueryBuilder $query Results from query() method.
     * @return \Yajra\DataTables\EloquentDataTable
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->rawColumns(['transaction_account_id', 'transaction_type'])
            // ->editColumn('transactions_type', function (Transaction $model) {
            //     return 1;
            // })
            ->order(function ($model) {
                $model->orderBy('id', 'DESC');
            })
            ->editColumn('transaction_account', function (Transaction $model) {
                $transactionAccountId = $model->transaction_account;
                $Accounts = Account::find($transactionAccountId);
                return $Accounts->account_holder_name ?? '';
            })
            ->editColumn('transaction_type', function (Transaction $model) {
                $transaction_type = $model->transaction_type;
                return view('pages.partials.statuslabel', compact('transaction_type'));
            });
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(Transaction $model): QueryBuilder
    {
        $model->newQuery();

        $query = $model->newQuery();
        $from_date = $this->request()->input('from_date');
        $to_date = $this->request()->input('to_date');
        $transaction_account_id = $this->request()->input('transaction_account_id');
        $transaction_type_id = $this->request()->input('transaction_type_id');
        Log::info("transaction_type_id");
        Log::info($transaction_type_id);

        if ($from_date || $to_date) {
            if ($from_date && $to_date) {
                $query->whereBetween('transaction_date', [$from_date, $to_date]);
            } elseif ($from_date) {
                $query->whereDate('transaction_date', $from_date);
            } elseif ($to_date) {
                $query->whereDate('transaction_date', $to_date);
            }
        }
        if ($transaction_type_id!==null) {
            $query->where('transaction_type', $transaction_type_id);
        }
        if ($transaction_account_id) {
            $query->where('transaction_account', $transaction_account_id);
        }
        return $this->applyScopes($query);
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        $createButton = [];

        // Check if the user is authenticated and has permission to create
        if (Auth::check() &&  Auth::user()->can('Transaction Report Download')) {
            $createButton[] = Button::make(['excel', 'csv', 'print']);
        }


        return $this->builder()
            ->setTableId('transaction_reports-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->stateSave(false)
            ->responsive()
            ->autoWidth(true)
            ->parameters([
                'scrollX' => true,
                'drawCallback' => 'function() { KTMenu.createInstances(); }',
            ])
            // ->selectStyleSingle()
            ->addTableClass('align-middle table-striped table-row-dashed fs-6 gy-1')
            ->dom('Bltip')
            ->buttons($createButton);
        // ->buttons([
        //     Button::make('excel'),
        //     Button::make('csv'),
        //     // Button::make('pdf'),
        //     Button::make('print'),
        //     // Button::make('reset'),
        //     // Button::make('reload')
        // ]);
    }

    /**
     * Get the dataTable columns definition.
     */
    public function getColumns(): array
    {
        return [
            Column::make('id')->title('S.No')->render('meta.row + meta.settings._iDisplayStart + 1;')->addClass('ps-0'),
            Column::make('transaction_account')->title(__('Account Holder Name')),
            Column::make('transaction_date')->title(__('Date')),
            Column::make('notes'),
            Column::make('transaction_amount'),
            Column::make('transaction_type'),
            Column::make('available_balance')
        ];
    }

    /**
     * Get the filename for export.
     */
    protected function filename(): string
    {
        return 'TransactionsReport_' . date('YmdHis');
    }
}
