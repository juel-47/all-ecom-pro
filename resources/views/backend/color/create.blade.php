@extends('backend.layouts.master')
@section('title', 'Color')
@section('content')
    <!-- Main Content -->
    <section class="section">
        <div class="section-header">
            <h1>Color</h1>
        </div>
        <div class="section-body">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>Create Color</h4>
                            <div class="card-header-action">
                                <a href="{{ route('admin.color.index') }}" class="btn btn-primary">Back</a>
                            </div>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('admin.color.store') }}" method="post">
                                @csrf
                                <div class="row">
                                    <div class="form-group col-md-6">
                                        <label>Color Name</label>
                                        <input type="text" class="form-control" name="color_name" value="">
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label>Color</label>
                                        <input type="color" class="form-control" name="color_code">
                                    </div>
                                    
                                    {{-- <div class="form-group col-md-12">
                                        <label>Price <code>(set 0 for free)</code></label>
                                        <input type="text" class="form-control" name="price" value="{{ old('price') }}">
                                    </div> --}}
                                    <div class="form-group col-md-6">
                                        <label for="inputState">Status</label>
                                        <select id="inputState" class="form-control" name="status">
                                            <option value="1">Active</option>
                                            <option value="0">Inactive</option>
                                        </select>
                                    </div>
                                    {{-- <div class="form-group col-md-6">
                                        <label for="inputState">Is Default</label>
                                        <select id="inputState" class="form-control" name="is_default">
                                            <option value="">Select</option>
                                            <option value="1">Yes</option>
                                            <option value="0">No</option>
                                        </select>
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
