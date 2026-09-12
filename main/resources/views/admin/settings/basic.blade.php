@extends('admin.layouts.master')

@section('master')
    <div class="col-12">
        <div class="custom--card">
            <form action="" method="POST">
                @csrf
                <div class="card-header">
                    <h3 class="title">@lang('Site Preferences')</h3>
                </div>
                <div class="card-body">
                    <div class="row g-lg-4 g-3">
                        <div class="col-lg-4 col-sm-6">
                            <label class="form--label required">@lang('Site Name')</label>
                            <input type="text" class="form--control" name="site_name" value="{{ $setting->site_name }}" placeholder="@lang('RiverWind Bank')" required>
                        </div>
                        <div class="col-lg-4 col-sm-6">
                            <label class="form--label required">@lang('Platform Currency')</label>
                            <input type="text" class="form--control" name="site_cur" value="{{ $setting->site_cur }}" placeholder="@lang('USD')" required>
                        </div>
                        <div class="col-lg-4 col-sm-6">
                            <label class="form--label required">@lang('Currency Symbol')</label>
                            <input type="text" class="form--control" name="cur_sym" value="{{ $setting->cur_sym }}" placeholder="$" required>
                        </div>
                        <div class="col-lg-4 col-sm-6">
                            <label class="form--label required">@lang('Time Region')</label>
                            <select class="form--control form-select select-2" name="time_region" required>
                                @foreach($timeRegions as $timeRegion)
                                    <option value="'{{ $timeRegion }}'">{{ __($timeRegion) }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-lg-4 col-sm-6">
                            <label class="form--label required">@lang('Items Showing Per Page')</label>
                            <select class="form--control form-select" name="per_page_item" required>
                                <option value="20">20 @lang('items per page')</option>
                                <option value="50">50 @lang('items per page')</option>
                                <option value="100">100 @lang('items per page')</option>
                            </select>
                        </div>
                        <div class="col-lg-4 col-sm-6">
                            <label class="form--label required">@lang('Date Format')</label>
                            <select class="form--control form-select" name="date_format" required>
                                <option value="m-d-Y">MDY (Month-Day-Year)</option>
                                <option value="d-m-Y">DMY (Day-Month-Year)</option>
                                <option value="Y-m-d">YMD (Year-Month-Day)</option>
                            </select>
                        </div>
                        <div class="col-lg-4 col-sm-6">
                            <label class="form--label required">@lang('Fractional Digit Show')</label>
                            <input type="text" class="form--control" name="fraction_digit" value="{{ $setting->fraction_digit }}" placeholder="2" required>
                        </div>
                        <div class="col-lg-4 col-sm-6">
                            <label class="form--label required">@lang('Primary Color')</label>
                            <div class="input--group colorpicker">
                                <input type="color" class="form--control" value="#{{ $setting->primary_color }}">
                                <input type="text" class="form--control" name="primary_color" value="#{{ $setting->primary_color }}" placeholder="@lang('Hex Code e.g. #00ffff')" required>
                            </div>
                        </div>
                        <div class="col-lg-4 col-sm-6">
                            <label class="form--label required">@lang('Secondary Color')</label>
                            <div class="input--group colorpicker">
                                <input type="color" class="form--control" value="#{{ $setting->secondary_color }}">
                                <input type="text" class="form--control" name="secondary_color" value="#{{ $setting->secondary_color }}" placeholder="@lang('Hex Code e.g. #ffff00')" required>
                            </div>
                        </div>
                        <div class="col-lg-4 col-sm-6">
                            <label class="form--label required">@lang('Account Number Prefix')</label>
                            <input type="text" class="form--control" name="account_number_prefix" value="{{ $setting->account_number_prefix }}" placeholder="@lang('BANK')" required>
                        </div>
                        <div class="col-lg-4 col-sm-6">
                            <label class="form--label required">@lang('Account Number Length')</label>
                            <input type="number" min="0" class="form--control" name="account_number_length" value="{{ $setting->account_number_length }}" placeholder="12" required>
                        </div>
                        <div class="col-lg-4 col-sm-6">
                            <label class="form--label required">@lang('Referral Tree Level')</label>
                            <input type="number" min="0" class="form--control" name="referral_tree_level" value="{{ $setting->referral_tree_level }}" placeholder="5" required>
                        </div>
                        <div class="col-lg-4 col-sm-6">
                            <label class="form--label required">@lang('OTP Expiry')</label>
                            <div class="input--group">
                                <input type="number" min="0" class="form--control" name="otp_expiry" value="{{ $setting->otp_expiry }}" placeholder="150" required>
                                <span class="input-group-text">@lang('Seconds')</span>
                            </div>
                        </div>
                        <div class="col-lg-4 col-sm-6">
                            <label class="form--label required">@lang('Idle Timeout')</label>
                            <div class="input--group">
                                <input type="number" min="0" class="form--control" name="idle_timeout" value="{{ $setting->idle_timeout }}" placeholder="600" required>
                                <span class="input-group-text">@lang('Seconds')</span>
                            </div>
                        </div>
                        <div class="col-lg-4 col-sm-6">
                            <label class="form--label required">@lang('Statement Download Fee')</label>
                            <div class="input--group">
                                <input type="number" step="any" min="0" class="form--control" name="statement_download_fee" value="{{ getAmount($setting->statement_download_fee) }}" placeholder="5.00" required>
                                <span class="input-group-text">{{ __($setting->site_cur) }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                @can('update basic settings')
                    <div class="card-footer">
                        <div class="d-flex justify-content-center">
                            <button type="submit" class="btn btn--base px-4">@lang('Submit')</button>
                        </div>
                    </div>
                @endcan
            </form>
        </div>
    </div>
    <div class="col-12">
        <div class="custom--card">
            <form action="{{ route('admin.basic.setting.bank.transaction') }}" method="POST">
                @csrf
                <div class="card-header">
                    <h3 class="title">@lang('Own Bank Transaction Preferences')</h3>
                </div>
                <div class="card-body">
                    <div class="row g-lg-4 g-3">
                        <div class="col-lg-4 col-sm-6">
                            <label class="form--label required">@lang('Per Transaction Minimum Amount')</label>
                            <div class="input--group">
                                <input type="number" step="any" min="0" class="form--control" name="per_transaction_min_amount" value="{{ getAmount($setting->per_transaction_min_amount) }}" placeholder="10.00" required>
                                <span class="input-group-text">{{ $setting->site_cur }}</span>
                            </div>
                        </div>
                        <div class="col-lg-4 col-sm-6">
                            <label class="form--label required">@lang('Per Transaction Maximum Amount')</label>
                            <div class="input--group">
                                <input type="number" step="any" min="0" class="form--control" name="per_transaction_max_amount" value="{{ getAmount($setting->per_transaction_max_amount) }}" placeholder="10000.00" required>
                                <span class="input-group-text">{{ $setting->site_cur }}</span>
                            </div>
                        </div>
                        <div class="col-lg-4 col-sm-6">
                            <label class="form--label required">@lang('Daily Transaction Maximum Amount')</label>
                            <div class="input--group">
                                <input type="number" step="any" min="0" class="form--control" name="daily_transaction_max_amount" value="{{ getAmount($setting->daily_transaction_max_amount) }}" placeholder="100000.00" required>
                                <span class="input-group-text">{{ $setting->site_cur }}</span>
                            </div>
                        </div>
                        <div class="col-lg-4 col-sm-6">
                            <label class="form--label required">@lang('Monthly Transaction Maximum Amount')</label>
                            <div class="input--group">
                                <input type="number" step="any" min="0" class="form--control" name="monthly_transaction_max_amount" value="{{ getAmount($setting->monthly_transaction_max_amount) }}" placeholder="500000.00" required>
                                <span class="input-group-text">{{ $setting->site_cur }}</span>
                            </div>
                        </div>
                        <div class="col-lg-4 col-sm-6">
                            <label class="form--label required">@lang('Fixed Charge')</label>
                            <div class="input--group">
                                <input type="number" step="any" min="0" class="form--control" name="fixed_charge" value="{{ getAmount($setting->fixed_charge) }}" placeholder="5.00" required>
                                <span class="input-group-text">{{ $setting->site_cur }}</span>
                            </div>
                        </div>
                        <div class="col-lg-4 col-sm-6">
                            <label class="form--label required">@lang('Percentage Charge')</label>
                            <div class="input--group">
                                <input type="number" step="any" min="0" class="form--control" name="percentage_charge" value="{{ getAmount($setting->percentage_charge) }}" placeholder="2.5" required>
                                <span class="input-group-text">%</span>
                            </div>
                        </div>
                    </div>
                </div>

                @can('update bank transaction settings')
                    <div class="card-footer">
                        <div class="d-flex justify-content-center">
                            <button type="submit" class="btn btn--base px-4">@lang('Submit')</button>
                        </div>
                    </div>
                @endcan
            </form>
        </div>
    </div>
    <div class="col-12">
        <div class="custom--card">
            <form action="{{ route('admin.basic.setting.system') }}" method="POST">
                @csrf
                <div class="card-header">
                    <h3 class="title">@lang('System Preferences')</h3>
                </div>
                <div class="card-body">
                    <div class="row g-4">
                        <div class="col-12">
                            <div class="row g-lg-4 g-3 row-cols-xxl-5 row-cols-xl-4 row-cols-md-3 row-cols-sm-2 row-cols-1 preference-card-list justify-content-center">
                                <div class="col">
                                    <div class="preference-card bg-img" data-background-image="{{ asset('assets/admin/images/card-bg-1.png') }}">
                                        <div class="preference-card__thumb">
                                            <i class="ti ti-login-2"></i>
                                        </div>
                                        <div class="preference-card__content">
                                            <span class="preference-card__title">@lang('User Signup')</span>
                                            <span class="preference-card__desc">@lang('Toggle this switch to enable or disable user registration on your website, disabling the creation of new accounts when turned off.')</span>
                                        </div>
                                        <div class="form-check form--switch">
                                            <input type="checkbox" class="form-check-input" name="signup" @checked($setting->signup)>
                                        </div>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="preference-card bg-img" data-background-image="{{ asset('assets/admin/images/card-bg-1.png') }}">
                                        <div class="preference-card__thumb">
                                            <i class="ti ti-lock"></i>
                                        </div>
                                        <div class="preference-card__content">
                                            <span class="preference-card__title">@lang('Enforce Strong Password')</span>
                                            <span class="preference-card__desc">@lang('Activate this toggle to enforce strong passwords, enhancing account security and ensuring robust user authentication.')</span>
                                        </div>
                                        <div class="form-check form--switch">
                                            <input type="checkbox" class="form-check-input" name="strong_pass" @checked($setting->strong_pass)>
                                        </div>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="preference-card bg-img" data-background-image="{{ asset('assets/admin/images/card-bg-1.png') }}">
                                        <div class="preference-card__thumb">
                                            <i class="ti ti-clipboard-text"></i>
                                        </div>
                                        <div class="preference-card__content">
                                            <span class="preference-card__title">@lang('Accept Policy')</span>
                                            <span class="preference-card__desc">@lang('Activate this toggle to require users to agree to your terms before accessing the website, ensuring controlled user access.')</span>
                                        </div>
                                        <div class="form-check form--switch">
                                            <input type="checkbox" class="form-check-input" name="agree_policy" @checked($setting->agree_policy)>
                                        </div>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="preference-card bg-img" data-background-image="{{ asset('assets/admin/images/card-bg-1.png') }}">
                                        <div class="preference-card__thumb">
                                            <i class="ti ti-user-scan"></i>
                                        </div>
                                        <div class="preference-card__content">
                                            <span class="preference-card__title">@lang('Know Your Customer Check')</span>
                                            <span class="preference-card__desc">@lang('Enable this toggle to enforce user identity verification, enhancing trust and ensuring compliance with regulatory standards.')</span>
                                        </div>
                                        <div class="form-check form--switch">
                                            <input type="checkbox" class="form-check-input" name="kc" @checked($setting->kc)>
                                        </div>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="preference-card bg-img" data-background-image="{{ asset('assets/admin/images/card-bg-1.png') }}">
                                        <div class="preference-card__thumb">
                                            <i class="ti ti-mail-check"></i>
                                        </div>
                                        <div class="preference-card__content">
                                            <span class="preference-card__title">@lang('Email Confirmation')</span>
                                            <span class="preference-card__desc">@lang('Activate this toggle to require email verification during registration, ensuring user authenticity.')</span>
                                        </div>
                                        <div class="form-check form--switch">
                                            <input type="checkbox" class="form-check-input" name="ec" @checked($setting->ec)>
                                        </div>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="preference-card bg-img" data-background-image="{{ asset('assets/admin/images/card-bg-1.png') }}">
                                        <div class="preference-card__thumb">
                                            <i class="ti ti-mail-bolt"></i>
                                        </div>
                                        <div class="preference-card__content">
                                            <span class="preference-card__title">@lang('Email Alert')</span>
                                            <span class="preference-card__desc">@lang('Activate this toggle to send email notifications to users about important updates, events, and announcements on your website.')</span>
                                        </div>
                                        <div class="form-check form--switch">
                                            <input type="checkbox" class="form-check-input" name="ea" @checked($setting->ea)>
                                        </div>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="preference-card bg-img" data-background-image="{{ asset('assets/admin/images/card-bg-1.png') }}">
                                        <div class="preference-card__thumb">
                                            <i class="ti ti-message-check"></i>
                                        </div>
                                        <div class="preference-card__content">
                                            <span class="preference-card__title">@lang('Mobile Confirmation')</span>
                                            <span class="preference-card__desc">@lang('Activate this toggle to enhance user verification by requiring users to confirm their identity through their mobile devices during registration.')</span>
                                        </div>
                                        <div class="form-check form--switch">
                                            <input type="checkbox" class="form-check-input" name="sc" @checked($setting->sc)>
                                        </div>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="preference-card bg-img" data-background-image="{{ asset('assets/admin/images/card-bg-1.png') }}">
                                        <div class="preference-card__thumb">
                                            <i class="ti ti-message-bolt"></i>
                                        </div>
                                        <div class="preference-card__content">
                                            <span class="preference-card__title">@lang('SMS Alert')</span>
                                            <span class="preference-card__desc">@lang('Activate this toggle to send SMS notifications to users about important updates, events, and announcements on your website.')</span>
                                        </div>
                                        <div class="form-check form--switch">
                                            <input type="checkbox" class="form-check-input" name="sa" @checked($setting->sa)>
                                        </div>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="preference-card bg-img" data-background-image="{{ asset('assets/admin/images/card-bg-1.png') }}">
                                        <div class="preference-card__thumb">
                                            <i class="ti ti-certificate"></i>
                                        </div>
                                        <div class="preference-card__content">
                                            <span class="preference-card__title">@lang('Enforce SSL')</span>
                                            <span class="preference-card__desc">@lang('Activate this toggle to enforce data security by requiring all connections to your website to be encrypted.')</span>
                                        </div>
                                        <div class="form-check form--switch">
                                            <input type="checkbox" class="form-check-input" name="enforce_ssl" @checked($setting->enforce_ssl)>
                                        </div>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="preference-card bg-img" data-background-image="{{ asset('assets/admin/images/card-bg-1.png') }}">
                                        <div class="preference-card__thumb">
                                            <i class="ti ti-language"></i>
                                        </div>
                                        <div class="preference-card__content">
                                            <span class="preference-card__title">@lang('Language Preference')</span>
                                            <span class="preference-card__desc">@lang('Activate this toggle to enhance user experience by allowing visitors to select their preferred language for seamless interaction.')</span>
                                        </div>
                                        <div class="form-check form--switch">
                                            <input type="checkbox" class="form-check-input" name="language" @checked($setting->language)>
                                        </div>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="preference-card bg-img" data-background-image="{{ asset('assets/admin/images/card-bg-1.png') }}">
                                        <div class="preference-card__thumb">
                                            <i class="ti ti-user-square-rounded"></i>
                                        </div>
                                        <div class="preference-card__content">
                                            <span class="preference-card__title">@lang('Open Account')</span>
                                            <span class="preference-card__desc">@lang('Enable this option to allow account officers to create new bank accounts for customers, streamlining the onboarding process.')</span>
                                        </div>
                                        <div class="form-check form--switch">
                                            <input type="checkbox" class="form-check-input" name="open_account" @checked($setting->open_account)>
                                        </div>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="preference-card bg-img" data-background-image="{{ asset('assets/admin/images/card-bg-1.png') }}">
                                        <div class="preference-card__thumb">
                                            <i class="ti ti-wallet"></i>
                                        </div>
                                        <div class="preference-card__content">
                                            <span class="preference-card__title">@lang('Deposit')</span>
                                            <span class="preference-card__desc">@lang('Enable this toggle to allow customers to securely deposit funds into their accounts, enhancing financial accessibility and convenience.')</span>
                                        </div>
                                        <div class="form-check form--switch">
                                            <input type="checkbox" class="form-check-input" name="deposit" @checked($setting->deposit)>
                                        </div>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="preference-card bg-img" data-background-image="{{ asset('assets/admin/images/card-bg-1.png') }}">
                                        <div class="preference-card__thumb">
                                            <i class="ti ti-cash-banknote"></i>
                                        </div>
                                        <div class="preference-card__content">
                                            <span class="preference-card__title">@lang('Withdraw')</span>
                                            <span class="preference-card__desc">@lang('Enable this toggle to allow users to withdraw funds from their accounts securely, ensuring a seamless and trustworthy transaction experience.')</span>
                                        </div>
                                        <div class="form-check form--switch">
                                            <input type="checkbox" class="form-check-input" name="withdraw" @checked($setting->withdraw)>
                                        </div>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="preference-card bg-img" data-background-image="{{ asset('assets/admin/images/card-bg-1.png') }}">
                                        <div class="preference-card__thumb">
                                            <i class="ti ti-moneybag"></i>
                                        </div>
                                        <div class="preference-card__content">
                                            <span class="preference-card__title">@lang('DPS')</span>
                                            <span class="preference-card__desc">@lang('Enable this toggle to offer a Deposit Pension Scheme (DPS) option, encouraging users to save systematically while ensuring financial growth and stability.')</span>
                                        </div>
                                        <div class="form-check form--switch">
                                            <input type="checkbox" class="form-check-input" name="dps" @checked($setting->dps)>
                                        </div>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="preference-card bg-img" data-background-image="{{ asset('assets/admin/images/card-bg-1.png') }}">
                                        <div class="preference-card__thumb">
                                            <i class="ti ti-cash-register"></i>
                                        </div>
                                        <div class="preference-card__content">
                                            <span class="preference-card__title">@lang('FDS')</span>
                                            <span class="preference-card__desc">@lang('Enable this toggle to provide a Fixed Deposit Scheme (FDS) option, allowing users to invest their funds securely for higher returns over a fixed period.')</span>
                                        </div>
                                        <div class="form-check form--switch">
                                            <input type="checkbox" class="form-check-input" name="fds" @checked($setting->fds)>
                                        </div>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="preference-card bg-img" data-background-image="{{ asset('assets/admin/images/card-bg-1.png') }}">
                                        <div class="preference-card__thumb">
                                            <i class="ti ti-cash"></i>
                                        </div>
                                        <div class="preference-card__content">
                                            <span class="preference-card__title">@lang('Loan')</span>
                                            <span class="preference-card__desc">@lang('Enable this toggle to offer loan facilities, allowing users to apply for loans and manage repayments directly through the system.')</span>
                                        </div>
                                        <div class="form-check form--switch">
                                            <input type="checkbox" class="form-check-input" name="loan" @checked($setting->loan)>
                                        </div>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="preference-card bg-img" data-background-image="{{ asset('assets/admin/images/card-bg-1.png') }}">
                                        <div class="preference-card__thumb">
                                            <i class="ti ti-transfer"></i>
                                        </div>
                                        <div class="preference-card__content">
                                            <span class="preference-card__title">@lang('Internal Bank Transfer')</span>
                                            <span class="preference-card__desc">@lang('Enable this toggle to allow seamless transfers between accounts within the') <span class="text--base">{{ __($setting->site_name) }}</span>, @lang('simplifying internal transactions for users.')</span>
                                        </div>
                                        <div class="form-check form--switch">
                                            <input type="checkbox" class="form-check-input" name="internal_bank_transfer" @checked($setting->internal_bank_transfer)>
                                        </div>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="preference-card bg-img" data-background-image="{{ asset('assets/admin/images/card-bg-1.png') }}">
                                        <div class="preference-card__thumb">
                                            <i class="ti ti-building-bank"></i>
                                        </div>
                                        <div class="preference-card__content">
                                            <span class="preference-card__title">@lang('External Bank Transfer')</span>
                                            <span class="preference-card__desc">@lang('Enable this option to allow seamless transfers to other local bank accounts, making transactions between different financial institutions quick and easy.')</span>
                                        </div>
                                        <div class="form-check form--switch">
                                            <input type="checkbox" class="form-check-input" name="external_bank_transfer" @checked($setting->external_bank_transfer)>
                                        </div>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="preference-card bg-img" data-background-image="{{ asset('assets/admin/images/card-bg-1.png') }}">
                                        <div class="preference-card__thumb">
                                            <i class="ti ti-world-share"></i>
                                        </div>
                                        <div class="preference-card__content">
                                            <span class="preference-card__title">@lang('Wire Transfer')</span>
                                            <span class="preference-card__desc">@lang('Enable this option to facilitate secure and efficient wire transfers, allowing users to send high-value payments domestically or internationally with speed and reliability.')</span>
                                        </div>
                                        <div class="form-check form--switch">
                                            <input type="checkbox" class="form-check-input" name="wire_transfer" @checked($setting->wire_transfer)>
                                        </div>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="preference-card bg-img" data-background-image="{{ asset('assets/admin/images/card-bg-1.png') }}">
                                        <div class="preference-card__thumb">
                                            <i class="ti ti-password-mobile-phone"></i>
                                        </div>
                                        <div class="preference-card__content">
                                            <span class="preference-card__title">@lang('SMS-Based OTP')</span>
                                            <span class="preference-card__desc">@lang('Enable this option to deliver one-time passwords (OTPs) via SMS, providing a secure and convenient way for users to authenticate and complete their transactions.')</span>
                                        </div>
                                        <div class="form-check form--switch">
                                            <input type="checkbox" class="form-check-input" name="sms_based_otp" @checked($setting->sms_based_otp)>
                                        </div>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="preference-card bg-img" data-background-image="{{ asset('assets/admin/images/card-bg-1.png') }}">
                                        <div class="preference-card__thumb">
                                            <i class="ti ti-mail-cog"></i>
                                        </div>
                                        <div class="preference-card__content">
                                            <span class="preference-card__title">@lang('Email-Based OTP')</span>
                                            <span class="preference-card__desc">@lang('Enable this option to send one-time passwords (OTPs) via email, providing users with a secure and accessible authentication method for verifying their transactions.')</span>
                                        </div>
                                        <div class="form-check form--switch">
                                            <input type="checkbox" class="form-check-input" name="email_based_otp" @checked($setting->email_based_otp)>
                                        </div>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="preference-card bg-img" data-background-image="{{ asset('assets/admin/images/card-bg-1.png') }}">
                                        <div class="preference-card__thumb">
                                            <i class="ti ti-logout"></i>
                                        </div>
                                        <div class="preference-card__content">
                                            <span class="preference-card__title">@lang('Auto Logout')</span>
                                            <span class="preference-card__desc">@lang('Enable this option to automatically log users out after a period of inactivity, enhancing security and ensuring that sessions are not left open unattended.')</span>
                                        </div>
                                        <div class="form-check form--switch">
                                            <input type="checkbox" class="form-check-input" name="auto_logout" @checked($setting->auto_logout)>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                @can('update system settings')
                    <div class="card-footer">
                        <div class="d-flex justify-content-center">
                            <button type="submit" class="btn btn--base px-4">@lang('Submit')</button>
                        </div>
                    </div>
                @endcan
            </form>
        </div>
    </div>
    <div class="col-12">
        <div class="custom--card">
            <form action="{{ route('admin.basic.setting.logo.favicon') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="card-header">
                    <h3 class="title">@lang('Logo and Favicon Preferences')</h3>
                </div>
                <div class="card-body">
                    <div class="row g-lg-4 g-3">
                        <div class="col-12">
                            <div class="alert alert--base">
                                @lang('If the visual identifiers remain unchanged, it\'s advisable to perform a cache clearance within your browser. Typically, clearing the cache resolves this issue. However, if the previous logo or favicon persists, it could be attributed to caching mechanisms at the server or network level. Additional cache clearance may be necessary in such cases').
                            </div>
                        </div>
                        <div class="col-lg-4 col-sm-6">
                            <label for="logoLight" class="form--label">@lang('Logo Light')</label>
                            <div class="upload__img">
                                <label for="logoLight" class="upload__img__btn"><i class="ti ti-camera"></i></label>
                                <input type="file" id="logoLight" class="image-upload" name="logo_light" accept=".png">
                                <label for="logoLight" class="upload__img-preview image-preview">
                                    <img src="{{ getImage(getFilePath('logoFavicon') . '/logo_light.png') }}" alt="logo">
                                </label>
                                <button type="button" class="btn btn--sm btn--icon btn--danger custom-file-input-clear d-none">
                                    <i class="ti ti-circle-x"></i>
                                </button>
                            </div>
                        </div>
                        <div class="col-lg-4 col-sm-6">
                            <label for="logoDark" class="form--label">@lang('Logo Dark')</label>
                            <div class="upload__img">
                                <label for="logoDark" class="upload__img__btn"><i class="ti ti-camera"></i></label>
                                <input type="file" id="logoDark" class="image-upload" name="logo_dark" accept=".png">
                                <label for="logoDark" class="upload__img-preview image-preview">
                                    <img src="{{ getImage(getFilePath('logoFavicon') . '/logo_dark.png') }}" alt="logo">
                                </label>
                                <button type="button" class="btn btn--sm btn--icon btn--danger custom-file-input-clear d-none">
                                    <i class="ti ti-circle-x"></i>
                                </button>
                            </div>
                        </div>
                        <div class="col-lg-4 col-sm-6">
                            <label for="favicon" class="form--label">@lang('Favicon')</label>
                            <div class="upload__img">
                                <label for="favicon" class="upload__img__btn"><i class="ti ti-camera"></i></label>
                                <input type="file" id="favicon" class="image-upload" name="favicon" accept=".png">
                                <label for="favicon" class="upload__img-preview image-preview">
                                    <img src="{{ getImage(getFilePath('logoFavicon') . '/favicon.png', getFileSize('favicon')) }}" alt="logo">
                                </label>
                                <button type="button" class="btn btn--sm btn--icon btn--danger custom-file-input-clear d-none">
                                    <i class="ti ti-circle-x"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                @can('update logo and favicon')
                    <div class="card-footer">
                        <div class="d-flex justify-content-center">
                            <button type="submit" class="btn btn--base px-4">@lang('Submit')</button>
                        </div>
                    </div>
                @endcan
            </form>
        </div>
    </div>
@endsection

@push('page-script')
    <script>
        (function ($) {
            "use strict";

            let colorPicker = $('.colorpicker')

            colorPicker.find('input[type=color]').on('input', function () {
                let colorCode = $(this).val();
                $(this).siblings('input').val(colorCode);
            });

            colorPicker.find('input').on('keyup', function () {
                let colorCode = $(this).val();
                $(this).siblings('input').val(colorCode);
            });

            $('[name=per_page_item]').val('{{ bs('per_page_item') }}');
            $('[name=date_format]').val('{{ bs('date_format') }}');
            $('[name=time_region]').val("'{{ config('app.timezone') }}'").select2();
        })(jQuery);
    </script>
@endpush
