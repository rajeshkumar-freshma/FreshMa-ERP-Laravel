<?php

namespace App\DataTables\Product;

use App\Core\CommonComponent;
use App\Models\FishCutting;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class FishCuttingDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder $query Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->rawColumns(['status', 'action'])
            ->order(function ($model) {
                $model->orderBy('id', 'ASC');
            })
            ->editColumn('name', function (FishCutting $model) {
                return $model->product->name;
            })
            ->editColumn('store_id', function (FishCutting $model) {
                return $model->store->store_name;
            })
            ->addColumn('status', function (FishCutting $model) {
                $status = $model->status;
                return view('pages.partials.statuslabel', compact('status'));
            })
            ->editColumn('cutting_date', function (FishCutting $model) {
                return CommonComponent::getDateFormat($model->cutting_date);
            })
            ->editColumn('created_at', function (FishCutting $model) {
                return CommonComponent::getCreatedAtFormat($model->created_at);
            })
            ->addColumn('action', function (FishCutting $model) {
                return view('pages.product.fish_cutting._action-menu', compact('model'));
            });
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(FishCutting $model): QueryBuilder
    {
        $query = $model->newQuery();
        return $this->applyScopes($query);
        // return $model->newQuery()->orderBy('id', 'DESC');
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {

        $createButton = [];

        // Check if the user is authenticated and has permission to create
        if (Auth::check() && Auth::user()->can('Fish Cutting Create')) {
            $createButton[] = Button::make('create');

        }

        return $this->builder()
            ->setTableId('fishcutting-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->stateSave(false)
            ->responsive()
            ->autoWidth(true)
            ->parameters([
                'scrollX' => true,
                'drawCallback' => 'function() { KTMenu.createInstances(); }',
            ])
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
     */
    public function getColumns(): array
    {
        return [
            Column::make('id')->title('S.No')->render('meta.row + meta.settings._iDisplayStart + 1;')->addClass('ps-0'),
            Column::make('name')->title(__('Product Name')),
            Column::make('store_id')->title(__('Store')),
            Column::make('weight'),
            Column::make('cutting_date'),
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
     * Get the filename for export.
     */
    protected function filename(): string
    {
        return 'FishCutting_' . date('YmdHis');
    }
}
