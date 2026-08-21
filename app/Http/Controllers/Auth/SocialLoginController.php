<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Socialite\Facades\Socialite;
use Exception;

class SocialLoginController extends Controller
{
    // --- GOOGLE ---
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();
            
            // Check if user exists by google_id or email
            $user = User::where('google_id', $googleUser->id)
                        ->orWhere('email', $googleUser->email)
                        ->first();

            if ($user) {
                // Update google_id and avatar if missing
                if (empty($user->google_id)) {
                    $user->google_id = $googleUser->id;
                    $user->avatar = $googleUser->avatar;
                    $user->save();
                }
                Auth::login($user);
            } else {
                // Create a new user
                $newUser = User::create([
                    'name' => $googleUser->name,
                    'email' => $googleUser->email,
                    'google_id' => $googleUser->id,
                    'avatar' => $googleUser->avatar,
                    'password' => Hash::make(uniqid('social_')), // secure random password
                    'is_admin' => 0,
                    'status' => 0,
                    'is_delete' => 0,
                ]);
                Auth::login($newUser);
            }

            return redirect(url('/'))->with('success', 'Logged in with Google successfully!');

        } catch (Exception $e) {
            return redirect(url('/'))->withErrors(['login_error' => 'Something went wrong with Google Login: ' . $e->getMessage()]);
        }
    }

    // --- FACEBOOK ---
    public function redirectToFacebook()
    {
        return Socialite::driver('facebook')->redirect();
    }

    public function handleFacebookCallback()
    {
        try {
            $facebookUser = Socialite::driver('facebook')->user();
            
            $user = User::where('facebook_id', $facebookUser->id)
                        ->orWhere('email', $facebookUser->email)
                        ->first();

            if ($user) {
                if (empty($user->facebook_id)) {
                    $user->facebook_id = $facebookUser->id;
                    $user->avatar = $facebookUser->avatar;
                    $user->save();
                }
                Auth::login($user);
            } else {
                $newUser = User::create([
                    'name' => $facebookUser->name,
                    'email' => $facebookUser->email ?: $facebookUser->id . '@facebook.com', // Facebook email can be null if not verified
                    'facebook_id' => $facebookUser->id,
                    'avatar' => $facebookUser->avatar,
                    'password' => Hash::make(uniqid('social_')),
                    'is_admin' => 0,
                    'status' => 0,
                    'is_delete' => 0,
                ]);
                Auth::login($newUser);
            }

            return redirect(url('/'))->with('success', 'Logged in with Facebook successfully!');

        } catch (Exception $e) {
            return redirect(url('/'))->withErrors(['login_error' => 'Something went wrong with Facebook Login: ' . $e->getMessage()]);
        }
    }
}
