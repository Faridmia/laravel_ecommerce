@extends('layouts.app')

@section('content')
<main class="main">
    <div class="page-header text-center" style="background-image: url('{{ asset('assets/images/page-header-bg.jpg') }}')">
        <div class="container">
            <h1 class="page-title">Order Confirmation<span>Thank You</span></h1>
        </div><!-- End .container -->
    </div><!-- End .page-header -->

    <nav aria-label="breadcrumb" class="breadcrumb-nav">
        <div class="container">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('shop') }}">Shop</a></li>
                <li class="breadcrumb-item active" aria-current="page">Order Complete</li>
            </ol>
        </div><!-- End .container -->
    </nav><!-- End .breadcrumb-nav -->

    <div class="page-content">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-10">
                    <div class="order-success-container text-center mb-5 mt-3">
                        <div class="success-iconmb-3" style="font-size: 5rem; color: #c96;">
                            <i class="icon-check-circle"></i>
                        </div>
                        <h2 class="title text-success mb-2">Your Order Has Been Placed!</h2>
                        <p class="lead">Thank you for your purchase. We have received your order and are processing it.</p>
                    </div>

                    <!-- WooCommerce Style Order Metadata Bar -->
                    <div class="order-meta-info-bar p-4 mb-5" style="background: #fafafa; border: 1px dashed #d7d7d7; border-radius: 5px;">
                        <div class="row text-center">
                            <div class="col-md-3 mb-2">
                                <span class="text-uppercase text-muted" style="font-size: 1.1rem; display:block;">Order Number:</span>
                                <strong>{{ $order->order_number }}</strong>
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
                                <strong>{{ strtoupper($order->payment_method === 'cod' ? 'Cash on Delivery' : $order->payment_method) }}</strong>
                            </div>
                        </div>
                    </div>

                    <!-- Order Details Table -->
                    <h3 class="summary-title text-start mb-2">Order Details</h3>
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
                                    <a href="{{ $item->product ? url($item->product->slug) : '#' }}">{{ $item->product_name }}</a>
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
                                <td class="text-start">Shipping:</td>
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

                    <div class="text-center mt-5 mb-5">
                        <a href="{{ route('shop') }}" class="btn btn-outline-primary-2 btn-minwidth-lg">
                            <span>Continue Shopping</span>
                            <i class="icon-long-arrow-right"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
@endsection
