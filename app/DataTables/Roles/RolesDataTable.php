<?php

namespace App\DataTables\Roles;

use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Spatie\Permission\Models\Role;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class RolesDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder $query Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            // ->editColumn('name', function (Role $permission) {
            //     return ucwords($permission->name);
            // })
            // ->addColumn('assigned_to', function (Role $permission) {
            //     $roles = $permission->roles;
            //     return view('pages.apps.user-management.permissions.columns._assign-to', compact('roles'));
            // })
            // ->editColumn('permission', function (Role $role) {
            //     $getpermissions = $role->permissions;
            //     return $getpermissions->pluck('name')->toArray();
            // })
            ->addColumn('actions', function (Role $model) {
                return view('pages.roles._actions', compact('model'));
            })
            ->setRowId('id');
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(Role $model): QueryBuilder
    {
        return $model->newQuery();
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        // return $this->builder()
        //     ->setTableId('roles-table')
        //     ->columns($this->getColumns())
        //     ->minifiedAjax()
        //     ->dom('rtp')
        //     ->addTableClass('table align-middle table-row-dashed fs-6 gy-5 dataTable no-footer')
        //     ->setTableHeadClass('text-start text-muted fw-bold fs-7 text-uppercase gs-0')
        //     ->orderBy(0)
        //     ->drawCallback("function() {" . file_get_contents(resource_path('views/pages//apps/user-management/permissions/columns/_draw-scripts.js')) . "}");
        $createButton = []; // Initialize the $createButton array

        // Check if the user is authenticated and has permission to create
        if (Auth::check() && Auth::user()->can('Role Create')) {
            $createButton[] = Button::make([
                'create',

            ]);


        }

        return $this->builder()
            ->setTableId('roles-table')
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
        //     //     // Button::make('export'),
        //     //     // Button::make('print'),
        //     //     // Button::make('reset'),
        //     //     // Button::make('reload')
        // ]);
    }

    /**
     * Get the dataTable columns definition.
     */
    public function getColumns(): array
    {
        return [
            Column::make('id'),
            Column::make('name'),
            Column::make('guard_name'),
            Column::make('created_at'),
            Column::computed('actions')
                ->addClass('text-end')
                ->exportable(false)
                ->printable(false),
        ];
    }

    /**
     * Get the filename for export.
     */
    protected function filename(): string
    {
        return 'Roles' . date('YmdHis');
    }
}
