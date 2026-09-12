<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Lib\OTPManager;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Psr\SimpleCache\InvalidArgumentException;

class OTPController extends Controller
{
    /**
     * @throws InvalidArgumentException
     */
    public function otpForm()
    {
        $pageTitle = 'OTP Verification';
        $user      = auth('web')->user();
        $sendVia   = session('transaction_state_information')['send_via'];

        $storage       = Cache::store('file');
        $key           = 'otp_' . md5($user->email);
        $storedData    = $storage->get($key);
        $remainingTime = $storedData['expires_at'] - time();

        return view("{$this->activeTheme}user.page.otp", compact('pageTitle', 'user', 'sendVia', 'remainingTime'));
    }

    /**
     * @throws InvalidArgumentException
     */
    public function verify(Request $request)
    {
        $validated = $request->validate([
            'code'   => 'required|array|size:6',
            'code.*' => 'required|integer|digits:1',
        ]);

        $user = auth('web')->user();
        $code = implode('', $validated['code']);

        if (!OTPManager::make()->validateOTP($user->email, $code)) {
            return back()->with('toasts', [
                ['error', 'The OTP you provided is invalid. Please try again.']
            ]);
        }

        $transactionStateInformation = session('transaction_state_information');

        session()->put('transaction_state_information', [
            ...$transactionStateInformation,
            'otp_verified' => true,
        ]);

        return to_route($transactionStateInformation['redirect_route']);
    }

    /**
     * @throws InvalidArgumentException
     */
    public function regenerate()
    {
        $user    = auth('web')->user();
        $sendVia = session('transaction_state_information')['send_via'];

        if (!in_array($sendVia, ['email', 'sms'])) {
            return to_route('user.home')->with('toasts', [
                ['error', 'Invalid OTP sending method!']
            ]);
        }

        try {
            OTPManager::make()->regenerateOTP($user->email)->sendOTP($sendVia);
        } catch (Exception $exception) {
            return back()->with('toasts', [
                ['error', $exception->getMessage()]
            ]);
        }

        return back()->with('toasts', [
            ['success', 'A new OTP has been sent to your ' . ($sendVia == 'email' ? 'email.' : 'mobile.')]
        ]);
    }
}
