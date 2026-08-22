@extends('admin.layouts.app')
@section('content')

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-12">
                    <h1>Home Page Settings</h1>
                </div>
            </div>
        </div>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            @include('admin.layouts._message')

            <form action="{{ route('admin.home_setting.update') }}" method="POST" enctype="multipart/form-data">
                {{ csrf_field() }}

                <!-- CARD 1: Section Titles -->
                <div class="card card-default mb-4 shadow-sm">
                    <div class="card-header bg-light">
                        <h3 class="card-title fw-bold text-secondary">General Homepage Section Titles</h3>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Trendy Products Title</label>
                                <input type="text" name="trendy_product_title" value="{{ old('trendy_product_title', $settings->trendy_product_title ?? '') }}" class="form-control" placeholder="e.g. Trendy Products">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Shop by Categories Title</label>
                                <input type="text" name="shop_category_title" value="{{ old('shop_category_title', $settings->shop_category_title ?? '') }}" class="form-control" placeholder="e.g. Shop by Categories">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Recent Arrivals Title</label>
                                <input type="text" name="recent_arrival_title" value="{{ old('recent_arrival_title', $settings->recent_arrival_title ?? '') }}" class="form-control" placeholder="e.g. Recent Arrivals">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">From Our Blog Title</label>
                                <input type="text" name="blog_title" value="{{ old('blog_title', $settings->blog_title ?? '') }}" class="form-control" placeholder="e.g. From Our Blog">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- CARD 2: Service / Features Section -->
                <div class="card card-default mb-4 shadow-sm">
                    <div class="card-header bg-light">
                        <h3 class="card-title fw-bold text-secondary">Service & Features Section</h3>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <!-- Feature 1: Payment & Delivery -->
                            <div class="col-md-4 mb-4 border-end">
                                <h5 class="fw-bold text-primary mb-3">1. Payment & Delivery</h5>
                                <div class="mb-3">
                                    <label class="form-label">Title</label>
                                    <input type="text" name="payment_delivery_title" value="{{ old('payment_delivery_title', $settings->payment_delivery_title ?? '') }}" class="form-control">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Description</label>
                                    <textarea name="payment_delivery_description" class="form-control" rows="2">{{ old('payment_delivery_description', $settings->payment_delivery_description ?? '') }}</textarea>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Icon / Image</label>
                                    <input type="file" name="payment_delivery_image" class="form-control">
                                    @if(!empty($settings->payment_delivery_image))
                                        <div class="mt-2">
                                            <img src="{{ $settings->getPaymentDeliveryImageUrl() }}" alt="Payment & Delivery Icon" style="max-height: 40px; border-radius: 4px;">
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <!-- Feature 2: Money Back Guarantee -->
                            <div class="col-md-4 mb-4 border-end">
                                <h5 class="fw-bold text-primary mb-3">2. Money Back Guarantee</h5>
                                <div class="mb-3">
                                    <label class="form-label">Title</label>
                                    <input type="text" name="refund_title" value="{{ old('refund_title', $settings->refund_title ?? '') }}" class="form-control">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Description</label>
                                    <textarea name="refund_description" class="form-control" rows="2">{{ old('refund_description', $settings->refund_description ?? '') }}</textarea>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Icon / Image</label>
                                    <input type="file" name="refund_image" class="form-control">
                                    @if(!empty($settings->refund_image))
                                        <div class="mt-2">
                                            <img src="{{ $settings->getRefundImageUrl() }}" alt="Refund Icon" style="max-height: 40px; border-radius: 4px;">
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <!-- Feature 3: Quality Support -->
                            <div class="col-md-4 mb-4">
                                <h5 class="fw-bold text-primary mb-3">3. Online Support</h5>
                                <div class="mb-3">
                                    <label class="form-label">Title</label>
                                    <input type="text" name="support_title" value="{{ old('support_title', $settings->support_title ?? '') }}" class="form-control">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Description</label>
                                    <textarea name="support_description" class="form-control" rows="2">{{ old('support_description', $settings->support_description ?? '') }}</textarea>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Icon / Image</label>
                                    <input type="file" name="support_image" class="form-control">
                                    @if(!empty($settings->support_image))
                                        <div class="mt-2">
                                            <img src="{{ $settings->getSupportImageUrl() }}" alt="Support Icon" style="max-height: 40px; border-radius: 4px;">
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- CARD 3: Newsletter Sign Up Section -->
                <div class="card card-default mb-4 shadow-sm">
                    <div class="card-header bg-light">
                        <h3 class="card-title fw-bold text-secondary">Newsletter Sign Up Section</h3>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Signup Title</label>
                                <input type="text" name="singup_title" value="{{ old('singup_title', $settings->singup_title ?? '') }}" class="form-control">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Signup Description</label>
                                <textarea name="singup_description" class="form-control" rows="2">{{ old('singup_description', $settings->singup_description ?? '') }}</textarea>
                            </div>
                            <div class="col-md-12 mb-3">
                                <label class="form-label">Newsletter Banner / Image</label>
                                <input type="file" name="singup_image" class="form-control">
                                @if(!empty($settings->singup_image))
                                    <div class="mt-2">
                                        <img src="{{ $settings->getSingupImageUrl() }}" alt="Newsletter Banner" style="max-height: 120px; border-radius: 4px;">
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Form Submit Buttons -->
                <div class="mb-5">
                    <button type="submit" class="btn btn-primary px-4 fw-bold me-2">Save Settings</button>
                </div>
            </form>
        </div>
    </section>
</div>

@endsection
