<?php

namespace App\DataTables\Product;

use App\Core\CommonComponent;
use App\Models\ProductTransfer;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class ProductTransferDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder $query Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->rawColumns(['status', 'store_indent_request_id', 'transfer_from', 'transfer_to', 'action'])
            ->order(function ($model) {
                $model->orderBy('id', 'DESC');
            })
            ->addColumn('status', function (ProductTransfer $model) {
                $status = $model->status;
                return view('pages.partials.statuslabel', compact('status'));
            })
            ->addColumn('transfer_from', function (ProductTransfer $model) {
                $transfer_data = $model;
                $type = 'transfer_from';
                return view('pages.product.product_transfer._action-menu', compact('transfer_data', 'type'));
            })
            ->addColumn('transfer_to', function (ProductTransfer $model) {
                $transfer_data = $model;
                $type = 'transfer_to';
                return view('pages.product.product_transfer._action-menu', compact('transfer_data', 'type'));
            })
            ->addColumn('status', function (ProductTransfer $model) {
                $indent_status = $model->status;
                return view('pages.partials.statuslabel', compact('indent_status'));
            })
            ->addColumn('store_indent_request_id', function (ProductTransfer $model) {
                if ($model->store_indent_request_id == null) {
                    return $model->store_indent_request;
                } else {
                    return null;
                }
            })
            ->editColumn('created_at', function (ProductTransfer $model) {
                return CommonComponent::getCreatedAtFormat($model->created_at);
            })
            ->addColumn('action', function (ProductTransfer $model) {
                return view('pages.product.product_transfer._action-menu', compact('model'));
            });
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(ProductTransfer $model): QueryBuilder
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
        if (Auth::check() && Auth::user()->can('Product Transfer Create')) {
            $createButton[] = Button::make('create');

        }

        return $this->builder()
            ->setTableId('producttransfer-table')
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
            Column::make('transfer_order_number')->title(__('Transfer Order Number')),
            Column::make('transfer_from')->title(__('Transfer From')),
            Column::make('transfer_to')->title(__('Transfer To')),
            Column::make('status')->title(value: __('Status')),
            Column::make('store_indent_request_id')->title(__('Store Indent Request Code')) ?? '-',
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
