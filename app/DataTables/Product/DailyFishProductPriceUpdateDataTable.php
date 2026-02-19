<?php

namespace App\DataTables\Product;

use App\Core\CommonComponent;
use App\Models\ProductPrice;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class DailyFishProductPriceUpdateDataTable extends DataTable
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
            ->rawColumns(['status', 'action', 'store_id', 'product_id', 'created_at', 'price_update_date'])
            ->editColumn('store_id', function (ProductPrice $model) {
                return $model->store_details->store_name ?? '';
            })
            ->editColumn('product_id', function (ProductPrice $model) {
                return $model->product_details->name ?? '';
            })
            ->editColumn('created_at', function (ProductPrice $model) {
                if (isset($model->created_at)) {
                    return CommonComponent::getCreatedAtFormat($model->created_at);
                }
            })
            ->editColumn('price_update_date', function (ProductPrice $model) {

                // return    Carbon::parse($model->price_update_dates)->format('Y-m-d');
                return CommonComponent::getDateFormat($model->price_update_date);
                //    return $date = Carbon::createFromFormat('Y-m-d', $model->price_update_date)->format('d-m-Y');
            })
            ->addColumn('status', function (ProductPrice $model) {
                $status = $model->status;
                return view('pages.partials.statuslabel', compact('status'));
            })
            ->addColumn('action', function (ProductPrice $model) {
                return view('pages.product.daily_fish_price_update._action-menu', compact('model'));
            });
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\ProductPrice $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(ProductPrice $model)
    {
        $query = $model->newQueryWithoutScopes(); // Disable soft deletes for this query

        $filters = [
            'price_update_date' => $this->request()->input('date'),
            'store_id' => $this->request()->input('store_id'),
            'product_id' => $this->request()->input('product_id'),
        ];

        // Check if any filter parameters are provided in the request
        if ($this->request()->hasAny(['date', 'store_id', 'product_id'])) {
            Log::info("Applying filters");

            // Apply filters if any are provided
            $query->from('product_price_histories')
                ->where(function ($query) use ($filters) {
                    foreach ($filters as $key => $value) {
                        if ($value) {
                            $query->where($key, $value);
                        }
                    }
                });
        }

        // Apply any additional scopes or modifications
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
        if (Auth::check() && Auth::user()->can('Daily Product Price Update Create')) {
            $createButton[] = Button::make('create');
        }

        return $this->builder()
            ->setTableId('daily_price_updates-table')
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
            Column::make('product_id')->title(__('Product')),
            Column::make('store_id')->title(__('Store')),
            Column::make('price'),
            Column::make('price_update_date'),
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
