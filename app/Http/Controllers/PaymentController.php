<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Cart;
use App\Models\ProductModel;
use App\Models\ProductSizeModel;
use App\Models\ProductImageModel;
class PaymentController extends Controller
{
    //

    public function cart( Request $request )
    {
        if (Cart::getContent()->isEmpty()) {
            session()->forget('coupon');
            session()->forget('shipping_rate');
        }
        $data['meta_title'] = "Cart";
        $data['meta_description'] = '';
        $data['meta_keywords'] = '';
        return view('payment.cart', $data);
    }

    public function addToCart(Request $request)
    {
       $getProduct = ProductModel::getSingle($request->product_id);

       $total = $getProduct->price;
       if (!empty($getProduct->sale_price) && $getProduct->sale_price < $getProduct->price) {
           $total = $getProduct->sale_price;
       }
      
       if( !empty( $request->size_id ) )
       {
           $size_id = $request->size_id;
           $getSize = ProductSizeModel::getSingle($size_id);
           $size_price = !empty( $getSize->price ) ? $getSize->price : 0;
           $total = $total + $size_price;
       } else {
              $size_id = 0;
       }

       $color_id = !empty( $request->color_id ) ? $request->color_id : 0;

       Cart::add([
           'id' => $getProduct->id,
           'name' => $getProduct->product_title,
           'price' => $total,
            'quantity' => $request->quantity,
           'attributes' => [
               'size_id' => $size_id,
               'color_id' => $color_id,
              
           ]
       ]);

        return redirect()->back(); 
    }

    public function updateCart(Request $request)
    {
        if (!empty($request->qty) && is_array($request->qty)) {
            foreach ($request->qty as $id => $qty) {
                Cart::update($id, [
                    'quantity' => [
                        'relative' => false,
                        'value'     => $qty
                    ]
                ]);
            }
        }

        if ($request->ajax()) {
            return response()->json([
                'status' => true,
                'message' => 'Cart updated successfully',
                'subtotal' => number_format(Cart::getSubTotal(), 2),
                'total' => number_format(Cart::getTotal(), 2),
                'cart_content' => Cart::getContent()
            ]);
        }

        return redirect()->back();
    }

    public function removeFromCart($id)
    {
        Cart::remove($id);
        if (Cart::getContent()->isEmpty()) {
            session()->forget('coupon');
        }
        session()->forget('shipping_rate');

        return redirect()->back();
    }

    public function checkout( Request $request )
    {
        if (Cart::getContent()->isEmpty()) {
            session()->forget('coupon');
            session()->forget('shipping_rate');
            return redirect('cart');
        }

        $data['meta_title'] = "Checkout";
        $data['meta_description'] = '';
        $data['meta_keywords'] = '';
        
        $discount = 0;
        $coupon = session()->get('coupon');
        if ($coupon) {
            $subtotal = Cart::getSubTotal();
            if ($subtotal >= $coupon->minimum_order_amount) {
                if ($coupon->discount_type == 'percentage') {
                    $discount = ($subtotal * $coupon->discount_value) / 100;
                    if (!empty($coupon->maximum_discount) && $discount > $coupon->maximum_discount) {
                        $discount = $coupon->maximum_discount;
                    }
                } else {
                    $discount = $coupon->discount_value;
                }
            } else {
                session()->forget('coupon');
                $coupon = null;
            }
        }
        
        $shippingRate = session()->get('shipping_rate');
        $shippingCharge = $shippingRate ? floatval($shippingRate['charge']) : 0;

        $data['countries'] = \App\Models\Country::orderBy('name', 'asc')->get();
        $data['discount'] = $discount;
        $data['coupon'] = $coupon;
        $data['shippingRate'] = $shippingRate;
        $data['shippingCharge'] = $shippingCharge;
        $data['total'] = Cart::getSubTotal() - $discount + $shippingCharge;
        
        $data['payment_gateways'] = \App\Models\PaymentGateway::getAllGateways();
        $stripeGateway = \App\Models\PaymentGateway::where('gateway_key', 'stripe')->first();
        $data['stripe_public_key'] = $stripeGateway ? $stripeGateway->public_key : '';

        $razorpayGateway = \App\Models\PaymentGateway::where('gateway_key', 'razorpay')->first();
        $data['razorpay_public_key'] = $razorpayGateway ? $razorpayGateway->public_key : '';
        $data['razorpay_active'] = $razorpayGateway && $razorpayGateway->status === 'yes';

        $paystackGateway = \App\Models\PaymentGateway::where('gateway_key', 'paystack')->first();
        $data['paystack_public_key'] = $paystackGateway ? $paystackGateway->public_key : '';
        $data['paystack_active'] = $paystackGateway && $paystackGateway->status === 'yes';

        return view('payment.checkout', $data);
    }

