@extends('admin.layouts.app')
@section('content')

<div class="content-wrapper">

    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Coupons List</h1>
                </div>
                <div class="col-sm-6" style="text-align: right;">
                    <a href="{{ route('admin.coupon.add') }}" class="btn btn-primary float-sm-right text-right">Add New Coupon</a>
                </div>
            </div>
        </div>
    </section>
    
    <div class="container-fluid">
        @include('admin.layouts._message')
        
        <div class="row">
            <div class="col-md-12">
                <div class="card mb-4">
                    <div class="card-header">
                        <h3 class="card-title">Coupon List</h3>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body p-0">
                        <table class="table table-striped" role="table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Code</th>
                                    <th>Type</th>
                                    <th>Value</th>
                                    <th>Min Order</th>
                                    <th>Max Discount</th>
                                    <th>Limit (Total/Per User)</th>
                                    <th>Usage</th>
                                    <th>First Order Only?</th>
                                    <th>Free Shipping?</th>
                                    <th>Expiry Date</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>

                            <tbody>
                                @foreach($getRecord as $value)
                                <tr class="align-middle">
                                    <td>{{ $value->id }}</td>
                                    <td><strong>{{ $value->code }}</strong></td>
                                    <td>{{ ucfirst($value->discount_type) }}</td>
                                    <td>
                                        @if($value->discount_type == 'percentage')
                                            {{ $value->discount_value }}%
                                        @else
                                            ${{ number_format($value->discount_value, 2) }}
                                        @endif
                                    </td>
                                    <td>${{ number_format($value->minimum_order_amount, 2) }}</td>
                                    <td>
                                        @if(!empty($value->maximum_discount))
                                            ${{ number_format($value->maximum_discount, 2) }}
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td>
                                        {{ $value->usage_limit ?? '∞' }} / {{ $value->usage_limit_per_user ?? '∞' }}
                                    </td>
                                    <td>{{ $value->usage_count }}</td>
                                    <td>
                                        <span class="badge bg-{{ $value->first_order_only ? 'success' : 'secondary' }}">
                                            {{ $value->first_order_only ? 'Yes' : 'No' }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge bg-{{ $value->free_shipping ? 'success' : 'secondary' }}">
                                            {{ $value->free_shipping ? 'Yes' : 'No' }}
                                        </span>
                                    </td>
                                    <td>
                                        @if(!empty($value->starts_at) || !empty($value->expires_at))
                                            <small>
                                                Start: {{ $value->starts_at ?? 'Any' }}<br>
                                                End: {{ $value->expires_at ?? 'Any' }}
                                            </small>
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge bg-{{ $value->status == 0 ? 'success' : 'danger' }}">
                                            {{ $value->status == 0 ? 'Active' : 'Inactive' }}
                                        </span>
                                    </td>
                                    <td>
                                        <a href="{{ route('admin.coupon.edit', $value->id) }}" class="btn btn-sm btn-primary">
                                            Edit
                                        </a>
                                        <a href="{{ route('admin.coupon.delete', $value->id) }}" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this coupon?')">
                                            Delete
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <div class="card-footer clearfix">
                            {{ $getRecord->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
