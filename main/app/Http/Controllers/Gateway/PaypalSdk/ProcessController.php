<?php

namespace App\Http\Controllers\Gateway\PaypalSdk;

use App\Models\Deposit;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Gateway\PaymentController;
use App\Http\Controllers\Gateway\PaypalSdk\Core\PayPalHttpClient;
use App\Http\Controllers\Gateway\PaypalSdk\PayPalHttp\HttpException;
use App\Http\Controllers\Gateway\PaypalSdk\Core\ProductionEnvironment;
use App\Http\Controllers\Gateway\PaypalSdk\Orders\OrdersCreateRequest;
use App\Http\Controllers\Gateway\PaypalSdk\Orders\OrdersCaptureRequest;

class ProcessController extends Controller
{
    public static function process(Deposit $deposit)
    {
        $paypalAcc = json_decode($deposit->gatewayCurrency()->gateway_parameter);

        // Creating an environment
        $clientId     = $paypalAcc->clientId;
        $clientSecret = $paypalAcc->clientSecret;
        $environment  = new ProductionEnvironment($clientId, $clientSecret);
        $client       = new PayPalHttpClient($environment);
        $request      = new OrdersCreateRequest();

        $request->prefer('return=representation');

        $request->body = [
            "intent"              => "CAPTURE",
            "purchase_units"      => [
                [
                    "reference_id" => $deposit->trx,
                    "amount"       => [
                        "value"         => number_format($deposit->final_amount, 2),
                        "currency_code" => $deposit->method_currency,
                    ],
                ],
            ],
            "application_context" => [
                "cancel_url" => route(gatewayRedirectUrl(false)),
                "return_url" => route('ipn.' . $deposit->gateway->alias),
            ],
        ];

        try {
            $response            = $client->execute($request);
            $deposit->btc_wallet = $response->result->id;
            $deposit->save();

            $send['redirect']     = true;
            $send['redirect_url'] = $response->result->links[1]->href;
        } catch (HttpException $ex) {
            $send['error']   = true;
            $send['message'] = 'Failed to process with api';
        }

        return json_encode($send);
    }

    public function ipn()
    {
        $request = new OrdersCaptureRequest($_GET['token']);

        $request->prefer('return=representation');

        $deposit = Deposit::where('btc_wallet', $_GET['token'])->initiate()->first();

        try {
            $paypalAcc    = json_decode($deposit->gatewayCurrency()->gateway_parameter);
            $clientId     = $paypalAcc->clientId;
            $clientSecret = $paypalAcc->clientSecret;
            $environment  = new ProductionEnvironment($clientId, $clientSecret);
            $client       = new PayPalHttpClient($environment);
            $response     = $client->execute($request);

            if (
                isset($response->result) && 
                is_object($response->result) && 
                property_exists($response->result, 'status') && 
                $response->result->status == 'COMPLETED'
            ) {
                $deposit->details = json_decode(json_encode($response->result->payer));
                $deposit->save();

                PaymentController::dataUpdate($deposit);
                $toast[] = ['success', 'Payment completed successfully'];

                return to_route(gatewayRedirectUrl(true))->with('toasts', $toast);
            } else {
                $toast[] = ['error', 'Payment captured failed'];

                return to_route(gatewayRedirectUrl(false))->with('toasts', $toast);
            }
        } catch (HttpException $ex) {
            return to_route(gatewayRedirectUrl(false));
        }
    }
}
