<?php

namespace App\DataTables\IndentRequest;

use App\Core\CommonComponent;
use App\Models\StoreIndentRequest;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class StoreIndentRequestDataTable extends DataTable
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
            ->addColumn('status', function (StoreIndentRequest $model) {
                $indent_status = $model->status;
                return view('pages.partials.statuslabel', compact('indent_status'));
            })
            ->editColumn('store_id', function (StoreIndentRequest $model) {
                return $model->store->store_name;
            })
            ->editColumn('request_date', function (StoreIndentRequest $model) {
                return CommonComponent::getDateFormat($model->request_date);
            })
            ->editColumn('expected_date', function (StoreIndentRequest $model) {
                return CommonComponent::getDateFormat($model->expected_date);
            })
            ->editColumn('created_at', function (StoreIndentRequest $model) {
                return CommonComponent::getCreatedAtFormat($model->created_at);
            })
            ->addColumn('action', function (StoreIndentRequest $model) {
                return view('pages.indent_request.store._action-menu', compact('model'));
            });
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\StoreIndentRequest $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(StoreIndentRequest $model): QueryBuilder
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
        if (Auth::check() && Auth::user()->can('Store Indent Request Create')) {
            $createButton[] = Button::make('create');

        }

        return $this->builder()
            ->setTableId('storeindentrequest-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->stateSave(false)
            ->responsive()
            ->autoWidth(true)
            ->parameters([
                'scrollX' => true,
                'drawCallback' => 'function() { KTMenu.createInstances(); }',
                'order' => [[0, 'desc']],
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
            Column::make('store_id')->title(__('Store Name')),
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
        return 'StoreIndentRequest_' . date('YmdHis');
    }
}
