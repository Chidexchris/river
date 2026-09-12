@extends("{$activeTheme}layouts.auth")

@section('auth')

    {{-- ═══════════════════════════════════════
         PAGE HEADER
    ═══════════════════════════════════════ --}}
    <div class="workspace-header">
        <div>
            <span class="eyebrow">@lang('Documents')</span>
            <h1>@lang('Account Statement')</h1>
            <p>@lang('Generate and export a detailed report of your transactions.')</p>
        </div>
    </div>

    {{-- ═══════════════════════════════════════
         FILTER FORM
    ═══════════════════════════════════════ --}}
    <div class="custom--card mb-4">
        <div class="card-body">
            <form action="{{ route('user.fetch.statement') }}" method="get" class="row g-3 align-items-end">
                <div class="col-xl-4 col-sm-6">
                    <label class="form--label">@lang('Date Range')</label>
                    <input type="text"
                           class="form--control form--control--sm date-picker"
                           name="date"
                           value="{{ request('date') }}"
                           data-range="true"
                           data-multiple-dates-separator=" - "
                           data-language="en"
                           placeholder="@lang('Start Date - End Date')"
                           autocomplete="off">
                </div>
                <div class="col-xl-2 col-sm-6">
                    <label class="form--label">@lang('Transaction Type')</label>
                    <select class="form--control form--control--sm wide" name="trx_type">
                        <option selected value="">@lang('All')</option>
                        <option value="+" @selected(request('trx_type') == '+')>@lang('Credits (+)')</option>
                        <option value="-" @selected(request('trx_type') == '-')>@lang('Debits (-)')</option>
                    </select>
                </div>
                <div class="col-xl-2 col-sm-6">
                    <label class="form--label">@lang('From Amount')</label>
                    <input type="number" step="any" min="0" class="form--control form--control--sm" name="from_amount" placeholder="@lang('Min')" value="{{ request('from_amount') }}">
                </div>
                <div class="col-xl-2 col-sm-6">
                    <label class="form--label">@lang('To Amount')</label>
                    <input type="number" step="any" min="0" class="form--control form--control--sm" name="to_amount" placeholder="@lang('Max')" value="{{ request('to_amount') }}">
                </div>
                <div class="col-xl-2">
                    <button type="submit" class="btn btn--base w-100">
                        <i class="ti ti-filter"></i> @lang('Generate')
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- ═══════════════════════════════════════
         RESULTS TABLE
    ═══════════════════════════════════════ --}}
    @isset($transactions)
        <div class="data-panel">
            <div class="data-panel__head">
                <div>
                    <span class="eyebrow">@lang('Statement')</span>
                    <h5>@lang('Financial overview')</h5>
                </div>
                @if($transactions->count())
                    <form action="{{ route('user.export.statement') }}" method="post">
                        @csrf
                        <input type="hidden" name="date" value="{{ request('date') }}">
                        <button type="submit" class="btn btn--base btn--sm">
                            <i class="ti ti-download"></i> @lang('Export PDF')
                        </button>
                    </form>
                @endif
            </div>

            <div class="table-responsive">
                <table class="table table-borderless table--striped table--responsive--md">
                    <thead>
                        <tr>
                            <th>@lang('TRX')</th>
                            <th>@lang('Date')</th>
                            <th>@lang('Amount')</th>
                            <th>@lang('Post Balance')</th>
                            <th>@lang('Details')</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($transactions as $transaction)
                            <tr>
                                <td>
                                    <span style="font-family:'Inter',monospace;font-weight:600;font-size:13px;color:var(--pb-text-1)">
                                        {{ $transaction->trx }}
                                    </span>
                                </td>
                                <td>
                                    <span>
                                        <span class="d-block" style="font-size:13px">{{ showDateTime($transaction->created_at) }}</span>
                                        <small>{{ diffForHumans($transaction->created_at) }}</small>
                                    </span>
                                </td>
                                <td>
                                    <span class="@if ($transaction->trx_type == '+') text--success @else text--danger @endif"
                                          style="font-weight:700;font-size:15px">
                                        {{ $transaction->trx_type == '+' ? '+' : '-' }} {{ showAmount($transaction->amount) . ' ' . __($setting->site_cur) }}
                                    </span>
                                </td>
                                <td>
                                    <span style="font-weight:600;color:var(--pb-text-1)">
                                        {{ showAmount($transaction->post_balance) . ' ' . __($setting->site_cur) }}
                                    </span>
                                </td>
                                <td>{{ __($transaction->details) }}</td>
                            </tr>
                        @empty
                            @include('partials.noData')
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($transactions->hasPages())
                <div style="padding:16px 24px;">
                    {{ paginateLinks($transactions) }}
                </div>
            @endif
        </div>
    @endisset

@endsection

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
            })
        })(jQuery)
    </script>
@endpush
