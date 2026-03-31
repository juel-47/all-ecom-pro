@extends('backend.layouts.master')
@section('title', 'Product Variant')
@section('content')
    <!-- Main Content -->
    
        <section class="section">
          <div class="section-header">
            <h1>Product Variant</h1>
          </div>

          <div class="section-body">

            <div class="row">
              <div class="col-12">
                <div class="mb-3 ml-3">
                    <a href="{{route('admin.product-variant.index', ['product'=>request()->product])}}" class="btn btn-primary "><i class="fas fa-caret-left mr-1"></i> <span class="d-none d-md-inline">Back</span> </a>
                </div>
                <div class="card">
                  <div class="card-header">
                    <h4>Create Product Variant</h4>
                  </div>
                   <div class="card-body">
                    <form action="{{route('admin.product-variant.store')}}" method="post">
                        @csrf
                          <div class="row">
                        <div class="form-group col-md-6">
                            <label>Name</label>
                            <input type="text" class="form-control" name="name" value="{{old('name')}}">
                          </div>
                        <div class="form-group">

                            <input type="hidden" class="form-control" name="product_id" value="{{request()->product}}">
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
