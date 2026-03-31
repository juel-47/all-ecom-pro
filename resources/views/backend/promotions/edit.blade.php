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
                            <form action="{{ route('admin.promotions.update', $promotion->id) }}" method="post">
                                @csrf
                                @method('put')
                                <div class="row">
                                    <div class="form-group col-md-6">
                                        <label>Title</label>
                                        <input type="text" name="title" class="form-control" value="{{ $promotion->title }}" required>
                                    </div>

                                    <div class="form-group col-md-6">
                                        <label>Type</label>
                                        <select name="type" class="form-control">
                                            <option {{$promotion->type == 'free_shipping' ? 'selected' : ''}} value="free_shipping">Free Shipping</option>
                                            <option {{$promotion->type == 'free_product' ? 'selected' : ''}} value="free_product">Free Product</option>
                                        </select>
                                    </div>

                                    <div class="form-group col-md-6">
                                        <label>Category (Optional)</label>
                                        <select name="category_id" class="form-control">
                                            <option value="">All Categories</option>
                                            @foreach ($categories as $cat)
                                                <option value="{{ $cat->id }}" {{ $promotion->category_id == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="form-group col-md-6">
                                        <label>Free Product (Optional)</label>
                                        <select name="product_id" class="form-control">
                                            <option value="">None</option>
                                            @foreach ($products as $prod)
                                                <option value="{{ $prod->id }}" {{ $promotion->product_id == $prod->id ? 'selected' : '' }}>{{ $prod->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="form-group col-md-6">
                                        <label>Buy Quantity</label>
                                        <input type="number" name="buy_quantity" class="form-control" value="{{ $promotion->buy_quantity }}" >
                                    </div>

                                    <div class="form-group col-md-6">
                                        <label>Get Quantity (for free product)</label>
                                        <input type="number" name="get_quantity" class="form-control" value="{{ $promotion->get_quantity  }}">
                                    </div>

                                    {{-- <div class="form-group col-md-6">
                                        <label>Start Date</label>
                                        <input type="date" name="start_date" class="form-control">
                                    </div> --}}
                                    <div class="form-group col-md-6">
                                        <label>Start Date</label>
                                        <input type="datetime-local" name="start_date" class="form-control" value="{{ $promotion->start_date }}">
                                    </div>

                                    <div class="form-group col-md-6">
                                        <label>End Date</label>
                                        <input type="datetime-local" name="end_date" class="form-control" value="{{ $promotion->end_date }}">
                                    </div>

                                    <div class="form-group col-md-6">
                                        <label>Allow Coupon Stack?</label>
                                        <select name="allow_coupon_stack" class="form-control">
                                            <option {{$promotion->allow_coupon_stack== 0 ? 'selected' : ''}} value="0">No</option>
                                            <option {{$promotion->allow_coupon_stack== 1 ? 'selected' : ''}} value="1">Yes</option>
                                        </select>
                                    </div>

                                    <div class="form-group col-md-6">
                                        <label>Status</label>
                                        <select name="status" class="form-control">
                                            <option {{$promotion->status== 1 ? 'selected' : ''}} value="1">Active</option>
                                            <option {{$promotion->status== 0 ? 'selected' : ''}} value="0">Inactive</option>
                                        </select>
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-primary mt-3">Update</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
    </section>
@endsection
