@extends("{$activeTheme}layouts.auth")

@section('auth')
    <div class="mb-xxl-4 mb-3">
        <form action="" method="get" class="d-flex flex-wrap align-items-end justify-content-end gap-3">
            <div class="input--group">
                <input type="text" class="form--control form--control--sm" name="search" placeholder="@lang('Transaction Number')" value="{{ request('search') }}">
                <button type="submit" class="btn btn--sm btn--base px-3">
                    <i class="ti ti-search"></i>
                </button>
            </div>
            <a href="{{ route('user.deposit') }}" class="btn btn--sm btn--base">
                @lang('Deposit Money')
            </a>
        </form>
    </div>
    <div class="table-responsive">
        <table class="table table--striped table-borderless table--responsive--md">
            <thead>
                <tr>
                    <th>@lang('S.N.')</th>
                    <th>@lang('Gateway') | @lang('Transaction')</th>
                    <th>@lang('Initiated')</th>
                    <th>@lang('Amount')</th>
                    <th>@lang('Conversion')</th>
                    <th>@lang('Status')</th>
                    <th>@lang('Details')</th>
                </tr>
            </thead>
            <tbody>
                @forelse($deposits as $deposit)
                    <tr>
                        <td>{{ $deposits->firstItem() + $loop->index }}</td>
                        <td>
                            <span>
                                <span class="d-block text--base fw-semibold">{{ __($deposit->gateway->name) }}</span>
                                <span class="d-block" title="@lang('Transaction Number')">{{ $deposit->trx }}</span>
                            </span>
                        </td>
                        <td>
                            <span>
                                <span class="d-block">{{ showDateTime($deposit->created_at) }}</span>
                                <span class="d-block">{{ diffForHumans($deposit->created_at) }}</span>
                            </span>
                        </td>
                        <td>
                            <span>
                                <span class="d-block">{{ $setting->cur_sym . showAmount($deposit->amount) }} + <span class="text--danger" title="@lang('Charge')">{{ showAmount($deposit->charge) }}</span></span>
                                <span class="d-block fw-bold" title="@lang('Amount With Charge')">{{ showAmount($deposit->amount + $deposit->charge) . ' ' . __($setting->site_cur) }}</span>
                            </span>
                        </td>
                        <td>
                            <span>
                                <span class="d-block">
                                    1 {{ $setting->site_cur }} = {{ showAmount($deposit->rate, 4) . ' ' . __($deposit->method_currency) }}
                                </span>
                                <span class="d-block fw-bold">
                                    {{ showAmount($deposit->final_amount) . ' ' . __($deposit->method_currency) }}
                                </span>
                            </span>
                        </td>
                        <td>
                            @php echo $deposit->status_badge @endphp
                        </td>
                        <td>
                            @php $details = $deposit->details ? json_encode($deposit->details) : null @endphp

                            <button type="button" class="btn btn--sm btn-outline--secondary py-1 @if($deposit->method_code >= 1000) btn-details @else disabled @endif" @if($deposit->method_code >= 1000) data-info="{{ $details }}" data-url="{{ route('user.file.download', ['filePath' => 'verify']) }}" @endif @if($deposit->status == ManageStatus::PAYMENT_CANCEL) data-admin_feedback="{{ __($deposit->admin_feedback) }}" @endif>
                                <i class="ti ti-eye"></i> @lang('Details')
                            </button>
                        </td>
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
@endsection

@push('user-panel-modal')
    {{-- Deposit Details Modal --}}
    <div class="custom--modal modal fade" id="depositDetailsModal" tabindex="-1" aria-labelledby="depositDetailsModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title" id="depositDetailsModalLabel">@lang('Details')</h3>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <table class="table table-borderless">
                        <tbody></tbody>
                    </table>
                    <div id="feedback"></div>
                </div>
                <div class="modal-footer">
                    <div class="d-flex justify-content-end gap-2">
                        <button type="button" class="btn btn--sm btn--secondary" data-bs-dismiss="modal">@lang('Close')</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endpush

@push('page-script')
    <script>
        (function ($) {
            'use strict'

            $('.btn-details').on('click', function () {
                let modal = $('#depositDetailsModal')
                let userData = $(this).data('info')
                let html = ``

                if (userData) {
                    let fileDownloadUrl = $(this).data('url')

                    userData.forEach(element => {
                        if (!element.value) return

                        if (element.type !== 'file') {
                            html += `
                                <tr>
                                    <td>${element.name}</td>
                                    <td>${element.value}</td>
                                </tr>
                            `
                        } else {
                            html += `
                                <tr>
                                    <td>${element.name}</td>
                                    <td>
                                        <a href="${fileDownloadUrl}&fileName=${element.value}">
                                            <i class="ti ti-download"></i> @lang('Download')
                                        </a>
                                    </td>
                                </tr>
                            `
                        }
                    })
                }

                modal.find('tbody').html(html)

                if ($(this).data('admin_feedback')) {
                    $('#feedback').html(`
                        <div class="custom--card w-100 h-auto mt-4 border rounded">
                            <div class="card-body p-3">
                                <h3 class="card-subtitle mb-2">@lang('Admin Feedback')</h3>
                                <p>${$(this).data('admin_feedback')}</p>
                            </div>
                        </div>
                    `)
                } else {
                    $('#feedback').html('')
                }

                modal.modal('show')
            })
        })(jQuery)
    </script>
@endpush
