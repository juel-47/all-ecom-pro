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
                            <h4>All Orders</h4>
                            <div class="card-header-action">
                                <div class="courier-actions">
                                    <button type="button" id="bulk-steadfast" class="btn courier-btn courier-steadfast">
                                        <i class="fas fa-truck mr-1"></i> Steadfast
                                    </button>
                                    <button type="button" id="bulk-pathao" class="btn courier-btn courier-pathao" disabled>
                                        <i class="fas fa-truck-loading mr-1"></i> pathao
                                    </button>
                                </div>
                            </div>
                            {{-- <div class="card-header-action">
                                <a href="{{ route('admin.coupons.create') }}" class="btn btn-primary"><i class="fas fa-plus"></i>
                                    Create New</a>
                            </div> --}}
                        </div>
                        <div class="table-responsive card-body">
                            {{ $dataTable->table(['class' => 'table table-striped table-bordered', 'id' => 'order-table']) }}
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
            const table = $('#order-table');
            const toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true,
            });

            table.on('change', '#select-all', function() {
                const isChecked = $(this).is(':checked');
                $('.order-select').prop('checked', isChecked);
            });

            table.on('change', '.order-select', function() {
                const total = $('.order-select').length;
                const checked = $('.order-select:checked').length;
                $('#select-all').prop('checked', total > 0 && total === checked);
            });

            $('#bulk-steadfast').on('click', function() {
                const ids = $('.order-select:checked').map(function() {
                    return $(this).val();
                }).get();

                if (ids.length === 0) {
                    toast.fire({
                        icon: 'warning',
                        title: 'Please select at least one order.',
                    });
                    return;
                }

                Swal.fire({
                    title: 'Send to Steadfast?',
                    text: 'Selected orders will be sent to Steadfast.',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, send',
                    cancelButtonText: 'Cancel',
                    confirmButtonColor: '#f2a900',
                }).then((result) => {
                    if (!result.isConfirmed) return;

                    $.ajax({
                        url: "{{ route('admin.orders.steadfast.bulk') }}",
                        method: 'POST',
                        data: {
                            _token: "{{ csrf_token() }}",
                            order_ids: ids
                        },
                        success: function(response) {
                            toast.fire({
                                icon: 'success',
                                title: response.message || 'Orders sent to Steadfast.',
                            });
                            window.LaravelDataTables['order-table'].ajax.reload(null, false);
                        },
                        error: function(xhr) {
                            const message = xhr.responseJSON?.message || 'Failed to send orders to Steadfast.';
                            toast.fire({
                                icon: 'error',
                                title: message,
                            });
                        }
                    });
                });
            });
        });
    </script>
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

@push('css')
<style>
    .courier-actions {
        display: flex;
        gap: 10px;
    }
    .courier-btn {
        border-radius: 999px;
        padding: 6px 16px;
        font-weight: 600;
        border: none;
        color: #fff !important;
        background: transparent;
    }
    .courier-btn:hover,
    .courier-btn:focus {
        color: #fff !important;
        text-decoration: none;
        box-shadow: none;
    }
    .courier-steadfast {
        background: #f2a900 !important;
    }
    .courier-steadfast:hover {
        background: #d18b00 !important;
    }
    .courier-pathao {
        background: #38bdf8 !important;
    }
    .courier-pathao:hover {
        background: #0ea5e9 !important;
    }
    .courier-btn:disabled {
        opacity: 0.6;
        cursor: not-allowed;
    }
</style>
@endpush
