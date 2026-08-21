<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Setting;

class SettingController extends Controller
{
    public function index()
    {
        $data['header_title'] = 'Settings';
        $data['guest_checkout'] = Setting::get('guest_checkout', 'yes');
        $data['account_creation'] = Setting::get('account_creation', 'yes');
        $data['shipping_destination'] = Setting::get('shipping_destination', 'billing_default');
        
        return view('admin.settings', $data);
    }

    public function update(Request $request)
    {
        $request->validate([
            'guest_checkout' => 'required|in:yes,no',
            'account_creation' => 'required|in:yes,no',
            'shipping_destination' => 'required|in:billing_default,shipping_default,billing_only',
        ]);

        Setting::set('guest_checkout', $request->guest_checkout);
        Setting::set('account_creation', $request->account_creation);
        Setting::set('shipping_destination', $request->shipping_destination);

        return redirect()->back()->with('success', 'Settings updated successfully.');
    }
}
