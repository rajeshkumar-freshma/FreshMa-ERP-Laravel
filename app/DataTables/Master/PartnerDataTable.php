<?php

namespace App\DataTables\Master;

use App\Core\CommonComponent;
use App\Models\Partner;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class PartnerDataTable extends DataTable
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
            ->addColumn('action', 'status', 'admin_type')
            ->addIndexColumn()
            ->order(function ($model) {
                $model->orderBy('id', 'DESC');
            })
            ->addColumn('status', function (Partner $model) {
                $status = $model->status;
                return view('pages.partials.statuslabel', compact('status'));
            })
            ->editColumn('company', function (Partner $model) {
                return @$model->user_info->company;
            })
            ->editColumn('gst_number', function (Partner $model) {
                return @$model->user_info->gst_number;
            })
            ->editColumn('created_at', function (Partner $model) {
                return CommonComponent::getCreatedAtFormat($model->created_at);
            })
            ->addColumn('admin_type', function (Partner $model) {
                $admin_type = $model->user_type;
                return view('pages.partials.statuslabel', compact('admin_type'));
            })
            ->addColumn('action', function (Partner $model) {
                return view('pages.master.partner._action-menu', compact('model'));
            });
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\Partner $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(Partner $model): QueryBuilder
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

        // Check if the user is authenticated and has permission to create
        if (Auth::check() && Auth::user()->can('Partner/Manager Create')) {
            $createButton[] = Button::make('create');

        }

        return $this->builder()
            ->setTableId('partner-table')
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
            ->dom('Bfrtip')
        // ->selectStyleSingle()
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
            Column::make('first_name')->title(__('First Name')),
            Column::make('last_name')->title(__('Last Name')),
            Column::make('email')->title(__('Email')),
            Column::make('phone_number')->title(__('Phone Number')),
            Column::make('admin_type')->title(__('Admin Type')),
            Column::make('company')->title(__('Company Name')),
            Column::make('gst_number')->title(__('GST Number')),
            Column::make('status'),
            Column::make('created_at')->title(__('Created At')),
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
        return 'Partner_' . date('YmdHis');
    }
}
