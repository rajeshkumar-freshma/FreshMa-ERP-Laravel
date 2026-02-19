<?php

namespace App\DataTables\Product;

use App\Core\CommonComponent;
use App\Models\WarehouseStockUpdate;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class StockManagementDataTable extends DataTable
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
            ->rawColumns(['status', 'image', 'image', 'action'])
            ->order(function ($model) {
                $model->orderBy('id', 'DESC');
            })
            ->addColumn('status', function (WarehouseStockUpdate $model) {
                $status = $model->status;
                return view('pages.partials.statuslabel', compact('status'));
            })
            ->addColumn('warehouse', function (WarehouseStockUpdate $model) {
                if ($model->warehouse != null) {
                    return $model->warehouse->name . ' - ' . $model->warehouse->code;
                } else {
                    return "-";
                }
            })
            ->addColumn('product_name', function (WarehouseStockUpdate $model) {
                if ($model->product != null) {
                    return $model->product->name;
                } else {
                    return "-";
                }
            })
            ->addColumn('image', function (WarehouseStockUpdate $model) {
                if ($model->product->image != null) {
                    $imageData = $model->product->image_full_url;
                    return view('pages.product.stock_management._action-menu', compact('imageData'));
                }
            })
            ->editColumn('stock_update_on', function (WarehouseStockUpdate $model) {
                return CommonComponent::getCreatedAtFormat($model->stock_update_on);
            })
            ->editColumn('created_at', function (WarehouseStockUpdate $model) {
                return CommonComponent::getCreatedAtFormat($model->created_at);
            })
            ->addColumn('action', function (WarehouseStockUpdate $model) {
                return view('pages.product.stock_management._action-menu', compact('model'));
            });
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\WarehouseStockUpdate $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(WarehouseStockUpdate $model): QueryBuilder
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
        return $this->builder()
            ->setTableId('stockmanagement-table')
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
            ->addTableClass('align-middle table-striped table-row-dashed fs-6 gy-1');
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
            Column::make('warehouse')->title(__('Warehouse')),
            Column::make('product_name')->title(__('Product Name')),
            Column::make('image')->title(__('Image')),
            Column::make('stock_update_on')->title(__('Stock Update On')),
            Column::make('total_stock'),
            Column::make('box_number'),
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
        return 'StockManagement_' . date('YmdHis');
    }
}
