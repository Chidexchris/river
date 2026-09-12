@extends('staff.layouts.master')

@section('master')
    @php
        $branch = session()->has('branchId')
            ? $staff->branches->find(session('branchId'))
            : $staff->branches->first();

        $depositsSumAmount = showAmount($branch->deposits_sum_amount ?? 0);
        $withdrawalsSumAmount = showAmount($branch->withdrawals_sum_amount ?? 0);
        $usersCount = $branch->users_count;
        $transactions = $branch->transactions;
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
    <div class="col-12">
        <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-3">
            <h5 class="mb-0">@lang('Latest Transactions')</h5>
            <a href="{{ route('staff.transactions') }}" class="btn btn--sm btn--base">
                @lang('View All')
            </a>
        </div>

        @include('staff.partials.transactionTable')
    </div>
@endsection
