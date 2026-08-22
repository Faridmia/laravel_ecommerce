<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\HomeSetting;

class HomeSettingController extends Controller
{
    public function index()
    {
        $data['header_title'] = 'Home Setting';
        $data['settings'] = HomeSetting::getSingle();
        return view('admin.home_settings', $data);
    }

    public function update(Request $request)
    {
        $request->validate([
            'trendy_product_title' => 'nullable|string|max:255',
            'shop_category_title' => 'nullable|string|max:255',
            'recent_arrival_title' => 'nullable|string|max:255',
            'blog_title' => 'nullable|string|max:255',
            'payment_delivery_title' => 'nullable|string|max:255',
            'payment_delivery_description' => 'nullable|string|max:255',
            'payment_delivery_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'refund_title' => 'nullable|string|max:255',
            'refund_description' => 'nullable|string|max:255',
            'refund_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'support_title' => 'nullable|string|max:255',
            'support_description' => 'nullable|string|max:255',
            'support_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'singup_title' => 'nullable|string|max:255',
            'singup_description' => 'nullable|string|max:255',
            'singup_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $settings = HomeSetting::getSingle();
        if (empty($settings)) {
            $settings = new HomeSetting();
        }

        $settings->trendy_product_title = trim($request->trendy_product_title);
        $settings->shop_category_title = trim($request->shop_category_title);
        $settings->recent_arrival_title = trim($request->recent_arrival_title);
        $settings->blog_title = trim($request->blog_title);
        $settings->payment_delivery_title = trim($request->payment_delivery_title);
        $settings->payment_delivery_description = trim($request->payment_delivery_description);
        $settings->refund_title = trim($request->refund_title);
        $settings->refund_description = trim($request->refund_description);
        $settings->support_title = trim($request->support_title);
        $settings->support_description = trim($request->support_description);
        $settings->singup_title = trim($request->singup_title);
        $settings->singup_description = trim($request->singup_description);

        $upload_path = public_path('upload/home');
        if (!file_exists($upload_path)) {
            mkdir($upload_path, 0777, true);
        }

        // Handle Payment Delivery Image upload
        if ($request->hasFile('payment_delivery_image')) {
            $file = $request->file('payment_delivery_image');
            if ($file->isValid()) {
                if (!empty($settings->payment_delivery_image) && file_exists($upload_path . '/' . $settings->payment_delivery_image)) {
                    @unlink($upload_path . '/' . $settings->payment_delivery_image);
                }
                $filename = 'pay_del_' . time() . '.' . $file->getClientOriginalExtension();
                $file->move($upload_path, $filename);
                $settings->payment_delivery_image = $filename;
            }
        }

        // Handle Refund Image upload
        if ($request->hasFile('refund_image')) {
            $file = $request->file('refund_image');
            if ($file->isValid()) {
                if (!empty($settings->refund_image) && file_exists($upload_path . '/' . $settings->refund_image)) {
                    @unlink($upload_path . '/' . $settings->refund_image);
                }
                $filename = 'refund_' . time() . '.' . $file->getClientOriginalExtension();
                $file->move($upload_path, $filename);
                $settings->refund_image = $filename;
            }
        }

        // Handle Support Image upload
        if ($request->hasFile('support_image')) {
            $file = $request->file('support_image');
            if ($file->isValid()) {
                if (!empty($settings->support_image) && file_exists($upload_path . '/' . $settings->support_image)) {
                    @unlink($upload_path . '/' . $settings->support_image);
                }
                $filename = 'support_' . time() . '.' . $file->getClientOriginalExtension();
                $file->move($upload_path, $filename);
                $settings->support_image = $filename;
            }
        }

        // Handle Signup Image upload (singup_image)
        if ($request->hasFile('singup_image')) {
            $file = $request->file('singup_image');
            if ($file->isValid()) {
                if (!empty($settings->singup_image) && file_exists($upload_path . '/' . $settings->singup_image)) {
                    @unlink($upload_path . '/' . $settings->singup_image);
                }
                $filename = 'singup_' . time() . '.' . $file->getClientOriginalExtension();
                $file->move($upload_path, $filename);
                $settings->singup_image = $filename;
            }
        }

        $settings->save();

        return redirect()->back()->with('success', 'Home settings updated successfully.');
    }
}
