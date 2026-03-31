<?php

namespace App\DataTables;

use App\Models\Employee;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class EmployeeDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder<Employee> $query Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addColumn('action', function ($query) {
                // $editBtn = "<a href='" . route('admin.employees.edit', $query->id) . "'class='btn btn-primary'><i class='far fa-edit'></i></a>";
                $deleteBtn = "<a href='" . route('admin.employees.destroy', $query->id) . "'class='btn btn-danger ml-2 delete-item'><i class='fas fa-trash'></i></i></a>";
                // $show="<a href='" . route('admin.employees.show', $query->user->id) . "'class='btn btn-info ml-2'><i class='fas fa-eye'></i></a>";
                $show="<a href='" . route('admin.employees.show', $query->id) . "'class='btn btn-info ml-2'><i class='fas fa-eye'></i></a>";
                // $show = $query->user
                // ? "<a href='" . route('admin.employees.show', $query->id) . "' class='btn btn-info ml-2'>
                // <i class='fas fa-eye'></i>
                // </a>"
                // : '-';
                // return $editBtn . $deleteBtn;
                return $show . $deleteBtn;
            })
            ->addColumn('name', function ($query) {
                return $query->user ? $query->user->name : '-';
            })
            ->addColumn('status', function ($query) {
                $status = $query->user ? $query->user->status : 0;

                if ($status == 1) {
                    $activeButton = '<label class="custom-switch mt-2">
                    <input type="checkbox" checked name="custom-switch-checkbox" class="custom-switch-input change-status" data-id="' . ($query->user ? $query->user->id : 0) . '" >
                    <span class="custom-switch-indicator"></span>
                    </label>';
                } else {
                    $activeButton = '<label class="custom-switch mt-2">
                    <input type="checkbox" name="custom-switch-checkbox" class="custom-switch-input change-status" data-id="' . ($query->user ? $query->user->id : 0) . '" >
                    <span class="custom-switch-indicator"></span>
                    </label>';
                }

                return $activeButton;
            })
            ->addColumn('email', function ($query) {
                return $query->user ? $query->user->email : '-';
            })
            ->rawColumns(['action', 'email', 'status'])
            ->setRowId('id');
    }

    /**
     * Get the query source of dataTable.
     *
     * @return QueryBuilder<Employee>
     */
    public function query(Employee $model): QueryBuilder
    {
        return $model->with('user')->newQuery();
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('employee-table')
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
            Column::make('email'),
            Column::make('designation'),
            Column::make('status'),
            // Column::make('updated_at'),
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
        return 'Employee_' . date('YmdHis');
    }
}
