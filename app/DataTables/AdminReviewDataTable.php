<?php

namespace App\DataTables;

use App\Models\AdminReview;
use App\Models\ProductReview;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class AdminReviewDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder<AdminReview> $query Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addColumn('action', function ($query) {
                $deleteBtn = "<a href='" . route('admin.reviews.destroy', $query->id) . "'class='btn btn-danger ml-2 delete-item'><i class='fas fa-trash'></i></i></a>";
                return $deleteBtn;
            })
            ->addColumn('product', function ($query) {
                return $query->product->name;
            })
            ->addColumn('user', function ($query) {
                    return optional($query->user)->name ?? 'N/A';
                // return $query->user->name ?? 'N/A';
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
            ->rawColumns(['action', 'status',])
            ->setRowId('id');
    }

    /**
     * Get the query source of dataTable.
     *
     * @return QueryBuilder<AdminReview>
     */
    public function query(ProductReview $model): QueryBuilder
    {
        return $model->newQuery()->with(['user', 'product']);
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('adminreview-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->orderBy(1)
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
            Column::make('id'),
            Column::make('product'),
            Column::make('user'),
            Column::make('review'),
            Column::make('rating'),
            Column::make('status'),
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
        return 'AdminReview_' . date('YmdHis');
    }
}