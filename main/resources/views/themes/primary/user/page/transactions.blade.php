@extends("{$activeTheme}layouts.auth")

@section('auth')

    {{-- ═══════════════════════════════════════
         PAGE HEADER
    ═══════════════════════════════════════ --}}
    <div class="page-intro transaction-intro">
        <div>
            <span class="eyebrow">@lang('Activity')</span>
            <h1>@lang('Transactions')</h1>
            <p>@lang('Every movement across your account, in one secure place.')</p>
        </div>
        <a href="{{ route('user.account.statement') }}" class="btn btn--outline">
            <i class="ti ti-file-invoice"></i> @lang('View statement')
        </a>
    </div>

    {{-- ═══════════════════════════════════════
         FILTERS
    ═══════════════════════════════════════ --}}
    <div class="custom--card transaction-filters mb-4">
        <div class="card-body">
            <form action="" method="get" class="row g-3 align-items-end">
                <div class="col-xl-5 col-lg-4 col-sm-4">
                    <label class="form--label">@lang('Transaction Number')</label>
                    <input type="text" class="form--control form--control--sm" name="search" value="{{ request('search') }}" placeholder="@lang('e.g. TRX123...')">
                </div>
                <div class="col-xl-2 col-lg-3 col-sm-4">
                    <label class="form--label">@lang('Type')</label>
                    <select class="form--control form--control--sm wide" name="trx_type">
                        <option selected value="">@lang('All')</option>
                        <option value="+" @selected(request('trx_type') == '+')>@lang('Credits (+)')</option>
                        <option value="-" @selected(request('trx_type') == '-')>@lang('Debits (-)')</option>
                    </select>
                </div>
                <div class="col-xl-3 col-lg-3 col-sm-4">
                    <label class="form--label">@lang('Category')</label>
                    <select class="form--control form--control--sm form-select select-2" name="remark">
                        <option value="">@lang('Any category')</option>
                        @foreach($remarks as $remark)
                            <option value="{{ $remark->remark }}" @selected(request('remark') == $remark->remark)>
                                {{ __(keyToTitle($remark->remark)) }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-xl-2 col-lg-2">
                    <button type="submit" class="btn btn--base w-100">
                        <i class="ti ti-search"></i> @lang('Search')
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- ═══════════════════════════════════════
         DATA PANEL
    ═══════════════════════════════════════ --}}
    <div class="data-panel">
        <div class="data-panel__head">
            <div>
                <span class="eyebrow">@lang('Ledger')</span>
                <h5>@lang('Account activity')</h5>
            </div>
            <span class="result-count">{{ $transactions->total() }} @lang('records')</span>
        </div>

        <div class="table-responsive transaction-table">
            <table class="table table-borderless table--striped table--responsive--md">
                <thead>
                    <tr>
                        <th>@lang('#')</th>
                        <th>@lang('Transaction')</th>
                        <th>@lang('Date')</th>
                        <th>@lang('Amount')</th>
                        <th>@lang('Post Balance')</th>
                        <th>@lang('Details')</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($transactions as $transaction)
                        <tr>
                            <td>{{ $transactions->firstItem() + $loop->index }}</td>
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
                            <td>
                                <span title="{{ $transaction->details }}" style="font-size:13px">
                                    {{ __(strLimit($transaction->details, 35)) }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        @include('partials.noData')
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($transactions->hasPages())
            <div style="padding:16px 24px;">
                {{ paginateLinks($transactions) }}
            </div>
        @endif
    </div>

@endsection

@push('page-style-lib')
    <link rel="stylesheet" href="{{ asset('assets/universal/css/select2.min.css') }}">
@endpush

@push('page-script-lib')
    <script src="{{ asset('assets/universal/js/select2.min.js') }}"></script>
@endpush
