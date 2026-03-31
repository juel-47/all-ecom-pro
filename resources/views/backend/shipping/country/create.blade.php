@extends('backend.layouts.master')
@section('title', $settings->site_name . ' | Create Country')
@section('content')
<section class="section">
    <div class="section-header">
        <h1>Create Country</h1>
        <div class="section-header-breadcrumb">
            <a href="{{ route('admin.countries.index') }}" class="btn btn-primary">Back</a>
        </div>
    </div>

    <div class="section-body">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4>New Country</h4>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('admin.countries.store') }}" method="POST">
                            @csrf
                            
                                <div class="row">
                                    <div class="form-group col-md-12">
                                    <label for="name">Country Name</label>
                                    <input type="text" name="name" id="name" class="form-control" value="{{ old('name') }}" required>
                                    @error('name')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
    
                                <div class="form-group col-md-6">
                                    <label for="code">Country Code</label>
                                    <input type="text" name="code" id="code" class="form-control" value="{{ old('code') }}" placeholder="e.g US, UK, CA, etc " required>
                                    @error('code')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
    
                                <div class="form-group col-md-6 ">
                                    <label for="status">Status</label>
                                    <select name="status" id="status" class="form-control">
                                         <option value="1">Active</option>
                                        <option value="0">Inactive</option>
                                    </select>
                                    @error('status')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                                </div>
                           

                            <button type="submit" class="btn btn-primary mt-3">Create</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
