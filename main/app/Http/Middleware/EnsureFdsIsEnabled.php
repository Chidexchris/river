<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureFdsIsEnabled
{
    /**
     * Handle an incoming request.
     *
     * @param Closure(Request): (Response) $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!bs('fds')) {
            $toast[] = ['error', 'The FDS feature is currently disabled.'];

            return redirect('/user/dashboard')->with('toasts', $toast);
        }

        return $next($request);
    }
}
