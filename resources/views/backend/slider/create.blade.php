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
            <h4>Create Slider</h4>
            <div class="card-header-action">
              <a href="{{ route('admin.slider.index') }}" class="btn btn-primary">Back</a>
            </div>
          </div>
          <div class="card-body">
            <form action="{{ route('admin.slider.store') }}" method="post" enctype="multipart/form-data">
              @csrf
              <div class="form-group">
                <label>Title</label>
                <input type="text" class="form-control" name="type" value="{{ old('type') }}">
              </div>
              <div class="form-group">
                <label>Description</label>
                <input type="text" class="form-control" name="title" value="{{ old('title') }}">
              </div>
              <div class="form-group">
                <label>Starting Price</label>
                <input type="text" class="form-control" name="starting_price" value="{{ old('starting_price') }}">
              </div>
              <div class="form-group">
                <label>Button Url</label>
                <input type="text" class="form-control" name="btn_url" value="{{ old('btn_url') }}">
              </div>
              <div class="form-group">
                <label>Serial</label>
                <input type="text" class="form-control" name="serial" value="{{ old('serial') }}">
              </div>
              <div class="row">
                <div class="form-group col-md-6">
                  <label for="inputState">Status</label>
                  <select id="inputState" class="form-control" name="status">
                    <option value="1">Active</option>
                    <option value="0">Inactive</option>
                  </select>
                </div>
                <div class="form-group col-md-6">
                  <label>Banner</label>
                  <input type="file" class="form-control" name="banner">
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