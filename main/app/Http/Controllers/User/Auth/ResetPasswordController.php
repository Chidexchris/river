<?php

namespace App\Http\Controllers\User\Auth;

use App\Http\Controllers\Controller;
use App\Models\PasswordReset;
use App\Models\User;
use App\Rules\StrongPassword;
use Illuminate\Support\Facades\Hash;

class ResetPasswordController extends Controller
{
    function resetForm($code = null)
    {
        $pageTitle            = 'Reset Password';
        $email                = session('fpass_email');
        $passwordResetContent = getSiteData('password_reset.content', true);

        if (PasswordReset::where('code', $code)->where('email', $email)->count() != 1) {
            $toast[] = ['error', 'Invalid verification code'];

            return to_route('user.password.request.form')->with('toasts', $toast);
        }

        return view("{$this->activeTheme}user.auth.password.reset", compact('code', 'email', 'pageTitle', 'passwordResetContent'));
    }

    function resetPassword()
    {
        $this->validate(request(), [
            'code'     => 'required|int',
            'email'    => 'required|email',
            'password' => ['required', 'confirmed', new StrongPassword],
        ]);

        $email     = request('email');
        $checkCode = PasswordReset::where('code', request('code'))->where('email', $email)->latest()->first();

        if (!$checkCode) {
            $toast[] = ['error', 'Invalid verification code'];

            return to_route('user.password.request.form')->with('toasts', $toast);
        }

        $user           = User::where('email', $email)->first();
        $user->password = Hash::make(request('password'));
        $user->save();

        $userIpInfo      = getIpInfo();
        $userBrowserInfo = osBrowser();

        notify($user, 'PASS_RESET_DONE', [
            'operating_system' => $userBrowserInfo['os_platform'],
            'browser'          => $userBrowserInfo['browser'],
            'ip'               => $userIpInfo['ip'],
            'time'             => $userIpInfo['time'],
        ], ['email']);

        session()->forget('fpass_email');

        $toast[] = ['success', 'Your password has been successfully reset'];

        return to_route('user.login.form')->with('toasts', $toast);
    }
}
