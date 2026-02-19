<?php

namespace App\DataTables\Report;

use App\Models\Product;
use App\Models\SalesOrderDetail;
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

class ProductWiseIndentRequestReportDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder $query Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->rawColumns(['product_name', 'unit', 'rate', 'amount'])
            ->addColumn('product_name', function (Product $model) {
                return $model->name;
            })
            ->addColumn('unit', function (Product $model) {
                $product = $model;
                $unit_details = 'units';
                return view('pages.report.product_sale_report.unit_amount', compact('unit_details', 'product'));
            })
            ->addColumn('store_indent_count', function (Product $model) {
                // Accessing related models. Check if the relationship is loaded first.
                $storeIndentRequestCount = $model->storeIndentRequestDetails ? $model->storeIndentRequestDetails->sum('request_quantity') : 0;
                return $storeIndentRequestCount;
            })
            ->addColumn('vendor_indent_count', function (Product $model) {
                // Accessing related models. Check if the relationship is loaded first.
                $vendorIndentRequestCount = $model->vendorIndentRequestDetails ? $model->vendorIndentRequestDetails->sum('request_quantity') : 0;
                return $vendorIndentRequestCount;
            })

            ->addColumn('rate', function (Product $model) {
                $product = $model;
                $rate = 'rate';
                return view('pages.report.product_sale_report.unit_amount', compact('rate', 'product'));
            })
            ->addColumn('amount', function (Product $model) {
                $product = $model;
                $amount = 'amount';
                return view('pages.report.product_sale_report.unit_amount', compact('amount', 'product'));
            });
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(Product $model): QueryBuilder
    {
        $query = $model->newQuery();

        if ($this->request()->id != null) {
            $query = $query->where('id', $this->request()->id);
        }


        // Check if 'product_id' is provided in the request
        $productId = $this->request()->product_id ?? null;
        Log::info("productId");
        Log::info($productId);
        if ($productId !== null) {
            $query = $query->where('id', $productId);
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
        if (Auth::check() &&  Auth::user()->can('Indent Request Report Download')) {
            $createButton[] = Button::make([
                'excel',
                'csv',
                'print',
            ]);


        }

        return $this->builder()
            ->setTableId('productwiseindentrequestreport-table')
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
            Column::make('product_name')->title(__('Product Name')),
            Column::make('unit'),
            Column::make('rate'),
            Column::make('amount'),
            Column::make('store_indent_count')->title('Store Indent Count'),
            Column::make('vendor_indent_count')->title('Vendor Indent Count'),
        ];
    }

    /**
     * Get the filename for export.
     */
    protected function filename(): string
    {
        return 'ProductWiseIndentRequestReport_' . date('YmdHis');
    }
}
