<?php

use Illuminate\Support\Facades\Route;

Route::middleware('guest:web')->namespace('User\Auth')->name('user.')->group(function () {
    // User Login and Logout Process
    Route::controller('LoginController')->group(function () {
        Route::get('login', 'loginForm')->name('login.form');
        Route::post('login', 'login')->name('login');
        Route::get('logout', 'logout')->withoutMiddleware('guest:web')->middleware('auth')->name('logout');
    });

    // User Registration Process
    Route::middleware('register.status')->controller('RegisterController')->group(function () {
        Route::get('register', 'registerForm')->name('register.form');
        Route::post('register', 'register')->name('register');
        Route::post('check-user', 'checkUser')->withoutMiddleware('register.status')->name('check.user');
    });

    // Forgot Password
    Route::controller('ForgotPasswordController')->prefix('password')->name('password.')->group(function () {
        Route::get('forgot', 'requestForm')->name('request.form');
        Route::post('forgot', 'sendResetCode');
        Route::get('verification/form', 'verificationForm')->name('code.verification.form');
        Route::post('verification/form', 'verificationCode');
    });

    // Reset Password
    Route::controller('ResetPasswordController')->prefix('password/reset')->name('password.')->group(function () {
        Route::get('form/{token}', 'resetForm')->name('reset.form');
        Route::post('/', 'resetPassword')->name('reset');
    });
});

