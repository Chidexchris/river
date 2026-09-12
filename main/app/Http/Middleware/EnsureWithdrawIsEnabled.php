<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureWithdrawIsEnabled
{
    /**
     * Handle an incoming request.
     *
     * @param Closure(Request): (Response) $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!bs('withdraw')) {
            $toast[] = ['error', 'The Withdraw feature is currently disabled.'];

            return redirect('/user/dashboard')->with('toasts', $toast);
        }

        return $next($request);
    }
}
