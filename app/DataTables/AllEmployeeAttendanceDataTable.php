<?php

namespace App\DataTables;

// use App\Models\AllEmployeeAttendance;
use App\Models\Attendance;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class AllEmployeeAttendanceDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder<AllEmployeeAttendance> $query Results from query() method.
     */
    protected $user_id; // employee_id holder

    // Employee ID set method
    public function setUserId($id)
    {
        $this->user_id = $id;
    }
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {

        return (new EloquentDataTable($query))
            // ->addColumn('action', 'allemployeeattendance.action')
            ->addColumn('employee_name', function ($query) {
                return $query->user->name;
            })
            ->addColumn('start_time', function ($row) {
                return $row->start_time ? Carbon::parse($row->start_time)->format('h:i A') : '-';
            })
            ->addColumn('end_time', function ($row) {
                return $row->end_time ? Carbon::parse($row->end_time)->format('h:i A') : '-';
            })

            ->addColumn('total_hours', function ($row) {
                if ($row->start_time && $row->end_time) {
                    $start = Carbon::parse($row->start_time);
                    $end = Carbon::parse($row->end_time);

                    // Total minutes
                    $totalMinutes = $start->diffInMinutes($end);
                    $hours = intdiv($totalMinutes, 60);
                    $minutes = $totalMinutes % 60;

                    return $hours . 'h:' . $minutes . 'm';
                }

                return '-';
            })
            ->rawColumns(['employee_name', 'start_time', 'end_time', 'total_hours'])
            ->setRowId('id');
    }

    /**
     * Get the query source of dataTable.
     *
     * @return QueryBuilder<AllEmployeeAttendance>
     */
    public function query(Attendance $model): QueryBuilder
    {
        // $query = $model->newQuery()->with('user');

        // if ($this->user_id) {
        //     $query->where('user_id', $this->user_id);
        // }

        // // Get filter params
        // $type = request('type', 'date');
        // $date = request('date');
        // $month = request('month');
        // $year = request('year');

        // if ($type === 'date' && $date) {
        //     $query->whereDate('date', $date);
        // }

        // if ($type === 'week' && $date) {
        //     $start = Carbon::parse($date)->startOfWeek();
        //     $end = Carbon::parse($date)->endOfWeek();
        //     $query->whereBetween('date', [$start, $end]);
        // }

        // if ($type === 'month' && $month) {
        //     $query->whereMonth('date', Carbon::parse($month)->month)
        //         ->whereYear('date', Carbon::parse($month)->year);
        // }

        // if ($type === 'year' && $year) {
        //     $query->whereYear('date', $year);
        // }

        // return $query;
        $query = $model->newQuery()->with('user');

        // Filter by user_id if set
        if ($this->user_id) {
            $query->where('user_id', $this->user_id);
        }

        // Get filter parameters
        $type  = request('type', 'date'); // default 'date'
        $date  = request('date');
        $week  = request('week'); // new week input
        $month = request('month');
        $year  = request('year');

        // Filter by single date
        if ($type === 'date' && $date) {
            $query->whereDate('date', $date);
        }

        // Filter by week (format: 2025-W05)
        if ($type === 'week' && $week) {
            // Convert "2025-W05" to start and end date
            $start = Carbon::parse($week)->startOfWeek();
            $end   = Carbon::parse($week)->endOfWeek();
            $query->whereBetween('date', [$start, $end]);
        }

        // Filter by month
        if ($type === 'month' && $month) {
            $carbonMonth = Carbon::parse($month);
            $query->whereMonth('date', $carbonMonth->month)
                ->whereYear('date', $carbonMonth->year);
        }

        // Filter by year
        if ($type === 'year' && $year) {
            $query->whereYear('date', $year);
        }

        return $query;
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('allemployeeattendance-table')
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
            Column::make('employee_name')->title('Name'),
            Column::make('date'),
            Column::make('start_time'),
            Column::make('end_time'),
            Column::make('total_hours'),
            // Column::computed('action')
            //       ->exportable(false)
            //       ->printable(false)
            //       ->width(60)
            //       ->addClass('text-center'),
        ];
    }

    /**
     * Get the filename for export.
     */
    protected function filename(): string
    {
        return 'AllEmployeeAttendance_' . date('YmdHis');
    }
}
