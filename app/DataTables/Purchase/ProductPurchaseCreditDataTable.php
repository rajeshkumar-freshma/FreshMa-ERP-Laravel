<?php

namespace App\DataTables\Purchase;

use App\Core\CommonComponent;
use App\Models\PurchaseOrder;
use Auth;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class ProductPurchaseCreditDataTable extends DataTable
{
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->rawColumns(['status', 'supplier_id', 'supplier_phone_number', 'action'])
            ->order(function ($model) {
                $model->orderBy('id', 'DESC');
            })
            ->addColumn('status', function (PurchaseOrder $model) {
                $indent_status = $model->status;
                return view('pages.partials.statuslabel', compact('indent_status'));
            })
            ->addColumn('supplier_id', function (PurchaseOrder $model) {
                if ($model->supplier != null) {
                    return @$model->supplier->first_name . '-' . @$model->supplier->last_name;
                } else {
                    return @$model->user_details->first_name . '-' . @$model->user_details->last_name;
                }
            })
            ->addColumn('supplier_phone_number', function (PurchaseOrder $model) {
                if ($model->supplier != null) {
                    return @$model->supplier->phone_number;
                } else {
                    return @$model->user_details->phone_number;
                }
            })
            ->editColumn('delivery_date', function (PurchaseOrder $model) {
                return CommonComponent::getDateFormat($model->delivery_date);
            })
            ->editColumn('created_at', function (PurchaseOrder $model) {
                return CommonComponent::getCreatedAtFormat($model->created_at);
            })
            ->addColumn('action', function (PurchaseOrder $model) {
                return view('pages.purchase.purchase_credit._action-menu', compact('model'));
            });
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\ProductPurchase $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(PurchaseOrder $model): QueryBuilder
    {
        if (Auth::guard('admin')->check()) {
            $query = $model->query()->whereIn('payment_status', [2, 3]);
            return $this->applyScopes($query);
        } else {
            $query = $model->query()->where([['supplier_id', Auth::user()->id]])->whereIn('payment_status', [2, 3]);
            return $this->applyScopes($query);
        }
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\DataTables\Html\Builder
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('productpurchase-table')
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
            ->addTableClass('align-middle table-striped table-row-dashed fs-6 gy-1')
            ->dom('Bfltip')
            ->buttons([
                // Button::make('create'),
                // Button::make('export'),
                // Button::make('print'),
                // Button::make('reset'),
                // Button::make('reload')
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
            Column::make('id')->title('S.No')->render('meta.row + meta.settings._iDisplayStart + 1;')->addClass('ps-0'),
            Column::make('supplier_id')->title(__('Supplier Name')),
            Column::make('supplier_phone_number')->title(__('Supplier Phone')),
            Column::make('purchase_order_number')->title(__('Purchase Order Number')),
            Column::make('delivery_date')->title(__('Delivery Date')),
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
        return 'ProductPurchaseCredit_' . date('YmdHis');
    }
}
