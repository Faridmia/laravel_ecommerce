@extends('layouts.app')

@section('content')
<main class="main">
    <div class="page-header text-center" style="background-image: url('{{ asset('assets/images/page-header-bg.jpg') }}')">
        <div class="container">
            <h1 class="page-title">Order Details<span>Invoice</span></h1>
        </div><!-- End .container -->
    </div><!-- End .page-header -->

    <nav aria-label="breadcrumb" class="breadcrumb-nav">
        <div class="container">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('user.dashboard') }}">My Account</a></li>
                <li class="breadcrumb-item active" aria-current="page">Order #{{ $order->order_number }}</li>
            </ol>
        </div><!-- End .container -->
    </nav><!-- End .breadcrumb-nav -->

    <div class="page-content text-start">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-10">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h2 class="title mb-0">Order: {{ $order->order_number }}</h2>
                        <a href="{{ route('user.dashboard') }}#tab-orders" class="btn btn-outline-primary-2 btn-sm">Back to Dashboard</a>
                    </div>

                    <!-- WooCommerce Style Order Metadata Bar -->
                    <div class="order-meta-info-bar p-4 mb-4" style="background: #fafafa; border: 1px dashed #d7d7d7; border-radius: 5px;">
                        <div class="row text-center">
                            <div class="col-md-3 mb-2">
                                <span class="text-uppercase text-muted" style="font-size: 1.1rem; display:block;">Order Status:</span>
                                <strong>
                                    @if($order->status == 'pending')
                                        <span class="text-warning">Pending Payment</span>
                                    @elseif($order->status == 'processing')
                                        <span class="text-primary">Processing</span>
                                    @elseif($order->status == 'on-hold')
                                        <span class="text-secondary">On Hold</span>
                                    @elseif($order->status == 'completed')
                                        <span class="text-success">Completed</span>
                                    @elseif($order->status == 'cancelled')
                                        <span class="text-danger">Cancelled</span>
                                    @elseif($order->status == 'refunded')
                                        <span class="text-dark">Refunded</span>
                                    @elseif($order->status == 'failed')
                                        <span class="text-danger">Failed</span>
                                    @endif
                                </strong>
                            </div>
                            <div class="col-md-3 mb-2">
                                <span class="text-uppercase text-muted" style="font-size: 1.1rem; display:block;">Date:</span>
                                <strong>{{ $order->created_at->format('M d, Y') }}</strong>
                            </div>
                            <div class="col-md-3 mb-2">
                                <span class="text-uppercase text-muted" style="font-size: 1.1rem; display:block;">Total:</span>
                                <strong>${{ number_format($order->total, 2) }}</strong>
                            </div>
                            <div class="col-md-3 mb-2">
                                <span class="text-uppercase text-muted" style="font-size: 1.1rem; display:block;">Payment Method:</span>
                                <strong>{{ strtoupper($order->payment_method === 'cod' ? 'Cash on Delivery' : $order->payment_method) }} ({{ strtoupper($order->payment_status) }})</strong>
                            </div>
                        </div>
                    </div>

                    <!-- Order Items Table -->
                    <h3 class="summary-title text-start mb-2">Order Items</h3>
                    <table class="table table-summary mb-5">
                        <thead>
                            <tr>
                                <th class="text-start">Product</th>
                                <th class="text-end">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($order->items as $item)
                            <tr>
                                <td class="text-start">
                                    <a href="{{ $item->product ? url($item->product->slug) : '#' }}" target="_blank">{{ $item->product_name }}</a>
                                    <strong> x {{ $item->quantity }}</strong>
                                    @if($item->size)
                                        <br><small class="text-muted">Size: {{ $item->size->name }}</small>
                                    @endif
                                    @if($item->color)
                                        <br><small class="text-muted">Color: {{ $item->color->name }}</small>
                                    @endif
                                </td>
                                <td class="text-end">${{ number_format($item->total, 2) }}</td>
                            </tr>
                            @endforeach
                            
                            <tr class="summary-subtotal">
                                <td class="text-start">Subtotal:</td>
                                <td class="text-end">${{ number_format($order->subtotal, 2) }}</td>
                            </tr>
                            @if($order->discount > 0)
                            <tr>
                                <td class="text-start">Discount:</td>
                                <td class="text-end">-${{ number_format($order->discount, 2) }}</td>
                            </tr>
                            @endif
                            <tr>
                                <td class="text-start">Shipping Charge:</td>
                                <td class="text-end">${{ number_format($order->shipping_charge, 2) }}</td>
                            </tr>
                            <tr class="summary-total">
                                <td class="text-start" style="font-size: 1.8rem;">Total:</td>
                                <td class="text-end" style="font-size: 1.8rem; color: #c96;">${{ number_format($order->total, 2) }}</td>
                            </tr>
                        </tbody>
                    </table>

                    <!-- Customer Addresses Info -->
                    <div class="row text-start mt-5">
                        <!-- Billing Address -->
                        <div class="col-md-6 mb-4">
                            <h4 class="fw-bold mb-3" style="border-bottom: 2px solid #c96; padding-bottom: 5px;">Billing Address</h4>
                            <address style="font-style: normal; line-height: 1.8;">
                                <strong>{{ $order->billing_first_name }} {{ $order->billing_last_name }}</strong><br>
                                @if($order->billing_company)
                                    {{ $order->billing_company }}<br>
                                @endif
                                {{ $order->billing_address_1 }}<br>
                                @if($order->billing_address_2)
                                    {{ $order->billing_address_2 }}<br>
                                @endif
                                
                                @if($order->billingCountry && $order->billingCountry->code === 'BD')
                                    {{ $order->billingArea ? $order->billingArea->name . ', ' : '' }}
                                    {{ $order->billingDistrict ? $order->billingDistrict->name . ', ' : '' }}
                                    {{ $order->billingDivision ? $order->billingDivision->name : '' }}<br>
                                @else
                                    {{ $order->billing_city }}, {{ $order->billing_state }}<br>
                                @endif
                                
                                {{ $order->billingCountry ? $order->billingCountry->name : '' }} - {{ $order->billing_postcode }}<br>
                                <i class="icon-phone"></i> {{ $order->billing_phone }}<br>
                                <i class="icon-envelope"></i> {{ $order->billing_email }}
                            </address>
                        </div>

                        <!-- Shipping Address -->
                        <div class="col-md-6 mb-4">
                            <h4 class="fw-bold mb-3" style="border-bottom: 2px solid #c96; padding-bottom: 5px;">Shipping Address</h4>
                            <address style="font-style: normal; line-height: 1.8;">
                                <strong>{{ $order->shipping_first_name }} {{ $order->shipping_last_name }}</strong><br>
                                @if($order->shipping_company)
                                    {{ $order->shipping_company }}<br>
                                @endif
                                {{ $order->shipping_address_1 }}<br>
                                @if($order->shipping_address_2)
                                    {{ $order->shipping_address_2 }}<br>
                                @endif
                                
                                @if($order->shippingCountry && $order->shippingCountry->code === 'BD')
                                    {{ $order->shippingArea ? $order->shippingArea->name . ', ' : '' }}
                                    {{ $order->shippingDistrict ? $order->shippingDistrict->name . ', ' : '' }}
                                    {{ $order->shippingDivision ? $order->shippingDivision->name : '' }}<br>
                                @else
                                    {{ $order->shipping_city }}, {{ $order->shipping_state }}<br>
                                @endif
                                
                                {{ $order->shippingCountry ? $order->shippingCountry->name : '' }} - {{ $order->shipping_postcode }}<br>
                                @if($order->shipping_phone)
                                    <i class="icon-phone"></i> {{ $order->shipping_phone }}
                                @endif
                            </address>
                        </div>
                    </div>

                    @if($order->order_notes)
                    <div class="text-start mt-4 mb-4" style="background:#f9f9f9; padding: 15px; border-left: 3px solid #c96;">
                        <strong>Order Notes:</strong>
                        <p class="mb-0 text-muted">{{ $order->order_notes }}</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</main>
@endsection
