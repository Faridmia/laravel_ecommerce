<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\User;
use App\Models\ProductModel;
use App\Models\ContactMessageModel;

class DashboardController extends Controller
{
    /**
     * Display the admin dashboard.
     */
    public function dashboard()
    {
        // General top widget statistics
        $data['totalOrders'] = Order::count();
        $data['totalAmount'] = Order::sum('total');
        $data['totalCustomer'] = User::where('is_admin', 0)->where('is_delete', 0)->count();
        
        $data['totalProducts'] = ProductModel::where('is_delete', 0)->count();
        $data['pendingOrders'] = Order::where('status', 'pending')->count();
        $data['totalContactMessages'] = ContactMessageModel::count();

        // 1. Store Activity Chart data (last 7 days orders vs completed orders)
        $visitorsCategories = [];
        $ordersData = [];
        $completedOrdersData = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = date('Y-m-d', strtotime("-$i days"));
            $dayLabel = date('jS', strtotime($date));
            $visitorsCategories[] = $dayLabel;

            $ordersData[] = Order::whereDate('created_at', $date)->count();
            $completedOrdersData[] = Order::whereDate('created_at', $date)->where('status', 'completed')->count();
        }
        $data['visitorsCategories'] = $visitorsCategories;
        $data['ordersData'] = $ordersData;
        $data['completedOrdersData'] = $completedOrdersData;

        // 2. Sales Chart data (last 6 months Sales vs Shipping vs Discounts)
        $salesCategories = [];
        $salesData = [];
        $shippingData = [];
        $discountData = [];
        for ($i = 5; $i >= 0; $i--) {
            $monthStart = date('Y-m-01', strtotime("-$i months"));
            $monthEnd = date('Y-m-t', strtotime("-$i months"));
            $monthLabel = date('M', strtotime($monthStart));
            $salesCategories[] = $monthLabel;

            $salesSum = Order::whereBetween('created_at', [$monthStart . ' 00:00:00', $monthEnd . ' 23:59:59'])->sum('total');
            $shippingSum = Order::whereBetween('created_at', [$monthStart . ' 00:00:00', $monthEnd . ' 23:59:59'])->sum('shipping_charge');
            $discountSum = Order::whereBetween('created_at', [$monthStart . ' 00:00:00', $monthEnd . ' 23:59:59'])->sum('discount');

            $salesData[] = round($salesSum, 2);
            $shippingData[] = round($shippingSum, 2);
            $discountData[] = round($discountSum, 2);
        }
        $data['salesCategories'] = $salesCategories;
        $data['salesData'] = $salesData;
        $data['shippingData'] = $shippingData;
        $data['discountData'] = $discountData;

        $data['header_title'] = 'Dashboard';   
        return view('admin.dashboard', $data);
    }
}
