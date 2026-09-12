@extends("{$activeTheme}layouts.auth")

@section('auth')
    <div class="mb-xxl-4 mb-3">
        <div class="d-flex flex-wrap align-items-end justify-content-end gap-3">
            <form action="" method="get" class="d-flex flex-wrap gap-3 justify-content-center">
                <div class="input--group">
                    <input type="text" class="form--control form--control--sm" name="search" value="{{ request('search') }}" placeholder="@lang('TRX Number')">
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
                    <th>@lang('TRX') | @lang('Date')</th>
                    <th>@lang('Account')</th>
                    <th>@lang('Bank')</th>
                    <th>@lang('Amount')</th>
                    <th>@lang('Charge')</th>
                    <th>@lang('Paid Amount')</th>
                    <th>@lang('Status')</th>
                    <th>@lang('Action')</th>
                </tr>
            </thead>
            <tbody>
                @forelse($moneyTransfers as $moneyTransfer)
                    <tr>
                        <td>
                            <span>
                                <span class="d-block fw-semibold">{{ $moneyTransfer->trx }}</span>
                                <span class="d-block text--muted small">{{ showDateTime($moneyTransfer->created_at, 'M d, Y - h:i A') }}</span>
                            </span>
                        </td>
                        <td>
                            <span>
                                <span class="d-block text--base">{{ __($moneyTransfer->account_name ?? $moneyTransfer->beneficiary->details['account_name']) }}</span>
                                <span class="d-block text--muted small">{{ $moneyTransfer->account_number ?? $moneyTransfer->beneficiary->details['account_number'] }}</span>
                            </span>
                        </td>
                        <td>
                            @if(is_null($moneyTransfer->beneficiary_id))
                                <div>
                                    @lang('Wire Transfer')
                                    <button type="button" class="text--info btn-payload-info" data-bs-toggle="modal" data-bs-target="#wireTransferDetailsModal" data-wire_transfer_payload="{{ json_encode($moneyTransfer->wire_transfer_payload) }}" data-file_download_url="{{ route('user.money.transfer.file', [$moneyTransfer, 'data' => ':value']) }}">
                                        <i class="ti ti-info-circle"></i>
                                    </button>
                                </div>
                            @else
                                {{ __($moneyTransfer->bank_name) }}
                            @endif
                        </td>
                        <td>{{ $setting->cur_sym . showAmount($moneyTransfer->amount) }}</td>
                        <td>{{ $setting->cur_sym . showAmount($moneyTransfer->charge) }}</td>
                        <td>
                            @php $paidAmount = $moneyTransfer->amount + $moneyTransfer->charge @endphp

                            {{ $setting->cur_sym . showAmount($paidAmount) }}
                        </td>
                        <td>
                            @php echo $moneyTransfer->status_badge @endphp
                        </td>
                        <td>
                            <form action="{{ route('user.money.transfer.export', $moneyTransfer) }}" method="post">
                                @csrf
                                <button type="submit" @class(['btn btn--base btn--icon', 'disabled' => $moneyTransfer->status != ManageStatus::MONEY_TRANSFER_COMPLETED])>
                                    <i class="ti ti-download transform-0"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    @include('partials.noData')
                @endforelse
            </tbody>
        </table>
    </div>

    @if ($moneyTransfers->hasPages())
        {{ paginateLinks($moneyTransfers) }}
    @endif
@endsection

@push('user-panel-modal')
    <div class="custom--modal modal fade" id="wireTransferDetailsModal" tabindex="-1" aria-labelledby="wireTransferDetailsModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title" id="wireTransferDetailsModalLabel">@lang('Wire Transfer Info')</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <table class="table table-borderless no-shadow">
                        <tbody id="payloadInfo"></tbody>
                    </table>
                </div>
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

                $('.btn-payload-info').on('click', function () {
                    let data = $(this).data()
                    let payloadHtml = []

                    data.wire_transfer_payload.forEach(item => {
                        let name = item.name
                        let value = item.value
                        let type = item.type

                        if (type === 'checkbox') {
                            value = value.join(', ')
                        } else if (type === 'file') {
                            const fileDownloadUrl = decodeURIComponent(data.file_download_url).replace(':value', value)

                            value = `
                                <a href="${fileDownloadUrl}">
                                    <i class="ti ti-file-download"></i> @lang('Download')
                                </a>
                            `
                        }

                        if (type === 'textarea') {
                            payloadHtml.push(`
                                <tr>
                                    <td colspan="100%" class="text-start">
                                        <p class="fw-bold mb-1">${name}</p>
                                        <p>${value}</p>
                                    </td>
                                </tr>
                            `)
                        } else {
                            payloadHtml.push(`
                                <tr>
                                    <td><span class="fw-bold">${name}</span></td>
                                    <td>${value}</td>
                                </tr>
                            `)
                        }
                    })

                    $('#payloadInfo').html(payloadHtml.join(''))
                })
            })
        })(jQuery)
    </script>
@endpush
