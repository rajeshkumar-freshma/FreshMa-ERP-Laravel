<?php

namespace App\DataTables\LoanManagement;

use App\Models\Loan;
use App\Models\LoanRepayment;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class LoanRepaymentDataTable extends DataTable
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
        // ->addIndexColumn()
            ->addIndexColumn()
            ->rawColumns(['action', 'status'])
            ->order(function ($model) {
                $model->orderBy('id', 'DESC');
            })
            ->editColumn('loan_code', function (LoanRepayment $model) {
                return $model->loans->loan_code ?? 'N/A';
            })
            // ->addColumn('payment_date', function (Loan $model) {
            //     $payment_date = $model->first_payment_date;
            //     return $payment_date;
            // })
            // ->addColumn('instalment_amount', function (Loan $model) {
            //     $repayment_amount = $model->repayment_amount;
            //     return $repayment_amount;
            // })
            // ->addColumn('pay_amount', function (Loan $model) {
            //     $repayment_amount = $model->repayment_amount;
            //     return $repayment_amount;
            // })
            // ->addColumn('status', function (LoanRepayment $model) {
            //     $status = $model->status;
            //     return view('pages.partials.statuslabel', compact('status'));
            // })
            ->addColumn('action', function (LoanRepayment $model) {
                return view('pages.loan_management.loan_repayment._action-menu', compact('model'));
            });
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\LoanRepayment $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(LoanRepayment $model): QueryBuilder
    {
        $query = $model->newQuery();
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
        if (Auth::check() && Auth::user()->can('RePayment Create')) {
            $createButton[] = Button::make('create');
        }

        return $this->builder()
            ->setTableId('loan_repayments-table')
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
            ->addTableClass('align-middle table-row-dashed fs-6 gy-5')
            ->dom('Bfrtip')
            ->buttons($createButton);
        // ->buttons([
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
            Column::make('loan_code')->title(__('loan Id')),
            Column::make('payment_date')->title(__('Payment Date')),
            // Column::make('invoice_number')->title(__('Invoice Number')),
            Column::make('instalment_amount')->title(__('Instalment Amount')),
            Column::make('pay_amount')->title(__('Pay Amount')),
            Column::computed('action')
                ->exportable(false)
                ->printable(false)
                ->addClass('text-center')
                ->responsivePriority(-1),
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename(): string
    {
        return 'LoanRepayment' . date('YmdHis');
    }
}
