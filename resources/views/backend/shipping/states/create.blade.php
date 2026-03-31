@extends('backend.layouts.master')
@section('title', $settings->site_name . ' | Create State')

@section('content')
<section class="section">
    <div class="section-header">
        <h1>Create State</h1>
        <div class="section-header-breadcrumb">
            <a href="{{ route('admin.states.index') }}" class="btn btn-primary">Back</a>
        </div>
    </div>

    <div class="section-body">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4>New State</h4>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('admin.states.store') }}" method="POST">
                            @csrf
                            <div class="row">
                                {{-- State Name --}}
                                <div class="form-group col-md-12">
                                    <label for="name">State Name</label>
                                    <input type="text" name="name" id="name" class="form-control" 
                                           value="{{ old('name') }}" required>
                                    @error('name')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>

                                {{-- Country Select --}}
                                <div class="form-group col-md-6">
                                    <label for="country_id">Select Country</label>
                                    <select name="country_id" id="country_id" class="form-control" required>
                                        <option value="">Select Country</option>
                                        @foreach ($countries as $country)
                                            <option value="{{ $country->id }}" 
                                                {{ old('country_id') == $country->id ? 'selected' : '' }}>
                                                {{ $country->name }} ({{ $country->code }})
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('country_id')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>

                                {{-- Status --}}
                                <div class="form-group col-md-6">
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
