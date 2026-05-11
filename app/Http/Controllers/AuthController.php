<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Hash;
use Auth;
class AuthController extends Controller
{
    //
    public function login_admin()
    {   
        if( !empty(auth()->user()) && auth()->user()->is_admin == 1){
            return redirect()->route('admin.dashboard');
        }

        return view('admin.auth.login');
    }

    public function auth_login_admin(Request $request)
    {
       
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $remember = $request->has('remember');

        if (auth()->attempt([
            'email' => $request->email,
            'password' => $request->password,
            'is_admin' => 1
        ], $remember)) {

            $request->session()->regenerate();

            return redirect()->route('admin.dashboard')
                ->with('success', 'Login successful');

        } else {

            return redirect()->back()
                ->withErrors(['email' => 'Invalid credentials'])
                ->withInput(); 
        }
    }

    public function logout_admin(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login');
    }
}
