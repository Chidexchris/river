@extends("{$activeTheme}layouts.auth")

@section('auth')
    <div class="mb-xxl-4 mb-3">
        <div class="d-flex flex-wrap align-items-end justify-content-end gap-3">
            <form action="" method="get" class="input--group">
                <input type="text" class="form--control form--control--sm" name="search" placeholder="@lang('Transaction Number')" value="{{ request('search') }}">
                <button type="submit" class="btn btn--sm btn--base px-3">
                    <i class="ti ti-search"></i>
                </button>
            </form>
            <a href="{{ route('user.withdraw') }}" class="btn btn--sm btn--base">
                @lang('Withdraw Money')
            </a>
        </div>
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
                @forelse($withdrawals as $withdrawal)
                    <tr>
                        <td>{{ $withdrawals->firstItem() + $loop->index }}</td>
                        <td>
                            <span>
                                <span class="d-block text--base">{{ __($withdrawal->method->name) }}</span>
                                <span class="d-block" title="@lang('Transaction Number')">{{ $withdrawal->trx }}</span>
                            </span>
                        </td>
                        <td>
                            <span>
                                <span class="d-block">{{ showDateTime($withdrawal->created_at) }}</span>
                                <span class="d-block">{{ diffForHumans($withdrawal->created_at) }}</span>
                            </span>
                        </td>
                        <td>
                            <span>
                                <span class="d-block">{{ $setting->cur_sym . showAmount($withdrawal->amount) }} - <span class="text--danger" title="@lang('Charge')">{{ showAmount($withdrawal->charge) }}</span></span>
                                <span class="d-block fw-bold" title="@lang('Amount Without Charge')">
                                    {{ showAmount($withdrawal->amount - $withdrawal->charge) . ' ' . __($setting->site_cur) }}
                                </span>
                            </span>
                        </td>
                        <td>
                            <span>
                                <span class="d-block">
                                    1 {{ $setting->site_cur }} = {{ showAmount($withdrawal->rate, 4) . ' ' . __($withdrawal->currency) }}
                                </span>
                                <span class="d-block fw-bold">
                                    {{ showAmount($withdrawal->final_amount) . ' ' . __($withdrawal->currency) }}
                                </span>
                            </span>
                        </td>
                        <td>
                            @php echo $withdrawal->statusBadge @endphp
                        </td>
                        <td>
                            <button type="button" class="btn btn--sm btn-outline--secondary py-1 @if(is_null($withdrawal->withdraw_information)) disabled @else btn-details @endif" data-info="{{ json_encode($withdrawal->withdraw_information) }}" data-url="{{ route('user.file.download', ['filePath' => 'verify']) }}" @if($withdrawal->status == ManageStatus::PAYMENT_CANCEL) data-admin_feedback="{{ __($withdrawal->admin_feedback) }}" @endif>
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

    @if ($withdrawals->hasPages())
        {{ paginateLinks($withdrawals) }}
    @endif
@endsection

@push('user-panel-modal')
    {{-- Withdraw Details Modal --}}
    <div class="custom--modal modal fade" id="withdrawDetailsModal" tabindex="-1" aria-labelledby="withdrawDetailsModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title" id="withdrawDetailsModalLabel">@lang('Details')</h3>
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
        (function($) {
            "use strict"

            $('.btn-details').on('click', function() {
                let modal    = $('#withdrawDetailsModal')
                let userData = $(this).data('info')
                let html     = ``

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
