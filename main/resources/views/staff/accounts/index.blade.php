@extends('staff.layouts.master')

@section('master')
    <div class="col-12">
        <div class="table-responsive scroll">
            <table class="table table--striped table-borderless table--responsive--sm">
                <thead>
                    <tr>
                        <th>@lang('S.N.')</th>
                        <th>@lang('Account Number')</th>
                        <th>@lang('Account Name')</th>
                        <th>@lang('Username')</th>

                        @if(isManager())
                            <th>@lang('Branch')</th>
                            <th>@lang('Opened By')</th>
                        @endif

                        <th>@lang('Opening Date')</th>
                        <th>@lang('Action')</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($accounts as $account)
                        <tr>
                            <td>{{ $accounts->firstItem() + $loop->index }}</td>
                            <td>{{ $account->account_number }}</td>
                            <td>{{ __($account->fullname) }}</td>
                            <td>{{ $account->username }}</td>

                            @if(isManager())
                                <td>{{ __($account->branch->name) }}</td>
                                <td>{{ __($account->staff->name) }}</td>
                            @endif

                            <td>
                                <span>
                                    <span class="d-block">{{ showDateTime($account->created_at) }}</span>
                                    <span class="d-block">{{ diffForHumans($account->created_at) }}</span>
                                </span>
                            </td>
                            <td>
                                <div class="custom--dropdown">
                                    <button type="button" class="btn btn--icon btn--sm btn--base" data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class="ti ti-dots-vertical"></i>
                                    </button>
                                    <ul class="dropdown-menu">
                                        <li>
                                            <a href="#accountDetails" class="dropdown-item btn-account-details" data-bs-toggle="offcanvas" @if(isManager()) data-branch="{{ __($account->branch->name) }}" data-staff="{{ __($account->staff->name) }}" @endif data-account_number="{{ $account->account_number }}" data-image="{{ getImage(getFilePath('userProfile') . "/$account->image", getFileSize('userProfile'), true) }}" data-full_name="{{ __($account->fullname) }}" data-username="{{ $account->username }}" data-email_address="{{ $account->email }}" data-country="{{ __($account->country_name) }}" data-mobile="{{ "+$account->mobile" }}" data-balance="{{ showAmount($account->balance) . ' ' . $setting->site_cur }}" data-address="{{ isset($account->address->address) ? __($account->address->address) : trans('N/A') }}" data-city="{{ isset($account->address->city) ? __($account->address->city) : trans('N/A') }}" data-state="{{ isset($account->address->state) ? __($account->address->state) : trans('N/A') }}" data-zip_code="{{ isset($account->address->zip) ? $account->address->zip : 'N/A' }}" data-status="{{ $account->status_badge }}" @if($account->status == ManageStatus::INACTIVE) data-ban_reason="{{ __($account->ban_reason) }}" @endif data-opening_date="{{ showDateTime($account->created_at) }}" data-email_status="{{ $account->email_status_badge }}" data-mobile_status="{{ $account->mobile_status_badge }}">
                                                <span class="dropdown-icon"><i class="ti ti-device-desktop text--info"></i></span> @lang('Account Details')
                                            </a>
                                        </li>

                                        @if(!isManager())
                                            <li>
                                                <a href="{{ route('staff.accounts.edit', $account->account_number) }}" class="dropdown-item">
                                                    <span class="dropdown-icon"><i class="ti ti-edit text--base"></i></span> @lang('Edit Account')
                                                </a>
                                            </li>
                                            <li>
                                                <button type="button" class="dropdown-item btn-deposit" data-modal_heading="@lang('Deposit Money')" data-action="{{ route('staff.accounts.deposit.money', $account->account_number) }}" data-bs-toggle="modal" data-bs-target="#depositAndWithdrawModal">
                                                    <span class="dropdown-icon"><i class="ti ti-pig-money text--success"></i></span> @lang('Deposit Money')
                                                </button>
                                            </li>
                                            <li>
                                                <button type="button" class="dropdown-item btn-withdraw" data-modal_heading="@lang('Withdraw Money')" data-action="{{ route('staff.accounts.withdraw.money', $account->account_number) }}" data-bs-toggle="modal" data-bs-target="#depositAndWithdrawModal">
                                                    <span class="dropdown-icon"><i class="ti ti-cash-banknote text--warning"></i></span> @lang('Withdraw Money')
                                                </button>
                                            </li>
                                            <li>
                                                <a href="{{ route('staff.accounts.statement', $account->account_number) }}" class="dropdown-item">
                                                    <span class="dropdown-icon"><i class="ti ti-file-invoice text--base-two"></i></span> @lang('Account Statement')
                                                </a>
                                            </li>
                                        @endif
                                    </ul>
                                </div>
                            </td>
                        </tr>
                    @empty
                        @include('partials.noData')
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($accounts->hasPages())
            {{ paginateLinks($accounts) }}
        @endif
    </div>

    <div class="col-12">
        <div class="custom--offcanvas offcanvas offcanvas-end" tabindex="-1" id="accountDetails" aria-labelledby="accountDetailsLabel">
            <div class="offcanvas-header">
                <h5 class="offcanvas-title" id="accountDetailsLabel">@lang('Account Details')</h5>
                <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
            </div>
            <div class="offcanvas-body" id="accountDetailsBody">
                <div class="account-details-image rounded border overflow-hidden mb-3">
                    <img src="" alt="" class="img-fluid" id="accountImage">
                </div>
                <p class="offcanvas__subtitle fw-semibold mb-1">@lang('Basic Information')</p>
                <table class="table table-borderless mb-3">
                    <tbody id="accountInfo"></tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="custom--modal modal fade" id="depositAndWithdrawModal" tabindex="-1" aria-labelledby="depositAndWithdrawModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h2 class="modal-title" id="depositAndWithdrawModalLabel"></h2>
                    <button type="button" class="btn btn--sm btn--icon btn-outline--secondary modal-close" data-bs-dismiss="modal" aria-label="Close">
                        <i class="ti ti-x"></i>
                    </button>
                </div>
                <form action="" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-12">
                                <label class="form--label required">@lang('Amount')</label>
                                <div class="input--group">
                                    <input type="number" step="any" min="0" class="form--control" name="amount" required>
                                    <span class="input-group-text">{{ __($setting->site_cur) }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer gap-2">
                        <button type="button" class="btn btn--sm btn-outline--base" data-bs-dismiss="modal">@lang('Close')</button>
                        <button type="submit" class="btn btn--sm btn--base">@lang('Save')</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('breadcrumb')
    <x-searchForm placeholder="Search Here..." />

    @if(bs('open_account') && !isManager())
        <a href="{{ route('staff.accounts.create') }}" class="btn btn--sm btn--base d-flex align-items-center gap-1">
            <i class="ti ti-circle-plus transform-0"></i> @lang('Create Account')
        </a>
    @endif
@endpush

@push('page-style')
    <style>
        .account-details-image {
            width: 150px;
            height: 150px;
            margin: auto;
        }
    </style>
@endpush

@push('page-script')
    <script>
        (function ($) {
            'use strict'

            $(function () {
                $('.btn-account-details').on('click', function () {
                    let data = $(this).data()

                    $('#accountImage').attr('src', data.image)

                    let accountInfoHtml = `
                        <tr>
                            <td class="fw-bold">@lang('Account No.')</td>
                            <td>${data.account_number}</td>
                        </tr>
                        <tr>
                            <td class="fw-bold">@lang('Full Name')</td>
                            <td>${data.full_name}</td>
                        </tr>
                        <tr>
                            <td class="fw-bold">@lang('Username')</td>
                            <td>${data.username}</td>
                        </tr>
                        <tr>
                            <td class="fw-bold">@lang('Email')</td>
                            <td>${data.email_address}</td>
                        </tr>
                        <tr>
                            <td class="fw-bold">@lang('Email Status')</td>
                            <td>${data.email_status}</td>
                        </tr>
                        <tr>
                            <td class="fw-bold">@lang('Contact No.')</td>
                            <td>${data.mobile}</td>
                        </tr>
                        <tr>
                            <td class="fw-bold">@lang('Contact Status')</td>
                            <td>${data.mobile_status}</td>
                        </tr>
                        <tr>
                            <td class="fw-bold">@lang('Balance')</td>
                            <td>${data.balance}</td>
                        </tr>
                        <tr>
                            <td class="fw-bold">@lang('Street Address')</td>
                            <td>${data.address}</td>
                        </tr>
                        <tr>
                            <td class="fw-bold">@lang('City')</td>
                            <td>${data.city}</td>
                        </tr>
                        <tr>
                            <td class="fw-bold">@lang('State')</td>
                            <td>${data.state}</td>
                        </tr>
                        <tr>
                            <td class="fw-bold">@lang('ZIP Code')</td>
                            <td>${data.zip_code}</td>
                        </tr>
                        <tr>
                            <td class="fw-bold">@lang('Country')</td>
                            <td>${data.country}</td>
                        </tr>
                    `

                    if (data.branch && data.staff) {
                        accountInfoHtml += `
                            <tr>
                                <td class="fw-bold">@lang('Branch')</td>
                                <td>${data.branch}</td>
                            </tr>
                            <tr>
                                <td class="fw-bold">@lang('Opened By')</td>
                                <td>${data.staff}</td>
                            </tr>
                        `
                    }

                    accountInfoHtml += `
                        <tr>
                            <td class="fw-bold">@lang('Opening Date')</td>
                            <td>${data.opening_date}</td>
                        </tr>
                        <tr>
                            <td class="fw-bold">@lang('Status')</td>
                            <td>${data.status}</td>
                        </tr>
                    `

                    $('#accountInfo').html(accountInfoHtml)

                    if (data.ban_reason) {
                        $('#accountDetailsBody').append(`
                            <div id="banReason">
                                <p class="offcanvas__subtitle fw-semibold">@lang('Ban Reason')</p>
                                <div class="custom--card h-auto">
                                    <div class="card-body p-3">
                                        <p>${data.ban_reason}</p>
                                    </div>
                                </div>
                            </div>
                        `)
                    } else {
                        let banReasonHtml = $('#banReason')

                        if (banReasonHtml.length) banReasonHtml.remove()
                    }
                })

                $('.btn-deposit, .btn-withdraw').on('click', function () {
                    let data = $(this).data()

                    $('#depositAndWithdrawModalLabel').text(data.modal_heading)
                    $('#depositAndWithdrawModal').find('form').attr('action', data.action)
                })
            })
        })(jQuery)
    </script>
@endpush
