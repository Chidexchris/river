@extends('admin.layouts.master')

@section('master')
    <div class="col-12">
        <div class="table-responsive scroll">
            <table class="table table--striped table-borderless table--responsive--sm">
                <thead>
                    <tr>
                        <th>@lang('Name') | @lang('Processing Time')</th>
                        <th>@lang('Per Transaction')</th>
                        <th>@lang('Daily Transaction')</th>
                        <th>@lang('Monthly Transaction')</th>
                        <th>@lang('Charge')</th>
                        <th>@lang('Added On')</th>
                        <th>@lang('Status')</th>

                        @canany(['edit other bank', 'change other bank status'])
                            <th>@lang('Action')</th>
                        @endcanany
                    </tr>
                </thead>
                <tbody>
                    @forelse ($otherBanks as $otherBank)
                        <tr>
                            <td>
                                <div>
                                    <p class="fw-semibold text--base">{{ __($otherBank->name) }}</p>
                                    <p>{{ __($otherBank->processing_time) }}</p>
                                </div>
                            </td>
                            <td>
                                <div>
                                    <p><span class="fw-semibold">@lang('Min Amount'):</span> {{ $setting->cur_sym . showAmount($otherBank->per_transaction_min_amount) }}</p>
                                    <p><span class="fw-semibold">@lang('Max Amount'):</span> {{ $setting->cur_sym . showAmount($otherBank->per_transaction_max_amount) }}</p>
                                </div>
                            </td>
                            <td>
                                <div>
                                    <p><span class="fw-semibold">@lang('Max Amount'):</span> {{ $setting->cur_sym . showAmount($otherBank->daily_transaction_max_amount) }}</p>
                                    <p><span class="fw-semibold">@lang('Limit'):</span> {{ $otherBank->daily_transaction_limit . ' ' . trans('Times') }}</p>
                                </div>
                            </td>
                            <td>
                                <div>
                                    <p><span class="fw-semibold">@lang('Max Amount'):</span> {{ $setting->cur_sym . showAmount($otherBank->monthly_transaction_max_amount) }}</p>
                                    <p><span class="fw-semibold">@lang('Limit'):</span> {{ $otherBank->monthly_transaction_limit . ' ' . trans('Times') }}</p>
                                </div>
                            </td>
                            <td>
                                <div>
                                    <p><span class="fw-semibold">@lang('Fixed'):</span> {{ $setting->cur_sym . showAmount($otherBank->fixed_charge) }}</p>
                                    <p><span class="fw-semibold">@lang('Percentage'):</span> {{ showAmount($otherBank->percentage_charge) . '%' }}</p>
                                </div>
                            </td>
                            <td>
                                <div>
                                    <p>{{ showDateTime($otherBank->created_at) }}</p>
                                    <p>{{ diffForHumans($otherBank->created_at) }}</p>
                                </div>
                            </td>
                            <td>
                                @php echo $otherBank->status_badge @endphp
                            </td>

                            @canany(['edit other bank', 'change other bank status'])
                                <td>
                                    <div class="custom--dropdown">
                                        <button type="button" class="btn btn--icon btn--sm btn--base" data-bs-toggle="dropdown" aria-expanded="false">
                                            <i class="ti ti-dots-vertical"></i>
                                        </button>
                                        <ul class="dropdown-menu">
                                            @can('edit other bank')
                                                <li>
                                                    <a href="{{ route('admin.other.banks.edit', $otherBank) }}" class="dropdown-item">
                                                        <span class="dropdown-icon"><i class="ti ti-edit text--base"></i></span> @lang('Edit Bank')
                                                    </a>
                                                </li>
                                            @endcan

                                            @can('change other bank status')
                                                @if($otherBank->status)
                                                    <li>
                                                        <button type="button" class="dropdown-item decisionBtn" data-action="{{ route('admin.other.banks.status', $otherBank->id) }}" data-question="@lang('Are you sure you want to inactive this bank?')">
                                                            <span class="dropdown-icon"><i class="ti ti-circle-x text--warning"></i></span> @lang('Inactive Bank')
                                                        </button>
                                                    </li>
                                                @else
                                                    <li>
                                                        <button type="button" class="dropdown-item decisionBtn" data-action="{{ route('admin.other.banks.status', $otherBank->id) }}" data-question="@lang('Are you sure you want to active this bank?')">
                                                            <span class="dropdown-icon"><i class="ti ti-circle-check text--success"></i></span> @lang('Active Bank')
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

        @if ($otherBanks->hasPages())
            {{ paginateLinks($otherBanks) }}
        @endif
    </div>

    <x-decisionModal />
@endsection

@push('breadcrumb')
    <x-searchForm placeholder="Name" />

    @can('add other bank')
        <a href="{{ route('admin.other.banks.create') }}" class="btn btn--sm btn--base d-flex align-items-center gap-1">
            <i class="ti ti-circle-plus transform-0"></i> @lang('Add New')
        </a>
    @endcan
@endpush
