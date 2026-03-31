<?php

namespace App\DataTables;

use App\Models\Promotion;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class PromotionDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder<Promotion> $query Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addColumn('action', function ($query) {
                $editBtn = "<a href='" . route('admin.promotions.edit', $query->id) . "'class='btn btn-primary'><i class='far fa-edit'></i></a>";
                $deleteBtn = "<a href='" . route('admin.promotions.destroy', $query->id) . "'class='btn btn-danger ml-2 delete-item'><i class='fas fa-trash'></i></i></a>";
                return $editBtn . $deleteBtn;
            })
            ->addColumn('category', function ($query) {
                return limitText($query->category?->name ?? 'N/A', 20);
            })
            ->addColumn('product', function ($query) {
                return limitText($query->product?->name ?? 'N/A', 20);
            })
            ->addColumn('get_quantity', function ($query) {
                return $query?->get_quantity ?? '-';
            })
            // ->addColumn('date_range', function ($query) {
            //     // return $query->start_date . ' - ' . $query->end_date;
            //     $start = Carbon::parse($query?->start_date)->format('M d, Y h:i A') ?? 'N/A';
            //     $end = Carbon::parse($query?->end_date)->format('M d, Y h:i A') ?? 'N/A';
            //     return "$start - $end";
            // })
            ->addColumn('date_range', function ($query) {
                $start = $query->start_date
                    ? Carbon::parse($query->start_date)->format('M d, Y h:i A')
                    : '-';

                $end = $query->end_date
                    ? Carbon::parse($query->end_date)->format('M d, Y h:i A')
                    : '-';

                return "$start - $end";
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
            ->addColumn('type', function ($query) {
                if ($query->type == 'free_shipping') {
                    return 'Free Shipping';
                } elseif ($query->type == 'free_product') {
                    return 'Free Product';
                }
            })

            ->rawColumns(['action', 'status', 'type'])
            ->setRowId('id');
    }

    /**
     * Get the query source of dataTable.
     *
     * @return QueryBuilder<Promotion>
     */
    public function query(Promotion $model): QueryBuilder
    {
        return $model->newQuery();
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('promotion-table')
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
            Column::make('title'),
            Column::make('type'),
            Column::make('category'),
            Column::make('product'),
            Column::make('buy_quantity')
                ->addClass('text-center'),
            Column::make('get_quantity')
                ->addClass('text-center'),
            Column::make('date_range')
                ->width(200)
                ->addClass('text-center'),

            // Column::make('start_date'),
            // Column::make('end_date'),
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
        return 'Promotion_' . date('YmdHis');
    }
}
