<div class="tab-pane fade show {{$activeTab === 'paypal' ? 'show active' : ''}}" id="list-home" role="tabpanel" aria-labelledby="list-home-list">
    <form action="{{route('admin.paypal-setting.update', 1)}}" method="post">
        @csrf
        @method('PUT')
        <div class="row">
        <div class="form-group col-md-6">
            <label for="inputState">Paypal Status</label>
            <select id="inputState" class="form-control" name="status">
                <option value="">Select</option>
              <option {{$paypalSetting?->status===1 ? 'selected' : ''}} value="1">Enable</option>
              <option {{$paypalSetting?->status===0 ? 'selected' : ''}} value="0">Disable</option>
            </select>
          </div>
        <div class="form-group col-md-6">
            <label for="inputState">Account Mode</label>
            <select id="inputState" class="form-control" name="account_mode">
                <option value="">Select</option>
              <option {{$paypalSetting->account_mode===0 ? 'selected' : ''}} value="0">Sandbox</option>
              <option {{$paypalSetting->account_mode===1 ? 'selected' : ''}} value="1">Live</option>
            </select>
          </div>
        <div class="form-group col-md-6">
            <label for="inputState">Country Name</label>
            <select id="inputState" class="form-control select2" name="country_name">
                <option value="">Select</option>
                @foreach (config('settings.country_list') as $country)
                    <option {{$paypalSetting->country_name===$country ? 'selected' : ''}} value="{{$country}}">{{$country}}</option>
                @endforeach

            </select>
          </div>
        <div class="form-group col-md-6" >
            <label for="inputState">Currency Name</label>
            <select id="inputState" class="form-control select2" name="currency_name">
                <option value="">Select</option>
                @foreach (config('settings.currency_list') as $key=>$currency)
                    <option {{$paypalSetting->currency_name===$currency ? 'selected' : ''}} value="{{$currency}}">{{$key}}</option>
                @endforeach

            </select>
          </div>
        <div class="form-group col-md-12">
            <label>Paypal Client Id</label>
            <input type="text" class="form-control" name="client_id" value="{{$paypalSetting->client_id}}">
        </div>
        <div class="form-group col-md-6">
            <label>Paypal Secret Key</label>
            <input type="text" class="form-control" name="secret_key" value="{{$paypalSetting->secret_key}}">
        </div>
        <div class="form-group col-md-6">
            <label>Currency Rate (Per  {{$settings->currency_name}})</label>
            <input type="text" class="form-control" name="currency_rate" value="{{$paypalSetting->currency_rate}}">
        </div>
    </div>
        <button type="submit" class="btn btn-primary">Update</button>
    </form>
</div>
