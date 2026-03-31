@extends('backend.layouts.master')
@section('title', $settings->site_name . ' | Shipping Method')
@section('content')
    <section class="section">
        <div class="section-header">
            <h1>Shipping Method</h1>
        </div>
        <div class="section-body">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>Create Shipping-method</h4>
                            <div class="card-header-action">
                                <a href="{{ route('admin.shipping.index') }}" class="btn btn-primary">Back</a>
                            </div>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('admin.shipping.store') }}" method="post">
                                @csrf
                                <div class="row">
                                    <div class="form-group col-md-6">
                                        <label>Name</label>
                                        <input type="text" class="form-control" name="name" value="">
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="inputState">Type</label>
                                        <select id="type-select" class="form-control" name="type_select"
                                            onchange="toggleOtherInput()">
                                            <option value="">Select</option>
                                            <option value="flat_rate">Flat Rate</option>
                                            <option value="express">Express Shipping</option>
                                            <option value="free_shipping">Free Shipping</option>
                                            <option value="same_day_delivery">Same Day Delivery</option>
                                            <option value="pickup">Pickup</option>
                                            <option value="courier">Courier (FedEx, UPS, DHL)</option>
                                            <option value="other">Other</option>
                                        </select>
                                    </div>
                                    <div class="form-group col-md-6" id="other-type-group" style="display:none;">

                                        {{-- <div id="other-type-group" style="display:none;"> --}}
                                            <label for="inputState">Custom Type</label>
                                            <input type="text" id="other-type" name="type"
                                                placeholder="Enter custom shipping type" class="form-control">
                                        {{-- </div> --}}
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="inputState">Status</label>
                                        <select id="inputState" class="form-control" name="is_active">
                                            <option value="">Select</option>
                                            <option value="1">Active</option>
                                            <option value="0">Inactive</option>
                                        </select>
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-primary mt-3">Create</button>
                        </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        </div>
    </section>
@endsection
@push('scripts')
    {{-- <script>
        function toggleOtherInput() {
            var select = document.getElementById('type-select');
            var otherInput = document.getElementById('other-type-group');
            if (select.value === 'other') {
                otherInput.style.display = 'block';
            } else {
                otherInput.style.display = 'none';
                document.getElementById('other-type').value = '';
            }
        }
    </script> --}}
    <script>
        function toggleOtherInput() {
            var select = document.getElementById('type-select');
            var otherInput = document.getElementById('other-type-group');
            if (!select || !otherInput) return;

            if (select.value === 'other') {
                otherInput.style.display = 'block';
            } else {
                otherInput.style.display = 'none';
                document.getElementById('other-type').value = '';
            }
        }


        document.addEventListener('DOMContentLoaded', function() {
            toggleOtherInput();
        });
    </script>
@endpush
