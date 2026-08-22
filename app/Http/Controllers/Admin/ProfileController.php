<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class ProfileController extends Controller
{
    /**
     * Show the admin profile settings page.
     */
    public function index()
    {
        $data['header_title'] = 'Profile Settings';
        $data['user'] = Auth::user();
        return view('admin.profile', $data);
    }

    /**
     * Update the admin profile details.
     */
    public function update(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:20',
            'heading' => 'nullable|string|max:255',
            'intro' => 'nullable|string',
            'profile_pic' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $user->first_name = trim($request->first_name);
        $user->last_name = trim($request->last_name);
        $user->name = trim($request->first_name . ' ' . $request->last_name);
        $user->display_name = trim($request->first_name . ' ' . $request->last_name);
        $user->email = trim($request->email);
        $user->phone = trim($request->phone);
        $user->heading = trim($request->heading);
        $user->intro = trim($request->intro);

        if ($request->hasFile('profile_pic')) {
            $image = $request->file('profile_pic');
            if ($image->isValid()) {
                // Delete old profile picture if exists
                if (!empty($user->profile_pic) && file_exists(public_path('upload/profile/' . $user->profile_pic))) {
                    unlink(public_path('upload/profile/' . $user->profile_pic));
                }

                // Ensure the directory exists
                if (!file_exists(public_path('upload/profile'))) {
                    mkdir(public_path('upload/profile'), 0777, true);
                }

                $imageName = time() . '_' . $user->id . '.' . $image->getClientOriginalExtension();
                $image->move(public_path('upload/profile/'), $imageName);
                
                $user->profile_pic = $imageName;
            }
        }

        $user->save();

        return redirect()->back()->with('success', 'Profile settings updated successfully.');
    }

    /**
     * Update the admin password.
     */
    public function updatePassword(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'old_password' => 'required',
            'new_password' => 'required|min:6',
            'confirm_password' => 'required|same:new_password',
        ]);

        // Check if old password matches
        if (!Hash::check($request->old_password, $user->password)) {
            return redirect()->back()->withErrors(['old_password' => 'The provided password does not match your current password.']);
        }

        // Update password
        $user->password = Hash::make($request->new_password);
        $user->save();

        return redirect()->back()->with('success', 'Password updated successfully.');
    }
}
