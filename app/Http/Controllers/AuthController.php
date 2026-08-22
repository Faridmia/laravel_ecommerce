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
        $user->email_verified_at = now(); // Automatically verify email
        $user->save();

        // Notify admins about new registration
        \App\Models\NotificationModel::notifyAdmins("New Customer Register #" . $user->name, route('admin.customer.list'));

        // Notify user about registration
        \App\Models\NotificationModel::notifyUser($user->id, "Welcome to Molla! Your account has been created successfully.", route('user.dashboard'));

        // event(new \Illuminate\Auth\Events\Registered($user)); // Uncomment to enable email verification in the future

        Auth::login($user);

        return redirect()->route('user.dashboard')->with('success', 'Registration successful! Welcome to your dashboard.');
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

    /**
     * Show the email verification notice.
     */
    public function verifyNotice()
    {
        return auth()->user()->hasVerifiedEmail()
            ? redirect()->intended(route('user.dashboard'))
            : view('auth.verify');
    }

    /**
     * Handle the email verification.
     */
    public function verifyEmail(\Illuminate\Foundation\Auth\EmailVerificationRequest $request)
    {
        $request->fulfill();
        return redirect()->route('user.dashboard')->with('success', 'Your email has been verified successfully!');
    }

    /**
     * Resend the email verification link.
     */
    public function verifyResend(Request $request)
    {
        $request->user()->sendEmailVerificationNotification();
        return back()->with('message', 'A fresh verification link has been sent to your email address.');
    }

    /**
     * Show the forgot password request page.
     */
    public function forgotPassword()
    {
        return view('auth.forgot');
    }

    /**
     * Send the password reset link to user.
     */
    public function sendResetLink(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
        ]);

        $status = \Illuminate\Support\Facades\Password::broker()->sendResetLink(
            $request->only('email')
        );

        return $status === \Illuminate\Support\Facades\Password::RESET_LINK_SENT
            ? back()->with('success', __($status))
            : back()->withErrors(['email' => __($status)]);
    }

    /**
     * Show the password reset update form.
     */
    public function resetPasswordForm(Request $request, $token)
    {
        return view('auth.reset', [
            'token' => $token,
            'email' => $request->email
        ]);
    }

    /**
     * Handle the password reset submission.
     */
    public function resetPasswordUpdate(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email|exists:users,email',
            'password' => 'required|min:6|confirmed',
        ]);

        $status = \Illuminate\Support\Facades\Password::broker()->reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->forceFill([
                    'password' => \Illuminate\Support\Facades\Hash::make($password)
                ])->setRememberToken(\Illuminate\Support\Str::random(60));

                $user->save();

                event(new \Illuminate\Auth\Events\PasswordReset($user));
            }
        );

        return $status === \Illuminate\Support\Facades\Password::PASSWORD_RESET
            ? redirect(url('/'))->with('success', 'Your password has been reset successfully! You can now log in using the Sign In modal.')
            : back()->withErrors(['email' => __($status)]);
    }
}
