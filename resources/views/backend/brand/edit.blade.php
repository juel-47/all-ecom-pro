@extends('backend.layouts.master')
@section('title', 'Brand')
@section('content')
    <section class="section">
          <div class="section-header">
            <h1>Update Brand</h1>
          </div>
          <div class="section-body">
            <div class="row">
              <div class="col-12">
                <div class="card">
                  <div class="card-header">
                    <h4>Update Brand</h4>
                    <div class="card-header-action">
                      <a href="{{ route('admin.brand.index') }}" class="btn btn-primary">Back</a>
                    </div>
                  </div>
                   <div class="card-body">
                    <form action="{{route('admin.brand.update',$brand->id )}}" method="post" enctype="multipart/form-data">
                        @csrf
                        @method('put')
                        <div class="row">
                    <div class="form-group col-md-6">
                        <label>Name</label>
                        <input type="text" class="form-control" name="name" value="{{$brand->name}}">
                      </div>
                      <div class="form-group col-md-6" >
                        <label for="inputState">Status</label>
                        <select id="inputState" class="form-control" name="status">
                          <option {{$brand->status ==1 ? 'selected': ''}} value="1">Active</option>
                          <option {{$brand->status ==0 ? 'selected': ''}} value="0">Inactive</option>
                        </select>
                      </div>
                    </div>
                      <div class="row">
                      <div class="form-group col-md-6" >
                        <label for="inputState">Is Featured</label>
                        <select id="inputState" class="form-control" name="is_featured">
                          <option  value="">Select</option>
                          <option {{$brand->is_featured ==1 ? 'selected': ''}}   value="1">Yes</option>
                          <option {{$brand->is_featured ==0 ? 'selected': ''}} value="0">No</option>
                        </select>
                      </div>
                    <div class="form-group col-md-6">
                        <label>Logo</label>
                        <input type="file" class="form-control" name="logo">
                      </div>
                    </div>
                    <div class="form-group col-md-6">
                        <label class="mr-5">Preview</label>
                        <img src="{{asset($brand->logo)}}"  alt="" width="120px">
                      </div>
                      <button type="submit" class="btn btn-primary mt-3" >Update</button>
                    </form>
                  </div>
                </div>
              </div>
          </div>
        </section>
@endsection
