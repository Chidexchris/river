@extends('admin.layouts.master')

@section('master')
    @if(request()->routeIs('admin.deposits.index'))
        <div class="col-12">
            <div class="row g-lg-4 g-3">
                <div class="col-xl-3 col-sm-6">
                    <a href="{{ route('admin.deposits.done') }}" class="dashboard-widget-3 dashboard-widget-3__success">
                        <div class="dashboard-widget-3__top">
                            <h3 class="dashboard-widget-3__number">{{ $setting->cur_sym . showAmount($done) }}</h3>
                            <div class="dashboard-widget-3__icon">
                                <i class="ti ti-circle-check"></i>
                            </div>
                        </div>
                        <p class="dashboard-widget-3__txt">@lang('Done Deposit Amount')</p>
                    </a>
                </div>
                <div class="col-xl-3 col-sm-6">
                    <a href="{{ route('admin.deposits.index') }}" class="dashboard-widget-3 dashboard-widget-3__info">
                        <div class="dashboard-widget-3__top">
                            <h3 class="dashboard-widget-3__number">{{ $setting->cur_sym . showAmount($charge) }}</h3>
                            <div class="dashboard-widget-3__icon">
                                <i class="ti ti-coins"></i>
                            </div>
                        </div>
                        <p class="dashboard-widget-3__txt">@lang('Total Charge for Deposit')</p>
                    </a>
                </div>
                <div class="col-xl-3 col-sm-6">
                    <a href="{{ route('admin.deposits.pending') }}" class="dashboard-widget-3 dashboard-widget-3__warning">
                        <div class="dashboard-widget-3__top">
                            <h3 class="dashboard-widget-3__number">{{ $setting->cur_sym . showAmount($pending) }}</h3>
                            <div class="dashboard-widget-3__icon">
                                <i class="ti ti-rotate-clockwise-2"></i>
                            </div>
                        </div>
                        <p class="dashboard-widget-3__txt">@lang('Pending Deposit Amount')</p>
                    </a>
                </div>
                <div class="col-xl-3 col-sm-6">
                    <a href="{{ route('admin.deposits.cancelled') }}" class="dashboard-widget-3 dashboard-widget-3__danger">
                        <div class="dashboard-widget-3__top">
                            <h3 class="dashboard-widget-3__number">{{ $setting->cur_sym . showAmount($cancelled) }}</h3>
                            <div class="dashboard-widget-3__icon">
                                <i class="ti ti-circle-x"></i>
                            </div>
                        </div>
                        <p class="dashboard-widget-3__txt">@lang('Cancelled Deposit Amount')</p>
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
                    @forelse ($deposits as $deposit)
                        <tr>
                            <td>
                                <div class="table-card-with-image__content">
                                    <p class="fw-semibold">{{ __($deposit->user->fullname) }}</p>
                                    <p class="fw-semibold">
                                        <a href="{{ appendQuery('search', $deposit->user->username) }}">
                                            <small>@</small>{{ $deposit->user->username }}
                                        </a>
                                    </p>
                                </div>
                            </td>
                            <td>
                                <div>
                                    @if($deposit->gateway)
                                        <a href="{{ appendQuery('method', $deposit->gateway->alias) }}" class="fw-semibold text--base">
                                            {{ __($deposit->gateway->name) }}
                                        </a>
                                    @else
                                        <p class="fw-semibold text--base">@lang('Bank Payment')</p>
                                    @endif

                                    <p>{{ $deposit->trx }}</p>
                                </div>
                            </td>
                            <td>
                                {{ $deposit->branch ? __($deposit->branch->name) : trans('N/A') }}
                            </td>
                            <td>
                                {{ $deposit->staff ? __($deposit->staff->name) : trans('N/A') }}
                            </td>
                            <td>
                                <div>
                                    <p>{{ $setting->cur_sym . showAmount($deposit->amount) }} + <span class="text--danger" title="@lang('Charge')">{{ $setting->cur_sym . showAmount($deposit->charge) }}</span></p>
                                    <p class="fw-semibold" title="Amount With Charge">{{ showAmount($deposit->amount + $deposit->charge) . ' ' . __($setting->site_cur) }}</p>
                                </div>
                            </td>
                            <td>
                                <div>
                                    <p>1 {{ __($setting->site_cur) }} = {{ showAmount($deposit->rate, 4) . ' ' . __($deposit->method_currency) }}</p>
                                    <p class="fw-semibold">{{ showAmount($deposit->final_amount) . ' ' . __($deposit->method_currency) }}</p>
                                </div>
                            </td>
                            <td>
                                <div>
                                    <p>{{ showDateTime($deposit->created_at) }}</p>
                                    <p>{{ diffForHumans($deposit->created_at) }}</p>
                                </div>
                            </td>
                            <td>
                                @php echo $deposit->status_badge @endphp
                            </td>
                            <td>
                                <div class="d-flex justify-content-end gap-2">
                                    <a href="#depositDetails" class="btn btn--icon btn--sm btn-outline--base depositViewBtn" title="@lang('Details')" data-bs-toggle="offcanvas" data-username="{{ '@' . $deposit->user->username }}" data-method="{{ $deposit->gateway ? __($deposit->gateway->name) : trans('Bank Payment') }}" data-amount="{{ showAmount($deposit->amount) . ' ' . $setting->site_cur }}" data-charge="{{ showAmount($deposit->charge) . ' ' . $setting->site_cur }}" data-total_amount="{{ showAmount($deposit->amount + $deposit->charge) . ' ' . __($setting->site_cur) }}" data-rate="{{ '1 ' . __($setting->site_cur) . ' = ' . showAmount($deposit->rate, 4) . ' ' . __($deposit->method_currency) }}" data-final_amount="{{ showAmount($deposit->final_amount) . ' ' . __($deposit->method_currency) }}" data-trx="{{ $deposit->trx }}" data-status="{{ $deposit->status_badge }}" data-created_at="{{ showDateTime($deposit->created_at) }}" data-user_data="{{ json_encode($deposit->details) }}" data-admin_feedback="{{ __($deposit->admin_feedback) }}" data-url="{{ route('admin.file.download', ['filePath' => 'verify']) }}">
                                        <i class="ti ti-eye"></i>
                                    </a>

                                    @canany(['approve deposit', 'reject deposit'])
                                        @if ($deposit->status == ManageStatus::PAYMENT_PENDING)
                                            <div class="custom--dropdown">
                                                <button type="button" class="btn btn--icon btn--sm btn--base" data-bs-toggle="dropdown" aria-expanded="false">
                                                    <i class="ti ti-dots-vertical"></i>
                                                </button>
                                                <ul class="dropdown-menu">
                                                    @can('approve deposit')
                                                        <li>
                                                            <button type="button" class="dropdown-item decisionBtn" data-question="@lang('Do you want to approve this deposit?')" data-action="{{ route('admin.deposits.approve', $deposit->id) }}">
                                                                <span class="dropdown-icon"><i class="ti ti-circle-check text--success"></i></span> @lang('Approve')
                                                            </button>
                                                        </li>
                                                    @endcan

                                                    @can('reject deposit')
                                                        <li>
                                                            <button type="button" class="dropdown-item cancelBtn" data-action="{{ route('admin.deposits.reject', $deposit->id) }}">
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

        @if ($deposits->hasPages())
            {{ paginateLinks($deposits) }}
        @endif
    </div>

    <div class="col-12">
        <div class="custom--offcanvas offcanvas offcanvas-end" tabindex="-1" id="depositDetails" aria-labelledby="depositDetailsLabel">
            <div class="offcanvas-header">
                <h5 class="offcanvas-title" id="depositDetailsLabel">@lang('Details')</h5>
                <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
            </div>
            <div class="offcanvas-body">
                <p class="offcanvas__subtitle fw-semibold mb-1">@lang('Deposit Information')</p>
                <table class="table table-borderless mb-3">
                    <tbody class="deposit-info"></tbody>
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

    <x-decisionModal />

    <div class="col-12">
        <div class="custom--modal modal fade" id="cancelModal" tabindex="-1" aria-labelledby="cancelModalLabel" aria-hidden="true">
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
                            <h2 class="modal-title" id="cancelModalLabel">@lang('Make Your Decision')</h2>
                            <p class="mb-3">@lang('Do you want to reject this deposit?')</p>
                            <form action="" method="POST">
                                @csrf
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
            "use strict"

            $('.depositViewBtn').on('click', function () {
                let depositHtml = `<tr>
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
                    <td class="fw-bold">@lang('Total Amount')</td>
                    <td>${$(this).data('total_amount')}</td>
                </tr>
                <tr>
                    <td class="fw-bold">@lang('Rate')</td>
                    <td>${$(this).data('rate')}</td>
                </tr>
                <tr>
                    <td class="fw-bold">@lang('Payable')</td>
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

                $('.deposit-info').html(depositHtml)

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

            $('.cancelBtn').on('click', function () {
                let modal = $('#cancelModal')

                modal.find('form').attr('action', $(this).data('action'))
                modal.modal('show')
            })
        })(jQuery)
    </script>
@endpush
