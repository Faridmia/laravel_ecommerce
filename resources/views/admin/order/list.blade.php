@extends('admin.layouts.app')
@section('style')
@endsection
@section('content')

<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Orders List</h1>
                </div>
            </div>
        </div>
    </section>

    <div class="container-fluid">
        <!-- Search and Filter Form -->
        <div class="card mb-3">
            <div class="card-body">
                <form action="{{ route('admin.orders.list') }}" method="GET">
                    <div class="row">
                        <div class="col-md-4 mb-2">
                            <label class="form-label">Search</label>
                            <input type="text" name="search" value="{{ request()->search }}" class="form-control" placeholder="Search by Order #, Name, Email">
                        </div>
                        <div class="col-md-3 mb-2">
                            <label class="form-label">Order Status</label>
                            <select name="status" class="form-select">
                                <option value="">All Statuses</option>
                                <option value="pending" {{ request()->status == 'pending' ? 'selected' : '' }}>Pending Payment</option>
                                <option value="processing" {{ request()->status == 'processing' ? 'selected' : '' }}>Processing</option>
                                <option value="on-hold" {{ request()->status == 'on-hold' ? 'selected' : '' }}>On Hold</option>
                                <option value="completed" {{ request()->status == 'completed' ? 'selected' : '' }}>Completed</option>
                                <option value="cancelled" {{ request()->status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                <option value="refunded" {{ request()->status == 'refunded' ? 'selected' : '' }}>Refunded</option>
                                <option value="failed" {{ request()->status == 'failed' ? 'selected' : '' }}>Failed</option>
                            </select>
                        </div>
                        <div class="col-md-5 mb-2 d-flex align-items-end">
                            <button type="submit" class="btn btn-primary me-2">Filter</button>
                            <a href="{{ route('admin.orders.list') }}" class="btn btn-secondary">Reset</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        @include('admin.layouts._message')

        <div class="row">
            <div class="col-md-12">
                <div class="card mb-4">
                    <div class="card-header">
                        <h3 class="card-title">Order Records</h3>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body p-0 table-responsive">
                        <table class="table table-striped align-middle" role="table">
                            <thead>
                                <tr>
                                    <th>Order #</th>
                                    <th>Customer</th>
                                    <th>Total</th>
                                    <th>Payment Method</th>
                                    <th>Payment Status</th>
                                    <th>Order Status</th>
                                    <th>Date</th>
                                    <th style="width: 180px;">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($orders as $value)
                                <tr>
                                    <td><strong>{{ $value->order_number }}</strong></td>
                                    <td>
                                        {{ $value->billing_first_name }} {{ $value->billing_last_name }}
                                        <div class="small text-muted">{{ $value->billing_email }}</div>
                                    </td>
                                    <td>${{ number_format($value->total, 2) }}</td>
                                    <td>{{ strtoupper($value->payment_method === 'cod' ? 'COD' : $value->payment_method) }}</td>
                                    <td>
                                        @if($value->payment_status == 'paid')
                                            <span class="badge text-bg-success">Paid</span>
                                        @elseif($value->payment_status == 'pending')
                                            <span class="badge text-bg-warning">Pending</span>
                                        @elseif($value->payment_status == 'failed')
                                            <span class="badge text-bg-danger">Failed</span>
                                        @elseif($value->payment_status == 'refunded')
                                            <span class="badge text-bg-secondary">Refunded</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($value->status == 'pending')
                                            <span class="badge text-bg-warning">Pending Payment</span>
                                        @elseif($value->status == 'processing')
                                            <span class="badge text-bg-primary">Processing</span>
                                        @elseif($value->status == 'on-hold')
                                            <span class="badge text-bg-secondary">On Hold</span>
                                        @elseif($value->status == 'completed')
                                            <span class="badge text-bg-success">Completed</span>
                                        @elseif($value->status == 'cancelled')
                                            <span class="badge text-bg-danger">Cancelled</span>
                                        @elseif($value->status == 'refunded')
                                            <span class="badge text-bg-dark">Refunded</span>
                                        @elseif($value->status == 'failed')
                                            <span class="badge text-bg-danger">Failed</span>
                                        @endif
                                    </td>
                                    <td>{{ $value->created_at->format('Y-m-d h:i A') }}</td>
                                    <td>
                                        <a href="{{ route('admin.orders.show', $value->id) }}" class="btn btn-sm btn-info text-white">
                                            <i class="bi bi-eye"></i> View
                                        </a>
                                        <a href="{{ route('admin.orders.delete', $value->id) }}" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this order?')">
                                            <i class="bi bi-trash"></i> Delete
                                        </a>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="8" class="text-center p-4">No orders found.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                        <div class="card-footer clearfix">
                            {{ $orders->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
