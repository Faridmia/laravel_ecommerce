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

                    <form action="#">
                        <div class="row">
                            <div class="col-lg-9">
                                <h2 class="checkout-title">Billing Details</h2><!-- End .checkout-title -->
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <label>First Name *</label>
                                            <input type="text" class="form-control" required>
                                        </div><!-- End .col-sm-6 -->

                                        <div class="col-sm-6">
                                            <label>Last Name *</label>
                                            <input type="text" class="form-control" required>
                                        </div><!-- End .col-sm-6 -->
                                    </div><!-- End .row -->

                                    <label>Company Name (Optional)</label>
                                    <input type="text" class="form-control">

                                    <label>Country *</label>
                                    <input type="text" class="form-control" required>

                                    <label>Street address *</label>
                                    <input type="text" class="form-control" placeholder="House number and Street name" required>
                                    <input type="text" class="form-control" placeholder="Appartments, suite, unit etc ..." required>

                                    <div class="row">
                                        <div class="col-sm-6">
                                            <label>Town / City *</label>
                                            <input type="text" class="form-control" required>
                                        </div><!-- End .col-sm-6 -->

                                        <div class="col-sm-6">
                                            <label>State / County *</label>
                                            <input type="text" class="form-control" required>
                                        </div><!-- End .col-sm-6 -->
                                    </div><!-- End .row -->

                                    <div class="row">
                                        <div class="col-sm-6">
                                            <label>Postcode / ZIP *</label>
                                            <input type="text" class="form-control" required>
                                        </div><!-- End .col-sm-6 -->

                                        <div class="col-sm-6">
                                            <label>Phone *</label>
                                            <input type="tel" class="form-control" required>
                                        </div><!-- End .col-sm-6 -->
                                    </div><!-- End .row -->

                                    <label>Email address *</label>
                                    <input type="email" class="form-control" required>

                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input" id="checkout-create-acc">
                                        <label class="custom-control-label" for="checkout-create-acc">Create an account?</label>
                                    </div><!-- End .custom-checkbox -->

                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input" id="checkout-diff-address">
                                        <label class="custom-control-label" for="checkout-diff-address">Ship to a different address?</label>
                                    </div><!-- End .custom-checkbox -->

                                    <label>Order notes (optional)</label>
                                    <textarea class="form-control" cols="30" rows="4" placeholder="Notes about your order, e.g. special notes for delivery"></textarea>
                            </div><!-- End .col-lg-9 -->
                            <aside class="col-lg-3">
                                <div class="summary">
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
                                                <td><a href="{{ url($slug) }}">{{ $title }}</a></td>
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
                                            <tr>
                                                <td>Shipping:</td>
                                                <td>Free shipping</td>
                                            </tr>
                                            <tr class="summary-total">
                                                <td>Total:</td>
                                                <td id="checkout-total">${{ number_format($total, 2) }}</td>
                                            </tr><!-- End .summary-total -->
                                        </tbody>
                                    </table><!-- End .table table-summary -->

                                    <div class="accordion-summary" id="accordion-payment">
                                       
                                        <div class="card">
                                            <div class="card-header" id="heading-3">
                                                <h2 class="card-title">
                                                    <a class="collapsed" role="button" data-toggle="collapse" href="#collapse-3" aria-expanded="false" aria-controls="collapse-3">
                                                        Cash on delivery
                                                    </a>
                                                </h2>
                                            </div><!-- End .card-header -->
                                            <div id="collapse-3" class="collapse" aria-labelledby="heading-3" data-parent="#accordion-payment">
                                                <div class="card-body">Quisque volutpat mattis eros. Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Donec odio. Quisque volutpat mattis eros. 
                                                </div><!-- End .card-body -->
                                            </div><!-- End .collapse -->
                                        </div><!-- End .card -->

                                        <!-- <div class="card">
                                            <div class="card-header" id="heading-4">
                                                <h2 class="card-title">
                                                    <a class="collapsed" role="button" data-toggle="collapse" href="#collapse-4" aria-expanded="false" aria-controls="collapse-4">
                                                        PayPal <small class="float-right paypal-link">What is PayPal?</small>
                                                    </a>
                                                </h2>
                                            </div>
                                            <div id="collapse-4" class="collapse" aria-labelledby="heading-4" data-parent="#accordion-payment">
                                                <div class="card-body">
                                                    Nullam malesuada erat ut turpis. Suspendisse urna nibh, viverra non, semper suscipit, posuere a, pede. Donec nec justo eget felis facilisis fermentum.
                                                </div>
                                            </div>
                                        </div> -->

                                        <!-- <div class="card">
                                            <div class="card-header" id="heading-5">
                                                <h2 class="card-title">
                                                    <a class="collapsed" role="button" data-toggle="collapse" href="#collapse-5" aria-expanded="false" aria-controls="collapse-5">
                                                        Credit Card (Stripe)
                                                        <img src="assets/images/payments-summary.png" alt="payments cards">
                                                    </a>
                                                </h2>
                                            </div>
                                            <div id="collapse-5" class="collapse" aria-labelledby="heading-5" data-parent="#accordion-payment">
                                                <div class="card-body"> Donec nec justo eget felis facilisis fermentum.Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Donec odio. Quisque volutpat mattis eros. Lorem ipsum dolor sit ame.
                                                </div>
                                            </div>
                                        </div> -->
                                    </div>

                                    <button type="submit" class="btn btn-outline-primary-2 btn-order btn-block">
                                        <span class="btn-text">Place Order</span>
                                        <span class="btn-hover-text">Proceed to Checkout</span>
                                    </button>
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
<script>
    $(document).ready(function() {
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
    });
</script>
@endsection