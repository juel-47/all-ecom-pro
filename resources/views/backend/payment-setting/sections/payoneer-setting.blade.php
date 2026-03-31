<div class="tab-pane fade show {{ $activeTab === 'payoneer' ? 'show active' : '' }}" id="list-payoneer" role="tabpanel" aria-labelledby="list-payoneer-list">
    <form action="{{ route('admin.payoneer-setting.update', 1) }}" method="post">
        @csrf
        @method('PUT')

        <div class="row">
            <div class="form-group col-md-6">
                <label for="status">Payoneer Status</label>
                <select id="status" class="form-control" name="status">
                    <option value="">Select</option>
                    <option value="1" {{ ($payoneerSetting?->status ?? null) == 1 ? 'selected' : '' }}>Enable</option>
                    <option value="0" {{ ($payoneerSetting?->status ?? null) == 0 ? 'selected' : '' }}>Disable</option>
                </select>
            </div>

            <div class="form-group col-md-6">
                <label for="account_mode">Account Mode</label>
                <select id="account_mode" class="form-control" name="account_mode">
                    <option value="">Select</option>
                    <option value="0" {{ ($payoneerSetting?->account_mode ?? null) == 0 ? 'selected' : '' }}>Sandbox</option>
                    <option value="1" {{ ($payoneerSetting?->account_mode ?? null) == 1 ? 'selected' : '' }}>Live</option>
                </select>
            </div>

            <div class="form-group col-md-6">
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
            </div>

            <div class="form-group col-md-12">
                <label>API Key</label>
                <input type="text" class="form-control" name="api_key" value="{{ $payoneerSetting->api_key ?? '' }}">
            </div>

            <div class="form-group col-md-6">
                <label>API Secret Key</label>
                <input type="text" class="form-control" name="api_secret_key" value="{{ $payoneerSetting->api_secret_key ?? '' }}">
            </div>

            <div class="form-group col-md-6">
                <label>Program ID</label>
                <input type="text" class="form-control" name="program_id" value="{{ $payoneerSetting->program_id ?? '' }}">
            </div>
            <div class="form-group col-md-12">
                <label>Api Url</label>
                <input type="text" class="form-control" name="api_url" value="{{ $payoneerSetting->api_url ?? '' }}">
            </div>
            <div class="form-group col-md-12">
                <label>Token url</label>
                <input type="text" class="form-control" name="token_url" value="{{ $payoneerSetting->token_url ?? '' }}">
            </div>
        </div>

        <button type="submit" class="btn btn-primary">Update</button>
    </form>
</div>
