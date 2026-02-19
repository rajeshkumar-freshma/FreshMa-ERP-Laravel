<?php

namespace App\DataTables\Master;

use App\Core\CommonComponent;
use App\Models\Warehouse;
use Auth;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class WarehouseDataTable extends DataTable
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
        // ->addIndexColumn()
            ->addIndexColumn()
            ->rawColumns(['action', 'status', 'defaultwarehouse'])
            ->order(function ($model) {
                $model->orderBy('id', 'DESC');
            })
            ->addColumn('status', function (Warehouse $model) {
                $statuschange = 1;
                return view('pages.partials.status_toggle_button', compact('model', 'statuschange'));
            })
            ->addColumn('defaultwarehouse', function (Warehouse $model) {
                $defaultwarehouse = 1;
                return view('pages.partials.status_toggle_button', compact('model', 'defaultwarehouse'));
            })
            ->editColumn('created_at', function (Warehouse $model) {
                return CommonComponent::getCreatedAtFormat($model->created_at);
            })
            ->editColumn('city_id', function (Warehouse $model) {
                return $model->city->name;
            })
            ->editColumn('state_id', function (Warehouse $model) {
                return $model->state->name;
            })
            ->editColumn('country_id', function (Warehouse $model) {
                return $model->country->name;
            })
            ->editColumn('start_date', function (Warehouse $model) {
                return CommonComponent::getDateFormat($model->start_date);
            })
            ->addColumn('action', function (Warehouse $model) {
                return view('pages.master.warehouse._action-menu', compact('model'));
            });
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\Warehouse $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(Warehouse $model): QueryBuilder
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
        if (Auth::check() && Auth::user()->can('Warehouse Create')) {
            $createButton[] = Button::make('create');
        }

        return $this->builder()
            ->setTableId('warehouse-table')
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
            ->addTableClass('align-middle table-row-dashed fs-6 gy-5')
            ->dom('Bfrtip')
            ->buttons($createButton);
        // ->buttons([
        //     // Other buttons you may want to include
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
            Column::make('code')->title(__('Code')),
            Column::make('phone_number')->title(__('Phone Number')),
            Column::make('email'),
            Column::make('city_id')->title(__('City')),
            Column::make('state_id')->title(__('State')),
            Column::make('country_id')->title(__('Country')),
            Column::make('status'),
            Column::make('start_date'),
            Column::make('defaultwarehouse')->title(__('Is Default')),
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
        return 'Warehouse_' . date('YmdHis');
    }
}
