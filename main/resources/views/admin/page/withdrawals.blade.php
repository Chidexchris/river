@extends('admin.layouts.master')

@section('master')
    @if(request()->routeIs('admin.withdrawals.index'))
        <div class="col-12">
            <div class="row g-lg-4 g-3">
                <div class="col-xl-3 col-sm-6">
                    <a href="{{ route('admin.withdrawals.done') }}" class="dashboard-widget-3 dashboard-widget-3__success">
                        <div class="dashboard-widget-3__top">
                            <h3 class="dashboard-widget-3__number">{{ $setting->cur_sym . showAmount($done) }}</h3>
                            <div class="dashboard-widget-3__icon">
                                <i class="ti ti-circle-check"></i>
                            </div>
                        </div>
                        <p class="dashboard-widget-3__txt">@lang('Done Withdrawn Amount')</p>
                    </a>
                </div>
                <div class="col-xl-3 col-sm-6">
                    <a href="{{ route('admin.withdrawals.index') }}" class="dashboard-widget-3 dashboard-widget-3__info">
                        <div class="dashboard-widget-3__top">
                            <h3 class="dashboard-widget-3__number">{{ $setting->cur_sym . showAmount($charge) }}</h3>
                            <div class="dashboard-widget-3__icon">
                                <i class="ti ti-coins"></i>
                            </div>
                        </div>
                        <p class="dashboard-widget-3__txt">@lang('Total Charge for Withdrawn')</p>
                    </a>
                </div>
                <div class="col-xl-3 col-sm-6">
                    <a href="{{ route('admin.withdrawals.pending') }}" class="dashboard-widget-3 dashboard-widget-3__warning">
                        <div class="dashboard-widget-3__top">
                            <h3 class="dashboard-widget-3__number">{{ $setting->cur_sym . showAmount($pending) }}</h3>
                            <div class="dashboard-widget-3__icon">
                                <i class="ti ti-rotate-clockwise-2"></i>
                            </div>
                        </div>
                        <p class="dashboard-widget-3__txt">@lang('Pending Withdrawn Amount')</p>
                    </a>
                </div>
                <div class="col-xl-3 col-sm-6">
                    <a href="{{ route('admin.withdrawals.cancelled') }}" class="dashboard-widget-3 dashboard-widget-3__danger">
                        <div class="dashboard-widget-3__top">
                            <h3 class="dashboard-widget-3__number">{{ $setting->cur_sym . showAmount($cancelled) }}</h3>
                            <div class="dashboard-widget-3__icon">
                                <i class="ti ti-circle-x"></i>
                            </div>
                        </div>
                        <p class="dashboard-widget-3__txt">@lang('Cancelled Withdrawn Amount')</p>
                    </a>
                </div>
            </div>
        </div>
    @endif

    <div class="col-12">
        <div class="table-responsive scroll">
            <table class="table table--striped table-borderless table--responsive--sm">
                <thead>
                    <tr>
                        <th>@lang('User')</th>
                        <th>@lang('Gateway') | @lang('Transaction')</th>
                        <th>@lang('Branch')</th>
                        <th>@lang('Staff')</th>
                        <th>@lang('Amount')</th>
                        <th>@lang('Conversion Rate')</th>
                        <th>@lang('Initiated')</th>
                        <th>@lang('Status')</th>
                        <th>@lang('Action')</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($withdrawals as $withdraw)
                        <tr>
                            <td>
                                <div class="table-card-with-image__content">
                                    <p class="fw-semibold">{{ $withdraw->user->fullname }}</p>
                                    <p class="fw-semibold">
                                        <a href="{{ appendQuery('search', $withdraw->user->username) }}">
                                            {{ $withdraw->user->username }}
                                        </a>
                                    </p>
                                </div>
                            </td>
                            <td>
                                <div>
                                    @if($withdraw->method)
                                        <a href="{{ appendQuery('method', $withdraw->method->id) }}" class="fw-semibold text--base">
                                            {{ __($withdraw->method->name) }}
                                        </a>
                                    @else
                                        <p class="fw-semibold text--base">@lang('Bank Payment')</p>
                                    @endif

                                    <p>{{ $withdraw->trx }}</p>
                                </div>
                            </td>
                            <td>
                                {{ $withdraw->branch ? __($withdraw->branch->name) : trans('N/A') }}
                            </td>
                            <td>
                                {{ $withdraw->staff ? __($withdraw->staff->name) : trans('N/A') }}
                            </td>
                            <td>
                                <div>
                                    <p>{{ $setting->cur_sym . showAmount($withdraw->amount) }} + <span class="text--danger" title="@lang('Charge')">{{ $setting->cur_sym . showAmount($withdraw->charge) }}</span></p>
                                    <p class="fw-semibold" title="@lang('Amount Without Charge')">{{ showAmount($withdraw->after_charge) . ' ' . __($setting->site_cur) }}</p>
                                </div>
                            </td>
                            <td>
                                <div>
                                    <p>1 {{ __($setting->site_cur) }} = {{ showAmount($withdraw->rate, 4) . ' ' . __($withdraw->currency) }}</p>
                                    <p class="fw-semibold">{{ showAmount($withdraw->final_amount) . ' ' . __($withdraw->currency) }}</p>
                                </div>
                            </td>
                            <td>
                                <div>
                                    <p>{{ showDateTime($withdraw->created_at) }}</p>
                                    <p>{{ diffForHumans($withdraw->created_at) }}</p>
                                </div>
                            </td>
                            <td>
                                @php echo $withdraw->status_badge @endphp
                            </td>
                            <td>
                                <div class="d-flex justify-content-end gap-2">
                                    <a href="#withdrawDetails" class="btn btn--sm btn-outline--base withdrawViewBtn" data-bs-toggle="offcanvas" data-username="{{ '@' . $withdraw->user->username }}" data-method="{{ $withdraw->method ? __($withdraw->method->name) : trans('Bank Payment') }}" data-amount="{{ showAmount($withdraw->amount) . ' ' . $setting->site_cur }}" data-charge="{{ showAmount($withdraw->charge) . ' ' . $setting->site_cur }}" data-subtracted_amount="{{ showAmount($withdraw->after_charge) . ' ' . __($setting->site_cur) }}" data-rate="{{ '1 ' . __($setting->site_cur) . ' = ' . showAmount($withdraw->rate, 4) . ' ' . __($withdraw->currency) }}" data-final_amount="{{ showAmount($withdraw->final_amount) . ' ' . __($withdraw->currency) }}" data-trx="{{ $withdraw->trx }}" data-status="{{ $withdraw->status_badge }}" data-created_at="{{ showDateTime($withdraw->created_at) }}" data-user_data="{{ json_encode($withdraw->withdraw_information) }}" data-admin_feedback="{{ __($withdraw->admin_feedback) }}" data-url="{{ route('admin.file.download', ['filePath' => 'verify']) }}">
                                        <i class="ti ti-info-square-rounded"></i> @lang('Details')
                                    </a>

                                    @canany(['approve withdraw', 'reject withdraw'])
                                        @if ($withdraw->status == ManageStatus::PAYMENT_PENDING)
                                            <div class="custom--dropdown">
                                                <button type="button" class="btn btn--icon btn--sm btn--base" data-bs-toggle="dropdown" aria-expanded="false">
                                                    <i class="ti ti-dots-vertical"></i>
                                                </button>
                                                <ul class="dropdown-menu">
                                                    @can('approve withdraw')
                                                        <li>
                                                            <button type="button" class="dropdown-item approveBtn" data-id="{{ $withdraw->id }}" data-amount="{{ showAmount($withdraw->final_amount) }}" data-currency="{{ $withdraw->currency }}">
                                                                <span class="dropdown-icon"><i class="ti ti-circle-check text--success"></i></span> @lang('Approve')
                                                            </button>
                                                        </li>
                                                    @endcan

                                                    @can('reject withdraw')
                                                        <li>
                                                            <button class="dropdown-item rejectBtn" data-id="{{ $withdraw->id }}">
                                                                <span class="dropdown-icon"><i class="ti ti-circle-x text--danger"></i></span> @lang('Reject')
                                                            </button>
                                                        </li>
                                                    @endcan
                                                </ul>
                                            </div>
                                        @endif
                                    @endcanany
                                </div>
                            </td>
                        </tr>
                    @empty
                        @include('partials.noData')
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($withdrawals->hasPages())
            {{ paginateLinks($withdrawals) }}
        @endif
    </div>

    <div class="col-12">
        <div class="custom--offcanvas offcanvas offcanvas-end" tabindex="-1" id="withdrawDetails" aria-labelledby="withdrawDetailsLabel">
            <div class="offcanvas-header">
                <h5 class="offcanvas-title" id="withdrawDetailsLabel">@lang('Details')</h5>
                <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
            </div>
            <div class="offcanvas-body">
                <p class="offcanvas__subtitle fw-semibold mb-1">@lang('Withdraw Information')</p>
                <table class="table table-borderless mb-3">
                    <tbody class="withdraw-info"></tbody>
                </table>
                <p class="offcanvas__subtitle fw-semibold mb-1">@lang('User Information')</p>
                <table class="table table-borderless mb-3">
                    <tbody class="user-info"></tbody>
                </table>
                <p class="offcanvas__subtitle fw-semibold mb-1">@lang('Admin Feedback')</p>
                <div class="custom--card h-auto">
                    <div class="card-body p-3">
                        <p class="admin-feedback"></p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-12">
        <div class="custom--modal modal fade" id="approveWithdrawModal" tabindex="-1" aria-labelledby="approveWithdrawModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <button type="button" class="btn btn--sm btn--icon btn-outline--secondary modal-close" data-bs-dismiss="modal" aria-label="Close">
                        <i class="ti ti-x"></i>
                    </button>
                    <div class="modal-body modal-alert">
                        <div class="text-center">
                            <div class="modal-thumb">
                                <img src="{{ asset('assets/admin/images/light.png') }}" alt="Image">
                            </div>
                            <h2 class="modal-title" id="approveWithdrawModalLabel">@lang('Approve Withdrawal Confirmation')</h2>
                            <p class="mb-3">
                                @lang('Have you sent') <span class="text--base fw-bold withdraw-amount"></span> <span class="withdraw-currency"></span>?
                            </p>
                            <form action="{{ route('admin.withdrawals.approve') }}" method="POST">
                                @csrf
                                <input type="hidden" name="id">
                                <textarea class="form--control form--control--sm" name="admin_feedback" placeholder="@lang('For example, furnish the specifics, such as the transaction number.')" required></textarea>
                                <div class="d-flex justify-content-center gap-2 mt-3">
                                    <button type="button" class="btn btn--sm btn-outline--base" data-bs-dismiss="modal">@lang('No')</button>
                                    <button type="submit" class="btn btn--sm btn--base">@lang('Yes')</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-12">
        <div class="custom--modal modal fade" id="rejectWithdrawModal" tabindex="-1" aria-labelledby="rejectWithdrawModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <button type="button" class="btn btn--sm btn--icon btn-outline--secondary modal-close" data-bs-dismiss="modal" aria-label="Close">
                        <i class="ti ti-x"></i>
                    </button>
                    <div class="modal-body modal-alert">
                        <div class="text-center">
                            <div class="modal-thumb">
                                <img src="{{ asset('assets/admin/images/light.png') }}" alt="Image">
                            </div>
                            <h2 class="modal-title" id="rejectWithdrawModalLabel">@lang('Reject Withdrawal Confirmation')</h2>
                            <form action="{{ route('admin.withdrawals.reject') }}" method="POST">
                                @csrf
                                <input type="hidden" name="id">
                                <label class="form--label">@lang('Reason'):</label>
                                <textarea class="form--control form--control--sm" name="admin_feedback" required></textarea>
                                <div class="d-flex justify-content-center gap-2 mt-3">
                                    <button type="button" class="btn btn--sm btn-outline--base" data-bs-dismiss="modal">@lang('No')</button>
                                    <button type="submit" class="btn btn--sm btn--base">@lang('Yes')</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('breadcrumb')
    <x-searchForm placeholder="TRX / Username" dateSearch="yes" />
