@extends('admin.layouts.master')

@section('master')
    <div class="col-12">
        <div class="table-responsive scroll">
            <table class="table table--striped table-borderless table--responsive--sm">
                <thead>
                    <tr>
                        <th>@lang('DPS No.') | @lang('Plan')</th>
                        <th>@lang('User') | @lang('Account No.')</th>
                        <th>@lang('Installment Amount')</th>
                        <th>@lang('Installment')</th>
                        <th>@lang('Maturity Amount')</th>
                        <th>@lang('Installment Tracker')</th>
                        <th>@lang('Initiated')</th>
                        <th>@lang('Status')</th>

                        @can('view dps installments')
                            <th>@lang('Action')</th>
                        @endcan
                    </tr>
                </thead>
                <tbody>
                    @forelse ($dpsList as $dps)
                        <tr>
                            <td>
                                <div>
                                    <p class="fw-semibold text--base">{{ $dps->scheme_code }}</p>
                                    <p class="fw-semibold">{{ __($dps->plan_name) }}</p>
                                </div>
                            </td>
                            <td>
                                <div>
                                    <p class="fw-semibold text--base">{{ __($dps->user->fullname) }}</p>
                                    <p class="fw-semibold">{{ $dps->user->account_number }}</p>
                                </div>
                            </td>
                            <td>
                                <div>
                                    <p>{{ $setting->cur_sym . showAmount($dps->per_installment) }}</p>
                                    <p class="text--base">
                                        {{ trans('Per') . ' ' . $dps->installment_interval . ' ' . trans(\Illuminate\Support\Str::plural('Day', $dps->installment_interval)) }}
                                    </p>
                                </div>
                            </td>
                            <td>
                                <div>
                                    <p><span class="fw-semibold">@lang('Total'):</span> {{ $dps->total_installment }}</p>
                                    <p><span class="fw-semibold text--base">@lang('Given'):</span> {{ $dps->given_installment }}</p>
                                </div>
                            </td>
                            <td>
                                <div>
                                    <p>{{ $setting->cur_sym . showAmount($dps->maturity_amount) }}</p>
                                    <p class="text--base">
                                        {{ $setting->cur_sym . showAmount($dps->total_deposit_amount) . ' + ' . showAmount($dps->interest_rate) . '%' }}
                                    </p>
                                </div>
                            </td>
                            <td>
                                <div>
                                    <p><span class="fw-semibold">@lang('Next Date'):</span> {{ showDateTime($dps->next_installment_date, 'd M, Y') }}</p>
                                    <p><span class="fw-semibold">@lang('Due Count'):</span> {{ $dps->late_installments }}</p>
                                </div>
                            </td>
                            <td>
                                <div>
                                    <p>{{ showDateTime($dps->created_at) }}</p>
                                    <p>{{ diffForHumans($dps->created_at) }}</p>
                                </div>
                            </td>
                            <td>
                                @php echo $dps->status_badge @endphp
                            </td>

                            @can('view dps installments')
                                <td>
                                    <a href="{{ route('admin.dps.installments', $dps) }}" class="btn btn--icon btn--sm btn--base">
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

        @if ($dpsList->hasPages())
            {{ paginateLinks($dpsList) }}
        @endif
    </div>
@endsection

@push('breadcrumb')
    <x-searchForm placeholder="Search Here..." />
@endpush
