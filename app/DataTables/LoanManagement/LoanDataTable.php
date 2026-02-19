<?php

namespace App\DataTables\LoanManagement;

use App\Models\Admin;
use App\Models\Loan;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class LoanDataTable extends DataTable
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
            ->editColumn('loan_code', function (Loan $model) {
                $loan_code = $model->loan_code;
                return $loan_code ?? '';
            })
            ->editColumn('employee_id', function (Loan $model) {
                $employee = Admin::find($model->employee_id);
                if ($employee !== null) {

                    $employeeName = $employee ? $employee->first_name . ' ' . $employee->last_name : '';
                } else {
                    $employeeName = 'Company';
                }
                return $employeeName;
            })

            ->editColumn('interest_frequency', function (Loan $model) {
                $interestFrequency = $model->interest_frequency;
                return view('pages.partials.statuslabel', compact('interestFrequency'));
            })
            ->addColumn('loan_status', function (Loan $model) {
                $data['loan_status'] = $model->loan_status;
                $data['loan_id'] = $model->id;
                return view('pages.loan_management.loan.status_label', $data);
            })
            ->addColumn('action', function (Loan $model) {
                return view('pages.loan_management.loan._action-menu', compact('model'));
            });
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\Loan $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(Loan $model): QueryBuilder
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
        if (Auth::check() && Auth::user()->can('Apply Loan Create')) {
            $createButton[] = Button::make('create');

        }

        return $this->builder()
            ->setTableId('loans-table')
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
            Column::make('loan_code')->title(__('Loan Code')),
            Column::make('employee_id')->title(__('Borrower')),
            Column::make('applied_on')->title(__('Applied On')),
            Column::make('principal_amount')->title(__('Principal Amount')),
            Column::make('applied_amount')->title(__('Applied Amount')),
            Column::make('repayment_amount')->title(__('Repayment Amount')),
            Column::make('interest_rate'),
            Column::make('interest_frequency'),
            Column::make('loan_tenure')->title(__('Loan Tenure')),
            // Column::make('loan_term_method')->title(__('Term Method')),
            Column::make('loan_status'),
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
        return 'Loan' . date('YmdHis');
    }
}
