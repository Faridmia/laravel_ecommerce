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
    .dashboard .card-dashboard-metric {
        border: 1px solid #ebebeb;
        background-color: #fff;
        padding: 2rem 1rem;
        margin-bottom: 2rem;
        border-radius: 5px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.02);
    }
    .dashboard .card-dashboard-metric h3 {
        font-size: 2.2rem;
        font-weight: 700;
        color: #333;
        margin-bottom: 0.5rem;
    }
    .dashboard .card-dashboard-metric span {
        font-size: 1.3rem;
        font-weight: 500;
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
                                <a class="nav-link" id="tab-profile-link" data-toggle="tab" href="#tab-profile" role="tab" aria-controls="tab-profile" aria-selected="false">Edit Profile</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="tab-notifications-link" data-toggle="tab" href="#tab-notifications" role="tab" aria-controls="tab-notifications" aria-selected="false">Notification</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="tab-password-link" data-toggle="tab" href="#tab-password" role="tab" aria-controls="tab-password" aria-selected="false">Change Password</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('user.logout') }}">Logout</a>
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
                                <div class="row">
                                    <!-- Total Orders -->
                                    <div class="col-6 col-md-3">
                                        <div class="card-dashboard-metric text-center">
                                            <h3>{{ $totalOrders }}</h3>
                                            <span style="color: #666;">Total Orders</span>
                                        </div>
                                    </div>
                                    <!-- Today Order -->
                                    <div class="col-6 col-md-3">
                                        <div class="card-dashboard-metric text-center">
                                            <h3>{{ $todayOrders }}</h3>
                                            <span style="color: #c96;">Today Order</span>
                                        </div>
                                    </div>
                                    <!-- Total Amount -->
                                    <div class="col-6 col-md-3">
                                        <div class="card-dashboard-metric text-center">
                                            <h3>${{ number_format($totalAmount, 2) }}</h3>
                                            <span style="color: #666;">Total Amount</span>
                                        </div>
                                    </div>
                                    <!-- Today Amount -->
                                    <div class="col-6 col-md-3">
                                        <div class="card-dashboard-metric text-center">
                                            <h3>${{ number_format($todayAmount, 2) }}</h3>
                                            <span style="color: #20c997;">Today Amount</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <!-- Pending Orders -->
                                    <div class="col-6 col-md-3">
                                        <div class="card-dashboard-metric text-center">
                                            <h3>{{ $pendingOrders }}</h3>
                                            <span style="color: #ffc107;">Pending Orders</span>
                                        </div>
                                    </div>
                                    <!-- In Progress Orders -->
                                    <div class="col-6 col-md-3">
                                        <div class="card-dashboard-metric text-center">
                                            <h3>{{ $inProgressOrders }}</h3>
                                            <span style="color: #0d6efd;">In Progress Orders</span>
                                        </div>
                                    </div>
                                    <!-- Completed Orders -->
                                    <div class="col-6 col-md-3">
                                        <div class="card-dashboard-metric text-center">
                                            <h3>{{ $completedOrders }}</h3>
                                            <span style="color: #28a745;">Completed Orders</span>
                                        </div>
                                    </div>
                                    <!-- Cancelled Orders -->
                                    <div class="col-6 col-md-3">
                                        <div class="card-dashboard-metric text-center">
                                            <h3>{{ $cancelledOrders }}</h3>
                                            <span style="color: #dc3545;">Cancelled Orders</span>
                                        </div>
                                    </div>
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

                            <!-- 3. Edit Profile Tab -->
                            <div class="tab-pane fade" id="tab-profile" role="tabpanel" aria-labelledby="tab-profile-link">
                                <form action="{{ route('user.profile.update') }}" method="POST">
                                    {{ csrf_field() }}
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <label>First Name *</label>
                                            <input type="text" name="first_name" value="{{ auth()->user()->first_name }}" class="form-control" required>
                                        </div>

                                        <div class="col-sm-6">
                                            <label>Last Name *</label>
                                            <input type="text" name="last_name" value="{{ auth()->user()->last_name }}" class="form-control" required>
                                        </div>
                                    </div>

                                    <label>Email address *</label>
                                    <input type="email" name="email" value="{{ auth()->user()->email }}" class="form-control" required>

                                    <label>Company Name (Optional)</label>
                                    <input type="text" name="billing_company" value="{{ auth()->user()->billing_company }}" class="form-control">

                                    <label>Country *</label>
                                    <select name="billing_country_id" class="form-control" required>
                                        <option value="">Select Country</option>
                                        @foreach($countries as $country)
                                            <option value="{{ $country->id }}" {{ auth()->user()->billing_country_id == $country->id ? 'selected' : '' }}>
                                                {{ $country->name }}
                                            </option>
                                        @endforeach
                                    </select>

                                    <label>Street address *</label>
                                    <input type="text" name="billing_address_1" value="{{ auth()->user()->billing_address_1 }}" class="form-control" placeholder="House number and street name" required style="margin-bottom: 10px;">
                                    <input type="text" name="billing_address_2" value="{{ auth()->user()->billing_address_2 }}" class="form-control" placeholder="Apartment, suite, unit etc. (optional)">

                                    <div class="row">
                                        <div class="col-sm-6">
                                            <label>Town / City *</label>
                                            <input type="text" name="billing_city" value="{{ auth()->user()->billing_city }}" class="form-control" required>
                                        </div>

                                        <div class="col-sm-6">
                                            <label>State *</label>
                                            <input type="text" name="billing_state" value="{{ auth()->user()->billing_state }}" class="form-control" required>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-sm-6">
                                            <label>Postcode / ZIP *</label>
                                            <input type="text" name="billing_postcode" value="{{ auth()->user()->billing_postcode }}" class="form-control" required>
                                        </div>

                                        <div class="col-sm-6">
                                            <label>Phone *</label>
                                            <input type="text" name="billing_phone" value="{{ auth()->user()->billing_phone }}" class="form-control" required>
                                        </div>
                                    </div>

                                    <button type="submit" class="btn btn-outline-primary-2 mt-3">
                                        <span>Submit</span>
                                        <i class="icon-long-arrow-right"></i>
                                    </button>
                                </form>
                            </div>

                            <!-- 4. Notification Tab -->
                            <div class="tab-pane fade" id="tab-notifications" role="tabpanel" aria-labelledby="tab-notifications-link">
                                <div class="notifications-list">
                                    @forelse($notifications as $notification)
                                        <div style="background: @if(!$notification->is_read) #f5f5f5 @else #ffffff @endif; padding: 20px; margin-bottom: 15px; border-radius: 0; border: none; border-bottom: 1px solid #ebebeb;">
                                            <a href="{{ route('notifications.read', $notification->id) }}" style="text-decoration: none; display: block; color: inherit;">
                                                <div style="font-size: 1.6rem; font-weight: 600; color: #333333; margin-bottom: 5px;">{{ $notification->message }}</div>
                                                <div style="font-size: 1.3rem; color: #888888;">{{ $notification->created_at->format('d-m-Y h:i A') }}</div>
                                            </a>
                                        </div>
                                    @empty
                                        <div class="text-center p-4">
                                            <p>No notifications available.</p>
                                        </div>
                                    @endforelse
                                </div>
                            </div>

                            <!-- 5. Change Password Tab -->
                            <div class="tab-pane fade" id="tab-password" role="tabpanel" aria-labelledby="tab-password-link">
                                <form action="{{ route('user.password.update') }}" method="POST">
                                    {{ csrf_field() }}
                                    <label>Current Password *</label>
                                    <input type="password" name="current_password" class="form-control" required>

                                    <label>New Password *</label>
                                    <input type="password" name="new_password" class="form-control" required>

                                    <label>Confirm Password *</label>
                                    <input type="password" name="new_password_confirmation" class="form-control" required>

                                    <button type="submit" class="btn btn-outline-primary-2 mt-3">
                                        <span>Submit</span>
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
