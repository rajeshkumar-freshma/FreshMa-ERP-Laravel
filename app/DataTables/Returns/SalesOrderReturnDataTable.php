<?php

namespace App\DataTables\Returns;

use App\Core\CommonComponent;
use App\Models\SalesOrderReturn;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class SalesOrderReturnDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder $query Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->rawColumns(['status', 'return_from', 'action'])
            ->order(function ($model) {
                $model->orderBy('id', 'DESC');
            })
            ->addColumn('status', function (SalesOrderReturn $model) {
                $indent_status = $model->status;
                return view('pages.partials.statuslabel', compact('indent_status'));
            })
            ->addColumn('return_from', function (SalesOrderReturn $model) {
                if ($model->return_from == 1) {
                    return "Warehouse";
                } else {
                    return "Store";
                }
            })
            ->addColumn('sales_order_id', function (SalesOrderReturn $model) {
                if ($model->sales_order_id != null) {
                    return @$model->sales_order_details->invoice_number;
                } else {
                    return "-";
                }
            })
            ->editColumn('return_date', function (SalesOrderReturn $model) {
                return CommonComponent::getDateFormat($model->return_date);
            })
            ->editColumn('created_at', function (SalesOrderReturn $model) {
                return CommonComponent::getCreatedAtFormat($model->created_at);
            })
            ->addColumn('action', function (SalesOrderReturn $model) {
                return view('pages.return.sales_return._action-menu', compact('model'));
            })
            ->setRowId('id');
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(SalesOrderReturn $model): QueryBuilder
    {
        $query = $model->newQuery();
        return $this->applyScopes($query);
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        $createButton = [];

        // Check if the user is authenticated and has permission to create
        if (Auth::check() &&  Auth::user()->can('Sales Return Create')) {
            $createButton[] = Button::make('create');


        }

        return $this->builder()
            ->setTableId('salesorderreturn-table')
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
            ->dom('Bfrtip')
            ->buttons($createButton);
        //     ->buttons([
        //     Button::make('create'),
        //     // Button::make('export'),
        //     // Button::make('print'),
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
            Column::make('sales_order_return_number')->title(__('Return Number')),
            Column::make('return_from')->title(__('Return From')),
            Column::make('return_date')->title(__('Return Date')),
            Column::make('sales_order_id')->title(__('Sales Order ID')),
            Column::make('total_amount')->title(__('Total Number')),
            Column::make('status'),
            Column::make('created_at')->title(__('Created At')),
            Column::computed('action')
                ->exportable(false)
                ->printable(false)
                ->addClass('text-center')
                ->responsivePriority(-1)
        ];
    }

    /**
     * Get the filename for export.
     */
    protected function filename(): string
    {
        return 'SalesOrderReturn_' . date('YmdHis');
    }
}
