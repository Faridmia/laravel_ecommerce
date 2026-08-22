@extends('admin.layouts.app')
@section('content')

<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-12">
                    <h1>Orders List</h1>
                </div>
            </div>
        </div>
    </section>

    <div class="container-fluid">
        <!-- Search and Filter Form -->
        <div class="card mb-3 shadow-sm">
            <div class="card-header bg-transparent py-3">
                <h3 class="card-title fw-bold text-secondary">Orders Search</h3>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.orders.list') }}" method="GET">
                    <div class="row">
                        <div class="col-md-3 mb-2">
                            <label class="form-label text-secondary fw-semibold">Search</label>
                            <input type="text" name="search" value="{{ request()->search }}" class="form-control" placeholder="Order #, Name, Email">
                        </div>
                        <div class="col-md-2 mb-2">
                            <label class="form-label text-secondary fw-semibold">Order Status</label>
                            <select name="status" class="form-select">
                                <option value="">All Statuses</option>
                                <option value="pending" {{ request()->status == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="processing" {{ request()->status == 'processing' ? 'selected' : '' }}>Inprogress</option>
                                <option value="on-hold" {{ request()->status == 'on-hold' ? 'selected' : '' }}>On Hold</option>
                                <option value="completed" {{ request()->status == 'completed' ? 'selected' : '' }}>Completed</option>
                                <option value="cancelled" {{ request()->status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                <option value="refunded" {{ request()->status == 'refunded' ? 'selected' : '' }}>Refunded</option>
                                <option value="failed" {{ request()->status == 'failed' ? 'selected' : '' }}>Failed</option>
                            </select>
                        </div>
                        <div class="col-md-2 mb-2">
                            <label class="form-label text-secondary fw-semibold">State</label>
                            <input type="text" name="state" value="{{ request()->state }}" class="form-control" placeholder="State/Division">
                        </div>
                        <div class="col-md-2 mb-2">
                            <label class="form-label text-secondary fw-semibold">City</label>
                            <input type="text" name="city" value="{{ request()->city }}" class="form-control" placeholder="City/District">
                        </div>
                        <div class="col-md-3 mb-2">
                            <label class="form-label text-secondary fw-semibold">Phone</label>
                            <input type="text" name="phone" value="{{ request()->phone }}" class="form-control" placeholder="Phone">
                        </div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-md-3 mb-2">
                            <label class="form-label text-secondary fw-semibold">Postcode</label>
                            <input type="text" name="postcode" value="{{ request()->postcode }}" class="form-control" placeholder="Postcode">
                        </div>
                        <div class="col-md-3 mb-2">
                            <label class="form-label text-secondary fw-semibold">Start Date</label>
                            <input type="date" name="start_date" value="{{ request()->start_date }}" class="form-control">
                        </div>
                        <div class="col-md-3 mb-2">
                            <label class="form-label text-secondary fw-semibold">End Date</label>
                            <input type="date" name="end_date" value="{{ request()->end_date }}" class="form-control">
                        </div>
                        <div class="col-md-3 mb-2 d-flex align-items-end">
                            <button type="submit" class="btn btn-primary me-2 px-4 fw-semibold">Search</button>
                            <a href="{{ route('admin.orders.list') }}" class="btn btn-secondary px-4 fw-semibold">Reset</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        @include('admin.layouts._message')

        <div class="row">
            <div class="col-md-12">
                <div class="card mb-4 shadow-sm">
                    <div class="card-header bg-transparent py-3">
                        <h3 class="card-title fw-bold text-secondary">Orders List</h3>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body p-0 table-responsive">
                        <table class="table table-striped align-middle" role="table" style="font-size: 13px;">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Order Number</th>
                                    <th>Name</th>
                                    <th>Company Name</th>
                                    <th>County</th>
                                    <th>Address</th>
                                    <th>City</th>
                                    <th>State</th>
                                    <th>Postcode</th>
                                    <th>Phone</th>
                                    <th>Email</th>
                                    <th>Discount Code</th>
                                    <th>Discount Amount ($)</th>
                                    <th>Shipping Amount ($)</th>
                                    <th>Total Amount ($)</th>
                                    <th>Payment Method</th>
                                    <th>Status</th>
                                    <th>Created Date</th>
                                    <th style="width: 100px; text-align: center;">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($orders as $value)
                                <tr>
                                    <td>{{ $value->id }}</td>
                                    <td><strong>{{ $value->order_number }}</strong></td>
                                    <td>
                                        {{ $value->billing_first_name }}<br>
                                        {{ $value->billing_last_name }}
                                    </td>
                                    <td>{{ !empty($value->billing_company) ? $value->billing_company : '—' }}</td>
                                    <td>{{ $value->billingCountry->name ?? '—' }}</td>
                                    <td>
                                        {{ $value->billing_address_1 }}
                                        @if(!empty($value->billing_address_2))
                                            <br>{{ $value->billing_address_2 }}
                                        @endif
                                    </td>
                                    <td>
                                        {{ !empty($value->billingDistrict) ? $value->billingDistrict->name : (!empty($value->billing_city) ? $value->billing_city : '—') }}
                                    </td>
                                    <td>
                                        {{ !empty($value->billingDivision) ? $value->billingDivision->name : (!empty($value->billing_state) ? $value->billing_state : '—') }}
                                    </td>
                                    <td>{{ $value->billing_postcode }}</td>
                                    <td>{{ $value->billing_phone }}</td>
                                    <td><a href="mailto:{{ $value->billing_email }}">{{ $value->billing_email }}</a></td>
                                    <td>{{ !empty($value->coupon_code) ? $value->coupon_code : '—' }}</td>
                                    <td>${{ number_format($value->discount, 2) }}</td>
                                    <td>${{ number_format($value->shipping_charge, 2) }}</td>
                                    <td><strong>${{ number_format($value->total, 2) }}</strong></td>
                                    <td>{{ strtoupper($value->payment_method) === 'COD' ? 'Cash' : ucfirst($value->payment_method) }}</td>
                                    <td>
                                        <select class="form-select order-status-select form-select-sm" data-id="{{ $value->id }}" style="min-width: 120px;">
                                            <option value="pending" {{ $value->status == 'pending' ? 'selected' : '' }}>Pending</option>
                                            <option value="processing" {{ $value->status == 'processing' ? 'selected' : '' }}>Inprogress</option>
                                            <option value="on-hold" {{ $value->status == 'on-hold' ? 'selected' : '' }}>On Hold</option>
                                            <option value="completed" {{ $value->status == 'completed' ? 'selected' : '' }}>Completed</option>
                                            <option value="cancelled" {{ $value->status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                            <option value="refunded" {{ $value->status == 'refunded' ? 'selected' : '' }}>Refunded</option>
                                            <option value="failed" {{ $value->status == 'failed' ? 'selected' : '' }}>Failed</option>
                                        </select>
                                    </td>
                                    <td>{{ $value->created_at->format('d-m-Y h:i A') }}</td>
                                    <td class="text-center">
                                        <a href="{{ route('admin.orders.show', $value->id) }}" class="btn btn-sm btn-primary fw-semibold px-3">Detail</a>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="19" class="text-center py-4 text-muted">No orders found.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                        
                        @if($orders->hasPages())
                            <div class="card-footer bg-transparent py-3 clearfix">
                                <div class="float-end">
                                    {{ $orders->appends(request()->except('page'))->links() }}
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('script')
<script>
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.order-status-select').forEach(function(select) {
        select.addEventListener('change', function() {
            var status = this.value;
            var orderId = this.getAttribute('data-id');
            var url = "{{ url('admin/orders/update-status') }}/" + orderId;
            
            fetch(url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': "{{ csrf_token() }}"
                },
                body: JSON.stringify({
                    status: status
                })
            })
            .then(function(response) {
                return response.json();
            })
            .then(function(data) {
                if(data.status === 'success') {
                    alert(data.message);
                } else {
                    alert('Failed to update status.');
                }
            })
            .catch(function(error) {
                alert('Something went wrong. Please try again.');
            });
        });
    });
});
</script>
@endsection
