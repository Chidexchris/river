<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class DeductStatementFee
{
    /**
     * Handle an incoming request.
     *
     * @param Closure(Request): (Response) $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        return $next($request);
    }

    /**
     * Handle tasks after the response has been sent to the browser.
     */
    public function terminate(Request $request, Response $response): void
    {
        $settings             = bs();
        $statementDownloadFee = $settings->statement_download_fee;

        if ($statementDownloadFee > 0) {
            $user = auth('web')->user();

            $user->decrement('balance', $statementDownloadFee);

            $formattedAmount = showAmount($statementDownloadFee);
            $currency        = $settings->site_cur;

            $user->transactions()->create([
                'amount'       => $statementDownloadFee,
                'post_balance' => $user->balance,
                'trx_type'     => '-',
                'trx'          => getTrx(),
                'details'      => "A fee of $formattedAmount $currency has been deducted for the statement download.",
                'remark'       => 'statement_fee',
            ]);
        }
    }
}
