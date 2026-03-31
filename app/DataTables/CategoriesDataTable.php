<?php

namespace App\DataTables;

use App\Models\Category;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class CategoriesDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder<Category> $query Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addColumn('action', function ($query) {
                $editBtn = "<a href='" . route('admin.category.edit', $query->id) . "'class='btn btn-primary'><i class='far fa-edit'></i></a>";
                $deleteBtn = "<a href='" . route('admin.category.destroy', $query->id) . "'class='btn btn-danger ml-2 delete-item'><i class='fas fa-trash'></i></i></a>";
                return $editBtn . $deleteBtn;
            })
            ->addColumn('icon', function ($query) {
                $iconClass = $query->icon ? $query->icon : 'fas fa-question';
                return '<i style="font-size:20px" class="' . $iconClass . '"></i>';
            })
            ->addColumn('image', function ($query) {
                return '<img src="' . asset($query->image) . '" class="img-fluid" width="50" height="50" />';
            })
            ->addColumn('front_show', function ($query) {
                if ($query->front_show == 1) {
                    $activeButton = '<label class="custom-switch mt-2">
                <input type="checkbox" checked name="custom-switch-checkbox" class="custom-switch-input front_show" data-id="' . $query->id . '" >
                <span class="custom-switch-indicator"></span>
              </label>';
                } else {
                    $activeButton = '<label class="custom-switch mt-2">
                <input type="checkbox" name="custom-switch-checkbox" class="custom-switch-input front_show" data-id="' . $query->id . '" >
                <span class="custom-switch-indicator"></span>
              </label>';
                }
                return $activeButton;
            })
            ->addColumn('status', function ($query) {
                if ($query->status == 1) {
                    $activeButton = '<label class="custom-switch mt-2">
                <input type="checkbox" checked name="custom-switch-checkbox" class="custom-switch-input change-status" data-id="' . $query->id . '" >
                <span class="custom-switch-indicator"></span>
              </label>';
                } else {
                    $activeButton = '<label class="custom-switch mt-2">
                <input type="checkbox" name="custom-switch-checkbox" class="custom-switch-input change-status" data-id="' . $query->id . '" >
                <span class="custom-switch-indicator"></span>
              </label>';
                }
                return $activeButton;
            })
            ->rawColumns(['action', 'icon', 'status', 'front_show', 'image'])
            ->setRowId('id');
    }

    /**
     * Get the query source of dataTable.
     *
     * @return QueryBuilder<Category>
     */
    public function query(Category $model): QueryBuilder
    {
        return $model->newQuery();
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('categories-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->orderBy(0)
            ->selectStyleSingle()
            ->buttons([
                Button::make('excel'),
                Button::make('csv'),
                Button::make('pdf'),
                Button::make('print'),
                // Button::make('reset'),
                // Button::make('reload')
            ]);
    }

    /**
     * Get the dataTable columns definition.
     */
    public function getColumns(): array
    {
        return [
            Column::make('id')
                ->width('80'),
            Column::make('image')
                ->addClass('text-center'),
            Column::make('icon')
                ->width(180)
                ->addClass('text-center'),
            Column::make('name'),
            Column::make('front_show')
                ->width(100),
            Column::make('status')
                ->width(100),
            Column::computed('action')
                ->exportable(false)
                ->printable(false)
                ->width(200)
                ->addClass('text-center'),
        ];
    }

    /**
     * Get the filename for export.
     */
    protected function filename(): string
    {
        return 'Categories_' . date('YmdHis');
    }
}
