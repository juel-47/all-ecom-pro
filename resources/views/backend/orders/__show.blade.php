@php
    $address = json_decode($order->order_address);
    // dd($address);
    $shipping = json_decode($order->shipping_method);
    // dd($shippingMethod);
    $coupon = json_decode($order->coupon);
    // dd($coupon);
@endphp
@extends('backend.layouts.master')
@section('content')
    <!-- Main Content -->

    <section class="section">
        <div class="section-header justify-content-between">
            <h1>Orders Details</h1>
            <div class="section-header-action justify-content-end">
                <a href="{{ route('admin.order.index') }}" class="btn btn-primary">Back</a>
            </div>
            {{-- <div class="section-header-breadcrumb">
        <div class="breadcrumb-item active"><a href="#">Dashboard</a></div>
        <div class="breadcrumb-item"><a href="#">Components</a></div>
        <div class="breadcrumb-item">Table</div>
    </div> --}}
        </div>

        <div class="section-body">
            <div class="invoice">
                <div class="invoice-print">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="invoice-title">
                                <h2>Invoice</h2>
                                <div class="invoice-number">Order #{{ $order->invoice_id }}</div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-md-6">
                                    <address>
                                        <strong>Billed To:</strong><br>
                                        <b>Name: </b> {{ $address->name }}<br>
                                        <b>Eamil: </b> {{ $address->email }}<br>
                                        <b>Phone: </b>{{ $address->phone }}<br>
                                        <b>Address: </b>{{ $address->address }}, <br>
                                        {{ $address->city }}, {{ $address->state }}, {{ $address->zip }}<br>
                                        <b>Country: </b>{{ $address->country }}
                                    </address>
                                </div>
                                <div class="col-md-6 text-md-right">
                                    <address>
                                        <strong>Billed To:</strong><br>
                                        <b>Name:</b> {{ $address->name }}<br>
                                        <b>Eamil:</b> {{ $address->email }}<br>
                                        <b>Phone:</b> {{ $address->phone }}<br>
                                        <b>Address:</b> {{ $address->address }}, <br>
                                        {{ $address->city }}, {{ $address->state }}, {{ $address->zip }}<br>
                                        <b>Country:</b> {{ $address->country }}
                                    </address>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <address>
                                        <strong>Payment Information:</strong><br>
                                        <b>Method:</b> {{ $order->payment_method }}<br>
                                        <b>Transaction id:</b> {{ @$order->transaction->transaction_id }} <br>
                                        <b>Status:</b> {{ $order->payment_status === 1 ? 'Complete' : 'Pending' }}
                                    </address>
                                </div>
                                <div class="col-md-6 text-md-right">
                                    <address>
                                        <strong>Order Date:</strong><br>
                                        {{ date('d F, Y', strtotime($order->created_at)) }}<br><br>
                                    </address>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-4">
                        <div class="col-md-12">
                            <div class="section-title">Order Summary</div>
                            <p class="section-lead">All items here cannot be deleted.</p>
                            <div class="table-responsive">
                                <table class="table table-striped table-hover table-md">
                                    <tr>
                                        <th data-width="40">#</th>
                                        <th>Item</th>
                                        <th>Variant</th>
                                        {{-- <th>Vendor Name</th> --}}
                                        <th class="text-center">Price</th>
                                        <th class="text-center">Quantity</th>
                                        <th class="text-right">Totals</th>
                                    </tr>
                                    @foreach ($order->orderProducts as $product)
                                        @php
                                            $variants = json_decode($product->variants, true);
                                            $variants = is_array($variants) ? $variants : [];
                                        @endphp
                                        <tr>
                                            <td>{{ ++$loop->index }}</td>
                                            @if (isset($product->product->slug))
                                                <td> <a target="_blank"
                                                        href="{{ route('api.v1.product-detail', $product->product->slug) }}">{{ $product->product_name }}</a>
                                                </td>
                                            @else
                                                <td>{{ $product->product_name }}</td>
                                            @endif
                                            {{-- @foreach ($variants as $key => $variant)
                            <td>{{$key}}: {{$variant->name}}</td>
                        @endforeach --}}
                                            @if (count($variants) > 0)
                                                @foreach ($variants as $key => $variant)
                                                    <td>{{ $key }}:
                                                        {{ $variant['name'] ?? 'N/A' }}({{ $settings->currency_icon }}{{ $variant['price'] }})
                                                        <br>
                                                        {{ $variant['size'] ?? '' }}
                                                    </td>
                                                @endforeach
                                            @else
                                                <td>No variants available</td>
                                            @endif
                                            {{-- <td class="">{{$product->vendor->shop_name}}</td> --}}
                                            <td class="text-center">
                                                {{ $settings->currency_icon }}{{ $product->unit_price }}</td>
                                            <td class="text-center">{{ $product->qty }}</td>
                                            <td class="text-right">
                                                {{ $settings->currency_icon }}{{ $product->unit_price * $product->qty + $product->variants_total * $product->qty }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </table>
                            </div>
                            <div class="row mt-4">
                                <div class="col-md-8">
                                    <div class="col-md-5">
                                        <div class="form-group">
                                            <label for="">Payment Status</label>
                                            <select name="product_name" id="payment_status" class="form-control"
                                                data-id="{{ $order->id }}">
                                                <option {{ $order->payment_status === 0 ? 'selected' : '' }}
                                                    value="0">
                                                    Pending</option>
                                                <option {{ $order->payment_status === 1 ? 'selected' : '' }}
                                                    value="1">
                                                    Complete</option>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label for="">Order Status</label>
                                            <select name="order_status_id" id="order_status" class="form-control"
                                                data-id="{{ $order->id }}">
                                                {{-- @foreach (config('order_status.order_status_admin') as $key => $orderStatus)

                            <option {{$order->order_status === $key? 'selected' : ''}} value="{{$key}}">{{$orderStatus['status']}}</option>
                            @endforeach --}}
                                            @foreach ($order_status as $status )
                                                <option value="{{$status->id}}" {{ $order->order_status_id === $status->id ? 'selected' : '' }}>{{$status->name}}</option>
                                            @endforeach
                                            </select>

                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-4 text-right">
                                    <div class="invoice-detail-item">
                                        <div class="invoice-detail-name">Subtotal</div>
                                        <div class="invoice-detail-value">
                                            {{ $settings->currency_icon }}{{ $order->sub_total }}</div>
                                    </div>
                                    <div class="invoice-detail-item">
                                        <div class="invoice-detail-name">Shipping</div>
                                        <div class="invoice-detail-value">
                                            {{ $settings->currency_icon }}{{ @$shipping->cost }}</div>
                                    </div>
                                    <div class="invoice-detail-item">
                                        <div class="invoice-detail-name">Coupon</div>
                                        <div class="invoice-detail-value">
                                            {{ $settings->currency_icon }}{{ @$coupon->discount ? $coupon->discount : 0 }}
                                        </div>
                                    </div>
                                    <hr class="mt-2 mb-2">
                                    <div class="invoice-detail-item">
                                        <div class="invoice-detail-name">Total</div>
                                        <div class="invoice-detail-value invoice-detail-value-lg">
                                            {{ $settings->currency_icon }}{{ $order->amount }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <hr>
                <div class="text-md-right">
                    <button class="btn btn-warning btn-icon icon-left btn_print"><i class="fas fa-print"></i> Print</button>
                </div>
            </div>
        </div>
    </section>
@endsection
@push('scripts')
    <script>
        $(document).ready(function() {
            // order status change
                $('#order_status').on('change', function(){
                    let order_status_id= $(this).val();
                    let id = $(this).data('id');
                    // console.log(order_status_id, id);
                    
                    $.ajax({
                        url:"{{route('admin.order-status')}}",
                        method:'get',
                        data:{
                            status:order_status_id,
                            id:id
                        },
                        success:function(data){
                            if(data.status==='success'){
                                toastr.success(data.message);
                            }
                        },
                        error:function(data){
                            console.log(data);
                        }
                })
            })
            //payment status change
            $('#payment_status').on('change', function() {
                let status = $(this).val();
                let id = $(this).data('id');
                $.ajax({
                    url: "{{ route('admin.payment-status') }}",
                    method: 'get',
                    data: {
                        status: status,
                        id: id
                    },
                    success: function(data) {
                        if (data.status === 'success') {
                            toastr.success(data.message);
                        }
                    },
                    error: function(data) {
                        console.log(data);
                    }
                })
            })

            //print
            $('.btn_print').on('click', function() {
                let printBody = $('.invoice-print');
                let originalBody = $('body').html();
                $('body').html(printBody.html());
                window.print();
                $('body').html(originalBody);
            })

        });
    </script>
@endpush
