@extends('backend.layouts.master')
@section('title', 'Job Applications Cover Letter' . ' | ' . $settings->site_name )
@section('content')
    <!-- Main Content -->

    <section class="section">
        <div class="section-header">
            <h1>Job Applications</h1>
        </div>
        <div class="section-body">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>All Job Applications</h4>
                            {{-- <div class="card-header-action">
                                <a href="{{ route('admin.users.create') }}" class="btn btn-primary"><i
                                        class="fas fa-plus"></i> Create New</a>
                            </div> --}}
                        </div>
                        <div class="table-responsive card-body">
                            {{ $dataTable->table(['class' => 'table table-striped table-bordered', 'id' => 'jobapplications-table']) }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

