@extends('backend.layouts.master')
@section('title', 'Edit Campaign')
@section('content')
    <section class="section">
        <div class="section-header">
            <h1>Campaign</h1>
        </div>
        <div class="section-body">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>Edit Campaign</h4>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('admin.campaign.update', $campaign->id) }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                @method('PUT')
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Preview Image</label><br>
                                            <img src="{{asset('storage/'.$campaign->image)}}" width="150px" alt="">
                                        </div>
                                        <div class="form-group">
                                            <label>Image</label>
                                            <input type="file" name="image" class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Preview Banner</label><br>
                                            <img src="{{asset('storage/'.$campaign->banner)}}" width="150px" alt="">
                                        </div>
                                        <div class="form-group">
                                            <label>Banner</label>
                                            <input type="file" name="banner" class="form-control">
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label>Name</label>
                                    <input type="text" name="name" value="{{ $campaign->name }}" class="form-control">
                                </div>

                                <div class="form-group">
                                    <label>Title</label>
                                    <input type="text" name="title" value="{{ $campaign->title }}" class="form-control">
                                </div>

                                <div class="form-group">
                                    <label>Sub Title</label>
                                    <input type="text" name="sub_title" value="{{ $campaign->sub_title }}" class="form-control">
                                </div>

                                <div class="form-group">
                                    <label>Description</label>
                                    <textarea name="description" class="form-control summernote">{{ $campaign->description }}</textarea>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Start Date</label>
                                            <input type="text" name="start_date" value="{{ $campaign->start_date }}" class="form-control datepicker">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>End Date</label>
                                            <input type="text" name="end_date" value="{{ $campaign->end_date }}" class="form-control datepicker">
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="status">Status</label>
                                    <select name="status" class="form-control">
                                        <option {{ $campaign->status == 1 ? 'selected' : '' }} value="1">Active</option>
                                        <option {{ $campaign->status == 0 ? 'selected' : '' }} value="0">Inactive</option>
                                    </select>
                                </div>

                                <button type="submit" class="btn btn-primary">Update</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
