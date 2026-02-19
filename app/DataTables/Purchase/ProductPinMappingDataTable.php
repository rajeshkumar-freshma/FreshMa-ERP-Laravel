<?php

namespace App\DataTables\Purchase;

use App\Models\Product;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class ProductPinMappingDataTable extends DataTable
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
            ->addColumn('weight_box', function (Product $model) {
                $product = $model;
                $unit_details = 'units';
                return view('pages.purchase.pin_mapping.weight_box', compact('unit_details', 'product'));
            })
            ->addColumn('date', function (Product $model) {
                $date = $model;
                return view('pages.purchase.pin_mapping.weight_box', compact('date'));
            })
            ->addColumn('type', function (Product $model) {
                $type = $model;
                return view('pages.purchase.pin_mapping.weight_box', compact('type'));
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

        return $query;
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        $createButton = [];

        // Check if the user is authenticated and has permission to create
        if (Auth::check() && Auth::user()->can('Product Pin Mapping Download ')) {
            $createButton[] = Button::make('create');

        }

        return $this->builder()
            ->setTableId('productpinmapping-table')
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
            Column::make('product_name')->title(__('Product Name')),
            Column::make('weight_box')->title(__('Box No / Weight')),
            Column::make('date'),
            Column::make('type'),
        ];
    }

    /**
     * Get the filename for export.
     */
    protected function filename(): string
    {
        return 'ProductPinMapping_' . date('YmdHis');
    }
}
