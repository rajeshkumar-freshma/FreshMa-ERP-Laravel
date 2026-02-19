<?php

namespace App\DataTables\Report;

use App\Models\Product;
use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderDetail;
use App\Models\Supplier;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class SupplierWisePurchaseOrderReportDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder $query Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->rawColumns(['first_name', 'sales_type', 'warehouse_id', 'store_id', 'vendor_id'])
            ->addColumn('first_name', function (Supplier $model) {
                return $model->first_name;
            })
            ->addColumn('phone_number', function (Supplier $model) {
                return $model->phone_number ?? '';
            })
            ->addColumn('total_amount', function (Supplier $supplier) {
                return $supplier->purchase_order->sum('total');
            })
            ->addColumn('total_orders', function (Supplier $supplier) {
                return $supplier->purchase_order->count();
            })
            ->addColumn('paid_amount', function (Supplier $supplier) {
                // Calculate the total amount paid for purchase orders of this supplier
                return $supplier->purchase_order->sum(function ($order) {
                    return $order->purchase_order_transactions->sum('amount');
                });
            })
            ->addColumn('pending_amount', function (Supplier $supplier) {
                // Calculate the pending amount for this supplier
                return $supplier->purchase_order->sum('total') - $supplier->purchase_order->sum(function ($order) {
                    return $order->purchase_order_transactions->sum('amount');
                });
            })
            ->addColumn('action', function (Supplier $model) {
                return view('pages.report.supplier_wise_purchase_report._action-menu', compact('model'));
            });
    }


    /**
     * Get the query source of dataTable.
     */
    public function query(Supplier $model): QueryBuilder
    {
        $query = $model->newQuery();

        // Check if ID is provided
        if ($this->request()->id != null) {
            $query->where('id', $this->request()->id);
        }

        // Check if supplier ID is provided
        if ($this->request()->supplier_id != null) {
            Log::info("Supplier Already");
            Log::info($this->request()->supplier_id);
            $query->where('id', $this->request()->supplier_id);
        }

        // Extract from_date and to_date
        $fromDate = $this->request()->from_date;
        $toDate = $this->request()->to_date;

        // Log the query before applying date range filtering
        Log::info("Before date range filtering: ");
        Log::info($query->get());

        // Eager load purchase orders with their transactions and apply date range filtering
        $query->with([
            'purchase_order' => function ($query) use ($fromDate, $toDate) {
                if ($fromDate && $toDate) {
                    $query->whereBetween('delivery_date', [$fromDate, $toDate]);
                }
                $query->with('purchase_order_transactions');
            }
        ]);

        // Log the query after applying date range filtering
        Log::info("After date range filtering: ");
        Log::info($query->get());

        return $query;
    }


    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        $createButton = [];

        // Check if the user is authenticated and has permission to create
        if (Auth::check() &&  Auth::user()->can('Supplier Wise Purchase Report Download')) {
            $createButton[] = Button::make([
                'excel',
                'csv',
                'print',
            ]);


        }

        return $this->builder()
            ->setTableId('supplierpurchaseorderreport-table')
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
            Column::make('first_name')->title(__('First Name')),
            Column::make('phone_number'),
            Column::make('total_orders'),
            Column::make('total_amount'),
            Column::make('paid_amount'),
            Column::make('pending_amount'),
            // Column::computed('action')
            //     ->exportable(false)
            //     ->printable(false)
            //     ->addClass('text-center')
            //     ->responsivePriority(-1)
        ];
    }

    /**
     * Get the filename for export.
     */
    protected function filename(): string
    {
        return 'SupplierPurchaseOrderReport_' . date('YmdHis');
    }
}
