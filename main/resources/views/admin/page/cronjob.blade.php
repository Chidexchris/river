@extends('admin.layouts.master')

@section('master')
    <div class="col-12">
        <div class="row g-lg-4 g-3">
            <div class="col-xl-3 col-sm-6">
                <a href="{{ route('cronjob.clean') }}" class="dashboard-widget-1" target="_blank">
                    <div class="dashboard-widget-1__icon">
                        <i class="ti ti-server"></i>
                    </div>
                    <div class="dashboard-widget-1__content">
                        <h3 class="dashboard-widget-1__number">@lang('Storage Clean Command')</h3>
                        <p class="dashboard-widget-1__txt">@lang('Execute Immediately')</p>
                    </div>
                </a>
            </div>
            <div class="col-xl-3 col-sm-6">
                <a href="{{ route('cronjob.dps') }}" class="dashboard-widget-1 dashboard-widget-1__info" target="_blank">
                    <div class="dashboard-widget-1__icon">
                        <i class="ti ti-moneybag"></i>
                    </div>
                    <div class="dashboard-widget-1__content">
                        <h3 class="dashboard-widget-1__number">@lang('DPS Command')</h3>
                        <p class="dashboard-widget-1__txt">@lang('Execute Immediately')</p>
                    </div>
                </a>
            </div>
            <div class="col-xl-3 col-sm-6">
                <a href="{{ route('cronjob.fds') }}" class="dashboard-widget-1" target="_blank">
                    <div class="dashboard-widget-1__icon">
                        <i class="ti ti-cash-register"></i>
                    </div>
                    <div class="dashboard-widget-1__content">
                        <h3 class="dashboard-widget-1__number">@lang('FDS Command')</h3>
                        <p class="dashboard-widget-1__txt">@lang('Execute Immediately')</p>
                    </div>
                </a>
            </div>
            <div class="col-xl-3 col-sm-6">
                <a href="{{ route('cronjob.loan') }}" class="dashboard-widget-1 dashboard-widget-1__info" target="_blank">
                    <div class="dashboard-widget-1__icon">
                        <i class="ti ti-cash"></i>
                    </div>
                    <div class="dashboard-widget-1__content">
                        <h3 class="dashboard-widget-1__number">@lang('Loan Command')</h3>
                        <p class="dashboard-widget-1__txt">@lang('Execute Immediately')</p>
                    </div>
                </a>
            </div>
            <div class="col-xl-3 col-sm-6">
                <div class="dashboard-widget-1 dashboard-widget-1__warning">
                    <div class="dashboard-widget-1__icon">
                        <i class="ti ti-clock"></i>
                    </div>
                    <div class="dashboard-widget-1__content">
                        <h3 class="dashboard-widget-1__number">{{ diffForHumans($setting->clean_cron) }}</h3>
                        <p class="dashboard-widget-1__txt">@lang('Last Storage Clean Cron Run')</p>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-sm-6">
                <div class="dashboard-widget-1 dashboard-widget-1__danger">
                    <div class="dashboard-widget-1__icon">
                        <i class="ti ti-clock"></i>
                    </div>
                    <div class="dashboard-widget-1__content">
                        <h3 class="dashboard-widget-1__number">{{ diffForHumans($setting->dps_cron) }}</h3>
                        <p class="dashboard-widget-1__txt">@lang('Last DPS Cron Run')</p>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-sm-6">
                <div class="dashboard-widget-1 dashboard-widget-1__warning">
                    <div class="dashboard-widget-1__icon">
                        <i class="ti ti-clock"></i>
                    </div>
                    <div class="dashboard-widget-1__content">
                        <h3 class="dashboard-widget-1__number">{{ diffForHumans($setting->fds_cron) }}</h3>
                        <p class="dashboard-widget-1__txt">@lang('Last FDS Cron Run')</p>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-sm-6">
                <div class="dashboard-widget-1 dashboard-widget-1__danger">
                    <div class="dashboard-widget-1__icon">
                        <i class="ti ti-clock"></i>
                    </div>
                    <div class="dashboard-widget-1__content">
                        <h3 class="dashboard-widget-1__number">{{ diffForHumans($setting->loan_cron) }}</h3>
                        <p class="dashboard-widget-1__txt">@lang('Last Loan Cron Run')</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-12">
        <div class="custom--card">
            <div class="card-header">
                <h3 class="title">@lang('Cronjob Preferences')</h3>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-12">
                        <div class="alert alert--base" role="alert">
                            @lang('To automate system maintenance, schedule the following cron jobs. Set up a daily task to clean temporary uploads for storage optimization, to process Deposit Pension Scheme (DPS) installments, for Fixed Deposit Schedule (FDS) interest calculations, and loan management. You should schedule cron jobs with a frequency set to the shortest interval possible.')
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="row align-items-center gy-2">
                            <div class="col-xxl-3 col-sm-4">
                                <label class="col-form--label required">@lang('Storage Clean Command')</label>
                            </div>
                            <div class="col-xxl-9 col-sm-8">
                                <div class="input--group">
                                    <input type="text" class="form--control copyText" value="curl -s {{ route('cronjob.clean') }}" readonly>
                                    <span class="input-group-text bg--base text-white copyBtn" title="@lang('Copy Storage Clean Command')" role="button">
                                        <i class="ti ti-copy"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="row align-items-center gy-2">
                            <div class="col-xxl-3 col-sm-4"><label class="col-form--label required">@lang('DPS Cron Command')</label></div>
                            <div class="col-xxl-9 col-sm-8">
                                <div class="input--group">
                                    <input type="text" class="form--control copyText" value="curl -s {{ route('cronjob.dps') }}" readonly>
                                    <span class="input-group-text bg--base text-white copyBtn" title="@lang('Copy DPS Command')" role="button">
                                        <i class="ti ti-copy"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="row align-items-center gy-2">
                            <div class="col-xxl-3 col-sm-4"><label class="col-form--label required">@lang('FDS Cron Command')</label></div>
                            <div class="col-xxl-9 col-sm-8">
                                <div class="input--group">
                                    <input type="text" class="form--control copyText" value="curl -s {{ route('cronjob.fds') }}" readonly>
                                    <span class="input-group-text bg--base text-white copyBtn" title="@lang('Copy FDS Command')" role="button">
                                        <i class="ti ti-copy"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="row align-items-center gy-2">
                            <div class="col-xxl-3 col-sm-4"><label class="col-form--label required">@lang('Loan Cron Command')</label></div>
                            <div class="col-xxl-9 col-sm-8">
                                <div class="input--group">
                                    <input type="text" class="form--control copyText" value="curl -s {{ route('cronjob.loan') }}" readonly>
                                    <span class="input-group-text bg--base text-white copyBtn" title="@lang('Copy Loan Command')" role="button">
                                        <i class="ti ti-copy"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('page-script')
    <script>
        (function ($) {
            "use strict";

            $('.copyBtn').on('click', function() {
                var copyText = $(this).siblings('.copyText')[0];
                copyText.select();
                copyText.setSelectionRange(0, 99999);
                document.execCommand("copy");
                copyText.blur();
                $(this).addClass('copied');
                setTimeout(() => {
                    $(this).removeClass('copied');
                }, 1500);

                showToasts('success', 'Copy successful');
            });
        })(jQuery);
    </script>
@endpush