    public function applyCoupon(Request $request)
    {
        $code = trim($request->coupon_code);
        if (empty($code)) {
            return response()->json([
                'status' => false,
                'message' => 'Please enter a coupon code.'
            ]);
        }

        $coupon = \App\Models\CouponModel::where('is_delete', 0)
            ->where('status', 0)
            ->where('code', $code)
            ->first();

        if (!$coupon) {
            return response()->json([
                'status' => false,
                'message' => 'Invalid or inactive coupon code.'
            ]);
        }

        // Check start date
        $currentDate = date('Y-m-d');
        if (!empty($coupon->starts_at) && $currentDate < $coupon->starts_at) {
            return response()->json([
                'status' => false,
                'message' => 'This coupon is not active yet.'
            ]);
        }

        // Check expiry date
        if (!empty($coupon->expires_at) && $currentDate > $coupon->expires_at) {
            return response()->json([
                'status' => false,
                'message' => 'This coupon has expired.'
            ]);
        }

        // Check usage limit
        if (!empty($coupon->usage_limit) && $coupon->usage_count >= $coupon->usage_limit) {
            return response()->json([
                'status' => false,
                'message' => 'This coupon has reached its usage limit.'
            ]);
        }

        // Check minimum order amount
        $subtotal = Cart::getSubTotal();
        if ($subtotal < $coupon->minimum_order_amount) {
            return response()->json([
                'status' => false,
                'message' => 'Minimum order amount to use this coupon is $' . number_format($coupon->minimum_order_amount, 2)
            ]);
        }

        // Save coupon in session
        session()->put('coupon', $coupon);

        // Calculate discount
        $discount = 0;
        if ($coupon->discount_type == 'percentage') {
            $discount = ($subtotal * $coupon->discount_value) / 100;
            if (!empty($coupon->maximum_discount) && $discount > $coupon->maximum_discount) {
                $discount = $coupon->maximum_discount;
            }
        } else {
            $discount = $coupon->discount_value;
        }

        $shippingRate = session()->get('shipping_rate');
        $shippingCharge = $shippingRate ? floatval($shippingRate['charge']) : 0;
        $total = $subtotal - $discount + $shippingCharge;

        return response()->json([
            'status' => true,
            'message' => 'Coupon applied successfully!',
            'discount' => number_format($discount, 2),
            'total' => number_format($total, 2),
            'coupon_code' => $coupon->code
        ]);
    }

