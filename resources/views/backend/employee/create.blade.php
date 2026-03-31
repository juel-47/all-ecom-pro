@extends('backend.layouts.master')
@section('title', $settings->site_name . ' | Create Employee')
@section('content')
    <!-- Main Content -->
    <section class="section">
        <div class="section-header">
            <h1>Employee</h1>
        </div>
        <div class="section-body">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>Create Employee</h4>
                            <div class="card-header-action">
                                <a href="{{ route('admin.employees.index') }}" class="btn btn-primary">Back</a>
                            </div>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('admin.employees.store') }}" method="post">
                                @csrf
                                <div class="row">
                                    <div class="form-group col-md-6">
                                        <label>Employee Name</label>
                                        <input type="text" class="form-control" name="name" value="{{old('name')}}">
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label>Email</label>
                                        <input type="email" class="form-control" name="email" value="{{old('email')}}">
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label>Password</label>
                                        <input type="password" class="form-control" name="password" value="">
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label>Confirm Password</label>
                                        <input type="password" class="form-control" name="password_confirmation" value="">
                                    </div>
                                    
                                    <div class="form-group col-md-6">
                                        <label>Designation</label>
                                        <input type="text" class="form-control" name="designation" value="{{ old('designation') }}">
                                    </div>
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
