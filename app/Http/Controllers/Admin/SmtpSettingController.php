<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SmtpSetting;

class SmtpSettingController extends Controller
{
    /**
     * Display the SMTP settings page.
     */
    public function index()
    {
        $data['header_title'] = 'SMTP Setting';
        $data['settings'] = SmtpSetting::getSingle();
        return view('admin.smtp_settings', $data);
    }

    /**
     * Update the SMTP settings.
     */
    public function update(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'mail_mailer' => 'required|string|max:255',
            'mail_host' => 'required|string|max:255',
            'mail_port' => 'required|string|max:255',
            'mail_username' => 'nullable|string|max:255',
            'mail_password' => 'nullable|string|max:255',
            'mail_encryption' => 'nullable|string|max:255',
            'mail_from_address' => 'required|email|max:255',
        ]);

        $settings = SmtpSetting::getSingle();
        
        $settings->name = trim($request->name);
        $settings->mail_mailer = trim($request->mail_mailer);
        $settings->mail_host = trim($request->mail_host);
        $settings->mail_port = trim($request->mail_port);
        $settings->mail_username = trim($request->mail_username);
        $settings->mail_password = trim($request->mail_password);
        $settings->mail_encryption = trim($request->mail_encryption);
        $settings->mail_from_address = trim($request->mail_from_address);
        
        $settings->save();

        return redirect()->back()->with('success', 'SMTP Settings updated successfully.');
    }
}
