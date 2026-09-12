<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Constants\ManageStatus;
use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\AdminPasswordReset;
use App\Rules\StrongPassword;
use Illuminate\Support\Facades\Hash;

class ResetPasswordController extends Controller
{
    function resetForm($email, $verCode)
    {
        $pageTitle = 'Account Recovery';
        $checkCode = AdminPasswordReset::where('code', $verCode)->where('email', $email)->active()->first();

        if (!$checkCode) {
            $toast[] = ['error', 'Invalid verification code'];

            return to_route('admin.password.request.form')->with('toasts', $toast);
        }

        return view('admin.auth.reset', compact('pageTitle', 'email', 'verCode'));
    }

    function resetPassword()
    {
        $this->validate(request(), [
            'code'     => 'required|int',
            'email'    => 'required|email',
            'password' => ['required', 'confirmed', new StrongPassword],
        ]);

        $checkCode = AdminPasswordReset::where('code', request('code'))
            ->where('email', request('email'))
            ->active()
            ->latest()
            ->first();

        if (!$checkCode) {
            $toast[] = ['error', 'Invalid verification code'];

            return to_route('admin.password.request.form')->with('toasts', $toast);
        }

        $admin           = Admin::where('email', $checkCode->email)->first();
        $admin->password = Hash::make(request('password'));
        $admin->save();

        $checkCode->status = ManageStatus::INACTIVE;
        $checkCode->save();

        $toast[] = ['success', 'Your password has been successfully reset'];

        return to_route('admin.login.form')->with('toasts', $toast);
    }
}
