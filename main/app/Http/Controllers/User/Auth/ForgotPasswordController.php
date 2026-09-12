<?php

namespace App\Http\Controllers\User\Auth;

use App\Http\Controllers\Controller;
use App\Models\PasswordReset;
use App\Models\User;

class ForgotPasswordController extends Controller
{
    function requestForm()
    {
        $pageTitle             = 'Forgot Password';
        $forgotPasswordContent = getSiteData('forgot_password.content', true);

        return view("{$this->activeTheme}user.auth.password.email", compact('pageTitle', 'forgotPasswordContent'));
    }

    function sendResetCode()
    {
        $this->validate(request(), [
            'value' => 'required',
        ]);

        if (!verifyCaptcha()) {
            $toast[] = ['error', 'Invalid captcha provided'];

            return back()->with('toasts', $toast);
        }

        $fieldType = $this->findFieldType();
        $user      = User::where($fieldType, request('value'))->first();

        if (!$user) {
            $toast[] = ['error', 'The provided information does not match any existing account'];

            return back()->with('toasts', $toast);
        }

        PasswordReset::where('email', $user->email)->delete();

        $verCode               = verificationCode(6);
        $passReset             = new PasswordReset();
        $passReset->email      = $user->email;
        $passReset->code       = $verCode;
        $passReset->created_at = now();
        $passReset->save();

        $userIpInfo      = getIpInfo();
        $userBrowserInfo = osBrowser();

        notify($user, 'PASS_RESET_CODE', [
            'code'             => $verCode,
            'operating_system' => $userBrowserInfo['os_platform'],
            'browser'          => $userBrowserInfo['browser'],
            'ip'               => $userIpInfo['ip'],
            'time'             => $userIpInfo['time'],
        ], ['email']);

        session()->put('user_pass_res_email', $user->email);

        $toast[] = ['success', 'Well, we have found you as a registered one'];

        return to_route('user.password.code.verification.form')->with('toasts', $toast);
    }

    function findFieldType()
    {
        $input     = request('value');
        $fieldType = filter_var($input, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

        request()->merge([$fieldType => $input]);

        return $fieldType;
    }

    function verificationForm()
    {
        $pageTitle         = 'Code Verification';
        $email             = session()->get('user_pass_res_email');
        $codeVerifyContent = getSiteData('code_verification.content', true);

        if (!$email) {
            $toast[] = ['error', 'Oops! session expired'];

            return to_route('user.password.request.form')->with('toasts', $toast);
        }

        return view("{$this->activeTheme}user.auth.password.codeVerification", compact('pageTitle', 'email', 'codeVerifyContent'));
    }

    function verificationCode()
    {
        $this->validate(request(), [
            'code'   => 'required|array|min:6',
            'code.*' => 'required|integer',
            'email'  => 'required|email',
        ], [
            'code.*.required' => 'All code field is required',
            'code.*.integer'  => 'All code should be integer',
        ]);

        $email   = request('email');
        $verCode = (int)implode("", request('code'));

        if (PasswordReset::where('code', $verCode)->where('email', $email)->count() != 1) {
            $toast[] = ['error', 'Invalid verification code'];

            return to_route('user.password.request.form')->with('toasts', $toast);
        }

        session()->put('fpass_email', $email);

        $toast[] = ['success', 'You can now reset your password'];

        return to_route('user.password.reset.form', $verCode)->with('toasts', $toast);
    }
}
