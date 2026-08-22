<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;

class OrderController extends Controller
{
    public function list(Request $request)
    {
        $query = Order::with(['billingCountry', 'billingDivision', 'billingDistrict', 'billingArea'])->orderBy('id', 'desc');

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

        if ($request->filled('state')) {
            $state = trim($request->state);
            $query->where(function($q) use ($state) {
                $q->where('billing_state', 'like', "%{$state}%")
                  ->orWhereHas('billingDivision', function($sq) use ($state) {
                      $sq->where('name', 'like', "%{$state}%");
                  });
            });
        }

        if ($request->filled('city')) {
            $city = trim($request->city);
            $query->where(function($q) use ($city) {
                $q->where('billing_city', 'like', "%{$city}%")
                  ->orWhereHas('billingDistrict', function($sq) use ($city) {
                      $sq->where('name', 'like', "%{$city}%");
                  });
            });
        }

        if ($request->filled('phone')) {
            $query->where('billing_phone', 'like', '%' . trim($request->phone) . '%');
        }

        if ($request->filled('postcode')) {
            $query->where('billing_postcode', 'like', '%' . trim($request->postcode) . '%');
        }

        if ($request->filled('start_date')) {
            $query->whereDate('created_at', '>=', $request->start_date);
        }

        if ($request->filled('end_date')) {
            $query->whereDate('created_at', '<=', $request->end_date);
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

        $oldStatus = $order->status;
        $order->status = $request->status;
        $order->payment_status = $request->payment_status;
        $order->save();

        if ($oldStatus !== $order->status && !empty($order->billing_email)) {
            try {
                $order->load(['items.size', 'items.color', 'billingCountry', 'billingDivision', 'billingDistrict', 'billingArea', 'shippingCountry', 'shippingDivision', 'shippingDistrict', 'shippingArea']);
                \Illuminate\Support\Facades\Mail::to($order->billing_email)->send(new \App\Mail\OrderStatusMail($order));
            } catch (\Exception $e) {
                \Log::error('Order Status Email Error (Standard): ' . $e->getMessage());
            }
        }

        return redirect()->route('admin.orders.list')->with('success', 'Order updated successfully.');
    }

    public function delete($id)
    {
        $order = Order::findOrFail($id);
        $order->delete();

        return redirect()->route('admin.orders.list')->with('success', 'Order deleted successfully.');
    }

    /**
     * Update order status via AJAX.
     */
    public function updateStatus(Request $request, $id)
    {
        $order = Order::findOrFail($id);
        
        $request->validate([
            'status' => 'required|in:pending,processing,on-hold,completed,cancelled,refunded,failed',
        ]);

        $oldStatus = $order->status;
        $order->status = $request->status;
        $order->save();

        if ($oldStatus !== $order->status && !empty($order->billing_email)) {
            try {
                $order->load(['items.size', 'items.color', 'billingCountry', 'billingDivision', 'billingDistrict', 'billingArea', 'shippingCountry', 'shippingDivision', 'shippingDistrict', 'shippingArea']);
                \Illuminate\Support\Facades\Mail::to($order->billing_email)->send(new \App\Mail\OrderStatusMail($order));
            } catch (\Exception $e) {
                \Log::error('Order Status Email Error (AJAX): ' . $e->getMessage());
            }
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Order status updated successfully.'
        ]);
    }
}
