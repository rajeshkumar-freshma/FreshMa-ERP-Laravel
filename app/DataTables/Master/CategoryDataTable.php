<?php

namespace App\DataTables\Master;

use App\Core\CommonComponent;
use App\Models\Category;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class CategoryDataTable extends DataTable
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
            ->rawColumns(['status', 'image', 'action'])
            ->order(function ($model) {
                $model->orderBy('id', 'DESC');
            })
            ->addColumn('status', function (Category $model) {
                $status = $model->status;
                return view('pages.partials.status_toggle_master', ['model' => $model, 'entity' => 'category']);
            })
            ->addColumn('image', function (Category $model) {
                if ($model->image != null) {
                    $imageData = $model->image_full_url;
                    return view('pages.master.category._action-menu', compact('imageData'));
                }
            })
            ->editColumn('parent_id', function (Category $model) {
                return $model->getParentNameAttribute();
            })
            ->editColumn('created_at', function (Category $model) {
                return CommonComponent::getCreatedAtFormat($model->created_at);
            })
            ->addColumn('action', function (Category $model) {
                return view('pages.master.category._action-menu', compact('model'));
            });
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\Category $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(Category $model): QueryBuilder
    {
        $query = $model->newQuery()->with(['getParent:id,name']);

        if ($this->request()->name != null) {
            $query = $query->where('name', 'LIKE', '%' . $this->request()->name . '%');
        }

        if ($this->request()->filled('status')) {
            $query->where('status', $this->request()->get('status'));
        }

        if ($this->request()->filled('date_from')) {
            $query->where('created_at', '>=', $this->request()->get('date_from') . ' 00:00:00');
        }

        if ($this->request()->filled('date_to')) {
            $query->where('created_at', '<=', $this->request()->get('date_to') . ' 23:59:59');
        }

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
            ->setTableId('category-table')
            ->columns($this->getColumns())
            ->minifiedAjax('', 'data.date_from = $("#category-table-date-from").val(); data.date_to = $("#category-table-date-to").val(); data.status = $("#category-table-status-filter").val();')
            ->stateSave(false)
            ->responsive(false)
            ->autoWidth(false)
            ->parameters([
                'processing' => true,
                'serverSide' => true,
                'scrollX' => true,
                'deferRender' => true,
                'searchDelay' => 350,
                'drawCallback' => 'function() { KTMenu.createInstances(); }',
            ])
            ->addTableClass('align-middle table-row-dashed table-sm fs-7 gy-1 text-nowrap')
            ->dom("<'d-flex justify-content-between mb-3'B>rtip")
            ->buttons([
                Button::make('create')->className('btn btn-success btn-xs btn-sm'),
                // Button::make('export'),
                // Button::make('print'),
                // Button::make('reset'),
                // Button::make('reload')
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
            Column::make('name')->title(__('Category Name')),
            Column::make('parent_id')->title(__('Parent Category')),
            Column::make('status'),
            Column::make('image'),
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
        return 'Category_' . date('YmdHis');
    }
}


