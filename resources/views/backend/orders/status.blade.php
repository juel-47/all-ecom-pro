
@extends('backend.layouts.master')
@section('title', $settings->site_name . ' | Orders')
@section('content')
    <!-- Main Content -->

    <section class="section">
        <div class="section-header">
            <h1>Orders</h1>
        </div>
        <div class="section-body">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>{{ $statuses->where('id',$selected_status)->first()->name ?? 'Orders' }}</h4>
                            {{-- <div class="card-header-action">
                                <a href="{{ route('admin.coupons.create') }}" class="btn btn-primary"><i class="fas fa-plus"></i>
                                    Create New</a>
                            </div> --}}
                        </div>
                        <div class="card-body table-responsive">
                {{ $dataTable->table(['class'=>'table table-striped table-bordered']) }}
            </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('scripts')
    {{ $dataTable->scripts(attributes: ['type' => 'module']) }}
    {{-- <script>
        $(document).ready(function() {
            $('body').on('click', '.change-status', function() {
                let isChecked = $(this).is(':checked');
                let id = $(this).data('id');
                $.ajax({
                    url: "{{ route('admin.coupon.change-status') }}",
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
    </script> --}}
    {{-- <script>
        $(function() {
            var statusId = "{{ $status->id ?? '' }}";
            $('#orderTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ url('orders/status') }}/" + statusId,
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex'
                    },
                    {
                        data: 'invoice_id',
                        name: 'invoice_id'
                    },
                    {
                        data: 'user_id',
                        name: 'user_id'
                    },
                    {
                        data: 'amount',
                        name: 'amount'
                    },
                    {
                        data: 'payment_status',
                        name: 'payment_status'
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    },
                ]
            });
        });
    </script> --}}
@endpush

