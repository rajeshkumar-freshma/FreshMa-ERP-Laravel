<?php

namespace App\DataTables\IndentRequest;

use App\Core\CommonComponent;
use App\Models\WarehouseIndentRequest;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class WarehouseIndentRequestDataTable extends DataTable
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
            ->rawColumns(['status', 'supplier_id', 'supplier_phone_number', 'action'])
            ->addColumn('status', function (WarehouseIndentRequest $model) {
                $indent_status = $model->status;
                return view('pages.partials.statuslabel', compact('indent_status'));
            })
            ->addColumn('supplier_id', function (WarehouseIndentRequest $model) {
                return @$model->supplier->first_name . " " . @$model->supplier->last_name;
            })
            ->addColumn('supplier_phone_number', function (WarehouseIndentRequest $model) {
                return @$model->supplier->phone_number;
            })
            ->editColumn('expected_date', function (WarehouseIndentRequest $model) {
                return CommonComponent::getDateFormat($model->expected_date);
            })
            ->editColumn('created_at', function (WarehouseIndentRequest $model) {
                return CommonComponent::getCreatedAtFormat($model->created_at);
            })
            ->addColumn('action', function (WarehouseIndentRequest $model) {
                return view('pages.indent_request.warehouse._action-menu', compact('model'));
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
        if (Auth::check() && Auth::user()->can('Warehouse Indent Request Create')) {
            $createButton[] = Button::make('create');

        }

        return $this->builder()
            ->setTableId('warehouseindentrequest-table')
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
            ->buttons($createButton);
        //     ->buttons([
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
            Column::make('supplier_id')->title(__('Supplier Name')),
            Column::make('supplier_phone_number')->title(__('Supplier Phone')),
            Column::make('request_code')->title(__('Request Code')),
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
        return 'WarehouseIndentRequest_' . date('YmdHis');
    }
}
