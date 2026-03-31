@extends('backend.layouts.master')
@section('title', $settings->site_name . ' | Shipping Rule Create')
@section('content')
    <!-- Main Content -->
    <section class="section">
        <div class="section-header">
            <h1>Shipping Rule</h1>
        </div>
        <div class="section-body">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>Create Shipping-rule</h4>
                            <div class="card-header-action">
                                <a href="{{ route('admin.shipping-rule.index') }}" class="btn btn-primary">Back</a>
                            </div>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('admin.shipping-rule.update', $shipping_rule->id) }}" method="post">
                                @csrf
                                @method('put')
                                <div class="row">
                                    <div class="form-group col-md-6">
                                        <label>Name</label>
                                        <input type="text" class="form-control" name="name"
                                            value="{{ $shipping_rule->name }}">
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="inputState">Type</label>
                                        <select id="inputState" class="form-control shipping-type" name="type">
                                            <option value="">Select</option>
                                            <option {{ $shipping_rule->type === 'flat_cost' ? 'selected' : '' }}
                                                value="flat_cost">Flat</option>
                                            <option {{ $shipping_rule->type === 'min_cost' ? 'selected' : '' }}
                                                value="min_cost">Minimum Order Amount</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group {{ $shipping_rule->type === 'min_cost' ? '' : 'd-none' }} min_cost">
                                    <label>Minimum Cost</label>
                                    <input type="text" class="form-control" name="min_cost"
                                        value="{{ $shipping_rule->min_cost }}">
                                </div>
                                <div class="row">
                                    <div class="form-group col-md-6">
                                        <label>Cost</label>
                                        <input type="text" class="form-control" name="cost"
                                            value="{{ $shipping_rule->cost }}">
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="inputState">Status</label>
                                        <select id="inputState" class="form-control" name="status">
                                            <option value="">Select</option>
                                            <option {{ $shipping_rule->status == 1 ? 'selected' : '' }} value="1">Active
                                            </option>
                                            <option {{ $shipping_rule->status == 0 ? 'selected' : '' }} value="0">
                                                Inactive</option>
                                        </select>
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-primary mt-3">Update</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
@push('scripts')
    <script>
        $(document).ready(function() {
            $('body').on('change', '.shipping-type', function() {
                let value = $(this).val();
                //   alert(value);
                if (value != 'min_cost') {
                    $('.min_cost').addClass('d-none');
                } else {
                    $('.min_cost').removeClass('d-none');
                }
            })
        })
    </script>
@endpush
