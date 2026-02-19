<?php

namespace App\DataTables\Report;

use App\Models\Product;
use App\Models\SalesOrder;
use App\Models\SalesOrderDetail;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Illuminate\Support\Carbon;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class ReturnReportDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder $query Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->rawColumns(['sales_from', 'sales_type', 'warehouse_id', 'store_id', 'vendor_id'])
            ->addColumn('sales_from', function (SalesOrder $model) {
                return view('pages.report.sales_order_report.sales_orders_datas', [
                    'sales_from' => $model->sales_from,
                ]);
            })
            ->addColumn('sales_type', function (SalesOrder $model) {
                return view('pages.report.sales_order_report.sales_orders_datas', [
                    'sales_type' => $model->sales_type,
                ]);
            })
            ->addColumn('warehouse_id', function (SalesOrder $model) {
                return $model->warehouse->name ?? '';
            })
            ->addColumn('store_id', function (SalesOrder $model) {
                return $model->store->store_name ?? '';
            })
            ->addColumn('vendor_id', function (SalesOrder $model) {
                return $model->vendor->first_name ?? ''; // Fix the typo in 'first_name'
            });
    }


    /**
     * Get the query source of dataTable.
     */
    public function query(SalesOrder $model): QueryBuilder
    {
        $query = $model->newQuery();

        if ($this->request()->id != null) {
            $query = $query->where('id', $this->request()->id);
        }

        return $query;
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('salesorderreport-table')
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
            Column::make('invoice_number')->title(__('invoice Number')),
            Column::make('sales_from'),
            Column::make('sales_type'),
            Column::make('warehouse_id')->title(__('Warehouse')),
            Column::make('store_id')->title(__('Store')),
            Column::make('total_given_qunatity'),
            Column::make('total_amount'),
            Column::make('vendor_id')->title(__('Vendor')),
            Column::make('delivered_date'),
            Column::make('payment_status'),
            Column::make('vendor_id'),
        ];
    }

    /**
     * Get the filename for export.
     */
    protected function filename(): string
    {
        return 'SalesOrderReport_' . date('YmdHis');
    }
}
