<?php

namespace App\DataTables\StoreCashRegister;

use App\Core\CommonComponent;
use App\Models\CashRegister;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\Html\Builder as HtmlBuilder;

class CashRegisterDataTable extends DataTable
{
    /**
     * Build DataTable class.
     *
     * @param  mixed  $query  Results from query() method.
     *
     * @return \Yajra\DataTables\DataTableAbstract
     */
    public function dataTable($query)
    {
        return (new EloquentDataTable($query))
            ->addIndexColumn()
            ->rawColumns(['status', 'action', 'store_id', 'created_at'])
            ->order(function ($model) {
                $model->orderBy('id', 'DESC');
            })
            ->editColumn('store_id', function (CashRegister $model) {
                return $model->store->store_name ?? '';
            })
            ->editColumn('verified_by', function (CashRegister $model) {
                return $model->verified->first_name ?? '';
            })
            ->editColumn('is_opened', function (CashRegister $model) {
                $is_open = $model->is_opened;
                return view('pages.partials.statuslabel', compact('is_open'));
            })
            ->editColumn('transaction_type', function (CashRegister $model) {
                $cash_register_transaction_type = $model->transaction_type;
                return view('pages.partials.statuslabel', compact('cash_register_transaction_type'));
            })
            ->editColumn('created_at', function (CashRegister $model) {
                return CommonComponent::getCreatedAtFormat($model->created_at);
            })
            ->editColumn('open_close_time', function (CashRegister $model) {
                return CommonComponent::getCreatedAtFormat($model->open_close_time);
            })
            ->addColumn('status', function (CashRegister $model) {
                $status = $model->status;
                return view('pages.partials.statuslabel', compact('status'));
            })
            ->addColumn('action', function (CashRegister $model) {
                return view('pages.store_cash_register.cash_register._action-menu', compact('model'));
            });
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\CashRegister $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(CashRegister $model)
    {
        $query = $model->newQuery();
        return $this->applyScopes($query);
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\DataTables\Html\Builder
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('cash_registers-table')
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
            ->buttons([
                // Button::make('create'),
                // Button::make('export'),
                // Button::make('print'),
                // Button::make('reset'),
                // Button::make('reload')
            ]);
    }


    /**
     * Get columns.
     *
     * @return array
     */
    protected function getColumns()
    {
        return [
            Column::make('id')->title('S.No')->render('meta.row + meta.settings._iDisplayStart + 1;')->addClass('ps-0'),
            Column::make('store_id')->title(__('Store')),
            Column::make('is_opened')->title(__('Is Open')),
            Column::make('amount'),
            Column::make('add_dedect_amount')->title(__('Add Dedect Amount')),
            Column::make('total_amount'),
            Column::make('transaction_type')->title(__('Transaction Type')),
            Column::make('open_close_time'),
            Column::make('verified_by'),
            Column::make('created_at'),
            Column::make('status'),
            Column::computed('action')
                ->exportable(false)
                ->printable(false)
                ->addClass('text-center')
                ->responsivePriority(-1),
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
        return 'CashRegisters_' . date('YmdHis');
    }
}
