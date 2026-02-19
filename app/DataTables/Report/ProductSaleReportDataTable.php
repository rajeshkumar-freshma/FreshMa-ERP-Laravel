<?php

namespace App\DataTables\Report;

use App\Models\Product;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class ProductSaleReportDataTable extends DataTable
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
        if ($this->request()->product_id != null) {
            $query = $query->where('id', $this->request()->product_id);
        }
        if ($this->request()->store_id != null) {
            $query = $query->where('store_id', $this->request()->store_id);
        }
        if ($this->request()->warehouse_id != null) {
            $query = $query->where('warehouse_id', $this->request()->warehouse_id);
        }
        if ($this->request()->from_date != null) {
            $query = $query->where('delivered_date', $this->request()->from_date);
        }

        return $this->applyScopes($query);
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        $createButton = [];

        // Check if the user is authenticated and has permission to create
        if (Auth::check() && Auth::user()->can('Product Sales Report Download')) {
            $createButton[] = Button::make([
                'csv',
                'print',
            ]);
        }

        return $this->builder()
            ->setTableId('productwisesalereport-table')
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
        //     // Button::make('excel'),
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
        ];
    }

    /**
     * Get the filename for export.
     */
    protected function filename(): string
    {
        return 'ProductWiseSalesReport_' . date('YmdHis');
    }
}
