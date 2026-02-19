<?php

namespace App\DataTables\Sales;

use App\Core\CommonComponent;
use App\Models\SalesOrder;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Log;

class SalesOrderDataTable extends DataTable
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
            ->rawColumns(['vendor_name', 'machine_name', 'status', 'action'])
            ->order(function ($model) {
                $model->orderBy('id', 'DESC');
            })
            ->addColumn('status', function (SalesOrder $model) {
                $indent_status = $model->status;
                return view('pages.partials.statuslabel', compact('indent_status'));
            })
            ->addColumn('vendor_name', function (SalesOrder $model) {
                return @$model->vendor->first_name . '-' . @$model->vendor->user_code;
            })
            ->editColumn('bill_no', function (SalesOrder $model) {
                return $model->bill_no != null ? $model->bill_no : "-";
            })
            ->addColumn('store_id', function (SalesOrder $model) {
                return @$model->store->store_name;
            })
            ->editColumn('delivered_date', function (SalesOrder $model) {
                return CommonComponent::getCreatedAtFormat($model->delivered_date);
            })
            ->editColumn('created_at', function (SalesOrder $model) {
                return CommonComponent::getCreatedAtFormat($model->created_at);
            })
            ->addColumn('action', function (SalesOrder $model) {
                return view('pages.sales.sales_order._action-menu', compact('model'));
            });
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\SalesOrder $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(SalesOrder $model): QueryBuilder
    {
        $query = $model->newQuery();

        $from_date = $this->request()->input('from_date');
        $to_date = $this->request()->input('to_date');
        $store_id = $this->request()->input('store_id');
        $bill_no = $this->request()->input('bill_no');

        if ($from_date || $to_date) {
            if ($from_date && $to_date) {
                $query->whereBetween('delivered_date', [$from_date, $to_date]);
            } elseif ($from_date) {
                $query->whereDate('delivered_date', $from_date);
            } elseif ($to_date) {
                $query->whereDate('delivered_date', $to_date);
            }
        }

        if ($store_id) {
            $query->where('store_id', $store_id);
        }

        //daily sales report query end
        if ($this->request()->machine_id != null) {
            $query = $query->where('machine_id', $this->request()->machine_id);
        }

        if ($bill_no) {
            $query->where(function ($q) use ($bill_no) {
                $q->where('invoice_number', 'LIKE', '%' . $bill_no . '%')
                    ->orWhere('bill_no', 'LIKE', '%' . $bill_no . '%');
            });
        }

        return $this->applyScopes($query);
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\DataTables\Html\Builder
     */
    public function html(): HtmlBuilder
    {
        $createButton = [];

        // Check if the user is authenticated and has permission to create
        if (Auth::check() &&  Auth::user()->can('Sales Order Create')) {
            $createButton[] = Button::make('create');
        }

        return $this->builder()
            ->setTableId('salesorder-table')
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
     *
     * @return array
     */
    public function getColumns(): array
    {
        return [
            Column::make('id')->title('S.No')->render('meta.row + meta.settings._iDisplayStart + 1;')->addClass('ps-0'),
            Column::make('vendor_name')->title(__('Vendor Name')),
            Column::make('invoice_number'),
            Column::make('bill_no'),
            Column::make('store_id')->title(__('Store')),
            Column::make('delivered_date'),
            Column::make('total_amount'),
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
     * Get filename for export.
     *
     * @return string
     */
    protected function filename(): string
    {
        return 'SalesOrder_' . date('YmdHis');
    }
}
