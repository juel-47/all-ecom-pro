<div class="tab-pane fade show {{$activeTab === 'steadfast' ? 'show active' : ''}}" id="list-steadfast" role="tabpanel" aria-labelledby="list-steadfast-list">
    <form action="{{route('admin.steadfast-setting.update', 1)}}" method="post">
        @csrf
        @method('PUT')
        <div class="row">
            <div class="form-group col-md-6">
                <label for="steadfastStatus">Steadfast Status</label>
                <select id="steadfastStatus" class="form-control" name="status">
                    <option value="">Select</option>
                    <option {{ ($steadfastSetting?->status ?? 0) === 1 ? 'selected' : '' }} value="1">Enable</option>
                    <option {{ ($steadfastSetting?->status ?? 0) === 0 ? 'selected' : '' }} value="0">Disable</option>
                </select>
            </div>
            <div class="form-group col-md-6">
                <label>Base URL</label>
                <input type="text" class="form-control" name="base_url" value="{{ $steadfastSetting?->base_url ?? 'https://portal.steadfast.com.bd/api/v1' }}">
            </div>
            <div class="form-group col-md-6">
                <label>API Key</label>
                <input type="text" class="form-control" name="api_key" value="{{ $steadfastSetting?->api_key ?? '' }}">
            </div>
            <div class="form-group col-md-6">
                <label>Secret Key</label>
                <input type="text" class="form-control" name="secret_key" value="{{ $steadfastSetting?->secret_key ?? '' }}">
            </div>
            <div class="form-group col-md-12">
                <label>Webhook Bearer Token (Optional)</label>
                <input type="text" class="form-control" name="webhook_bearer_token" value="{{ $steadfastSetting?->webhook_bearer_token ?? '' }}">
            </div>
        </div>
        <button type="submit" class="btn btn-primary">Update</button>
    </form>
</div>
