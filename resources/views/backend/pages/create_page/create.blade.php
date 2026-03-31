@extends('backend.layouts.master')
@section('title', $settings->site_name . ' | Create Page')
@section('content')
    <!-- Main Content -->
    <section class="section">
        <div class="section-header">
            <h1>Page</h1>
        </div>
        <div class="section-body">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>Create Page</h4>
                            <div class="card-header-action">
                                <a href="{{ route('admin.create-page.index') }}" class="btn btn-primary">Back</a>
                            </div>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('admin.create-page.store') }}" method="post">
                                @csrf
                                <div class="row">
                                    <div class="form-group col-md-6">
                                        <label for="page_for">Page For <code>*</code></label>
                                        <select id="page_for" class="form-control" name="page_for">
                                            <option value="">Select</option>
                                            <option value="f_about" {{ old('page_for') == 'f_about' ? 'selected' : '' }}>Footer
                                                About</option>
                                            <option value="f_h_s" {{ old('pageFor') == 'f_h_s' ? 'selected' : '' }}>Footer
                                                HELP & SUPPORT </option>
                                            <option value="f_l" {{ old('pageFor') == 'f_l' ? 'selected' : '' }}>Footer
                                                LEGAL </option>
                                        </select>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label>Name <code>*</code></label>
                                        <input type="text" class="form-control" name="name" value="{{old('name')}}">
                                    </div>
                                    <div class="form-group col-md-12">
                                        <label>Title <code>*</code></label>
                                        <input type="text" class="form-control" name="title" value="{{old('title')}}">
                                    </div>

                                    <div class="form-group col-md-12">
                                        <label>Long Description <code>*</code></label>
                                        <textarea name="description" class="form-control summernote" required>{{ old('description') }}</textarea>

                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="inputState">Status</label>
                                        <select id="inputState" class="form-control" name="status">
                                            <option value="1">Active</option>
                                            <option value="0">Inactive</option>
                                        </select>
                                    </div>
                                    {{-- <div class="form-group col-md-6">
                                        <label for="status" class=" font-weight-bold">Status</label>
                                        <br>
                                        <label class="custom-switch">
                                            <input type="checkbox" name="status" class="custom-switch-input" value="1">
                                            <span class="custom-switch-indicator"></span>
                                        </label>
                                    </div> --}}
                                </div>
                                <button type="submit" class="btn btn-primary mt-3">Create</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
    </section>
@endsection
