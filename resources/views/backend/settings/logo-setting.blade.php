<div class="tab-pane fade" id="list-messages" role="tabpanel" aria-labelledby="list-messages-list">
    <form action="{{ route('admin.log-setting.update') }}" method="post" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="row">
            <div class="form-group col-md-6">
                <label>Logo</label>
                <input type="file" class="form-control" name="logo" value="">
                <input type="hidden" name="old_logo" value="{{ @$logoSetting->logo }}">
            </div>
            <div class="form-group col-md-6">
                <img src="{{ asset(@$logoSetting->logo) }}" alt="" width="150px">
            </div>
            <div class="form-group col-md-6">
                <label>Favicon</label>
                <input type="file" class="form-control" name="favicon" value="">
                <input type="hidden" name="old_favicon" value="{{ @$logoSetting->favicon }}">
            </div>
            <div class="form-group col-md-6">
                <img src="{{ asset(@$logoSetting->favicon) }}" alt="" width="150px">
            </div>
        </div>
        <button type="submit" class="btn btn-primary">Update</button>
    </form>
</div>
