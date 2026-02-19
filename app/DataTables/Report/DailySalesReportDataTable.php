<?php

namespace App\DataTables\Report;

use App\Core\CommonComponent;
use App\Models\SalesOrder;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class DailySalesReportDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder $query Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->rawColumns(['total_count', 'total_amount', 'delivered_date'])
            ->editColumn('delivered_date', function (SalesOrder $model) {
                $delivered_date = $model->delivered_date;
                return CommonComponent::getCreatedAtFormat($delivered_date);
            })
            ->editColumn('total_count', function (SalesOrder $model) {
                $total_count = $model->total_count;
                return $total_count ?? '';
            })
            ->editColumn('discount_amount', function (SalesOrder $model) {
                $total_discount_amount = $model->total_discount_amount;
                return $total_discount_amount ?? '';
            })
            ->editColumn('total_amount', function (SalesOrder $model) {
                $final_total_amount = $model->final_total_amount;
                return $final_total_amount ?? '';
            })
            ->editColumn('paid_amount', function (SalesOrder $model) {
                // $paid_amount = $model->sales_order_transactions->sum('amount');
                $paid_amount = $model->total_payment_amount;
                return $paid_amount ?? '';
            })
            ->editColumn('pending_amount', function (SalesOrder $model) {
                // Manually calculate the pending amount
                $totalAmount = $model->final_total_amount;
                $paidAmount = $model->total_payment_amount;
                return max(0, $totalAmount - $paidAmount);
            })
            ->addColumn('action', function (SalesOrder $model) {
                return view('pages.report.daily_sales_report._action-menu', compact('model'));
            });
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(SalesOrder $model): QueryBuilder
    {
        $query = $model->newQuery();
        $currentDate = Carbon::now()->toDateString();

        $from_date = Carbon::parse($currentDate)->startOfDay();
        $to_date = Carbon::parse($currentDate)->endOfDay();

        if ($this->request()->id != null) {
            $query = $model->where('id', $this->request()->id);
        }

        $query = $model->select(
            'id',
            'delivered_date',
            DB::raw('COALESCE(sum(sales_orders.discount_amount),0) as total_discount_amount'),
            DB::raw('COALESCE(sum(sales_orders.total_amount),0) as final_total_amount'),
            DB::raw('count(sales_orders.id) as total_count'),
            DB::raw('(SELECT COALESCE(sum(pt.amount), 0) FROM payment_transactions pt WHERE pt.reference_id = sales_orders.id AND pt.transaction_type = 2 AND pt.type = 1 AND pt.deleted_at IS NULL) as total_payment_amount'),

        )
            ->whereBetween('delivered_date', [$from_date, $to_date])
            ->groupBy('id', 'delivered_date');
        // ->groupBy('delivered_date');
        //  $query = $model;
        return $query;

        // $query->select(
        //     'id',
        //     'delivered_date',
        //     DB::raw('COALESCE(sum(sales_orders.discount_amount),0) as total_discount_amount'),
        //     DB::raw('COALESCE(sum(sales_orders.total_amount),0) as final_total_amount'),
        //     DB::raw('count(sales_orders.id) as total_count'),
        //     DB::raw('(SELECT COALESCE(sum(pt.amount), 0) FROM payment_transactions pt WHERE pt.reference_id = sales_orders.id AND pt.transaction_type = 2 AND pt.type = 1 AND pt.deleted_at IS NULL) as total_payment_amount'),
        // );
        // $query->with('sales_order_transactions', function ($query) {
        //     $query->groupBy('transaction_datetime');
        // })
        // ;
        // $query->withSum('sales_order_transactions', 'amount as total_payment_amount');

        // // Separate the subquery using a leftJoin
        // $query->leftJoin('payment_transactions', function ($join) {
        //     $join->on('sales_orders.id', '=', 'payment_transactions.reference_id')
        //         ->where('payment_transactions.transaction_type', 2)
        //         ->where('payment_transactions.type', 1)
        //         ->whereNull('payment_transactions.deleted_at');
        // });

        // Use withSum on the joined table
        // $query->withSum('payment_transactions', 'amount as total_paid_amount');

        // $query->groupBy('delivered_date');
        // $query->orderByDesc('id');

        // return $query;
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        $createButton = [];

        // Check if the user is authenticated and has permission to create
        if (Auth::check() && Auth::user()->can('Daily Sales Report Download')) {
            $createButton[] = Button::make([
                'excel',
                'csv',
                'print',
            ]);

        }

        return $this->builder()
            ->setTableId('dailysalesreport-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->stateSave(false)
            ->responsive()
            ->autoWidth(true)
            ->parameters([
                'scrollX' => true,
                'drawCallback' => 'function() { KTMenu.createInstances(); }',
                'order' => [[0, 'desc']],
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
            Column::make('delivered_date'),
            // Column::make('total_count'),
            Column::make('discount_amount')->title(__('Total Discount Amount')),
            Column::make('total_amount'),
            Column::make('paid_amount'),
            Column::make('pending_amount'),
            Column::computed('action')
                ->exportable(false)
                ->printable(false)
                ->addClass('text-center')
                ->responsivePriority(-1),
        ];
    }

    /**
     * Get the filename for export.
     */
    protected function filename(): string
    {
        return 'DailySalesReport_' . date('YmdHis');
    }
}
