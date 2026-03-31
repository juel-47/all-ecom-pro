<?php

namespace App\DataTables;

use App\Models\MobilePayTransaction;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class MobilePayTransactionDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder<MobilePayTransaction> $query Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addColumn('transaction_id', function ($row) {
                return $row->response['transaction_id'] ?? 'N/A';
            })
            ->addColumn('order_id', function ($row) {
                return $row->response['order_id'] ?? $row->order_id;
            })
            ->addColumn('amount', function ($row) {
                return '$' . number_format($row->response['amount'] ?? $row->amount, 2);
            })
            // ->addColumn('status', function ($row) {
            //     $status = $row->response['status'] ?? $row->status;
            //     if ($status == 'success') {
            //         return '<span class="badge bg-success">Success</span>';
            //     } elseif ($status == 'pending') {
            //         return '<span class="badge bg-warning">Pending</span>';
            //     } else {
            //         return '<span class="badge bg-danger">Failed</span>';
            //     }
            // })
            ->addColumn('payer_name', function ($row) {
                return $row->response['payer_info']['name'] ?? 'N/A';
            })
            ->addColumn('created_at', function ($row) {
                return $row->created_at->format('Y-m-d H:i');
            })
            ->rawColumns(['status'])
            ->setRowId('id');
    }

    /**
     * Get the query source of dataTable.
     *
     * @return QueryBuilder<MobilePayTransaction>
     */
    public function query(MobilePayTransaction $model): QueryBuilder
    {
        return $model->newQuery();
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('mobilepaytransaction-table')
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
            Column::make('transaction_id'),
            Column::make('order_id'),
            Column::make('amount'),
            Column::make('payer_name'),
            // Column::computed('action')
            //     ->exportable(false)
            //     ->printable(false)
            //     ->width(60)
            //     ->addClass('text-center'),
        ];
    }

    /**
     * Get the filename for export.
     */
    protected function filename(): string
    {
        return 'MobilePayTransaction_' . date('YmdHis');
    }
}
