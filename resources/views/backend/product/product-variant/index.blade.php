@extends('backend.layouts.master')
@section('title', 'Product Variant')
@section('content')
    <!-- Main Content -->
    <section class="section">
        <div class="section-header">
            <h1>Product Variant</h1>
        </div>
        <div class="section-body">
            <div class="row">
                <div class="col-12">
                    <div class="mb-3 ml-3">
                        <a href="{{ route('admin.products.index') }}" class="btn btn-primary "><i
                                class="fas fa-caret-left mr-1"></i> Back</a>
                    </div>
                    <div class="card">
                        <div class="card-header">
                            <h4>Product : {{ $product->name }}</h4>
                            <div class="card-header-action">
                                <a href="{{ route('admin.product-variant.create', ['product' => $product->id]) }}"
                                    class="btn btn-primary"><i class="fas fa-plus"></i> Create New</a>
                            </div>
                        </div>
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
    {{ $dataTable->scripts(attributes: ['type' => 'module']) }}
    <script>
        $(document).ready(function() {
            $('body').on('click', '.change-status', function() {
                let isChecked = $(this).is(':checked');
                let id = $(this).data('id');
                $.ajax({
                    url: "{{ route('admin.product-variant.change-status') }}",
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
