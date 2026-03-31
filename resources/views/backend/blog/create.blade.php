@extends('backend.layouts.master')
@section('title', 'Blog')
@section('content')

    <section class="section">
        <div class="section-header">
            <h1>Blog</h1>
        </div>
        <div class="section-body">

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>Create Blog</h4>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('admin.blog.store') }}" method="post" enctype="multipart/form-data">
                                @csrf
                                <div class="row">
                                    <div class="form-group col-md-12">
                                        <label>Title</label>
                                        <input type="text" class="form-control" name="title"
                                            value="{{ old('title') }}">
                                    </div>

                                    <div class="form-group col-md-12">
                                        <label for="inputState">Category</label>
                                        <select id="inputState" class="form-control main-category" name="category">
                                            <option value="">select category</option>
                                            @foreach ($categories as $category)
                                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group col-md-12">
                                        <label>Description</label>
                                        <textarea name="description" class="form-control summernote">{{ old('description') }}</textarea>
                                    </div>

                                    <div class="form-group col-md-6">
                                        <label>Seo Title</label>
                                        <input type="text" class="form-control" name="seo_title"
                                            value="{{ old('seo_title') }}">
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="inputState">Status</label>
                                        <select id="inputState" class="form-control" name="status">
                                            <option value="1">Active</option>
                                            <option value="0">Inactive</option>
                                        </select>
                                    </div>
                                    <div class="form-group col-md-12">
                                        <label>Seo Description</label>
                                        <textarea class="form-control" name="seo_description" value="">{{ old('seo_description') }}</textarea>
                                    </div>
                                    <div class="form-group col-md-12">
                                        <label>Image</label>
                                        <input type="file" class="form-control" name="image">
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
