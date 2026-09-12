<?php

Route::middleware('staff.guest')->namespace('Auth')->group(function () {
    Route::controller('LoginController')->group(function () {
        Route::get('', 'showLoginForm')->name('login.form');
        Route::post('login', 'login')->name('login');
        Route::post('logout', 'logout')->name('logout')
            ->withoutMiddleware('staff.guest')
            ->middleware('staff.auth');
    });

    Route::controller('ForgotPasswordController')->group(function () {
        Route::get('forgot-password', 'showForgotPasswordForm')->name('forgot.password.form');
        Route::post('forgot-password', 'sendResetCode');
        Route::get('verify-code', 'showVerificationCodeForm')->name('verify.code.form');
        Route::post('verify-code', 'verifyCode');
    });

    Route::controller('ResetPasswordController')->group(function () {
        Route::get('reset-password/{token}', 'showResetPasswordForm')->name('reset.password.form');
        Route::post('reset-password', 'resetPassword')->name('reset.password');
    });
});

Route::middleware(['staff.auth', 'staff.status'])->group(function () {
    Route::controller('StaffController')->group(function () {
        Route::get('dashboard', 'dashboard')->name('dashboard');
        Route::post('switch-branch', 'switchBranch')->name('switch.branch');
        Route::get('profile', 'profile')->name('profile');
        Route::post('profile', 'updateProfile')->name('profile.update');
        Route::post('password', 'updatePassword')->name('password.update');
        Route::get('branches', 'branches')->name('branches');
        Route::get('transactions', 'transactions')->name('transactions');
    });

    Route::prefix('accounts')->name('accounts.')->group(function () {
        Route::controller('AccountController')->group(function () {
            Route::get('', 'index')->name('index');
            Route::middleware('account.opening')->group(function () {
                Route::get('create', 'create')->name('create');
                Route::post('store', 'store')->name('store');
            });
            Route::get('{account}/edit', 'edit')->name('edit');
            Route::post('{account}', 'update')->name('update');
            Route::get('check-unique/{id?}', 'checkUnique')->name('check.unique');
            Route::get('{account}/statement', 'statement')->name('statement');
            Route::get('{account}/fetch-statement', 'fetchStatement')->name('fetch.statement');
            Route::post('{account}/export-statement', 'exportStatement')->name('export.statement');
        });

        Route::post('{account}/deposit-money', 'DepositController@store')->name('deposit.money');
        Route::post('{account}/withdraw-money', 'WithdrawController@store')->name('withdraw.money');
    });

    Route::get('deposits', 'DepositController@index')->name('deposits.index');
    Route::get('withdrawals', 'WithdrawController@index')->name('withdrawals.index');
});
