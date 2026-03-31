<?php

namespace App\DataTables;

use App\Models\Coupon;
use App\Models\GeneralSetting;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class CouponDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder<Coupon> $query Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addColumn('action', function ($query) {
                $editBtn = "<a href='" . route('admin.coupons.edit', $query->id) . "'class='btn btn-primary'><i class='far fa-edit'></i></a>";
                $deleteBtn = "<a href='" . route('admin.coupons.destroy', $query->id) . "'class='btn btn-danger ml-2 delete-item'><i class='fas fa-trash'></i></i></a>";
                return $editBtn . $deleteBtn;
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
            // ->addColumn('start_date', function ($query) {
            //     $start = $query->start_date
            //         ? Carbon::parse($query->start_date)->format('m-d-y h:i A')
            //         : '-';
            //     return "$start";
            // })
            // ->addColumn('end_date', function ($query) {

            //     $end = $query->end_date
            //         ? Carbon::parse($query->end_date)->format('m-d-y h:i A')
            //         : '-';

            //     return "$end";
            // })
            ->addColumn('start_date', function ($query) {
                if ($query->start_date) {
                    $date = Carbon::parse($query->start_date)->format('m-d-y');
                    $time = Carbon::parse($query->start_date)->format('h:i A');
                    return $date . '<br>' . $time;
                }
                return '-';
            })
            ->addColumn('end_date', function ($query) {
                if ($query->end_date) {
                    $date = Carbon::parse($query->end_date)->format('m-d-y');
                    $time = Carbon::parse($query->end_date)->format('h:i A');
                    return $date . '<br>' . $time;
                }
                return '-';
            })
            ->addColumn('discount', function ($query) {
                return GeneralSetting::first()->currency_icon . $query->discount;
            })
            ->rawColumns(['action', 'status', 'discount', 'start_date', 'end_date'])
            ->setRowId('id');
    }

    /**
     * Get the query source of dataTable.
     *
     * @return QueryBuilder<Coupon>
     */
    public function query(Coupon $model): QueryBuilder
    {
        return $model->newQuery();
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('coupon-table')
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
            Column::make('name'),
            Column::make('discount_type'),
            Column::make('discount'),
            Column::make('start_date'),
            Column::make('end_date'),
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
        return 'Coupon_' . date('YmdHis');
    }
}
