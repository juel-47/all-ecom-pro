<div class="tab-pane fade show {{ $activeTab === 'mobile-pay' ? 'show active' : '' }}" id="list-mobile-pay" role="tabpanel" aria-labelledby="list-mobile-pay-list">
    <form action="{{ route('admin.mobile-pay-setting.update', 1) }}" method="post">
        @csrf
        @method('PUT')

        <div class="row">
            <div class="form-group col-md-6">
                <label for="status">MobilePay Status</label>
                <select id="status" class="form-control" name="active">
                    <option value="">Select</option>
                    <option value="1" {{ ($mobilePaySetting?->active ?? null) == 1 ? 'selected' : '' }}>Enable</option>
                    <option value="0" {{ ($mobilePaySetting?->active ?? null) == 0 ? 'selected' : '' }}>Disable</option>
                </select>
            </div>

            <div class="form-group col-md-6">
                <label for="account_mode">Account Mode</label>
                <select id="account_mode" class="form-control" name="environment">
                    <option value="">Select</option>
                    <option value="test" {{ @ $mobilePaySetting && $mobilePaySetting->environment=='test' ? 'selected' : '' }}>test</option>
                    <option value="production" {{ @ $mobilePaySetting && $mobilePaySetting->environment=='production' ? 'selected' : '' }}>Production</option>
                </select>
            </div>

            {{-- <div class="form-group col-md-6">
                <label for="country_name">Country Name</label>
                <select id="country_name" class="form-control select2" name="country_name">
                    <option value="">Select</option>
                    @foreach (config('settings.country_list') as $country)
                        <option value="{{ $country }}" {{ ($payoneerSetting?->country_name ?? '') === $country ? 'selected' : '' }}>
                            {{ $country }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group col-md-6">
                <label for="currency_name">Currency Name</label>
                <select id="currency_name" class="form-control select2" name="currency_name">
                    <option value="">Select</option>
                    @foreach (config('settings.currency_list') as $key => $currency)
                        <option value="{{ $currency }}" {{ ($payoneerSetting?->currency_name ?? '') === $currency ? 'selected' : '' }}>
                            {{ $key }}
                        </option>
                    @endforeach
                </select>
            </div> --}}

            <div class="form-group col-md-12">
                <label>Client ID</label>
                <input type="text" class="form-control" name="client_id" value="{{@ $mobilePaySetting->client_id ?? '' }}">
            </div>

            <div class="form-group col-md-6">
                <label>Client Secret</label>
                <input type="text" class="form-control" name="client_secret" value="{{ $mobilePaySetting->client_secret ?? '' }}">
            </div>

            <div class="form-group col-md-6">
                <label>Subscription Key</label>
                <input type="text" class="form-control" name="subscription_key" value="{{ $mobilePaySetting->subscription_key ?? '' }}">
            </div>
            <div class="form-group col-md-12">
                <label>Merchant Serial Number</label>
                <input type="text" class="form-control" name="merchant_serial_number" value="{{ $mobilePaySetting->merchant_serial_number ?? '' }}">
            </div>
            <div class="form-group col-md-12">
                <label>Webhook Secret</label>
                <input type="text" class="form-control" name="webhook_secret" value="{{ $mobilePaySetting->webhook_secret ?? '' }}">
            </div>
            <div class="form-group col-md-12">
                <label>Token Url</label>
                <input type="text" class="form-control" name="token_url" value="{{ $mobilePaySetting->token_url ?? '' }}">
            </div>
            <div class="form-group col-md-12">
                <label>Checkout Url</label>
                <input type="text" class="form-control" name="checkout_url" value="{{ $mobilePaySetting->checkout_url ?? '' }}">
            </div>
        </div>

        <button type="submit" class="btn btn-primary">Update</button>
    </form>
</div>
