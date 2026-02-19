<?php

namespace App\DataTables\Setting;

use App\Models\EmailTemplate;
use App\Models\AccountType;
use App\Models\Staff;
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

class EmailTemplateDataTable extends DataTable
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
            ->addIndexColumn()
            ->rawColumns(['account_type', 'action', 'status'])

            ->editColumn('account_type', function (EmailTemplate $model) {
                $account_type = $model->account_type;
                return view('pages.partials.statuslabel', compact('account_type'));
            })
            ->editColumn('status', function (EmailTemplate $model) {
                $status = $model->status;
                return view('pages.partials.statuslabel', compact('status'));
            })
            ->addColumn('action', function (EmailTemplate $model) {
                return view('pages.accounting.account._action-menu', compact('model'));
            });
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\EmailTemplate $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(EmailTemplate $model): QueryBuilder
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
        if (Auth::check() &&  Auth::user()->can('Accounts Create')) {
            $createButton[] = Button::make('create');
        }

        return $this->builder()
            ->setTableId('accounts-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->stateSave(false)
            ->responsive()
            ->autoWidth(true)
            // ->selectStyleSingle()
            ->parameters([
                'scrollX' => true,
                'drawCallback' => 'function() { KTMenu.createInstances(); }',
            ])
            ->addTableClass('align-middle table-row-dashed fs-6 gy-5')
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
            Column::make('account_holder_name')->title(__('Holder Name')),
            Column::make('account_number'),
            Column::make('account_type'),
            Column::make('bank_name')->title(__('Bank Name')),
            Column::make('branch_name')->title(__('Branch Name')),
            Column::make('balance'),
            // Column::make('is_half_day'),
            Column::make('address'),
            Column::make('notes'),
            Column::make('status'),
            Column::computed('action')
                ->exportable(false)
                ->printable(false)
                ->addClass('text-center')
                ->responsivePriority(-1)
            // Column::make('properties')->addClass('none'),
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename(): string
    {
        return 'EmailTemplate' . date('YmdHis');
    }
}
