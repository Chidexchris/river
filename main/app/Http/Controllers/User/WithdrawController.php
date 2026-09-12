<?php

namespace App\Http\Controllers\User;

use App\Lib\FormProcessor;
use App\Lib\OTPManager;
use App\Models\Withdrawal;
use App\Models\Transaction;
use App\Models\WithdrawMethod;
use App\Constants\ManageStatus;
use App\Models\AdminNotification;
use App\Http\Controllers\Controller;
use Illuminate\Validation\Rule;
use Symfony\Component\HttpFoundation\Response;

class WithdrawController extends Controller
{
    public function withdraw()
    {
        $pageTitle = 'Withdraw Money';
        $methods   = WithdrawMethod::active()->get();
        $user      = auth('web')->user();

        return view("{$this->activeTheme}user.withdraw.index", compact('pageTitle', 'methods', 'user'));
    }

    public function store()
    {
        $settings = bs();

        $validated = $this->validate(request(), [
            'method_id'          => 'required|int|gt:0',
            'amount'             => 'required|numeric|gt:0',
            'authorization_mode' => [
                Rule::requiredIf(fn() => $settings->sms_based_otp || $settings->email_based_otp),
                'integer',
                Rule::in([ManageStatus::AUTHORIZATION_MODE_EMAIL, ManageStatus::AUTHORIZATION_MODE_SMS]),
            ],
        ]);

        $user   = auth('web')->user();
        $amount = $validated['amount'];
        $method = WithdrawMethod::where('id', $validated['method_id'])->active()->firstOrFail();

        if ($amount < $method->min_amount) {
            $toast[] = ['error', 'Requested amount cannot be less than the minimum amount'];

            return back()->with('toasts', $toast);
        }

        if ($amount > $method->max_amount) {
            $toast[] = ['error', 'Requested amount cannot be greater than the maximum amount'];

            return back()->with('toasts', $toast);
        }

        if ($amount > $user->balance) {
            $toast[] = ['error', "You don't have enough amount to make this withdrawal"];

            return back()->with('toasts', $toast);
        }

        $charge      = $method->fixed_charge + ($amount * $method->percent_charge / 100);
        $afterCharge = $amount - $charge;
        $finalAmount = $afterCharge * $method->rate;

        $withdraw               = new Withdrawal();
        $withdraw->method_id    = $method->id;
        $withdraw->user_id      = $user->id;
        $withdraw->amount       = $amount;
        $withdraw->currency     = $method->currency;
        $withdraw->rate         = $method->rate;
        $withdraw->charge       = $charge;
        $withdraw->final_amount = $finalAmount;
        $withdraw->after_charge = $afterCharge;
        $withdraw->trx          = getTrx();
        $withdraw->save();

        if ($settings->email_based_otp || $settings->sms_based_otp) {
            $sendVia = $validated['authorization_mode'] == ManageStatus::AUTHORIZATION_MODE_EMAIL ? 'email' : 'sms';

            OTPManager::make()->generateOTP($user->email)->sendOTP($sendVia);

            session()->put('transaction_state_information', [
                'withdraw_transaction' => $withdraw->trx,
                'send_via'             => $sendVia,
                'redirect_route'       => 'user.withdraw.preview',
            ]);

            return to_route('user.otp.form');
        }

        session()->put('transaction_state_information', [
            'withdraw_transaction' => $withdraw->trx,
        ]);

        return to_route('user.withdraw.preview');
    }

    public function preview()
    {
        $settings                    = bs();
        $transactionStateInformation = session('transaction_state_information', []);

        if ($settings->email_based_otp || $settings->sms_based_otp) {
            if (!($transactionStateInformation['otp_verified'] ?? false)) {
                return to_route('user.withdraw')->with('toasts', [
                    ['error', 'Authorization failed: OTP verification is required to proceed with the transaction.'],
                ]);
            }
        } else {
            if (!($transactionStateInformation['withdraw_transaction'] ?? false)) {
                return to_route('user.withdraw')->with('toasts', [
                    ['error', 'Transaction failed: No withdraw transaction found!'],
                ]);
            }
        }

        $pageTitle = 'Withdraw Preview';
        $withdraw  = Withdrawal::with(['method', 'user'])
            ->where('trx', $transactionStateInformation['withdraw_transaction'])
            ->initiate()
            ->firstOrFail();
        $user      = $withdraw->user;

        return view("{$this->activeTheme}user.withdraw.preview", compact('pageTitle', 'withdraw', 'user'));
    }

    public function submit()
    {
        $transactionStateInformation = session('transaction_state_information', []);

        $withdraw = Withdrawal::with(['method.form', 'user'])
            ->where('trx', $transactionStateInformation['withdraw_transaction'])
            ->initiate()
            ->firstOrFail();

        $method = $withdraw->method;

        if ($method->status == ManageStatus::INACTIVE) abort(Response::HTTP_NOT_FOUND);

        $formData       = $method->form->form_data;
        $formProcessor  = new FormProcessor();
        $validationRule = $formProcessor->valueValidation($formData);

        $this->validate(request(), $validationRule);

        $user = $withdraw->user;

        if ($user->ts) {
            $response = verifyG2fa($user, request('authenticator_code'));

            if (!$response) {
                $toast[] = ['error', 'Wrong verification code'];

                return back()->with('toasts', $toast);
            }
        }

        if ($withdraw->amount > $user->balance) {
            $toast[] = ['error', "You don't have enough amount to make this withdrawal"];

            return back()->with('toasts', $toast);
        }

        $withdraw->withdraw_information = $formProcessor->processFormData(request(), $formData);
        $withdraw->status               = ManageStatus::PAYMENT_PENDING;
        $withdraw->save();

        $user->balance -= $withdraw->amount;
        $user->save();

        $transaction               = new Transaction();
        $transaction->user_id      = $withdraw->user_id;
        $transaction->amount       = $withdraw->amount;
        $transaction->post_balance = $user->balance;
        $transaction->charge       = $withdraw->charge;
        $transaction->trx_type     = '-';
        $transaction->details      = showAmount($withdraw->final_amount) . ' ' . $withdraw->currency . ' Withdraw via ' . $withdraw->method->name;
        $transaction->trx          = $withdraw->trx;
        $transaction->remark       = 'withdraw';
        $transaction->save();

        $adminNotification            = new AdminNotification();
        $adminNotification->user_id   = $user->id;
        $adminNotification->title     = "New withdrawal request from $user->username";
        $adminNotification->click_url = urlPath('admin.withdrawals.pending');
        $adminNotification->save();

        notify($user, 'WITHDRAW_REQUEST', [
            'method_name'     => $withdraw->method->name,
            'method_amount'   => showAmount($withdraw->final_amount),
            'method_currency' => $withdraw->currency,
            'amount'          => showAmount($withdraw->amount),
            'charge'          => showAmount($withdraw->charge),
            'rate'            => showAmount($withdraw->rate),
            'trx'             => $withdraw->trx,
            'post_balance'    => showAmount($user->balance),
        ]);

        session()->forget('transaction_state_information');

        $toast[] = ['success', 'Withdrawal request has submitted'];

        return to_route('user.withdraw.history')->with('toasts', $toast);
    }

    public function withdrawHistory()
    {
        $pageTitle   = 'Withdraw History';
        $user        = auth('web')->user();
        $withdrawals = $user->withdrawals()
            ->with('method')
            ->index()
            ->searchable(['trx'])
            ->latest()
            ->paginate(getPaginate());

        return view("{$this->activeTheme}user.withdraw.history", compact('pageTitle', 'withdrawals', 'user'));
    }
}
