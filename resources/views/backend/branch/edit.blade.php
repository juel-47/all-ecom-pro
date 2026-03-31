@extends('backend.layouts.master')
@section('title', $settings->site_name . ' | Update Branch')
@section('content')
    <!-- Main Content -->

    <section class="section">
        <div class="section-header">
            <h1>Branch</h1>
        </div>

        <div class="section-body">

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>Update Branch</h4>
                            <div class="card-header-action">
                                <a href="{{ route('admin.branch.index') }}" class="btn btn-primary">Back</a>
                            </div>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('admin.branch.update', $branch->id) }}" method="post" enctype="multipart/form-data">
                                @csrf
                                @method('put')
                                <div class="row">
                                    @if ($branch->image)
                                    <div class="form-group col-md-12">
                                        <label>Preview</label>
                                        <br>
                                        <img src="{{ asset($branch->image) }}" alt="" width="150px">
                                    </div>
                                    @endif
                                    <div class="form-group col-md-12">
                                        <label>Image</label>
                                        <input type="file" class="form-control" name="image">
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label>Name <code>*</code></label>
                                        <input type="text" class="form-control" name="name" value="{{ old('name') ?? $branch->name }}">
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label>Email</label>
                                        <input type="email" class="form-control" name="email" value="{{ old('email') ?? $branch->email }}">
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label>Phone</label>
                                        <input type="text" class="form-control" name="phone" value="{{ old('phone') ?? $branch->phone }}">
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="inputState">Status</label>
                                        <select id="inputState" class="form-control" name="status">
                                            <option {{$branch->status==1 ? 'selected' : ''}} value="1">Active</option>
                                            <option {{$branch->status==0 ? 'selected' : ''}} value="0">Inactive</option>
                                        </select>
                                    </div>
                                    <div class="form-group col-md-12">
                                        <label>Address</label>
                                        <textarea name="address" class="form-control">{{old('address') ?? $branch->address}}</textarea>
                                    </div>
                                    <div class="form-group col-md-12">
                                        <label>Location url <code>*</code> </label>
                                        <input type="text" class="form-control" name="location_url" value="{{ old('location_url') ?? $branch->location_url }}">
                                    </div>
                                    <div class="form-group col-md-12">
                                        <label>Description <code>*</code></label>
                                        <textarea name="description" class="form-control">{!! old('description') ?? $branch->description !!}</textarea>
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
