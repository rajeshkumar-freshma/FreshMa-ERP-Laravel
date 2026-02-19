<?php

namespace App\DataTables\HRM;

use App\Models\Store;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class EmployeeReportDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder $query Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addColumn('action', 'status', )
            ->addIndexColumn()
            // ->addColumn('status', function (Store $model) {
            //     $status = $model->status;
            //     return view('pages.partials.statuslabel', compact('status'));
            // })
            ->addColumn('no_of_employees', function (Store $model) {
                // return 1;
                return $status = $model->staff->count('staff_id');
                // $staffIds = $status->pluck('staff_id');
                // return view('pages.report.employee_report.view', compact('staffIds'));
            })
            // ->editColumn('view_employees', function (Store $model) {
            //      $satffs = $model->staff;
            //     // $staffIds = $satffs->pluck('staff_id');
            //     return view('pages.report.employee_report.view', compact('satffs'));
            // })
            // ->addColumn('admin_type', function (Store $model) {
            //     $admin_type = $model->user_type;
            //     return view('pages.partials.statuslabel', compact('admin_type'));
            // })
            ->addColumn('action', function (Store $model) {
                $satffs = $model->staff;
                $staffIds = $satffs->pluck('staff_id');
                return view('pages.report.employee_report._action-menu', compact('model', 'staffIds'));
            });
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(Store $model): QueryBuilder
    {
        return $model->newQuery();
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        $createButton = []; // Initialize the $createButton array

        // Check if the user is authenticated and has permission to create
        if (Auth::check() && Auth::user()->can('Employee Report Download')) {
            $createButton[] = Button::make(
                'excel',
                'csv',
                'print',
            );

        }

        return $this->builder()
            ->setTableId('employeereport-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->buttons($createButton);
    }

    /**
     * Get the dataTable columns definition.
     */
    public function getColumns(): array
    {
        return [
            Column::make('id')->title('S.No')->render('meta.row + meta.settings._iDisplayStart + 1;')->addClass('ps-0'),
            Column::make('store_name')->title(__('Store Name')),
            Column::make('no_of_employees'),
            // Column::make('view_employees'),
            Column::make('status'),
            Column::make('created_at')->title(__('Created At')),
            Column::computed('action')
                ->exportable(false)
                ->printable(false)
                ->width(60)
                ->addClass('text-center'),
        ];
    }

    /**
     * Get the filename for export.
     */
    protected function filename(): string
    {
        return 'EmployeeReport_' . date('YmdHis');
    }
}
