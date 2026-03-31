@php
    // Ensure order_address and shipping_method are objects
    $address = is_string($order->order_address) ? json_decode($order->order_address) : $order->order_address;
    $shipping = is_string($order->shipping_method) ? json_decode($order->shipping_method) : $order->shipping_method;
    $coupon = is_string($order->coupon) ? json_decode($order->coupon) : $order->coupon;
@endphp

@extends('backend.layouts.master')
@section('content')
    <section class="section">
        <div class="section-header justify-content-between">
            <h1>Orders Details</h1>
            <div class="section-header-action justify-content-end">
                <a href="{{ route('admin.order.index') }}" class="btn btn-primary">Back</a>
            </div>
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
                                        <b>Name:</b> {{ $address->name ?? 'N/A' }}<br>
                                        <b>Email:</b> {{ $address->email ?? 'N/A' }}<br>
                                        <b>Phone:</b> {{ $address->phone ?? 'N/A' }}<br>
                                        <b>Address:</b> {{ $address->address ?? '' }}, {{ $address->city ?? '' }},
                                        {{ $address->state ?? '' }}, {{ $address->zip ?? '' }}<br>
                                        <b>Country:</b> {{ $address->country ?? '' }}
                                    </address>
                                </div>
                                <div class="col-md-6 text-md-right">
                                    <address>
                                        <strong>Payment Information:</strong><br>
                                        <b>Method:</b> {{ $order->payment_method }}<br>
                                        <b>Transaction ID:</b> {{ $order->transaction->transaction_id ?? 'N/A' }}<br>
                                        <b>Status:</b> {{ $order->payment_status === 1 ? 'Complete' : 'Pending' }}
                                    </address>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Order Products Table --}}
                    <div class="row mt-4">
                        <div class="col-md-12">
                            <div class="section-title">Order Summary</div>
                            <div class="table-responsive">
                                <table class="table table-striped table-hover table-md">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Item</th>
                                            <th>Variant</th>
                                            <th>Extra Custom Price</th>
                                            <th class="text-center">Price</th>
                                            <th class="text-center">Quantity</th>
                                            <th class="text-right">Total</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($order->orderProducts as $index => $product)
                                            {{-- @php
                                            $variants = json_decode($product->variants, true) ?: [];
                                            
                                            $variantTotal = $product->variants_total ?? 0;
                                            $extraPrice = $product->extra_price ?? 0;
                                            $totalPrice = ($product->unit_price + $variantTotal + $extraPrice) * $product->qty;
                                            // dd($variantTotal, $extraPrice, $totalPrice);

                                            @endphp --}}
                                            @php
                                                $variants = json_decode($product->variants, true);
                                                dd($variants);
                                                $variants = is_array($variants) ? $variants : [];

                                                // শুধু color আর size filter করা
                                                $filteredVariants = collect($variants)->filter(function ($value, $key) {
                                                    return in_array(strtolower($key), [
                                                        'color_name',
                                                        'size_name',
                                                        'color_price',
                                                        'size_price',
                                                    ]);
                                                });
                                                // dd($filteredVariants);

                                                $extraPrice = $product->extra_price ?? 0;
                                                $variantTotal = $product->variants_total ?? 0;

                                                $totalPrice =
                                                    ($product->unit_price + $variantTotal + $extraPrice) *
                                                    $product->qty;
                                            @endphp
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>
                                                    @if (isset($product->product->slug))
                                                        <a target="_blank"
                                                            href="{{ route('api.v1.product-detail', $product->product->slug) }}">
                                                            {{ $product->product_name }}
                                                        </a>
                                                    @else
                                                        {{ $product->product_name }}
                                                    @endif
                                                </td>
                                                <td>
                                                    {{-- @if (count($variants) > 0)
                                                        @foreach ($variants as $key => $variant)
                                                            {{ $key }}: {{ $variant['name'] ?? 'N/A' }}
                                                            ({{ $settings->currency_icon }}{{ $variant['price'] ?? 0 }})<br>
                                                            {{ $variant['size'] ?? '' }}
                                                        @endforeach
                                                    @else
                                                        No variants
                                                    @endif --}}
                                                    @if ($filteredVariants->count() > 0)
                                                
                                                    @foreach ($filteredVariants as $key => $variant)
                                                        {{ ucfirst($key) }}:
                                                        {{ $variant['c'] ?? 'N/A' }}
                                                        ({{ $settings->currency_icon }}{{ $variant['price'] ?? 0 }})
                                                        <br>
                                                    @endforeach
                                                
                                            @else
                                                <td>No variants available</td>
                                        @endif
                                        </td>
                                        <td>{{ $product->extra_price }}</td>
                                        <td class="text-center">
                                            {{ $settings->currency_icon }}{{ $product->unit_price }}</td>
                                        <td class="text-center">{{ $product->qty }}</td>
                                        <td class="text-right">{{ $settings->currency_icon }}{{ $totalPrice }}
                                        </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            {{-- Invoice Summary --}}
                            <div class="row mt-4">
                                <div class="col-md-8">
                                    <div class="form-group">
                                        <label>Payment Status</label>
                                        <select name="payment_status" id="payment_status" class="form-control"
                                            data-id="{{ $order->id }}">
                                            <option value="0" {{ $order->payment_status === 0 ? 'selected' : '' }}>
                                                Pending</option>
                                            <option value="1" {{ $order->payment_status === 1 ? 'selected' : '' }}>
                                                Complete</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label>Order Status</label>
                                        <select name="order_status_id" id="order_status" class="form-control"
                                            data-id="{{ $order->id }}">
                                            @foreach ($order_status as $status)
                                                <option value="{{ $status->id }}"
                                                    {{ $order->order_status_id === $status->id ? 'selected' : '' }}>
                                                    {{ $status->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4 text-right">
                                    <div class="invoice-detail-item">
                                        <div class="invoice-detail-name">Subtotal</div>
                                        <div class="invoice-detail-value">
                                            {{ $settings->currency_icon }}{{ $order->sub_total }}</div>
                                    </div>
                                    <div class="invoice-detail-item">
                                        <div class="invoice-detail-name">Shipping</div>
                                        <div class="invoice-detail-value">
                                            {{ $settings->currency_icon }}{{ $shipping->cost ?? 0 }}</div>
                                    </div>
                                    <div class="invoice-detail-item">
                                        <div class="invoice-detail-name">Coupon</div>
                                        <div class="invoice-detail-value">
                                            {{ $settings->currency_icon }}{{ $coupon->discount ?? 0 }}</div>
                                    </div>
                                    <hr>
                                    <div class="invoice-detail-item">
                                        <div class="invoice-detail-name">Total</div>
                                        <div class="invoice-detail-value invoice-detail-value-lg">
                                            {{ $settings->currency_icon }}{{ $order->amount }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <hr>
                    <div class="text-md-right">
                        <button class="btn btn-warning btn-icon icon-left btn_print"><i class="fas fa-print"></i>
                            Print</button>
                    </div>
                </div>
            </div>
    </section>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            // Order status change
            $('#order_status').on('change', function() {
                let id = $(this).data('id');
                let status = $(this).val();
                $.get("{{ route('admin.order-status') }}", {
                    id: id,
                    status: status
                }, function(data) {
                    if (data.status === 'success') toastr.success(data.message);
                });
            });

            // Payment status change
            $('#payment_status').on('change', function() {
                let id = $(this).data('id');
                let status = $(this).val();
                $.get("{{ route('admin.payment-status') }}", {
                    id: id,
                    status: status
                }, function(data) {
                    if (data.status === 'success') toastr.success(data.message);
                });
            });

            // Print
            $('.btn_print').on('click', function() {
                let printBody = $('.invoice-print').html();
                let originalBody = $('body').html();
                $('body').html(printBody);
                window.print();
                $('body').html(originalBody);
            });
        });
    </script>
@endpush
