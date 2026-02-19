<?php

namespace App\DataTables\HRM;

use App\Core\CommonComponent;
use App\Models\Staff;
use App\Models\StaffAttendance;
use App\Models\Store;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class StaffAttendanceDataTable extends DataTable
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
            ->rawColumns(['action', 'status', 'attendance_date', 'created_at'])
            ->addColumn('store_id', function (StaffAttendance $model) {
                $store = Store::find($model->store_id);
                return $store ? $store->store_name : '';
            })
            // ->addColumn('staff_id', function (StaffAttendance $model) {
            //     $staffAttendanceId = $model->id;
            //     $staffAttendanceDetails = StaffAttendanceDetails::where('staff_attendance_id', $staffAttendanceId)->first();
            //     $staff = Staff::find($staffAttendanceDetails->staff_id);
            //     // Check if $staffAttendanceDetails is not null and has the property staff_id
            //     if ($staff) {
            //         // Check if $staff is not null and has the property first_name
            //         return $staff ? $staff->first_name : '';
            //     }

            //     return ''; // Return an empty string or handle it according to your needs
            // })
            ->editColumn('created_at', function (StaffAttendance $model) {
                Log::info("before Staff attendance created_at");
                Log::info($model->created_at);
                if (isset($model->created_at)) {
                    $created_at = CommonComponent::getCreatedAtFormat($model->created_at);
                    return $created_at;
                }
                // return CommonComponent::getCreatedAtFormat($model->created_at);
            })
            ->editColumn('attendance_date', function (StaffAttendance $model) {
                return CommonComponent::getDateFormat($model->attendance_date);
            })
            ->addColumn('status', function (StaffAttendance $model) {
                $status = $model->status;
                return view('pages.partials.statuslabel', compact('status'));
            })
            ->addColumn('action', function (StaffAttendance $model) {
                return view('pages.hrm.staff_attendance._action-menu', compact('model'));
            });
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\StaffAttendance $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(StaffAttendance $model): QueryBuilder
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
        if (Auth::check() && Auth::user()->can('Staff Attendance Create')) {
            $createButton[] = Button::make('create');
        }

        return $this->builder()
            ->setTableId('staff_attendance-table')
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
            Column::make('store_id')->title(__('Store')),
            Column::make('attendance_date'),
            Column::make('total_working_hours')->title(__('Total Working Hours')),
            Column::make('total_present'),
            Column::make('total_absent'),
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
        return 'StaffAttendance' . date('YmdHis');
    }
}
