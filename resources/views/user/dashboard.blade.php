@extends('layouts.app')

@section('style')
<style>
    .dashboard .card-dashboard {
        border: 0.1rem dashed #d7d7d7;
        background-color: #f9f9f9;
        padding: 2rem;
        margin-bottom: 2rem;
        border-radius: 5px;
    }
    .dashboard .card-title {
        color: #333;
        font-weight: 600;
        margin-bottom: 1.5rem;
    }
    .nav-dashboard .nav-link {
        font-weight: 500;
        padding: 1.5rem 0;
        border-bottom: 0.1rem solid #ebebeb;
        color: #666;
        transition: all 0.3s;
    }
    .nav-dashboard .nav-link.active, .nav-dashboard .nav-link:hover {
        color: #c96;
        border-bottom-color: #c96;
    }
</style>
@endsection

@section('content')
<main class="main">
    <div class="page-header text-center" style="background-image: url('{{ asset('assets/images/page-header-bg.jpg') }}')">
        <div class="container">
            <h1 class="page-title">My Account<span>Dashboard</span></h1>
        </div><!-- End .container -->
    </div><!-- End .page-header -->

    <nav aria-label="breadcrumb" class="breadcrumb-nav">
        <div class="container">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">My Account</li>
            </ol>
        </div><!-- End .container -->
    </nav><!-- End .breadcrumb-nav -->

    <div class="page-content text-start">
        <div class="dashboard">
            <div class="container">
                <div class="row">
                    <!-- Dashboard Sidebar Menu -->
                    <aside class="col-md-4 col-lg-3">
                        <ul class="nav nav-dashboard flex-column mb-3 mb-md-0" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" id="tab-dashboard-link" data-toggle="tab" href="#tab-dashboard" role="tab" aria-controls="tab-dashboard" aria-selected="true">Dashboard</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="tab-orders-link" data-toggle="tab" href="#tab-orders" role="tab" aria-controls="tab-orders" aria-selected="false">Orders</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="tab-downloads-link" data-toggle="tab" href="#tab-downloads" role="tab" aria-controls="tab-downloads" aria-selected="false">Downloads</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="tab-address-link" data-toggle="tab" href="#tab-address" role="tab" aria-controls="tab-address" aria-selected="false">Addresses</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="tab-wishlist-link" data-toggle="tab" href="#tab-wishlist" role="tab" aria-controls="tab-wishlist" aria-selected="false">Wishlist</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="tab-coupons-link" data-toggle="tab" href="#tab-coupons" role="tab" aria-controls="tab-coupons" aria-selected="false">Coupons</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="tab-reviews-link" data-toggle="tab" href="#tab-reviews" role="tab" aria-controls="tab-reviews" aria-selected="false">Reviews</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="tab-account-link" data-toggle="tab" href="#tab-account" role="tab" aria-controls="tab-account" aria-selected="false">Account Details</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('user.logout') }}">Sign Out</a>
                            </li>
                        </ul>
                    </aside>

                    <!-- Dashboard Tab Contents -->
                    <div class="col-md-8 col-lg-9">
                        @if(session('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                {{ session('success') }}
                            </div>
                        @endif

                        @if($errors->any())
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <ul class="mb-0" style="padding-left: 15px;">
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <div class="tab-content" style="border: none; padding: 0;">
                            
                            <!-- 1. Dashboard Tab -->
                            <div class="tab-pane fade show active" id="tab-dashboard" role="tabpanel" aria-labelledby="tab-dashboard-link">
                                <p>Hello <span class="font-weight-normal text-dark">{{ auth()->user()->name }}</span> (not <span class="font-weight-normal text-dark">{{ auth()->user()->name }}</span>? <a href="{{ route('user.logout') }}" class="text-primary font-weight-bold">Log out</a>)</p>
                                <p>From your account dashboard you can view your <a href="#tab-orders" class="tab-trigger-link link-underline">recent orders</a>, manage your <a href="#tab-address" class="tab-trigger-link link-underline">billing and shipping addresses</a>, and <a href="#tab-account" class="tab-trigger-link link-underline">edit your password and account details</a>.</p>
                                
                                <div class="row mt-4">
                                    <div class="col-sm-6 col-lg-4">
                                        <div class="card card-dashboard p-3 text-center">
                                            <h5 class="card-title mb-1">Total Orders</h5>
                                            <span class="fs-4 fw-bold text-primary">{{ $orders->total() }}</span>
                                        </div>
                                    </div>
                                    @if($latestOrder)
                                    <div class="col-sm-6 col-lg-4">
                                        <div class="card card-dashboard p-3 text-center">
                                            <h5 class="card-title mb-1">Latest Order</h5>
                                            <span class="fs-5 fw-bold text-dark">{{ $latestOrder->order_number }}</span>
                                            <span class="badge text-bg-info mt-1" style="display:inline-block; max-width:fit-content; margin:auto;">{{ strtoupper($latestOrder->status) }}</span>
                                        </div>
                                    </div>
                                    @endif
                                </div>
                            </div>

                            <!-- 2. Orders Tab -->
                            <div class="tab-pane fade" id="tab-orders" role="tabpanel" aria-labelledby="tab-orders-link">
                                @if($orders->count() > 0)
                                    <div class="table-responsive">
                                        <table class="table table-wishlist table-mobile" style="font-size: 1.3rem;">
                                            <thead>
                                                <tr>
                                                    <th>Order #</th>
                                                    <th>Date</th>
                                                    <th>Status</th>
                                                    <th>Total</th>
                                                    <th>Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($orders as $order)
                                                <tr>
                                                    <td><strong>{{ $order->order_number }}</strong></td>
                                                    <td>{{ $order->created_at->format('M d, Y') }}</td>
                                                    <td>
                                                        @if($order->status == 'pending')
                                                            <span class="badge text-bg-warning">Pending Payment</span>
                                                        @elseif($order->status == 'processing')
                                                            <span class="badge text-bg-primary">Processing</span>
                                                        @elseif($order->status == 'on-hold')
                                                            <span class="badge text-bg-secondary">On Hold</span>
                                                        @elseif($order->status == 'completed')
                                                            <span class="badge text-bg-success">Completed</span>
                                                        @elseif($order->status == 'cancelled')
                                                            <span class="badge text-bg-danger">Cancelled</span>
                                                        @elseif($order->status == 'refunded')
                                                            <span class="badge text-bg-dark">Refunded</span>
                                                        @elseif($order->status == 'failed')
                                                            <span class="badge text-bg-danger">Failed</span>
                                                        @endif
                                                    </td>
                                                    <td>${{ number_format($order->total, 2) }}</td>
                                                    <td>
                                                        <a href="{{ route('user.orders.show', $order->id) }}" class="btn btn-outline-primary-2 btn-sm">View Details</a>
                                                    </td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="mt-3">
                                        {{ $orders->links() }}
                                    </div>
                                @else
                                    <p>No order has been made yet.</p>
                                    <a href="{{ route('shop') }}" class="btn btn-outline-primary-2"><span>Go Shop</span><i class="icon-long-arrow-right"></i></a>
                                @endif
                            </div>

                            <!-- 3. Downloads Tab -->
                            <div class="tab-pane fade" id="tab-downloads" role="tabpanel" aria-labelledby="tab-downloads-link">
                                <p>No downloads available yet.</p>
                                <a href="{{ route('shop') }}" class="btn btn-outline-primary-2"><span>Go Shop</span><i class="icon-long-arrow-right"></i></a>
                            </div>

                            <!-- 4. Addresses Tab -->
                            <div class="tab-pane fade" id="tab-address" role="tabpanel" aria-labelledby="tab-address-link">
                                <p>The following addresses will be used on the checkout page by default.</p>
                                @if($latestOrder)
                                    <div class="row">
                                        <div class="col-lg-6">
                                            <div class="card card-dashboard">
                                                <div class="card-body">
                                                    <h3 class="card-title">Billing Address</h3>
                                                    <address style="font-style: normal; line-height: 1.6;">
                                                        <strong>{{ $latestOrder->billing_first_name }} {{ $latestOrder->billing_last_name }}</strong><br>
                                                        @if($latestOrder->billing_company)
                                                            {{ $latestOrder->billing_company }}<br>
                                                        @endif
                                                        {{ $latestOrder->billing_address_1 }}<br>
                                                        @if($latestOrder->billing_address_2)
                                                            {{ $latestOrder->billing_address_2 }}<br>
                                                        @endif
                                                        @if($latestOrder->billingCountry && $latestOrder->billingCountry->code === 'BD')
                                                            {{ $latestOrder->billingArea ? $latestOrder->billingArea->name . ', ' : '' }}
                                                            {{ $latestOrder->billingDistrict ? $latestOrder->billingDistrict->name . ', ' : '' }}
                                                            {{ $latestOrder->billingDivision ? $latestOrder->billingDivision->name : '' }}<br>
                                                        @else
                                                            {{ $latestOrder->billing_city }}, {{ $latestOrder->billing_state }}<br>
                                                        @endif
                                                        {{ $latestOrder->billingCountry ? $latestOrder->billingCountry->name : '' }} - {{ $latestOrder->billing_postcode }}<br>
                                                        <i class="icon-phone"></i> {{ $latestOrder->billing_phone }}<br>
                                                        <i class="icon-envelope"></i> {{ $latestOrder->billing_email }}
                                                    </address>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-lg-6">
                                            <div class="card card-dashboard">
                                                <div class="card-body">
                                                    <h3 class="card-title">Shipping Address</h3>
                                                    <address style="font-style: normal; line-height: 1.6;">
                                                        <strong>{{ $latestOrder->shipping_first_name }} {{ $latestOrder->shipping_last_name }}</strong><br>
                                                        @if($latestOrder->shipping_company)
                                                            {{ $latestOrder->shipping_company }}<br>
                                                        @endif
                                                        {{ $latestOrder->shipping_address_1 }}<br>
                                                        @if($latestOrder->shipping_address_2)
                                                            {{ $latestOrder->shipping_address_2 }}<br>
                                                        @endif
                                                        @if($latestOrder->shippingCountry && $latestOrder->shippingCountry->code === 'BD')
                                                            {{ $latestOrder->shippingArea ? $latestOrder->shippingArea->name . ', ' : '' }}
                                                            {{ $latestOrder->shippingDistrict ? $latestOrder->shippingDistrict->name . ', ' : '' }}
                                                            {{ $latestOrder->shippingDivision ? $latestOrder->shippingDivision->name : '' }}<br>
                                                        @else
                                                            {{ $latestOrder->shipping_city }}, {{ $latestOrder->shipping_state }}<br>
                                                        @endif
                                                        {{ $latestOrder->shippingCountry ? $latestOrder->shippingCountry->name : '' }} - {{ $latestOrder->shipping_postcode }}<br>
                                                        @if($latestOrder->shipping_phone)
                                                            <i class="icon-phone"></i> {{ $latestOrder->shipping_phone }}
                                                        @endif
                                                    </address>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @else
                                    <p>You have not configured billing or shipping addresses yet. Your addresses will be automatically saved from checkout details when you place an order.</p>
                                @endif
                            </div>

                             <!-- 5. Wishlist Tab -->
                             <div class="tab-pane fade" id="tab-wishlist" role="tabpanel" aria-labelledby="tab-wishlist-link">
                                 <h3 class="card-title">My Wishlist</h3>
                                 @if($wishlist->count() > 0)
                                     <div class="table-responsive">
                                         <table class="table table-wishlist table-mobile" style="font-size: 1.3rem;">
                                             <thead>
                                                 <tr>
                                                     <th>Product</th>
                                                     <th>Price</th>
                                                     <th>Stock Status</th>
                                                     <th></th>
                                                     <th></th>
                                                 </tr>
                                             </thead>
                                             <tbody>
                                                 @foreach ($wishlist as $item)
                                                 @php
                                                     $product = $item->product;
                                                     if (!$product) continue;

                                                     $image = App\Models\ProductImageModel::where('product_id', $product->id)->orderBy('order_by', 'asc')->first();
                                                     $imageSrc = $image ? $image->getImagesLogo() : null;
                                                     $slug = $product->slug;
                                                     $title = $product->product_title;

                                                     $hasSizes = App\Models\ProductSizeModel::where('product_id', $product->id)->exists();
                                                     $hasColors = App\Models\ProductColorModel::where('product_id', $product->id)->exists();
                                                     $hasVariations = $hasSizes || $hasColors;
                                                 @endphp
                                                 <tr>
                                                     <td class="product-col">
                                                         <div class="product">
                                                             <figure class="product-media">
                                                                 <a href="{{ url($slug) }}">
                                                                     <img src="{{ $imageSrc ? $imageSrc : asset('assets/images/products/table/product-1.jpg') }}" alt="{{ $title }}">
                                                                 </a>
                                                             </figure>
                                                             <h3 class="product-title">
                                                                 <a href="{{ url($slug) }}">{{ $title }}</a>
                                                             </h3>
                                                         </div>
                                                     </td>
                                                     <td class="price-col">${{ number_format($product->price, 2) }}</td>
                                                     <td class="stock-col">
                                                         @if($product->status == 0)
                                                             <span class="in-stock">In Stock</span>
                                                         @else
                                                             <span class="out-of-stock" style="color: red;">Out of Stock</span>
                                                         @endif
                                                     </td>
                                                     <td class="action-col">
                                                         @if($product->status == 0)
                                                             @if($hasVariations)
                                                                 <a href="{{ url($slug) }}" class="btn btn-outline-primary-2 btn-sm">Select Options</a>
                                                             @else
                                                                 <form action="{{ url('product/add-to-cart') }}" method="POST" style="margin: 0;">
                                                                     {{ csrf_field() }}
                                                                     <input type="hidden" name="product_id" value="{{ $product->id }}">
                                                                     <input type="hidden" name="quantity" value="1">
                                                                     <button type="submit" class="btn btn-outline-primary-2 btn-sm">Add to Cart</button>
                                                                 </form>
                                                             @endif
                                                         @else
                                                             <button class="btn btn-sm btn-outline-primary-2 disabled" disabled>Out of Stock</button>
                                                         @endif
                                                     </td>
                                                     <td class="remove-col">
                                                         <a href="{{ route('wishlist.remove', $item->id) }}" class="btn-remove" title="Remove Product"><i class="icon-close"></i></a>
                                                     </td>
                                                 </tr>
                                                 @endforeach
                                             </tbody>
                                         </table>
                                     </div>
                                 @else
                                     <p>Your wishlist is currently empty.</p>
                                     <a href="{{ route('shop') }}" class="btn btn-outline-primary-2"><span>Go Shop</span><i class="icon-long-arrow-right"></i></a>
                                 @endif
                             </div>

                            <!-- 6. Coupons Tab -->
                            <div class="tab-pane fade" id="tab-coupons" role="tabpanel" aria-labelledby="tab-coupons-link">
                                <h3 class="card-title">Available Coupons</h3>
                                @if(count($coupons) > 0)
                                    <div class="table-responsive">
                                        <table class="table table-wishlist table-mobile" style="font-size: 1.3rem;">
                                            <thead>
                                                <tr>
                                                    <th>Code</th>
                                                    <th>Discount</th>
                                                    <th>Min Order</th>
                                                    <th>Expires</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($coupons as $coupon)
                                                <tr>
                                                    <td><strong class="text-primary" style="font-size:1.6rem; letter-spacing:1px;">{{ $coupon->code }}</strong></td>
                                                    <td>
                                                        @if($coupon->discount_type === 'percentage')
                                                            {{ $coupon->discount_value }}% Off
                                                        @else
                                                            ${{ number_format($coupon->discount_value, 2) }} Off
                                                        @endif
                                                    </td>
                                                    <td>${{ number_format($coupon->minimum_order_amount, 2) }}</td>
                                                    <td>{{ $coupon->expires_at ? date('M d, Y', strtotime($coupon->expires_at)) : 'Never' }}</td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                @else
                                    <p>No active coupons available at this time.</p>
                                @endif
                            </div>

                            <!-- 7. Reviews Tab -->
                            <div class="tab-pane fade" id="tab-reviews" role="tabpanel" aria-labelledby="tab-reviews-link">
                                <p>You have not submitted any product reviews yet.</p>
                            </div>

                            <!-- 8. Account Details Tab -->
                            <div class="tab-pane fade" id="tab-account" role="tabpanel" aria-labelledby="tab-account-link">
                                <form action="{{ route('user.profile.update') }}" method="POST">
                                    {{ csrf_field() }}
                                    
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <label>Full Name *</label>
                                            <input type="text" name="name" value="{{ auth()->user()->name }}" class="form-control" required>
                                        </div>
                                    </div>

                                    <label>Email address *</label>
                                    <input type="email" name="email" value="{{ auth()->user()->email }}" class="form-control" required>

                                    <div style="border: 1px solid #ebebeb; padding: 20px; border-radius: 5px; margin-top: 20px; margin-bottom: 20px;">
                                        <h4 class="mb-3">Password Change (leave blank to leave unchanged)</h4>
                                        
                                        <label>Current password</label>
                                        <input type="password" name="current_password" class="form-control">

                                        <label>New password</label>
                                        <input type="password" name="new_password" class="form-control">
                                    </div>

                                    <button type="submit" class="btn btn-outline-primary-2">
                                        <span>SAVE CHANGES</span>
                                        <i class="icon-long-arrow-right"></i>
                                    </button>
                                </form>
                            </div>
                            
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
@endsection

@section('script')
<script>
    $(document).ready(function() {
        // Handle trigger links inside tabs to switch active tabs
        $('.tab-trigger-link').on('click', function(e) {
            e.preventDefault();
            let targetTab = $(this).attr('href');
            $(`.nav-dashboard a[href="${targetTab}"]`).tab('show');
        });
    });
</script>
@endsection
