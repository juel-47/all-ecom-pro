@extends('backend.layouts.master')
@section('title', 'Category')
@section('content')
    <!-- Main Content -->

    <section class="section">
        <div class="section-header">
            <h1>Category</h1>
        </div>
        <div class="section-body">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>All Categories</h4>
                            <div class="card-header-action">
                                <a href="{{ route('admin.category.create') }}" class="btn btn-primary"><i
                                        class="fas fa-plus"></i> Create New</a>
                            </div>
                        </div>
                        {{-- <div class="card-body responsive">
                            {{ $dataTable->table() }}
                        </div> --}}
                        <div class="table-responsive card-body">
                                {{ $dataTable->table(['class' => 'table table-striped table-bordered', 'id' => 'category-table']) }}
                            </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    @endsection
    
    @push('scripts')
    <!-- Add DataTables Responsive Script -->
    {{-- <script>
      {{ $dataTable->scripts(attributes: ['type' => 'module']) }}
    </script> --}}
    {{ $dataTable->scripts(attributes: ['type' => 'module']) }}
    <script>
        $(document).ready(function() {
            $('body').on('click', '.change-status', function() {
                let isChecked = $(this).is(':checked');
                let id = $(this).data('id');
                $.ajax({
                    url: "{{ route('admin.category.change-status') }}",
                    method: 'put',
                    data: {
                        id: id,
                        status: isChecked
                    },
                    success: function(data) {
                        toastr.success(data.message)
                    },
                    error: function(xhr, status, error) {
                        console.log(error);
                    }
                })
            })
        })
    </script>
    {{-- front show status  --}}
    <script>
        $(document).ready(function() {
            $('body').on('click', '.front_show', function() {
                let isChecked = $(this).is(':checked');
                let id = $(this).data('id');
                // alert(id, isChecked);
                $.ajax({
                    url: "{{ route('admin.category.front-show') }}",
                    method: 'put',
                    data: {
                        id: id,
                        status: isChecked
                    },
                    success: function(data) {
                        toastr.success(data.message)
                    },
                    error: function(xhr, status, error) {
                        console.log(error);
                    }
                })
            })
        })
    </script>
@endpush
