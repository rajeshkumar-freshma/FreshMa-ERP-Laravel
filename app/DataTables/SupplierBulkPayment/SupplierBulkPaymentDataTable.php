<?php

namespace App\DataTables\SupplierBulkPayment;

use App\Core\CommonComponent;
use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderMultiTransaction;
use Auth;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class SupplierBulkPaymentDataTable extends DataTable
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
            // ->editColumn('purchase_order_id', function (PurchaseOrderMultiTransaction $model) {
            //     return $model->purchaseOrder->purchase_order_number;
            // })
            ->editColumn('supplier_id', function (PurchaseOrderMultiTransaction $model) {
                $supplierName = $model->supplier ? $model->supplier->name : '';
                $userCode = $model->supplier ? $model->supplier->user_code : '';
                return $supplierName . " - " . $userCode;
            })

            ->addColumn('payment_type_id', function (PurchaseOrderMultiTransaction $model) {
                return $model->paymentType->payment_type??'';
            })
            ->editColumn('advance_amount_included', function (PurchaseOrderMultiTransaction $model) {
                return $model->advance_amount_included == 1 ? 'Yes' : 'No';
            })
            ->editColumn('transaction_date', function (PurchaseOrderMultiTransaction $model) {
                return CommonComponent::getCreatedAtFormat($model->transaction_date);
            })
            ->editColumn('created_at', function (PurchaseOrderMultiTransaction $model) {
                return CommonComponent::getCreatedAtFormat($model->created_at);
            })
            ->addColumn('action', function (PurchaseOrderMultiTransaction $model) {
                return view('pages.supplier_bulk_payment._action-menu', compact('model'));
            });
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\PurchaseOrderMultiTransaction $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(PurchaseOrderMultiTransaction $model): QueryBuilder
    {
        if (Auth::guard('admin')->check()) {
            return $model->query();
        } else {
            return $model->query()->where([['supplier_id', Auth::user()->id]]);
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
            ->setTableId('Purchase_order_multi_transactions-table')
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
            ->dom('Bltip')
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
            Column::make('id')->title('S.No')->render('meta.row + meta.settings._iDisplayStart + 1;')->addClass('ps-0'),
            // Column::make('purchase_order_id')->title(__('Purchase Order Number')),
            Column::make('supplier_id')->title(__('Supplier Name')),
            Column::make('amount'),
            Column::make('advance_amount_included'),
            Column::make('advance_amount'),
            Column::make('payment_type_id')->title(__('Payment Type')),
            Column::make('transaction_date'),
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
        return 'PurchaseOrderMultiTransaction_' . date('YmdHis');
    }
}
