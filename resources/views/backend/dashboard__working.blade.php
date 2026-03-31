@extends('backend.layouts.master')
@section('title', 'Dashboard')
@section('content')
    <section class="section">
        <div class="section-header">
            <h1>Dashboard</h1>
        </div>
        <div class="row">
            @if (auth()->user()->employee && auth()->user()->employee->role_name === 'employee')
                <div class="col-md-6">
                    <div class="card shadow-lg border-0 text-center p-4">
                        <div class="card-body">
                            <h4 class="mb-3 fw-semibold">👋 Welcome, {{ auth()->user()->name }}</h4>
                            {{-- <p class="text-muted mb-4">আজকের Attendance শুরু করতে নিচের বাটন ব্যবহার করো</p> --}}

                            <div class="d-flex justify-content-center gap-3">
                                <form action="{{ route('employee.attendance.start') }}" method="POST" class="d-inline">
                                    @csrf
                                    <button class="btn btn-success btn-lg px-5 py-3 shadow-sm">
                                        <i class="fa fa-play me-1"></i> Start Attendance
                                    </button>
                                </form>

                                <form action="{{ route('employee.attendance.end') }}" method="POST" class="d-inline">
                                    @csrf
                                    <button class="btn btn-danger btn-lg px-5 py-3 shadow-sm">
                                        <i class="fa fa-stop me-1"></i> Stop Attendance
                                    </button>
                                </form>
                            </div>

                            <hr class="my-4">

                            {{-- <a href="{{ route('employee.attendance.index') }}" class="btn btn-outline-primary">
                            <i class="fa fa-calendar-check me-1"></i> View My Attendance
                        </a> --}}
                        </div>
                    </div>
                </div>
            @else
                @can('Manage Orders')
                    <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                        <a href="{{ route('admin.order.index') }}">
                            <div class="card card-statistic-1">
                                <div class="card-icon bg-primary">
                                    <i class="fas fa-cart-plus"></i>
                                </div>
                                <div class="card-wrap">
                                    <div class="card-header">
                                        <h4>Todays Orders</h4>
                                    </div>
                                    <div class="card-body">
                                        {{ @$todaysOrders }}
                                        {{-- 11 --}}
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                        <a href="{{ route('admin.order.index') }}">
                            <div class="card card-statistic-1">
                                <div class="card-icon bg-primary">
                                    <i class="fas fa-cart-plus"></i>
                                </div>
                                <div class="card-wrap">
                                    <div class="card-header">
                                        <h4>Total Orders</h4>
                                    </div>
                                    <div class="card-body">
                                        {{ @$totalOrders }}
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                @endcan
                @can('Manage Products')
                    <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                        <a href="{{ route('admin.products.index') }}">
                            <div class="card card-statistic-1">
                                <div class="card-icon bg-primary">
                                    {{-- <i class="fa fa-product-hunt"></i> --}}
                                    {{-- <i class="fa-brands fa-product-hunt"></i> --}}
                                    <i class="fab fa-product-hunt"></i>
                                </div>
                                <div class="card-wrap">
                                    <div class="card-header">
                                        <h4>Total Products</h4>
                                    </div>
                                    <div class="card-body">
                                        {{ @$totalProduct }}
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                @endcan
                {{-- @foreach ($ordersByStatus as $order_status)
                <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                    <a href="{{ route('admin.order.status', ['id' => $order_status['order_status_id']]) }}">
                        <div class="card card-statistic-1">
                            <div class="card-icon bg-primary">
                                <i class="fas fa-cart-plus"></i>
                            </div>
                            <div class="card-wrap">
                                <div class="card-header">
                                    <h4>{{ @$order_status['name'] }}</h4>
                                </div>
                                <div class="card-body">
                                    {{ @$order_status['count'] }}
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
            @endforeach --}}
                @can('Manage Categories')
                    <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                        <a href="{{ route('admin.category.index') }}">
                            <div class="card card-statistic-1">
                                <div class="card-icon bg-info">
                                    <i class="fas fa-list"></i>
                                </div>
                                <div class="card-wrap">
                                    <div class="card-header">
                                        <h4>Total Categories</h4>
                                    </div>
                                    <div class="card-body">
                                        {{ @$totalCategories }}
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                @endcan
                @can('Manage Setting & More')
                    <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                        <a href="{{ route('admin.customer.index') }}">
                            <div class="card card-statistic-1">
                                <div class="card-icon bg-warning">
                                    <i class="far fa-user"></i>
                                </div>
                                <div class="card-wrap">
                                    <div class="card-header">
                                        <h4>Total Customer</h4>
                                    </div>
                                    <div class="card-body">
                                        {{ @$totalUsers }}
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                @endcan

                {{-- <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                <a href="{{route('admin.pending-orders')}}">
                <div class="card card-statistic-1">
                    <div class="card-icon bg-primary">
                        <i class="fas fa-cart-plus"></i>
                    </div>
                    <div class="card-wrap">
                        <div class="card-header">
                            <h4>Today Pending Orders</h4>
                        </div>
                        <div class="card-body">
                            {{$todaysPendingOrders}}
                            11
                        </div>
                    </div>
                </div>
                </a>
            </div> --}}
            @endif
        </div>
    </section>
@endsection
