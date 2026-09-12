<?php

namespace App\Http\Middleware;

use App\Constants\ManageStatus;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckIfAdminIsBanned
{
    /**
     * Handle an incoming request.
     *
     * @param Closure(Request): (Response) $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $admin = auth()->user();

        if ($admin && $admin->status == ManageStatus::INACTIVE) {
            auth()->logout();

            $toast[] = ['error', 'Your account has been banned.'];

            return redirect('admin')->with('toasts', $toast);
        }

        return $next($request);
    }
}
