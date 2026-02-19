<?php

namespace App\DataTables\HRM;

use App\Core\CommonComponent;
use App\Models\StaffAdvance;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class StaffAdvanceDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder $query Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addColumn('action', 'status', 'admin_type')
            ->addIndexColumn()
            ->order(function ($model) {
                $model->orderBy('id', 'DESC');
            })
            ->addColumn('status', function (StaffAdvance $model) {
                $status = $model->status;
                return view('pages.partials.statuslabel', compact('status'));
            })
            ->editColumn('name', function (StaffAdvance $model) {
                return $model->staff->name;
            })
            ->editColumn('phone_number', function (StaffAdvance $model) {
                return $model->staff->phone_number;
            })
            ->editColumn('created_at', function (StaffAdvance $model) {
                return CommonComponent::getCreatedAtFormat($model->created_at);
            })
            ->addColumn('action', function (StaffAdvance $model) {
                return view('pages.hrm.staff_advance._action-menu', compact('model'));
            });
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(StaffAdvance $model): QueryBuilder
    {
        return $model->newQuery();
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        $createButton = [];

        // Check if the user is authenticated and has permission to create
        if (Auth::check() && Auth::user()->can('Staff Advanced Create')) {
            $createButton[] = Button::make('create');

        }

        return $this->builder()
            ->setTableId('staffadvance-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->dom('Bfrtip')
        // ->orderBy(1)
        // ->selectStyleSingle()
            ->buttons($createButton);
    }

    /**
     * Get the dataTable columns definition.
     */
    public function getColumns(): array
    {
        return [
            Column::make('id')->title('S.No')->render('meta.row + meta.settings._iDisplayStart + 1;')->addClass('ps-0'),
            Column::make('name')->title(__('Staff Name')),
            Column::make('phone_number')->title(__('Phone Number')),
            Column::make('amount')->title(__('Amount')),
            Column::make('date')->title(__('Date')),
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
        return 'StaffAdvance_' . date('YmdHis');
    }
}
