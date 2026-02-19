<?php

namespace App\DataTables\Master;

use App\Core\CommonComponent;
use App\Models\IncomeExpenseType;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class IncomeExpenseTypeDataTable extends DataTable
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
            ->rawColumns(['status', 'type', 'action'])
            ->order(function ($model) {
                $model->orderBy('id', 'DESC');
            })
            ->addColumn('status', function (IncomeExpenseType $model) {
                $status = $model->status;
                return view('pages.partials.statuslabel', compact('status'));
            })
            ->addColumn('type', function (IncomeExpenseType $model) {
                $type = $model->type;
                return view('pages.partials.income_expense_label', compact('type'));
            })
            ->editColumn('created_at', function (IncomeExpenseType $model) {
                return CommonComponent::getCreatedAtFormat($model->created_at);
            })
            ->addColumn('action', function (IncomeExpenseType $model) {
                return view('pages.master.income_expense_type._action-menu', compact('model'));
            });
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\IncomeExpenseType $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(IncomeExpenseType $model): QueryBuilder
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
        $createButton = [];

        // Check if the user is authenticated and has permission to create
        if (Auth::check() && Auth::user()->can('Income/Expense Category Create')) {
            $createButton[] = Button::make('create');

        }

        return $this->builder()
            ->setTableId('incomeexpensetype-table')
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
        // ->buttons([
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
            Column::make('name')->title(__('Name')),
            Column::make('type'),
            Column::make('status'),
            Column::make('created_at')->title(__('Created At')),
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
        return 'IncomeExpenseType_' . date('YmdHis');
    }
}
