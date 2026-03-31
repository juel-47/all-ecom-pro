@extends('backend.layouts.master')
@section('title', 'Blog Category')
@section('content')
     <section class="section">
          <div class="section-header">
            <h1>Blog Category</h1>
          </div>
          <div class="section-body">
            <div class="row">
              <div class="col-12">
                <div class="card">
                  <div class="card-header">
                    <h4>Update Blog Category</h4>
                  </div>
                   <div class="card-body">
                    <form action="{{route('admin.blog-category.update', $category->id)}}" method="post">
                        @csrf
                        @method('put')
                          <div class="row">
                        <div class="form-group col-md-6">
                            <label>Name</label>
                            <input type="text" class="form-control" name="name" value="{{$category->name}}">
                          </div>
                          <div class="form-group col-md-6">
                            <label for="inputState">Status</label>
                            <select id="inputState" class="form-control" name="status">
                              <option {{$category->status == 1 ? 'selected' : ''}} value="1">Active</option>
                              <option {{$category->status == 0 ? 'selected' : ''}} value="0">Inactive</option>
                            </select>
                          </div>
                        </div>
                          <button type="submit" class="btn btn-primary mt-3" >Update</button>
                    </form>
                  </div>
                </div>
              </div>
          </div>
        </section>
@endsection