@extends('admin.layouts.master')

@section('master')
    <div class="col-12">
        <div class="table-responsive scroll">
            <table class="table table--striped table-borderless table--responsive--sm">
                <thead>
                    <tr>
                        <th>@lang('FDS No.') | @lang('Plan')</th>
                        <th>@lang('User') | @lang('Account No.')</th>
                        <th>@lang('Interest Rate')</th>
                        <th>@lang('Deposit Amount')</th>
                        <th>@lang('Profit')</th>
                        <th>@lang('Initiated')</th>
                        <th>@lang('Next Installment Date')</th>
                        <th>@lang('Locked Until')</th>
                        <th>@lang('Status')</th>

                        @can('view fds installments')
                            <th>@lang('Action')</th>
                        @endcan
                    </tr>
                </thead>
                <tbody>
                    @forelse ($fdsList as $fds)
                        <tr>
                            <td>
                                <div>
                                    <p class="fw-semibold text--base">{{ $fds->scheme_code }}</p>
                                    <p class="fw-semibold">{{ __($fds->plan_name) }}</p>
                                </div>
                            </td>
                            <td>
                                <div>
                                    <p class="fw-semibold text--base">{{ __($fds->user->fullname) }}</p>
                                    <p class="fw-semibold">{{ $fds->user->account_number }}</p>
                                </div>
                            </td>
                            <td>{{ showAmount($fds->interest_rate) . '%' }}</td>
                            <td>{{ $setting->cur_sym . showAmount($fds->deposit_amount) }}</td>
                            <td>
                                <div>
                                    <p>{{ $setting->cur_sym . showAmount($fds->per_installment) }}</p>
                                    <p class="text--base">
                                        {{ trans('Per') . ' ' . $fds->interest_payout_interval . ' ' . trans(\Illuminate\Support\Str::plural('Day', $fds->interest_payout_interval)) }}
                                    </p>
                                </div>
                            </td>
                            <td>
                                <div>
                                    <p>{{ showDateTime($fds->created_at) }}</p>
                                    <p>{{ diffForHumans($fds->created_at) }}</p>
                                </div>
                            </td>
                            <td>{{ showDateTime($fds->next_installment_date, 'd M, Y') }}</td>
                            <td>{{ showDateTime($fds->locked_until, 'd M, Y') }}</td>
                            <td>
                                @php echo $fds->status_badge @endphp
                            </td>

                            @can('view fds installments')
                                <td>
                                    <a href="{{ route('admin.fds.installments', $fds) }}" class="btn btn--icon btn--sm btn--base">
                                        <i class="ti ti-calendar-dollar"></i>
                                    </a>
                                </td>
                            @endcan
                        </tr>
                    @empty
                        @include('partials.noData')
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($fdsList->hasPages())
            {{ paginateLinks($fdsList) }}
        @endif
    </div>
@endsection

@push('breadcrumb')
    <x-searchForm placeholder="Search Here..." />
@endpush
