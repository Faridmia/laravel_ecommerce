 @extends('layouts.app')
 @section('style')
 @endsection
 @section('content')
 
    <main class="main">
        <div class="page-header text-center" style="background-image: url('assets/images/page-header-bg.jpg')">
            <div class="container">
                <h1 class="page-title">Checkout<span>Shop</span></h1>
            </div><!-- End .container -->
        </div><!-- End .page-header -->
        <nav aria-label="breadcrumb" class="breadcrumb-nav">
            <div class="container">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('shop') }}">Shop</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Checkout</li>
                </ol>
            </div><!-- End .container -->
        </nav><!-- End .breadcrumb-nav --> 

        <div class="page-content">
            <div class="checkout">
                <div class="container">

                    @php
                        $guestCheckoutSetting = \App\Models\Setting::get('guest_checkout', 'yes');
                        $accountCreationSetting = \App\Models\Setting::get('account_creation', 'yes');
                        $shippingDestinationSetting = \App\Models\Setting::get('shipping_destination', 'billing_default');
                    @endphp

                    <form action="{{ route('checkout.place') }}" method="POST" id="checkout-form">
                        {{ csrf_field() }}
                        <div class="row">
                            <div class="col-lg-9 text-start">
                                <h2 class="checkout-title">Billing Details</h2><!-- End .checkout-title -->
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <label>First Name *</label>
                                            <input type="text" name="billing_first_name" value="{{ auth()->check() ? auth()->user()->name : old('billing_first_name') }}" class="form-control" required>
                                        </div><!-- End .col-sm-6 -->

                                        <div class="col-sm-6">
                                            <label>Last Name *</label>
                                            <input type="text" name="billing_last_name" value="{{ old('billing_last_name') }}" class="form-control" required>
                                        </div><!-- End .col-sm-6 -->
                                    </div><!-- End .row -->

                                    <label>Company Name (Optional)</label>
                                    <input type="text" name="billing_company" value="{{ old('billing_company') }}" class="form-control">

                                    <label>Country *</label>
                                    <select name="billing_country_id" id="billing_country_id" class="form-control" required style="margin-bottom: 20px;">
                                        <option value="">Select Country</option>
                                        @foreach($countries as $country)
                                            <option value="{{ $country->id }}" data-code="{{ $country->code }}">{{ $country->name }}</option>
                                        @endforeach
                                    </select>

                                    <!-- BD regions dropdowns wrapper for Billing -->
                                    <div id="billing_bd_regions_wrapper" class="d-none" style="margin-bottom: 20px;">
                                        <div class="row">
                                            <div class="col-sm-4">
                                                <label>Division *</label>
                                                <select name="billing_division_id" id="billing_division_id" class="form-control">
                                                    <option value="">Select Division</option>
                                                </select>
                                            </div>
                                            <div class="col-sm-4">
                                                <label>District *</label>
                                                <select name="billing_district_id" id="billing_district_id" class="form-control">
                                                    <option value="">Select District</option>
                                                </select>
                                            </div>
                                            <div class="col-sm-4">
                                                <label>Area *</label>
                                                <select name="billing_area_id" id="billing_area_id" class="form-control">
                                                    <option value="">Select Area</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Generic inputs for other countries for Billing -->
                                    <div id="billing_generic_regions_wrapper" class="row" style="margin-bottom: 20px;">
                                        <div class="col-sm-6">
                                            <label>Town / City *</label>
                                            <input type="text" name="billing_city" id="billing_city" value="{{ old('billing_city') }}" class="form-control" required>
                                        </div>
                                        <div class="col-sm-6">
                                            <label>State / County *</label>
                                            <input type="text" name="billing_state" id="billing_state" value="{{ old('billing_state') }}" class="form-control" required>
                                        </div>
                                    </div>

                                    <label>Street address *</label>
                                    <input type="text" name="billing_address_1" value="{{ old('billing_address_1') }}" class="form-control" placeholder="House number and Street name" required>
                                    <input type="text" name="billing_address_2" value="{{ old('billing_address_2') }}" class="form-control" placeholder="Apartment, suite, unit etc (optional)">

                                    <div class="row">
                                        <div class="col-sm-6">
                                            <label>Postcode / ZIP *</label>
                                            <input type="text" name="billing_postcode" value="{{ old('billing_postcode') }}" class="form-control" required>
                                        </div><!-- End .col-sm-6 -->

                                        <div class="col-sm-6">
                                            <label>Phone *</label>
                                            <input type="tel" name="billing_phone" value="{{ old('billing_phone') }}" class="form-control" required>
                                        </div><!-- End .col-sm-6 -->
                                    </div><!-- End .row -->

                                    <label>Email address *</label>
                                    <input type="email" name="billing_email" class="form-control" value="{{ auth()->check() ? auth()->user()->email : old('billing_email') }}" {{ auth()->check() ? 'readonly style=background-color:#eee;' : 'required' }}>

                                    <!-- Guest Checkout & Account Creation -->
                                    @if(!auth()->check())
                                        @if($guestCheckoutSetting === 'no')
                                            <div class="alert alert-warning mt-2">
                                                You must create an account to complete checkout.
                                            </div>
                                            <input type="hidden" name="create_account" value="1">
                                            <div id="account-password-wrapper" class="mt-2 mb-2">
                                                <label>Account password *</label>
                                                <input type="password" name="password" id="account_password" class="form-control" required placeholder="Create a password">
                                            </div>
                                        @elseif($accountCreationSetting === 'yes')
                                            <div class="custom-control custom-checkbox mt-2">
                                                <input type="checkbox" name="create_account" value="1" class="custom-control-input" id="checkout-create-acc">
                                                <label class="custom-control-label" for="checkout-create-acc">Create an account?</label>
                                            </div>
                                            <div id="account-password-wrapper" class="d-none mt-2 mb-2">
                                                <label>Account password *</label>
                                                <input type="password" name="password" id="account_password" class="form-control" placeholder="Create a password">
                                            </div>
                                        @endif
                                    @endif

                                    <!-- Shipping Destination Checkbox -->
                                    @if($shippingDestinationSetting !== 'billing_only')
                                        <div class="custom-control custom-checkbox mt-2">
                                            <input type="checkbox" name="ship_to_different_address" value="1" class="custom-control-input" id="checkout-diff-address" {{ $shippingDestinationSetting === 'shipping_default' ? 'checked' : '' }}>
                                            <label class="custom-control-label" for="checkout-diff-address">Ship to a different address?</label>
                                        </div>
                                    @endif

                                    <!-- Shipping Address Fields Container -->
                                    @if($shippingDestinationSetting !== 'billing_only')
                                        <div id="shipping-details-container" class="d-none mt-4">
                                            <h2 class="checkout-title">Shipping Details</h2>
                                            <div class="row">
                                                <div class="col-sm-6">
                                                    <label>First Name *</label>
                                                    <input type="text" name="shipping_first_name" value="{{ old('shipping_first_name') }}" class="form-control">
                                                </div>
                                                <div class="col-sm-6">
                                                    <label>Last Name *</label>
                                                    <input type="text" name="shipping_last_name" value="{{ old('shipping_last_name') }}" class="form-control">
                                                </div>
                                            </div>

                                            <label>Company Name (Optional)</label>
                                            <input type="text" name="shipping_company" value="{{ old('shipping_company') }}" class="form-control">

                                            <label>Country *</label>
                                            <select name="shipping_country_id" id="shipping_country_id" class="form-control" style="margin-bottom: 20px;">
                                                <option value="">Select Country</option>
                                                @foreach($countries as $country)
                                                    <option value="{{ $country->id }}" data-code="{{ $country->code }}">{{ $country->name }}</option>
                                                @endforeach
                                            </select>

                                            <!-- BD regions dropdowns wrapper for Shipping -->
                                            <div id="shipping_bd_regions_wrapper" class="d-none" style="margin-bottom: 20px;">
                                                <div class="row">
                                                    <div class="col-sm-4">
                                                        <label>Division *</label>
                                                        <select name="shipping_division_id" id="shipping_division_id" class="form-control">
                                                            <option value="">Select Division</option>
                                                        </select>
                                                    </div>
                                                    <div class="col-sm-4">
                                                        <label>District *</label>
                                                        <select name="shipping_district_id" id="shipping_district_id" class="form-control">
                                                            <option value="">Select District</option>
                                                        </select>
                                                    </div>
                                                    <div class="col-sm-4">
                                                        <label>Area *</label>
                                                        <select name="shipping_area_id" id="shipping_area_id" class="form-control">
                                                            <option value="">Select Area</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Generic inputs for other countries for Shipping -->
                                            <div id="shipping_generic_regions_wrapper" class="row" style="margin-bottom: 20px;">
                                                <div class="col-sm-6">
                                                    <label>Town / City *</label>
                                                    <input type="text" name="shipping_city" id="shipping_city" value="{{ old('shipping_city') }}" class="form-control">
                                                </div>
                                                <div class="col-sm-6">
                                                    <label>State / County *</label>
                                                    <input type="text" name="shipping_state" id="shipping_state" value="{{ old('shipping_state') }}" class="form-control">
                                                </div>
                                            </div>

                                            <label>Street address *</label>
                                            <input type="text" name="shipping_address_1" value="{{ old('shipping_address_1') }}" class="form-control" placeholder="House number and Street name">
                                            <input type="text" name="shipping_address_2" value="{{ old('shipping_address_2') }}" class="form-control" placeholder="Apartment, suite, unit etc (optional)">

                                            <div class="row">
                                                <div class="col-sm-6">
                                                    <label>Postcode / ZIP *</label>
                                                    <input type="text" name="shipping_postcode" value="{{ old('shipping_postcode') }}" class="form-control">
                                                </div>
                                                <div class="col-sm-6">
                                                    <label>Phone *</label>
                                                    <input type="tel" name="shipping_phone" value="{{ old('shipping_phone') }}" class="form-control">
                                                </div>
                                            </div>
                                        </div>
                                    @endif

                                    <div class="mt-3">
                                        <label>Order notes (optional)</label>
                                        <textarea name="order_notes" class="form-control" cols="30" rows="4" placeholder="Notes about your order, e.g. special notes for delivery">{{ old('order_notes') }}</textarea>
                                    </div>
                            </div><!-- End .col-lg-9 -->
                            <aside class="col-lg-3">
                                <div class="summary text-start">
                                    <h3 class="summary-title">Your Order</h3><!-- End .summary-title -->

                                    <table class="table table-summary">
                                        <thead>
                                            <tr>
                                                <th>Product</th>
                                                <th>Total</th>
                                            </tr>
                                        </thead>

                                        <tbody>
                                            @foreach (Cart::getContent() as $item)
                                            @php
                                                $product = App\Models\ProductModel::getSingle($item->id);
                                                $title = $product ? $product->product_title : 'Product';
                                                $slug = $product ? $product->slug : '#';
                                            @endphp
                                            <tr>
                                                <td><a href="{{ url($slug) }}">{{ $title }}</a> x {{ $item->quantity }}</td>
                                                <td>${{ number_format($item->price * $item->quantity, 2) }}</td>
                                            </tr>
                                            @endforeach
                                            <tr class="summary-subtotal">
                                                <td>Subtotal:</td>
                                                <td>${{ number_format(Cart::getSubTotal(), 2) }}</td>
                                            </tr><!-- End .summary-subtotal -->
                                            <tr>
                                                <td colspan="2" style="padding: 10px 0; border-top: none;">
                                                    <div class="input-group" style="display: flex;">
                                                        <input type="text" name="coupon_code" id="coupon_code" class="form-control" placeholder="Coupon Code" value="{{ !empty($coupon) ? $coupon->code : '' }}" style="height: 40px; margin-bottom: 0;">
                                                        <div class="input-group-append">
                                                            <button type="button" id="apply_coupon" class="btn btn-outline-primary-2" style="height: 40px; min-width: 80px; padding: 0;">Apply</button>
                                                        </div>
                                                    </div>
                                                    <div id="coupon-message" style="margin-top: 5px; font-weight: 500; font-size: 1.2rem; display: none;"></div>
                                                </td>
                                            </tr>
                                            <tr class="summary-subtotal">
                                                <td>Discount:</td>
                                                <td id="checkout-discount">-${{ number_format($discount, 2) }}</td>
                                            </tr><!-- End .summary-total -->
                                            <tr class="summary-shipping">
                                                <td>Shipping:</td>
                                                <td id="checkout-shipping-options" style="padding: 10px 0;">
                                                    @if($shippingRate)
                                                        <div class="custom-control custom-radio">
                                                            <input type="radio" id="selected-rate-session" name="shipping_rate_id" value="{{ $shippingRate['rate_id'] }}" class="custom-control-input" checked>
                                                            <label class="custom-control-label" for="selected-rate-session">
                                                                {{ $shippingRate['method_name'] }}: ${{ number_format($shippingRate['charge'], 2) }}
                                                            </label>
                                                        </div>
                                                    @else
                                                        <span class="text-muted" style="font-size: 1.2rem;">Select location to calculate shipping</span>
                                                    @endif
                                                </td>
                                            </tr>
                                            <tr class="summary-total">
                                                <td>Total:</td>
                                                <td id="checkout-total">${{ number_format($total, 2) }}</td>
                                            </tr><!-- End .summary-total -->
                                        </tbody>
                                    </table><!-- End .table table-summary -->

                                    <div class="accordion-summary text-start mb-3" id="accordion-payment">
                                        @php $first = true; @endphp
                                        @foreach($payment_gateways as $gateway)
                                            @if($gateway->status === 'yes')
                                                @php $gatewayKey = $gateway->gateway_key; @endphp
                                                <div class="card">
                                                    <div class="card-header" id="heading-{{ $gatewayKey }}">
                                                        <h2 class="card-title">
                                                            <a role="button" data-toggle="collapse" href="#collapse-{{ $gatewayKey }}" aria-expanded="@if($first) true @else false @endif" aria-controls="collapse-{{ $gatewayKey }}" class="@if(!$first) collapsed @endif d-block">
                                                                {{ $gateway->name }}
                                                                <input type="radio" id="payment_{{ $gatewayKey }}" name="payment_method" value="{{ $gatewayKey }}" style="display: none;" @if($first) checked @endif>
                                                            </a>
                                                        </h2>
                                                    </div>
                                                    <div id="collapse-{{ $gatewayKey }}" class="collapse @if($first) show @endif" aria-labelledby="heading-{{ $gatewayKey }}" data-parent="#accordion-payment">
                                                        <div class="card-body">
                                                            {{ $gateway->description }}
                                                            
                                                             @if($gatewayKey === 'stripe')
                                                                 <!-- Stripe Card Elements Form -->
                                                                 <div id="stripe-card-element-container" class="mt-3" style="display: none;">
                                                                     <div class="form-group mb-2">
                                                                         <label for="card-number-element" style="font-weight: 600; font-size: 1.3rem;">Card Number</label>
                                                                         <div id="card-number-element" class="form-control" style="padding: 12px; height: 40px; border: 1px solid #ccc; background: white; border-radius: 4px;"></div>
                                                                     </div>
                                                                     <div class="row">
                                                                         <div class="col-md-6">
                                                                             <div class="form-group mb-2">
                                                                                 <label for="card-expiry-element" style="font-weight: 600; font-size: 1.3rem;">Expiration Date</label>
                                                                                 <div id="card-expiry-element" class="form-control" style="padding: 12px; height: 40px; border: 1px solid #ccc; background: white; border-radius: 4px;"></div>
                                                                             </div>
                                                                         </div>
                                                                         <div class="col-md-6">
                                                                             <div class="form-group mb-2">
                                                                                 <label for="card-cvc-element" style="font-weight: 600; font-size: 1.3rem;">Card CVC / CVV</label>
                                                                                 <div id="card-cvc-element" class="form-control" style="padding: 12px; height: 40px; border: 1px solid #ccc; background: white; border-radius: 4px;"></div>
                                                                             </div>
                                                                         </div>
                                                                     </div>
                                                                     <!-- Used to display form errors. -->
                                                                     <div id="card-errors" role="alert" class="text-danger mt-2" style="font-size: 1.3rem;"></div>
                                                                 </div>
                                                             @endif

                                                             @if($gatewayKey === 'authorizenet')
                                                                 <!-- Authorize.Net Card Form -->
                                                                 <div id="authorizenet-card-container" class="mt-3" style="display: none; width: 100%;">
                                                                     <div style="margin-bottom: 10px; width: 100%;">
                                                                         <label style="font-weight: 600; font-size: 1.3rem; display: block; margin-bottom: 5px; text-align: left;">Card Number</label>
                                                                         <input type="text" name="authorizenet_card_number" class="form-control" placeholder="1111 2222 3333 4444" style="height: 40px; width: 100%; border: 1px solid #ccc; padding: 10px; border-radius: 4px; box-sizing: border-box;">
                                                                     </div>
                                                                     <div style="display: flex; gap: 15px; width: 100%; box-sizing: border-box;">
                                                                         <div style="flex: 1;">
                                                                             <label style="font-weight: 600; font-size: 1.3rem; display: block; margin-bottom: 5px; text-align: left;">Expiration (MM/YY)</label>
                                                                             <input type="text" name="authorizenet_card_expiry" class="form-control" placeholder="MM/YY" style="height: 40px; width: 100%; border: 1px solid #ccc; padding: 10px; border-radius: 4px; box-sizing: border-box;">
                                                                         </div>
                                                                         <div style="flex: 1;">
                                                                             <label style="font-weight: 600; font-size: 1.3rem; display: block; margin-bottom: 5px; text-align: left;">Card CVC / CVV</label>
                                                                             <input type="text" name="authorizenet_card_code" class="form-control" placeholder="123" style="height: 40px; width: 100%; border: 1px solid #ccc; padding: 10px; border-radius: 4px; box-sizing: border-box;">
                                                                         </div>
                                                                     </div>
                                                                 </div>
                                                             @endif

                                                             @if($gatewayKey === 'square')
                                                                 <!-- Square Card Form -->
                                                                 <div id="square-card-container" class="mt-3" style="display: none; width: 100%;">
                                                                     <div style="margin-bottom: 10px; width: 100%;">
                                                                         <label style="font-weight: 600; font-size: 1.3rem; display: block; margin-bottom: 5px; text-align: left;">Card Number</label>
                                                                         <input type="text" name="square_card_number" class="form-control" placeholder="1111 2222 3333 4444" style="height: 40px; width: 100%; border: 1px solid #ccc; padding: 10px; border-radius: 4px; box-sizing: border-box;">
                                                                     </div>
                                                                     <div style="display: flex; gap: 15px; width: 100%; box-sizing: border-box;">
                                                                         <div style="flex: 1;">
                                                                             <label style="font-weight: 600; font-size: 1.3rem; display: block; margin-bottom: 5px; text-align: left;">Expiration (MM/YY)</label>
                                                                             <input type="text" name="square_card_expiry" class="form-control" placeholder="MM/YY" style="height: 40px; width: 100%; border: 1px solid #ccc; padding: 10px; border-radius: 4px; box-sizing: border-box;">
                                                                         </div>
                                                                         <div style="flex: 1;">
                                                                             <label style="font-weight: 600; font-size: 1.3rem; display: block; margin-bottom: 5px; text-align: left;">Card CVC / CVV</label>
                                                                             <input type="text" name="square_card_code" class="form-control" placeholder="123" style="height: 40px; width: 100%; border: 1px solid #ccc; padding: 10px; border-radius: 4px; box-sizing: border-box;">
                                                                         </div>
                                                                     </div>
                                                                 </div>
                                                             @endif

                                                            @if($gatewayKey === 'paypal')
                                                                <!-- PayPal Button Container -->
                                                                <div id="paypal-button-container" class="mt-3" style="display: none;"></div>
                                                                <div id="paypal-errors" class="text-danger mt-2" style="font-size: 1.3rem;"></div>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                                @php $first = false; @endphp
                                            @endif
                                        @endforeach
                                    </div>

                                    @if ($errors->any())
                                        <div class="alert alert-danger" style="font-size: 1.3rem;">
                                            <ul class="mb-0" style="padding-left: 15px;">
                                                @foreach ($errors->all() as $error)
                                                    <li>{{ $error }}</li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    @endif

                                    <div id="place-order-btn-wrapper">
                                        <button type="submit" class="btn btn-outline-primary-2 btn-order btn-block">
                                            <span class="btn-text">Place Order</span>
                                            <span class="btn-hover-text">Proceed to Checkout</span>
                                        </button>
                                    </div>
                                </div><!-- End .summary -->
                            </aside><!-- End .col-lg-3 -->
                        </div><!-- End .row -->
                    </form>
                </div><!-- End .container -->
            </div><!-- End .checkout -->
        </div><!-- End .page-content -->
    </main><!-- End .main -->

