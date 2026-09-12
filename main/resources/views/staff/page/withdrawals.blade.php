@extends('staff.layouts.master')

@section('master')
    <div class="col-12">
        <div class="table-responsive scroll">
            <table class="table table--striped table-borderless table--responsive--sm">
                <thead>
                    <tr>
                        <th>@lang('TRX')</th>
                        <th>@lang('Account Number')</th>
                        <th>@lang('Account Name')</th>

                        @if(isManager())
                            <th>@lang('Account Officer')</th>
                        @endif

                        <th>@lang('Initiated')</th>
                        <th>@lang('Amount')</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($withdrawals as $withdrawal)
                        <tr>
                            <td>{{ $withdrawal->trx }}</td>
                            <td>{{ $withdrawal->user->account_number }}</td>
                            <td>{{ __($withdrawal->user->fullname) }}</td>

                            @if(isManager())
                                <td>{{ __($withdrawal->staff->name) }}</td>
                            @endif

                            <td>
                                <span>
                                    <span class="d-block">{{ showDateTime($withdrawal->created_at) }}</span>
                                    <span class="d-block">{{ diffForHumans($withdrawal->created_at) }}</span>
                                </span>
                            </td>
                            <td>{{ $setting->cur_sym . showAmount($withdrawal->amount) }}</td>
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
@endsection

@push('breadcrumb')
    <x-searchForm placeholder="Search Here..." dateSearch="yes" />
@endpush
