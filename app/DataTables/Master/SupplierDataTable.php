<?php

namespace App\DataTables\Master;

use App\Core\CommonComponent;
use App\Models\Supplier;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class SupplierDataTable extends DataTable
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
            ->rawColumns(['status', 'action'])
            ->addColumn('action', 'status', 'user_type')
            ->addIndexColumn()
            ->order(function ($model) {
                $model->orderBy('id', 'DESC');
            })
            ->addColumn('status', function (Supplier $model) {
                $status = $model->status;
                return view('pages.partials.status_toggle_master', ['model' => $model, 'entity' => 'supplier']);
            })
            ->editColumn('company', function (Supplier $model) {
                return @$model->user_info->company;
            })
            ->editColumn('gst_number', function (Supplier $model) {
                return @$model->user_info->gst_number;
            })
            ->editColumn('created_at', function (Supplier $model) {
                return CommonComponent::getCreatedAtFormat($model->created_at);
            })
            ->addColumn('user_type', function (Supplier $model) {
                $user_type = $model->user_type;
                return view('pages.partials.statuslabel', compact('user_type'));
            })
            ->addColumn('action', function (Supplier $model) {
                return view('pages.master.supplier._action-menu', compact('model'));
            });
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\Supplier $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(Supplier $model): QueryBuilder
    {
        $query = $model->newQuery()->with(['user_info:id,user_id,company,gst_number']);

        if ($this->request()->filled('status')) {
            $query->where('status', $this->request()->get('status'));
        }

        if ($this->request()->filled('date_from')) {
            $query->where('created_at', '>=', $this->request()->get('date_from') . ' 00:00:00');
        }

        if ($this->request()->filled('date_to')) {
            $query->where('created_at', '<=', $this->request()->get('date_to') . ' 23:59:59');
        }

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
        if (Auth::check() && Auth::user()->can('Supplier Create')) {
            $createButton[] = Button::make('create')->className('btn btn-success btn-xs btn-sm');

        }

        return $this->builder()
            ->setTableId('supplier-table')
            ->columns($this->getColumns())
            ->minifiedAjax('', 'data.date_from = $("#supplier-table-date-from").val(); data.date_to = $("#supplier-table-date-to").val(); data.status = $("#supplier-table-status-filter").val();')
            ->stateSave(false)
            ->responsive(false)
            ->autoWidth(false)
            ->parameters([
                'processing' => true,
                'serverSide' => true,
                'scrollX' => true,
                'deferRender' => true,
                'searchDelay' => 350,
                'drawCallback' => 'function() { KTMenu.createInstances(); }',
            ])
            ->addTableClass('align-middle table-row-dashed table-sm fs-7 gy-1 text-nowrap')
            ->dom("<'d-flex justify-content-between mb-3'B>rtip")
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
            Column::make('user_type')->title(__('User Type')),
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
        return 'Supplier_' . date('YmdHis');
    }
}



