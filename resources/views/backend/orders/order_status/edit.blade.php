@extends('backend.layouts.master')
@section('title', $settings->site_name . ' | Create Order Status')
@section('content')
    <!-- Main Content -->

    <section class="section">
        <div class="section-header">
            <h1>Order Status</h1>
        </div>

        <div class="section-body">

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>Update Order Status</h4>
                            <div class="card-header-action">
                                <a href="{{ route('admin.order-status.index') }}" class="btn btn-primary">Back</a>
                            </div>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('admin.order-status.update',$order_status->id) }}" method="post">
                                @csrf
                                @method('put')
                                <div class="row">
                                    <div class="form-group col-md-6">
                                        <label>Name</label>
                                        <input type="text" class="form-control" name="name" value="{{ old('name', $order_status->name) }}">
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="inputState">Status</label>
                                        <select id="inputState" class="form-control" name="status">
                                            <option {{$order_status->status==1 ? 'selected' : ''}} value="1">Active</option>
                                            <option {{$order_status->status==0 ? 'selected' : ''}} value="0">Inactive</option>
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
