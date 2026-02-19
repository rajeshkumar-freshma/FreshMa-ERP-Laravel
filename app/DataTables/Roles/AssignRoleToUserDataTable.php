<?php

namespace App\DataTables\Roles;

use App\Models\Admin;
use App\Models\UsersAssingedRole;
use Illuminate\Contracts\Database\Query\Builder;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class AssignRoleToUserDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder $query Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->editColumn('user', function (Admin $user) {
                return view('pages.apps.user-management.roles.columns._user', compact('user'));
            })
            ->addColumn('name', function (Admin $user) {
                return $user->first_name;
            })
            ->addColumn('role_name', function (Admin $user) {
                $role = $user->roles;
                return $role->pluck('name')->toArray();
            })
            ->editColumn('created_at', function (Admin $user) {
                return $user->created_at->format('d M Y, h:i a');
            })
            ->addColumn('action', function (Admin $user) {
                return view('pages.assign_role_to_user._actions', compact('user'));
            })
            ->setRowId('id');
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(Admin $model): QueryBuilder
    {
        $query = $model->newQuery();
        // Filter users who have roles assigned
        $query->whereHas('roles');
        return $query;
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
            // ->setTableId('usersassingedrole-table')
            // ->columns($this->getColumns())
            // ->minifiedAjax()
            // ->dom('rtp')
            // ->addTableClass('table align-middle table-row-dashed fs-6 gy-5 dataTable no-footer')
            // ->setTableHeadClass('text-start text-muted fw-bold fs-7 text-uppercase gs-0')
            // ->orderBy(1)
            // ->drawCallback("function() {" . file_get_contents(resource_path('views/pages//apps/user-management/users/columns/_draw-scripts.js')) . "}");
            // return $this->builder()
            ->setTableId('usersassingedrole-table')
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
                //     // Button::make('export'),
                //     // Button::make('print'),
                //     // Button::make('reset'),
                //     // Button::make('reload')
            ]);
    }

    /**
     * Get the dataTable columns definition.
     */
    public function getColumns(): array
    {
        return [
            Column::make('id'),
            Column::make('user')->addClass('d-flex align-items-center')->name('name'),
            Column::make('role_name'),
            Column::make('created_at')->title('Joined Date'),
            Column::computed('action')
                ->addClass('text-end')
                ->exportable(false)
                ->printable(false)
                ->width(60)
                ->addClass('text-center'),
        ];
    }

    /**
     * Get the filename for export.
     */
    protected function filename(): string
    {
        return 'UsersAssingedRole_' . date('YmdHis');
    }
}