@endsection
@section('script')
@php
    $stripeGateway = $payment_gateways->where('gateway_key', 'stripe')->first();
    $stripeActive = $stripeGateway && $stripeGateway->status === 'yes';
    
    $paypalGateway = $payment_gateways->where('gateway_key', 'paypal')->first();
    $paypalActive = $paypalGateway && $paypalGateway->status === 'yes';
    $paypalClientId = $paypalGateway ? $paypalGateway->public_key : '';
@endphp

@if($stripeActive && !empty($stripe_public_key))
<script src="https://js.stripe.com/v3/"></script>
@endif

@if($paypalActive && !empty($paypalClientId))
<script src="https://www.paypal.com/sdk/js?client-id={{ $paypalClientId }}&currency=USD"></script>
@endif

@if(!empty($razorpay_public_key) && $razorpay_active)
<script src="https://checkout.razorpay.com/v1/checkout.js"></script>
@endif

@if(!empty($paystack_public_key) && $paystack_active)
<script src="https://js.paystack.co/v1/inline.js"></script>
@endif

<script>
    $(document).ready(function() {
        // Toggle payment elements display on change of payment method radio
        $(document).on('change', 'input[name="payment_method"]', function() {
            var selectedMethod = $(this).val();
            if (selectedMethod === 'stripe') {
                $('#stripe-card-element-container').slideDown();
            } else {
                $('#stripe-card-element-container').slideUp();
            }

            if (selectedMethod === 'authorizenet') {
                $('#authorizenet-card-container').slideDown();
            } else {
                $('#authorizenet-card-container').slideUp();
            }

            if (selectedMethod === 'square') {
                $('#square-card-container').slideDown();
            } else {
                $('#square-card-container').slideUp();
            }

            if (selectedMethod === 'paypal') {
                if (typeof paypal !== 'undefined') {
                    $('#place-order-btn-wrapper').hide();
                    $('#paypal-button-container').slideDown();
                    $('#paypal-errors').hide();
                } else {
                    $('#place-order-btn-wrapper').show();
                    $('#paypal-button-container').hide();
                    $('#paypal-errors').text('PayPal SDK could not be loaded. Please ensure your Client ID is valid or use another payment method.').show();
                }
            } else {
                $('#paypal-errors').hide();
                $('#paypal-button-container').slideUp();
                $('#place-order-btn-wrapper').show();
            }
        });

        // Trigger on load
        var activeMethod = $('input[name="payment_method"]:checked').val();
        if (activeMethod === 'stripe') {
            $('#stripe-card-element-container').show();
        }
        if (activeMethod === 'authorizenet') {
            $('#authorizenet-card-container').show();
        }
        if (activeMethod === 'square') {
            $('#square-card-container').show();
        }
        if (activeMethod === 'paypal') {
            if (typeof paypal !== 'undefined') {
                $('#place-order-btn-wrapper').hide();
                $('#paypal-button-container').show();
            } else {
                $('#place-order-btn-wrapper').show();
                $('#paypal-button-container').hide();
                $('#paypal-errors').text('PayPal SDK could not be loaded. Please ensure your Client ID is valid or use another payment method.').show();
            }
        }

        // When a collapse panel starts showing, check its corresponding radio button!
        $(document).on('show.bs.collapse', '.collapse', function () {
            var gatewayKey = $(this).attr('id').replace('collapse-', '');
            $('#payment_' + gatewayKey).prop('checked', true).trigger('change');
        });
        // Toggle account creation password input
        $(document).on('change', '#checkout-create-acc', function() {
            if ($(this).is(':checked')) {
                $('#account-password-wrapper').removeClass('d-none');
                $('#account_password').prop('required', true);
            } else {
                $('#account-password-wrapper').addClass('d-none');
                $('#account_password').prop('required', false).val('');
            }
        });

        // Toggle Shipping details form container
        function toggleShippingForm() {
            let isChecked = $('#checkout-diff-address').is(':checked');
            if (isChecked) {
                $('#shipping-details-container').removeClass('d-none');
                // Set shipping inputs required
                $('#shipping_first_name').prop('required', true);
                $('#shipping_last_name').prop('required', true);
                $('#shipping_country_id').prop('required', true);
                $('#shipping_address_1').prop('required', true);
                $('#shipping_postcode').prop('required', true);
                
                // Set billing regions required flags dynamically
                let countryCode = $('#shipping_country_id').find(':selected').data('code');
                if (countryCode === 'BD') {
                    $('#shipping_division_id').prop('required', true);
                    $('#shipping_district_id').prop('required', true);
                    $('#shipping_city').prop('required', false);
                    $('#shipping_state').prop('required', false);
                } else if ($('#shipping_country_id').val() !== '') {
                    $('#shipping_division_id').prop('required', false);
                    $('#shipping_district_id').prop('required', false);
                    $('#shipping_city').prop('required', true);
                    $('#shipping_state').prop('required', true);
                }
            } else {
                $('#shipping-details-container').addClass('d-none');
                // Clear shipping fields & turn off required
                $('#shipping_first_name').prop('required', false).val('');
                $('#shipping_last_name').prop('required', false).val('');
                $('#shipping_country_id').prop('required', false).val('');
                $('#shipping_address_1').prop('required', false).val('');
                $('#shipping_postcode').prop('required', false).val('');
                $('#shipping_division_id').prop('required', false).html('<option value="">Select Division</option>');
                $('#shipping_district_id').prop('required', false).html('<option value="">Select District</option>');
                $('#shipping_area_id').prop('required', false).html('<option value="">Select Area</option>');
                $('#shipping_city').prop('required', false).val('');
                $('#shipping_state').prop('required', false).val('');
            }
            
            // Re-calculate shipping rates because the shipping location might have changed
            getShippingRates();
        }

        $(document).on('change', '#checkout-diff-address', function() {
            toggleShippingForm();
        });

        // Trigger on load to match initial checked state
        if ($('#checkout-diff-address').length > 0) {
            toggleShippingForm();
        }

        // ==========================================
        // Billing Locations AJAX Handlers
        // ==========================================
        $('#billing_country_id').on('change', function() {
            let countryId = $(this).val();
            let countryCode = $(this).find(':selected').data('code');
            
            $('#billing_division_id').html('<option value="">Select Division</option>');
            $('#billing_district_id').html('<option value="">Select District</option>');
            $('#billing_area_id').html('<option value="">Select Area</option>');
            
            if (countryCode === 'BD') { // Bangladesh
                $('#billing_bd_regions_wrapper').removeClass('d-none');
                $('#billing_division_id').prop('required', true);
                $('#billing_district_id').prop('required', true);
                
                $('#billing_generic_regions_wrapper').addClass('d-none');
                $('#billing_city').prop('required', false).val('');
                $('#billing_state').prop('required', false).val('');
                
                $.ajax({
                    url: "{{ url('locations/divisions') }}/" + countryId,
                    method: "GET",
                    success: function(data) {
                        let options = '<option value="">Select Division</option>';
                        data.forEach(function(item) {
                            options += `<option value="${item.id}">${item.name}</option>`;
                        });
                        $('#billing_division_id').html(options);
                    }
                });
            } else { // Other Countries
                $('#billing_bd_regions_wrapper').addClass('d-none');
                $('#billing_division_id').prop('required', false);
                $('#billing_district_id').prop('required', false);
                
                $('#billing_generic_regions_wrapper').removeClass('d-none');
                $('#billing_city').prop('required', true);
                $('#billing_state').prop('required', true);
                
                if (countryId && !$('#checkout-diff-address').is(':checked')) {
                    getShippingRates();
                }
            }
        });

        $('#billing_division_id').on('change', function() {
            let divisionId = $(this).val();
            $('#billing_district_id').html('<option value="">Select District</option>');
            $('#billing_area_id').html('<option value="">Select Area</option>');
            
            if (divisionId) {
                $.ajax({
                    url: "{{ url('locations/districts') }}/" + divisionId,
                    method: "GET",
                    success: function(data) {
                        let options = '<option value="">Select District</option>';
                        data.forEach(function(item) {
                            options += `<option value="${item.id}">${item.name}</option>`;
                        });
                        $('#billing_district_id').html(options);
                    }
                });
            }
        });

        $('#billing_district_id').on('change', function() {
            let districtId = $(this).val();
            $('#billing_area_id').html('<option value="">Select Area</option>');
            
            if (districtId) {
                $.ajax({
                    url: "{{ url('locations/areas') }}/" + districtId,
                    method: "GET",
                    success: function(data) {
                        let options = '<option value="">Select Area</option>';
                        data.forEach(function(item) {
                            options += `<option value="${item.id}">${item.name}</option>`;
                        });
                        $('#billing_area_id').html(options);
                        
                        if (!$('#checkout-diff-address').is(':checked')) {
                            getShippingRates();
                        }
                    }
                });
            }
        });

        $('#billing_area_id').on('change', function() {
            if (!$('#checkout-diff-address').is(':checked')) {
                getShippingRates();
            }
        });


        // ==========================================
        // Shipping Locations AJAX Handlers
        // ==========================================
        $(document).on('change', '#shipping_country_id', function() {
            let countryId = $(this).val();
            let countryCode = $(this).find(':selected').data('code');
            
            $('#shipping_division_id').html('<option value="">Select Division</option>');
            $('#shipping_district_id').html('<option value="">Select District</option>');
            $('#shipping_area_id').html('<option value="">Select Area</option>');
            
            if (countryCode === 'BD') { // Bangladesh
                $('#shipping_bd_regions_wrapper').removeClass('d-none');
                $('#shipping_division_id').prop('required', true);
                $('#shipping_district_id').prop('required', true);
                
                $('#shipping_generic_regions_wrapper').addClass('d-none');
                $('#shipping_city').prop('required', false).val('');
                $('#shipping_state').prop('required', false).val('');
                
                $.ajax({
                    url: "{{ url('locations/divisions') }}/" + countryId,
                    method: "GET",
                    success: function(data) {
                        let options = '<option value="">Select Division</option>';
                        data.forEach(function(item) {
                            options += `<option value="${item.id}">${item.name}</option>`;
                        });
                        $('#shipping_division_id').html(options);
                    }
                });
            } else { // Other Countries
                $('#shipping_bd_regions_wrapper').addClass('d-none');
                $('#shipping_division_id').prop('required', false);
                $('#shipping_district_id').prop('required', false);
                
                $('#shipping_generic_regions_wrapper').removeClass('d-none');
                $('#shipping_city').prop('required', true);
                $('#shipping_state').prop('required', true);
                
                if (countryId && $('#checkout-diff-address').is(':checked')) {
                    getShippingRates();
                }
            }
        });

        $(document).on('change', '#shipping_division_id', function() {
            let divisionId = $(this).val();
            $('#shipping_district_id').html('<option value="">Select District</option>');
            $('#shipping_area_id').html('<option value="">Select Area</option>');
            
            if (divisionId) {
                $.ajax({
                    url: "{{ url('locations/districts') }}/" + divisionId,
                    method: "GET",
                    success: function(data) {
                        let options = '<option value="">Select District</option>';
                        data.forEach(function(item) {
                            options += `<option value="${item.id}">${item.name}</option>`;
                        });
                        $('#shipping_district_id').html(options);
                    }
                });
            }
        });

        $(document).on('change', '#shipping_district_id', function() {
            let districtId = $(this).val();
            $('#shipping_area_id').html('<option value="">Select Area</option>');
            
            if (districtId) {
                $.ajax({
                    url: "{{ url('locations/areas') }}/" + districtId,
                    method: "GET",
                    success: function(data) {
                        let options = '<option value="">Select Area</option>';
                        data.forEach(function(item) {
                            options += `<option value="${item.id}">${item.name}</option>`;
                        });
                        $('#shipping_area_id').html(options);
                        
                        if ($('#checkout-diff-address').is(':checked')) {
                            getShippingRates();
                        }
                    }
                });
            }
        });

        $(document).on('change', '#shipping_area_id', function() {
            if ($('#checkout-diff-address').is(':checked')) {
                getShippingRates();
            }
        });


        // ==========================================
        // AJAX Shipping Rates Calculator
        // ==========================================
        function getShippingRates() {
            let isDiffAddress = $('#checkout-diff-address').is(':checked');
            
            let countryId = isDiffAddress ? $('#shipping_country_id').val() : $('#billing_country_id').val();
            let divisionId = isDiffAddress ? $('#shipping_division_id').val() : $('#billing_division_id').val();
            let districtId = isDiffAddress ? $('#shipping_district_id').val() : $('#billing_district_id').val();
            let areaId = isDiffAddress ? $('#shipping_area_id').val() : $('#billing_area_id').val();
            
            if (!countryId) {
                $('#checkout-shipping-options').html('<span class="text-muted" style="font-size: 1.2rem;">Select location to calculate shipping</span>');
                return;
            }

            $('#checkout-shipping-options').html('<span class="text-muted">Calculating shipping...</span>');

            $.ajax({
                url: "{{ route('checkout.shipping_rates') }}",
                method: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    country_id: countryId,
                    division_id: divisionId,
                    district_id: districtId,
                    area_id: areaId
                },
                dataType: "json",
                success: function(response) {
                    if (response.status && response.rates.length > 0) {
                        let html = '';
                        response.rates.forEach(function(rate, index) {
                            let label = `${rate.method_name}: $${parseFloat(rate.charge).toFixed(2)}`;
                            if (rate.estimated_days) {
                                label += ` (${rate.estimated_days})`;
                            }
                            html += `
                                <div class="custom-control custom-radio" style="margin-bottom: 5px;">
                                    <input type="radio" id="rate_${rate.rate_id}" name="shipping_rate_id" value="${rate.rate_id}" class="custom-control-input shipping-rate-radio" data-rate-id="${rate.rate_id}" data-charge="${rate.charge}" data-name="${rate.method_name}" ${index === 0 ? 'checked' : ''}>
                                    <label class="custom-control-label" for="rate_${rate.rate_id}">${label}</label>
                                </div>
                            `;
                        });
                        $('#checkout-shipping-options').html(html);
                        
                        // Auto-select the first option
                        let firstRate = response.rates[0];
                        selectShippingRate(firstRate.rate_id, firstRate.charge, firstRate.method_name);
                    } else {
                        $('#checkout-shipping-options').html('<span class="text-danger" style="font-size: 1.1rem;">No shipping options available for this region.</span>');
                    }
                },
                error: function(xhr) {
                    console.error("Shipping calculation error:", xhr);
                    $('#checkout-shipping-options').html('<span class="text-danger">Failed to calculate shipping.</span>');
                }
            });
        }

        // Handle shipping rate option selection
        $(document).on('change', '.shipping-rate-radio', function() {
            let rateId = $(this).val();
            let charge = $(this).data('charge');
            let name = $(this).data('name');
            selectShippingRate(rateId, charge, name);
        });

        // AJAX to Save Shipping Choice and update final checkout card totals
        function selectShippingRate(rateId, charge, name) {
            $.ajax({
                url: "{{ route('checkout.select_shipping_rate') }}",
                method: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    rate_id: rateId,
                    charge: charge,
                    method_name: name
                },
                dataType: "json",
                success: function(response) {
                    if (response.status) {
                        $('#checkout-total').text('$' + response.total);
                    }
                }
            });
        }

        // Apply Coupon Code
        $('#apply_coupon').on('click', function() {
            let couponCode = $('#coupon_code').val().trim();
            let $messageDiv = $('#coupon-message');
            
            if (couponCode === '') {
                $messageDiv.text('Please enter a coupon code.').css('color', 'red').show();
                return;
            }
            
            $.ajax({
                url: "{{ route('coupon.apply') }}",
                method: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    coupon_code: couponCode
                },
                dataType: "json",
                success: function(response) {
                    if (response.status) {
                        $messageDiv.text(response.message).css('color', 'green').show();
                        $('#checkout-discount').text('-$' + response.discount);
                        $('#checkout-total').text('$' + response.total);
                    } else {
                        $messageDiv.text(response.message).css('color', 'red').show();
                    }
                },
                error: function(xhr) {
                    console.error("Coupon apply error:", xhr);
                    $messageDiv.text('An error occurred. Please try again.').css('color', 'red').show();
                }
            });
        });

        @if(!empty($stripe_public_key) && $stripeActive)
            try {
                if (typeof Stripe !== 'undefined') {
                    var stripe = Stripe('{{ $stripe_public_key }}');
                    var elements = stripe.elements();
                    var style = {
                        base: {
                            color: '#32325d',
                            fontFamily: '"Helvetica Neue", Helvetica, sans-serif',
                            fontSmoothing: 'antialiased',
                            fontSize: '16px',
                            '::placeholder': {
                                color: '#aab7c4'
                            }
                        },
                        invalid: {
                            color: '#fa755a',
                            iconColor: '#fa755a'
                        }
                    };
                    var cardNumber = elements.create('cardNumber', {style: style});
                    cardNumber.mount('#card-number-element');

                    var cardExpiry = elements.create('cardExpiry', {style: style});
                    cardExpiry.mount('#card-expiry-element');

                    var cardCvc = elements.create('cardCvc', {style: style});
                    cardCvc.mount('#card-cvc-element');

                    function handleErrors(event) {
                        var displayError = document.getElementById('card-errors');
                        if (event.error) {
                            displayError.textContent = event.error.message;
                        } else {
                            displayError.textContent = '';
                        }
                    }

                    cardNumber.on('change', handleErrors);
                    cardExpiry.on('change', handleErrors);
                    cardCvc.on('change', handleErrors);
                } else {
                    console.error("Stripe.js was not loaded.");
                    $('#card-errors').text('Stripe.js failed to load. Please check your internet connection.').show();
                }
            } catch (err) {
                console.error("Stripe initialization error:", err);
                $('#card-errors').text('Stripe error: ' + err.message).show();
            }
        @endif

        $('#checkout-form').on('submit', function(e) {
            var selectedMethod = $('input[name="payment_method"]:checked').val();
            
            // Get current total from the text (strip dollar sign)
            var totalText = $('#checkout-total').text().replace('$', '').trim();
            var totalVal = parseFloat(totalText);
            if (isNaN(totalVal) || totalVal <= 0) {
                totalVal = {{ round($total, 2) }};
            }

            if (selectedMethod === 'stripe') {
                e.preventDefault();
                
                var submitBtn = $(this).find('button[type="submit"]');
                submitBtn.prop('disabled', true).text('Processing Payment...');

                if (typeof stripe !== 'undefined' && typeof cardNumber !== 'undefined') {
                    stripe.createToken(cardNumber).then(function(result) {
                        if (result.error) {
                            var displayError = document.getElementById('card-errors');
                            displayError.textContent = result.error.message;
                            submitBtn.prop('disabled', false).text('Place Order');
                        } else {
                            var form = document.getElementById('checkout-form');
                            var tokenInput = $('input[name="stripeToken"]');
                            if (tokenInput.length > 0) {
                                tokenInput.val(result.token.id);
                            } else {
                                var hiddenInput = document.createElement('input');
                                hiddenInput.setAttribute('type', 'hidden');
                                hiddenInput.setAttribute('name', 'stripeToken');
                                hiddenInput.setAttribute('value', result.token.id);
                                form.appendChild(hiddenInput);
                            }
                            form.submit();
                        }
                    });
                } else {
                    $('#card-errors').text('Stripe is not properly initialized. Please try another payment method.').show();
                    submitBtn.prop('disabled', false).text('Place Order');
                }
            }

            if (selectedMethod === 'razorpay') {
                if ($('input[name="razorpay_payment_id"]').length > 0) {
                    return true;
                }
                
                e.preventDefault();
                
                if (typeof Razorpay !== 'undefined') {
                    var options = {
                        "key": "{{ $razorpay_public_key }}",
                        "amount": (totalVal * 100).toFixed(0),
                        "currency": "INR",
                        "name": "{{ config('app.name', 'Molla E-commerce') }}",
                        "description": "Order checkout payment",
                        "handler": function (response){
                            var form = document.getElementById('checkout-form');
                            var hiddenInput = document.createElement('input');
                            hiddenInput.setAttribute('type', 'hidden');
                            hiddenInput.setAttribute('name', 'razorpay_payment_id');
                            hiddenInput.setAttribute('value', response.razorpay_payment_id);
                            form.appendChild(hiddenInput);
                            form.submit();
                        },
                        "prefill": {
                            "name": $('input[name="billing_first_name"]').val() + " " + $('input[name="billing_last_name"]').val(),
                            "email": $('input[name="billing_email"]').val(),
                            "contact": $('input[name="billing_phone"]').val()
                        },
                        "theme": {
                            "color": "#c96"
                        }
                    };
                    var rzp1 = new Razorpay(options);
                    rzp1.open();
                } else {
                    alert('Razorpay SDK could not be loaded. Please try another payment method.');
                }
            }

            if (selectedMethod === 'paystack') {
                if ($('input[name="paystack_reference"]').length > 0) {
                    return true;
                }
                
                e.preventDefault();
                
                if (typeof PaystackPop !== 'undefined') {
                    var handler = PaystackPop.setup({
                        key: '{{ $paystack_public_key }}',
                        email: $('input[name="billing_email"]').val(),
                        amount: (totalVal * 100).toFixed(0),
                        currency: 'NGN',
                        callback: function(response){
                            var form = document.getElementById('checkout-form');
                            var hiddenInput = document.createElement('input');
                            hiddenInput.setAttribute('type', 'hidden');
                            hiddenInput.setAttribute('name', 'paystack_reference');
                            hiddenInput.setAttribute('value', response.reference);
                            form.appendChild(hiddenInput);
                            form.submit();
                        },
                        onClose: function(){
                            alert('Transaction was not completed.');
                        }
                    });
                    handler.openIframe();
                } else {
                    alert('Paystack SDK could not be loaded. Please try another payment method.');
                }
            }
        });

        @if($paypalActive && !empty($paypalClientId))
            if (typeof paypal !== 'undefined') {
                paypal.Buttons({
                    createOrder: function(data, actions) {
                        // Get current total from the text (strip dollar sign)
                        var totalText = $('#checkout-total').text().replace('$', '').trim();
                        var totalVal = parseFloat(totalText);
                        if (isNaN(totalVal) || totalVal <= 0) {
                            totalVal = {{ round($total, 2) }};
                        }
                        return actions.order.create({
                            purchase_units: [{
                                amount: {
                                    value: totalVal.toFixed(2)
                                }
                            }]
                        });
                    },
                    onApprove: function(data, actions) {
                        return actions.order.capture().then(function(details) {
                            var form = document.getElementById('checkout-form');
                            
                            var tokenInput = document.createElement('input');
                            tokenInput.setAttribute('type', 'hidden');
                            tokenInput.setAttribute('name', 'paypal_order_id');
                            tokenInput.setAttribute('value', data.orderID);
                            form.appendChild(tokenInput);

                            var paymentInput = document.createElement('input');
                            paymentInput.setAttribute('type', 'hidden');
                            paymentInput.setAttribute('name', 'paypal_payment_id');
                            paymentInput.setAttribute('value', details.purchase_units[0].payments.captures[0].id);
                            form.appendChild(paymentInput);

                            form.submit();
                        });
                    },
                    onError: function(err) {
                        var errorDiv = document.getElementById('paypal-errors');
                        errorDiv.textContent = 'PayPal Payment Error: ' + err.toString();
                    }
                }).render('#paypal-button-container');
            } else {
                console.error("PayPal SDK could not be loaded.");
            }
        @endif
    });
</script>
@endsection