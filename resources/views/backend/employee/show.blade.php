@extends('backend.layouts.master')
@section('title', 'Attendance | ' . $settings->site_name)
@section('content')
    <section class="section">
        <div class="section-header d-flex justify-content-between">
            <h1>Attendance - {{ $employee->user->name }}</h1>
            <div class="card-header-action">
                <a href="{{ route('admin.employees.index') }}" class="btn btn-primary rounded-md">Back</a>
            </div>
        </div>

        <div class="section-body">
            <div class="row">
                <div class="col-12">
                    <div class="card">

                        {{-- Filter --}}
                        <div class="card-header">
                            <h4>All Attendance</h4>
                            <div class="card-header-action">
                                <div class="d-flex align-items-center flex-wrap">

                                    {{-- Filter type --}}
                                    <div class="form-group mr-2 mb-2">
                                        <select id="filter_type" class="form-control">
                                            <option value="date">Day</option>
                                            <option value="week">Week</option>
                                            <option value="month">Month</option>
                                            <option value="year">Year</option>
                                        </select>
                                    </div>

                                    {{-- Date --}}
                                    <div class="form-group mr-2 mb-2" id="date_input_group">
                                        <input type="date" id="filter_date" class="form-control">
                                    </div>

                                    {{-- Week --}}
                                    <div class="form-group mr-2 mb-2" id="week_input_group" style="display:none;">
                                        <input type="week" id="filter_week" class="form-control">
                                    </div>

                                    {{-- Month --}}
                                    <div class="form-group mr-2 mb-2" id="month_input_group" style="display:none;">
                                        <input type="month" id="filter_month" class="form-control">
                                    </div>

                                    {{-- Year --}}
                                    <div class="form-group mr-2 mb-2" id="year_input_group" style="display:none;">
                                        <input type="number" id="filter_year" class="form-control" placeholder="YYYY">
                                    </div>

                                    <button type="button" id="filterBtn" class="btn btn-primary mr-2 mb-2">Filter</button>
                                    <button type="button" id="resetBtn" class="btn btn-danger mb-2">Reset</button>

                                </div>
                            </div>
                        </div>

                        {{-- Summary --}}
                        <div class="card-body">
                            <div class="row justify-content-center g-4">

                                <div class="col-auto">
                                    <div class="card text-center shadow-sm">
                                        <div class="card-body py-2">
                                            <h6 class="text-muted mb-1">Today</h6>
                                            <span id="summary_today" class="badge bg-primary fs-6 text-white">0h:0m</span>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-auto">
                                    <div class="card text-center shadow-sm">
                                        <div class="card-body py-2">
                                            <h6 class="text-muted mb-1">This Week</h6>
                                            <span id="summary_week" class="badge bg-success fs-6">0h:0m</span>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-auto">
                                    <div class="card text-center shadow-sm">
                                        <div class="card-body py-2">
                                            <h6 class="text-muted mb-1">This Month</h6>
                                            <span id="summary_month" class="badge bg-warning text-dark fs-6">0h:0m</span>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-auto">
                                    <div class="card text-center shadow-sm">
                                        <div class="card-body py-2">
                                            <h6 class="text-muted mb-1">This Year</h6>
                                            <span id="summary_year" class="badge bg-info text-dark fs-6">0h:0m</span>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-auto">
                                    <div class="card text-center shadow-sm">
                                        <div class="card-body py-2">
                                            <h6 class="text-muted mb-1">Filtered</h6>
                                            <span id="summary_filtered" class="badge bg-secondary fs-6">0h:0m</span>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>

                        {{-- DataTable --}}
                        <div class="table-responsive card-body">
                            {{ $dataTable->table(['class' => 'table table-striped table-bordered', 'id' => 'allemployeeattendance-table']) }}
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('scripts')
    {{ $dataTable->scripts(attributes: ['type' => 'module']) }}

    <script>
        $(document).ready(function() {

            let table = window.LaravelDataTables['allemployeeattendance-table'];

            // Show/hide input fields on filter type change
            $('#filter_type').change(function() {
                let type = $(this).val();

                $('#date_input_group, #week_input_group, #month_input_group, #year_input_group').hide();

                if (type === 'date') $('#date_input_group').show();
                if (type === 'week') $('#week_input_group').show();
                if (type === 'month') $('#month_input_group').show();
                if (type === 'year') $('#year_input_group').show();
            });

            // Fetch summary with correct formatting
            function fetchSummary() {

                $.get("{{ route('admin.employees.summary', $employee->id) }}", {
                    type: $('#filter_type').val(),
                    date: $('#filter_date').val(),
                    week: $('#filter_week').val(),
                    month: $('#filter_month').val(),
                    year: $('#filter_year').val()
                }, function(res) {

                    function fix(v) {
                        return v.replace('h ', 'h:');
                    }

                    $('#summary_today').text(fix(res.today));
                    $('#summary_week').text(fix(res.week));
                    $('#summary_month').text(fix(res.month));
                    $('#summary_year').text(fix(res.year));
                    $('#summary_filtered').text(fix(res.filtered));
                });
            }

            // Filter button click
            $('#filterBtn').click(function() {

                let type = $('#filter_type').val();
                let url = "{{ route('admin.employees.show', $employee->id) }}";

                if (type === 'date')
                    url += '?type=date&date=' + $('#filter_date').val();

                if (type === 'week')
                    url += '?type=week&week=' + $('#filter_week').val();

                if (type === 'month')
                    url += '?type=month&month=' + $('#filter_month').val();

                if (type === 'year')
                    url += '?type=year&year=' + $('#filter_year').val();

                table.ajax.url(url).load();
                fetchSummary();
            });

            // Reset button
            $('#resetBtn').click(function() {

                $('#filter_date, #filter_week, #filter_month, #filter_year').val('');
                $('#filter_type').val('date').change();

                table.ajax.url("{{ route('admin.employees.show', $employee->id) }}").load();
                fetchSummary();
            });

            // Initial load
            fetchSummary();
        });
    </script>
@endpush
