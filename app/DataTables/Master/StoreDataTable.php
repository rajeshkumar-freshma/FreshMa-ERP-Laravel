<?php

namespace App\DataTables\Master;

use App\Core\CommonComponent;
use App\Models\Store;
use Auth;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class StoreDataTable extends DataTable
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
            ->rawColumns(['status', 'action'])
            ->order(function ($model) {
                $model->orderBy('id', 'DESC');
            })
            ->addColumn('status', function (Store $model) {
                return view('pages.partials.status_toggle_master', ['model' => $model, 'entity' => 'store']);
            })
            ->editColumn('created_at', function (Store $model) {
                return CommonComponent::getCreatedAtFormat($model->created_at);
            })
            ->editColumn('city_id', function (Store $model) {
                return optional($model->city)->name ?? '-';
            })
            ->editColumn('state_id', function (Store $model) {
                return optional($model->state)->name ?? '-';
            })
            ->editColumn('country_id', function (Store $model) {
                return optional($model->country)->name ?? '-';
            })
            ->addColumn('action', function (Store $model) {
                return view('pages.master.store._action-menu', compact('model'));
            });
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\Store $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(Store $model): QueryBuilder
    {
        $query = $model->newQuery()->with(['city', 'state', 'country']);

        if ($this->request()->filled('status')) {
            $query->where('status', $this->request()->get('status'));
        }

        if ($this->request()->filled('date_from')) {
            $query->where('created_at', '>=', $this->request()->get('date_from') . ' 00:00:00');
        }

        if ($this->request()->filled('date_to')) {
            $query->where('created_at', '<=', $this->request()->get('date_to') . ' 23:59:59');
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
        if (Auth::check() && Auth::user()->can('Store Create')) {
            $createButton[] = Button::make('create')->className('btn btn-success btn-xs btn-sm');

        }

        return $this->builder()
            ->setTableId('store-table')
            ->columns($this->getColumns())
            ->minifiedAjax('', 'data.date_from = $("#store-table-date-from").val(); data.date_to = $("#store-table-date-to").val(); data.status = $("#store-table-status-filter").val();')
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
            ->addTableClass('align-middle table-row-dashed table-sm fs-7 gy-1 text-nowrap')
            ->dom("<'d-flex justify-content-between mb-3'B>rtip")
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
            Column::make('store_name')->title(__('Name')),
            Column::make('store_code')->title(__('Code')),
            Column::make('phone_number')->title(__('Phone Number')),
            Column::make('email'),
            Column::make('city_id')->title(__('City')),
            Column::make('state_id')->title(__('State')),
            Column::make('country_id')->title(__('Country')),
            Column::make('status')->title(__('Active')),
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
        return 'Store_' . date('YmdHis');
    }
}



