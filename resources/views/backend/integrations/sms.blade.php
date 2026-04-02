@extends('backend.layouts.master')
@section('title', 'SMS API')
@section('content')
    <section class="section">
        <div class="section-header">
            <h1>SMS API</h1>
        </div>

        <div class="section-body">
            <form action="{{ route('admin.integrations.sms.update', $smsSetting?->id ?? 1) }}" method="post">
                @csrf
                @method('PUT')

                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h4>Overview</h4>
                            </div>
                            <div class="card-body">
                                <div class="alert alert-light mb-4">
                                    For BD numbers, Barta will be used. For other countries, the Global gateway will be used.
                                    Fill only the required fields. Leave Advanced options empty if not needed.
                                </div>

                                <div class="row">
                                    <div class="form-group col-md-4">
                                        <label>SMS Status</label>
                                        <select class="form-control" name="status">
                                            <option value="1" {{ (int)($smsSetting?->status ?? 0) === 1 ? 'selected' : '' }}>Enable</option>
                                            <option value="0" {{ (int)($smsSetting?->status ?? 0) === 0 ? 'selected' : '' }}>Disable</option>
                                        </select>
                                    </div>
                                    <div class="form-group col-md-8">
                                        <label>Message Template</label>
                                        <input type="text" class="form-control" name="message_template"
                                            value="{{ $smsSetting?->message_template ?? 'Your order #{order_id} has been placed successfully. Amount: {amount} {currency}.' }}">
                                        <small class="text-muted">Placeholders: {order_id}, {invoice_id}, {amount}, {currency}, {payment_method}, {site_name}</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h4>Bangladesh (Barta)</h4>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="form-group col-md-6">
                                        <label>BD SMS Status</label>
                                        <select class="form-control" name="bd_enabled">
                                            <option value="1" {{ (int)($smsSetting?->bd_enabled ?? 0) === 1 ? 'selected' : '' }}>Enable</option>
                                            <option value="0" {{ (int)($smsSetting?->bd_enabled ?? 0) === 0 ? 'selected' : '' }}>Disable</option>
                                        </select>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label>Barta Driver</label>
                                        <select class="form-control" name="bd_driver">
                                            @php
                                                $drivers = ['log','ssl','bulksms','alphasms','esms','mimsms','grameenphone','banglalink','robi','infobip','adnsms','greenweb','elitbuzz','smsnoc'];
                                            @endphp
                                            <option value="">Select Driver</option>
                                            @foreach ($drivers as $driver)
                                                <option value="{{ $driver }}" {{ ($smsSetting?->bd_driver ?? '') === $driver ? 'selected' : '' }}>
                                                    {{ strtoupper($driver) }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <small class="text-muted">Example: For BulkSMSBD use driver = bulksms</small>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="form-group col-md-12">
                                        @php
                                            $bdCreds = $smsSetting?->bd_credentials ?? [];
                                            $bdKeys = array_keys($bdCreds);
                                            $bdVals = array_values($bdCreds);
                                        @endphp
                                        <label>BD Credentials (Key/Value)</label>
                                        <div class="row">
                                            <div class="form-group col-md-4">
                                                <input type="text" class="form-control" name="bd_key_1" placeholder="Key (e.g., api_key)" value="{{ $bdKeys[0] ?? '' }}">
                                            </div>
                                            <div class="form-group col-md-4">
                                                <input type="text" class="form-control" name="bd_value_1" placeholder="Value" value="{{ $bdVals[0] ?? '' }}">
                                            </div>
                                            <div class="form-group col-md-4">
                                                <input type="text" class="form-control" name="bd_key_2" placeholder="Key (e.g., sender_id)" value="{{ $bdKeys[1] ?? '' }}">
                                            </div>
                                            <div class="form-group col-md-4">
                                                <input type="text" class="form-control" name="bd_value_2" placeholder="Value" value="{{ $bdVals[1] ?? '' }}">
                                            </div>
                                            <div class="form-group col-md-4 mb-0">
                                                <input type="text" class="form-control" name="bd_key_3" placeholder="Key (optional)" value="{{ $bdKeys[2] ?? '' }}">
                                            </div>
                                            <div class="form-group col-md-4 mb-0">
                                                <input type="text" class="form-control" name="bd_value_3" placeholder="Value" value="{{ $bdVals[2] ?? '' }}">
                                            </div>
                                        </div>
                                        <small class="text-muted">For BulkSMSBD: api_key, sender_id</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h4>Global (Any REST API)</h4>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="form-group col-md-3">
                                        <label>Global SMS Status</label>
                                        <select class="form-control" name="global_enabled">
                                            <option value="1" {{ (int)($smsSetting?->global_enabled ?? 0) === 1 ? 'selected' : '' }}>Enable</option>
                                            <option value="0" {{ (int)($smsSetting?->global_enabled ?? 0) === 0 ? 'selected' : '' }}>Disable</option>
                                        </select>
                                    </div>
                                    <div class="form-group col-md-3">
                                        <label>HTTP Method</label>
                                        <select class="form-control" name="http_method">
                                            @php $method = $smsSetting?->http_method ?? 'POST'; @endphp
                                            <option value="POST" {{ $method === 'POST' ? 'selected' : '' }}>POST</option>
                                            <option value="GET" {{ $method === 'GET' ? 'selected' : '' }}>GET</option>
                                        </select>
                                    </div>
                                    <div class="form-group col-md-3">
                                        <label>Content Type</label>
                                        <select class="form-control" name="content_type">
                                            @php $ctype = $smsSetting?->content_type ?? 'form'; @endphp
                                            <option value="form" {{ $ctype === 'form' ? 'selected' : '' }}>Form</option>
                                            <option value="json" {{ $ctype === 'json' ? 'selected' : '' }}>JSON</option>
                                        </select>
                                    </div>
                                    <div class="form-group col-md-3">
                                        <label>Request Timeout (sec)</label>
                                        <input type="number" class="form-control" name="request_timeout"
                                            value="{{ $smsSetting?->request_timeout ?? 10 }}">
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="form-group col-md-6">
                                        <label>Provider Name</label>
                                        <input type="text" class="form-control" name="provider_name"
                                            value="{{ $smsSetting?->provider_name ?? '' }}" placeholder="Twilio / Vonage / Any">
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label>Endpoint URL</label>
                                        <input type="text" class="form-control" name="endpoint_url"
                                            value="{{ $smsSetting?->endpoint_url ?? '' }}" placeholder="https://api.example.com/send">
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="form-group col-md-3">
                                        <label>To Param</label>
                                        <input type="text" class="form-control" name="to_key" value="{{ $smsSetting?->to_key ?? 'to' }}">
                                    </div>
                                    <div class="form-group col-md-3">
                                        <label>Message Param</label>
                                        <input type="text" class="form-control" name="message_key" value="{{ $smsSetting?->message_key ?? 'message' }}">
                                    </div>
                                    <div class="form-group col-md-3">
                                        <label>Sender Param (Optional)</label>
                                        <input type="text" class="form-control" name="sender_key" placeholder="sender_id" value="{{ $smsSetting?->sender_key ?? '' }}">
                                    </div>
                                    <div class="form-group col-md-3">
                                        <label>Sender Value</label>
                                        <input type="text" class="form-control" name="sender_value" placeholder="SENDER" value="{{ $smsSetting?->sender_value ?? '' }}">
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="form-group col-md-3">
                                        <label>Add Country Code</label>
                                        <select class="form-control" name="add_code">
                                            <option value="1" {{ (int)($smsSetting?->add_code ?? 0) === 1 ? 'selected' : '' }}>Yes</option>
                                            <option value="0" {{ (int)($smsSetting?->add_code ?? 0) === 0 ? 'selected' : '' }}>No</option>
                                        </select>
                                    </div>
                                    <div class="form-group col-md-3">
                                        <label>Default Country Code</label>
                                        <input type="text" class="form-control" name="country_code"
                                            value="{{ $smsSetting?->country_code ?? '' }}" placeholder="1 / 44 / 61 / 880">
                                    </div>
                                    <div class="form-group col-md-3">
                                        <label>Auth Type</label>
                                        <select class="form-control" name="auth_type">
                                            @php $auth = $smsSetting?->auth_type ?? 'none'; @endphp
                                            <option value="none" {{ $auth === 'none' ? 'selected' : '' }}>None</option>
                                            <option value="bearer" {{ $auth === 'bearer' ? 'selected' : '' }}>Bearer Token</option>
                                            <option value="header" {{ $auth === 'header' ? 'selected' : '' }}>Header Key</option>
                                            <option value="basic" {{ $auth === 'basic' ? 'selected' : '' }}>Basic Auth</option>
                                            <option value="query" {{ $auth === 'query' ? 'selected' : '' }}>Query Param</option>
                                        </select>
                                    </div>
                                    <div class="form-group col-md-3">
                                        <label>Auth Key</label>
                                        <input type="text" class="form-control" name="auth_key"
                                            value="{{ $smsSetting?->auth_key ?? '' }}" placeholder="API_KEY / username">
                                    </div>
                                    <div class="form-group col-md-3">
                                        <label>Auth Value</label>
                                        <input type="text" class="form-control" name="auth_value"
                                            value="{{ $smsSetting?->auth_value ?? '' }}" placeholder="token / password">
                                    </div>
                                </div>

                                <div class="card mt-3">
                                    <div class="card-header">
                                        <h4 class="mb-0">Advanced Options</h4>
                                        <div class="card-header-action">
                                            <a data-toggle="collapse" href="#smsAdvanced" class="btn btn-sm btn-outline-secondary">Show/Hide</a>
                                        </div>
                                    </div>
                                    <div class="collapse" id="smsAdvanced">
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="form-group col-md-4">
                                                    <label>Headers (JSON)</label>
                                                    <textarea class="form-control" name="headers" rows="4"
                                                        placeholder='{"Accept":"application/json"}'>{{ $smsSetting?->headers ? json_encode($smsSetting->headers, JSON_PRETTY_PRINT) : '' }}</textarea>
                                                    <small class="text-muted">Usually not required</small>
                                                </div>
                                                <div class="form-group col-md-4">
                                                    <label>Extra Params (JSON)</label>
                                                    <textarea class="form-control" name="extra_params" rows="4"
                                                        placeholder='{"route":"4"}'>{{ $smsSetting?->extra_params ? json_encode($smsSetting->extra_params, JSON_PRETTY_PRINT) : '' }}</textarea>
                                                    <small class="text-muted">Provider-specific field</small>
                                                </div>
                                                <div class="form-group col-md-4">
                                                    <label>Wrapper (Optional)</label>
                                                    <input type="text" class="form-control" name="wrapper"
                                                        value="{{ $smsSetting?->wrapper ?? '' }}" placeholder="sms / data">
                                                    <small class="text-muted">Wrapper Params (JSON)</small>
                                                    <textarea class="form-control mt-2" name="wrapper_params" rows="3"
                                                        placeholder='{"campaign_id":"welcome"}'>{{ $smsSetting?->wrapper_params ? json_encode($smsSetting->wrapper_params, JSON_PRETTY_PRINT) : '' }}</textarea>
                                                </div>
                                                <div class="form-group col-md-3">
                                                    <label>JSON To Array</label>
                                                    <select class="form-control" name="json_to_array">
                                                        <option value="1" {{ (int)($smsSetting?->json_to_array ?? 1) === 1 ? 'selected' : '' }}>Yes</option>
                                                        <option value="0" {{ (int)($smsSetting?->json_to_array ?? 1) === 0 ? 'selected' : '' }}>No</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <button type="submit" class="btn btn-primary">Update SMS Settings</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
@endsection
