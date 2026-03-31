<div class="tab-pane fade show {{$activeTab === 'bkash' ? 'show active' : ''}}" id="list-bkash" role="tabpanel" aria-labelledby="list-bkash-list">
    <form action="{{route('admin.bkash-setting.update', 1)}}" method="post">
        @csrf
        @method('PUT')
        <div class="row">
            <div class="form-group col-md-6">
                <label for="bkashStatus">bKash Status</label>
                <select id="bkashStatus" class="form-control" name="status">
                    <option value="">Select</option>
                    <option {{ ($bkashSetting?->status ?? 0) === 1 ? 'selected' : '' }} value="1">Enable</option>
                    <option {{ ($bkashSetting?->status ?? 0) === 0 ? 'selected' : '' }} value="0">Disable</option>
                </select>
            </div>
            <div class="form-group col-md-6">
                <label for="bkashMode">Account Mode</label>
                <select id="bkashMode" class="form-control" name="account_mode">
                    <option value="">Select</option>
                    <option {{ ($bkashSetting?->account_mode ?? 0) === 0 ? 'selected' : '' }} value="0">Sandbox</option>
                    <option {{ ($bkashSetting?->account_mode ?? 0) === 1 ? 'selected' : '' }} value="1">Live</option>
                </select>
            </div>
            <div class="form-group col-md-6">
                <label>bKash App Key</label>
                <input type="text" class="form-control" name="app_key" value="{{ $bkashSetting?->app_key ?? '' }}">
            </div>
            <div class="form-group col-md-6">
                <label>bKash App Secret</label>
                <input type="text" class="form-control" name="app_secret" value="{{ $bkashSetting?->app_secret ?? '' }}">
            </div>
            <div class="form-group col-md-6">
                <label>bKash Username</label>
                <input type="text" class="form-control" name="username" value="{{ $bkashSetting?->username ?? '' }}">
            </div>
            <div class="form-group col-md-6">
                <label>bKash Password</label>
                <input type="text" class="form-control" name="password" value="{{ $bkashSetting?->password ?? '' }}">
            </div>
        </div>
        <button type="submit" class="btn btn-primary">Update</button>
    </form>
</div>
