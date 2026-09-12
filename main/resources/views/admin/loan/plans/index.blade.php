@extends('admin.layouts.master')

@section('master')
    <div class="col-12">
        <div class="table-responsive scroll">
            <table class="table table--striped table-borderless table--responsive--sm">
                <thead>
                    <tr>
                        <th>@lang('Name')</th>
                        <th>@lang('Amount')</th>
                        <th>@lang('Installment Rate')</th>
                        <th>@lang('Installment Interval')</th>
                        <th>@lang('Total Installments')</th>
                        <th>@lang('Delay Duration')</th>
                        <th>@lang('Charge')</th>
                        <th>@lang('Status')</th>

                        @canany(['edit loan plan', 'change loan plan status'])
                            <th>@lang('Action')</th>
                        @endcanany
                    </tr>
                </thead>
                <tbody>
                    @forelse ($loanPlans as $loanPlan)
                        <tr>
                            <td>{{ __($loanPlan->name) }}</td>
                            <td>
                                <div>
                                    <p><span class="fw-bold">@lang('Min'):</span> {{ $setting->cur_sym . showAmount($loanPlan->minimum_amount) }}</p>
                                    <p><span class="fw-bold">@lang('Max'):</span> {{ $setting->cur_sym . showAmount($loanPlan->maximum_amount) }}</p>
                                </div>
                            </td>
                            <td>{{ showAmount($loanPlan->installment_rate) . '%' }}</td>
                            <td>{{ $loanPlan->installment_interval . ' ' . trans(\Illuminate\Support\Str::plural('Day', $loanPlan->installment_interval)) }}</td>
                            <td>{{ $loanPlan->total_installments }}</td>
                            <td>{{ $loanPlan->delay_duration . ' ' . trans(\Illuminate\Support\Str::plural('Day', $loanPlan->delay_duration)) }}</td>
                            <td>
                                <div>
                                    <p><span class="fw-bold">@lang('Fixed'):</span> {{ $setting->cur_sym . showAmount($loanPlan->fixed_charge) }}</p>
                                    <p><span class="fw-bold">@lang('Percentage'):</span> {{ showAmount($loanPlan->percentage_charge) . '%' }}</p>
                                </div>
                            </td>
                            <td>
                                @php echo $loanPlan->status_badge @endphp
                            </td>

                            @canany(['edit loan plan', 'change loan plan status'])
                                <td>
                                    <div class="custom--dropdown">
                                        <button type="button" class="btn btn--icon btn--sm btn--base" data-bs-toggle="dropdown" aria-expanded="false">
                                            <i class="ti ti-dots-vertical"></i>
                                        </button>
                                        <ul class="dropdown-menu">
                                            @can('edit loan plan')
                                                <li>
                                                    <a href="{{ route('admin.loan.plans.edit', ['plan' => $loanPlan]) }}" class="dropdown-item">
                                                        <span class="dropdown-icon"><i class="ti ti-edit text--base"></i></span> @lang('Edit Plan')
                                                    </a>
                                                </li>
                                            @endcan

                                            @can('change loan plan status')
                                                @if($loanPlan->status)
                                                    <li>
                                                        <button type="button" class="dropdown-item decisionBtn" data-action="{{ route('admin.loan.plans.status', $loanPlan->id) }}" data-question="@lang('Are you sure you want to inactive this plan?')">
                                                            <span class="dropdown-icon"><i class="ti ti-circle-x text--warning"></i></span> @lang('Inactive Plan')
                                                        </button>
                                                    </li>
                                                @else
                                                    <li>
                                                        <button type="button" class="dropdown-item decisionBtn" data-action="{{ route('admin.loan.plans.status', $loanPlan->id) }}" data-question="@lang('Are you sure you want to active this plan?')">
                                                            <span class="dropdown-icon"><i class="ti ti-circle-check text--success"></i></span> @lang('Active Plan')
                                                        </button>
                                                    </li>
                                                @endif
                                            @endcan
                                        </ul>
                                    </div>
                                </td>
                            @endcanany
                        </tr>
                    @empty
                        @include('partials.noData')
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($loanPlans->hasPages())
            {{ paginateLinks($loanPlans) }}
        @endif
    </div>

    <x-decisionModal />
@endsection

@push('breadcrumb')
    <x-searchForm placeholder="Name" />

    @can('create loan plan')
        <a href="{{ route('admin.loan.plans.create') }}" class="btn btn--sm btn--base d-flex align-items-center gap-1">
            <i class="ti ti-circle-plus transform-0"></i> @lang('Create New')
        </a>
    @endcan
@endpush
