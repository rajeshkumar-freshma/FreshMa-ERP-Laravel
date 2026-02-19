<?php

namespace App\DataTables\ActivityLog;

use App\Core\CommonComponent;
use App\Models\Admin;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Spatie\Activitylog\Models\Activity;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class ActivityLogDataTable extends DataTable
{
    /**
     * Build DataTable class.
     *
     * @param  mixed  $query  Results from query() method.
     *
     * @return \Yajra\DataTables\DataTableAbstract
     */
    public function dataTable($query)
    {
        return (new EloquentDataTable($query))
            ->addIndexColumn()
            ->order(function ($model) {
                $model->orderBy('id', 'DESC');
            })
            ->editColumn('properties', function (Activity $model) {
                return $model->properties ?? '';
            })
            ->editColumn('causer_id', function (Activity $model) {
                $admin = Admin::find($model->causer_id)->first();
                return $admin->first_name . ' ' . $admin->last_name ?? '';
            })
            ->editColumn('created_at', function (Activity $model) {
                return CommonComponent::getCreatedAtFormat($model->created_at) ?? '';
            });
    }

    /**
     * Get query source of dataTable.
     *
     * @param \Spatie\Activitylog\Models\Activity $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(Activity $model)
    {
        if (Auth::guard('admin')->check()) {
            $query = $model->query();
        } else {
            $query = $model->query()->where('supplier_id', Auth::user()->id);
        }

        if ($this->request()->has('from_date') && $this->request()->has('to_date')) {
            $fromDate = Carbon::parse($this->request()->from_date)->format('Y-m-d 00:00:00');
            $toDate = Carbon::parse($this->request()->to_date)->format('Y-m-d 23:59:59');
            $query->whereBetween('created_at', [$fromDate, $toDate]);
        }

        if ($this->request()->has('ip_address')) {
            $ipAddress = $this->request()->input('ip_address');
            $query->where('ip_address', 'LIKE', "%$ipAddress%");
        }

        if ($this->request()->has('subject_type')) {
            // Partial match using LIKE
            $subjectType = $this->request()->input('subject_type');
            $query->where('subject_type', 'LIKE', "%$subjectType%");
        }

        // Uncomment this block if you need to filter by 'properties' using LIKE
        // if ($this->request()->has('properties')) {
        //     $properties = $this->request()->input('properties');
        //     $query->where('properties', 'LIKE', '%' . $properties . '%');
        // }

        return $query;
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\DataTables\Html\Builder
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('activity_log-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->stateSave(false)
            ->responsive()
            ->autoWidth(true)
            ->parameters([
                'scrollX' => true,
                'drawCallback' => 'function() { KTMenu.createInstances(); }',
            ])
            ->addTableClass('align-middle table-row-dashed fs-6 gy-5')
            ->dom('Bfrtip')
            ->buttons([
                // Button::make('create'),
                Button::make('export'),
                Button::make('print'),
                // Button::make('reset'),
                // Button::make('reload')
            ]);
    }

    /**
     * Get columns.
     *
     * @return array
     */
    protected function getColumns()
    {
        return [
            Column::make('id')->title('S.No')->render('meta.row + meta.settings._iDisplayStart + 1;')->addClass('ps-0'),
            Column::make('log_name'),
            Column::make('description'),
            Column::make('subject_type'),
            Column::make('subject_id'),
            Column::make('ip_address'),
            Column::make('event'),
            Column::make('causer_type'),
            Column::make('causer_id')->title(__('Causer Name')),
            Column::make('created_at'),
            Column::make('properties'),
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename(): string
    {
        return 'ActivityLog_' . date('YmdHis');
    }
}
