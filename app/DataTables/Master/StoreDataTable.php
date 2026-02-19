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
            ->addColumn('action', 'status')
            ->order(function ($model) {
                $model->orderBy('id', 'DESC');
            })
            ->addColumn('status', function (Store $model) {
                $status = $model->status;
                return view('pages.partials.statuslabel', compact('status'));
            })
            ->editColumn('created_at', function (Store $model) {
                return CommonComponent::getCreatedAtFormat($model->created_at);
            })
            ->editColumn('city_id', function (Store $model) {
                return $model->city->name;
            })
            ->editColumn('state_id', function (Store $model) {
                return $model->state->name;
            })
            ->editColumn('country_id', function (Store $model) {
                return $model->country->name;
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
        if (Auth::check() && Auth::user()->can('Store Create')) {
            $createButton[] = Button::make('create');

        }

        return $this->builder()
            ->setTableId('store-table')
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
            Column::make('status'),
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
