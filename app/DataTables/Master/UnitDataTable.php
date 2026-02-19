<?php

namespace App\DataTables\Master;

use App\Core\CommonComponent;
use App\Models\Unit;
use Auth;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class UnitDataTable extends DataTable
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
            ->addColumn('action', 'status')
            ->addIndexColumn()
            ->order(function ($model) {
                $model->orderBy('id', 'DESC');
            })
            ->addColumn('status', function (Unit $model) {
                $status = $model->status;
                return view('pages.partials.statuslabel', compact('status'));
            })
            ->addColumn('default', function (Unit $model) {
                $default = $model->default;
                return view('pages.partials.statuslabel', compact('default'));
            })
            ->editColumn('created_at', function (Unit $model) {
                return CommonComponent::getCreatedAtFormat($model->created_at);
            })
            ->editColumn('allow_decimal', function (Unit $model) {
                $allow_decimal = $model->allow_decimal;
                // Check if $allow_decimal is a decimal and less than 1
                if (is_numeric($allow_decimal) && $allow_decimal < 1) {
                    // Add a leading zero
                    $allow_decimal = '0' . $allow_decimal;
                }
                return $allow_decimal;
            })
            ->editColumn('operator', function (Unit $model) {
                $operator = $model->operator;
                return view('pages.partials.statuslabel', compact('operator'));
            })
            ->addColumn('action', function (Unit $model) {
                return view('pages.master.unit._action-menu', compact('model'));
            });
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\Unit $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(Unit $model): QueryBuilder
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
        if (Auth::check() && Auth::user()->can('Unit Create')) {
            $createButton[] = Button::make('create');
        }

        return $this->builder()
            ->setTableId('unit-table')
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
            Column::make('unit_name')->title(__('Unit Name')),
            Column::make('unit_short_code')->title(__('Unit Short Code')),
            Column::make('base_unit')->title(__('Base Unit')),
            Column::make('allow_decimal')->title(__('Allow Decimal')),
            Column::make('operator'),
            Column::make('operation_value')->title(__('Opration Value')),
            Column::make('status'),
            Column::make('default')->title(__('Default')),
            Column::make('created_at')->title(__('Created At')),
            Column::computed('action')
                ->exportable(false)
                ->printable(false)
                ->addClass('text-center')
                ->responsivePriority(-1),
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename(): string
    {
        return 'Unit_' . date('YmdHis');
    }
}
