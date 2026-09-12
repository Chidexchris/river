@extends("{$activeTheme}layouts.auth")

@section('auth')
    <div class="mb-xxl-4 mb-3">
        <div class="d-flex flex-wrap align-items-end justify-content-end gap-3">
            <form action="" method="get" class="d-flex flex-wrap gap-3 justify-content-center">
                <div class="input--group">
                    <input type="text" class="form--control form--control--sm" name="search" value="{{ request('search') }}" placeholder="@lang('Plan/DPS Number')">
                    <button type="submit" class="btn btn--sm btn--base px-3">
                        <i class="ti ti-search"></i>
                    </button>
                </div>
                <div class="input--group">
                    <input type="text" class="form--control form--control--sm date-picker" name="date" value="{{ request('date') }}" data-range="true" data-multiple-dates-separator=" - " data-language="en" placeholder="@lang('Start Date - End Date')" autocomplete="off">
                    <button type="submit" class="btn btn--sm btn--base px-3">
                        <i class="ti ti-search"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>
    <div class="table-responsive">
        <table class="table table--striped table-borderless table--responsive--md">
            <thead>
                <tr>
                    <th>@lang('S.N.')</th>
                    <th>@lang('DPS No.') | @lang('Plan')</th>
                    <th>@lang('Installment Amount')</th>
                    <th>@lang('Installment')</th>
                    <th>@lang('Initiated')</th>
                    <th>@lang('Next Installment')</th>
                    <th>@lang('Maturity Amount')</th>
                    <th>@lang('Status')</th>
                    <th>@lang('Action')</th>
                </tr>
            </thead>
            <tbody>
                @forelse($dpsList as $dps)
                    <tr>
                        <td>{{ $dpsList->firstItem() + $loop->index }}</td>
                        <td>
                            <span>
                                <span class="d-block">{{ $dps->scheme_code }}</span>
                                <span class="d-block text--base small">{{ __($dps->plan_name) }}</span>
                            </span>
                        </td>
                        <td>
                            <span>
                                <span class="d-block">{{ $setting->cur_sym . showAmount($dps->per_installment) }}</span>
                                <span class="d-block text--base small">{{ trans('Per') . ' ' . $dps->installment_interval . ' ' . trans(\Illuminate\Support\Str::plural('Day', $dps->installment_interval)) }}</span>
                            </span>
                        </td>
                        <td>
                            <span>
                                <span class="d-block">@lang('Total'): {{ $dps->total_installment }}</span>
                                <span class="d-block text--base small">@lang('Given'): {{ $dps->given_installment }}</span>
                            </span>
                        </td>
                        <td>{{ showDateTime($dps->created_at, 'd M, Y') }}</td>
                        <td>{{ showDateTime($dps->next_installment_date, 'd M, Y') }}</td>
                        <td>
                            <span>
                                <span class="d-block">{{ $setting->cur_sym . showAmount($dps->maturity_amount) }}</span>
                                <span class="d-block text--base small">{{ $setting->cur_sym . showAmount($dps->total_deposit_amount) . ' + ' . showAmount($dps->interest_rate) . '%' }}</span>
                            </span>
                        </td>
                        <td>
                            @php echo $dps->status_badge @endphp
                        </td>
                        <td>
                            <div class="d-flex justify-content-end gap-2">
                                <a href="{{ route('user.dps.installments', $dps) }}" class="btn btn-outline--base btn--icon">
                                    <i class="ti ti-calendar-dollar transform-0"></i>
                                </a>

                                @if($dps->status == ManageStatus::DPS_MATURED)
                                    <button type="button" class="btn btn--base btn--icon btn-dps-close" data-bs-toggle="modal" data-bs-target="#closeModal" data-url="{{ route('user.dps.close', $dps) }}">
                                        <i class="ti ti-circle-x transform-0"></i>
                                    </button>
                                @endif
                            </div>
                        </td>
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
@endsection

@push('user-panel-modal')
    <div class="custom--modal modal fade" id="closeModal" tabindex="-1" aria-labelledby="closeModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="" method="post">
                    @csrf
                    <div class="modal-header">
                        <h1 class="modal-title" id="closeModalLabel">@lang('Close DPS')</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p class="px-1">
                            @lang('Are you sure you want to close this DPS? The maturity amount will be added to your main balance.')
                        </p>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn--sm btn--base">
                            @lang('Confirm')
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endpush

@push('page-style-lib')
    <link rel="stylesheet" href="{{ asset('assets/universal/css/datepicker.css') }}">
@endpush

@push('page-script-lib')
    <script src="{{ asset('assets/universal/js/datepicker.js') }}"></script>
    <script src="{{ asset('assets/universal/js/datepicker.en.js') }}"></script>
@endpush

@push('page-script')
    <script>
        (function ($) {
            'use strict'

            $(function () {
                let datePicker = $('.date-picker')

                datePicker.on('input keyup keydown keypress', function () {
                    return false
                })

                datePicker.datepicker()

                $('.btn-dps-close').on('click', function () {
                    $('#closeModal').find('form').attr('action', $(this).data('url'))
                })
            })
        })(jQuery)
    </script>
@endpush
