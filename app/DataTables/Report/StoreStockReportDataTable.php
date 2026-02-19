<?php

namespace App\DataTables\Report;

use App\Core\CommonComponent;
use App\Models\Product;
use App\Models\Store;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class StoreStockReportDataTable extends DataTable
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
            ->editColumn('name', function (Store $model) {
                return $model->name;
            })
            ->addColumn('branch', function (Store $model) {
                // You need to fetch branch names here
                return $model->storeStockInventory->pluck('store_id')->implode(', ');
            })
            ->editColumn('product_stock', function (Store $model) {
                // Assuming you have a relationship setup between Product and StoreInventoryDetail
                // Fetch product stock for each store/branch
                return $model->storeStockInventory->pluck('weight')->toArray();
            });
    }
    // public function dataTable(QueryBuilder $query): EloquentDataTable
    // {
    //     return (new EloquentDataTable($query))
    //         ->editColumn('name', function (Product $model) {
    //             return $model->name;
    //         })
    //         ->addColumn('product_stock', function (Product $model) {
    //             $storeStocks = [];
    //             foreach (Store::all() as $store) {
    //                 $storeStock = $model->storeStockInventory->where('store_id', $store->id)->first();
    //                 $storeStocks[$store->id] = $storeStock ? $storeStock->weight : 0;
    //             }
    //             return $storeStocks;
    //         });
    // }


    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\Store $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(Store $model): QueryBuilder
    {
        // return $model->newQuery();
        $query = $model->with([
            'store_stock_update' => function ($subQuery) {
                $subQuery->select(
                    'store_id',
                    'product_id',
                    DB::raw('SUM(total_stock) as total_stock_sum')
                )
                    ->groupBy('store_id', 'product_id'); // Group by both store and product
            }
        ]);

        $stores = $query->get();

        // Now you have stores with their respective product-wise total stock sum

        // If you want to further group it by product, you can do it like this
         $storesGroupedByProduct = $stores->groupBy(function ($store) {
            return $store->store_stock_update->map(function ($stock) {
                return $stock->product_id;
            });
        });

        return $storesGroupedByProduct->newQuery();

        // return $this->applyScopes($query);
    }


    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\DataTables\Html\Builder
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('product-table')
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
            ->buttons([
                Button::make('create'),
            ]);
    }

    /**
     * Get the dataTable columns definition.
     *
     * @return array
     */
    public function getColumns(): array
    {
        return [
            Column::make('name')->title(__('Name')),
            Column::make('branch')->title(__('Branch')),
            Column::make('product_stock')->title(__('Product Stock'))->addClass('text-center'),
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename(): string
    {
        return 'Product_' . date('YmdHis');
    }
}
