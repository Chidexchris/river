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
                    @forelse ($deposits as $deposit)
                        <tr>
                            <td>{{ $deposit->trx }}</td>
                            <td>{{ $deposit->user->account_number }}</td>
                            <td>{{ __($deposit->user->fullname) }}</td>

                            @if(isManager())
                                <td>{{ __($deposit->staff->name) }}</td>
                            @endif

                            <td>
                                <span>
                                    <span class="d-block">{{ showDateTime($deposit->created_at) }}</span>
                                    <span class="d-block">{{ diffForHumans($deposit->created_at) }}</span>
                                </span>
                            </td>
                            <td>{{ $setting->cur_sym . showAmount($deposit->amount) }}</td>
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
@endsection

@push('breadcrumb')
    <x-searchForm placeholder="Search Here..." dateSearch="yes" />
@endpush
