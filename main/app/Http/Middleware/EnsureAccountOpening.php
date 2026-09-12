<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureAccountOpening
{
    /**
     * Handle an incoming request.
     *
     * @param Closure(Request): (Response) $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!bs('open_account')) {
            $toast[] = ['error', 'Account opening is currently disabled.'];

            return to_route('staff.accounts.index')->with('toasts', $toast);
        }

        return $next($request);
    }
}
