<?php

namespace App\DataTables\Setting;

use App\Core\CommonComponent;
use App\Models\UserAppMenuMapping;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class UserMenuMapDataTable extends DataTable
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
            ->addColumn('status', function (UserAppMenuMapping $model) {
                $status = $model->status;
                return view('pages.partials.statuslabel', compact('status'));
            })
            ->editColumn('created_at', function (UserAppMenuMapping $model) {
                return CommonComponent::getCreatedAtFormat($model->created_at);
            })
            ->addColumn('admin_id', function (UserAppMenuMapping $model) {
                if($model->admin_type==2){
                    return $model->supplier_details->name;
                }else{
                    return $model->admin_detail->name;
                }
            })
            ->addColumn('menu_type', function (UserAppMenuMapping $model) {
                return $model->menu_type == 1 ? "Bottom Menu" : "Sidebar Menu";
            })
            ->addColumn('action', function (UserAppMenuMapping $model) {
                return view('pages.setting.app.menu_map._action-menu', compact('model'));
            });
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(UserAppMenuMapping $model): QueryBuilder
    {
        $query = $model->newQuery();
        return $this->applyScopes($query);
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        $createButton = []; // Initialize the $createButton array

        // Check if the user is authenticated and has permission to create
        if (Auth::check() && Auth::user()->can('App Menu Mapping Create')) {
            $createButton[] = Button::make('create');


        }
        return $this->builder()
            ->setTableId('usermenumap-table')
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
            ->dom('Bfrtip')
            ->buttons($createButton);
    }

    /**
     * Get the dataTable columns definition.
     */
    public function getColumns(): array
    {
        return [
            Column::make('id')->title('S.No')->render('meta.row + meta.settings._iDisplayStart + 1;')->addClass('ps-0'),
            Column::make('admin_id')->title(__('Employee')),
            Column::make('menu_type')->title('Menu Type'),
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
        return 'UserMenuMap_' . date('YmdHis');
    }
}