    public function placeOrder(Request $request)
    {
        if (Cart::getContent()->isEmpty()) {
            return redirect('cart')->withErrors(['cart_empty' => 'Your cart is empty.']);
        }

        $guestCheckoutSetting = \App\Models\Setting::get('guest_checkout', 'yes');
        $accountCreationSetting = \App\Models\Setting::get('account_creation', 'yes');
        $shippingDestinationSetting = \App\Models\Setting::get('shipping_destination', 'billing_default');

        $gateways = \App\Models\PaymentGateway::where('status', 'yes')->pluck('gateway_key')->toArray();
        if (empty($gateways)) {
            $gateways = ['cod'];
        }

        $rules = [
            'billing_first_name' => 'required|string|max:255',
            'billing_last_name' => 'required|string|max:255',
            'billing_company' => 'nullable|string|max:255',
            'billing_country_id' => 'required|exists:countries,id',
            'billing_address_1' => 'required|string|max:255',
            'billing_address_2' => 'nullable|string|max:255',
            'billing_postcode' => 'required|string|max:20',
            'billing_phone' => 'required|string|max:30',
            'shipping_rate_id' => 'required|exists:shipping_rates,id',
            'payment_method' => 'required|string|in:' . implode(',', $gateways),
            'order_notes' => 'nullable|string',
        ];

        if ($request->payment_method === 'stripe') {
            $rules['stripeToken'] = 'required|string';
        }
        if ($request->payment_method === 'paypal') {
            $rules['paypal_payment_id'] = 'required|string';
        }
        if ($request->payment_method === 'razorpay') {
            $rules['razorpay_payment_id'] = 'required|string';
        }
        if ($request->payment_method === 'paystack') {
            $rules['paystack_reference'] = 'required|string';
        }

        if ($request->payment_method === 'authorizenet') {
            $rules['authorizenet_card_number'] = 'required|string';
            $rules['authorizenet_card_expiry'] = 'required|string';
            $rules['authorizenet_card_code'] = 'required|string';
        }

        if ($request->payment_method === 'square') {
            $rules['square_card_number'] = 'required|string';
            $rules['square_card_expiry'] = 'required|string';
            $rules['square_card_code'] = 'required|string';
        }

        $billingCountry = \App\Models\Country::find($request->billing_country_id);
        $isBillingBD = $billingCountry && $billingCountry->code === 'BD';
        if ($isBillingBD) {
            $rules['billing_division_id'] = 'required|exists:divisions,id';
            $rules['billing_district_id'] = 'required|exists:districts,id';
            $rules['billing_area_id'] = 'nullable|exists:areas,id';
        } else {
            $rules['billing_city'] = 'required|string|max:255';
            $rules['billing_state'] = 'required|string|max:255';
        }

        if (!auth()->check()) {
            if ($guestCheckoutSetting === 'no') {
                $rules['billing_email'] = 'required|email|max:255|unique:users,email';
                $rules['password'] = 'required|string|min:6';
            } elseif ($accountCreationSetting === 'yes' && $request->has('create_account')) {
                $rules['billing_email'] = 'required|email|max:255|unique:users,email';
                $rules['password'] = 'required|string|min:6';
            } else {
                $rules['billing_email'] = 'required|email|max:255';
            }
        } else {
            $rules['billing_email'] = 'required|email|max:255';
        }

        $shipToDifferent = ($shippingDestinationSetting !== 'billing_only') && $request->has('ship_to_different_address');
        if ($shipToDifferent) {
            $rules['shipping_first_name'] = 'required|string|max:255';
            $rules['shipping_last_name'] = 'required|string|max:255';
            $rules['shipping_company'] = 'nullable|string|max:255';
            $rules['shipping_country_id'] = 'required|exists:countries,id';
            $rules['shipping_address_1'] = 'required|string|max:255';
            $rules['shipping_address_2'] = 'nullable|string|max:255';
            $rules['shipping_postcode'] = 'required|string|max:20';
            $rules['shipping_phone'] = 'nullable|string|max:30';

            $shippingCountry = \App\Models\Country::find($request->shipping_country_id);
            $isShippingBD = $shippingCountry && $shippingCountry->code === 'BD';
            if ($isShippingBD) {
                $rules['shipping_division_id'] = 'required|exists:divisions,id';
                $rules['shipping_district_id'] = 'required|exists:districts,id';
                $rules['shipping_area_id'] = 'nullable|exists:areas,id';
            } else {
                $rules['shipping_city'] = 'required|string|max:255';
                $rules['shipping_state'] = 'required|string|max:255';
            }
        }

        $request->validate($rules);

        if (!auth()->check()) {
            if ($guestCheckoutSetting === 'no' || ($accountCreationSetting === 'yes' && $request->has('create_account'))) {
                $user = new \App\Models\User();
                $user->name = trim($request->billing_first_name) . ' ' . trim($request->billing_last_name);
                $user->email = trim($request->billing_email);
                $user->password = \Hash::make($request->password);
                $user->is_admin = 0;
                $user->status = 0;
                $user->is_delete = 0;
                $user->save();

                // Notify admins about new registration
                \App\Models\NotificationModel::notifyAdmins("New Customer Register #" . $user->name, route('admin.customer.list'));

                // Notify user about registration
                \App\Models\NotificationModel::notifyUser($user->id, "Welcome to Molla! Your account has been created successfully.", route('user.dashboard'));

                \Auth::login($user);
            }
        }

        $subtotal = Cart::getSubTotal();
        $discount = 0;
        $coupon = session()->get('coupon');
        if ($coupon) {
            if ($subtotal >= $coupon->minimum_order_amount) {
                if ($coupon->discount_type == 'percentage') {
                    $discount = ($subtotal * $coupon->discount_value) / 100;
                    if (!empty($coupon->maximum_discount) && $discount > $coupon->maximum_discount) {
                        $discount = $coupon->maximum_discount;
                    }
                } else {
                    $discount = $coupon->discount_value;
                }
            } else {
                $coupon = null;
            }
        }

        $shippingRate = \App\Models\ShippingRate::find($request->shipping_rate_id);
        $shippingCharge = $shippingRate ? floatval($shippingRate->charge) : 0;
        $total = $subtotal - $discount + $shippingCharge;

        $order = new \App\Models\Order();
        $order->order_number = 'ORD-' . date('Ymd') . '-' . rand(1000, 9999);
        $order->user_id = auth()->check() ? auth()->id() : null;
        $order->subtotal = $subtotal;
        $order->discount = $discount;
        $order->shipping_charge = $shippingCharge;
        $order->total = $total;
        $order->payment_method = $request->payment_method;
        $order->payment_status = 'pending';
        $order->status = 'pending';
        $order->coupon_code = $coupon ? $coupon->code : null;
        $order->order_notes = $request->order_notes;

        $order->billing_first_name = $request->billing_first_name;
        $order->billing_last_name = $request->billing_last_name;
        $order->billing_company = $request->billing_company;
        $order->billing_country_id = $request->billing_country_id;
        if ($isBillingBD) {
            $order->billing_division_id = $request->billing_division_id;
            $order->billing_district_id = $request->billing_district_id;
            $order->billing_area_id = $request->billing_area_id;
        } else {
            $order->billing_city = $request->billing_city;
            $order->billing_state = $request->billing_state;
        }
        $order->billing_address_1 = $request->billing_address_1;
        $order->billing_address_2 = $request->billing_address_2;
        $order->billing_postcode = $request->billing_postcode;
        $order->billing_phone = $request->billing_phone;
        $order->billing_email = $request->billing_email;

        if ($shipToDifferent) {
            $order->shipping_first_name = $request->shipping_first_name;
            $order->shipping_last_name = $request->shipping_last_name;
            $order->shipping_company = $request->shipping_company;
            $order->shipping_country_id = $request->shipping_country_id;
            if ($isShippingBD) {
                $order->shipping_division_id = $request->shipping_division_id;
                $order->shipping_district_id = $request->shipping_district_id;
                $order->shipping_area_id = $request->shipping_area_id;
            } else {
                $order->shipping_city = $request->shipping_city;
                $order->shipping_state = $request->shipping_state;
            }
            $order->shipping_address_1 = $request->shipping_address_1;
            $order->shipping_address_2 = $request->shipping_address_2;
            $order->shipping_postcode = $request->shipping_postcode;
            $order->shipping_phone = $request->shipping_phone;
        } else {
            $order->shipping_first_name = $request->billing_first_name;
            $order->shipping_last_name = $request->billing_last_name;
            $order->shipping_company = $request->billing_company;
            $order->shipping_country_id = $request->billing_country_id;
            if ($isBillingBD) {
                $order->shipping_division_id = $request->billing_division_id;
                $order->shipping_district_id = $request->billing_district_id;
                $order->shipping_area_id = $request->billing_area_id;
            } else {
                $order->shipping_city = $request->billing_city;
                $order->shipping_state = $request->billing_state;
            }
            $order->shipping_address_1 = $request->billing_address_1;
            $order->shipping_address_2 = $request->billing_address_2;
            $order->shipping_postcode = $request->billing_postcode;
            $order->shipping_phone = $request->billing_phone;
        }

        // Handle SSLCommerz initialization
        if ($request->payment_method === 'sslcommerz') {
            $sslGateway = \App\Models\PaymentGateway::where('gateway_key', 'sslcommerz')->first();
            $storeId = $sslGateway ? $sslGateway->public_key : null;
            $storePassword = $sslGateway ? $sslGateway->secret_key : null;
            $mode = $sslGateway ? $sslGateway->mode : 'sandbox';
            
            if (empty($storeId) || empty($storePassword)) {
                return redirect()->back()->withErrors(['sslcommerz_error' => 'SSLCommerz is not configured correctly.'])->withInput();
            }

            $host = ($mode === 'live') 
                ? 'https://securepay.sslcommerz.com' 
                : 'https://sandbox.sslcommerz.com';

            $order->status = 'pending';
            $order->payment_status = 'pending';
            $order->save();

            // Save Order Items
            foreach (Cart::getContent() as $item) {
                $orderItem = new \App\Models\OrderItem();
                $orderItem->order_id = $order->id;
                $orderItem->product_id = $item->id;
                $orderItem->product_name = $item->name;
                $orderItem->price = $item->price;
                $orderItem->quantity = $item->quantity;
                $orderItem->size_id = isset($item->attributes['size_id']) ? $item->attributes['size_id'] : 0;
                $orderItem->color_id = isset($item->attributes['color_id']) ? $item->attributes['color_id'] : 0;
                $orderItem->total = $item->price * $item->quantity;
                $orderItem->save();
            }

            $payload = [
                'store_id' => $storeId,
                'store_passwd' => $storePassword,
                'total_amount' => $order->total,
                'currency' => 'BDT', 
                'tran_id' => $order->order_number,
                'success_url' => route('sslcommerz.success'),
                'fail_url' => route('sslcommerz.fail'),
                'cancel_url' => route('sslcommerz.cancel'),
                'shipping_method' => 'NO',
                'product_name' => 'Order #' . $order->order_number,
                'product_category' => 'E-commerce',
                'product_profile' => 'general',
                'cus_name' => $order->billing_first_name . ' ' . $order->billing_last_name,
                'cus_email' => $order->billing_email,
                'cus_phone' => $order->billing_phone,
                'cus_add1' => $order->billing_address_1,
                'cus_city' => $order->billing_city ?? 'Dhaka',
                'cus_state' => $order->billing_state ?? 'Dhaka',
                'cus_postcode' => $order->billing_postcode,
                'cus_country' => $order->billingCountry ? $order->billingCountry->name : 'Bangladesh',
            ];

            try {
                $response = \Illuminate\Support\Facades\Http::withoutVerifying()->asForm()->post($host . '/gwprocess/v4/api.php', $payload);

                if ($response->failed()) {
                    $order->delete();
                    return redirect()->back()->withErrors(['sslcommerz_error' => 'SSLCommerz API connection failed.'])->withInput();
                }

                $body = $response->json();
                $status = strtoupper((string)($body['status'] ?? ''));
                $redirectUrl = (string)($body['GatewayPageURL'] ?? '');

                if ($status !== 'SUCCESS' || empty($redirectUrl)) {
                    $failedReason = $body['failedreason'] ?? 'Unknown error';
                    $order->delete();
                    return redirect()->back()->withErrors(['sslcommerz_error' => 'SSLCommerz Initialization Failed: ' . $failedReason])->withInput();
                }

                if ($coupon) {
                    $couponModel = \App\Models\CouponModel::find($coupon->id);
                    if ($couponModel) {
                        $couponModel->increment('usage_count');
                    }
                }

                Cart::clear();
                session()->forget('coupon');
                session()->forget('shipping_rate');

                return redirect($redirectUrl);

            } catch (\Exception $e) {
                $order->delete();
                return redirect()->back()->withErrors(['sslcommerz_error' => 'SSLCommerz Error: ' . $e->getMessage()])->withInput();
            }
        }

        // Handle Stripe Payment
        if ($request->payment_method === 'stripe') {
            $stripeGateway = \App\Models\PaymentGateway::where('gateway_key', 'stripe')->first();
            $stripeSecret = $stripeGateway ? $stripeGateway->secret_key : null;
            if (empty($stripeSecret)) {
                return redirect()->back()->withErrors(['stripe_error' => 'Stripe is not configured correctly.'])->withInput();
            }

            try {
                $response = \Illuminate\Support\Facades\Http::withoutVerifying()->withHeaders([
                    'Authorization' => 'Bearer ' . $stripeSecret
                ])->asForm()->post('https://api.stripe.com/v1/charges', [
                    'amount' => round($order->total * 100),
                    'currency' => 'usd',
                    'source' => $request->stripeToken,
                    'description' => 'Payment for Order #' . $order->order_number,
                ]);

                if ($response->failed()) {
                    $errorData = $response->json();
                    $errorMessage = $errorData['error']['message'] ?? 'An unknown error occurred with Stripe.';
                    return redirect()->back()->withErrors(['stripe_error' => 'Stripe Payment Failed: ' . $errorMessage])->withInput();
                }

                $chargeData = $response->json();
                if (isset($chargeData['id'])) {
                    $order->payment_status = 'paid';
                    $order->status = 'processing';
                }
            } catch (\Exception $e) {
                return redirect()->back()->withErrors(['stripe_error' => 'Stripe Error: ' . $e->getMessage()])->withInput();
            }
        }

        // Handle PayPal Payment
        if ($request->payment_method === 'paypal') {
            $order->payment_status = 'paid';
            $order->status = 'processing';
        }

        // Handle Razorpay Payment
        if ($request->payment_method === 'razorpay') {
            $order->payment_status = 'paid';
            $order->status = 'processing';
        }

        // Handle Paystack Payment
        if ($request->payment_method === 'paystack') {
            $order->payment_status = 'paid';
            $order->status = 'processing';
        }

        // Handle Mollie Payment (Redirect Gateway)
        if ($request->payment_method === 'mollie') {
            $mollieGateway = \App\Models\PaymentGateway::where('gateway_key', 'mollie')->first();
            $apiKey = $mollieGateway ? $mollieGateway->secret_key : null;
            if (empty($apiKey)) {
                return redirect()->back()->withErrors(['mollie_error' => 'Mollie is not configured correctly.'])->withInput();
            }

            $order->status = 'pending';
            $order->payment_status = 'pending';
            $order->save();

            // Save Order Items
            foreach (Cart::getContent() as $item) {
                $orderItem = new \App\Models\OrderItem();
                $orderItem->order_id = $order->id;
                $orderItem->product_id = $item->id;
                $orderItem->product_name = $item->name;
                $orderItem->price = $item->price;
                $orderItem->quantity = $item->quantity;
                $orderItem->size_id = isset($item->attributes['size_id']) ? $item->attributes['size_id'] : 0;
                $orderItem->color_id = isset($item->attributes['color_id']) ? $item->attributes['color_id'] : 0;
                $orderItem->total = $item->price * $item->quantity;
                $orderItem->save();
            }

            try {
                $response = \Illuminate\Support\Facades\Http::withoutVerifying()->withHeaders([
                    'Authorization' => 'Bearer ' . $apiKey
                ])->post('https://api.mollie.com/v2/payments', [
                    'amount' => [
                        'currency' => 'EUR',
                        'value' => number_format($order->total, 2, '.', '')
                    ],
                    'description' => 'Payment for Order #' . $order->order_number,
                    'redirectUrl' => route('checkout.success', $order->id),
                    'metadata' => [
                        'order_id' => $order->id
                    ]
                ]);

                if ($response->failed()) {
                    $order->delete();
                    return redirect()->back()->withErrors(['mollie_error' => 'Mollie API connection failed.'])->withInput();
                }

                $body = $response->json();
                $redirectUrl = $body['_links']['checkout']['href'] ?? '';

                if (empty($redirectUrl)) {
                    $order->delete();
                    return redirect()->back()->withErrors(['mollie_error' => 'Mollie Initialization Failed.'])->withInput();
                }

                $order->payment_status = 'paid';
                $order->status = 'processing';
                $order->save();

                if ($coupon) {
                    $couponModel = \App\Models\CouponModel::find($coupon->id);
                    if ($couponModel) {
                        $couponModel->increment('usage_count');
                    }
                }

                Cart::clear();
                session()->forget('coupon');
                session()->forget('shipping_rate');

                return redirect($redirectUrl);

            } catch (\Exception $e) {
                $order->delete();
                return redirect()->back()->withErrors(['mollie_error' => 'Mollie Error: ' . $e->getMessage()])->withInput();
            }
        }

        // Handle Authorize.Net
        if ($request->payment_method === 'authorizenet') {
            $gateway = \App\Models\PaymentGateway::where('gateway_key', 'authorizenet')->first();
            $apiLoginId = $gateway ? $gateway->public_key : null;
            $transactionKey = $gateway ? $gateway->secret_key : null;
            $mode = $gateway ? $gateway->mode : 'sandbox';
            
            if (empty($apiLoginId) || empty($transactionKey)) {
                return redirect()->back()->withErrors(['authorizenet_error' => 'Authorize.Net is not configured correctly.'])->withInput();
            }

            $url = ($mode === 'live') 
                ? 'https://api2.authorize.net/xml/v1/request.api' 
                : 'https://apitest.authorize.net/xml/v1/request.api';

            $expiry = str_replace('/', '', $request->authorizenet_card_expiry);

            $payload = [
                'createTransactionRequest' => [
                    'merchantAuthentication' => [
                        'name' => $apiLoginId,
                        'transactionKey' => $transactionKey,
                    ],
                    'transactionRequest' => [
                        'transactionType' => 'authCaptureTransaction',
                        'amount' => number_format($order->total, 2, '.', ''),
                        'payment' => [
                            'creditCard' => [
                                'cardNumber' => str_replace(' ', '', $request->authorizenet_card_number),
                                'expirationDate' => $expiry,
                                'cardCode' => $request->authorizenet_card_code,
                            ],
                        ],
                    ],
                ],
            ];

            try {
                $response = \Illuminate\Support\Facades\Http::withoutVerifying()->post($url, $payload);
                
                if ($response->successful()) {
                    $body = $response->json();
                    $responseCode = $body['transactionResponse']['responseCode'] ?? null;

                    if ($responseCode == 1) {
                        $order->payment_status = 'paid';
                        $order->status = 'processing';
                    } else {
                        $msg = $body['transactionResponse']['errors'][0]['errorText'] ?? ($body['messages']['message'][0]['text'] ?? 'Transaction Declined');
                        return redirect()->back()->withErrors(['authorizenet_error' => 'Authorize.Net Error: ' . $msg])->withInput();
                    }
                } else {
                    return redirect()->back()->withErrors(['authorizenet_error' => 'Authorize.Net Connection Error.'])->withInput();
                }
            } catch (\Exception $e) {
                return redirect()->back()->withErrors(['authorizenet_error' => 'Authorize.Net Error: ' . $e->getMessage()])->withInput();
            }
        }

        // Handle Square Payment
        if ($request->payment_method === 'square') {
            $gateway = \App\Models\PaymentGateway::where('gateway_key', 'square')->first();
            $accessToken = $gateway ? $gateway->secret_key : null;
            
            if (empty($accessToken)) {
                return redirect()->back()->withErrors(['square_error' => 'Square is not configured correctly.'])->withInput();
            }

            $order->payment_status = 'paid';
            $order->status = 'processing';
        }

        $order->save();

        // Notify admins about new order placement
        \App\Models\NotificationModel::notifyAdmins("New Order Placed #" . $order->order_number, route('admin.orders.show', $order->id));

        // Notify user about new order placement
        if ($order->user_id) {
            \App\Models\NotificationModel::notifyUser($order->user_id, "Your order #" . $order->order_number . " has been placed successfully.", route('user.orders.show', $order->id));
        }

        if ($coupon) {
            $couponModel = \App\Models\CouponModel::find($coupon->id);
            if ($couponModel) {
                $couponModel->increment('usage_count');
            }
        }

        foreach (Cart::getContent() as $item) {
            $orderItem = new \App\Models\OrderItem();
            $orderItem->order_id = $order->id;
            $orderItem->product_id = $item->id;
            $orderItem->product_name = $item->name;
            $orderItem->price = $item->price;
            $orderItem->quantity = $item->quantity;
            $orderItem->size_id = isset($item->attributes['size_id']) ? $item->attributes['size_id'] : 0;
            $orderItem->color_id = isset($item->attributes['color_id']) ? $item->attributes['color_id'] : 0;
            $orderItem->total = $item->price * $item->quantity;
            $orderItem->save();
        }

        // Eager load relations for the invoice email view
        $order->load(['items.size', 'items.color', 'billingCountry', 'billingDivision', 'billingDistrict', 'billingArea', 'shippingCountry', 'shippingDivision', 'shippingDistrict', 'shippingArea']);

        // Send Invoice Email to Customer
        if (!empty($order->billing_email)) {
            try {
                \Illuminate\Support\Facades\Mail::to($order->billing_email)->send(new \App\Mail\OrderInvoiceMail($order));
            } catch (\Exception $e) {
                \Log::error('Order Invoice Email Error: ' . $e->getMessage());
            }
        }

        Cart::clear();
        session()->forget('coupon');
        session()->forget('shipping_rate');

        return redirect()->route('checkout.success', $order->id)->with('success', 'Thank you! Your order has been placed successfully.');
    }

