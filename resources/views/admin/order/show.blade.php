@extends('admin.layouts.app')
@section('content')

<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Order Details: {{ $order->order_number }}</h1>
                </div>
                <div class="col-sm-6 text-end">
                    <a href="{{ route('admin.orders.list') }}" class="btn btn-secondary btn-sm">Back to List</a>
                </div>
            </div>
        </div>
    </section>

    <div class="container-fluid text-start">
        @include('admin.layouts._message')

        <div class="row">
            <!-- Left Column: Address and Items Details -->
            <div class="col-md-8">
                
                <!-- Billing and Shipping Addresses -->
                <div class="row">
                    <!-- Billing Address -->
                    <div class="col-md-6 mb-3">
                        <div class="card h-100 card-outline card-info">
                            <div class="card-header">
                                <h3 class="card-title fw-bold">Billing Address</h3>
                            </div>
                            <div class="card-body">
                                <address class="mb-0" style="font-style: normal; line-height: 1.6;">
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
                                    <i class="bi bi-telephone"></i> Phone: {{ $order->billing_phone }}<br>
                                    <i class="bi bi-envelope"></i> Email: {{ $order->billing_email }}
                                </address>
                            </div>
                        </div>
                    </div>

                    <!-- Shipping Address -->
                    <div class="col-md-6 mb-3">
                        <div class="card h-100 card-outline card-info">
                            <div class="card-header">
                                <h3 class="card-title fw-bold">Shipping Address</h3>
                            </div>
                            <div class="card-body">
                                <address class="mb-0" style="font-style: normal; line-height: 1.6;">
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
                                        <i class="bi bi-telephone"></i> Phone: {{ $order->shipping_phone }}
                                    @endif
                                </address>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Order Items Card -->
                <div class="card mb-4 card-outline card-primary">
                    <div class="card-header">
                        <h3 class="card-title fw-bold">Items Purchased</h3>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-striped mb-0">
                                <thead>
                                    <tr>
                                        <th>Product</th>
                                        <th>Price</th>
                                        <th>Qty</th>
                                        <th class="text-end">Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($order->items as $item)
                                    <tr>
                                        <td>
                                            <a href="{{ $item->product ? url($item->product->slug) : '#' }}" target="_blank">
                                                {{ $item->product_name }}
                                            </a>
                                            @if($item->size)
                                                <div class="small text-muted">Size: {{ $item->size->name }}</div>
                                            @endif
                                            @if($item->color)
                                                <div class="small text-muted">Color: {{ $item->color->name }}</div>
                                            @endif
                                        </td>
                                        <td>${{ number_format($item->price, 2) }}</td>
                                        <td>{{ $item->quantity }}</td>
                                        <td class="text-end">${{ number_format($item->total, 2) }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

            </div>

            <!-- Right Column: Status Update & Summary -->
            <div class="col-md-4">
                
                <!-- Order Status Update Card -->
                <div class="card mb-3 card-outline card-warning">
                    <div class="card-header">
                        <h3 class="card-title fw-bold">Manage Status</h3>
                    </div>
                    <form action="{{ route('admin.orders.update', $order->id) }}" method="POST">
                        {{ csrf_field() }}
                        <div class="card-body">
                            <!-- Order Status -->
                            <div class="mb-3">
                                <label class="form-label fw-bold">Order Status</label>
                                <select name="status" class="form-select">
                                    <option value="pending" {{ $order->status === 'pending' ? 'selected' : '' }}>Pending Payment</option>
                                    <option value="processing" {{ $order->status === 'processing' ? 'selected' : '' }}>Processing</option>
                                    <option value="on-hold" {{ $order->status === 'on-hold' ? 'selected' : '' }}>On Hold</option>
                                    <option value="completed" {{ $order->status === 'completed' ? 'selected' : '' }}>Completed</option>
                                    <option value="cancelled" {{ $order->status === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                    <option value="refunded" {{ $order->status === 'refunded' ? 'selected' : '' }}>Refunded</option>
                                    <option value="failed" {{ $order->status === 'failed' ? 'selected' : '' }}>Failed</option>
                                </select>
                            </div>

                            <!-- Payment Status -->
                            <div class="mb-3">
                                <label class="form-label fw-bold">Payment Status</label>
                                <select name="payment_status" class="form-select">
                                    <option value="pending" {{ $order->payment_status === 'pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="paid" {{ $order->payment_status === 'paid' ? 'selected' : '' }}>Paid</option>
                                    <option value="failed" {{ $order->payment_status === 'failed' ? 'selected' : '' }}>Failed</option>
                                    <option value="refunded" {{ $order->payment_status === 'refunded' ? 'selected' : '' }}>Refunded</option>
                                </select>
                            </div>
                        </div>
                        <div class="card-footer">
                            <button type="submit" class="btn btn-warning w-100">Update Statuses</button>
                        </div>
                    </form>
                </div>

                <!-- Order Totals Card -->
                <div class="card mb-3 card-outline card-success">
                    <div class="card-header">
                        <h3 class="card-title fw-bold">Order Summary</h3>
                    </div>
                    <div class="card-body">
                        <ul class="list-group list-group-unbordered mb-0">
                            <li class="list-group-item d-flex justify-content-between">
                                <span>Subtotal</span>
                                <strong>${{ number_format($order->subtotal, 2) }}</strong>
                            </li>
                            @if($order->discount > 0)
                            <li class="list-group-item d-flex justify-content-between">
                                <span>Discount</span>
                                <strong class="text-danger">-${{ number_format($order->discount, 2) }}</strong>
                            </li>
                            @endif
                            <li class="list-group-item d-flex justify-content-between">
                                <span>Shipping Charge</span>
                                <strong>${{ number_format($order->shipping_charge, 2) }}</strong>
                            </li>
                            <li class="list-group-item d-flex justify-content-between">
                                <span class="fs-5">Grand Total</span>
                                <strong class="fs-5 text-success">${{ number_format($order->total, 2) }}</strong>
                            </li>
                        </ul>
                    </div>
                </div>

                <!-- Order Notes Card -->
                @if($order->order_notes)
                <div class="card card-outline card-secondary">
                    <div class="card-header">
                        <h3 class="card-title fw-bold">Order Notes</h3>
                    </div>
                    <div class="card-body">
                        <p class="mb-0 text-muted">{{ $order->order_notes }}</p>
                    </div>
                </div>
                @endif

            </div>
        </div>
    </div>
</div>

@endsection
