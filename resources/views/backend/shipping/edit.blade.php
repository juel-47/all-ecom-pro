@extends('backend.layouts.master')
@section('title', $settings->site_name . ' | ' . (isset($shippingMethod) ? 'Edit Shipping Method' : 'Create Shipping Method'))
@section('content')
<section class="section">
    <div class="section-header">
        <h1>{{ isset($shipping) ? 'Edit' : 'Create' }} Shipping Method</h1>
    </div>
    <div class="section-body">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4>{{ isset($shipping) ? 'Edit' : 'Create' }} Shipping Method</h4>
                        <div class="card-header-action">
                            <a href="{{ route('admin.shipping-methods.index') }}" class="btn btn-primary">Back</a>
                        </div>
                    </div>
                    <div class="card-body">
                        <form action="{{ isset($shipping) ? route('admin.shipping-methods.update', $shipping->id) : route('admin.shipping-methods.store') }}" method="POST">
                            @csrf
                            @if(isset($shipping))
                                @method('PUT')
                            @endif
                            <div class="row">

                                <!-- Name -->
                                <div class="form-group col-md-6">
                                    <label for="name">Shipping Name <span class="text-danger">*</span></label>
                                    <input type="text" name="name" id="name" class="form-control"
                                        value="{{ old('name', $shipping->name ?? '') }}" required>
                                    @error('name')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>

                                <!-- Type (from database only) -->
                                <div class="form-group col-md-6">
                                    <label for="typeSelect">Shipping Type <span class="text-danger">*</span></label>
                                    <select name="type[]" id="typeSelect" class="form-control" multiple required>
                                        @php
                                            $selectedTypes = old('type', isset($shipping) ? json_decode($shipping->type, true) : []);
                                        @endphp

                                        {{-- Show only database types --}}
                                        @foreach ($selectedTypes as $type)
                                            <option value="{{ $type }}" selected>{{ ucfirst($type) }}</option>
                                        @endforeach
                                    </select>
                                    @error('type')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                    <small class="form-text text-muted">
                                        Add new type below if needed (e.g., express, pickup)
                                    </small>
                                </div>

                                <!-- Add new type -->
                                <div class="form-group col-md-6">
                                    <input type="text" id="newType" class="form-control" placeholder="Add new type">
                                    <button type="button" class="btn btn-sm btn-primary mt-2" onclick="addType()">Add Type</button>
                                </div>

                                <!-- Status -->
                                <div class="form-group col-md-6">
                                    <label for="status">Status <span class="text-danger">*</span></label>
                                    <select name="status" id="status" class="form-control" required>
                                        <option value="">Select Status</option>
                                        <option value="1" {{ old('status', $shipping->status ?? '') == 1 ? 'selected' : '' }}>Active</option>
                                        <option value="0" {{ old('status', $shipping->status ?? '') == 0 ? 'selected' : '' }}>Inactive</option>
                                    </select>
                                    @error('status')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>

                            </div>
                            <button type="submit" class="btn btn-primary mt-3">{{ isset($shipping) ? 'Update' : 'Create' }}</button>
                        </form>
                    </div> <!-- card-body -->
                </div> <!-- card -->
            </div>
        </div>
    </div>
</section>

<script>
function addType(){
    let input = document.getElementById('newType');
    let val = input.value.trim();
    let select = document.getElementById('typeSelect');

    if(val){
        if(!Array.from(select.options).some(o => o.value === val)){
            let option = new Option(val, val, true, true);
            select.add(option);
        }
        input.value = '';
    }
}
</script>
@endsection
