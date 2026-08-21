@extends('admin.layouts.app')

@section('content')

<div class="content-wrapper">

    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-12">
                    <h1>WooCommerce Checkout Settings</h1>
                </div>
            </div>
        </div>
    </section>

    <div class="container-fluid">

        <div class="row">
            <div class="col-md-8">

                <div class="card card-primary card-outline mb-4">
                    <div class="card-header">
                        <h3 class="card-title">Checkout & Shipping Options</h3>
                    </div>

                    @include('admin.layouts._message')

                    <form action="{{ route('admin.settings.update') }}" method="POST">
                        {{ csrf_field() }}

                        <div class="card-body">

                            <!-- Guest Checkout -->
                            <div class="mb-4">
                                <label class="form-label fw-bold">Guest Checkout</label>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="guest_checkout" id="guest_checkout_yes" value="yes" {{ $guest_checkout === 'yes' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="guest_checkout_yes">
                                        Allow customers to place orders without an account
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="guest_checkout" id="guest_checkout_no" value="no" {{ $guest_checkout === 'no' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="guest_checkout_no">
                                        Require customers to be logged in to checkout
                                    </label>
                                </div>
                                <small class="text-muted">Controls whether users can checkout as a guest or must have an account.</small>
                            </div>

                            <hr>

                            <!-- Account Creation -->
                            <div class="mb-4 text-start">
                                <label class="form-label fw-bold">Account Creation</label>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="account_creation" id="account_creation_yes" value="yes" {{ $account_creation === 'yes' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="account_creation_yes">
                                        Allow customers to create an account during checkout
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="account_creation" id="account_creation_no" value="no" {{ $account_creation === 'no' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="account_creation_no">
                                        Disable account creation during checkout
                                    </label>
                                </div>
                                <small class="text-muted">Controls whether a password input is shown during checkout to create an account.</small>
                            </div>

                            <hr>

                            <!-- Shipping Destination (WooCommerce Style) -->
                            <div class="mb-4 text-start">
                                <label class="form-label fw-bold">Shipping Destination</label>
                                <select name="shipping_destination" class="form-select">
                                    <option value="billing_default" {{ $shipping_destination === 'billing_default' ? 'selected' : '' }}>
                                        Default to customer billing address
                                    </option>
                                    <option value="shipping_default" {{ $shipping_destination === 'shipping_default' ? 'selected' : '' }}>
                                        Default to customer shipping address
                                    </option>
                                    <option value="billing_only" {{ $shipping_destination === 'billing_only' ? 'selected' : '' }}>
                                        Force shipping to the customer billing address
                                    </option>
                                </select>
                                <small class="text-muted">
                                    - <strong>Default to billing address</strong>: Toggles "Ship to different address" off by default.
                                    <br>- <strong>Default to shipping address</strong>: Toggles "Ship to different address" on by default.
                                    <br>- <strong>Force shipping to billing address</strong>: Completely hides the "Ship to different address" option and uses the billing address for shipping rate calculation.
                                </small>
                            </div>

                        </div>

                        <div class="card-footer">
                            <button type="submit" class="btn btn-primary">
                                Save Settings
                            </button>
                        </div>

                    </form>

                </div>

            </div>
        </div>

    </div>
</div>

@endsection
