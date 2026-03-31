@extends('backend.layouts.master')
@section('title', $settings->site_name . ' | My Attendance')
@section('content')
    <section class="section">
        <div class="section-header">
            <h1>My Attendance</h1>
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

                                    {{-- Date picker --}}
                                    <div class="form-group mr-2 mb-2" id="date_input_group">
                                        <input type="date" id="filter_date" class="form-control">
                                    </div>

                                    {{-- Week picker --}}
                                    <div class="form-group mr-2 mb-2" id="week_input_group" style="display:none;">
                                        <input type="week" id="filter_week" class="form-control">
                                    </div>

                                    {{-- Month picker --}}
                                    <div class="form-group mr-2 mb-2" id="month_input_group" style="display:none;">
                                        <input type="month" id="filter_month" class="form-control">
                                    </div>

                                    {{-- Year input --}}
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
                                            <h6 class="card-title text-muted mb-1">Today</h6>
                                            <span id="summary_today" class="badge bg-primary fs-6 text-white">0h:0m</span>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-auto">
                                    <div class="card text-center shadow-sm">
                                        <div class="card-body py-2">
                                            <h6 class="card-title text-muted mb-1">This Week</h6>
                                            <span id="summary_week" class="badge bg-success fs-6">0h:0m</span>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-auto">
                                    <div class="card text-center shadow-sm">
                                        <div class="card-body py-2">
                                            <h6 class="card-title text-muted mb-1">This Month</h6>
                                            <span id="summary_month" class="badge bg-warning text-dark fs-6">0h:0m</span>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-auto">
                                    <div class="card text-center shadow-sm">
                                        <div class="card-body py-2">
                                            <h6 class="card-title text-muted mb-1">This Year</h6>
                                            <span id="summary_year" class="badge bg-info text-dark fs-6">0h:0m</span>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-auto">
                                    <div class="card text-center shadow-sm">
                                        <div class="card-body py-2">
                                            <h6 class="card-title text-muted mb-1">Filtered</h6>
                                            <span id="summary_filtered" class="badge bg-secondary fs-6">0h:0m</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- DataTable --}}
                        <div class="table-responsive card-body">
                            {{ $dataTable->table(['class' => 'table table-striped table-bordered', 'id' => 'employeeattendance-table']) }}
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
            let table = window.LaravelDataTables['employeeattendance-table'];

            // Show/hide input fields based on filter type
            $('#filter_type').change(function() {
                let type = $(this).val();
                $('#date_input_group, #week_input_group, #month_input_group, #year_input_group').hide();
                if (type === 'date') $('#date_input_group').show();
                else if (type === 'week') $('#week_input_group').show();
                else if (type === 'month') $('#month_input_group').show();
                else if (type === 'year') $('#year_input_group').show();
            });

            // Fetch summary
            function fetchSummary(date = '', week = '', month = '', year = '', type = 'date') {
                $.get("{{ route('employee.summary', auth()->id()) }}", {
                    date: date,
                    week: week,
                    month: month,
                    year: year,
                    type: type
                }, function(res) {
                    $('#summary_today').text(res.today);
                    $('#summary_week').text(res.week);
                    $('#summary_month').text(res.month);
                    $('#summary_year').text(res.year);
                    $('#summary_filtered').text(res.filtered);
                });
            }

            // Filter button
            $('#filterBtn').click(function() {
                let type = $('#filter_type').val();
                let url = "{{ route('employee.attendance.index') }}";

                if (type === 'date') url += '?date=' + $('#filter_date').val() + '&type=date';
                else if (type === 'week') url += '?week=' + $('#filter_week').val() + '&type=week';
                else if (type === 'month') url += '?month=' + $('#filter_month').val() + '&type=month';
                else if (type === 'year') url += '?year=' + $('#filter_year').val() + '&type=year';

                table.ajax.url(url).load();

                fetchSummary(
                    $('#filter_date').val(),
                    $('#filter_week').val(),
                    $('#filter_month').val(),
                    $('#filter_year').val(),
                    type
                );
            });

            // Reset button
            $('#resetBtn').click(function() {
                $('#filter_date').val('');
                $('#filter_week').val('');
                $('#filter_month').val('');
                $('#filter_year').val('');
                $('#filter_type').val('date');
                $('#date_input_group').show();
                $('#week_input_group, #month_input_group, #year_input_group').hide();

                table.ajax.url("{{ route('employee.attendance.index') }}").load();
                fetchSummary();
            });

            // Initial summary load
            fetchSummary();
        });
    </script>
@endpush
