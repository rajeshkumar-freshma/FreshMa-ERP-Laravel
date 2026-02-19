<?php

namespace App\DataTables\Setting;

use App\Core\CommonComponent;
use App\Models\ApiKeySetting;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class ApiKeySettingDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder $query Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addIndexColumn()
            ->rawColumns(['admin_id', 'status', 'action'])
            ->order(function ($model) {
                $model->orderBy('id', 'DESC');
            })
            ->addColumn('status', function (ApiKeySetting $model) {
                $status = $model->status;
                return view('pages.partials.statuslabel', compact('status'));
            })
            ->addColumn('action', function (ApiKeySetting $model) {
                return view('pages.setting.api_key_setting._action-menu', compact('model'));
            });
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(ApiKeySetting $model): QueryBuilder
    {
        $query = $model->newQuery();
        return $this->applyScopes($query);
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {


        return $this->builder()
            ->setTableId('apikeysettings-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->stateSave(false)
            ->responsive()
            ->autoWidth(true)
            ->parameters([
                'scrollX' => true,
                'drawCallback' => 'function() { KTMenu.createInstances(); }',
            ])
            ->addTableClass('align-middle table-striped table-row-dashed fs-6 gy-1')
            // ->dom('Bfrtip');
        ;
    }

    /**
     * Get the dataTable columns definition.
     */
    public function getColumns(): array
    {
        return [
            Column::make('id')->title('S.No')->render('meta.row + meta.settings._iDisplayStart + 1;')->addClass('ps-0'),
            Column::make('name')->title(__('Name')),
            Column::make('api_key')->title('Api Key'),
            Column::make('status'),
            Column::make('created_at')->title(__('Created At')),
            Column::computed('action')
                ->exportable(false)
                ->printable(false)
                ->addClass('text-center')
                ->responsivePriority(-1)
        ];
    }

    /**
     * Get the filename for export.
     */
    protected function filename(): string
    {
        return 'APiKeySetting' . date('YmdHis');
    }
}
