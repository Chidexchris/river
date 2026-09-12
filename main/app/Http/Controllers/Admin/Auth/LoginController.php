<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    public string $redirectTo = 'admin';

    function loginForm()
    {
        $pageTitle = 'Admin Login';

        return view('admin.auth.login', compact('pageTitle'));
    }

    protected function guard()
    {
        return auth()->guard('admin');
    }

    function username()
    {
        return 'username';
    }

    function login()
    {
        $this->validateLogin(request());

        request()->session()->regenerateToken();

        if (!verifyCaptcha()) {
            $toast[] = ['error', 'Invalid captcha provided'];

            return back()->with('toasts', $toast);
        }

        /**
         * If the class is using the ThrottlesLogins trait, we can automatically throttle
         * the login attempts for this application. We'll key this by the username and
         * the IP address of the client making these requests into this application.
         */
        if (method_exists($this, 'hasTooManyLoginAttempts') && $this->hasTooManyLoginAttempts(request())) {
            $this->fireLockoutEvent(request());

            return $this->sendLockoutResponse(request());
        }

        if ($this->attemptLogin(request())) {
            return $this->sendLoginResponse(request());
        }

        /**
         * If the login attempt was unsuccessful we will increment the number of attempts
         * for login and redirect the user back to the login form. Of course, when this
         * user surpasses their maximum number of attempts they will get locked out.
         */
        $this->incrementLoginAttempts(request());

        return $this->sendFailedLoginResponse(request());
    }

    function logout()
    {
        $this->guard()->logout();

        request()->session()->invalidate();

        return $this->loggedOut(request()) ?: redirect($this->redirectTo);
    }
}
