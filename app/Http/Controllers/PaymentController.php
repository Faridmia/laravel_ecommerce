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
            'payment_method' => 'required|string|in:cod',
            'order_notes' => 'nullable|string',
        ];

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

        $order->save();

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
}
