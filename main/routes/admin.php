<?php

use Illuminate\Support\Facades\Route;

Route::middleware('admin.guest')->namespace('Auth')->group(function () {
    // Admin Login and Logout Process
    Route::controller('LoginController')->group(function () {
        Route::get('/', 'loginForm')->name('login.form');
        Route::post('/', 'login')->name('login');
        Route::get('logout', 'logout')->withoutMiddleware('admin.guest')->middleware('admin')->name('logout');
    });

    // Admin Forgot Password and Verification Process
    Route::controller('ForgotPasswordController')->prefix('password')->name('password.')->group(function () {
        Route::get('forgot', 'requestForm')->name('request.form');
        Route::post('forgot', 'sendResetCode');
        Route::get('verification/form', 'verificationForm')->name('code.verification.form');
        Route::post('verification/form', 'verificationCode');
    });

    // Admin Reset Password
    Route::controller('ResetPasswordController')->prefix('password')->name('password.')->group(function () {
        Route::get('reset/form/{email}/{code}', 'resetForm')->name('reset.form');
        Route::post('reset', 'resetPassword')->name('reset');
    });
});

// Operations for Admin
Route::middleware(['admin', 'admin.status'])->group(function () {
    Route::prefix('chat')->name('chat.')->controller('ChatController')->group(function () {
        Route::get('', 'index')->name('index');
        Route::get('messages', 'messages')->name('messages');
        Route::post('messages', 'store')->name('store');
    });

    Route::controller('AdminController')->group(function () {
        Route::get('dashboard', 'dashboard')->name('dashboard');
        Route::get('profile', 'profile')->name('profile');
        Route::post('profile', 'profileUpdate')->name('profile.update');
        Route::post('password', 'passwordChange')->name('password.update');

        // Notification
        Route::prefix('notification')->name('system.notification.')->group(function () {
            Route::get('all', 'allNotification')->name('all');
            Route::get('{id}/read', 'notificationRead')->name('read');
            Route::post('read-all', 'notificationReadAll')->name('read.all');
            Route::post('{id}/remove', 'notificationRemove')->name('remove');
            Route::post('remove-all', 'notificationRemoveAll')->name('remove.all');
        });

        // Transactions
        Route::get('transaction', 'transaction')->name('transaction.index');
        Route::get('transaction/{transaction}/edit', 'transactionEdit')->name('transaction.edit');
        Route::post('transaction/{transaction}/edit', 'transactionUpdate')->name('transaction.update');

        // File Download
        Route::get('file-download', 'fileDownload')->name('file.download');
    });

    // Payment Gateway
    Route::name('gateway.')->prefix('gateway')->group(function () {
        // Automated Gateway
        Route::controller('AutomatedGatewayController')->prefix('automated')->name('automated.')->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('edit/{alias}', 'edit')->name('edit');
            Route::post('update/{code}', 'update')->name('update');
            Route::post('remove/{id}', 'remove')->name('remove');
            Route::post('status/{id}', 'status')->name('status');
        });

        // Manual Gateway
        Route::controller('ManualGatewayController')->prefix('manual')->name('manual.')->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('new', 'new')->name('new');
            Route::post('store/{id?}', 'store')->name('store');
            Route::get('edit/{id}', 'edit')->name('edit');
            Route::post('status/{id}', 'status')->name('status');
        });
    });

    // Role & Permission Management
    Route::prefix('roles')->name('roles.')->controller('RoleController')->group(function () {
        Route::get('', 'index')->name('index');
        Route::get('create', 'create')->name('create');
        Route::post('', 'store')->name('store');
        Route::get('{role}/edit', 'edit')->name('edit');
        Route::post('{role}/update', 'update')->name('update');
    });

    Route::prefix('permissions')->controller('PermissionController')->group(function () {
        Route::get('', 'index');
        Route::post('', 'update');
    });

    // Admin Staff Management
    Route::prefix('staffs')->name('staffs.')->controller('AdminStaffController')->group(function () {
        Route::get('', 'index')->name('index');
        Route::post('', 'store')->name('store');
        Route::post('{admin}/update', 'update')->name('update');
        Route::post('{id}/status', 'updateStatus')->name('status');
        Route::get('{admin}/login', 'staffLogin')->name('login');
    });

    // User Management
    Route::controller('UserController')->name('user.')->prefix('user')->group(function () {
        Route::get('index', 'index')->name('index');
        Route::get('active', 'active')->name('active');
        Route::get('banned', 'banned')->name('banned');
        Route::get('kyc-pending', 'kycPending')->name('kyc.pending');
        Route::get('kyc-unconfirmed', 'kycUnConfirmed')->name('kyc.unconfirmed');
        Route::get('email-unconfirmed', 'emailUnConfirmed')->name('email.unconfirmed');
        Route::get('mobile-unconfirmed', 'mobileUnConfirmed')->name('mobile.unconfirmed');

        // User KYC Operation
        Route::post('{id}/kyc-approve', 'kycApprove')->name('kyc.approve');
        Route::post('{id}/kyc-cancel', 'kycCancel')->name('kyc.cancel');

        // User Details Operation
        Route::get('{id}/details', 'details')->name('details');
        Route::post('{id}/update', 'update')->name('update');
        Route::get('{id}/login', 'login')->name('login');
        Route::post('{id}/balance-update', 'balanceUpdate')->name('add.sub.balance');
        Route::post('{id}/status', 'status')->name('status');
    });

    // Branch Management
    Route::prefix('branches')->name('branches.')->controller('BranchController')->group(function () {
        Route::get('', 'index')->name('index');
        Route::get('create', 'create')->name('create');
        Route::post('', 'store')->name('store');
        Route::get('{branch}/edit', 'edit')->name('edit');
        Route::post('{branch}', 'update')->name('update');
        Route::post('{id}/status', 'updateStatus')->name('status');
    });

    // Branch Staff Management
    Route::prefix('branch-staffs')->name('branch.staffs.')->controller('StaffController')->group(function () {
        Route::get('', 'index')->name('index');
        Route::get('create', 'create')->name('create');
        Route::post('', 'store')->name('store');
        Route::get('{staff}/edit', 'edit')->name('edit');
        Route::post('{staff}', 'update')->name('update');
        Route::post('{id}/status', 'updateStatus')->name('status');
        Route::get('{staff}/login', 'staffLogin')->name('login');
    });

    // DPS Management
    Route::prefix('dps')->name('dps.')->group(function () {
        Route::controller('DepositPensionSchemePlanController')->group(function () {
            Route::get('plans', 'index')->name('plans');
            Route::post('plans', 'store')->name('store');
            Route::post('plan/{depositPensionSchemePlan}', 'update')->name('update');
            Route::post('plan/{id}/status', 'updateStatus')->name('status');
        });
        Route::controller('DepositPensionSchemeController')->group(function () {
            Route::get('', 'index')->name('index');
            Route::get('running', 'running')->name('running');
            Route::get('late-installment', 'lateInstallment')->name('late.installment');
            Route::get('matured', 'matured')->name('matured');
            Route::get('closed', 'closed')->name('closed');
            Route::get('{dps:scheme_code}/installments', 'installments')->name('installments');
        });
    });

    // FDS Management
    Route::prefix('fds')->name('fds.')->group(function () {
        Route::controller('FixedDepositSchemePlanController')->group(function () {
            Route::get('plans', 'index')->name('plans');
            Route::post('plans', 'store')->name('store');
            Route::post('plan/{fixedDepositSchemePlan}', 'update')->name('update');
            Route::post('plan/{id}/status', 'updateStatus')->name('status');
        });
        Route::controller('FixedDepositSchemeController')->group(function () {
            Route::get('', 'index')->name('index');
            Route::get('running', 'running')->name('running');
            Route::get('closed', 'closed')->name('closed');
            Route::get('{fds:scheme_code}/installments', 'installments')->name('installments');
        });
    });

    // Loan Management
    Route::prefix('loan')->name('loan.')->group(function () {
        Route::prefix('plans')->name('plans')->controller('LoanPlanController')->group(function () {
            Route::get('', 'index');
            Route::get('create', 'create')->name('.create');
            Route::post('', 'store')->name('.store');
            Route::get('{plan}/edit', 'edit')->name('.edit');
            Route::post('{plan}', 'update')->name('.update');
            Route::post('{id}/status', 'updateStatus')->name('.status');
        });
        Route::controller('LoanController')->group(function () {
            Route::get('', 'index')->name('index');
            Route::get('pending', 'pending')->name('pending');
            Route::get('running', 'running')->name('running');
            Route::get('late-installment', 'lateInstallment')->name('late.installment');
            Route::get('paid', 'paid')->name('paid');
            Route::get('rejected', 'rejected')->name('rejected');
            Route::prefix('{loan:scheme_code}')->group(function () {
                Route::get('file', 'download')->name('file');
                Route::post('approve', 'approveLoan')->name('approve');
                Route::post('reject', 'rejectLoan')->name('reject');
                Route::get('installments', 'installments')->name('installments');
            });
        });
    });

    // Deposit Management
    Route::prefix('deposits')->name('deposits.')->controller('DepositController')->group(function () {
        Route::get('index', 'index')->name('index');
        Route::get('pending', 'pending')->name('pending');
        Route::get('done', 'done')->name('done');
        Route::get('cancelled', 'cancelled')->name('cancelled');
        Route::post('approve/{id}', 'approve')->name('approve');
        Route::post('reject/{id}', 'reject')->name('reject');
    });

    // Withdraw Method Management
    Route::prefix('withdraw-method')->name('withdraw.method.')->controller('WithdrawMethodController')->group(function () {
        Route::get('', 'index')->name('index');
        Route::get('new', 'new')->name('new');
        Route::post('store/{id?}', 'store')->name('store');
        Route::get('edit/{id}', 'edit')->name('edit');
        Route::post('status/{id}', 'status')->name('status');
    });

    // Withdrawal Management
    Route::prefix('withdrawals')->name('withdrawals.')->controller('WithdrawController')->group(function () {
        Route::get('index', 'index')->name('index');
        Route::get('pending', 'pending')->name('pending');
        Route::get('done', 'done')->name('done');
        Route::get('cancelled', 'cancelled')->name('cancelled');
        Route::post('approve', 'approve')->name('approve');
        Route::post('reject', 'reject')->name('reject');
    });

    // Other Bank
    Route::prefix('other-banks')->name('other.banks.')->controller('OtherBankController')->group(function () {
        Route::get('', 'index')->name('index');
        Route::get('create', 'create')->name('create');
        Route::post('', 'store')->name('store');
        Route::get('{otherBank}/edit', 'edit')->name('edit');
        Route::post('{otherBank}', 'update')->name('update');
        Route::post('{id}/status', 'updateStatus')->name('status');
    });

    // Wire Transfer Settings
    Route::prefix('wire-transfer-settings')->name('wire.transfer.settings')->controller('WireTransferSettingsController')
        ->group(function () {
            Route::get('', 'index');
            Route::post('', 'update')->name('.update');
        });

    // Money Transfer
    Route::prefix('money-transfers')->name('money.transfers.')->controller('MoneyTransferController')->group(function () {
        Route::get('', 'index')->name('index');
        Route::get('pending', 'pending')->name('pending');
        Route::get('completed', 'completed')->name('completed');
        Route::get('failed', 'failed')->name('failed');
        Route::get('internal', 'internal')->name('internal');
        Route::get('external', 'external')->name('external');
        Route::get('wire', 'wire')->name('wire');
        Route::prefix('{moneyTransfer}')->group(function () {
            Route::get('file', 'download')->name('file');
            Route::post('complete', 'complete')->name('complete');
            Route::post('fail', 'fail')->name('fail');
        });
    });

    // Subscriber
    Route::controller('ContactController')->group(function () {
        Route::prefix('subscriber')->name('subscriber.')->group(function () {
            Route::get('/', 'subscriberIndex')->name('index');
            Route::post('remove/{id}', 'subscriberRemove')->name('remove');
            Route::post('send-email', 'sendEmailSubscriber')->name('send.email');
        });

        Route::prefix('contact')->name('contact.')->group(function () {
            Route::get('/', 'contactIndex')->name('index');
            Route::post('remove/{id}', 'contactRemove')->name('remove');
            Route::post('status/{id}', 'contactStatus')->name('status');
        });
    });

    // Settings
    Route::controller('SettingController')->group(function () {
        Route::prefix('setting')->group(function () {
            // Basic Settings
            Route::prefix('basic-settings')->name('basic.setting')->group(function () {
                Route::get('', 'basic');
                Route::post('', 'updateBasicPreferences')->name('.update');
                Route::post('bank-transaction', 'updateBankTransactionPreferences')->name('.bank.transaction');
                Route::post('system', 'updateSystemPreferences')->name('.system');
                Route::post('logo-favicon', 'updateLogoFavicon')->name('.logo.favicon');
            });

            // Plugin Settings
            Route::name('plugin.')->group(function () {
                Route::get('plugin-settings', 'plugin')->name('setting');
                Route::post('plugin/{id}/update', 'updatePlugin')->name('update');
                Route::post('plugin/{id}/status', 'updatePluginStatus')->name('status');
            });

            // SEO Settings
            Route::get('seo-settings', 'seo')->name('seo.setting');

            // KYC Settings
            Route::get('kyc-settings', 'kyc')->name('kyc.setting');
            Route::post('kyc-settings', 'updateKYC')->name('kyc.setting.update');

            // Cronjob
            Route::get('cronjob', 'cronjobIndex')->name('cronjob.index');
        });

        // Cookie
        Route::get('cookie-policy', 'cookie')->name('cookie.setting');
        Route::post('cookie-policy', 'updateCookie')->name('cookie.setting.update');

        // Maintenance
        Route::get('maintenance-mode', 'maintenance')->name('maintenance.setting');
        Route::post('maintenance-mode', 'updateMaintenance')->name('maintenance.setting.update');

        // Cache Clear
        Route::get('cache-clear', 'clearCache')->name('cache.clear');
    });

    // Referral Settings
    Route::controller('ReferralSettingsController')->prefix('referral-settings')->name('referral.settings')->group(function () {
        Route::get('', 'index');
        Route::post('', 'store')->name('.store');
        Route::post('status', 'status')->name('.status');
    });

    // Email & SMS Setting
    Route::controller('NotificationController')->prefix('notification')->name('notification.')->group(function () {
        // Template Setting
        Route::get('universal', 'universal')->name('universal');
        Route::post('universal', 'universalUpdate')->name('universal.update');
        Route::get('templates', 'templates')->name('templates');
        Route::get('template/{id}/edit', 'templateEdit')->name('template.edit');
        Route::post('template/{id}/update', 'templateUpdate')->name('template.update');

        // Email Setting
        Route::get('email-settings', 'email')->name('email');
        Route::post('email-settings', 'emailUpdate')->name('email.update');
        Route::post('email-test', 'testEmail')->name('email.test');

        // SMS Setting
        Route::get('sms-settings', 'sms')->name('sms');
        Route::post('sms-settings', 'smsUpdate')->name('sms.update');
        Route::post('sms-test', 'testSMS')->name('sms.test');
    });

    // Language Setting
    Route::controller('LanguageController')->prefix('language')->name('language.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('keywords', 'keywords')->name('keywords');
        Route::post('store/{id?}', 'store')->name('store');
        Route::post('{id}/status', 'status')->name('status');
        Route::post('{id}/delete', 'delete')->name('delete');
        Route::get('{id}/translate-keyword', 'translateKeyword')->name('translate.keyword');
        Route::post('import', 'languageImport')->name('import.lang');
        Route::post('{id}/store-key', 'languageKeyStore')->name('store.key');
        Route::post('{id}/update-key', 'languageKeyUpdate')->name('update.key');
        Route::post('{id}/delete-key', 'languageKeyDelete')->name('delete.key');
    });

    // Frontend
    Route::controller('SiteController')->prefix('site')->name('site.')->group(function () {
        Route::get('themes', 'themes')->name('themes');
        Route::post('themes', 'makeActive');
        Route::get('sections/{key}', 'sections')->name('sections');
        Route::post('content/{key}', 'content')->name('sections.content');
        Route::get('element/{key}/{id?}', 'element')->name('sections.element');
        Route::post('{id}/remove', 'remove')->name('remove');
    });
});
