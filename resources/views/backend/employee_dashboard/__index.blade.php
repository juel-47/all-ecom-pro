@extends('backend.layouts.master')
@section('title', $settings->site_name . ' | Attendance')
@section('content')
    <!-- Main Content -->

    <section class="section">
        <div class="section-header">
            <h1>Attendance</h1>
        </div>
        <div class="section-body">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>All Attendance</h4>
                            <div class="card-header-action">
                                <div class="d-flex align-items-center flex-wrap">
                                    <div class="form-group mr-2 mb-2">
                                        <input type="date" id="filter_date" class="form-control">
                                    </div>

                                    <button type="button" id="filterBtn" class="btn btn-primary mr-2 mb-2">Filter</button>
                                    <button type="button" id="resetBtn" class="btn btn-secondary mb-2">Reset</button>
                                </div>
                            </div>
                        </div>
                        {{-- <div class="card-body">
                            <form id="filterForm" class="form-inline mb-3">
                                <div class="form-group mr-2">
                                    <label for="year" class="mr-2">Year:</label>
                                    <select name="year" id="year" class="form-control">
                                        <option value="">All</option>
                                        @for ($i = date('Y'); $i >= date('Y') - 5; $i--)
                                            <option value="{{ $i }}">{{ $i }}</option>
                                        @endfor
                                    </select>
                                </div>

                                <div class="form-group mr-2">
                                    <label for="month" class="mr-2">Month:</label>
                                    <select name="month" id="month" class="form-control">
                                        <option value="">All</option>
                                        @for ($m = 1; $m <= 12; $m++)
                                            <option value="{{ $m }}">{{ date('F', mktime(0, 0, 0, $m, 1)) }}
                                            </option>
                                        @endfor
                                    </select>
                                </div>

                                <div class="form-group mr-2">
                                    <label for="day" class="mr-2">Day:</label>
                                    <input type="number" name="day" id="day" class="form-control"
                                        placeholder="1-31">
                                </div>

                                <button type="button" id="filterBtn" class="btn btn-primary">Filter</button>
                                <button type="button" id="resetBtn" class="btn btn-secondary ml-2">Reset</button>
                            </form>
                        </div> --}}
                        {{-- <hr> --}}
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

    {{-- working code || form method if user want to filter  --}}

    {{-- <script>
        $(document).ready(function() {
            let table = window.LaravelDataTables["employeeattendance-table"];

            // Filter Button Click
            $('#filterBtn').click(function() {
                let year = $('#year').val();
                let month = $('#month').val();
                let day = $('#day').val();

                // Update Ajax URL dynamically
                table.ajax.url("{{ route('employee.attendance.index') }}?year=" + year + "&month=" + month +
                    "&day=" + day).load();
            });

            // Reset Button Click
            $('#resetBtn').click(function() {
                $('#year').val('');
                $('#month').val('');
                $('#day').val('');
                table.ajax.url("{{ route('employee.attendance.index') }}").load();
            });
        });
    </script> --}}
    {{-- <script>
        $(document).ready(function() {
            let table = $('#employeeattendance-table').DataTable();

            $('#filterBtn').click(function() {
                let date = $('#filter_date').val(); // YYYY-MM-DD format
                table.ajax.url("{{ route('employee.attendance.index') }}?date=" + date).load();
            });

            $('#resetBtn').click(function() {
                $('#filter_date').val('');
                table.ajax.url("{{ route('employee.attendance.index') }}").load();
            });
        });
    </script> --}}
@endpush
