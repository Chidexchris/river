<?php

namespace App\Http\Controllers\Staff\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    public string $redirectTo = 'staff/dashboard';

    public function showLoginForm()
    {
        $pageTitle = 'Staff Login';

        return view('staff.auth.login', compact('pageTitle'));
    }

    public function login(Request $request)
    {
        $this->validateLogin($request);

        if (!verifyCaptcha()) {
            $toast[] = ['error', 'Invalid captcha provided'];

            return back()->with('toasts', $toast);
        }

        if (method_exists($this, 'hasTooManyLoginAttempts') && $this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);

            return $this->sendLockoutResponse($request);
        }

        if ($this->attemptLogin($request)) {
            if ($request->hasSession()) {
                $request->session()->put('auth.password_confirmed_at', time());
            }

            return $this->sendLoginResponse($request);
        }

        $this->incrementLoginAttempts($request);

        return $this->sendFailedLoginResponse($request);
    }

    public function username()
    {
        $value = request()->input('username');

        if (filter_var($value, FILTER_VALIDATE_EMAIL)) {
            request()->merge(['email_address' => $value]);

            return 'email_address';
        }

        return 'username';
    }

    protected function loggedOut(Request $request)
    {
        return redirect()->intended('staff');
    }

    protected function guard()
    {
        return Auth::guard('staff');
    }
}
