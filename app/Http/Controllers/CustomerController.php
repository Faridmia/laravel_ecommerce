<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\CouponModel;
use App\Models\User;
use App\Models\WishlistModel;
use Hash;

class CustomerController extends Controller
{
    public function dashboard(Request $request)
    {
        $user = auth()->user();
        
        $data['orders'] = Order::where('user_id', $user->id)
            ->orderBy('id', 'desc')
            ->paginate(10);

        // Get active coupons
        $data['coupons'] = CouponModel::getRecordActive();

        // Get wishlist items
        $data['wishlist'] = WishlistModel::where('user_id', $user->id)
            ->with(['product.getImages'])
            ->orderBy('id', 'desc')
            ->get();

        // Get latest order to show billing/shipping addresses in Address tab
        $data['latestOrder'] = Order::where('user_id', $user->id)
            ->orderBy('id', 'desc')
            ->first();

        $data['meta_title'] = "My Account";
        $data['meta_description'] = "";
        $data['meta_keywords'] = "";

        return view('user.dashboard', $data);
    }

    public function updateProfile(Request $request)
    {
        $user = auth()->user();

        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
        ];

        if ($request->filled('new_password')) {
            $rules['current_password'] = 'required|string';
            $rules['new_password'] = 'required|string|min:6';
        }

        $request->validate($rules);

        if ($request->filled('new_password')) {
            if (!Hash::check($request->current_password, $user->password)) {
                return redirect()->back()->withErrors(['current_password' => 'The current password does not match.'])->withInput();
            }
            $user->password = Hash::make($request->new_password);
        }

        $user->name = trim($request->name);
        $user->email = trim($request->email);
        $user->save();

        return redirect()->back()->with('success', 'Profile updated successfully.');
    }

    public function showOrder($id)
    {
        // Restrict to current user's orders to ensure security
        $order = Order::where('user_id', auth()->id())
            ->with([
                'items.product',
                'billingCountry', 'billingDivision', 'billingDistrict', 'billingArea',
                'shippingCountry', 'shippingDivision', 'shippingDistrict', 'shippingArea'
            ])
            ->findOrFail($id);

        $data['order'] = $order;
        $data['meta_title'] = "Order details #" . $order->order_number;
        $data['meta_description'] = "";
        $data['meta_keywords'] = "";

        return view('user.order_details', $data);
    }
}
