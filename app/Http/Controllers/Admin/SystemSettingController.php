<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SystemSetting;

class SystemSettingController extends Controller
{
    /**
     * Display the system settings page.
     */
    public function index()
    {
        $data['header_title'] = 'System Setting';
        $data['settings'] = SystemSetting::getSingle();
        return view('admin.system_settings', $data);
    }

    /**
     * Update the system settings.
     */
    public function update(Request $request)
    {
        $request->validate([
            'website_name' => 'nullable|string|max:255',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'fevicon' => 'nullable|image|mimes:jpeg,png,jpg,gif,ico|max:1024',
            'footer_description' => 'nullable|string',
            'footer_payment_icon' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'address' => 'nullable|string',
            'phone' => 'nullable|string|max:100',
            'phone_two' => 'nullable|string|max:100',
            'submit_email' => 'nullable|email|max:255',
            'email' => 'nullable|email|max:255',
            'email_two' => 'nullable|email|max:255',
            'working_hour' => 'nullable|string',
            'facebook_link' => 'nullable|string|max:500',
            'twitter_link' => 'nullable|string|max:500',
            'instagram_link' => 'nullable|string|max:500',
            'youtube_link' => 'nullable|string|max:500',
            'pinterest_link' => 'nullable|string|max:500',
        ]);

        $settings = SystemSetting::getSingle();
        
        $settings->website_name = trim($request->website_name);
        $settings->footer_description = trim($request->footer_description);
        $settings->address = trim($request->address);
        $settings->phone = trim($request->phone);
        $settings->phone_two = trim($request->phone_two);
        $settings->submit_email = trim($request->submit_email);
        $settings->email = trim($request->email);
        $settings->email_two = trim($request->email_two);
        $settings->working_hour = trim($request->working_hour);
        $settings->facebook_link = trim($request->facebook_link);
        $settings->twitter_link = trim($request->twitter_link);
        $settings->instagram_link = trim($request->instagram_link);
        $settings->youtube_link = trim($request->youtube_link);
        $settings->pinterest_link = trim($request->pinterest_link);

        // Handle logo file upload
        if ($request->hasFile('logo')) {
            $logoFile = $request->file('logo');
            if ($logoFile->isValid()) {
                if (!empty($settings->logo) && file_exists(public_path('upload/system/' . $settings->logo))) {
                    @unlink(public_path('upload/system/' . $settings->logo));
                }

                if (!file_exists(public_path('upload/system'))) {
                    mkdir(public_path('upload/system'), 0777, true);
                }

                $logoName = 'logo_' . time() . '.' . $logoFile->getClientOriginalExtension();
                $logoFile->move(public_path('upload/system/'), $logoName);
                $settings->logo = $logoName;
            }
        }

        // Handle favicon upload
        if ($request->hasFile('fevicon')) {
            $favFile = $request->file('fevicon');
            if ($favFile->isValid()) {
                if (!empty($settings->fevicon) && file_exists(public_path('upload/system/' . $settings->fevicon))) {
                    @unlink(public_path('upload/system/' . $settings->fevicon));
                }

                if (!file_exists(public_path('upload/system'))) {
                    mkdir(public_path('upload/system'), 0777, true);
                }

                $favName = 'favicon_' . time() . '.' . $favFile->getClientOriginalExtension();
                $favFile->move(public_path('upload/system/'), $favName);
                $settings->fevicon = $favName;
            }
        }

        // Handle payment icon upload
        if ($request->hasFile('footer_payment_icon')) {
            $payFile = $request->file('footer_payment_icon');
            if ($payFile->isValid()) {
                if (!empty($settings->footer_payment_icon) && file_exists(public_path('upload/system/' . $settings->footer_payment_icon))) {
                    @unlink(public_path('upload/system/' . $settings->footer_payment_icon));
                }

                if (!file_exists(public_path('upload/system'))) {
                    mkdir(public_path('upload/system'), 0777, true);
                }

                $payName = 'payment_' . time() . '.' . $payFile->getClientOriginalExtension();
                $payFile->move(public_path('upload/system/'), $payName);
                $settings->footer_payment_icon = $payName;
            }
        }

        $settings->save();

        return redirect()->back()->with('success', 'System settings updated successfully.');
    }
}
