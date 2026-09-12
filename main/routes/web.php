<?php

use Illuminate\Support\Facades\Route;

// CronJobs
Route::prefix('cron')->name('cronjob.')->controller('CronjobController')->group(function () {
    Route::get('clean-temporary-uploads', 'cleanTemporaryUploads')->name('clean');
    Route::get('process-dps-installments', 'processDpsInstallments')->name('dps');
    Route::get('process-fds-installments', 'processFdsInstallments')->name('fds');
    Route::get('process-loan-installments', 'processLoanInstallments')->name('loan');
});

Route::controller('WebsiteController')->group(function () {
    Route::get('/', fn () => response()->file(public_path('riverwind/index.html')))->name('home');
    Route::get('about-us', 'aboutUs')->name('about.us');
    Route::get('our-branches', 'branches')->name('our.branches');
    Route::get('faq', 'faq')->name('faq');
    Route::get('contact', 'contact')->name('contact');
    Route::post('contact', 'contactStore');

    // Cookie
    Route::get('cookie/accept', 'cookieAccept')->name('cookie.accept');
    Route::get('cookie-policy', 'cookiePolicy')->name('cookie.policy');

    // Language
    Route::get('change-language/{lang?}', 'changeLanguage')->name('lang');

    // Policy Details
    Route::get('policy/{id}/{slug}', 'policyPages')->name('policy.pages');

    Route::get('placeholder-image/{size}', 'placeholderImage')->name('placeholder.image');

    // Subscriber
    Route::post('subscriber', 'storeSubscriber')->name('subscriber.store');
});
