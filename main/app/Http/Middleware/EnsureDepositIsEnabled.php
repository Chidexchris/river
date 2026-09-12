<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureDepositIsEnabled
{
    /**
     * Handle an incoming request.
     *
     * @param Closure(Request): (Response) $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!bs('deposit')) {
            $toast[] = ['error', 'The Deposit feature is currently disabled.'];

            return redirect('/user/dashboard')->with('toasts', $toast);
        }

        return $next($request);
    }
}
