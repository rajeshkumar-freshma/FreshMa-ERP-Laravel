<?php

namespace App\DataTables\Accounting;

use App\Core\CommonComponent;
use App\Models\Account;
use App\Models\Transfer;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class TransferDataTable extends DataTable
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
            ->editColumn('status', function (Transfer $model) {
                $status = $model->status;
                return view('pages.partials.statuslabel', compact('status'));
            })
            ->editColumn('transaction_date', function (Transfer $model) {
                return CommonComponent::getDateFormat($model->transaction_date);
                // return $transaction_date = Carbon::createFromFormat('M d Y h:i:s A', $model->transaction_date)->format('d-m-Y');
                // return $date = Carbon::createFromFormat('Y-m-d', $model->transaction_date)->format('d-m-Y');
            })
            ->addColumn('from_account', function (Transfer $model) {
                $from_account = Account::find($model->from_account_id);
                return $from_account ? $from_account->account_holder_name : '';
            })
            ->addColumn('to_account', function (Transfer $model) {
                $to_account = Account::find($model->to_account_id);
                return $to_account ? $to_account->account_holder_name : '';
            })
            ->addColumn('action', function (Transfer $model) {
                return view('pages.accounting.transfer._action-menu', compact('model'));
            });
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\Transfer $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(Transfer $model): QueryBuilder
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
        if (Auth::check() && Auth::user()->can('Transfer Create')) {
            $createButton[] = Button::make('create');
        }

        return $this->builder()
            ->setTableId('transfer-table')
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
            Column::make('transfer_reason')->title(__('Transfer Reason')),
            Column::make('from_account'),
            Column::make('to_account'),
            // Column::make('available_balance'),
            Column::make('transfer_amount'),
            // Column::make('is_half_day'),
            Column::make('transaction_date'),
            Column::make('notes'),
            Column::make('status'),
            // Column::computed('action') // why this line commanded because this is payment related so it is senstive once created there is not allowed to eidt the module
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
