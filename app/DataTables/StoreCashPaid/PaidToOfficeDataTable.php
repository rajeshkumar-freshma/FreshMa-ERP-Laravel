<?php

namespace App\DataTables\StoreCashPaid;

use App\Models\CashPaidToOffice;
use Spatie\Activitylog\Models\Activity;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;

class PaidToOfficeDataTable extends DataTable
{
    /**
     * Build DataTable class.
     *
     * @param  mixed  $query  Results from query() method.
     *
     * @return \Yajra\DataTables\DataTableAbstract
     */
    public function dataTable($query)
    {
        return (new EloquentDataTable($query))
            ->addIndexColumn()
            ->rawColumns(['status', 'action', 'store_id'])
            ->order(function ($model) {
                $model->orderBy('id', 'DESC');
            })
            ->editColumn('store_id', function (CashPaidToOffice $model) {
                return $model->store->store_name ?? '';
            })
            ->editColumn('payer_id', function (CashPaidToOffice $model) {
                return $model->payer_details->first_name ?? '';
            })
            ->editColumn('receiver_id', function (CashPaidToOffice $model) {
                return $model->receiver_details->first_name ?? '';
            })
            // ->editColumn('is_notification_send_to_admin', function (CashPaidToOffice $model) {
            //     $is_notification_send_to_admin = $model->receiver_details->first_name ?? '';
            //     return view('pages.partials.statuslabel', compact('is_notification_send_to_admin'));
            // })
            ->editColumn('created_at', function (CashPaidToOffice $model) {
                return $model->created_at->format('d M, Y H:i:s') ?? '';
            })
            ->addColumn('status', function (CashPaidToOffice $model) {
                $status = $model->status;
                return view('pages.partials.statuslabel', compact('status'));
            })
            ->addColumn('action', function (CashPaidToOffice $model) {
                return view('pages.store_cash_paid.paid_to_office._action-menu', compact('model'));
            });
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\CashPaidToOffice $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(CashPaidToOffice $model)
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
        if (Auth::check() &&  Auth::user()->can('Cash Paind To Offiice Create')) {
            $createButton[] = Button::make('create');


        }

        return $this->builder()
            ->setTableId('cash_paid_to_office-table')
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
     * Get columns.
     *
     * @return array
     */
    protected function getColumns()
    {
        return [
            Column::make('id')->title('S.No')->render('meta.row + meta.settings._iDisplayStart + 1;')->addClass('ps-0'),
            Column::make('store_id')->title(__('Store')),
            Column::make('amount'),
            Column::make('payer_id')->title(__('Payer')),
            Column::make('receiver_id')->title(__('Receiver')),
            // Column::make('is_notification_send_to_admin'),
            Column::make('status'),
            Column::make('created_at'),
            Column::computed('action')
                ->exportable(false)
                ->printable(false)
                ->addClass('text-center')
                ->responsivePriority(-1),
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
        return 'CashPaidToOffice_' . date('YmdHis');
    }
}
