<?php

namespace App\DataTables\Report;

use App\Models\Product;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class FishCuttingDetailsReportDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder $query Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->rawColumns(['fish_cutting_weight', 'grouped_product'])
            ->addColumn('fish_cutting_weight', function (Product $model) {
                if ($model->fish_cutting) {
                    return $model->fish_cutting->weight . 'kg';
                } else {
                    return '-';
                }
            })

            ->addColumn('grouped_product', function (Product $model) {
                if ($model->fish_cutting) {
                    $details = $model->fish_cutting->fish_cutting_details;
                    if ($details) {
                        $weights = [];

                        $columns = [
                            'slice' => 'Slice Percentage',
                            'head' => 'Head Percentage',
                            'tail' => 'Tail Percentage',
                            'eggs' => 'Eggs Percentage',
                            'wastage' => 'Wastage Percentage',
                        ];

                        foreach ($columns as $column => $columnName) {
                            if ($details->{$column} != 0) {
                                $weights[] = "Name: $columnName, Weight: {$details->{$column}}";
                            }
                        }

                        $result = implode(', ', $weights);

                        return $result ? "Product Id: {$details->product_id}, $result" : "Product Id: {$details->product_id}, No weights available";
                    }
                }

                return "No fish cutting details available";
            });

        // ->addColumn('vendor_id', function (Product $model) {
        //     return $model->vendor->first_name ?? ''; // Fix the typo in 'first_name'
        // });
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

        $query = $query->with('fish_cutting.fish_cutting_details');

        $request = $this->request();

        // Filter based on transaction datetime range
        $fromDate = $this->request()->input('from_date');
        $toDate = $this->request()->input('to_date');

        if ($fromDate || $toDate) {
            $query->whereHas('fish_cutting', function ($query) use ($fromDate, $toDate) {
                if ($fromDate) {
                    $query->whereDate('cutting_date', $fromDate);
                }

                if ($toDate) {
                    $query->whereDate('cutting_date', $toDate);
                }
            });
        }

        // Filter based on purchase datetime range
        $storeId = $this->request()->input('store_id');
        if ($storeId) {
            $query->whereHas('fish_cutting', function ($fishCuttingQuery) use ($storeId) {
                $fishCuttingQuery->where('store_id', $storeId);
            });
        }

        // Filter based on purchase order number
        $productId = $this->request()->input('product_id');
        Log::info('productId');
        Log::info($productId);
        if ($productId) {
            $query->whereHas('fish_cutting', function ($fishCuttingQuery) use ($productId) {
                $fishCuttingQuery->where('product_id', $productId);
            });
        }

        Log::info('data');
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
        if (Auth::check() && Auth::user()->can('Fish Cutting Details Report Download')) {
            $createButton[] = Button::make([
                'excel',
                'csv',
                'print',
            ]);
        }

        return $this->builder()
            ->setTableId('fishcuttingdetailsreport-table')
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
            Column::make('name')->title(__('Product Name')),
            Column::make('sku_code')->title(__('Product SKU Code')),
            Column::make('fish_cutting_weight'),
            Column::make('grouped_product'),
        ];
    }

    /**
     * Get the filename for export.
     */
    protected function filename(): string
    {
        return 'FishCuttingDetailsReport_' . date('YmdHis');
    }
}
