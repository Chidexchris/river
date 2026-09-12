@extends('staff.layouts.master')

@section('master')
    @php
        $branch = $staff->branches->first();
        $depositsSumAmount = showAmount($branch->deposits_sum_amount ?? 0);
        $withdrawalsSumAmount = showAmount($branch->withdrawals_sum_amount ?? 0);
        $usersCount = $branch->users_count;
        $deposits = $branch->deposits;
        $withdrawals = $branch->withdrawals;
    @endphp

    <div class="col-12">
        <div class="row g-lg-4 g-3">
            <x-staff.dashboardCard
                icon="ti ti-map-pin"
                :value="$branch->name"
                :text="$branch->address"
            />
            <x-staff.dashboardCard
                class="dashboard-widget-1__success"
                icon="ti ti-wallet"
                :value="$depositsSumAmount"
                :text="trans('Total Deposits Made Today')"
            />
            <x-staff.dashboardCard
                class="dashboard-widget-1__warning"
                icon="ti ti-building-bank"
                :value="$withdrawalsSumAmount"
                :text="trans('Total Withdrawals Made Today')"
            />
            <x-staff.dashboardCard
                class="dashboard-widget-1__info"
                icon="ti ti-users"
                :value="$usersCount"
                :text="trans('Total Accounts Opened Today')"
            />
        </div>
    </div>
    <div class="col-xl-6">
        <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-3">
            <h5 class="mb-0">@lang('Latest Deposits')</h5>
            <a href="{{ route('staff.deposits.index') }}" class="btn btn--sm btn--base">
                @lang('View All')
            </a>
        </div>
        <div class="table-responsive scroll">
            <table class="table table--striped table-borderless table--responsive--sm">
                <thead>
                    <tr>
                        <th>@lang('TRX')</th>
                        <th>@lang('Account No.')</th>
                        <th>@lang('Initiated')</th>
                        <th>@lang('Amount')</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($deposits as $deposit)
                        <tr>
                            <td>{{ $deposit->trx }}</td>
                            <td>{{ $deposit->user->account_number }}</td>
                            <td>
                                <span>
                                    <span class="d-block">{{ showDateTime($deposit->created_at) }}</span>
                                    <span class="d-block">{{ diffForHumans($deposit->created_at) }}</span>
                                </span>
                            </td>
                            <td>{{ showAmount($deposit->amount) . ' ' . __($setting->site_cur) }}</td>
                        </tr>
                    @empty
                        @include('partials.noData')
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <div class="col-xl-6">
        <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-3">
            <h5 class="mb-0">@lang('Latest Withdrawals')</h5>
            <a href="{{ route('staff.withdrawals.index') }}" class="btn btn--sm btn--base">
                @lang('View All')
            </a>
        </div>
        <div class="table-responsive scroll">
            <table class="table table--striped table-borderless table--responsive--sm">
                <thead>
                    <tr>
                        <th>@lang('TRX')</th>
                        <th>@lang('Account No.')</th>
                        <th>@lang('Initiated')</th>
                        <th>@lang('Amount')</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($withdrawals as $withdrawal)
                        <tr>
                            <td>{{ $withdrawal->trx }}</td>
                            <td>{{ $withdrawal->user->account_number }}</td>
                            <td>
                                <span>
                                    <span class="d-block">{{ showDateTime($withdrawal->created_at) }}</span>
                                    <span class="d-block">{{ diffForHumans($withdrawal->created_at) }}</span>
                                </span>
                            </td>
                            <td>{{ showAmount($withdrawal->amount) . ' ' . __($setting->site_cur) }}</td>
                        </tr>
                    @empty
                        @include('partials.noData')
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection

@push('breadcrumb')
    <x-searchForm action="{{ route('staff.accounts.index') }}" placeholder="Account Number" />
@endpush
