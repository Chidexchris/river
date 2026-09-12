<?php

namespace App\Http\Controllers\Staff\Auth;

use App\Http\Controllers\Controller;
use App\Models\Staff;
use App\Models\StaffPasswordResetToken;
use App\Rules\StrongPassword;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ResetPasswordController extends Controller
{
    public function showResetPasswordForm(string $token)
    {
        if (!session()->has('password_reset.staff_email')) {
            $toast[] = ['error', 'Unauthorized access. Please verify your email first.'];

            return to_route('staff.forgot.password.form')->with('toasts', $toast);
        }

        $pageTitle    = 'Reset Password';
        $emailAddress = session('password_reset.staff_email');

        return view('staff.auth.resetPassword', compact('pageTitle', 'emailAddress', 'token'));
    }

    public function resetPassword(Request $request)
    {
        $validated = $request->validate([
            'email_address' => 'required|email',
            'token'         => 'required|string|size:6',
            'password'      => ['required', 'confirmed', new StrongPassword],
        ]);

        $resetToken = StaffPasswordResetToken::where([
            'email' => $validated['email_address'],
            'token' => $validated['token'],
        ])->first();

        if (!$resetToken) {
            $toast[] = ['error', 'Invalid token provided.'];

            return to_route('staff.forgot.password.form')->with('toasts', $toast);
        }

        $staff = Staff::where('email_address', $validated['email_address'])->active()->firstOrFail();

        $staff->update([
            'password' => Hash::make($validated['password'])
        ]);

        session()->forget('password_reset.staff_email');

        // send mail to the staff
        $staffIpInfo      = getIpInfo();
        $staffBrowserInfo = osBrowser();

        notify($staff, 'PASS_RESET_DONE', [
            'ip'               => $staffIpInfo['ip'],
            'browser'          => $staffBrowserInfo['browser'],
            'operating_system' => $staffBrowserInfo['os_platform'],
            'time'             => $staffIpInfo['time'],
        ], ['email']);

        $toast[] = ['success', 'Your password has been reset'];

        return to_route('staff.login.form')->with('toasts', $toast);
    }
}
