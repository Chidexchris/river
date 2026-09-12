<?php

namespace App\Http\Controllers\Staff\Auth;

use App\Http\Controllers\Controller;
use App\Models\Staff;
use App\Models\StaffPasswordResetToken;
use Illuminate\Http\Request;

class ForgotPasswordController extends Controller
{
    public function showForgotPasswordForm()
    {
        $pageTitle = 'Forgot Password';

        return view('staff.auth.forgotPassword', compact('pageTitle'));
    }

    public function sendResetCode(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email'
        ]);

        if (!verifyCaptcha()) {
            $toast[] = ['error', 'Invalid captcha provided'];

            return back()->with('toasts', $toast);
        }

        $staff = Staff::where('email_address', $validated['email'])->active()->first();

        if (!$staff) {
            $toast[] = ['error', 'Staff not found or inactive.'];

            return back()->with('toasts', $toast);
        }

        $resetToken = StaffPasswordResetToken::updateOrCreate(
            [
                'email' => $validated['email'],
            ],
            [
                'token'      => verificationCode(6),
                'created_at' => now(),
            ]
        );

        session()->put('password_reset.staff_email', $validated['email']);

        // send mail to the staff
        $staffIpInfo      = getIpInfo();
        $staffBrowserInfo = osBrowser();

        notify($staff, 'PASS_RESET_CODE', [
            'code'             => $resetToken->token,
            'ip'               => $staffIpInfo['ip'],
            'browser'          => $staffBrowserInfo['browser'],
            'operating_system' => $staffBrowserInfo['os_platform'],
            'time'             => $staffIpInfo['time'],
        ], ['email']);

        $toast[] = ['success', 'A verification code has been sent to your email address.'];

        return to_route('staff.verify.code.form')->with('toasts', $toast);
    }

    public function showVerificationCodeForm()
    {
        $pageTitle = 'Verify Code';

        if (!session()->has('password_reset.staff_email')) {
            $toast[] = ['error', 'Oops! Something went wrong. Please try again.'];

            return to_route('staff.forgot.password.form')->with('toasts', $toast);
        }

        $emailAddress = session('password_reset.staff_email');

        return view('staff.auth.verifyCode', compact('pageTitle', 'emailAddress'));
    }

    public function verifyCode(Request $request)
    {
        $validated = $request->validate([
            'email'  => 'required|email|exists:staffs,email_address',
            'code'   => 'required|array|size:6',
            'code.*' => 'required|integer',
        ], [
            'code.*.required' => 'Each digit in the verification code is required.',
            'code.*.integer'  => 'Each digit in the verification code must be an integer.',
        ]);

        $token = implode('', $validated['code']);

        $resetToken = StaffPasswordResetToken::where([
            'email' => $validated['email'],
            'token' => $token,
        ])->first();

        if (!$resetToken) {
            $toast[] = ['error', 'Invalid token provided.'];

            return to_route('staff.forgot.password.form')->with('toasts', $toast);
        }

        $toast[] = ['success', 'You can now reset your password.'];

        return to_route('staff.reset.password.form', $resetToken->token)->with('toasts', $toast);
    }
}
