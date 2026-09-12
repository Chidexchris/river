<?php

use App\Http\Controllers\WebsiteController;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Support\Facades\Route;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        using: function () {
            Route::namespace('App\Http\Controllers')->group(function () {
                Route::middleware(['web', 'maintenance'])
                    ->namespace('Gateway')
                    ->prefix('ipn')
                    ->name('ipn.')
                    ->group(base_path('routes/ipn.php'));

                Route::middleware('web')
                    ->namespace('Admin')
                    ->prefix('admin')
                    ->name('admin.')
                    ->group(base_path('routes/admin.php'));

                Route::middleware(['web', 'maintenance'])
                    ->prefix('staff')
                    ->name('staff.')
                    ->namespace('Staff')
                    ->group(base_path('routes/staff.php'));

                Route::middleware(['web', 'maintenance'])
                    ->prefix('user')
                    ->group(base_path('routes/user.php'));

                Route::middleware(['web', 'maintenance'])
                    ->group(base_path('routes/web.php'));
            });

            Route::get('maintenance-mode', [WebsiteController::class, 'maintenance'])->name('maintenance');
        },
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->group('web', [
            \App\Http\Middleware\EncryptCookies::class,
            \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
            \Illuminate\Session\Middleware\StartSession::class,
            \Illuminate\View\Middleware\ShareErrorsFromSession::class,
            \App\Http\Middleware\VerifyCsrfToken::class,
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
            \App\Http\Middleware\LanguageMiddleware::class,
        ]);

        $middleware->alias([
            'auth'             => \App\Http\Middleware\Authenticate::class,
            'auth.basic'       => \Illuminate\Auth\Middleware\AuthenticateWithBasicAuth::class,
            'auth.session'     => \Illuminate\Session\Middleware\AuthenticateSession::class,
            'cache.headers'    => \Illuminate\Http\Middleware\SetCacheHeaders::class,
            'can'              => \Illuminate\Auth\Middleware\Authorize::class,
            'guest'            => \App\Http\Middleware\RedirectIfAuthenticated::class,
            'password.confirm' => \Illuminate\Auth\Middleware\RequirePassword::class,
            'precognitive'     => \Illuminate\Foundation\Http\Middleware\HandlePrecognitiveRequests::class,
            'signed'           => \App\Http\Middleware\ValidateSignature::class,
            'throttle'         => \Illuminate\Routing\Middleware\ThrottleRequests::class,
            'verified'         => \Illuminate\Auth\Middleware\EnsureEmailIsVerified::class,
            'admin'            => \App\Http\Middleware\RedirectIfNotAdmin::class,
            'admin.guest'      => \App\Http\Middleware\RedirectIfAdmin::class,
            'demo'             => \App\Http\Middleware\Demo::class,
            'kyc.status'       => \App\Http\Middleware\KycCheck::class,
            'maintenance'      => \App\Http\Middleware\MaintenanceMode::class,
            'register.status'  => \App\Http\Middleware\AllowRegistration::class,
            'authorize.status' => \App\Http\Middleware\AuthorizationStatus::class,
            'staff.guest'      => \App\Http\Middleware\RedirectIfStaffIsAuthenticated::class,
            'staff.auth'       => \App\Http\Middleware\EnsureStaffIsAuthenticated::class,
            'staff.status'     => \App\Http\Middleware\CheckIfStaffIsBanned::class,
            'account.opening'  => \App\Http\Middleware\EnsureAccountOpening::class,
            'admin.status'     => \App\Http\Middleware\CheckIfAdminIsBanned::class,
            'dps.check'        => \App\Http\Middleware\EnsureDpsIsEnabled::class,
            'fds.check'        => \App\Http\Middleware\EnsureFdsIsEnabled::class,
            'loan.check'       => \App\Http\Middleware\EnsureLoanIsEnabled::class,
            'deposit.check'    => \App\Http\Middleware\EnsureDepositIsEnabled::class,
            'withdraw.check'   => \App\Http\Middleware\EnsureWithdrawIsEnabled::class,
            'referral.check'   => \App\Http\Middleware\EnsureReferralIsEnabled::class,
            'money.transfer'   => \App\Http\Middleware\EnsureMoneyTransferIsEnabled::class,
            'ensure.otp'       => \App\Http\Middleware\EnsureOtpIsGenerated::class,
            'auto.logout'      => \App\Http\Middleware\AutoLogout::class,
            'statement.fee'    => \App\Http\Middleware\DeductStatementFee::class,
        ]);

        $middleware->validateCsrfTokens(
            except: ['user/deposit', 'ipn*']
        );
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
