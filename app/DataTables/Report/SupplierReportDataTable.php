<?php

namespace App\DataTables\Report;

use App\Core\CommonComponent;
use App\Models\PaymentTransaction;
use App\Models\PaymentType;
use App\Models\PurchaseOrder;
use Carbon\Doctrine\CarbonType;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class SupplierReportDataTable extends DataTable
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
            // ... (existing code)
            ->rawColumns(['type', 'transaction_type', 'payment_type_id', 'action'])
            ->editColumn('transaction_datetime', function (PaymentTransaction $model) {
                $transaction_datetime = $model->transaction_datetime;
                return CommonComponent::getCreatedAtFormat($transaction_datetime);
            })
            ->editColumn('transaction_number', function (PaymentTransaction $model) {
                return $model->transaction_number;
            })
            ->addColumn('purchase_order_number', function (PaymentTransaction $model) {
                return $model->purchase_order->purchase_order_number ?? ''; // Replace 'order_number' with the actual purchase order property you want to display
            })

            ->editColumn('type', function (PaymentTransaction $model) {
                $type = $model->type;
                return view('pages.partials.statuslabel', compact('type'));
            })

            ->editColumn('transaction_type', function (PaymentTransaction $model) {
                $paymentTransactionType = $model->transaction_type;
                return view('pages.partials.statuslabel', compact('paymentTransactionType'));
            })

            ->editColumn('payment_type_id', function (PaymentTransaction $model) {
                $paymentType = $model->paymentType; // Assuming your relationship is named 'paymentType'
                $paymentTypeName = $paymentType ? $paymentType->payment_type : '';
                return $paymentTypeName;
            })
            ->addColumn('user', function (PaymentTransaction $model) {
                // Access the purchase_order relationship and chain it to supplier
                $supplierFirstName = optional($model->purchase_order)->user_details->first_name ?? '-';
                return $supplierFirstName ?? '';
            })

            ->addColumn('purchase_total_amount', function (PaymentTransaction $model) {
                // Access the purchase_order relationship and chain it to supplier
                $purchaseTotalAmount = optional($model->purchase_order)->total;
                return $purchaseTotalAmount ?? '';
            })

            ->addColumn('action', function (PaymentTransaction $model) {
                return view('pages.report.supplier_report._action-menu', compact('model'));
            });
    }


    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\PaymentTransaction $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(PaymentTransaction $model): QueryBuilder
    {
        $query = $model->newQuery();

        // Filter by transaction ID
        if ($id = $this->request()->id) {
            $query->where('id', $id);
        }

        // Filter by transaction type
        $query->where('transaction_type', 1);

        // Include the 'purchase_order' relationship from the PaymentTransaction model
        $query->with(['purchase_order.user_details']);

        // Filter by transaction datetime range
        $transaction_from_date = $this->request()->input('transaction_from_date');
        $transaction_to_date = $this->request()->input('transaction_to_date');

        if ($transaction_from_date && $transaction_to_date) {
            $query->whereBetween('transaction_datetime', [
                $transaction_from_date,
                $transaction_to_date,
            ]);
        } elseif ($transaction_from_date) {
            $query->where('transaction_datetime', '>=', $transaction_from_date);
        } elseif ($transaction_to_date) {
            $query->where('transaction_datetime', '<=', $transaction_to_date);
        }

        // Filter by purchase datetime range
        $purchase_from_date = $this->request()->input('purchase_from_date');
        $purchase_to_date = $this->request()->input('purchase_to_date');

        if ($purchase_from_date && $purchase_to_date) {
            $query->whereHas('purchase_order', function ($purchaseOrderQuery) use ($purchase_from_date, $purchase_to_date) {
                $purchaseOrderQuery->whereBetween('delivery_date', [
                    $purchase_from_date,
                    $purchase_to_date
                ]);
            });
        } elseif ($purchase_from_date) {
            $query->whereHas('purchase_order', function ($purchaseOrderQuery) use ($purchase_from_date) {
                $purchaseOrderQuery->where('delivery_date', '>=', $purchase_from_date);
            });
        } elseif ($purchase_to_date) {
            $query->whereHas('purchase_order', function ($purchaseOrderQuery) use ($purchase_to_date) {
                $purchaseOrderQuery->where('delivery_date', '<=', $purchase_to_date);
            });
        }

        // Filter by purchase order number
        if ($purchaseOrderNumber = $this->request()->purchase_number) {
            $query->whereHas('purchase_order', function ($purchaseOrderQuery) use ($purchaseOrderNumber) {
                $purchaseOrderQuery->where('purchase_order_number', $purchaseOrderNumber);
            });
        }

        // Filter by supplier ID
        if ($supplier = $this->request()->supplier_id) {
            $query->whereHas('purchase_order', function ($purchaseOrderQuery) use ($supplier) {
                $purchaseOrderQuery->where('supplier_id', $supplier);
            });
        }

        return $query;
    }




    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('suppliers-table')
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
                Button::make('excel'),
                Button::make('csv'),
                // Button::make('pdf'),
                Button::make('print'),
                // Button::make('reset'),
                // Button::make('reload')
            ]);
    }

    /**
     * Get the dataTable columns definition.
     */
    public function getColumns(): array
    {
        return [
            Column::make('id')->title('S.No')->render('meta.row + meta.settings._iDisplayStart + 1;')->addClass('ps-0'),
            Column::make('user')->title(__('Supplier Number')),
            Column::make('transaction_datetime'),
            Column::make('transaction_number'),
            Column::make('transaction_type'),
            Column::make('purchase_order_number')->title(__('Purchase Number')),
            Column::make('purchase_total_amount')->title(__('Purchase Amount')),
            Column::make('payment_type_id')->title(__('Payment Type')),
            Column::make('type'),
            Column::make('amount'),
            Column::computed('action')
                ->exportable(false)
                ->printable(false)
                ->addClass('text-center')
                ->responsivePriority(-1)
            // Column::make('properties')->addClass('none'),
        ];
    }

    /**
     * Get the filename for export.
     */
    protected function filename(): string
    {
        return 'SuppliersReport_' . date('YmdHis');
    }
}
