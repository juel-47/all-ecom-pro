@php
    // Decode JSON fields safely
    $address = is_string($order->order_address) ? json_decode($order->order_address) : $order->order_address;
    $shipping = is_string($order->shipping_method) ? json_decode($order->shipping_method) : $order->shipping_method;
    $coupon = is_string($order->coupon) ? json_decode($order->coupon) : $order->coupon;
@endphp

@extends('backend.layouts.master')
@section('title', $settings->site_name . ' | Order Details')
@section('content')
    <section class="section">
        <div class="section-header justify-content-between">
            <h1>Order Details</h1>
            <div class="section-header-action justify-content-end">
                <a href="{{ route('admin.order.index') }}" class="btn btn-primary">Back</a>
            </div>
        </div>

        <div class="section-body">
            <div class="invoice">
                <div class="invoice-print">
                    {{-- ===================== INVOICE HEADER ===================== --}}
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
                                        <b>Address:</b>
                                        {{ $address->address ?? '' }},
                                        {{ $address->city ?? '' }},
                                        {{ $address->state ?? '' }},
                                        {{ $address->zip ?? '' }}<br>
                                        <b>Country:</b> {{ $address->country ?? '' }}
                                    </address>
                                </div>
                                <div class="col-md-6 text-md-right">
                                    <address>
                                        <strong>Payment Information:</strong><br>
                                        <b>Method:</b> {{ ucfirst($order->payment_method) }}<br>
                                        <b>Transaction ID:</b> {{ $order->transaction->transaction_id ?? 'N/A' }}<br>
                                        <b>Status:</b>
                                        <span class="badge badge-{{ $order->payment_status ? 'success' : 'warning' }}">
                                            {{ $order->payment_status ? 'Complete' : 'Pending' }}
                                        </span>
                                    </address>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- ===================== ORDER PRODUCT TABLE ===================== --}}
                    <div class="row mt-4">
                        <div class="col-md-12">
                            <div class="section-title">Order Summary</div>
                            <div class="table-responsive">
                                <table id="invoice-table" class="table table-striped table-hover table-md">
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
                                            @php
                                                $variants = json_decode($product->variants, true);
                                                $variants = is_array($variants) ? $variants : [];
                                                $colorName = $variants['color_name'] ?? null;
                                                $colorPrice = $variants['color_price'] ?? 0;
                                                $sizeName = $variants['size_name'] ?? null;
                                                $sizePrice = $variants['size_price'] ?? 0;
                                                $frontImage = $variants['font_image'] ?? null;
                                                $backImage = $variants['back_image'] ?? null;
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
                                                    @if ($colorName || $sizeName)
                                                        @if ($colorName)
                                                            <b>Color:</b> {{ ucfirst($colorName) }}
                                                            @if ($colorPrice > 0)
                                                                (+{{ $settings->currency_icon }}{{ number_format($colorPrice, 2) }})
                                                            @endif
                                                            <br>
                                                        @endif
                                                        @if ($sizeName)
                                                            <b>Size:</b> {{ strtoupper($sizeName) }}
                                                            @if ($sizePrice > 0)
                                                                (+{{ $settings->currency_icon }}{{ number_format($sizePrice, 2) }})
                                                            @endif
                                                        @endif
                                                    @else
                                                        N/A
                                                    @endif
                                                </td>
                                                <td>{{ $settings->currency_icon }}{{ number_format($extraPrice, 2) }}</td>
                                                <td class="text-center">
                                                    {{ $settings->currency_icon }}{{ number_format($product->unit_price, 2) }}
                                                </td>
                                                <td class="text-center">{{ $product->qty }}</td>
                                                <td class="text-right">
                                                    {{ $settings->currency_icon }}{{ number_format($totalPrice, 2) }}</td>
                                            </tr>

                                            @if ($frontImage || $backImage)
                                                <tr>
                                                    <td colspan="7">
                                                        <strong>Customization Preview:</strong><br>
                                                        <div
                                                            style="display:flex; gap:25px; margin-top:10px; flex-wrap: wrap;">
                                                            @if ($frontImage)
                                                                <div>
                                                                    <p>Front Design:</p>
                                                                    <img src="{{ asset($frontImage) }}" alt="Front Image"
                                                                        width="150"
                                                                        style="border:1px solid #ddd; border-radius:8px; padding:4px;">
                                                                    <br>
                                                                    <a href="{{ asset($frontImage) }}" download
                                                                        class="btn btn-sm btn-outline-primary mt-2">
                                                                        <i class="fas fa-download"></i> Download Front
                                                                    </a>
                                                                </div>
                                                            @endif
                                                            @if ($backImage)
                                                                <div>
                                                                    <p>Back Design:</p>
                                                                    <img src="{{ asset($backImage) }}" alt="Back Image"
                                                                        width="150"
                                                                        style="border:1px solid #ddd; border-radius:8px; padding:4px;">
                                                                    <br>
                                                                    <a href="{{ asset($backImage) }}" download
                                                                        class="btn btn-sm btn-outline-primary mt-2">
                                                                        <i class="fas fa-download"></i> Download Back
                                                                    </a>
                                                                </div>
                                                            @endif
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endif
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            {{-- ===================== ORDER TOTAL SUMMARY ===================== --}}
                            <div class="row mt-4">
                                <div class="col-md-8">
                                    <div class="form-group">
                                        <label>Payment Status</label>
                                        <select name="payment_status" id="payment_status" class="form-control"
                                            data-id="{{ $order->id }}">
                                            <option value="" {{ $order->payment_status ? '' : 'selected' }}>
                                                
                                            </option>
                                            <option value="0" {{ $order->payment_status == 0 ? 'selected' : '' }}>
                                                Pending</option>
                                            <option value="1" {{ $order->payment_status == 1 ? 'selected' : '' }}>
                                                Complete</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label>Order Status</label>
                                        <select name="order_status_id" id="order_status" class="form-control"
                                            data-id="{{ $order->id }}">
                                            <option value="" {{ $order->order_status_id ? '' : 'selected' }}>
                                                
                                            </option>
                                            @foreach ($order_status as $status)
                                                <option value="{{ $status->id }}"
                                                    {{ $order->order_status_id == $status->id ? 'selected' : '' }}>
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
                                            {{ $settings->currency_icon }}{{ number_format($order->sub_total, 2) }}</div>
                                    </div>
                                    <div class="invoice-detail-item">
                                        <div class="invoice-detail-name">Shipping</div>
                                        <div class="invoice-detail-value">
                                            {{ $settings->currency_icon }}{{ number_format($shipping->cost ?? 0, 2) }}
                                        </div>
                                    </div>
                                    <div class="invoice-detail-item">
                                        <div class="invoice-detail-name">Coupon</div>
                                        <div class="invoice-detail-value">
                                            {{ $settings->currency_icon }}{{ number_format($coupon->discount ?? 0, 2) }}
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="invoice-detail-item">
                                        <div class="invoice-detail-name"><b>Total</b></div>
                                        <div class="invoice-detail-value invoice-detail-value-lg">
                                            {{ $settings->currency_icon }}{{ number_format($order->amount, 2) }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- ===================== PRINT BUTTON ===================== --}}
                    {{-- <hr>
                <div class="text-md-right">
                    <button class="btn btn-warning btn-icon icon-left" onclick="printInvoice()">
                        <i class="fas fa-print"></i> Print
                    </button>
                </div> --}}
                </div>
            </div>
        </div>
    </section>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            // Order Status Update
            $('#order_status').on('change', function() {
                let id = $(this).data('id');
                let status = $(this).val();
                $.get("{{ route('admin.order-status') }}", {
                    id,
                    status
                }, function(data) {
                    if (data.status === 'success') toastr.success(data.message);
                });
            });

            // Payment Status Update
            $('#payment_status').on('change', function() {
                let id = $(this).data('id');
                let status = $(this).val();
                $.get("{{ route('admin.payment-status') }}", {
                    id,
                    status
                }, function(data) {
                    if (data.status === 'success') toastr.success(data.message);
                });
            });
        });

        function printInvoice() {
            let printContents = document.querySelector('.invoice-print').innerHTML;
            let originalContents = document.body.innerHTML;

            document.body.innerHTML = printContents;
            window.print();
            document.body.innerHTML = originalContents;

            // Reload JS events for order/payment status after print
            location.reload();
        }
    </script>
@endpush

@push('styles')
    <style>
        .invoice img {
            transition: 0.3s ease;
        }

        .invoice img:hover {
            transform: scale(1.05);
        }

        @media print {
            body * {
                visibility: hidden;
            }

            .invoice-print,
            .invoice-print * {
                visibility: visible;
            }

            .invoice-print {
                position: absolute;
                left: 0;
                top: 0;
                width: 100%;
                padding: 0;
                margin: 0;
            }

            /* Hide action buttons */
            .btn-icon,
            .section-header-action {
                display: none !important;
            }

            select,
            input {
                border: none !important;
                box-shadow: none !important;
                background: transparent !important;
                -webkit-appearance: none;
                -moz-appearance: none;
                appearance: none;
            }

            table {
                border-collapse: collapse !important;
            }

            table th,
            table td {
                border: 1px solid #333 !important;
                padding: 5px !important;
            }
        }
    </style>
@endpush
