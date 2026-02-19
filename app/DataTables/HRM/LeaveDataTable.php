<?php

namespace App\DataTables\HRM;

use App\Core\CommonComponent;
use App\Models\Leave;
use App\Models\LeaveType;
use App\Models\Staff;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class LeaveDataTable extends DataTable
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
            ->rawColumns(['leave_type', 'action'])
            ->addColumn('employee_id', function (Leave $model) {
                $employee = Staff::find($model->employee_id);
                return $employee ? $employee->first_name : '';
            })
            ->addColumn('leave_type', function (Leave $model) {
                $employee = LeaveType::find($model->leave_type);
                return $employee ? $employee->name : '';
            })
            ->addColumn('approved_status', function (Leave $model) {
                $approved_status = $model->approved_status;
                return view('pages.partials.statuslabel', compact('approved_status'));
            })
            ->editColumn('start_date', function (Leave $model) {
                // return CommonComponent::getChangedDateFormat($model->start_date);
                // return $date = Carbon::createFromFormat('d-m-Y', $model->start_date)->todataString();
                return CommonComponent::getDateFormat($model->start_date);
            })
            ->editColumn('end_date', function (Leave $model) {
                // return CommonComponent::getChangedDateFormat($model->end_date);
                // return $date = Carbon::createFromFormat('d-m-Y', $model->end_date)->todataString();;
                return CommonComponent::getDateFormat($model->end_date);
            })
            ->editColumn('created_at', function (Leave $model) {
                return CommonComponent::getCreatedAtFormat($model->created_at);
                // return $date = Carbon::createFromFormat('Y-d-m H:i:s', $model->created_at)->format('d/m/Y');
                // return $model->created_at;
            })
            ->addColumn('action', function (Leave $model) {
                return view('pages.hrm.leave._action-menu', compact('model'));
            });
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\Leave $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(Leave $model): QueryBuilder
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
        if (Auth::check() && Auth::user()->can('Leave Create')) {
            $createButton[] = Button::make('create');
        }

        return $this->builder()
            ->setTableId('Leave-table')
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
            Column::make('leave_type'),
            Column::make('created_at')->title(__('Applied On')),
            Column::make('start_date')->title(__('Start Date')),
            Column::make('end_date')->title(__('End Date')),
            Column::make('reasons')->title(__('Leave Reasons')),
            Column::make('remark'),
            // Column::make('is_half_day'),
            Column::make('approved_status'),
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
        return 'Leave' . date('YmdHis');
    }
}
