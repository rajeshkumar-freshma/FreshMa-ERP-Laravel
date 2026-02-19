<?php

namespace App\DataTables\Payment;

use App\Core\CommonComponent;
use App\Models\PaymentTransaction;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class PaymentTransactionsReportDataTable extends DataTable
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
            ->rawColumns(['type', 'transaction_type', 'payment_type_id', 'action'])
            ->order(function ($model) {
                $model->orderBy('id', 'DESC');
            })
            ->editColumn('transaction_datetime', function (PaymentTransaction $model) {
                return CommonComponent::getCreatedAtFormat($model->transaction_datetime);
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
                return $paymentType = @$model->payment_type_details->payment_type;
            })
            ->addColumn('action', function (PaymentTransaction $model) {
                return view('pages.payment.transactions_report._action-menu', compact('model'));
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

        // // Check if supplier_id is provided in the request
        // if ($this->request()->has('supplier_id')) {
        //     //     $supplierId = $this->request()->input('supplier_id');

        //     //     // Retrieve purchase order IDs related to the supplier
        //     //     $purchaseOrderIds = $model->newQuery()
        //     //         ->whereHas('new_purchase_order', function ($query) use ($supplierId) {
        //     //             $query->where('supplier_id', $supplierId);
        //     //         })
        //     //         ->pluck('id')
        //     //         ->toArray();

        //     //     // Filter payment transactions related to these purchase orders
        //     //     $query->whereIn('id', $purchaseOrderIds);
        //     // }
        //     // Check if supplier_id is provided in the request
        //     // if ($this->request()->has('supplier_id')) {
        //     //     $supplierId = $this->request()->input('supplier_id');

        //     //     // // Retrieve payment transactions related to the supplier's purchase orders
        //     //     // $query->whereHas('new_purchase_order', function ($query) use ($supplierId) {
        //     //     //     $query->where('supplier_id', $supplierId);
        //     //     // });
        //     // }
        //     // Retrieve payment transactions with associated purchase orders filtered by supplier ID
        //     $paymentTransactions = PurchaseOrder::where('supplier_id', $this->request()->has('supplier_id'))->with([
        //         'purchase_order_transactions'
        //         // => function ($query) use ($supplierId) {
        //         //     // $query->select(
        //         //     //     'purchase_order_transactions.'
        //         //     // )
        //         //     ;
        //         // }
        //     ])->get();
        //     $reference = [];
        //     foreach ($paymentTransactions->purchase_order_transactions as $item) {
        //         $reference_id = $item->refernce_id;
        //         return $reference[] = $reference_id;
        //     }
        //     $query->whereIn(['supplier_id', $reference]);
        // }

        //in below filter comes in another form request datas results get

        if ($this->request()->has('supplier_id')) {
            $supplierId = $this->request()->input('supplier_id');
            $paymentTransactions = $query->where('transaction_type', 1)->where('type', 2)->with([
                'new_purchase_order' => function ($subQuery) use ($supplierId) {

                    $subQuery->where('supplier_id', $supplierId);
                },
            ])
                ->get();

            // Extract reference IDs from purchase order transactions
            $purchase_id = [];
            foreach ($paymentTransactions as $transactionOrder) {
                foreach ($transactionOrder->new_purchase_order as $purchase) {
                    $purchase_id[] = $purchase->id;
                }
            }

            // Filter payment transactions by reference IDs
            $query->whereIn('reference_id', $purchase_id);
        }
        // Filter by date range
        // if ($this->request()->from_date !== null && $this->request()->to_date !== null) {
        //     $fromDate = Carbon::parse($this->request()->input('from_date'))->startOfDay();
        //     $toDate = Carbon::parse($this->request()->input('to_date'))->endOfDay();

        //     $query = $query->whereBetween('transaction_datetime', [$fromDate, $toDate]);
        // }
        $from_date = $this->request()->input('from_date');
        $to_date = $this->request()->input('to_date');
        if ($from_date || $to_date) {
            if ($from_date && $to_date) {
                $query->whereBetween('transaction_datetime', [$from_date, $to_date]);
            } elseif ($from_date) {
                $query->whereDate('transaction_datetime', $from_date);
            } elseif ($to_date) {
                $query->whereDate('transaction_datetime', $to_date);
            }
        }

        if ($this->request()->transaction_type != null) {
            $query = $query->where('transaction_type', $this->request()->transaction_type);
        }
        if ($this->request()->type != null) {
            $query = $query->where('type', $this->request()->type);
        }
        if ($this->request()->payment_type_id != null) {
            $query = $query->where('payment_type_id', $this->request()->payment_type_id);
        }

        return $query;
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        $createButton = [];

        // Check if the user is authenticated and has permission to create
        if (Auth::check() && Auth::user()->can('Transactions Download')) {
            $createButton[] = Button::make([
                'excel',
                'csv',
                'print',
            ]);
        }

        return $this->builder()
            ->setTableId('payment_transactions-table')
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
            ->buttons($createButton);
        // ->buttons([
        //     Button::make('excel'),
        //     Button::make('csv'),
        //     // Button::make('pdf'),
        //     Button::make('print'),
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
            Column::make('transaction_datetime'),
            Column::make('transaction_number'),
            Column::make('transaction_type'),
            Column::make('amount'),
            Column::make('payment_type_id')->title(__('Payment Type')),
            Column::make('type'),
            Column::computed('action')
                ->exportable(false)
                ->printable(false)
                ->addClass('text-center')
                ->responsivePriority(-1),
            // Column::make('properties')->addClass('none'),
        ];
    }

    /**
     * Get the filename for export.
     */
    protected function filename(): string
    {
        return 'PymentTransactionsReport_' . date('YmdHis');
    }
}
