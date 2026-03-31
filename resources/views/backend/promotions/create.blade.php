@extends('backend.layouts.master')
@section('title', $settings->site_name . ' | Create Promotion')
@section('content')
    <!-- Main Content -->
    <section class="section">
        <div class="section-header">
            <h1>Promotion</h1>
        </div>
        <div class="section-body">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>Create Promotion</h4>
                            <div class="card-header-action">
                                <a href="{{ route('admin.promotions.index') }}" class="btn btn-primary">Back</a>
                            </div>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('admin.promotions.store') }}" method="post">
                                @csrf
                                <div class="row">
                                    <div class="form-group col-md-6">
                                        <label>Title</label>
                                        <input type="text" name="title" class="form-control" required>
                                    </div>

                                    <div class="form-group col-md-6">
                                        <label>Type</label>
                                        <select name="type" class="form-control">
                                            <option value="free_shipping">Free Shipping</option>
                                            <option value="free_product">Free Product</option>
                                        </select>
                                    </div>

                                    <div class="form-group col-md-6">
                                        <label>Category (Optional)</label>
                                        <select name="category_id" class="form-control">
                                            <option value="">All Categories</option>
                                            @foreach ($categories as $cat)
                                                <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="form-group col-md-6">
                                        <label>Free Product (Optional)</label>
                                        <select name="product_id" class="form-control">
                                            <option value="">None</option>
                                            @foreach ($products as $prod)
                                                <option value="{{ $prod->id }}">{{ $prod->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="form-group col-md-6">
                                        <label>Buy Quantity</label>
                                        <input type="number" name="buy_quantity" class="form-control" value="1">
                                    </div>

                                    <div class="form-group col-md-6">
                                        <label>Get Quantity (for free product)</label>
                                        <input type="number" name="get_quantity" class="form-control" value="">
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label>Start Date</label>
                                        <input type="datetime-local" name="start_date" class="form-control">
                                    </div>

                                    <div class="form-group col-md-6">
                                        <label>End Date</label>
                                        <input type="datetime-local" name="end_date" class="form-control">
                                    </div>

                                    <div class="form-group col-md-6">
                                        <label>Allow Coupon Stack?</label>
                                        <select name="allow_coupon_stack" class="form-control">
                                            <option value="0">No</option>
                                            <option value="1">Yes</option>
                                        </select>
                                    </div>

                                    <div class="form-group col-md-6">
                                        <label>Status</label>
                                        <select name="status" class="form-control">
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
    </section>
    {{-- <div class="container">
        <h4>Create Promotion</h4>

        <form action="{{ route('admin.promotions.store') }}" method="POST">
            @csrf
            <div class="form-group mb-3">
                <label>Title</label>
                <input type="text" name="title" class="form-control" required>
            </div>

            <div class="form-group mb-3">
                <label>Type</label>
                <select name="type" class="form-control">
                    <option value="free_shipping">Free Shipping</option>
                    <option value="free_product">Free Product</option>
                </select>
            </div>

            <div class="form-group mb-3">
                <label>Category (Optional)</label>
                <select name="category_id" class="form-control">
                    <option value="">All Categories</option>
                    @foreach ($categories as $cat)
                        <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group mb-3">
                <label>Free Product (Optional)</label>
                <select name="product_id" class="form-control">
                    <option value="">None</option>
                    @foreach ($products as $prod)
                        <option value="{{ $prod->id }}">{{ $prod->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group mb-3">
                <label>Buy Quantity</label>
                <input type="number" name="buy_quantity" class="form-control" value="1">
            </div>

            <div class="form-group mb-3">
                <label>Get Quantity (for free product)</label>
                <input type="number" name="get_quantity" class="form-control" value="1">
            </div>

            <div class="form-group mb-3">
                <label>Start Date</label>
                <input type="date" name="start_date" class="form-control">
            </div>

            <div class="form-group mb-3">
                <label>End Date</label>
                <input type="date" name="end_date" class="form-control">
            </div>

            <div class="form-group mb-3">
                <label>Allow Coupon Stack?</label>
                <select name="allow_coupon_stack" class="form-control">
                    <option value="0">No</option>
                    <option value="1">Yes</option>
                </select>
            </div>

            <div class="form-group mb-3">
                <label>Status</label>
                <select name="is_active" class="form-control">
                    <option value="1">Active</option>
                    <option value="0">Inactive</option>
                </select>
            </div>

            <button class="btn btn-success">Save</button>
        </form>
    </div> --}}
@endsection
