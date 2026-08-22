@extends('admin.layouts.app')

@section('content')

<div class="content-wrapper">

    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-12">
                    <h1>Payment Gateway Settings (Repeater System)</h1>
                </div>
            </div>
        </div>
    </section>

    <div class="container-fluid">
        <!-- Success and Error Messages -->
        @include('admin.layouts._message')

        <form action="{{ route('admin.payment_gateways.update') }}" method="POST">
            @csrf

            <div class="row">
                <div class="col-md-12">
                    
                    <div class="accordion mb-4" id="gatewaysAccordion">
                        @foreach($gateways as $gateway)
                            @php
                                $key = $gateway->gateway_key;
                                $pubLabel = 'Public Key';
                                $secLabel = 'Secret Key';
                                $showKeys = true;

                                if ($key === 'cod') {
                                    $showKeys = false;
                                } elseif ($key === 'paypal') {
                                    $pubLabel = 'Client ID';
                                    $secLabel = 'Client Secret';
                                } elseif ($key === 'stripe') {
                                    $pubLabel = 'Publishable Key';
                                    $secLabel = 'Secret Key';
                                } elseif ($key === 'razorpay') {
                                    $pubLabel = 'Key ID';
                                    $secLabel = 'Key Secret';
                                } elseif ($key === 'sslcommerz') {
                                    $pubLabel = 'Store ID';
                                    $secLabel = 'Store Password';
                                } elseif ($key === 'square') {
                                    $pubLabel = 'Application ID';
                                    $secLabel = 'Access Token';
                                } elseif ($key === 'authorizenet') {
                                    $pubLabel = 'API Login ID';
                                    $secLabel = 'Transaction Key';
                                } elseif ($key === 'mollie') {
                                    $pubLabel = 'Profile ID (Optional)';
                                    $secLabel = 'API Key';
                                } elseif ($key === 'paystack') {
                                    $pubLabel = 'Public Key';
                                    $secLabel = 'Secret Key';
                                }
                            @endphp

                            <div class="card mb-3 shadow-sm">
                                <div class="card-header bg-light d-flex justify-content-between align-items-center" id="heading-{{ $key }}" style="cursor: pointer;" data-bs-toggle="collapse" data-bs-target="#collapse-{{ $key }}" aria-expanded="false" aria-controls="collapse-{{ $key }}">
                                    <div class="d-flex align-items-center">
                                        <i class="bi bi-wallet2 me-2 fs-5 text-primary"></i>
                                        <span class="fw-bold fs-5 text-dark">{{ $gateway->name }}</span>
                                    </div>
                                    <div class="d-flex align-items-center">
                                        <!-- Active Badge -->
                                        @if($gateway->status === 'yes')
                                            <span class="badge bg-success me-3">Active</span>
                                        @else
                                            <span class="badge bg-secondary me-3">Inactive</span>
                                        @endif
                                        
                                        <!-- Mode Badge -->
                                        @if($showKeys)
                                            <span class="badge bg-info me-3">{{ ucfirst($gateway->mode) }}</span>
                                        @endif
                                        <i class="bi bi-chevron-down fs-5"></i>
                                    </div>
                                </div>

                                <div id="collapse-{{ $key }}" class="collapse" aria-labelledby="heading-{{ $key }}" data-bs-parent="#gatewaysAccordion">
                                    <div class="card-body">
                                        
                                        <!-- Gateway Name -->
                                        <div class="mb-3">
                                            <label class="form-label fw-bold">Display Name <span class="text-danger">*</span></label>
                                            <input type="text" name="gateways[{{ $key }}][name]" class="form-control" value="{{ $gateway->name }}" required>
                                        </div>

                                        <!-- Description -->
                                        <div class="mb-3">
                                            <label class="form-label fw-bold">Description</label>
                                            <textarea name="gateways[{{ $key }}][description]" class="form-control" rows="2">{{ $gateway->description }}</textarea>
                                        </div>

                                        <!-- Status Toggle -->
                                        <div class="mb-3">
                                            <label class="form-label fw-bold">Status</label>
                                            <div class="form-check form-switch">
                                                <input class="form-check-input" type="checkbox" name="gateways[{{ $key }}][status]" id="status-{{ $key }}" value="yes" {{ $gateway->status === 'yes' ? 'checked' : '' }}>
                                                <label class="form-check-label" for="status-{{ $key }}">Enable this payment gateway</label>
                                            </div>
                                        </div>

                                        @if($showKeys)
                                            <hr>

                                            <!-- Mode Select -->
                                            <div class="mb-3">
                                                <label class="form-label fw-bold">Mode</label>
                                                <select name="gateways[{{ $key }}][mode]" class="form-select">
                                                    <option value="sandbox" {{ $gateway->mode === 'sandbox' ? 'selected' : '' }}>Sandbox (Test)</option>
                                                    <option value="live" {{ $gateway->mode === 'live' ? 'selected' : '' }}>Live (Production)</option>
                                                </select>
                                            </div>

                                            <!-- Public Key -->
                                            <div class="mb-3">
                                                <label class="form-label fw-bold">{{ $pubLabel }}</label>
                                                <input type="text" name="gateways[{{ $key }}][public_key]" class="form-control" value="{{ $gateway->public_key }}" placeholder="Enter {{ $pubLabel }}">
                                            </div>

                                            <!-- Secret Key -->
                                            <div class="mb-3">
                                                <label class="form-label fw-bold">{{ $secLabel }}</label>
                                                <input type="password" name="gateways[{{ $key }}][secret_key]" class="form-control" value="{{ $gateway->secret_key }}" placeholder="Enter {{ $secLabel }}">
                                            </div>
                                        @endif

                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                </div>
            </div>

            <!-- Submit Button Footer -->
            <div class="row">
                <div class="col-md-12 mb-5">
                    <div class="card shadow-sm">
                        <div class="card-footer bg-light text-end">
                            <button type="submit" class="btn btn-primary px-5 py-2">Save All Gateways Settings</button>
                        </div>
                    </div>
                </div>
            </div>

        </form>
    </div>
</div>

@endsection

@section('script')
<script>
    $(document).ready(function() {
        // Toggle chevron icons in accordion headers
        $('.card-header').on('click', function() {
            var icon = $(this).find('.bi-chevron-down, .bi-chevron-up');
            if (icon.hasClass('bi-chevron-down')) {
                icon.removeClass('bi-chevron-down').addClass('bi-chevron-up');
            } else {
                icon.removeClass('bi-chevron-up').addClass('bi-chevron-down');
            }
        });
    });
</script>
@endsection
