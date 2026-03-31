@extends('backend.layouts.master')
@section('title', $settings->site_name . ' | Settings')
@section('content')
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
                                <!-- Sidebar -->
                                <div class="col-12 col-md-2 mb-3">
                                    <div class="list-group" id="list-tab" role="tablist">
                                        <a class="list-group-item list-group-item-action active" id="list-home-list"
                                            data-toggle="list" href="#list-home" role="tab">General Setting</a>
                                        <a class="list-group-item list-group-item-action" id="list-email-config-list"
                                            data-toggle="list" href="#list-email-config" role="tab">Email
                                            Configuration</a>
                                        <a class="list-group-item list-group-item-action" id="list-messages-list"
                                            data-toggle="list" href="#list-messages" role="tab">Logo and Favicon</a>
                                    </div>
                                </div>

                                <!-- Content -->
                                <div class="col-12 col-md-10 border">
                                    <div class="tab-content" id="nav-tabContent">
                                        <!-- General Setting -->
                                        @include('backend.settings.general-setting')

                                        <!-- Email Configuration -->
                                        @include('backend.settings.email-configuration')

                                        <!-- Logo Configuration -->
                                        @include('backend.settings.logo-setting')
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection
