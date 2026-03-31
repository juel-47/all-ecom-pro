@extends('backend.layouts.master')
@section('title', 'Category Update')

@section('content')
    <!-- Main Content -->

    <section class="section">
        <div class="section-header">
            <h1>Category</h1>
            {{-- <div class="section-header-breadcrumb">
              <div class="breadcrumb-item active"><a href="#">Dashboard</a></div>
              <div class="breadcrumb-item"><a href="#">Components</a></div>
              <div class="breadcrumb-item">Table</div>
            </div> --}}
        </div>

        <div class="section-body">

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>Update Category</h4>
                            <div class="card-header-action">
                                <a href="{{ route('admin.category.index') }}" class="btn btn-primary">Back</a>
                            </div>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('admin.category.update', $category->id) }}" method="post" enctype="multipart/form-data">
                                @method('put')
                                @csrf
                                <div class="row">

                                    <div class="form-group col-md-6">
                                        <label class="mr-2">Icon</label>
                                        <button class="btn btn-primary " data-icon="{{ $category->icon }}"
                                            data-selected-class="btn-danger" data-unselected-class="btn-info"
                                            role="iconpicker" name="icon" value=""></button>

                                        {{-- <input type="text" class="form-control" name="icon" value=""> --}}
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label class="custom-switch mt-2">
                                            <input type="checkbox" name="front_show" class="custom-switch-input"
                                                  {{ $category->front_show == 1 ? 'checked' : '' }}>
                                            <span class="custom-switch-indicator"></span>
                                            <span class="custom-switch-description">Front Show</span>
                                        </label>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group col-md-6">
                                        <label>Name</label>
                                        <input type="text" class="form-control" name="name"
                                            value="{{ $category->name }}">
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="inputState">Status</label>
                                        <select id="inputState" class="form-control" name="status">
                                            <option {{ $category->status == 1 ? 'selected' : '' }} value="1">Active
                                            </option>
                                            <option {{ $category->status == 0 ? 'selected' : '' }} value="0">Inactive
                                            </option>
                                        </select>
                                    </div>
                                     <div class="form-group col-md-12">
                                        <label>Meta Title</label>
                                        <input type="text" class="form-control" name="meta_title"
                                            value="{{ $category->meta_title }}">
                                    </div>
                                    <div class="form-group col-md-12">
                                        <label>Meta Description</label>
                                        <textarea name="meta_description" class="form-control">{!! $category->meta_description !!}</textarea>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label>Image <code>(optional)</code> </label>
                                        <input type="file" class="form-control" name="image" >
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label>Image Preview </label>
                                        <img src="{{ asset($category->image) }}" alt="" width="150px">
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
