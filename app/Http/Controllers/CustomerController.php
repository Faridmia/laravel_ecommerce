<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\CouponModel;
use App\Models\User;
use App\Models\WishlistModel;
use App\Models\Country;
use Hash;

class CustomerController extends Controller
{
    /**
     * Display customer account dashboard.
     */
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

        // Load countries for profile edit form
        $data['countries'] = Country::orderBy('name', 'asc')->get();

        // Calculate 8 dashboard metrics
        $data['totalOrders'] = Order::where('user_id', $user->id)->count();
        $data['todayOrders'] = Order::where('user_id', $user->id)->whereDate('created_at', date('Y-m-d'))->count();
        $data['totalAmount'] = Order::where('user_id', $user->id)->sum('total');
        $data['todayAmount'] = Order::where('user_id', $user->id)->whereDate('created_at', date('Y-m-d'))->sum('total');
        $data['pendingOrders'] = Order::where('user_id', $user->id)->where('status', 'pending')->count();
        $data['inProgressOrders'] = Order::where('user_id', $user->id)->where('status', 'processing')->count();
        $data['completedOrders'] = Order::where('user_id', $user->id)->where('status', 'completed')->count();
        $data['cancelledOrders'] = Order::where('user_id', $user->id)->where('status', 'cancelled')->count();

        $data['notifications'] = \App\Models\NotificationModel::where('user_id', $user->id)
            ->orderBy('id', 'desc')
            ->get();

        $data['meta_title'] = "My Account";
        $data['meta_description'] = "";
        $data['meta_keywords'] = "";

        return view('user.dashboard', $data);
    }

    /**
     * Update customer profile details.
     */
    public function updateProfile(Request $request)
    {
        $user = auth()->user();

        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
            'billing_country_id' => 'required|exists:countries,id',
            'billing_address_1' => 'required|string|max:255',
            'billing_address_2' => 'nullable|string|max:255',
            'billing_city' => 'required|string|max:255',
            'billing_state' => 'required|string|max:255',
            'billing_postcode' => 'required|string|max:255',
            'billing_phone' => 'required|string|max:255',
            'billing_company' => 'nullable|string|max:255',
        ]);

        $user->first_name = trim($request->first_name);
        $user->last_name = trim($request->last_name);
        $user->name = trim($request->first_name . ' ' . $request->last_name);
        $user->email = trim($request->email);
        $user->billing_company = trim($request->billing_company);
        $user->billing_country_id = $request->billing_country_id;
        $user->billing_address_1 = trim($request->billing_address_1);
        $user->billing_address_2 = trim($request->billing_address_2);
        $user->billing_city = trim($request->billing_city);
        $user->billing_state = trim($request->billing_state);
        $user->billing_postcode = trim($request->billing_postcode);
        $user->billing_phone = trim($request->billing_phone);
        $user->save();

        return redirect()->back()->with('success', 'Profile updated successfully.');
    }

    /**
     * Update customer password.
     */
    public function updatePassword(Request $request)
    {
        $user = auth()->user();

        $request->validate([
            'current_password' => 'required|string',
            'new_password' => 'required|string|min:6|confirmed',
        ]);

        if (!Hash::check($request->current_password, $user->password)) {
            return redirect()->back()->withErrors(['current_password' => 'The current password does not match.']);
        }

        $user->password = Hash::make($request->new_password);
        $user->save();

        return redirect()->back()->with('success', 'Password updated successfully.');
    }

    /**
     * Display order details page.
     */
    public function showOrder($id)
    {
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
