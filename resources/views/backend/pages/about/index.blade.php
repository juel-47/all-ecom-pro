@extends('backend.layouts.master')
@section('title', $settings->site_name . ' | About')
@section('content')
     <!-- Main Content -->

        <section class="section">
          <div class="section-header">
            <h1>About</h1>
          </div>

          <div class="section-body">

            <div class="row">
              <div class="col-12">
                <div class="card">
                  {{-- <div class="card-header">
                    <h4>About</h4>
                  </div> --}}
                   <div class="card-body">
                    <form action="{{route('admin.about.update')}}" method="post">
                        @csrf
                        @method('put')
                        <div class="form-group ">
                            <label>Content</label>
                            <textarea name="content" id="" cols="30" rows="10" class="summernote">{!!@$content->content!!}</textarea>
                          </div>
                          <button type="submit" class="btn btn-primary mt-3" >Update</button>
                    </form>
                  </div>
                </div>
              </div>
          </div>
        </section>
@endsection

