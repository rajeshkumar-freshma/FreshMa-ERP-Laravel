<?php

namespace App\DataTables\PushNotification;

use App\Core\CommonComponent;
use App\Models\PaymentTransaction;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class PushNotificationDataTable extends DataTable
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
            ->rawColumns(['type', 'transaction_type', 'payment_type_id', 'action'])
            ->order(function ($model) {
                $model->orderBy('id', 'DESC');
            })
            ->editColumn('transaction_datetime', function (PaymentTransaction $model) {
                return CommonComponent::getCreatedAtFormat($model->transaction_datetime);
            })
            ->editColumn('type', function (PaymentTransaction $model) {
                $type = $model->type;
                return view('pages.partials.statuslabel', compact('type'));
            })
            ->editColumn('transaction_type', function (PaymentTransaction $model) {
                $paymentTransactionType = $model->transaction_type;
                return view('pages.partials.statuslabel', compact('paymentTransactionType'));
            })
            ->editColumn('payment_type_id', function (PaymentTransaction $model) {
                return $paymentType = @$model->payment_type_details->payment_type;
            });
        // ->addColumn('action', function (PaymentTransaction $model) {
        //     return view('pages.payment.transactions_report._action-menu', compact('model'));
        // });
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\PaymentTransaction $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(PaymentTransaction $model): QueryBuilder
    {
        $query = $model->newQuery();
        return $this->applyScopes($query);
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('payment-transactions-table')
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
            ->buttons([
                Button::make('excel'),
                Button::make('csv'),
                // Button::make('pdf'),
                Button::make('print'),
                // Button::make('reset'),
                // Button::make('reload')
            ]);
    }

    /**
     * Get the dataTable columns definition.
     */
    public function getColumns(): array
    {
        return [
            Column::make('id')->title('S.No')->render('meta.row + meta.settings._iDisplayStart + 1;')->addClass('ps-0'),
            Column::make('transaction_datetime'),
            Column::make('transaction_number'),
            Column::make('transaction_type'),
            Column::make('amount'),
            Column::make('payment_type_id'),
            Column::make('type'),
            Column::computed('action')
                ->exportable(false)
                ->printable(false)
                ->addClass('text-center')
                ->responsivePriority(-1),
            // Column::make('properties')->addClass('none'),
        ];
    }

    /**
     * Get the filename for export.
     */
    protected function filename(): string
    {
        return 'PymentTransactionsReport_' . date('YmdHis');
    }
}
