<?php

namespace App\DataTables\Product;

use App\Core\CommonComponent;
use App\Models\ProductBulkTransfer;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class BulkProductTransferDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder $query Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        // $queryData =  (new EloquentDataTable($query))
        //     ->addColumn('product_name', function (Product $product) {
        //         $html = '<input type="hidden" class="form-control" name="product_transfer[product][]" id="'.$product->id.'"  value="'.$product->id.'" data-id="' . $product->name . '">';
        //         return $html .= '<div data-id="' . $product->id . '">' . $product->name . '</div>';
        //     });

        // $stores = Store::active()->get();
        // foreach ($stores as $key => $store) {
        //     if ($key == 0) {
        //         $headers[] = 'product_name';
        //     }
        //     $headers[] = str_replace(" ", "_", $store->store_name);
        //     $queryData = $queryData->addColumn(str_replace(" ", "_", $store->store_name), function (Product $product) use ($store, $key) {
        //         return '<input contenteditable="true" class="editable form-control form-control-sm" name="product_transfer[][$store->id][$product->id]" id="'.$store->id.$product->id.'"  value="0" data-id="' . $store->id.$product->id . '">';
        //     });
        // }
        // $queryData->rawColumns($headers);
        // return $queryData;

        return (new EloquentDataTable($query))
            ->rawColumns(['status', 'transfer_from', 'transfer_to', 'action'])
            ->order(function ($model) {
                $model->orderBy('id', 'DESC');
            })
            ->addColumn('status', function (ProductBulkTransfer $model) {
                $indent_status = $model->status;
                return view('pages.partials.statuslabel', compact('indent_status'));
            })
            ->addColumn('transfer_from', function (ProductBulkTransfer $model) {
                $transfer_data = $model;
                $type = 'transfer_from';
                return view('pages.product.bulk_product_transfer._action-menu', compact('transfer_data', 'type'));
            })
            ->editColumn('transfer_created_date', function (ProductBulkTransfer $model) {
                return CommonComponent::getDateFormat($model->transfer_created_date);
            })
            ->editColumn('created_at', function (ProductBulkTransfer $model) {
                return CommonComponent::getCreatedAtFormat($model->created_at);
            })
            ->addColumn('action', function (ProductBulkTransfer $model) {
                return view('pages.product.bulk_product_transfer._action-menu', compact('model'));
            });
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(ProductBulkTransfer $model): QueryBuilder
    {
        $query = $model->newQuery();
        return $this->applyScopes($query);
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        $createButton = [];

        // Check if the user is authenticated and has permission to create
        if (Auth::check() && Auth::user()->can('Bulk Product Transfer Create')) {
            $createButton[] = Button::make('create');

        }

        return $this->builder()
            ->setTableId('producttransfer-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->dom('Bfrtip')
        // ->orderBy(1)
        // ->buttons(
        //     Button::make('create'),
        //     // Button::make('reload')
        // );
            ->buttons($createButton);
    }

    /**
     * Get the dataTable columns definition.
     */
    public function getColumns(): array
    {
        // $stores = Store::active()->get();
        // foreach ($stores as $key => $store) {
        //     if ($key == 0) {
        //         $headers[] = Column::make('product_name')->title(__('Product/Branch'));
        //     }
        //     $headers[] = Column::make(str_replace(" ", "_", $store->store_name))->title(__($store->store_name));
        // }
        // return $headers;
        return [
            Column::make('id')->title('S.No')->render('meta.row + meta.settings._iDisplayStart + 1;')->addClass('ps-0'),
            Column::make('transfer_order_number')->title(__('Transfer Order Number')),
            Column::make('transfer_from')->title(__('Transfer From')),
            Column::make('transfer_created_date')->title(__('Transfer Created Date')),
            Column::make('status'),
            Column::make('created_at'),
            Column::computed('action')
                ->exportable(false)
                ->printable(false)
                ->width(60)
                ->addClass('text-center'),
        ];
    }

    /**
     * Get the filename for export.
     */
    protected function filename(): string
    {
        return 'ProductTransfer_' . date('YmdHis');
    }
}
