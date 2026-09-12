@extends("{$activeTheme}layouts.auth")

@section('auth')

    {{-- ═══════════════════════════════════════════════
         WELCOME BANNER
    ═══════════════════════════════════════════════ --}}
    <div class="dashboard-welcome">
        <div>
            <span class="eyebrow">@lang('Private Banking')</span>
            <h1>{{ __('Good to see you, :name', ['name' => $user->firstname]) }} 👋</h1>
            <p>@lang("Here's a complete view of your account today.")</p>
        </div>
        <div class="dashboard-welcome__meta">
            <span class="status-dot"></span>@lang('Account secure')
        </div>
    </div>

    {{-- ═══════════════════════════════════════════════
         KYC ALERT
    ═══════════════════════════════════════════════ --}}
    @if($user->kc == ManageStatus::UNVERIFIED || $user->kc == ManageStatus::PENDING)
        <div class="alert alert--warning premium-alert" role="alert">
            @if($user->kc == ManageStatus::UNVERIFIED)
                <span class="alert__title"><i class="ti ti-shield-exclamation"></i> {{ __($kycContent?->data_info->verification_required_heading) }}</span>
                <p class="alert__desc">{{ __($kycContent?->data_info->verification_required_details) }} <a href="{{ route('user.kyc.form') }}" class="alert__link">@lang('Complete verification →')</a></p>
            @else
                <span class="alert__title"><i class="ti ti-clock"></i> {{ __($kycContent?->data_info->verification_pending_heading) }}</span>
                <p class="alert__desc">{{ __($kycContent?->data_info->verification_pending_details) }} <a href="{{ route('user.kyc.data') }}" class="alert__link">@lang('View verification →')</a></p>
            @endif
        </div>
    @endif

    {{-- ═══════════════════════════════════════════════
         BALANCE HERO + BANK CARD
    ═══════════════════════════════════════════════ --}}
    <section class="balance-hero" aria-label="@lang('Account balance overview')">
        <div class="balance-hero__main">
            <span class="balance-hero__label">@lang('Available Balance')</span>
            <div class="balance-hero__amount">{{ $setting->cur_sym }}{{ showAmount($user->balance) }}</div>
            <div class="balance-hero__account">
                <span>@lang('Primary account')</span>
                <strong>{{ $user->account_number }}</strong>
            </div>
        </div>

        <div class="riverwind-card" aria-label="@lang('Primary RiverWind Bank card')">
            <div class="riverwind-card__top">
                <span>RiverWind <span>BANK</span></span>
                <i class="ti ti-wifi"></i>
            </div>
            <div class="riverwind-card__chip"></div>
            <div class="riverwind-card__number">{{ substr($user->account_number, 0, 4) }} •••• •••• {{ substr($user->account_number, -4) }}</div>
            <div class="riverwind-card__bottom">
                <span>
                    <small>@lang('ACCOUNT HOLDER')</small>
                    {{ $user->firstname }} {{ $user->lastname }}
                </span>
                <strong>VISA</strong>
            </div>
        </div>
    </section>

    {{-- ═══════════════════════════════════════════════
         QUICK ACTIONS
    ═══════════════════════════════════════════════ --}}
    <section class="quick-actions" aria-label="@lang('Quick actions')">
        <div class="section-heading">
            <div>
                <span class="eyebrow">@lang('Shortcuts')</span>
                <h2>@lang('Move money')</h2>
            </div>
        </div>
        <div class="quick-actions__grid">
            @if($setting->internal_bank_transfer)
                <a href="{{ route('user.money.transfer.within.bank') }}" class="quick-action">
                    <i class="ti ti-send"></i>
                    <span>@lang('Send')</span>
                </a>
            @endif
            @if($setting->external_bank_transfer)
                <a href="{{ route('user.money.transfer.other.bank') }}" class="quick-action">
                    <i class="ti ti-building-bank"></i>
                    <span>@lang('Transfer')</span>
                </a>
            @endif
            @if($setting->deposit)
                <a href="{{ route('user.deposit') }}" class="quick-action">
                    <i class="ti ti-arrow-down-left"></i>
                    <span>@lang('Deposit')</span>
                </a>
            @endif
            @if($setting->withdraw)
                <a href="{{ route('user.withdraw') }}" class="quick-action">
                    <i class="ti ti-arrow-up-right"></i>
                    <span>@lang('Withdraw')</span>
                </a>
            @endif
            @if($setting->loan)
                <a href="{{ route('user.loan.plans') }}" class="quick-action">
                    <i class="ti ti-cash"></i>
                    <span>@lang('Loan')</span>
                </a>
            @endif
            <a href="{{ route('user.account.statement') }}" class="quick-action">
                <i class="ti ti-file-invoice"></i>
                <span>@lang('Statement')</span>
            </a>
        </div>
    </section>

    {{-- ═══════════════════════════════════════════════
         PORTFOLIO + INSIGHT PANEL
    ═══════════════════════════════════════════════ --}}
    <div class="dashboard-grid">
        <section class="account-summary" aria-label="@lang('Your products')">
            <div class="section-heading">
                <div>
                    <span class="eyebrow">@lang('Portfolio')</span>
                    <h2>@lang('Your products')</h2>
                </div>
                <a href="{{ route('user.transactions') }}">@lang('All activity') <i class="ti ti-arrow-right"></i></a>
            </div>
            <div class="product-list">
                <a href="{{ route('user.deposit.history') }}" class="product-row">
                    <span class="product-row__icon is-success"><i class="ti ti-arrow-down-left"></i></span>
                    <span>
                        <small>@lang('Total deposited')</small>
                        <strong>{{ $setting->cur_sym }}{{ showAmount($depositAmount) }}</strong>
                    </span>
                    <i class="ti ti-chevron-right"></i>
                </a>
                <a href="{{ route('user.withdraw.history') }}" class="product-row">
                    <span class="product-row__icon is-warning"><i class="ti ti-arrow-up-right"></i></span>
                    <span>
                        <small>@lang('Total withdrawn')</small>
                        <strong>{{ $setting->cur_sym }}{{ showAmount($withdrawalAmount) }}</strong>
                    </span>
                    <i class="ti ti-chevron-right"></i>
                </a>
                @if($setting->dps)
                    <a href="{{ route('user.dps.list') }}" class="product-row">
                        <span class="product-row__icon"><i class="ti ti-pig-money"></i></span>
                        <span>
                            <small>@lang('Savings plans (DPS)')</small>
                            <strong>{{ $user->deposit_pension_schemes_count }} @lang('active')</strong>
                        </span>
                        <i class="ti ti-chevron-right"></i>
                    </a>
                @endif
                @if($setting->fds)
                    <a href="{{ route('user.fds.list') }}" class="product-row">
                        <span class="product-row__icon" style="background:rgba(59,130,246,0.12)"><i class="ti ti-receipt-2" style="color:#3B82F6"></i></span>
                        <span>
                            <small>@lang('Fixed deposits (FDS)')</small>
                            <strong>{{ $user->fixed_deposit_schemes_count }} @lang('active')</strong>
                        </span>
                        <i class="ti ti-chevron-right"></i>
                    </a>
                @endif
                @if($setting->loan)
                    <a href="{{ route('user.loan.list') }}" class="product-row">
                        <span class="product-row__icon is-danger"><i class="ti ti-cash"></i></span>
                        <span>
                            <small>@lang('Active loans')</small>
                            <strong>{{ $user->loans_count }} @lang('active')</strong>
                        </span>
                        <i class="ti ti-chevron-right"></i>
                    </a>
                @endif
            </div>
        </section>

        <aside class="insight-panel" aria-label="@lang('Financial insight')">
            <span class="eyebrow">@lang('Financial insight')</span>
            <h2>@lang('Account activity')</h2>
            <p>@lang('Monitor your deposits and withdrawals to stay in control of your cash flow.')</p>
            <div class="insight-panel__metrics">
                <div>
                    <span>@lang('Money in')</span>
                    <strong class="text--success">{{ $setting->cur_sym }}{{ showAmount($depositAmount) }}</strong>
                </div>
                <div>
                    <span>@lang('Money out')</span>
                    <strong>{{ $setting->cur_sym }}{{ showAmount($withdrawalAmount) }}</strong>
                </div>
            </div>
        </aside>
    </div>

    {{-- ═══════════════════════════════════════════════
         CASHFLOW CHART
    ═══════════════════════════════════════════════ --}}
    <section class="cashflow-section" aria-label="@lang('Cash flow chart')">
        <div class="section-heading">
            <div>
                <span class="eyebrow">@lang('Financial overview')</span>
                <h2>@lang('Cash flow at a glance')</h2>
            </div>
            <span class="chart-key">
                <i style="background:var(--pb-green)"></i>@lang('Deposits')
                <i style="background:var(--pb-amber)"></i>@lang('Withdrawals')
            </span>
        </div>
        @if($cashFlow->sum('deposits') > 0 || $cashFlow->sum('withdrawals') > 0)
            <div class="cashflow-chart" role="img" aria-label="@lang('Your deposits and withdrawals from the last four weeks')">
                <div class="chart-y">
                    <span>{{ $setting->cur_sym }}{{ showAmount($cashFlowMax) }}</span>
                    <span>{{ $setting->cur_sym }}{{ showAmount($cashFlowMax / 2) }}</span>
                    <span>0</span>
                </div>
                <div class="chart-bars">
                    @foreach($cashFlow as $period)
                        <div class="chart-group" title="{{ $period['label'] }}: {{ __('Deposits') }} {{ $setting->cur_sym . showAmount($period['deposits']) }}, {{ __('Withdrawals') }} {{ $setting->cur_sym . showAmount($period['withdrawals']) }}">
                            <span class="bar bar--in" style="height: {{ ($period['deposits'] / $cashFlowMax) * 100 }}%"></span>
                            <span class="bar bar--out" style="height: {{ ($period['withdrawals'] / $cashFlowMax) * 100 }}%"></span>
                            <small>{{ $period['label'] }}</small>
                        </div>
                    @endforeach
                </div>
                <div class="chart-summary">
                    <small>@lang('Total received')</small>
                    <strong class="text--success">{{ $setting->cur_sym }}{{ showAmount($cashFlow->sum('deposits')) }}</strong>
                    <small>@lang('Total sent')</small>
                    <strong>{{ $setting->cur_sym }}{{ showAmount($cashFlow->sum('withdrawals')) }}</strong>
                </div>
            </div>
        @else
            <div class="cashflow-empty">
                <i class="ti ti-chart-bar"></i>
                <strong>@lang('No cash-flow activity yet')</strong>
                <span>@lang('Completed deposits and withdrawals from the last four weeks will appear here.')</span>
            </div>
        @endif
    </section>

    {{-- ═══════════════════════════════════════════════
         RECENT ACTIVITY
    ═══════════════════════════════════════════════ --}}
    <section class="activity-section" aria-label="@lang('Recent account activity')">
        <div class="section-heading">
            <div>
                <span class="eyebrow">@lang('Latest activity')</span>
                <h2>@lang('Recent movements')</h2>
            </div>
            <a href="{{ route('user.transactions') }}">@lang('View all') <i class="ti ti-arrow-right"></i></a>
        </div>
        <div class="row g-4">
            <div class="col-xl-6">
                <div class="activity-card">
                    <div class="activity-card__head">
                        <h3>@lang('Recent deposits')</h3>
                        <i class="ti ti-arrow-down-left text--success"></i>
                    </div>
                    <table class="table table--responsive--md">
                        <tbody>
                            @forelse($recentDeposits as $deposit)
                                <tr>
                                    <td>
                                        <strong>{{ $deposit->trx }}</strong>
                                        <small>{{ showDateTime($deposit->created_at, 'd M, Y') }}</small>
                                    </td>
                                    <td class="text-end text--success">+{{ showAmount($deposit->amount) }} {{ $setting->site_cur }}</td>
                                </tr>
                            @empty
                                @include('partials.noData')
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="col-xl-6">
                <div class="activity-card">
                    <div class="activity-card__head">
                        <h3>@lang('Recent withdrawals')</h3>
                        <i class="ti ti-arrow-up-right text--warning"></i>
                    </div>
                    <table class="table table--responsive--md">
                        <tbody>
                            @forelse($recentWithdrawals as $withdrawal)
                                <tr>
                                    <td>
                                        <strong>{{ $withdrawal->trx }}</strong>
                                        <small>{{ showDateTime($withdrawal->created_at, 'd M, Y') }}</small>
                                    </td>
                                    <td class="text-end text--warning">-{{ showAmount($withdrawal->amount) }} {{ $setting->site_cur }}</td>
                                </tr>
                            @empty
                                @include('partials.noData')
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>

@endsection
