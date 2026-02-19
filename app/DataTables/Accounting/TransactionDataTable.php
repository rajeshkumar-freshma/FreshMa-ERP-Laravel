<?php

namespace App\DataTables\Accounting;

use App\Core\CommonComponent;
use App\Models\Account;
use App\Models\Transaction;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class TransactionDataTable extends DataTable
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
            ->addIndexColumn()
            ->rawColumns(['action', 'status'])
            ->order(function ($model) {
                $model->orderBy('id', 'DESC');
            })
            ->editColumn('status', function (Transaction $model) {
                $status = $model->status;
                return view('pages.partials.statuslabel', compact('status'));
            })
            ->editColumn('transaction_account', function (Transaction $model) {
                $transaction_account = Account::find($model->transaction_account);
                return $transaction_account ? $transaction_account->account_holder_name : '';
            })
            ->editColumn('transaction_type', function (Transaction $model) {
                $transaction_type = $model->transaction_type;
                return view('pages.partials.statuslabel', compact('transaction_type'));
            })
            ->editColumn('transaction_date', function (Transaction $model) {
                // return $transaction_date = Carbon::parse($model->transaction_date)->format('d-m-Y');
                // return $date = Carbon::createFromFormat('Y-m-d', $model->transaction_date)->format('d-m-Y');
                return CommonComponent::getDateFormat($model->transaction_date);
            })
            // ->addColumn('to_account', function (Transaction $model) {
            //     $to_account = Account::find($model->to_account);
            //     return $to_account ? $to_account->account_holder_name  : '';
            // })
            ->addColumn('action', function (Transaction $model) {
                return view('pages.accounting.transactions._action-menu', compact('model'));
            });
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\Transaction $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(Transaction $model): QueryBuilder
    {
        return $model->newQuery();
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\DataTables\Html\Builder
     */
    public function html(): HtmlBuilder
    {
        $createButton = [];

        // Check if the user is authenticated and has permission to create
        if (Auth::check() && Auth::user()->can('Transaction Create')) {
            $createButton[] = Button::make('create');
        }

        return $this->builder()
            ->setTableId('transactions-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->stateSave(false)
            ->responsive()
            ->autoWidth(true)
        // ->selectStyleSingle()
            ->parameters([
                'scrollX' => true,
                'drawCallback' => 'function() { KTMenu.createInstances(); }',
            ])
            ->addTableClass('align-middle table-row-dashed fs-6 gy-5')
            ->dom('Bfrtip')
            ->buttons($createButton);
        //     ->buttons([
        //     Button::make('create'),
        //     // Button::make('export'),
        //     // Button::make('print'),
        //     // Button::make('reset'),
        //     // Button::make('reload')
        // ]);
    }

    /**
     * Get the dataTable columns definition.
     *
     * @return array
     */
    public function getColumns(): array
    {
        return [
            Column::make('id')->title('S.No')->render('meta.row + meta.settings._iDisplayStart + 1;')->addClass('ps-0'),
            Column::make('transaction_account')->title(__('Transaction Account')),
            Column::make('transaction_amount'),
            // Column::make('available_balance'),
            Column::make('transaction_date'),
            // Column::make('is_half_day'),
            Column::make('transaction_type'),
            Column::make('status'),
            Column::make('notes'),
            // Column::computed('action')
            //     ->exportable(false)
            //     ->printable(false)
            //     ->addClass('text-center')
            //     ->responsivePriority(-1)
            // Column::make('properties')->addClass('none'),
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename(): string
    {
        return 'Account' . date('YmdHis');
    }
}
