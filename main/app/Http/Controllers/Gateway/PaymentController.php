<?php

namespace App\Http\Controllers\Gateway;

use App\Models\Deposit;
use App\Lib\FormProcessor;
use App\Models\Transaction;
use App\Constants\ManageStatus;
use App\Models\GatewayCurrency;
use App\Models\AdminNotification;
use App\Http\Controllers\Controller;
use Symfony\Component\HttpFoundation\Response;

class PaymentController extends Controller
{
    public function depositInsert()
    {
        $this->validate(request(), [
            'currency' => 'required|string',
            'gateway'  => 'required|exists:gateways,code',
            'amount'   => 'required|numeric|gt:0',
            'deposit_plan_id' => 'nullable|integer', // Add validation for deposit_plan_id
        ]);

        $gatewayData = GatewayCurrency::whereHas('method', fn($gateway) => $gateway->active())
            ->where('method_code', request('gateway'))
            ->where('currency', request('currency'))
            ->first();

        if (!$gatewayData) {
            $toast[] = ['error', 'Invalid gateway'];

            return back()->with('toasts', $toast);
        }

        $amount = request('amount');

        if ($gatewayData->min_amount > $amount || $gatewayData->max_amount < $amount) {
            $toast[] = ['error', 'Please follow the limit'];

            return back()->with('toasts', $toast);
        }

        $charge      = $gatewayData->fixed_charge + (($amount * $gatewayData->percent_charge) / 100);
        $payable     = $amount + $charge;
        $finalAmount = $payable * $gatewayData->rate;

        // store data
        $deposit                  = new Deposit();
        $deposit->user_id         = auth('web')->id();
        $deposit->method_code     = $gatewayData->method_code;
        $deposit->method_currency = strtoupper($gatewayData->currency);
        $deposit->amount          = $amount;
        $deposit->deposit_plan_id = request('deposit_plan_id'); // Store deposit_plan_id
        $deposit->charge          = $charge;
        $deposit->rate            = $gatewayData->rate;
        $deposit->final_amount    = $finalAmount;
        $deposit->trx             = getTrx();
        $deposit->save();

        session()->put('Track', $deposit->trx);

        return to_route('user.deposit.confirm');
    }

    public function depositConfirm()
    {
        $track   = session('Track');
        $deposit = Deposit::with('gateway')->where('trx', $track)->initiate()->firstOrFail();

        if ($deposit->method_code >= 1000) return to_route('user.deposit.manual.confirm');

        $user      = auth('web')->user();
        $pageTitle = 'Deposit Confirmation';
        $label     = 'Your deposit amount will be';

        $dirName = $deposit->gateway->alias;
        $new     = __NAMESPACE__ . '\\' . $dirName . '\\ProcessController';
        $data    = $new::process($deposit);
        $data    = json_decode($data);

        if (isset($data->error)) {
            $toast[] = ['error', $data->message];

            return to_route(gatewayRedirectUrl(false))->with('toasts', $toast);
        }

        if (isset($data->redirect)) return redirect($data->redirect_url);

        // For Stripe V3
        if (isset($data->session)) {
            $deposit->btc_wallet = $data->session->id;
            $deposit->save();
        }

        return view($this->activeTheme . $data->view, compact('data', 'pageTitle', 'label', 'deposit', 'user'));
    }

    public static function dataUpdate($deposit, $isManual = null)
    {
        if ($deposit->status == ManageStatus::PAYMENT_INITIATE || $deposit->status == ManageStatus::PAYMENT_PENDING) {
            $deposit->status = ManageStatus::PAYMENT_SUCCESS;
            $deposit->save();

            $user          = $deposit->user;
            $user->balance += $deposit->amount;
            $user->save();

            $transaction               = new Transaction();
            $transaction->user_id      = $deposit->user_id;
            $transaction->amount       = $deposit->amount;
            $transaction->charge       = $deposit->charge;
            $transaction->post_balance = $user->balance;
            $transaction->trx_type     = '+';
            $transaction->trx          = getTrx();
            $transaction->details      = 'Deposit money via ' . $deposit->gatewayCurrency()->name;
            $transaction->remark       = 'deposit';
            $transaction->save();

            // notify admin
            if (!$isManual) {
                $currency      = bs('site_cur');
                $depositAmount = showAmount($deposit->amount);

                $adminNotification            = new AdminNotification();
                $adminNotification->user_id   = $deposit->user_id;
                $adminNotification->title     = "$user->fullname has deposited $depositAmount $currency via " . $deposit->gatewayCurrency()->name;
                $adminNotification->click_url = urlPath('admin.deposits.done');
                $adminNotification->save();
            }

            // notify user
            notify($user, $isManual ? 'DEPOSIT_APPROVE' : 'DEPOSIT_COMPLETE', [
                'method_name'     => $deposit->gatewayCurrency()->name,
                'method_currency' => $deposit->method_currency,
                'method_amount'   => showAmount($deposit->final_amount),
                'amount'          => showAmount($deposit->amount),
                'charge'          => showAmount($deposit->charge),
                'rate'            => showAmount($deposit->rate),
                'trx'             => $deposit->trx,
                'post_balance'    => showAmount($user->balance),
            ]);
        }
    }

    public function manualDepositConfirm()
    {
        $track   = session('Track');
        $deposit = Deposit::with('gateway')->where('trx', $track)->initiate()->first();

        if (!$deposit) return to_route(gatewayRedirectUrl(false));

        if ($deposit->method_code > 999) {
            $pageTitle   = 'Deposit Confirmation';
            $cardTitle   = 'Deposit money via';
            $previewText = 'You have requested to deposit money, which amount is';
            $gateway     = $deposit->gatewayCurrency()->method;
            $user        = auth('web')->user();

            return view("{$this->activeTheme}user.payment.manual", compact('deposit', 'pageTitle', 'gateway', 'cardTitle', 'previewText', 'user'));
        }

        abort(Response::HTTP_NOT_FOUND);
    }

    public function manualDepositUpdate()
    {
        $track   = session('Track');
        $deposit = Deposit::with('gateway')->where('trx', $track)->initiate()->first();

        if (!$deposit) return to_route(gatewayRedirectUrl(false));

        $gateway  = $deposit->gatewayCurrency()->method;
        $formData = $gateway->form->form_data;

        $formProcessor  = new FormProcessor();
        $validationRule = $formProcessor->valueValidation($formData);

        $this->validate(request(), $validationRule);

        $deposit->details = $formProcessor->processFormData(request(), $formData);
        $deposit->status  = ManageStatus::PAYMENT_PENDING;
        $deposit->save();

        $user = $deposit->user;

        $adminNotification            = new AdminNotification();
        $adminNotification->user_id   = $user->id;
        $adminNotification->title     = "$user->fullname has requested to deposit money via " . $deposit->gatewayCurrency()->name;
        $adminNotification->click_url = urlPath('admin.deposits.pending');
        $adminNotification->save();

        notify($user, 'DEPOSIT_REQUEST', [
            'method_name'     => $deposit->gatewayCurrency()->name,
            'method_amount'   => showAmount($deposit->final_amount),
            'method_currency' => $deposit->method_currency,
            'amount'          => showAmount($deposit->amount),
            'charge'          => showAmount($deposit->charge),
            'rate'            => showAmount($deposit->rate),
            'trx'             => $deposit->trx,
        ]);

        $toast[] = ['success', 'Your deposit request has been taken. Please wait for admin response'];

        return to_route('user.deposit.history')->with('toasts', $toast);
    }
}
