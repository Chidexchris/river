@extends('admin.layouts.master')

@section('master')
    <div class="col-12">
        <div class="table-responsive scroll">
            <table class="table table--striped table-borderless table--responsive--sm">
                <thead>
                    <tr>
                        <th>@lang('Trx')</th>
                        <th>@lang('Sender')</th>
                        <th>@lang('Receiver')</th>
                        <th>@lang('Bank')</th>
                        <th>@lang('Amount')</th>
                        <th>@lang('Charge')</th>
                        <th>@lang('Initiated')</th>
                        <th>@lang('Status')</th>
                        <th>@lang('Action')</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($moneyTransfers as $moneyTransfer)
                        <tr>
                            <td>{{ $moneyTransfer->trx }}</td>
                            <td>
                                <div>
                                    <p class="fw-semibold">{{ __($moneyTransfer->user->fullname) }}</p>
                                    <a href="{{ route('admin.user.details', $moneyTransfer->user->id) }}" class="fw-semibold text--base">
                                        {{ $moneyTransfer->user->account_number }}
                                    </a>
                                </div>
                            </td>
                            <td>
                                <div>
                                    <p class="fw-semibold">{{ __($moneyTransfer->receiver_account_name) }}</p>
                                    <p class="fw-semibold text--base">{{ $moneyTransfer->receiver_account_number }}</p>
                                </div>
                            </td>
                            <td>{{ is_null($moneyTransfer->beneficiary_id) ? trans('Wire Transfer') : __($moneyTransfer->bank_name) }}</td>
                            <td>{{ $setting->cur_sym . showAmount($moneyTransfer->amount) }}</td>
                            <td>{{ $setting->cur_sym . showAmount($moneyTransfer->charge) }}</td>
                            <td>
                                <div>
                                    <p>{{ showDateTime($moneyTransfer->created_at) }}</p>
                                    <p>{{ diffForHumans($moneyTransfer->created_at) }}</p>
                                </div>
                            </td>
                            <td>
                                @php echo $moneyTransfer->status_badge @endphp
                            </td>
                            <td>
                                <div class="custom--dropdown">
                                    <button type="button" @class(['btn btn--sm btn--icon btn--base', 'disabled' => $moneyTransfer->beneficiary?->beneficiaryable_type == 'App\Models\User']) data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class="ti ti-dots-vertical"></i>
                                    </button>

                                    @if($moneyTransfer->beneficiary?->beneficiaryable_type == 'App\Models\OtherBank' || is_null($moneyTransfer->beneficiary_id))
                                        <ul class="dropdown-menu">
                                            <li>
                                                <a href="#moneyTransferInfo" class="dropdown-item view-details" data-bs-toggle="offcanvas" role="button" aria-controls="moneyTransferInfo" data-bank_name="{{ is_null($moneyTransfer->beneficiary_id) ? trans('Wire Transfer') : __($moneyTransfer->bank_name) }}" data-details="{{ $moneyTransfer->beneficiary ? json_encode($moneyTransfer->beneficiary->details) : json_encode($moneyTransfer->wire_transfer_payload) }}" data-file_download_url="{{ route('admin.money.transfers.file', [$moneyTransfer, 'data' => ':value']) }}" data-rejection_reason="{{ __($moneyTransfer->rejection_reason) }}">
                                                    <span class="dropdown-icon"><i class="ti ti-device-desktop text--info"></i></span> @lang('View Details')
                                                </a>
                                            </li>

                                            @if($moneyTransfer->status == ManageStatus::MONEY_TRANSFER_PENDING)
                                                @can('mark money transfer as complete')
                                                    <li>
                                                        <button type="button" class="dropdown-item decisionBtn" data-action="{{ route('admin.money.transfers.complete', $moneyTransfer) }}" data-question="@lang('Are you sure you want to mark this transfer as complete?')">
                                                            <span class="dropdown-icon"><i class="ti ti-circle-check text--success"></i></span> @lang('Mark As Complete')
                                                        </button>
                                                    </li>
                                                @endcan

                                                @can('mark money transfer as fail')
                                                    <li>
                                                        <button type="button" class="dropdown-item btn-fail" data-action="{{ route('admin.money.transfers.fail', $moneyTransfer) }}">
                                                            <span class="dropdown-icon"><i class="ti ti-circle-x text--danger"></i></span> @lang('Mark As Fail')
                                                        </button>
                                                    </li>
                                                @endcan
                                            @endif
                                        </ul>
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

        @if ($moneyTransfers->hasPages())
            {{ paginateLinks($moneyTransfers) }}
        @endif
    </div>

    <div class="col-12">
        <div class="custom--offcanvas offcanvas offcanvas-end" tabindex="-1" id="moneyTransferInfo" aria-labelledby="moneyTransferInfoLabel">
            <div class="offcanvas-header">
                <h5 class="offcanvas-title" id="moneyTransferInfoLabel">@lang('Money Transfer Details')</h5>
                <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
            </div>
            <div class="offcanvas-body">
                <p class="offcanvas__subtitle fw-semibold mb-1">@lang('Beneficiary Info')</p>
                <table class="table table-borderless mb-3">
                    <tbody id="beneficiaryInfo"></tbody>
                </table>
                <div id="reasonWrapper"></div>
            </div>
        </div>
    </div>

    <x-decisionModal />

    <div class="col-12">
        <div class="custom--modal modal fade" id="failModal" tabindex="-1" aria-labelledby="failModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <button type="button" class="btn btn--sm btn--icon btn-outline--secondary modal-close" data-bs-dismiss="modal" aria-label="Close">
                        <i class="ti ti-x"></i>
                    </button>
                    <div class="modal-body modal-alert">
                        <div class="text-center">
                            <div class="modal-thumb">
                                <img src="{{ asset('assets/admin/images/light.png') }}" alt="Image">
                            </div>
                            <h2 class="modal-title" id="failModalLabel">@lang('Make Your Decision')</h2>
                            <p class="mb-3">@lang('Are you sure you want to mark this transfer as fail?')</p>
                            <form action="" method="POST">
                                @csrf
                                <label class="form--label">@lang('Reason'):</label>
                                <textarea class="form--control form--control--sm" name="rejection_reason" required></textarea>
                                <div class="d-flex justify-content-center gap-2 mt-3">
                                    <button type="button" class="btn btn--sm btn-outline--base" data-bs-dismiss="modal">@lang('No')</button>
                                    <button type="submit" class="btn btn--sm btn--base">@lang('Yes')</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('breadcrumb')
    <x-searchForm placeholder="Search..." />