Route::middleware('auth')->name('user.')->group(function () {
    Route::namespace('User')->group(function () {
        Route::prefix('chat')->name('chat.')->controller('ChatController')->group(function () {
            Route::get('messages', 'messages')->name('messages');
            Route::post('messages', 'store')->name('store');
        });

        // Authorization
        Route::controller('AuthorizationController')->group(function () {
            Route::get('authorization', 'authorizeForm')->name('authorization');
            Route::get('resend-verify/{type}', 'sendVerifyCode')->name('send.verify.code');
            Route::post('verify-email', 'emailVerification')->name('verify.email');
            Route::post('verify-mobile', 'mobileVerification')->name('verify.mobile');
            Route::post('verify-g2fa', 'g2faVerification')->name('go2fa.verify');
        });

        Route::middleware(['authorize.status', 'auto.logout'])->group(function () {
            Route::controller('UserController')->group(function () {
                // Dashboard
                Route::get('dashboard', 'home')->name('home');

                // KYC Check
                Route::prefix('kyc')->name('kyc.')->group(function () {
                    Route::get('data', 'kycData')->name('data');
                    Route::get('form', 'kycForm')->name('form');
                    Route::post('form', 'kycSubmit');
                });

                // Profile Update
                Route::get('profile', 'profile')->name('profile');
                Route::post('profile', 'profileUpdate');

                // Password Change
                Route::get('change-password', 'password')->name('change.password');
                Route::post('change-password', 'passwordChange');

                // 2 Factor Authenticator
                Route::prefix('twofactor')->name('twofactor.')->group(function () {
                    Route::get('/', 'show2faForm')->name('form');
                    Route::post('enable', 'enable2fa')->name('enable');
                    Route::post('disable', 'disable2fa')->name('disable');
                });

                // Deposit
                Route::middleware('deposit.check')->name('deposit')->group(function () {
                    Route::get('deposit', 'deposit');
                    Route::get('deposit-history', 'depositHistory')->name('.history');
                    Route::post('/deposit-plan/store', 'store')->name('deposit-plan.store');
                    Route::get('/deposit-plan/create', 'createDepositPlan')->name('deposit-plan.create');
                    /**
                     * We are going to insert this deposit since the default is cash so that admin can approve it manually
                     * We are not going to use the Gateway\PaymentController for cash deposit
                     * because the Gateway\PaymentController is for online payment gateways only
                     */
                    Route::post('deposit/insert', 'insert')->name('.insert');
                });

                // Transactions
                Route::get('transactions', 'transactions')->name('transactions');

                // Account Statement
                Route::get('account-statement', 'accountStatement')->name('account.statement');
                Route::get('fetch-statement', 'fetchStatement')->name('fetch.statement');
                Route::post('export-statement', 'exportStatement')->name('export.statement')->middleware('statement.fee');

                // File Download
                Route::get('file-download', 'fileDownload')->name('file.download');

                // Referral
                Route::get('referred-users', 'showReferralTree')->name('referred.users')->middleware('referral.check');
            });

            // Withdraw
            Route::middleware(['withdraw.check', 'kyc.status'])->prefix('withdraw')->controller('WithdrawController')
                ->group(function () {
                    Route::get('', 'withdraw')->name('withdraw');
                    Route::post('', 'store');
                    Route::get('preview', 'preview')->name('withdraw.preview');
                    Route::post('preview', 'submit');
                    Route::get('history', 'withdrawHistory')->name('withdraw.history')
                        ->withoutMiddleware('kyc.status');
                });

            // DPS
            Route::middleware('dps.check')->prefix('dps')->name('dps.')->controller('DepositPensionSchemeController')
                ->group(function () {
                    Route::get('plans', 'plans')->name('plans');
                    Route::prefix('plan')->name('plan.')->group(function () {
                        Route::get('{plan}/preview', 'previewPlan')->name('preview');
                        Route::middleware('kyc.status')->group(function () {
                            Route::post('confirm', 'confirmPlan')->name('confirm');
                            Route::get('finalize', 'finalizePlan')->name('finalize');
                        });
                    });
                    Route::get('list', 'list')->name('list');
                    Route::prefix('{dps:scheme_code}')->group(function () {
                        Route::get('installments', 'installments')->name('installments');
                        Route::post('close', 'closeDps')->name('close')->middleware('kyc.status');
                    });
                });

            // FDS
            Route::middleware('fds.check')->prefix('fds')->name('fds.')->controller('FixedDepositSchemeController')
                ->group(function () {
                    Route::get('plans', 'plans')->name('plans');
                    Route::prefix('plan')->name('plan.')->group(function () {
                        Route::post('choose', 'choosePlan')->name('choose');
                        Route::get('{plan}/preview', 'previewPlan')->name('preview');
                        Route::middleware('kyc.status')->group(function () {
                            Route::post('confirm', 'confirmPlan')->name('confirm');
                            Route::get('finalize', 'finalizePlan')->name('finalize');
                        });
                    });
                    Route::get('list', 'list')->name('list');
                    Route::prefix('{fds:scheme_code}')->group(function () {
                        Route::get('installments', 'installments')->name('installments');
                        Route::post('close', 'closeFds')->name('close')->middleware('kyc.status');
                    });
                });

            // Loan
            Route::middleware('loan.check')->prefix('loan')->name('loan.')->controller('LoanController')
                ->group(function () {
                    Route::get('plans', 'plans')->name('plans');
                    Route::prefix('plan')->name('plan.')->group(function () {
                        Route::post('choose', 'choosePlan')->name('choose');
                        Route::get('{plan}/preview', 'previewPlan')->name('preview');
                        Route::middleware('kyc.status')->group(function () {
                            Route::post('confirm', 'confirmPlan')->name('confirm');
                            Route::get('finalize', 'finalizePlan')->name('finalize');
                        });
                    });
                    Route::get('list', 'list')->name('list');
                    Route::get('{loan:scheme_code}/installments', 'installments')->name('installments');
                });

            // Money Transfer
            Route::prefix('money-transfer')->name('money.transfer.')->controller('MoneyTransferController')
                ->group(function () {
                    Route::middleware('money.transfer:within_bank')->prefix('within-bank')->name('within.bank')
                        ->group(function () {
                            Route::get('', 'withinBank');
                            Route::post('check-account', 'checkAccount')->name('.check.account');
                            Route::middleware('kyc.status')->prefix('transfer')->name('.transfer')->group(function () {
                                Route::post('', 'transferWithinBank');
                                Route::get('finalize', 'finalizeTransferWithinBank')->name('.finalize');
                            });
                        });

                    Route::middleware('money.transfer:other_bank')->prefix('other-bank')->name('other.bank')
                        ->group(function () {
                            Route::get('', 'otherBank');
                            Route::get('{otherBank}/form', 'otherBankForm')->name('.form');
                            Route::middleware('kyc.status')->prefix('transfer')->name('.transfer')->group(function () {
                                Route::post('', 'transferToOtherBank');
                                Route::get('finalize', 'finalizeTransferToOtherBank')->name('.finalize');
                            });
                        });

                    Route::prefix('beneficiary')->name('beneficiary.')->group(function () {
                        Route::post('store', 'storeBeneficiary')->name('store');
                        Route::post('{beneficiary}/update', 'updateBeneficiary')->name('update');
                        Route::get('{beneficiary}/file', 'downloadBeneficiaryFile')->name('file');
                    });

                    Route::middleware(['money.transfer:wire_transfer', 'kyc.status'])->prefix('wire-transfer')->name('wire.transfer')
                        ->group(function () {
                            Route::get('', 'wireTransfer')->withoutMiddleware('kyc.status');
                            Route::post('submit', 'submitWireTransfer')->name('.submit');
                            Route::get('finalize', 'finalizeWireTransfer')->name('.finalize');
                        });

                    Route::get('history', 'history')->name('history');
                    Route::prefix('{moneyTransfer}')->group(function () {
                        Route::get('file', 'downloadMoneyTransferFile')->name('file');
                        Route::post('export', 'export')->name('export');
                    });
                });

            // Authorize via OTP
            Route::middleware('ensure.otp')->prefix('otp')->name('otp.')->controller('OTPController')
                ->group(function () {
                    Route::get('form', 'otpForm')->name('form');
                    Route::post('verify', 'verify')->name('verify');
                    Route::post('regenerate', 'regenerate')->name('regenerate')
                        ->withoutMiddleware('ensure.otp')
                        ->middleware('throttle:' . ceil(60 / bs('otp_expiry')) . ',1');
                });
        });
    });

    // Deposit
    Route::middleware(['deposit.check', 'authorize.status'])->prefix('deposit')->name('deposit.')
        ->controller('Gateway\PaymentController')
        ->group(function () {
            Route::post('insert', 'depositInsert')->name('insert');
            Route::get('confirm', 'depositConfirm')->name('confirm');
            Route::prefix('manual')->name('manual.')->group(function () {
                Route::get('', 'manualDepositConfirm')->name('confirm');
                Route::post('', 'manualDepositUpdate')->name('update');
            });
        });
});
