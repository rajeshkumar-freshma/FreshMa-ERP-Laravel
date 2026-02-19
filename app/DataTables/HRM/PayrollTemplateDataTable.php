<?php

namespace App\DataTables\HRM;

use App\Core\CommonComponent;
use App\Models\PayrollTemplate;
use App\Models\PayrollType;
use App\Models\Staff;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class PayrollTemplateDataTable extends DataTable
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
            ->addIndexColumn()
            ->rawColumns(['action', 'payroll_templates'])
            ->addColumn('employee_id', function (PayrollTemplate $model) {
                $employee = Staff::find($model->employee_id);
                return $employee ? $employee->first_name : '';
            })

            ->addColumn('status', function (PayrollTemplate $model) {
                $status = $model->status;
                return view('pages.partials.statuslabel', compact('status'));
            })
            ->editColumn('payroll_templates', function (PayrollTemplate $model) {
                $jsonData = $model->payroll_templates;
                $data = json_decode($jsonData, true);

                $ids = [];
                $amounts = [];

                foreach ($data as $item) {
                    $ids[] = $item['payroll_type_id'];
                    $amounts[$item['payroll_type_id']] = $item['amount']; // Associate amount with payroll type ID
                }

                $payrollTypes = PayrollType::whereIn('id', $ids)->get();

                $result = [];

                foreach ($payrollTypes as $payrollType) {
                    // Use payroll type ID to retrieve amount and associate it with the payroll type name
                    $amount = isset($amounts[$payrollType->id]) ? $amounts[$payrollType->id] : 0; // Default to 0 if amount not found
                    $result[] = $payrollType->name . '=' . $amount;
                }

                return implode(', ', $result); // Convert array to string
            })

            ->editColumn('created_at', function (PayrollTemplate $model) {
                return CommonComponent::getCreatedAtFormat($model->created_at);
            })
            ->addColumn('action', function (PayrollTemplate $model) {
                return view('pages.hrm.payroll_template._action-menu', compact('model'));
            });
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\PayrollTemplate $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(PayrollTemplate $model): QueryBuilder
    {
        return $model->newQuery();
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
        if (Auth::check() && Auth::user()->can('Payroll Template Create')) {
            $createButton[] = Button::make('create');
        }

        return $this->builder()
            ->setTableId('payroll_templates-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->stateSave(false)
            ->responsive()
            ->autoWidth(true)
        // ->selectStyleSingle()
            ->parameters([
                'scrollX' => true,
                'drawCallback' => 'function() { KTMenu.createInstances(); }',
            ])
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
            Column::make('employee_id')->title(__('Employee')),
            Column::make('payroll_templates')->title(__('Pay Roll Template')),
            Column::make('status'),
            Column::computed('action')
                ->exportable(false)
                ->printable(false)
                ->addClass('text-center')
                ->responsivePriority(-1),
            // Column::make('properties')->addClass('none'),
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename(): string
    {
        return 'PayrollTemplate' . date('YmdHis');
    }
}
