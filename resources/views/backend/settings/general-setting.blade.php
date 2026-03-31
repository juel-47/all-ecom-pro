<div class="tab-pane fade show active" id="list-home" role="tabpanel" aria-labelledby="list-home-list">
    <form action="{{route('admin.general-setting.update')}}" method="post">
        @csrf
        @method('PUT')
        <div class="form-group">
            <label>Site Name</label>
            <input type="text" class="form-control" name="site_name" value="{{@$generalSetting->site_name}}">
        </div>
        {{-- <div class="form-group" >
            <label for="inputState">Layout</label>
            <select id="inputState" class="form-control" name="layout">
                <option value="">Select</option>
              <option {{@$generalSetting->layout=='LTR' ? 'selected' : ''}} value="LTR">LTR</option>
              <option {{@$generalSetting->layout=='RTL' ? 'selected' : ''}} value="RTL">RTL</option>
            </select>
          </div> --}}
          <div class="form-group">
            <label>Contact Email</label>
            <input type="text" class="form-control" name="contact_email" value="{{@$generalSetting->contact_email}}">
        </div>
          <div class="form-group">
            <label>Contact Phone</label>
            <input type="text" class="form-control" name="contact_phone" value="{{@$generalSetting->contact_phone}}">
        </div>
          <div class="form-group">
            <label>Contact Address</label>
            <input type="text" class="form-control" name="contact_address" value="{{@$generalSetting->contact_address}}">
        </div>
          <div class="form-group">
            <label>Goole Map Url</label>
            <input type="text" class="form-control" name="map" value="{{@$generalSetting->map}}">
        </div>
          <div class="form-group">
            <label>Default Currency Name</label>
            <select id="inputState" class="form-control select2" name="currency_name">
                <option value="" >Select</option>
                @foreach (config('settings.currency_list') as $currency)
                <option {{@$generalSetting->currency_name == $currency ? 'selected' : ''}} value="{{$currency}}">{{$currency}}</option>
                @endforeach
            </select>
        </div>
          <div class="form-group">
            <label>Currency Icon</label>
            <input type="text" class="form-control" name="currency_icon" value="{{@$generalSetting->currency_icon}}">
        </div>
          <div class="form-group">
            <label for="inputState">TimeZone</label>
            <select id="inputState" class="form-control select2" name="timezone">
                <option value="">Select</option>
                @foreach (config('settings.time_zone') as $key => $value )
                    <option {{@$generalSetting->time_zone == $key ? 'selected' : ''}} value="{{$key}}">{{$key}}</option>
                @endforeach
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Update</button>
    </form>
</div>