    public function orderSuccess($id)
    {
        $order = \App\Models\Order::with(['items', 'billingCountry', 'billingDivision', 'billingDistrict', 'billingArea', 'shippingCountry', 'shippingDivision', 'shippingDistrict', 'shippingArea'])->findOrFail($id);

        $data['meta_title'] = "Order Success";
        $data['meta_description'] = '';
        $data['meta_keywords'] = '';
        $data['order'] = $order;

        return view('payment.order_success', $data);
    }

    public function sslCommerzSuccess(Request $request)
    {
        $tran_id = $request->input('tran_id');
        $val_id = $request->input('val_id');
        $amount = $request->input('amount');
        
        $order = \App\Models\Order::where('order_number', $tran_id)->first();
        if (!$order) {
            return redirect()->route('cart')->withErrors(['order_error' => 'Order not found for transaction: ' . $tran_id]);
        }

        // Validate the transaction using SSLCommerz Validation API
        $sslGateway = \App\Models\PaymentGateway::where('gateway_key', 'sslcommerz')->first();
        $storeId = $sslGateway ? $sslGateway->public_key : null;
        $storePassword = $sslGateway ? $sslGateway->secret_key : null;
        $mode = $sslGateway ? $sslGateway->mode : 'sandbox';
        $host = ($mode === 'live') ? 'https://securepay.sslcommerz.com' : 'https://sandbox.sslcommerz.com';

        try {
            $response = \Illuminate\Support\Facades\Http::withoutVerifying()->get($host . '/validator/api/merchantTransIDvalidationAPI.php', [
                'tran_id' => $tran_id,
                'store_id' => $storeId,
                'store_passwd' => $storePassword,
                'v' => 1,
                'format' => 'json'
            ]);

            if ($response->successful()) {
                $body = $response->json();
                $elements = $body['element'] ?? [];
                
                foreach ($elements as $element) {
                    if (($element['tran_id'] ?? '') === $tran_id) {
                        $status = strtoupper((string)($element['status'] ?? ''));
                        if (in_array($status, ['VALID', 'VALIDATED'], true)) {
                            $order->payment_status = 'paid';
                            $order->status = 'processing';
                            $order->save();

                            $order->load(['items.size', 'items.color', 'billingCountry', 'billingDivision', 'billingDistrict', 'billingArea', 'shippingCountry', 'shippingDivision', 'shippingDistrict', 'shippingArea']);

                            if (!empty($order->billing_email)) {
                                try {
                                    \Illuminate\Support\Facades\Mail::to($order->billing_email)->send(new \App\Mail\OrderInvoiceMail($order));
                                } catch (\Exception $e) {
                                    \Log::error('Order Invoice Email Error (SSLCommerz): ' . $e->getMessage());
                                }
                            }

                            \App\Models\NotificationModel::notifyAdmins("New Order Placed #" . $order->order_number, route('admin.orders.show', $order->id));

                            if ($order->user_id) {
                                \App\Models\NotificationModel::notifyUser($order->user_id, "Your order #" . $order->order_number . " has been placed successfully.", route('user.orders.show', $order->id));
                            }

                            return redirect()->route('checkout.success', $order->id)->with('success', 'Payment successful! Thank you for your order.');
                        }
                    }
                }
            }
        } catch (\Exception $e) {
            \Log::error('SSLCommerz validation error: ' . $e->getMessage());
        }

        return redirect()->route('checkout.success', $order->id)->withErrors(['payment_error' => 'Payment validation failed. Please contact support.']);
    }

    public function sslCommerzFail(Request $request)
    {
        $tran_id = $request->input('tran_id');
        $order = \App\Models\Order::where('order_number', $tran_id)->first();
        if ($order) {
            $order->status = 'cancelled';
            $order->payment_status = 'failed';
            $order->save();
            return redirect()->route('checkout.success', $order->id)->withErrors(['payment_error' => 'Your payment failed. Please try again.']);
        }
        return redirect()->route('cart')->withErrors(['payment_error' => 'Payment failed.']);
    }

    public function sslCommerzCancel(Request $request)
    {
        $tran_id = $request->input('tran_id');
        $order = \App\Models\Order::where('order_number', $tran_id)->first();
        if ($order) {
            $order->status = 'cancelled';
            $order->payment_status = 'failed';
            $order->save();
            return redirect()->route('checkout.success', $order->id)->withErrors(['payment_error' => 'Payment was cancelled.']);
        }
        return redirect()->route('cart')->withErrors(['payment_error' => 'Payment cancelled.']);
    }
}
