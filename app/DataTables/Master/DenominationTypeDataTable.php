<?php

namespace App\DataTables\Master;

use App\Core\CommonComponent;
use App\Models\DenominationType;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Facades\DataTables;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class DenominationTypeDataTable extends DataTable
{
    /**
     * Build DataTable class.
     *
     * @param QueryBuilder $query Results from query() method.
     * @return \Yajra\DataTables\EloquentDataTable
     */
    public function dataTable($query)
    {
        // Log::info($query->toSql());
        $data = datatables()
            ->eloquent($query)
            ->rawColumns(['status', 'action'])
            ->order(function ($model) {
                $model->orderBy('id', 'ASC');
            })
            ->addColumn('status', function (DenominationType $model) {
                $status = $model->status;
                return view('pages.partials.statuslabel', compact('status'));
            })
            ->editColumn('created_at', function (DenominationType $model) {
                return CommonComponent::getCreatedAtFormat($model->created_at);
            })
            ->addColumn('action', function (DenominationType $model) {
                return view('pages.master.denomination._action-menu', compact('model'));
            });

        return $data;
    }

    /**
     * Get query source of dataTable.
     *
     * @param  DenominationType  $model
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(DenominationType $model)
    {
        $query = $model->newQuery();

        return $this->applyScopes($query);
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\DataTables\Html\Builder
     */
    public function html()
    {
        $createButton = [];

        // Check if the user is authenticated and has permission to create
        if (Auth::check() && Auth::user()->can('Item Type Create')) {
            $createButton[] = Button::make('create');

        }

        return $this->builder()
            ->setTableId('item-type-table')
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
        // ->buttons([
        //     Button::make('create'),
        //     // Button::make('export'),
        //     // Button::make('print'),
        //     // Button::make('reset'),
        //     // Button::make('reload')
        // ]);
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
            Column::make('value')->title(__('Amount')),
            Column::make('denomination_code')->title(__('Denomination Code')),
            Column::make('description')->title(__('Description')),
            Column::make('created_at')->title(__('Created At')),
            Column::make('status'),
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
        return 'DenominationType' . date('YmdHis');
    }
}
