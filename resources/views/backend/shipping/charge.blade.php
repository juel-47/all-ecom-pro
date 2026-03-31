@extends('backend.layouts.master')
@section('title', $settings->site_name . ' | Shipping Charges')

@section('content')
<section class="section">
    <div class="section-header">
        <h1>Shipping Charges for {{ $shipping->name }}</h1>
        <div class="section-header-breadcrumb">
            <a href="{{ route('admin.shipping-methods.index') }}" class="btn btn-primary">Back</a>
        </div>
    </div>

    <div class="section-body">
        <form id="chargeForm" action="{{ route('admin.shipping.charge.save', $shipping->id) }}" method="POST">
            @csrf
            <div class="row">
                <div class="form-group col-md-4">
                    <label>Country</label>
                    <select name="country_id" id="country_id" class="form-control" required>
                        <option value="">Select Country</option>
                        @foreach(\App\Models\Country::all() as $country)
                            <option value="{{ $country->id }}">{{ $country->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group col-md-4">
                    <label>State</label>
                    <select name="state_id" id="state_id" class="form-control">
                        <option value="">Select State</option>
                    </select>
                </div>
                <div class="form-group col-md-2">
                    <label>Base Charge</label>
                    <input type="number" name="base_charge" class="form-control" required>
                </div>
                <div class="form-group col-md-2">
                    <label>Extra Per Kg</label>
                    <input type="number" name="extra_per_kg" class="form-control">
                </div>
                <div class="form-group col-md-2">
                    <label>Min Weight</label>
                    <input type="number" name="min_weight" class="form-control">
                </div>
                <div class="form-group col-md-2">
                    <label>Max Weight</label>
                    <input type="number" name="max_weight" class="form-control">
                </div>
            </div>
            <button type="submit" class="btn btn-success mt-2">Save Charge</button>
        </form>

        <hr>

        <table class="table table-bordered" id="charge-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Country</th>
                    <th>State</th>
                    <th>Base Charge</th>
                    <th>Extra Per Kg</th>
                    <th>Weight</th>
                    <th>Action</th>
                </tr>
            </thead>
        </table>
    </div>
</section>
@endsection

@push('scripts')
<script>
$(document).ready(function() {

    // Load states on country change
    $('#country_id').change(function(){
        var country_id = $(this).val();
        $.get('/admin/countries/'+country_id+'/states', function(data){
            $('#state_id').html('<option value="">Select State</option>');
            $.each(data, function(i,v){
                $('#state_id').append('<option value="'+v.id+'">'+v.name+'</option>');
            });
        });
    });

    // Yajra DataTable
    // $('#charge-table').DataTable({
    //     processing: true,
    //     serverSide: true,
    //     ajax: '{{ route("admin.shipping.charge", $shipping->id) }}',
    //     columns: [
    //         {data: 'id', name: 'id'},
    //         {data: 'country', name: 'country.name'},
    //         {data: 'state', name: 'state.name'},
    //         {data: 'base_charge', name: 'base_charge'},
    //         {data: 'extra_per_kg', name: 'extra_per_kg'},
    //         {data: 'weight', name: 'min_weight'},
    //         {data: 'action', name: 'action', orderable: false, searchable: false},
    //     ]
    // });

    // Delete Charge
    // $('body').on('click', '.delete-charge', function(){
    //     var id = $(this).data('id');
    //     if(confirm('Are you sure?')){
    //         $.ajax({
    //             url: '/admin/shipping-methods/charge/'+id,
    //             type: 'DELETE',
    //             data: {_token:'{{ csrf_token() }}'},
    //             success: function(res){
    //                 toastr.success(res.message);
    //                 $('#charge-table').DataTable().ajax.reload();
    //             }
    //         });
    //     }
    // });
});
</script>
@endpush
