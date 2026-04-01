@extends('backend.layouts.master')
@section('title', 'SMS API')
@section('content')
    <section class="section">
        <div class="section-header">
            <h1>SMS API</h1>
        </div>

        <div class="section-body">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>SMS Providers</h4>
                        </div>
                        <div class="card-body">
                            <p class="text-muted mb-0">Add SMS provider credentials here (e.g., Twilio, SMSBD, BulkSMS).</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
