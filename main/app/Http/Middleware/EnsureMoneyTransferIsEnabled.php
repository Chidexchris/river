<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureMoneyTransferIsEnabled
{
    /**
     * Handle an incoming request.
     *
     * @param Closure(Request): (Response) $next
     */
    public function handle(Request $request, Closure $next, string $transferType = null): Response
    {
        // Map transfer types to settings and error messages
        $transferFeatures = [
            'within_bank'   => [
                'setting' => 'internal_bank_transfer',
                'error'   => 'The Within Bank Transfer feature is currently disabled.',
            ],
            'other_bank'    => [
                'setting' => 'external_bank_transfer',
                'error'   => 'The Other Bank Transfer feature is currently disabled.',
            ],
            'wire_transfer' => [
                'setting' => 'wire_transfer',
                'error'   => 'The Wire Transfer feature is currently disabled.',
            ],
        ];

        if (isset($transferFeatures[$transferType]) && !bs($transferFeatures[$transferType]['setting'])) {
            $toast[] = ['error', $transferFeatures[$transferType]['error']];

            return redirect('/user/dashboard')->with('toasts', $toast);
        }

        return $next($request);
    }
}
