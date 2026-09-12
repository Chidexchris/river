<?php

namespace App\Http\Controllers\Gateway\StripeJs;

use Exception;
use Stripe\Charge;
use Stripe\Stripe;
use Stripe\Customer;
use App\Models\Deposit;
use Illuminate\Http\Request;
use App\Constants\ManageStatus;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Gateway\PaymentController;

class ProcessController extends Controller
{
    public static function process(Deposit $deposit)
    {
        $stripeJSAcc        = json_decode($deposit->gatewayCurrency()->gateway_parameter);
        $val['key']         = $stripeJSAcc->publishable_key;
        $val['name']        = $deposit->user->fullname;
        $val['description'] = "Payment with Stripe";
        $val['amount']      = round($deposit->final_amount, 2) * 100;
        $val['currency']    = $deposit->method_currency;
        $send['val']        = $val;
        $alias              = $deposit->gateway->alias;

        $send['src']    = "https://checkout.stripe.com/checkout.js";
        $send['view']   = 'user.payment.' . $alias;
        $send['method'] = 'post';
        $send['url']    = route('ipn.' . $deposit->gateway->alias);

        return json_encode($send);
    }

    public function ipn(Request $request)
    {
        $track   = session()->get('Track');
        $deposit = Deposit::where('trx', $track)->first();

        if ($deposit->status == ManageStatus::PAYMENT_SUCCESS) {
            $toast[] = ['error', 'Invalid request.'];

            return to_route(gatewayRedirectUrl(false))->with('toasts', $toast);
        }

        $stripeJSAcc = json_decode($deposit->gatewayCurrency()->gateway_parameter);

        Stripe::setApiKey($stripeJSAcc->secret_key);
        Stripe::setApiVersion("2020-03-02");

        try {
            $customer = Customer::create([
                'email'  => $request->stripeEmail,
                'source' => $request->stripeToken,
            ]);
        } catch (Exception $e) {
            $toast[] = ['error', $e->getMessage()];

            return to_route(gatewayRedirectUrl(false))->with('toasts', $toast);
        }

        try {
            $charge = Charge::create([
                'customer'    => $customer->id,
                'description' => 'Payment with Stripe',
                'amount'      => round($deposit->final_amount, 2) * 100,
                'currency'    => $deposit->method_currency,
            ]);
        } catch (Exception $e) {
            $toast[] = ['error', $e->getMessage()];

            return to_route(gatewayRedirectUrl(false))->with('toasts', $toast);
        }

        if ($charge['status'] == 'succeeded') {
            PaymentController::dataUpdate($deposit);
            $toast[] = ['success', 'Payment completed successfully'];

            return to_route(gatewayRedirectUrl(true))->with('toasts', $toast);
        } else {
            $toast[] = ['error', 'Failed to process'];

            return to_route(gatewayRedirectUrl(false))->with('toasts', $toast);
        }
    }
}
