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
                                <a href="{{ route('admin.shipping-methods.index') }}" class="btn btn-primary">Back</a>
                            </div>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('admin.shipping-methods.store') }}" method="post">
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
                        </form>
                    </div>
                </div>
            </div>
        </div>
        </div>
    </section>
@endsection
