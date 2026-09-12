<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AuthorizationStatus
{
    /**
     * Handle an incoming request.
     *
     * @param  Request  $request
     * @param Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next): mixed
    {
        if (auth('web')->check()) {
            $user = auth('web')->user();

            if ($user->status && $user->ec && $user->sc && $user->tc) {
                return $next($request);
            } else {
                return to_route('user.authorization');
            }
        }

        abort(Response::HTTP_FORBIDDEN);
    }
}
