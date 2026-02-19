<?php

namespace App\DataTables\Store;

use App\Core\CommonComponent;
use App\Models\StoreStockDailyUpdate;
use Spatie\Activitylog\Models\Activity;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Illuminate\Log\Logger;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;

class DailyStoreStockUpdateDataTable extends DataTable
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
            ->rawColumns(['status', 'action', 'store_id', 'product_id', 'created_at', 'stock_update_on'])
            ->editColumn('store_id', function (StoreStockDailyUpdate $model) {
                return $model->store_details->store_name ?? '';
            })
            ->editColumn('product_id', function (StoreStockDailyUpdate $model) {
                return $model->product_details->name ?? '';
            })
            ->editColumn('created_at', function (StoreStockDailyUpdate $model) {
                return CommonComponent::getCreatedAtFormat($model->created_at);
            })
            ->editColumn('stock_update_on', function (StoreStockDailyUpdate $model) {
                return CommonComponent::getCreatedAtFormat($model->stock_update_on);
            })
            ->addColumn('status', function (StoreStockDailyUpdate $model) {
                $status = $model->status;
                return view('pages.partials.statuslabel', compact('status'));
            })
            ->addColumn('action', function (StoreStockDailyUpdate $model) {
                return view('pages.store.daily_stock_updated._action-menu', compact('model'));
            });;
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\StoreStockDailyUpdate $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(StoreStockDailyUpdate $model)
    {
        $model = $model->newQuery()->whereDate('stock_update_on', today());

        $filters = [
            'stock_update_on' => $this->request()->input('date'),
            'store_id' => $this->request()->input('store_id'),
            'product_id' => $this->request()->input('product_id')
        ];

        // Check if any filter parameters are provided in the request
        if ($this->request()->hasAny(['date', 'store_id', 'product_id'])) {
            Log::info("Applying filters");

            // Apply filters if any are provided
            $model->where(function ($query) use ($filters) {
                foreach ($filters as $key => $value) {
                    if ($value) {
                        $query->where($key, $value);
                    }
                }
            });
        }

        return $this->applyScopes($model);
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
        if (Auth::check() &&  Auth::user()->can('Daily Stock Update Create')) {
            $createButton[] = Button::make('create');
        }
        return $this->builder()
            ->setTableId('store_stock_daily_updates-table')
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
            Column::make('store_id')->title(__('Store')),
            Column::make('product_id')->title(__('Product')),
            Column::make('stock_update_on'),
            Column::make('opening_stock'),
            Column::make('closing_stock'),
            Column::make('usage_stock'),
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
        return 'StoreStockDailyUpdate_' . date('YmdHis');
    }
}