@endpush

@push('page-script')
    <script>
        (function ($) {
            'use strict'

            $(function () {
                $('.view-details').on('click', function () {
                    let data = $(this).data()

                    if (data.details) {
                        let beneficiaryInfoHtml = [
                            `<tr>
                                <td class="fw-bold">Bank</td>
                                <td>${data.bank_name}</td>
                            </tr>`
                        ]

                        $.each(data.details, function (index, element) {
                            let name = element.name
                            let value = element.value
                            let type = element.type

                            if (type === 'checkbox') {
                                value = value.join(', ')
                            } else if (type === 'file') {
                                const fileDownloadUrl = decodeURIComponent(data.file_download_url).replace(':value', value)

                                value = `
                                    <a href="${fileDownloadUrl}" class="btn btn--sm btn-outline--secondary">
                                        <i class="ti ti-download"></i> Download
                                    </a>
                                `
                            }

                            if (type === 'textarea') {
                                beneficiaryInfoHtml.push(`
                                    <tr>
                                        <td colspan="100%" class="text-start">
                                            <p class="fw-bold mb-1">${name}</p>
                                            <p>${value}</p>
                                        </td>
                                    </tr>
                                `)
                            } else {
                                beneficiaryInfoHtml.push(`
                                    <tr>
                                        <td class="fw-bold">${name}</td>
                                        <td>${value}</td>
                                    </tr>
                                `)
                            }
                        })

                        $('#beneficiaryInfo').html(beneficiaryInfoHtml.join(''))
                    }

                    if (data.rejection_reason) {
                        $('#reasonWrapper').html(`
                            <p class="offcanvas__subtitle fw-semibold mb-1">@lang('Rejection Reason')</p>
                            <div class="custom--card h-auto">
                                <div class="card-body p-3">
                                    <p>${data.rejection_reason}</p>
                                </div>
                            </div>
                        `)
                    } else {
                        $('#reasonWrapper').html('')
                    }
                })

                $('.btn-fail').on('click', function () {
                    let modal = $('#failModal')

                    modal.find('form').attr('action', $(this).data('action'))
                    modal.modal('show')
                })
            })
        })(jQuery)
    </script>
@endpush
