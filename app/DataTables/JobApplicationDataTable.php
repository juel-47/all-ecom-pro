<?php

namespace App\DataTables;

use App\Models\JobApplication;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class JobApplicationDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder<JobApplication> $query Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addColumn('action', function ($query) {

                // View file (PDF/Excel/CSV/Other)
                $viewBtn = $query->resume
                    ? "<a href='" . route('admin.job-application.show', $query->id) . "' class='btn  btn-primary mr-1' target='_blank'>
                    <i class='fas fa-eye'></i> 
                  </a>"
                    : '';

                // Download file
                $downloadBtn = $query->resume
                    ? "<a href='" . route('admin.job-application.download', $query->id) . "' class='btn  btn-success mr-1'>
                    <i class='fas fa-download'></i> 
                  </a>"
                    : '';

                // Delete button
                $deleteBtn = "<a href='" . route('admin.job-application.destroy', $query->id) . "' class='btn  btn-danger delete-item'>
                <i class='fas fa-trash'></i> 
            </a>";

                return $viewBtn . $downloadBtn . $deleteBtn;
            })
            ->addColumn('video_cv', function ($query) {
                if ($query->video_cv) {
                    return "<video width='200' controls>
                            <source src='" . asset($query->video_cv) . "' type='video/mp4'>
                            Your browser does not support the video tag.
                        </video>
                        <br>
                        <a href='" . asset($query->video_cv) . "' class='btn btn-info btn-sm mt-1' download>
                            <i class='fas fa-download'></i> Download Video
                        </a>";
                }
                return '';
            })

            ->addColumn('cover_letter', function ($query) {
                $coverLetterBtn = $query->cover_letter
                    ? "<button class='btn btn-sm btn-info mr-1' onclick='showCoverLetter(`" . addslashes($query->cover_letter) . "`)'>
            <i class='far fa-eye'></i> C-L
          </button>"
                    : '';

                return $coverLetterBtn;
            })
            ->rawColumns(['action', 'cover_letter', 'video_cv'])
            ->setRowId('id');
    }

    /**
     * Get the query source of dataTable.
     *
     * @return QueryBuilder<JobApplication>
     */
    public function query(JobApplication $model): QueryBuilder
    {
        return $model->newQuery()->orderBy('id', 'desc');
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('jobapplication-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->orderBy(1)
            ->stateSave(true)
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
            Column::make('name')->addClass('text-center align-middle'),
            Column::make('email')->addClass('text-center align-middle'),
            Column::make('phone')->addClass('text-center align-middle'),
            Column::make('position')->addClass('text-center align-middle'),
            Column::make('cover_letter')->addClass('text-center align-middle'),
            Column::make('video_cv')->title('Video CV')->addClass('text-center align-middle'),
            Column::computed('action')
                ->exportable(false)
                ->printable(false)
                ->width(280)
                ->addClass('text-center align-middle'),
        ];
    }

    /**
     * Get the filename for export.
     */
    protected function filename(): string
    {
        return 'JobApplication_' . date('YmdHis');
    }
}
