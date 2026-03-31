@extends('backend.layouts.master')
@section('title', 'Payment Setting')
@section('content')
@section('content')
    @php
        $activeTab = session('active_tab' , 'paypal');
    @endphp
    <!-- Main Content -->
    <section class="section">
        <div class="section-header">
            <h1>Settings</h1>
        </div>

        <div class="section-body">

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-12 col-md-2 mb-3">
                                    <div class="list-group" id="list-tab" role="tablist">
                                        <a class="list-group-item list-group-item-action {{ $activeTab == 'paypal' ? 'active' : '' }}"
                                            id="list-home-list" data-toggle="list" href="#list-home"
                                            role="tab">Paypal</a>

                                        <a class="list-group-item list-group-item-action {{ $activeTab == 'bkash' ? 'active' : '' }}"
                                            id="list-bkash-list" data-toggle="list" href="#list-bkash"
                                            role="tab">bKash</a>

                                        <a class="list-group-item list-group-item-action {{ $activeTab == 'payoneer' ? 'active' : '' }}"
                                            id="list-payoneer-list" data-toggle="list" href="#list-payoneer"
                                            role="tab">Payoneer</a>


                                        <a class="list-group-item list-group-item-action {{ $activeTab == 'mobile-pay' ? 'active' : '' }}"
                                            id="list-mobile-pay-list" data-toggle="list" href="#list-mobile-pay"
                                            role="tab">Mobile Pay</a>
                                        {{-- <a class="list-group-item list-group-item-action" id="list-stripe-list" data-toggle="list" href="#list-stripe" role="tab">Stripe</a> --}}
                                        <a class="list-group-item list-group-item-action {{ $activeTab == 'cod' ? 'active' : '' }}" id="list-cod-list"
                                            data-toggle="list" href="#list-cod" role="tab">COD</a>
                                        {{-- <a class="list-group-item list-group-item-action" id="list-settings-list" data-toggle="list" href="#list-settings" role="tab">Settings</a> --}}
                                    </div>
                                </div>
                                <div class="col-12 col-md-10 border">
                                    <div class="tab-content" id="nav-tabContent">
                                        <!-- Paypal Setting -->
                                        @include('backend.payment-setting.sections.paypal-setting')

                                        <!-- bKash Setting -->
                                        @include('backend.payment-setting.sections.bkash-setting')

                                        <!-- Stripe Setting -->
                                        {{-- @include('backend.payment-settings.sections.stripe-setting') --}}

                                        <!-- Payoneer Setting -->
                                        @include('backend.payment-setting.sections.payoneer-setting')

                                        <!-- Mobile Pay Setting -->
                                        @include('backend.payment-setting.sections.mobilepay-setting')

                                        <!-- COD Setting -->
                                        @include('backend.payment-setting.sections.cod-setting')

                                        {{-- <div class="tab-pane fade" id="list-settings" role="tabpanel" aria-labelledby="list-settings-list">
                        Lorem ipsum culpa in ad velit dolore anim labore incididunt do aliqua sit veniam commodo elit dolore do labore occaecat laborum sed quis proident fugiat sunt pariatur. Cupidatat ut fugiat anim ut dolore excepteur ut voluptate dolore excepteur mollit commodo.
                    </div> --}}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
    </section>
@endsection
