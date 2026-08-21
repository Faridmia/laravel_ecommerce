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

    public function userRegister(Request $request)
    {
        $request->validate([
            'name' => 'required|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
        ]);

        $user = new \App\Models\User();
        $user->name = trim($request->name);
        $user->email = trim($request->email);
        $user->password = Hash::make($request->password);
        $user->is_admin = 0; // customer
        $user->status = 0; // active/verified
        $user->is_delete = 0;
        $user->save();

        Auth::login($user);

        return redirect()->back()->with('success', 'Registration successful!');
    }

    public function userLogin(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $remember = $request->has('remember');

        if (Auth::attempt([
            'email' => $request->email,
            'password' => $request->password,
            'is_delete' => 0
        ], $remember)) {

            $request->session()->regenerate();

            return redirect()->back()->with('success', 'Logged in successfully!');
        }

        return redirect()->back()->withErrors(['login_error' => 'Invalid email or password.'])->withInput();
    }

    public function userLogout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect(url('/'))->with('success', 'Logged out successfully!');
    }
}
