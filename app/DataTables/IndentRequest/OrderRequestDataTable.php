<?php

namespace App\DataTables\IndentRequest;

use App\Core\CommonComponent;
use App\Models\WarehouseIndentRequest;
use Auth;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class OrderRequestDataTable extends DataTable
{
    /**
     * Build DataTable class.
     *
     * @param QueryBuilder $query Results from query() method.
     * @return \Yajra\DataTables\EloquentDataTable
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        // return (new EloquentDataTable($query))
        // $query = WarehouseIndentRequest::query()->where('supplier_id', Auth::user()->id);

        return (new EloquentDataTable($query))
            ->rawColumns(['status', 'action'])
            ->addColumn('status', function (WarehouseIndentRequest $model) {
                $indent_status = $model->status;
                return view('pages.partials.statuslabel', compact('indent_status'));
            })
            ->editColumn('name', function (WarehouseIndentRequest $model) {
                return $model->created_by_details->first_name . '-' . $model->created_by_details->last_name;
            })
            ->editColumn('phone_number', function (WarehouseIndentRequest $model) {
                return $model->created_by_details->phone_number;
            })
            ->editColumn('request_date', function (WarehouseIndentRequest $model) {
                return CommonComponent::getDateFormat($model->request_date);
            })
            ->editColumn('expected_date', function (WarehouseIndentRequest $model) {
                return CommonComponent::getDateFormat($model->expected_date);
            })
            ->editColumn('created_at', function (WarehouseIndentRequest $model) {
                return CommonComponent::getCreatedAtFormat($model->created_at);
            })
            ->addColumn('action', function (WarehouseIndentRequest $model) {
                return view('supplier.order_request._action-menu', compact('model'));
            });
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\WarehouseIndentRequest $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(WarehouseIndentRequest $model): QueryBuilder
    {
        return $model = WarehouseIndentRequest::query()->where([['supplier_id', Auth::user()->id], ['status', '<=', 5]]);
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\DataTables\Html\Builder
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('orderrequest-table')
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
            ->buttons([
                Button::make('create'),
                // Button::make('export'),
                // Button::make('print'),
                // Button::make('reset'),
                // Button::make('reload')
            ]);
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
            Column::make('name')->title(__('Name')),
            Column::make('phone_number')->title(__('Phone Number')),
            Column::make('request_code')->title(__('Request Code')),
            Column::make('request_date')->title(__('Request Date')),
            Column::make('expected_date')->title(__('Expected Date')),
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
        return 'OrdersRequest_' . date('YmdHis');
    }
}
