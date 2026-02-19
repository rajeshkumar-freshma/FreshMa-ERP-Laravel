<?php

namespace App\DataTables\IncomeAndExpense;

use App\Core\CommonComponent;
use App\Models\IncomeExpenseTransaction;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class IncomeAndExpenseDataTable extends DataTable
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
            ->order(function ($model) {
                $model->orderBy('id', 'DESC');
            })
            // ->rawColumns(['status', 'action', 'store_id', 'product_id', 'created_at', 'stock_update_on'])
            ->editColumn('store_id', function (IncomeExpenseTransaction $model) {
                return $model->store->store_name ?? '';
            })
            ->editColumn('warehouse_id', function (IncomeExpenseTransaction $model) {
                return $model->warehouse->name ?? '';
            })
            ->editColumn('created_at', function (IncomeExpenseTransaction $model) {
                return CommonComponent::getCreatedAtFormat($model->created_at);
            })
            ->editColumn('related_to', function (IncomeExpenseTransaction $model) {
                $related_to = $model->related_to;
                return view('pages.partials.statuslabel', compact('related_to'));
            })
            ->editColumn('income_expense_type_id', function (IncomeExpenseTransaction $model) {
                $income_expense_type_id = $model->income_expense_type_id;
                return view('pages.partials.statuslabel', compact('income_expense_type_id'));
            })
            ->editColumn('transaction_datetime', function (IncomeExpenseTransaction $model) {
                return CommonComponent::getDateFormat($model->transaction_datetime);
            })
            ->addColumn('status', function (IncomeExpenseTransaction $model) {
                $status = $model->status;
                return view('pages.partials.statuslabel', compact('status'));
            })
            ->addColumn('payment_status', function (IncomeExpenseTransaction $model) {
                $income_expense_payment_status = $model->payment_status;
                return view('pages.partials.statuslabel', compact('income_expense_payment_status'));
            })
            ->addColumn('action', function (IncomeExpenseTransaction $model) {
                return view('pages.income_and_expense._action-menu', compact('model'));
            });
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\IncomeExpenseTransaction $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(IncomeExpenseTransaction $model)
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
        if (Auth::check() && Auth::user()->can('Income Expense Add Create')) {
            $createButton[] = Button::make('create');

        }

        return $this->builder()
            ->setTableId('income_expense_transactions_table')
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
     * Get columns.
     *
     * @return array
     */
    protected function getColumns()
    {
        return [
            Column::make('id')->title('S.No')->render('meta.row + meta.settings._iDisplayStart + 1;')->addClass('ps-0'),
            Column::make('expense_invoice_number'),
            Column::make('warehouse_id')->title(__('Warehouse')),
            Column::make('store_id')->title(__('Store')),
            Column::make('income_expense_type_id')->title(__('Income/Expense Type')),
            Column::make('transaction_datetime'),
            Column::make('related_to'),
            Column::make('total_amount'),
            Column::make('payment_status'),
            Column::make('remarks'),
            Column::make('status'),
            Column::make('created_at'),
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
        return 'IncomeExpenseTransaction_' . date('YmdHis');
    }
}
