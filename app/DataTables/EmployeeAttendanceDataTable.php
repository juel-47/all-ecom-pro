<?php

namespace App\DataTables;

use App\Models\Attendance;
use App\Models\Employee;
use App\Models\EmployeeAttendance;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Illuminate\Support\Carbon;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class EmployeeAttendanceDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder<EmployeeAttendance> $query Results from query() method.
     */
    protected $settings;
    protected $logoSetting;

    public function __construct($settings = null, $logoSetting = null)
    {
        $this->settings = $settings ?? \App\Models\GeneralSetting::first();
        $this->logoSetting = $logoSetting ?? \App\Models\LogoSetting::first();
        // dd($this->logoSetting->logo, $this->settings->site_name);

        if ($this->logoSetting && file_exists(public_path($this->logoSetting->logo))) {
            $path = public_path($this->logoSetting->logo);
            $type = strtolower(pathinfo($path, PATHINFO_EXTENSION));

            if ($type === 'svg') {
                $pngPath = public_path('uploads/logo/logo.png');
                if (file_exists($pngPath)) {
                    $path = $pngPath;
                    $type = 'png';
                } else {
                    $this->logoSetting->logo = null;
                }
            }

            if ($this->logoSetting->logo) {
                $this->logoSetting->logo = 'data:image/' . $type . ';base64,' . base64_encode(file_get_contents($path));
            }
            // Employee photo (optional, from $this->attributes if passed via ->with())
            if (!empty($this->attributes['employee_photo']) && file_exists(public_path($this->attributes['employee_photo']))) {
                $empPath = public_path($this->attributes['employee_photo']);
                $ext = strtolower(pathinfo($empPath, PATHINFO_EXTENSION));
                $this->attributes['employee_photo'] = 'data:image/' . $ext . ';base64,' . base64_encode(file_get_contents($empPath));
            }
        }
    }

    public function dataTable(QueryBuilder $query): EloquentDataTable
    {

        return (new EloquentDataTable($query))
            // ->addColumn('action', 'employeeattendance.action')
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
            ->rawColumns(['employee_name', 'total_hours'])
            ->setRowId('id');
    }

    /**
     * Get the query source of dataTable.
     *
     * @return QueryBuilder<EmployeeAttendance>
     */
    public function query(Attendance $model): QueryBuilder
    {
        $query = $model->where('user_id', auth()->id());

        // Get filter params
        $type  = request('type', 'date');
        $date  = request('date');
        $month = request('month');
        $year  = request('year');

        if ($type === 'date' && $date) {
            $query->whereDate('date', $date);
        }
        if ($type === 'week' && request()->filled('week')) {

            list($year, $week) = explode('-W', request('week'));

            $start = Carbon::now()->setISODate($year, $week)->startOfWeek();
            $end   = Carbon::now()->setISODate($year, $week)->endOfWeek();

            $query->whereBetween('date', [
                $start->format('Y-m-d'),
                $end->format('Y-m-d')
            ]);
        }

        if ($type === 'month' && $month) {
            $carbonMonth = Carbon::parse($month);
            $query->whereMonth('date', $carbonMonth->month)
                ->whereYear('date', $carbonMonth->year);
        }

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
        $employeeName = $this->attributes['employee_name'] ?? '-';
        $employeeDesignation = $this->attributes['employee_designation'] ?? '-';
        $employeeEmail = $this->attributes['employee_email'] ?? '';
        $employeePhone = $this->attributes['employee_phone'] ?? '';

        $logo = $this->logoSetting->logo ?? '';
        return $this->builder()
            ->setTableId('employeeattendance-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->orderBy(1)
            ->selectStyleSingle()
            ->dom('lBfrtip')
            ->buttons([
                Button::make('excel')->addClass('btn btn-primary'),
                Button::make('csv')->addClass('btn btn-primary'),
                Button::make('pdf')->addClass('btn btn-primary')->exportOptions([
                    'columns' => [2, 3, 4, 5]
                ])->customize("
                    function(doc) {
                        var logo = '" . $logo . "';
                        
                        let empName = '" . $employeeName . "';
                        let empDesignation = '" . $employeeDesignation . "';
                        let empEmail = '" . $employeeEmail . "';
                        let empPhone = '" . $employeePhone . "';

                        // Header
                        doc['header'] = function() {
                            var headerColumns = [];

                            if (logo) {
                                headerColumns.push({ image: logo, width: 50 });
                            }

                            headerColumns.push({
                                stack: [
                                    { text: 'Name: " . $this->settings->site_name . "', bold: true },
                                    { text: empName + ' - ' + empDesignation, bold: true },
                                    { text: empEmail + ' - ' + empEmail, bold: true },
                                    { text: empPhone + ' - ' + empPhone, bold: true },
                                ],
                                alignment: 'center',
                            });


                            return { columns: headerColumns, margin: [20, 10] };
                        };

                        // Footer
                        doc['footer'] = function(page, pages) {
                            return {
                                columns: [
                                    { text: 'Generated on: ' + new Date().toLocaleString(), alignment: 'left', margin: [20,0] },
                                    { text: 'Page ' + page.toString() + ' of ' + pages, alignment: 'right', margin: [0,0] }
                                ]
                            };
                        };

                        // Table widths
                        if(doc.content[1].table){
                            doc.content[1].table.widths = ['*','*','*','*'];
                        }
                    }"),
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
            Column::make('id')->exportable(false)->printable(false),
            Column::make('employee_name')->title('Name')->exportable(false)->printable(false),
            Column::make('date'),
            Column::make('start_time'),
            Column::make('end_time'),
            Column::make('total_hours'),
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
        return 'EmployeeAttendance_' . date('YmdHis');
    }
}
