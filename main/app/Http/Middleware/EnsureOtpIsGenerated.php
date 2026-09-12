<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Psr\SimpleCache\InvalidArgumentException;
use Symfony\Component\HttpFoundation\Response;

class EnsureOtpIsGenerated
{
    /**
     * Handle an incoming request.
     *
     * @param Closure(Request): (Response) $next
     * @throws InvalidArgumentException
     */
    public function handle(Request $request, Closure $next): Response
    {
        $storage    = Cache::store('file');
        $user       = auth('web')->user();
        $key        = 'otp_' . md5($user->email);
        $storedData = $storage->get($key);

        if (!$storedData || !array_key_exists('otp', $storedData)) {
            return redirect('/user/dashboard')->with('toasts', [
                ['error', 'An OTP is required to proceed. Please generate an OTP and try again.']
            ]);
        }

        return $next($request);
    }
}
