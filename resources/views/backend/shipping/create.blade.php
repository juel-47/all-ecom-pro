@extends('backend.layouts.master')
@section('title',
    $settings->site_name . ' | ' . isset($shippingMethod)
    ? 'Edit Shipping Method'
    : 'Create Shipping
    Method')
@section('content')
    <section class="section">
        <div class="section-header">
            <h1>{{ isset($shippingMethod) ? 'Edit' : 'Create' }} Shipping Method</h1>
        </div>
        <div class="section-body">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>Create Shipping-method</h4>
                            <div class="card-header-action">
                                <a href="{{ route('admin.shipping-methods.index') }}" class="btn btn-primary">Back</a>
                            </div>
                        </div>
                        <div class="card-body">
                            {{-- <form action="{{ route('admin.shipping-methods.store') }}" method="post">
                                @csrf
                                <div class="row">
                                    <div class="form-group col-md-6">
                                        <label>Name</label>
                                        <input type="text" class="form-control" name="name" value="">
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="inputState">Type</label>
                                        <select id="inputState" class="form-control shipping-type" name="type">
                                            <option value="">Select</option>
                                            <option value="international">International</option>
                                            <option value="local">Local</option>
                                            <option value="flat_rate">Flat Rate</option>
                                            <option value="express">Express</option>
                                            <option value="free_shipping">Free Shipping</option>
                                            <option value="courier">Courier</option>
                                            <option value="pickup">Pickup</option>
                                        </select>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="inputState">Status</label>
                                        <select id="inputState" class="form-control" name="status">
                                            <option value="">Select</option>
                                            <option value="1">Active</option>
                                            <option value="0">Inactive</option>
                                        </select>
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-primary mt-3">Create</button>
                        </div>
                        </form> --}}
                            <form
                                action="{{ isset($shippingMethod) ? route('admin.shipping-methods', $shippingMethod->id) : route('admin.shipping-methods.store') }}"
                                method="POST">
                                @csrf
                                @if (isset($shippingMethod))
                                    @method('PUT')
                                @endif
                                <div class="row">
                                
                                <div class="form-group col-md-6">
                                    <label for="name">Shipping Name</label>
                                    <input type="text" name="name" id="name" class="form-control"
                                        value="{{ $shippingMethod->name ?? old('name') }}" required>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="typeSelect">Shipping Type</label>
                                    <select name="type[]" id="typeSelect" class="form-control" multiple required>
                                        @php
                                            $selectedTypes = isset($shippingMethod)
                                                ? json_decode($shippingMethod->type, true)
                                                : [];
                                        @endphp

                                        {{-- Show existing types as selected --}}
                                        @foreach ($selectedTypes as $type)
                                            <option value="{{ $type }}" selected>{{ ucfirst($type) }}</option>
                                        @endforeach
                                    </select>
                                    <small class="form-text text-muted">
                                        Select existing types or add new type below (e.g., express, pickup).
                                    </small>
                                </div>
                                <div class="form-group col-md-6">
                                    <input type="text" id="newType" class="form-control" placeholder="Add new type">
                                    <button type="button" class="btn btn-sm btn-primary mt-2" onclick="addType()">Add
                                        Type</button>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="inputState">Status</label>
                                    <select id="inputState" class="form-control" name="status">
                                        <option value="">Select</option>
                                        <option value="1">Active</option>
                                        <option value="0">Inactive</option>
                                    </select>
                                </div>
                                </div>
                                <button type="submit" class="btn btn-primary mt-3">Create</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    {{-- JavaScript to add new type dynamically --}}
    <script>
        function addType() {
            let input = document.getElementById('newType');
            let val = input.value.trim();
            let select = document.getElementById('typeSelect');

            if (val) {
                // check if already exists
                if (!Array.from(select.options).some(option => option.value === val)) {
                    let option = new Option(val, val, true, true);
                    select.add(option);
                }
                input.value = '';
            }
        }
    </script>
@endsection
