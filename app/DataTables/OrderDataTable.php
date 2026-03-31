<?php

namespace App\DataTables;

use App\Models\Order;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class OrderDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder<Order> $query Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addColumn('action', function ($query) {
                $showBtn = "<a href='" . route('admin.order.show', $query->id) . "'class='btn btn-primary'><i class='far fa-eye'></i></a>";
                $deleteBtn = "<a href='" . route('admin.order.destroy', $query->id) . "'class='btn btn-danger ml-2 delete-item'><i class='fas fa-trash'></i></a>";


                return $showBtn . $deleteBtn;
            })
            ->addColumn('customer', function ($query) {
                return $query->customer->name ?? 'N/A';
            })
            ->addColumn('date', function ($query) {
                return date('d-M-Y', strtotime($query->created_at));
            })
            ->addColumn('status_name', function ($row) {
                // return $row->orderStatus ? $row->orderStatus->name : '-';

                $status = $row->orderStatus;

                if (!$status) {
                    return "<span class='badge bg-secondary'>Unknown</span>";
                }

                $name = strtolower($status->name);


                $colorMap = [
                    'pending'            => 'warning',   // yellow
                    'processed'          => 'info',      // light blue
                    'on hold'            => 'info',      // light blue
                    'hold'               => 'info',
                    'shipped'            => 'primary',   // blue
                    'out for delivery'   => 'primary',   // blue
                    'delivered'          => 'success',   // green
                    'cancelled'          => 'danger',    // red
                    'canceled'           => 'danger',
                    'returned'           => 'secondary', // gray
                    'refunded'           => 'secondary', // gray
                    'failed'             => 'danger',
                    'payment failed'     => 'danger',
                    'completed'          => 'success',
                    'approved'           => 'success',
                    'processing'         => 'info'
                ];


                $color = $colorMap[$name] ?? 'primary';

                return "<span class='badge bg-{$color} text-capitalize white_color '>{$status->name}</span>";
            })
            ->addColumn('payment_status', function ($query) {
                if ($query->payment_status === 1) {
                    return "<span class='badge bg-success white_color' >Complete</span>";
                } else {
                    return "<span class='badge bg-warning white_color'>Pending</span>";
                }
            })
            ->addColumn('amount', function ($query) {
                return $query->currency_icon . $query->amount;
            })
            ->rawColumns(['action', 'order_status', 'payment_status', 'status_name'])
            ->setRowId('id');
    }

    /**
     * Get the query source of dataTable.
     *
     * @return QueryBuilder<Order>
     */
    public function query(Order $model): QueryBuilder
    {
        return $model->newQuery();
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('order-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->orderBy(0, 'desc')
            ->selectStyleSingle()
            ->processing(true)
            ->dom('lBfrtip')
            ->buttons([
                Button::make('excel')->addClass('btn btn-primary'),
                Button::make('csv')->addClass('btn btn-primary'),
                Button::make('pdf')->addClass('btn btn-primary'),
                Button::make('print')->addClass('btn btn-primary'),
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
            Column::make('invoice_id'),
            Column::make('customer'),
            Column::make('date')
                ->width(120),
            Column::make('product_qty')
                ->width(40),
            Column::make('amount'),
            Column::make('status_name')
                ->title('Order Status')
                ->width(60),
            Column::make('payment_status'),
            Column::make('payment_method')
                ->width(50),
            Column::computed('action')
                ->exportable(false)
                ->printable(false)
                ->width(180)
                ->addClass('text-center'),
        ];
    }

    /**
     * Get the filename for export.
     */
    protected function filename(): string
    {
        return 'Order_' . date('YmdHis');
    }
}
