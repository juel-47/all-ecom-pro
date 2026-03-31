@extends('backend.layouts.master')
@section('title', 'Dashboard')
@section('content')
    <section class="section">

        {{-- Section Header --}}
        <div class="section-header">
            <div class="d-flex justify-content-between align-items-center w-100">
                <h1 class="mb-0">Dashboard</h1>

                @if (auth()->user()->employee && auth()->user()->employee->role_name === 'employee')
                    <div class="d-flex align-items-center gap-4">
                        {{-- <div class="text-end">
                    <small class="text-muted d-block">Welcome back</small>
                    <strong class="text-dark fs-5">{{ auth()->user()->name }}</strong>
                </div> --}}

                        <div class="d-flex align-items-center gap-3">
                            {{-- Live Attendance Button --}}
                            <button id="toggleAttendanceBtn"
                                class="btn btn-primary btn-lg shadow-sm rounded-pill d-flex align-items-center gap-2 px-4 py-2 mr-2 transition-all"
                                style="min-width: 80px; font-weight: 600;">
                                <i class="fas fa-play" id="toggleIcon"></i>
                                <span id="toggleText">Start</span>
                            </button>

                            {{-- Attendance Status Badge --}}
                            <div id="attendanceStatus">
                                <span class="badge bg-secondary px-3 py-2 rounded-pill fw-semibold" id="statusBadge">
                                    Not Started
                                </span>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        {{-- Dashboard Cards --}}
        <div class="row mt-4">
             @if (auth()->user()->employee && auth()->user()->employee->role_name === 'employee')
                <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                    <a href="">
                        <div class="card card-statistic-1">
                            <div class="card-icon bg-primary"><i class="fas fa-briefcase"></i></div>
                            <div class="card-wrap">
                                <div class="card-header">
                                    <h4>Todays Working Hours</h4>
                                </div>
                                <div class="card-body">{{$workingHours['today'] ?? '0h:0m:0s' }}</div>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                    <a href="">
                        <div class="card card-statistic-1">
                            <div class="card-icon bg-primary"><i class="fas fa-briefcase"></i></div>
                            <div class="card-wrap">
                                <div class="card-header">
                                    <h4>This Month Working Hours</h4>
                                </div>
                                <div class="card-body">{{$workingHours['month'] ?? '0h:0m:0s' }}</div>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                    <a href="">
                        <div class="card card-statistic-1">
                            <div class="card-icon bg-primary"><i class="fas fa-briefcase"></i></div>
                            <div class="card-wrap">
                                <div class="card-header">
                                    <h4>This Year Working Hours</h4>
                                </div>
                                <div class="card-body">{{$workingHours['year'] ?? '0h:0m:0s' }}</div>
                            </div>
                        </div>
                    </a>
                </div>
            @else
                @can('Manage Orders')
                    <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                        <a href="{{ route('admin.order.index') }}">
                            <div class="card card-statistic-1">
                                <div class="card-icon bg-primary"><i class="fas fa-cart-plus"></i></div>
                                <div class="card-wrap">
                                    <div class="card-header">
                                        <h4>Todays Orders</h4>
                                    </div>
                                    <div class="card-body">{{ @$todaysOrders }}</div>
                                </div>
                            </div>
                        </a>
                    </div>

                    <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                        <a href="{{ route('admin.order.index') }}">
                            <div class="card card-statistic-1">
                                <div class="card-icon bg-primary"><i class="fas fa-cart-plus"></i></div>
                                <div class="card-wrap">
                                    <div class="card-header">
                                        <h4>Total Orders</h4>
                                    </div>
                                    <div class="card-body">{{ @$totalOrders }}</div>
                                </div>
                            </div>
                        </a>
                    </div>
                @endcan

                @can('Manage Products')
                    <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                        <a href="{{ route('admin.products.index') }}">
                            <div class="card card-statistic-1">
                                <div class="card-icon bg-primary"><i class="fab fa-product-hunt"></i></div>
                                <div class="card-wrap">
                                    <div class="card-header">
                                        <h4>Total Products</h4>
                                    </div>
                                    <div class="card-body">{{ @$totalProduct }}</div>
                                </div>
                            </div>
                        </a>
                    </div>
                @endcan

                @can('Manage Categories')
                    <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                        <a href="{{ route('admin.category.index') }}">
                            <div class="card card-statistic-1">
                                <div class="card-icon bg-info"><i class="fas fa-list"></i></div>
                                <div class="card-wrap">
                                    <div class="card-header">
                                        <h4>Total Categories</h4>
                                    </div>
                                    <div class="card-body">{{ @$totalCategories }}</div>
                                </div>
                            </div>
                        </a>
                    </div>
                @endcan

                @can('Manage Setting & More')
                    <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                        <a href="{{ route('admin.customer.index') }}">
                            <div class="card card-statistic-1">
                                <div class="card-icon bg-warning"><i class="far fa-user"></i></div>
                                <div class="card-wrap">
                                    <div class="card-header">
                                        <h4>Total Customer</h4>
                                    </div>
                                    <div class="card-body">{{ @$totalUsers }}</div>
                                </div>
                            </div>
                        </a>
                    </div>
                @endcan
            @endif
        </div>

    </section>

    {{-- Ajax Script for Live Attendance --}}
    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const toggleBtn = document.getElementById('toggleAttendanceBtn');
                const toggleIcon = document.getElementById('toggleIcon');
                const toggleText = document.getElementById('toggleText');
                const statusBadge = document.getElementById('statusBadge');
                const token = "{{ csrf_token() }}";

                // Fetch current status
                function fetchAttendanceStatus() {
                    fetch("{{ route('employee.attendance.status') }}")
                        .then(res => res.json())
                        .then(data => {
                            if (data.active) {
                                toggleText.textContent = 'Stop';
                                toggleIcon.className = 'fas fa-stop';
                                statusBadge.textContent = 'Started';
                                statusBadge.className = 'badge bg-warning px-3 py-2 rounded-pill fw-semibold';
                            } else if (data.startTime) {
                                toggleText.textContent = 'Start';
                                toggleIcon.className = 'fas fa-play';
                                statusBadge.textContent = 'Ended';
                                statusBadge.className = 'badge bg-success px-3 py-2 rounded-pill fw-semibold';
                            } else {
                                toggleText.textContent = 'Start';
                                toggleIcon.className = 'fas fa-play';
                                statusBadge.textContent = 'Not Started';
                                statusBadge.className = 'badge bg-secondary px-3 py-2 rounded-pill fw-semibold';
                            }
                        });
                }

                // Initial load
                fetchAttendanceStatus();

                // Toggle button click
                toggleBtn.addEventListener('click', function() {
                    let action = toggleText.textContent.trim() === 'Start' ? 'start' : 'end';
                    fetch(`{{ url('employee/attendance') }}/${action}`, {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': token,
                                'Accept': 'application/json',
                                'Content-Type': 'application/json'
                            },
                        })
                        .then(res => res.json())
                        .then(data => {
                            fetchAttendanceStatus();
                            if (data.status === 'success') toastr.success(data.message);
                            else if (data.status === 'info') toastr.info(data.message);
                            else if (data.status === 'error') toastr.error(data.message);
                        })
                        .catch(err => console.log(err));
                });

                // Optional: refresh badge every 30s
                setInterval(fetchAttendanceStatus, 30000);
            });
        </script>
    @endpush
@endsection
