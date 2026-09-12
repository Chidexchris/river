<?php

namespace App\Http\Controllers\User\Auth;

use App\Constants\ManageStatus;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    protected string $username;

    function __construct()
    {
        parent::__construct();
    }

    function loginForm()
    {
        $pageTitle    = 'Login';
        $loginContent = getSiteData('login.content', true);

        return view("{$this->activeTheme}user.auth.login", compact('pageTitle', 'loginContent'));
    }

    function findUsername()
    {
        $login     = request()->input('username');
        $fieldType = filter_var($login, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

        request()->merge([$fieldType => $login]);

        return $fieldType;
    }

    function username()
    {
        return $this->username = $this->findUsername();
    }

    /**
     * Get the guard to be used during authentication.
     *
     */
    protected function guard()
    {
        return auth()->guard('web');
    }

    protected function validateLogin()
    {
        $this->validate(request(), [
            $this->username() => 'required|string',
            'password'        => 'required|string',
        ]);
    }

    function authenticated(Request $request, $user)
    {
        $user->tc = $user->ts == ManageStatus::ACTIVE ? ManageStatus::UNVERIFIED : ManageStatus::VERIFIED;
        $user->save();

        return redirect()->intended($request->get('redirect', route('user.home')));
    }

    function login()
    {
        $this->validateLogin();

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
        if ($this->hasTooManyLoginAttempts(request())) {
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

        $toast[] = ['success', 'You have logged out'];

        return to_route('user.login')->with('toasts', $toast);
    }
}
