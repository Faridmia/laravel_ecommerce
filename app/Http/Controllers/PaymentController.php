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

        return redirect()->back();
    }

    public function checkout( Request $request )
    {
        if (Cart::getContent()->isEmpty()) {
            session()->forget('coupon');
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
        
        $data['discount'] = $discount;
        $data['coupon'] = $coupon;
        $data['total'] = Cart::getSubTotal() - $discount;
        
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

        $total = $subtotal - $discount;

        return response()->json([
            'status' => true,
            'message' => 'Coupon applied successfully!',
            'discount' => number_format($discount, 2),
            'total' => number_format($total, 2),
            'coupon_code' => $coupon->code
        ]);
    }
}
