@extends('admin.layouts.master')

@section('master')
    <div class="col-12">
        <div class="row g-lg-4 g-3">
            <div class="col-xl-3 col-sm-6">
                <a href="{{ route('admin.transaction.index', ['search' => $user->username]) }}" class="dashboard-widget-4">
                    <div class="dashboard-widget-4__content">
                        <div class="dashboard-widget-4__icon">
                            <i class="ti ti-coins"></i>
                        </div>
                        <p class="dashboard-widget-4__txt">@lang('Balance')</p>
                    </div>
                    <h3 class="dashboard-widget-4__number">{{ showAmount($user->balance) . ' ' . __($setting->site_cur) }}</h3>
                    <div class="dashboard-widget-4__vector">
                        <i class="ti ti-coins"></i>
                    </div>
                </a>
            </div>
            <div class="col-xl-3 col-sm-6">
                <a href="{{ route('admin.deposits.index', ['search' => $user->username]) }}" class="dashboard-widget-4 dashboard-widget-4__success">
                    <div class="dashboard-widget-4__content">
                        <div class="dashboard-widget-4__icon">
                            <i class="ti ti-wallet"></i>
                        </div>
                        <p class="dashboard-widget-4__txt">@lang('Total Deposit')</p>
                    </div>
                    <h3 class="dashboard-widget-4__number">{{ showAmount($totalDeposit) . ' ' . __($setting->site_cur) }}</h3>
                    <div class="dashboard-widget-4__vector">
                        <i class="ti ti-wallet"></i>
                    </div>
                </a>
            </div>
            <div class="col-xl-3 col-sm-6">
                <a href="{{ route('admin.withdrawals.index', ['search' => $user->username]) }}" class="dashboard-widget-4 dashboard-widget-4__warning">
                    <div class="dashboard-widget-4__content">
                        <div class="dashboard-widget-4__icon">
                            <i class="ti ti-cash-banknote"></i>
                        </div>
                        <p class="dashboard-widget-4__txt">@lang('Total Withdrawal')</p>
                    </div>
                    <h3 class="dashboard-widget-4__number">{{ showAmount($totalWithdrawal) . ' ' . __($setting->site_cur) }}</h3>
                    <div class="dashboard-widget-4__vector">
                        <i class="ti ti-cash-banknote"></i>
                    </div>
                </a>
            </div>
            <div class="col-xl-3 col-sm-6">
                <a href="{{ route('admin.transaction.index', ['search' => $user->username]) }}" class="dashboard-widget-4 dashboard-widget-4__info">
                    <div class="dashboard-widget-4__content">
                        <div class="dashboard-widget-4__icon">
                            <i class="ti ti-transform"></i>
                        </div>
                        <p class="dashboard-widget-4__txt">@lang('Total Transactions')</p>
                    </div>
                    <h3 class="dashboard-widget-4__number">{{ $user->transactions_count }}</h3>
                    <div class="dashboard-widget-4__vector">
                        <i class="ti ti-transform"></i>
                    </div>
                </a>
            </div>
        </div>
    </div>

    <div class="col-12">
        <div class="custom--card">
            <div class="card-header">
                <h3 class="title">@lang('Information About') {{ $user->fullname }}</h3>
            </div>
            <form action="{{ route('admin.user.update', $user->id) }}" method="POST">
                @csrf
                <div class="card-body">
                    <div class="row gy-3">
                        <div class="col-md-6">
                            <div class="row g-2 align-items-center">
                                <div class="col-lg-4">
                                    <label class="col-form--label required">@lang('First Name')</label>
                                </div>
                                <div class="col-lg-8">
                                    <input type="text" class="form--control" name="firstname" value="{{ $user->firstname }}" required>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="row g-2 align-items-center">
                                <div class="col-lg-4">
                                    <label class="col-form--label required">@lang('Last Name')</label>
                                </div>
                                <div class="col-lg-8">
                                    <input type="text" class="form--control" name="lastname" value="{{ $user->lastname }}" required>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="row g-2 align-items-center">
                                <div class="col-lg-4">
                                    <label class="col-form--label required">@lang('Email')</label>
                                </div>
                                <div class="col-lg-8">
                                    <input type="email" class="form--control" name="email" value="{{ $user->email }}" required>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="row g-2 align-items-center">
                                <div class="col-lg-4">
                                    <label class="col-form--label required">@lang('Country')</label>
                                </div>
                                <div class="col-lg-8">
                                    <select class="form--control form-select select-2" name="country" required>
                                        @foreach($countries as $key => $country)
                                            <option data-mobile_code="{{ $country->dial_code }}" value="{{ $key }}">
                                                {{ __($country->country) }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="row g-2 align-items-center">
                                <div class="col-lg-4">
                                    <label class="col-form--label required">@lang('Mobile')</label>
                                </div>
                                <div class="col-lg-8">
                                    <div class="input--group">
                                        <span class="input-group-text mobile-code"></span>
                                        <input type="number" class="form--control" name="mobile" id="mobile" required>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="row g-2 align-items-center">
                                <div class="col-lg-4">
                                    <label class="col-form--label">@lang('City')</label>
                                </div>
                                <div class="col-lg-8">
                                    <input type="text" class="form--control" name="city" value="{{ $user->address?->city ?? '' }}">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="row g-2 align-items-center">
                                <div class="col-lg-4">
                                    <label class="col-form--label">@lang('State')</label>
                                </div>
                                <div class="col-lg-8">
                                    <input type="text" class="form--control" name="state" value="{{ $user->address?->state ?? '' }}">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="row g-2 align-items-center">
                                <div class="col-lg-4">
                                    <label class="col-form--label">@lang('Zip Code')</label>
                                </div>
                                <div class="col-lg-8">
                                    <input type="text" class="form--control" name="zip" value="{{ $user->address?->zip ?? '' }}">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body border-top">
                    <div class="row gy-3 checkbox-separator">
                        <div class="col-xl-3 col-sm-6">
                            <div class="row align-items-center">
                                <div class="col-8">
                                    <label class="col-form--label required">@lang('Email Confirmation')</label>
                                </div>
                                <div class="col-4 d-flex justify-content-end">
                                    <div class="form-check form--switch">
                                        <input type="checkbox" class="form-check-input" name="ec" @checked($user->ec)>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-sm-6">
                            <div class="row align-items-center">
                                <div class="col-8">
                                    <label class="col-form--label required">@lang('Mobile Confirmation')</label>
                                </div>
                                <div class="col-4 d-flex justify-content-end">
                                    <div class="form-check form--switch">
                                        <input type="checkbox" class="form-check-input" name="sc" @checked($user->sc)>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-sm-6">
                            <div class="row align-items-center">
                                <div class="col-8">
                                    <label class="col-form--label required">@lang('2FA Status')</label>
                                </div>
                                <div class="col-4 d-flex justify-content-end">
                                    <div class="form-check form--switch">
                                        <input type="checkbox" class="form-check-input" name="ts" @checked($user->ts)>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-sm-6">
                            <div class="row align-items-center">
                                <div class="col-8">
                                    <label class="col-form--label required">@lang('KYC Confirmation')</label>
                                </div>
                                <div class="col-4 d-flex justify-content-end">
                                    <div class="form-check form--switch">
                                        <input type="checkbox" class="form-check-input" name="kc" @checked($user->kc == ManageStatus::VERIFIED)>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                @can('update user information')
                    <div class="card-body border-top">
                        <div class="d-flex justify-content-center">
                            <button type="submit" class="btn btn--base px-4">@lang('Submit')</button>
                        </div>
                    </div>
                @endcan
            </form>
        </div>
    </div>

    <div class="col-12">
        <div class="custom--modal modal fade" id="balanceUpdateModal" tabindex="-1" aria-labelledby="balanceUpdateModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h2 class="modal-title" id="balanceUpdateModalLabel"></h2>
                        <button type="button" class="btn btn--sm btn--icon btn-outline--secondary modal-close" data-bs-dismiss="modal" aria-label="Close">
                            <i class="ti ti-x"></i>
                        </button>
                    </div>
                    <form action="{{ route('admin.user.add.sub.balance', $user->id) }}" method="POST">
                        @csrf
                        <input type="hidden" name="act">
                        <div class="modal-body">
                            <div class="row g-3">
                                <div class="rol-12">
                                    <label class="form--label required">@lang('Amount')</label>
                                    <div class="input--group">
                                        <input type="number" step="any" min="0" class="form--control form--control--sm" name="amount" placeholder="@lang('Enter a positive amount')" required>
                                        <span class="input-group-text">{{ __($setting->site_cur) }}</span>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <label class="form--label required">@lang('Remark')</label>
                                    <textarea class="form--control form--control--sm" name="remark" placeholder="@lang('Remark')" required></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer gap-2">
                            <button type="button" class="btn btn--sm btn--secondary" data-bs-dismiss="modal">@lang('Close')</button>
                            <button type="submit" class="btn btn--sm btn--base">@lang('Submit')</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="col-12">
        <div class="custom--modal modal fade" id="statusModal" tabindex="-1" aria-labelledby="statusModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <button type="button" class="btn btn--sm btn--icon btn-outline--secondary modal-close" data-bs-dismiss="modal" aria-label="Close">
                        <i class="ti ti-x"></i>
                    </button>

                    <form action="{{ route('admin.user.status', $user->id) }}" method="POST">
                        @csrf
                        <div class="modal-body text-center modal-alert">
                            <div class="modal-thumb">
                                <img src="{{ asset('assets/admin/images/light.png') }}" alt="Image">
                            </div>
                            <h2 class="modal-title" id="statusModalLabel">
                                {{ $user->status ? trans('Ban User') : trans('Unban User') }}
                            </h2>
                            <p class="mb-3">
                                @if ($user->status)
                                    @lang('Banning this user will restrict their access to the dashboard').
                                @else
                                    @lang('Do you confirm the action to unban on this user')?
                                @endif
                            </p>

                            @if ($user->status)
                                <label class="form--label required">@lang('Reason')</label>
                                <textarea class="form--control form--control--sm mb-3" name="ban_reason" required></textarea>
                            @else
                                <label class="form--label">@lang('Ban Reason'):</label>
                                <p class="mb-4 text-start">{{ __($user->ban_reason) }}</p>
                            @endif

                            <div class="d-flex gap-2 justify-content-center">
                                <button type="button" class="btn btn--sm btn-outline--base" data-bs-dismiss="modal">@lang('No')</button>
                                <button type="submit" class="btn btn--sm btn--base">@lang('Yes')</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('breadcrumb')
    <a href="{{ route('admin.user.index') }}" class="btn btn--sm btn--base">
        <i class="ti ti-circle-arrow-left"></i> @lang('Back')
    </a>

    @canany(['login as user', 'update user balance', 'change user status'])
        <div class="custom--dropdown">
            <button type="button" class="btn btn--sm btn--icon btn--base" data-bs-toggle="dropdown" aria-expanded="false">
                <i class="ti ti-dots-vertical"></i>
            </button>
            <ul class="dropdown-menu">
                @can('login as user')
                    <li>
                        <a href="{{ route('admin.user.login', $user->id) }}" target="_blank" class="dropdown-item">
                            <span class="dropdown-icon"><i class="ti ti-login-2 text--info"></i></span> @lang('Login as User')
                        </a>
                    </li>
                @endcan

                @can('update user balance')
                    <li>
                        <button type="button" class="dropdown-item balanceUpdateBtn" data-act="add">
                            <span class="dropdown-icon"><i class="ti ti-circle-plus text--success"></i></span> @lang('Add Balance')
                        </button>
                    </li>
                    <li>
                        <button type="button" class="dropdown-item balanceUpdateBtn" data-act="sub">
                            <span class="dropdown-icon"><i class="ti ti-circle-minus text--warning"></i></span> @lang('Sub Balance')
                        </button>
                    </li>
                @endcan

                @can('change user status')
                    <li>
                        @if ($user->status)
                            <button type="button" class="dropdown-item" data-bs-toggle="modal" data-bs-target="#statusModal">
                                <span class="dropdown-icon"><i class="ti ti-user-cancel text--danger"></i></span> @lang('Ban User')
                            </button>
                        @else
                            <button type="button" class="dropdown-item" data-bs-toggle="modal" data-bs-target="#statusModal">
                                <span class="dropdown-icon"><i class="ti ti-user-check text--base"></i></span> @lang('Unban User')
                            </button>
                        @endif
                    </li>
                @endcan
            </ul>
        </div>
    @endcanany
@endpush

@push('page-script')
    <script>
        (function ($) {
            "use strict";

            $('.balanceUpdateBtn').on('click', function () {
                let modal = $('#balanceUpdateModal');
                let act   = $(this).data('act');

                modal.find('[name=act]').val(act);

                if (act === 'add') {
                    modal.find('.modal-title').text(`@lang('Add Balance')`);
                } else {
                    modal.find('.modal-title').text(`@lang('Subtract Balance')`);
                }

                modal.modal('show');
            });

            let mobileElement = $('.mobile-code');
            let countryElement = $('[name=country]');

            countryElement.on('change', function() {
                mobileElement.text(`+${$('[name=country] :selected').data('mobile_code')}`);
            });

            countryElement.val('{{ $user->country_code }}');

            let dialCode     = $('[name=country] :selected').data('mobile_code');
            let mobileNumber = `{{ $user->mobile }}`;
            mobileNumber     = mobileNumber.replace(dialCode, '');

            $('[name=mobile]').val(mobileNumber);

            mobileElement.text(`+${dialCode}`);
        })(jQuery);
    </script>
@endpush
