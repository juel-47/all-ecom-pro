<?php

namespace App\DataTables;

use App\Models\ShippingMethod;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class ShippingMethodDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder<ShippingMethod> $query Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addColumn('action', function ($query) {
                $editBtn = "<a href='" . route('admin.shipping-methods.edit', $query->id) . "'class='btn btn-primary'><i class='far fa-edit'></i></a>";
                $deleteBtn = "<a href='" . route('admin.shipping-methods.destroy', $query->id) . "'class='btn btn-danger ml-2 delete-item'><i class='fas fa-trash'></i></i></a>";
                $chargeBtn = "<a href='" . route('admin.shipping.charge', $query->id) . "' class='btn btn-info ml-1'><i class='fas fa-dollar-sign'></i> Charges</a>";
                return $editBtn . $deleteBtn . $chargeBtn;
            })
            ->addColumn('charge', function ($query) {
                $html = '';
                foreach ($query->charges as $charge) {
                    $html .= $charge->country->name;
                    if ($charge->state) {
                        $html .= ' (' . $charge->state->name . ')';
                    }
                    // $html .= ': ' . $charge->base_charge . '$ [' . $charge->min_weight . 'kg - ' . $charge->max_weight . 'kg]<br>';
                    $html .= ': ' . $charge->base_charge . '$ [' . $charge->min_weight . ' - ' . $charge->max_weight . ']<br>';
                }
                return $html;
            })
            // ->addColumn('type', function ($query) {
            //     if ($query->type == 'international') {
            //         return '<i class="badge badge-primary">International</i>';
            //     } else if ($query->type == 'local') {
            //         return '<i class="badge badge-primary">Local</i>';
            //     } elseif ($query->type == 'flat_rate') {
            //         return '<i class="badge badge-primary">Flat Rate</i>';
            //     } elseif ($query->type == 'free_shipping') {
            //         return '<i class="badge badge-primary">Free Shipping</i>';
            //     } elseif ($query->type == 'express') {
            //         return '<i class="badge badge-primary">Express</i>';
            //     } elseif ($query->type == 'same_day_delivery') {
            //         return '<i class="badge badge-primary">Same Day Delivery</i>';
            //     } elseif ($query->type == 'Pickup') {
            //         return '<i class="badge badge-primary">Pickup</i>';
            //     } elseif ($query->type == 'courier') {
            //         return '<i class="badge badge-primary">courier</i>';
            //     } else {
            //         return '<i class="badge badge-primary">Other</i>';
            //     }
            // })
            ->addColumn('type', function ($query) {
                $types = json_decode($query->type, true) ?? [];
                $html = '';
                foreach ($types as $type) {
                    $html .= '<span class="badge badge-primary mr-1">' . ucfirst($type) . '</span>';
                }
                return $html;
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
            ->rawColumns(['action', 'charge', 'type', 'status'])
            ->setRowId('id');
    }

    /**
     * Get the query source of dataTable.
     *
     * @return QueryBuilder<ShippingMethod>
     */
    public function query(ShippingMethod $model): QueryBuilder
    {
        return $model->with('charges.country', 'charges.state')->newQuery();
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('shippingmethod-table')
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
            Column::make('id'),
            Column::make('name'),
            Column::make('charge'),
            Column::make('type'),
            Column::make('status'),
            Column::computed('action')
                ->exportable(false)
                ->printable(false)
                ->width(280)
                ->addClass('text-center'),
        ];
    }

    /**
     * Get the filename for export.
     */
    protected function filename(): string
    {
        return 'ShippingMethod_' . date('YmdHis');
    }
}
