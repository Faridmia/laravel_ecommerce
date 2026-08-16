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

        return redirect()->back();
    }

    public function removeFromCart($id)
    {
        Cart::remove($id);

        return redirect()->back();
    }

    public function checkout( Request $request )
    {
        $data['meta_title'] = "Checkout";
        $data['meta_description'] = '';
        $data['meta_keywords'] = '';
        return view('payment.checkout', $data);
    }
}