@endpush

@push('page-script')
    <script>
        (function ($) {
            "use strict";

            $('.withdrawViewBtn').on('click', function () {
                let withdrawHtml = `<tr>
                    <td class="fw-bold">@lang('Username')</td>
                    <td>${$(this).data('username')}</td>
                </tr>
                <tr>
                    <td class="fw-bold">@lang('Gateway')</td>
                    <td>${$(this).data('method')}</td>
                </tr>
                <tr>
                    <td class="fw-bold">@lang('Amount')</td>
                    <td>${$(this).data('amount')}</td>
                </tr>
                <tr>
                    <td class="fw-bold">@lang('Charge')</td>
                    <td>${$(this).data('charge')}</td>
                </tr>
                <tr>
                    <td class="fw-bold">@lang('Subtracted Amount')</td>
                    <td>${$(this).data('subtracted_amount')}</td>
                </tr>
                <tr>
                    <td class="fw-bold">@lang('Rate')</td>
                    <td>${$(this).data('rate')}</td>
                </tr>
                <tr>
                    <td class="fw-bold">@lang('Receivable')</td>
                    <td>${$(this).data('final_amount')}</td>
                </tr>
                <tr>
                    <td class="fw-bold">@lang('Transaction')</td>
                    <td>${$(this).data('trx')}</td>
                </tr>
                <tr>
                    <td class="fw-bold">@lang('Status')</td>
                    <td>${$(this).data('status')}</td>
                </tr>
                <tr>
                    <td class="fw-bold">@lang('Initiated')</td>
                    <td>${$(this).data('created_at')}</td>
                </tr>`

                $('.withdraw-info').html(withdrawHtml)

                let userData = $(this).data('user_data')
                let downloadUrl = $(this).data('url')
                let userDataHtml = ``

                if (userData) {
                    userData.forEach(element => {
                        if (!element.value) return

                        if (element.type !== 'file') {
                            userDataHtml += `<tr>
                                                <td class="fw-bold">${element.name}</td>
                                                <td>${element.value}</td>
                                            </tr>`
                        } else {
                            userDataHtml += `<tr>
                                                <td class="fw-bold">${element.name}</td>
                                                <td>
                                                    <a href="${downloadUrl}&fileName=${element.value}" class="btn btn--sm btn-outline--secondary">
                                                        <i class="ti ti-download"></i> @lang('Download')
                                                    </a>
                                                </td>
                                            </tr>`
                        }
                    })
                } else {
                    userDataHtml += `<tr>
                                        <td class="text-center">@lang('No Data Found')</td>
                                    </tr>`
                }

                $('.user-info').html(userDataHtml)

                // admin feedback
                let feedback = $(this).data('admin_feedback')

                if (feedback) {
                    $('.admin-feedback').removeClass('text-center').html(feedback)
                } else {
                    $('.admin-feedback').addClass('text-center').html("@lang('No Feedback')")
                }
            })

            $('.approveBtn').on('click', function () {
                let modal = $('#approveWithdrawModal')

                modal.find('[name=id]').val($(this).data('id'))
                modal.find('.withdraw-amount').text($(this).data('amount'))
                modal.find('.withdraw-currency').text($(this).data('currency'))
                modal.modal('show')
            })

            $('.rejectBtn').on('click', function () {
                let modal = $('#rejectWithdrawModal')

                modal.find('[name=id]').val($(this).data('id'))
                modal.modal('show')
            })
        })(jQuery)
    </script>
@endpush
