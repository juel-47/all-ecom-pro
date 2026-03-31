@extends('backend.layouts.master')
@section('title', 'Size')
@section('content')
    <!-- Main Content -->
    <section class="section">
        <div class="section-header">
            <h1>Size</h1>
        </div>
        <div class="section-body">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>Create Size</h4>
                            <div class="card-header-action">
                                <a href="{{ route('admin.size.index') }}" class="btn btn-primary">Back</a>
                            </div>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('admin.size.update', $size->id) }}" method="post">
                                @csrf
                                @method('put')
                                <div class="row">
                                    <div class="form-group col-md-6">
                                        <label>Size Name</label>
                                        <input type="text" class="form-control" name="size_name" value="{{ $size->size_name }}">
                                    </div>
                                    {{-- <div class="form-group col-md-6">
                                        <label>Price <code>(set 0 for free)</code></label>
                                        <input type="text" class="form-control" name="price" value="{{ $size->price }}">
                                    </div> --}}
                                    <div class="form-group col-md-6">
                                        <label for="inputState">Status</label>
                                        <select id="inputState" class="form-control" name="status">
                                            <option {{$size->status==1 ? 'selected' : ''}} value="1">Active</option>
                                            <option {{$size->status==0 ? 'selected' : ''}} value="0">Inactive</option>
                                        </select>
                                    </div>
                                    {{-- <div class="form-group col-md-6">
                                        <label for="inputState">Is Default</label>
                                        <select id="inputState" class="form-control" name="is_default">
                                            <option value="">Select</option>
                                            <option {{$size->is_default==1 ? 'selected' : ''}} value="1">Yes</option>
                                            <option {{$size->is_default==0 ? 'selected' : ''}} value="0">No</option>
                                        </select>
                                    </div> --}}
                                </div>
                                <button type="submit" class="btn btn-primary mt-3">Update</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
    </section>
@endsection