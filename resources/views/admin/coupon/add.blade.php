@extends('admin.layouts.app')
@section('content')

<div class="content-wrapper">

    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-12">
                    <h1>Add New Coupon</h1>
                </div>
            </div>
        </div>
    </section>

    <div class="container-fluid">
        <div class="row">
            <div class="col-md-10">
                <div class="card card-primary card-outline mb-4">
                    @include('admin.layouts._message')

                    <form action="{{ route('admin.coupon.store') }}" method="POST">
                        {{ csrf_field() }}

                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Coupon Code <span class="text-danger">*</span></label>
                                        <input type="text" name="code" value="{{ old('code') }}" class="form-control" required placeholder="e.g. SAVE20">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Discount Type <span class="text-danger">*</span></label>
                                        <select name="discount_type" class="form-select" required>
                                            <option value="fixed" {{ old('discount_type') == 'fixed' ? 'selected' : '' }}>Fixed Amount ($)</option>
                                            <option value="percentage" {{ old('discount_type') == 'percentage' ? 'selected' : '' }}>Percentage (%)</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Discount Value <span class="text-danger">*</span></label>
                                        <input type="number" step="0.01" name="discount_value" value="{{ old('discount_value') }}" class="form-control" required placeholder="e.g. 10.00">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Minimum Order Amount ($)</label>
                                        <input type="number" step="0.01" name="minimum_order_amount" value="{{ old('minimum_order_amount', '0.00') }}" class="form-control" placeholder="e.g. 50.00">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Maximum Discount ($)</label>
                                        <input type="number" step="0.01" name="maximum_discount" value="{{ old('maximum_discount') }}" class="form-control" placeholder="Limit for percentage coupons">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Status <span class="text-danger">*</span></label>
                                        <select name="status" class="form-select" required>
                                            <option value="0" {{ old('status') == '0' ? 'selected' : '' }}>Active</option>
                                            <option value="1" {{ old('status') == '1' ? 'selected' : '' }}>Inactive</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Starts At</label>
                                        <input type="date" name="starts_at" value="{{ old('starts_at') }}" class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Expires At</label>
                                        <input type="date" name="expires_at" value="{{ old('expires_at') }}" class="form-control">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Usage Limit (Total)</label>
                                        <input type="number" name="usage_limit" value="{{ old('usage_limit') }}" class="form-control" placeholder="Leave empty for unlimited">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Usage Limit Per User</label>
                                        <input type="number" name="usage_limit_per_user" value="{{ old('usage_limit_per_user') }}" class="form-control" placeholder="Leave empty for unlimited">
                                    </div>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <div class="form-check form-switch mt-4">
                                        <input class="form-check-input" type="checkbox" name="first_order_only" id="first_order_only" value="1" {{ old('first_order_only') ? 'checked' : '' }}>
                                        <label class="form-check-label" for="first_order_only">First Order Only</label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-check form-switch mt-4">
                                        <input class="form-check-input" type="checkbox" name="free_shipping" id="free_shipping" value="1" {{ old('free_shipping') ? 'checked' : '' }}>
                                        <label class="form-check-label" for="free_shipping">Free Shipping</label>
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Description</label>
                                <textarea name="description" class="form-control" rows="3" placeholder="Brief details about the coupon">{{ old('description') }}</textarea>
                            </div>
                        </div>

                        <div class="card-footer">
                            <button type="submit" class="btn btn-primary">Submit</button>
                            <a href="{{ route('admin.coupon.list') }}" class="btn btn-secondary">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
