@extends('backend.layouts.master')
@section('title', 'Sub Category')
@section('content')
     <!-- Main Content -->

        <section class="section">
          <div class="section-header">
            <h1>Sub Category</h1>
          </div>

          <div class="section-body">

            <div class="row">
              <div class="col-12">
                <div class="card">
                  <div class="card-header">
                    <h4>Create Sub Category</h4>
                    <div class="card-header-action">
                      <a href="{{ route('admin.sub-category.index') }}" class="btn btn-primary">Back</a>
                    </div>
                  </div>
                   <div class="card-body">
                    <form action="{{route('admin.sub-category.store')}}" method="post">
                        @csrf
                          <div class="form-group">
                            <label for="inputState">Category</label>
                            <select id="inputState" class="form-control" name="category_id">
                                <option value="">Select Category</option>
                                @foreach($categories as $category)
                                <option value="{{$category->id}}">{{$category->name}}</option>
                                @endforeach
                            </select>
                          </div>
                          <div class="row">
                        <div class="form-group col-md-6">
                            <label>Name</label>
                            <input type="text" class="form-control" name="name" value="">
                          </div>
                          <div class="form-group col-md-6">
                            <label for="inputState">Status</label>
                            <select id="inputState" class="form-control" name="status">
                              <option value="1">Active</option>
                              <option value="0">Inactive</option>
                            </select>
                          </div>
                        </div>
                          <button type="submit" class="btn btn-primary mt-3" >Create</button>
                    </form>
                  </div>
                </div>
              </div>
          </div>
        </section>
@endsection

