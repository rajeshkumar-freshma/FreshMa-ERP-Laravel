<?php

namespace App\DataTables\Sales;

use App\Core\CommonComponent;
use App\Models\MachineData;
use App\Models\MachineSalesBill;
use App\Models\SalesOrder;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class MachineSaleDataTable extends DataTable
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
            ->rawColumns(['machine_name','order_count','total_order_amount','action'])
            ->order(function ($model) {
                $model->orderBy('id', 'DESC');
            })
            ->addColumn('machine_name', function (SalesOrder $model) {
                return $model->machine_details->machine_name;
            })
            ->addColumn('order_count', function (SalesOrder $model) {
                return $model->machine_details->order_count;
            })
            ->addColumn('total_order_amount', function (SalesOrder $model) {
                return $model->machine_details->total_order_amount;
            })
            ->editColumn('created_at', function (SalesOrder $model) {
                return CommonComponent::getCreatedAtFormat($model->created_at);
            })
            ->addColumn('action', function (SalesOrder $datas) {
                return view('pages.report.sales_report._action-menu', compact('datas'));
            });
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\SalesOrder $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(SalesOrder $model): QueryBuilder
    {
        $query = $model->newQuery()->where(function ($provider) {
            if (request('billNo')) {
                $provider->where('billNo', request('billNo'));
            }
        });
        return $this->applyScopes($query);
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\DataTables\Html\Builder
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('machinesale-table')
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
            // ->editors([
            //     Editor::make()
            //     ->fields([
            //         Fields\Text::make('name'),
            //         Fields\Text::make('email'),
            //     ]),
            // ])
            ->buttons([
                Button::make('create')
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
            Column::make('store_id')->title(__('Store')),
            Column::make('machine_name')->title(__('Machine Name')),
            Column::make('order_count')->title(__('Order Count')),
            Column::make('total_order_amount')->title(__('Total Order Amount')),
            Column::make('created_at')->title(__('Created At')),
            Column::computed('action')
                ->exportable(false)
                ->printable(false)
                ->addClass('text-center')
                ->responsivePriority(-1)
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename(): string
    {
        return 'MachineSale_' . date('YmdHis');
    }
}
