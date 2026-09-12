@extends('admin.layouts.master')

@section('master')
    <div class="col-12">
        <div class="row g-lg-4 g-3">
            <div class="col-xl-3 col-sm-6">
                <a href="{{ route('admin.user.index') }}" class="dashboard-widget-1">
                    <div class="dashboard-widget-1__icon">
                        <i class="ti ti-users"></i>
                    </div>
                    <div class="dashboard-widget-1__content">
                        <h3 class="dashboard-widget-1__number">{{ $widget['totalUsers'] }}</h3>
                        <p class="dashboard-widget-1__txt">@lang('Total Users')</p>
                    </div>
                </a>
            </div>
            <div class="col-xl-3 col-sm-6">
                <a href="{{ route('admin.user.active') }}" class="dashboard-widget-1 dashboard-widget-1__success">
                    <div class="dashboard-widget-1__icon">
                        <i class="ti ti-user-check"></i>
                    </div>
                    <div class="dashboard-widget-1__content">
                        <h3 class="dashboard-widget-1__number">{{ $widget['activeUsers'] }}</h3>
                        <p class="dashboard-widget-1__txt">@lang('Active Users')</p>
                    </div>
                </a>
            </div>
            <div class="col-xl-3 col-sm-6">
                <a href="{{ route('admin.user.email.unconfirmed') }}" class="dashboard-widget-1 dashboard-widget-1__warning">
                    <div class="dashboard-widget-1__icon">
                        <i class="ti ti-mail-off"></i>
                    </div>
                    <div class="dashboard-widget-1__content">
                        <h3 class="dashboard-widget-1__number">{{ $widget['emailUnconfirmedUsers'] }}</h3>
                        <p class="dashboard-widget-1__txt">@lang('Email Unconfirmed Users')</p>
                    </div>
                </a>
            </div>
            <div class="col-xl-3 col-sm-6">
                <a href="{{ route('admin.user.mobile.unconfirmed') }}" class="dashboard-widget-1 dashboard-widget-1__danger">
                    <div class="dashboard-widget-1__icon">
                        <i class="ti ti-message-off"></i>
                    </div>
                    <div class="dashboard-widget-1__content">
                        <h3 class="dashboard-widget-1__number">{{ $widget['mobileUnconfirmedUsers'] }}</h3>
                        <p class="dashboard-widget-1__txt">@lang('Mobile Unconfirmed Users')</p>
                    </div>
                </a>
            </div>
        </div>
    </div>

    <div class="col-12">
        <div class="dashboard-widget-row">
            <a href="{{ route('admin.transaction.index') }}" class="dashboard-widget-2 dashboard-widget-2__success">
                <div class="dashboard-widget-2__top">
                    <h3 class="dashboard-widget-2__number">{{ $setting->cur_sym . formatAmount($widget['allUsersBalance']) }}</h3>
                    <div class="dashboard-widget-2__icon">
                        <i class="ti ti-coins"></i>
                    </div>
                </div>
                <p class="dashboard-widget-2__txt">@lang('Overall User Funds')</p>
            </a>
            <a href="{{ route('admin.dps.running') }}" class="dashboard-widget-2 dashboard-widget-2">
                <div class="dashboard-widget-2__top">
                    <h3 class="dashboard-widget-2__number">{{ $setting->cur_sym . formatAmount($widget['runningDpsTotal']) }}</h3>
                    <div class="dashboard-widget-2__icon">
                        <i class="ti ti-moneybag"></i>
                    </div>
                </div>
                <p class="dashboard-widget-2__txt">@lang('Ongoing DPS Balance')</p>
            </a>
            <a href="{{ route('admin.fds.running') }}" class="dashboard-widget-2 dashboard-widget-2__info">
                <div class="dashboard-widget-2__top">
                    <h3 class="dashboard-widget-2__number">{{ $setting->cur_sym . formatAmount($widget['runningFdsTotal']) }}</h3>
                    <div class="dashboard-widget-2__icon">
                        <i class="ti ti-cash-register"></i>
                    </div>
                </div>
                <p class="dashboard-widget-2__txt">@lang('Ongoing FDS Balance')</p>
            </a>
            <a href="{{ route('admin.loan.running') }}" class="dashboard-widget-2 dashboard-widget-2__warning">
                <div class="dashboard-widget-2__top">
                    <h3 class="dashboard-widget-2__number">{{ $setting->cur_sym . formatAmount($widget['runningLoanTotal']) }}</h3>
                    <div class="dashboard-widget-2__icon">
                        <i class="ti ti-cash"></i>
                    </div>
                </div>
                <p class="dashboard-widget-2__txt">@lang('Ongoing Loan Balance')</p>
            </a>
        </div>
    </div>

    <div class="col-12">
        <div class="row g-4">
            <div class="col-xxl-3">
                <div class="row g-4">
                    <div class="col-12">
                        <div class="dashboard-widget-6">
                            <div class="dashboard-widget-6__content">
                                <h3 class="dashboard-widget-6__number">{{ array_sum($pendingWidget) }}</h3>
                                <p class="dashboard-widget-6__txt">@lang('Total Pending')</p>
                            </div>
                            <div class="dashboard-widget-6__icon bg--warning">
                                <i class="ti ti-rotate-clockwise-2 transform-2"></i>
                            </div>
                            <div class="dashboard-widget-6__list">
                                <ul>
                                    <li class="dashboard-widget-6__list__item list-base">
                                        <a href="{{ route('admin.user.kyc.pending') }}">
                                            <span>@lang('KYC Verification')</span>
                                        </a> <span>{{ $pendingWidget['kycCount'] }}</span>
                                    </li>
                                    <li class="dashboard-widget-6__list__item list-danger">
                                        <a href="{{ route('admin.loan.pending') }}">
                                            <span>@lang('Loan Application')</span>
                                        </a> <span>{{ $pendingWidget['loanCount'] }}</span>
                                    </li>
                                    <li class="dashboard-widget-6__list__item list-info">
                                        <a href="{{ route('admin.deposits.pending') }}">
                                            <span>@lang('Deposit Request')</span>
                                        </a> <span>{{ $pendingWidget['depositCount'] }}</span>
                                    </li>
                                    <li class="dashboard-widget-6__list__item list-warning">
                                        <a href="{{ route('admin.withdrawals.pending') }}">
                                            <span>@lang('Withdraw Request')</span>
                                        </a> <span>{{ $pendingWidget['withdrawCount'] }}</span>
                                    </li>
                                    <li class="dashboard-widget-6__list__item list-success">
                                        <a href="{{ route('admin.money.transfers.pending') }}">
                                            <span>@lang('Money Transfer')</span>
                                        </a> <span>{{ $pendingWidget['moneyTransferCount'] }}</span>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="dashboard-widget-6">
                            <div class="dashboard-widget-6__content">
                                <h3 class="dashboard-widget-6__number">{{ array_sum($dueWidget) }}</h3>
                                <p class="dashboard-widget-6__txt">@lang('Total Due')</p>
                            </div>
                            <div class="dashboard-widget-6__icon bg-danger">
                                <i class="ti ti-calendar-exclamation transform-2"></i>
                            </div>
                            <div class="dashboard-widget-6__list">
                                <ul>
                                    <li class="dashboard-widget-6__list__item list-base">
                                        <a href="{{ route('admin.dps.late.installment') }}">
                                            <span>@lang('Deposit Pension Scheme')</span>
                                        </a> <span>{{ $dueWidget['dpsCount'] }}</span>
                                    </li>
                                    <li class="dashboard-widget-6__list__item list-info">
                                        <a href="{{ route('admin.loan.late.installment') }}">
                                            <span>@lang('Loan')</span>
                                        </a> <span>{{ $dueWidget['loanCount'] }}</span>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xxl-3 col-md-6">
                <div class="dashboard-widget-6 dashboard-widget-6-lite">
                    <div class="dashboard-widget-6__list-2">
                        <ul>
                            <li class="dashboard-widget-6__list-2__item">
                                <a href="{{ route('admin.dps.running') }}">
                                    <span class="left"><i class="ti ti-moneybag"></i> @lang('Running DPS')</span>
                                </a>
                                <span class="right">{{ $widget['runningDpsCount'] }}</span>
                            </li>
                            <li class="dashboard-widget-6__list-2__item list-success">
                                <a href="{{ route('admin.dps.matured') }}">
                                    <span class="left"><i class="ti ti-circle-check"></i> @lang('Matured DPS')</span>
                                </a>
                                <span class="right">{{ $widget['maturedDpsCount'] }}</span>
                            </li>
                            <li class="dashboard-widget-6__list-2__item list-info">
                                <a href="{{ route('admin.fds.running') }}">
                                    <span class="left"><i class="ti ti-cash-register"></i> @lang('Running FDS')</span>
                                </a>
                                <span class="right">{{ $widget['runningFdsCount'] }}</span>
                            </li>
                            <li class="dashboard-widget-6__list-2__item list-warning">
                                <a href="{{ route('admin.loan.running') }}">
                                    <span class="left"><i class="ti ti-cash"></i> @lang('Running Loan')</span>
                                </a>
                                <span class="right">{{ $widget['runningLoanCount'] }}</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-xxl-3 col-md-6">
                <div class="dashboard-widget-6 dashboard-widget-6-lite">
                    <div class="dashboard-widget-6__list-2">
                        <ul>
                            <li class="dashboard-widget-6__list-2__item list-success">
                                <a href="{{ route('admin.deposits.done') }}">
                                    <span class="left"><i class="ti ti-wallet"></i> @lang('Total Deposit')</span>
                                </a>
                                <span class="right">{{ $setting->cur_sym . formatAmount($widget['depositDone']) }}</span>
                            </li>
                            <li class="dashboard-widget-6__list-2__item list-warning">
                                <a href="{{ route('admin.deposits.pending') }}">
                                    <span class="left"><i class="ti ti-rotate-clockwise-2"></i> @lang('Pending Deposits')</span>
                                </a>
                                <span class="right">{{ $widget['depositPending'] }}</span>
                            </li>
                            <li class="dashboard-widget-6__list-2__item list-danger">
                                <a href="{{ route('admin.deposits.cancelled') }}">
                                    <span class="left"><i class="ti ti-x"></i> @lang('Cancelled Deposits')</span>
                                </a>
                                <span class="right">{{ $widget['depositCancelled'] }}</span>
                            </li>
                            <li class="dashboard-widget-6__list-2__item list-info">
                                <a href="{{ route('admin.deposits.index') }}">
                                    <span class="left"><i class="ti ti-coins"></i> @lang('Deposit Charge')</span>
                                </a>
                                <span class="right">{{ $setting->cur_sym . formatAmount($widget['depositCharge']) }}</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-xxl-3 col-md-6">
                <div class="dashboard-widget-6 dashboard-widget-6-lite">
                    <div class="dashboard-widget-6__list-2">
                        <ul>
                            <li class="dashboard-widget-6__list-2__item list-success">
                                <a href="{{ route('admin.withdrawals.done') }}">
                                    <span class="left"><i class="ti ti-cash-banknote"></i> @lang('Total Withdraw')</span>
                                </a>
                                <span class="right">{{ $setting->cur_sym . formatAmount($widget['withdrawDone']) }}</span>
                            </li>
                            <li class="dashboard-widget-6__list-2__item list-warning">
                                <a href="{{ route('admin.withdrawals.pending') }}">
                                    <span class="left"><i class="ti ti-rotate-clockwise-2"></i> @lang('Pending Withdrawals')</span>
                                </a>
                                <span class="right">{{ $widget['withdrawPending'] }}</span>
                            </li>
                            <li class="dashboard-widget-6__list-2__item list-danger">
                                <a href="{{ route('admin.withdrawals.cancelled') }}">
                                    <span class="left"><i class="ti ti-x"></i> @lang('Cancelled Withdrawals')</span>
                                </a>
                                <span class="right">{{ $widget['withdrawCancelled'] }}</span>
                            </li>
                            <li class="dashboard-widget-6__list-2__item list-info">
                                <a href="{{ route('admin.withdrawals.index') }}">
                                    <span class="left"><i class="ti ti-coins"></i> @lang('Withdrawn Charge')</span>
                                </a>
                                <span class="right">{{ $setting->cur_sym . formatAmount($widget['withdrawCharge']) }}</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-6">
        <div class="mb-3">
            <h6 class="mb-0">@lang('Deposit') & @lang('Withdraw')</h6>
        </div>
        <div class="custom--card h-auto">
            <div class="card-header">
                <small>{{ '(' . trans('Progress report for this year') . ')' }}</small>
            </div>
            <div class="card-body px-0 pb-0">
                <div id="chart"></div>
            </div>
        </div>
    </div>

    <div class="col-xl-6">
        <div class="mb-3">
            <h6 class="mb-0">@lang('Latest Transactions')</h6>
        </div>
        <div class="custom--card border-0 h-auto table-responsive">
            <table class="table table-borderless table--striped table--responsive--md">
                <thead>
                    <tr>
                        <th>@lang('User')</th>
                        <th>@lang('TRX')</th>
                        <th>@lang('Initiated')</th>
                        <th>@lang('Amount')</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($latestTrx as $trx)
                        <tr>
                            <td>
                                <a href="{{ route('admin.user.details', $trx->user->id) }}">
                                    {{ $trx->user->fullname }}
                                </a>
                            </td>
                            <td>
                                <span class="fw-semibold">{{ $trx->trx }}</span>
                            </td>
                            <td>
                                <p>{{ showDateTime($trx->created_at) }}</p>
                            </td>
                            <td>
                                <span class="@if($trx->trx_type == '+') text--success @else text--danger @endif">
                                    {{ $trx->trx_type . ' ' . showAmount($trx->amount) . ' ' . __($setting->site_cur) }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        @include('partials.noData')
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="col-12">
        <div class="change-password-modal">
            <div class="change-password-modal__body">
                <button class="btn btn--sm btn--icon btn-outline--secondary change-password-modal__close modal-close">
                    <i class="ti ti-x"></i>
                </button>
                <div class="change-password-modal__img">
                    <img src="{{ asset('assets/admin/images/light.png') }}" alt="Image">
                </div>
                <h3 class="change-password-modal__title">@lang('Security Advisory')</h3>
                <p class="change-password-modal__desc">@lang('An immediate change of the default username and password is required.')</p>
                <div class="change-password-modal__btn">
                    <a href="{{ route('admin.profile') }}" class="btn btn--sm btn--base">
                        @lang('Change')
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('page-script-lib')
    <script src="{{ asset('assets/admin/js/page/apexcharts.js') }}"></script>
@endpush

@push('page-script')
    <script>
        "use strict";

        @if ($passwordAlert)
            (function ($) {
                $('.change-password-modal').addClass('active');
            })(jQuery);
        @endif

        let options = {
            series: [
                {
                    name: 'Deposit',
                    color: '#02c05b',
                    data: JSON.parse('<?php echo json_encode($deposits); ?>')
                },
                {
                    name: 'Withdraw',
                    color: '#ff9e42',
                    data: JSON.parse('<?php echo json_encode($withdrawals); ?>')
                }
            ],
            chart: {
                type: 'bar',
                height: 411,
                toolbar: {
                    show: false,
                }
            },
            plotOptions: {
                bar: {
                    horizontal: false,
                    columnWidth: '50%',
                    endingShape: 'rounded',
                },
            },
            dataLabels: {
                enabled: false,
            },
            stroke: {
                show: true,
                width: 2,
                colors: ['transparent'],
            },
            xaxis: {
                categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
            },
            yaxis: {
                title: {
                    text: "{{ __($setting->site_cur) }}",
                    style: {
                        color: '#7c97bb',
                    }
                }
            },
            grid: {
                xaxis: {
                    lines: {
                        show: false,
                    }
                },
                yaxis: {
                    lines: {
                        show: false,
                    }
                },
            },
            fill: {
                opacity: 1
            },
            tooltip: {
                y: {
                    formatter: function (val) {
                        return "{{ $setting->cur_sym }}" + val + " "
                    }
                }
            }
        };

        let chart = new ApexCharts(document.querySelector("#chart"), options);
        chart.render();
    </script>
@endpush
