@extends("{$activeTheme}layouts.auth")

@section('auth')

    {{-- ═══════════════════════════════════════
         PAGE HEADER
    ═══════════════════════════════════════ --}}
    <div class="workspace-header">
        <div>
            <span class="eyebrow">@lang('Credit')</span>
            <h1>@lang('My Loans')</h1>
            <p>@lang('Manage active loans, repayments, and upcoming obligations.')</p>
        </div>
        <div class="workspace-header__actions">
            <a href="{{ route('user.loan.plans') }}" class="btn btn--base">
                <i class="ti ti-plus"></i> @lang('Explore loan plans')
            </a>
        </div>
    </div>

    @if($loanList->count())

        {{-- ═══════════════════════════════════════
             LOAN SUMMARY STATS
        ═══════════════════════════════════════ --}}
        <div class="loan-summary-grid">
            <div>
                <span>@lang('Active loans')</span>
                <strong>{{ $loanList->total() }}</strong>
            </div>
            <div>
                <span>@lang('Outstanding balance')</span>
                <strong>@lang('See details')</strong>
            </div>
            <div>
                <span>@lang('Repayment status')</span>
                <strong style="color:var(--pb-green)">@lang('On schedule')</strong>
            </div>
        </div>

        {{-- ═══════════════════════════════════════
             SEARCH / FILTER TOOLBAR
        ═══════════════════════════════════════ --}}
        <div class="workspace-toolbar">
            <span>@lang('Your loan portfolio')</span>
            <form action="" method="get" class="d-flex flex-wrap gap-3 justify-content-center">
                <div class="input--group">
                    <input type="text" class="form--control form--control--sm" name="search" value="{{ request('search') }}" placeholder="@lang('Plan or Loan No.')">
                    <button type="submit" class="btn btn--sm btn--base px-3">
                        <i class="ti ti-search"></i>
                    </button>
                </div>
                <div class="input--group">
                    <input type="text" class="form--control form--control--sm date-picker" name="date" value="{{ request('date') }}" data-range="true" data-multiple-dates-separator=" - " data-language="en" placeholder="@lang('Date range')" autocomplete="off">
                    <button type="submit" class="btn btn--sm btn--base px-3">
                        <i class="ti ti-filter"></i>
                    </button>
                </div>
            </form>
        </div>

        {{-- ═══════════════════════════════════════
             LOANS TABLE
        ═══════════════════════════════════════ --}}
        <div class="financial-table">
            <div class="table-responsive">
                <table class="table table--striped table-borderless table--responsive--md">
                    <thead>
                        <tr>
                            <th>@lang('#')</th>
                            <th>@lang('Loan / Plan')</th>
                            <th>@lang('Amount')</th>
                            <th>@lang('Installment')</th>
                            <th>@lang('Progress')</th>
                            <th>@lang('Next Payment')</th>
                            <th>@lang('Status')</th>
                            <th>@lang('Action')</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($loanList as $loan)
                            <tr>
                                <td>{{ $loanList->firstItem() + $loop->index }}</td>
                                <td>
                                    <span>
                                        <span class="d-block" style="font-weight:700;color:var(--pb-text-1);font-family:'Inter',monospace;font-size:13px">{{ $loan->scheme_code }}</span>
                                        <span class="d-block text--base small">{{ __($loan->plan_name) }}</span>
                                    </span>
                                </td>
                                <td>
                                    <span>
                                        <span class="d-block" style="font-weight:700;color:var(--pb-text-1)">{{ $setting->cur_sym . showAmount($loan->amount_requested) }}</span>
                                        <small>@lang('Payable'): {{ $setting->cur_sym . showAmount($loan->payable_amount) }}</small>
                                    </span>
                                </td>
                                <td>
                                    <span>
                                        <span class="d-block" style="font-weight:600;color:var(--pb-text-1)">{{ $setting->cur_sym . showAmount($loan->per_installment) }}</span>
                                        <small>{{ trans('Every') . ' ' . $loan->installment_interval . ' ' . trans(\Illuminate\Support\Str::plural('Day', $loan->installment_interval)) }}</small>
                                    </span>
                                </td>
                                <td>
                                    @php
                                        $progress = $loan->total_installment > 0
                                            ? round(($loan->given_installment / $loan->total_installment) * 100)
                                            : 0;
                                    @endphp
                                    <div style="min-width:120px">
                                        <div style="font-size:11px;color:var(--pb-text-3);margin-bottom:4px;font-weight:600">
                                            {{ $loan->given_installment }} / {{ $loan->total_installment }} @lang('paid')
                                        </div>
                                        <div style="height:6px;background:rgba(255,255,255,0.1);border-radius:99px;overflow:hidden">
                                            <div style="height:100%;width:{{ $progress }}%;background:var(--pb-gold);border-radius:99px;transition:width 0.6s ease"></div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span>
                                        <span class="d-block" style="font-size:13px;color:var(--pb-text-1)">
                                            {{ is_null($loan->approved_at) ? trans('N/A') : showDateTime($loan->next_installment_date, 'd M, Y') }}
                                        </span>
                                        <small>@lang('Approved'): {{ is_null($loan->approved_at) ? trans('N/A') : showDateTime($loan->approved_at, 'd M, Y') }}</small>
                                    </span>
                                </td>
                                <td>@php echo $loan->status_badge @endphp</td>
                                <td>
                                    <div class="d-flex justify-content-end gap-2">
                                        <a href="{{ route('user.loan.installments', $loan) }}" class="btn btn-outline--base btn--icon" title="@lang('View installments')">
                                            <i class="ti ti-calendar-dollar transform-0"></i>
                                        </a>
                                        @if($loan->status == ManageStatus::LOAN_REJECTED)
                                            <button type="button"
                                                    class="btn btn--base btn--icon btn-feedback"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#feedbackModal"
                                                    data-admin_feedback="{{ __($loan->admin_feedback) }}"
                                                    title="@lang('View feedback')">
                                                <i class="ti ti-message transform-1"></i>
                                            </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            @include('partials.noData')
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        @if ($loanList->hasPages())
            <div style="margin-top:16px">
                {{ paginateLinks($loanList) }}
            </div>
        @endif

    @else

        {{-- ═══════════════════════════════════════
             EMPTY STATE
        ═══════════════════════════════════════ --}}
        <div class="premium-empty-state">
            <div class="premium-empty-state__visual">
                <i class="ti ti-building-bank"></i>
                <span></span>
            </div>
            <span class="eyebrow">@lang('No active credit')</span>
            <h2>@lang('No active loans')</h2>
            <p>@lang("You don't have any active loans. Explore flexible plans designed around your next goal.")</p>
            <a href="{{ route('user.loan.plans') }}" class="btn btn--base">
                @lang('Explore loan plans') <i class="ti ti-arrow-right"></i>
            </a>
        </div>

    @endif

@endsection

@push('user-panel-modal')
    <div class="custom--modal modal fade" id="feedbackModal" tabindex="-1" aria-labelledby="feedbackModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title" id="feedbackModalLabel">@lang('Admin Feedback')</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="@lang('Close')"></button>
                </div>
                <div class="modal-body">
                    <p class="px-1"></p>
                </div>
            </div>
        </div>
    </div>
@endpush

@push('page-style-lib')
    <link rel="stylesheet" href="{{ asset('assets/universal/css/datepicker.css') }}">
@endpush

@push('page-script-lib')
    <script src="{{ asset('assets/universal/js/datepicker.js') }}"></script>
    <script src="{{ asset('assets/universal/js/datepicker.en.js') }}"></script>
@endpush

@push('page-script')
    <script>
        (function ($) {
            'use strict'
            $(function () {
                let datePicker = $('.date-picker')
                datePicker.on('input keyup keydown keypress', function () { return false })
                datePicker.datepicker()

                $('.btn-feedback').on('click', function () {
                    $('#feedbackModal').find('p').text($(this).data('admin_feedback'))
                })
            })
        })(jQuery)
    </script>
@endpush
