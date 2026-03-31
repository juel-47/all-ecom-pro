@extends('backend.layouts.master')
@section('title', 'Slider')
@section('content')
    <!-- Main Content -->
    <section class="section">
        <div class="section-header">
            <h1>Slider</h1>
        </div>
        <div class="section-body">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>Update Slider</h4>
                            <div class="card-header-action">
                                <a href="{{ route('admin.slider.index') }}" class="btn btn-primary">Back</a>
                            </div>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('admin.slider.update', $slider->id) }}" method="post"
                                enctype="multipart/form-data">
                                @csrf
                                @method('put')
                                <div class="form-group">
                                    <label>Type</label>
                                    <input type="text" class="form-control" name="type" value="{{ $slider->type }}">
                                </div>
                                <div class="form-group">
                                    <label>Title</label>
                                    <input type="text" class="form-control" name="title" value="{{ $slider->title }}">
                                </div>
                                <div class="row">
                                    <div class="form-group col-md-6">
                                        <label>Starting Price</label>
                                        <input type="text" class="form-control" name="starting_price"
                                            value="{{ $slider->starting_price }}">
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label>Serial</label>
                                        <input type="text" class="form-control" name="serial"
                                            value="{{ $slider->serial }}">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label>Button Url</label>
                                    <input type="text" class="form-control" name="btn_url"
                                        value="{{ $slider->btn_url }}">
                                </div>
                                <div class="row">
                                    <div class="form-group col-md-6">
                                        <label for="inputState">Status</label>
                                        <select id="inputState" class="form-control" name="status">
                                            <option {{ $slider->status == 1 ? 'selected' : '' }} value="1">Active
                                            </option>
                                            <option {{ $slider->status == 0 ? 'selected' : '' }} value="0">Inactive
                                            </option>
                                        </select>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label>Banner</label>
                                        <input type="file" class="form-control" name="banner">
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label class="mr-5">Preview</label>
                                        <img src="{{ asset($slider->banner) }}" alt="" width="120px">
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
