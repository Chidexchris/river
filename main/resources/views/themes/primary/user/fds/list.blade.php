@extends("{$activeTheme}layouts.auth")

@section('auth')
    <div class="mb-xxl-4 mb-3">
        <div class="d-flex flex-wrap align-items-end justify-content-end gap-3">
            <form action="" method="get" class="d-flex flex-wrap gap-3 justify-content-center">
                <div class="input--group">
                    <input type="text" class="form--control form--control--sm" name="search" value="{{ request('search') }}" placeholder="@lang('Plan/FDS Number')">
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
                    <th>@lang('FDS No.') | @lang('Plan')</th>
                    <th>@lang('Deposited Amount')</th>
                    <th>@lang('Profit')</th>
                    <th>@lang('Initiated')</th>
                    <th>@lang('Next Installment')</th>
                    <th>@lang('Locked Until')</th>
                    <th>@lang('Status')</th>
                    <th>@lang('Action')</th>
                </tr>
            </thead>
            <tbody>
                @forelse($fdsList as $fds)
                    <tr>
                        <td>{{ $fdsList->firstItem() + $loop->index }}</td>
                        <td>
                            <span>
                                <span class="d-block">{{ $fds->scheme_code }}</span>
                                <span class="d-block text--base small">{{ __($fds->plan_name) }}</span>
                            </span>
                        </td>
                        <td>{{ $setting->cur_sym . showAmount($fds->deposit_amount) }}</td>
                        <td>
                            <span>
                                <span class="d-block">{{ $setting->cur_sym . showAmount($fds->per_installment) }}</span>
                                <span class="d-block text--base small">{{ trans('Per') . ' ' . $fds->interest_payout_interval . ' ' . trans(\Illuminate\Support\Str::plural('Day', $fds->interest_payout_interval)) . ' at ' . showAmount($fds->interest_rate) . '%' }}</span>
                            </span>
                        </td>
                        <td>{{ showDateTime($fds->created_at, 'd M, Y') }}</td>
                        <td>{{ showDateTime($fds->next_installment_date, 'd M, Y') }}</td>
                        <td>{{ showDateTime($fds->locked_until, 'd M, Y') }}</td>
                        <td>
                            @php echo $fds->status_badge @endphp
                        </td>
                        <td>
                            <div class="d-flex justify-content-end gap-2">
                                <a href="{{ route('user.fds.installments', $fds) }}" class="btn btn-outline--base btn--icon">
                                    <i class="ti ti-calendar-dollar transform-0"></i>
                                </a>

                                @if($fds->locked_until->endOfDay()->lt(now()) && $fds->status == ManageStatus::FDS_RUNNING)
                                    <button type="button" class="btn btn--base btn--icon btn-fds-close" data-bs-toggle="modal" data-bs-target="#closeModal" data-url="{{ route('user.fds.close', $fds) }}">
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

    @if ($fdsList->hasPages())
        {{ paginateLinks($fdsList) }}
    @endif
@endsection

@push('user-panel-modal')
    <div class="custom--modal modal fade" id="closeModal" tabindex="-1" aria-labelledby="closeModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="" method="post">
                    @csrf
                    <div class="modal-header">
                        <h1 class="modal-title" id="closeModalLabel">@lang('Close FDS')</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p class="px-1">
                            @lang('Are you sure you want to close this FDS? The profit amount will be added to your main balance.')
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

                $('.btn-fds-close').on('click', function () {
                    $('#closeModal').find('form').attr('action', $(this).data('url'))
                })
            })
        })(jQuery)
    </script>
@endpush
