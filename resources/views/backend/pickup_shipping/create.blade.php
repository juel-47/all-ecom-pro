@extends('backend.layouts.master')
@section('title', $settings->site_name . ' | Create Pickup Shipping')
@section('content')
    <!-- Main Content -->
    <section class="section">
        <div class="section-header">
            <h1>Pickup Shipping</h1>
        </div>
        <div class="section-body">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>Create Pickup Shipping</h4>
                            <div class="card-header-action">
                                <a href="{{ route('admin.pickup-shipping.index') }}" class="btn btn-primary">Back</a>
                            </div>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('admin.pickup-shipping.store') }}" method="POST">
                                @csrf
                                <div class="row">
                                    <div class="form-group col-md-6">
                                        {{-- <label for="name">Shipping Method Name</label>
                                        <input type="text" name="name" class="form-control"
                                            placeholder="Enter shipping method name" value="{{ old('name') }}" required> --}}
                                        <label for="name">Shipping Method Name</label>
                                        <select name="name" class="form-control">
                                            {{-- <option value="">Select</option> --}}
                                            <option value="pickup">Pickup</option>
                                        </select>

                                    </div>

                                    <div class="form-group col-md-6">
                                        <label for="store_name">Store Name</label>
                                        <input type="text" name="store_name" class="form-control"
                                            placeholder="Enter store name" value="{{ old('store_name') }}" required>
                                    </div>

                                    <div class="form-group col-md-6">
                                        <label for="address">Address</label>
                                        <textarea name="address" class="form-control" rows="3" placeholder="Enter store address" required>{{ old('address') }}</textarea>
                                    </div>

                                    <div class="form-group col-md-6">
                                        <label for="map_location">Map Location (URL or Coordinates)</label>
                                        <input type="text" name="map_location" class="form-control"
                                            placeholder="Enter Google Maps link" value="{{ old('map_location') }}">
                                    </div>

                                    <div class="form-group col-md-6">
                                        <label for="phone">Phone</label>
                                        <input type="text" name="phone" class="form-control"
                                            placeholder="Enter phone number" value="{{ old('phone') }}">
                                    </div>

                                    <div class="form-group col-md-6">
                                        <label for="email">Email</label>
                                        <input type="email" name="email" class="form-control" placeholder="Enter email"
                                            value="{{ old('email') }}">
                                    </div>

                                    <div class="form-group col-md-6">
                                        <label for="cost">Cost (Optional)</label>
                                        <input type="number" name="cost" class="form-control"
                                            placeholder="Enter shipping cost" value="{{ old('cost') }}" step="1">
                                    </div>

                                    <div class="form-group col-md-6">
                                        <label for="status">Status</label>
                                        <select name="status" class="form-control">
                                            <option value="1" {{ old('status', 1) == 1 ? 'selected' : '' }}>Active
                                            </option>
                                            <option value="0" {{ old('status', 1) == 0 ? 'selected' : '' }}>Inactive
                                            </option>
                                        </select>
                                    </div>
                                </div>

                                <button type="submit" class="btn btn-primary mt-3">Create</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
    </section>
@endsection
