@extends('backend.layouts.master')
@section('content')
     <!-- Main Content -->

        <section class="section">
          <div class="section-header">
            <h1>Footer</h1>
          </div>
          <div class="section-body">
            <div class="row">
              <div class="col-12">
                <div class="card">
                   <div class="card-body">
                    <form action="{{route('admin.footer-info.update', 1)}}" method="post" enctype="multipart/form-data">
                        @csrf
                        @method('put')
                        <div class="row">
                        <div class="form-group col-md-6">
                            <label>Phone</label>
                            <input type="text" class="form-control" name="phone" value="{{@$footerInfo->phone}}">
                        </div>
                        <div class="form-group col-md-6">
                            <label>Email</label>
                            <input type="text" class="form-control" name="email" value="{{@$footerInfo->email}}">
                        </div>
                        <div class="form-group col-md-12">
                            <label>Address</label>
                            <input type="text" class="form-control" name="address" value="{{@$footerInfo->address}}">
                        </div>
                            <div class="form-group col-md-6">
                                <label>Logo preview</label>
                                <img src="{{asset(@$footerInfo->logo)}}" text-align="center" alt="" width="150px">
                            </div>
                            <div class="form-group col-md-6">
                                <label>Footer Logo</label>
                                <input type="file" class="form-control" name="footer_logo" value="">
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Copyright</label>
                            <input type="text" class="form-control" name="copyright" value="{{@$footerInfo->copyright}}">
                        </div>
                          <button type="submit" class="btn btn-primary mt-3" >Update</button>
                    </form>
                  </div>
                </div>
              </div>
          </div>
        </section>

@endsection

