<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;

class OrderController extends Controller
{
    public function list(Request $request)
    {
        $query = Order::orderBy('id', 'desc');

        if ($request->filled('search')) {
            $search = trim($request->search);
            $query->where(function($q) use ($search) {
                $q->where('order_number', 'like', "%{$search}%")
                  ->orWhere('billing_first_name', 'like', "%{$search}%")
                  ->orWhere('billing_last_name', 'like', "%{$search}%")
                  ->orWhere('billing_email', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $data['orders'] = $query->paginate(20);
        $data['header_title'] = 'Orders List';

        return view('admin.order.list', $data);
    }

    public function show($id)
    {
        $order = Order::with([
            'items', 
            'billingCountry', 'billingDivision', 'billingDistrict', 'billingArea', 
            'shippingCountry', 'shippingDivision', 'shippingDistrict', 'shippingArea'
        ])->findOrFail($id);

        $data['order'] = $order;
        $data['header_title'] = 'Order details ' . $order->order_number;

        return view('admin.order.show', $data);
    }

    public function update(Request $request, $id)
    {
        $order = Order::findOrFail($id);

        $request->validate([
            'status' => 'required|in:pending,processing,on-hold,completed,cancelled,refunded,failed',
            'payment_status' => 'required|in:pending,paid,failed,refunded',
        ]);

        $order->status = $request->status;
        $order->payment_status = $request->payment_status;
        $order->save();

        return redirect()->route('admin.orders.list')->with('success', 'Order updated successfully.');
    }

    public function delete($id)
    {
        $order = Order::findOrFail($id);
        $order->delete();

        return redirect()->route('admin.orders.list')->with('success', 'Order deleted successfully.');
    }
}
