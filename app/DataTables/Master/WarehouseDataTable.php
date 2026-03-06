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
                return optional($model->city)->name ?? '-';
            })
            ->editColumn('state_id', function (Warehouse $model) {
                return optional($model->state)->name ?? '-';
            })
            ->editColumn('country_id', function (Warehouse $model) {
                return optional($model->country)->name ?? '-';
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
        $request = request();
        $query = $model->newQuery()
            ->select([
                'id',
                'name',
                'code',
                'phone_number',
                'email',
                'city_id',
                'state_id',
                'country_id',
                'status',
                'start_date',
                'is_default',
                'created_at',
            ])
            ->with([
                'city:id,name',
                'state:id,name',
                'country:id,name',
            ]);

        // Apply server-side filters from AJAX
        if ($request->filled('status')) {
            $query->where('status', $request->get('status'));
        }

        if ($request->filled('date_from')) {
            $query->where('created_at', '>=', $request->get('date_from') . ' 00:00:00');
        }

        if ($request->filled('date_to')) {
            $query->where('created_at', '<=', $request->get('date_to') . ' 23:59:59');
        }

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
            // Use exact Bootstrap classes requested by UI
            $createButton[] = Button::make('create')->className('btn btn-success btn-xs btn-sm');
        }

        return $this->builder()
            ->setTableId('warehouse-table')
            ->columns($this->getColumns())
            ->minifiedAjax('', 'data.date_from = $("#warehouse-table-date-from").val(); data.date_to = $("#warehouse-table-date-to").val(); data.status = $("#warehouse-table-status-filter").val();')
            ->stateSave(false)
            ->responsive(false)
            ->autoWidth(false)
            ->parameters([
                'processing' => true,
                'serverSide' => true,
                'scrollX' => true,
                'deferRender' => true,
                'searchDelay' => 350,
                'drawCallback' => 'function() { KTMenu.createInstances(); }',
            ])
        // ->selectStyleSingle()
            ->addTableClass('align-middle table-row-dashed table-sm fs-7 gy-1 text-nowrap')
            ->dom("<'d-flex justify-content-between mb-3'B>rtip")
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
            Column::make('id')->title('S.No')->render('meta.row + meta.settings._iDisplayStart + 1;')->addClass('ps-0')->responsivePriority(1),
            Column::make('name')->title(__('Name'))->addClass('text-nowrap')->responsivePriority(2),
            Column::make('code')->title(__('Code'))->addClass('text-nowrap')->responsivePriority(3),
            Column::make('phone_number')->title(__('Phone'))->addClass('text-nowrap')->responsivePriority(10),
            Column::make('email')->addClass('text-nowrap')->responsivePriority(11),
            Column::make('city_id')->title(__('City'))->addClass('text-nowrap')->responsivePriority(12),
            Column::make('state_id')->title(__('State'))->addClass('text-nowrap')->responsivePriority(13),
            Column::make('country_id')->title(__('Country'))->addClass('text-nowrap')->responsivePriority(14),
            Column::make('status')->title(__('Active'))->addClass('text-nowrap')->responsivePriority(4),
            Column::make('start_date')->addClass('text-nowrap')->responsivePriority(5),
            Column::computed('defaultwarehouse')->title(__('Is Default'))->addClass('text-nowrap')->responsivePriority(6),
            Column::make('created_at')->title(__('Created At'))->addClass('text-nowrap')->responsivePriority(7),
            Column::computed('action')
                ->exportable(false)
                ->printable(false)
                ->addClass('text-center')
                ->responsivePriority(0),
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
