<?php

namespace App\Http\Middleware;

use App\Constants\ManageStatus;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckIfStaffIsBanned
{
    /**
     * Handle an incoming request.
     *
     * @param Closure(Request): (Response) $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $staff = auth('staff')->user();

        if ($staff && $staff->status == ManageStatus::INACTIVE) {
            auth('staff')->logout();

            $toast[] = ['error', 'Your account has been banned.'];

            return redirect('staff')->with('toasts', $toast);
        }

        return $next($request);
    }
}
