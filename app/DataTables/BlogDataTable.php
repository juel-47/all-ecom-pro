<?php

namespace App\DataTables;

use App\Models\Blog;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class BlogDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder<Blog> $query Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
             ->addColumn('action', function($query){
                $editBtn= "<a href='".route('admin.blog.edit', $query->id)."'class='btn btn-primary'><i class='far fa-edit'></i></a>";
                $deleteBtn= "<a href='".route('admin.blog.destroy', $query->id)."'class='btn btn-danger ml-2 delete-item'><i class='fas fa-trash'></i></i></a>";
                return $editBtn.$deleteBtn;
            })
            ->addColumn('image', function($query){
                return $query->image ? '<img src="'.asset('storage/'.$query->image).'" alt="'.$query->title.'" width="70px" height="50px" style="object-fit: cover; border-radius: 4px;">' : '<span class="text-muted">No Image</span>';
            })
            ->addColumn('category', function($query){
                return $query->category ? $query->category->name : '<span class="text-muted">No Category</span>';
            })
            ->addColumn('status', function($query){
                if($query->status==1){
                    $activeButton= '<label class="custom-switch mt-2">
                    <input type="checkbox" checked name="custom-switch-checkbox" class="custom-switch-input change-status" data-id="'.$query->id.'" >
                    <span class="custom-switch-indicator"></span>
                  </label>';
                }else{
                    $activeButton= '<label class="custom-switch mt-2">
                    <input type="checkbox" name="custom-switch-checkbox" class="custom-switch-input change-status" data-id="'.$query->id.'" >
                    <span class="custom-switch-indicator"></span>
                  </label>';
                }
              return $activeButton;
            })
            ->addColumn('publish_date', function($query){
                return date('d-m-Y', strtotime($query->created_at));
            })
            ->rawColumns(['image', 'action', 'status', 'publish_date'])
            ->setRowId('id');
    }

    /**
     * Get the query source of dataTable.
     *
     * @return QueryBuilder<Blog>
     */
    public function query(Blog $model): QueryBuilder
    {
        return $model->newQuery()->with('category');
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
                    ->setTableId('blog-table')
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
            Column::make('image')->width(100),
            Column::make('title'),
            Column::make('category'),
            Column::make('status'),
            Column::make('publish_date'),
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
        return 'Blog_' . date('YmdHis');
    }
}
