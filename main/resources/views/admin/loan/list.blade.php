@extends('admin.layouts.master')

@section('master')
    <div class="col-12">
        <div class="table-responsive scroll">
            <table class="table table--striped table-borderless table--responsive--sm">
                <thead>
                    <tr>
                        <th>@lang('Loan No.') | @lang('Plan')</th>
                        <th>@lang('User') | @lang('Account No.')</th>
                        <th>@lang('Amount')</th>
                        <th>@lang('Installment Amount')</th>
                        <th>@lang('Installment')</th>
                        <th>@lang('Installment Tracker')</th>
                        <th>@lang('Date')</th>
                        <th>@lang('Status')</th>
                        <th>@lang('Action')</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($loanList as $loan)
                        <tr>
                            <td>
                                <div>
                                    <p class="fw-semibold text--base">{{ $loan->scheme_code }}</p>
                                    <p class="fw-semibold">{{ __($loan->plan_name) }}</p>
                                </div>
                            </td>
                            <td>
                                <div>
                                    <p class="fw-semibold text--base">{{ __($loan->user->fullname) }}</p>
                                    <p class="fw-semibold">{{ $loan->user->account_number }}</p>
                                </div>
                            </td>
                            <td>
                                <div>
                                    <p><span class="fw-semibold">@lang('Requested'):</span> {{ $setting->cur_sym . showAmount($loan->amount_requested) }}</p>
                                    <p><span class="fw-semibold text--base">@lang('Receivable'):</span> {{ $setting->cur_sym . showAmount($loan->payable_amount) }}</p>
                                </div>
                            </td>
                            <td>
                                <div>
                                    <p>{{ $setting->cur_sym . showAmount($loan->per_installment) }}</p>
                                    <p class="text--base">
                                        {{ trans('Per') . ' ' . $loan->installment_interval . ' ' . trans(\Illuminate\Support\Str::plural('Day', $loan->installment_interval)) }}
                                    </p>
                                </div>
                            </td>
                            <td>
                                <div>
                                    <p><span class="fw-semibold">@lang('Total'):</span> {{ $loan->total_installment }}</p>
                                    <p><span class="fw-semibold text--base">@lang('Given'):</span> {{ $loan->given_installment }}</p>
                                </div>
                            </td>
                            <td>
                                <div>
                                    <p><span class="fw-semibold">@lang('Next Date'):</span> {{ is_null($loan->next_installment_date) ? trans('N/A') : showDateTime($loan->next_installment_date, 'd M, Y') }}</p>
                                    <p><span class="fw-semibold">@lang('Due Count'):</span> {{ $loan->late_installments }}</p>
                                </div>
                            </td>
                            <td>
                                <div>
                                    <p><span class="fw-semibold">@lang('Initiated'):</span> {{ showDateTime($loan->created_at, 'd M, Y') }}</p>
                                    <p><span class="fw-semibold text--base">@lang('Approved'):</span> {{ is_null($loan->approved_at) ? trans('N/A') : showDateTime($loan->approved_at, 'd M, Y') }}</p>
                                </div>
                            </td>
                            <td>
                                @php echo $loan->status_badge @endphp
                            </td>
                            <td>
                                <div class="custom--dropdown">
                                    <button type="button" class="btn btn--icon btn--sm btn--base" data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class="ti ti-dots-vertical"></i>
                                    </button>
                                    <ul class="dropdown-menu">
                                        <li>
                                            <a href="#loanApplicationInfo" class="dropdown-item view-details" data-bs-toggle="offcanvas" role="button" aria-controls="loanApplicationInfo" data-form_data="{{ json_encode($loan->form_data) }}" data-file_download_url="{{ route('admin.loan.file', [$loan, 'data' => ':value']) }}" data-admin_feedback="{{ __($loan->admin_feedback) }}" data-status="{{ $loan->status }}" data-approval_question="@lang('Do you want to approve this loan?')" data-approval_action="{{ route('admin.loan.approve', $loan) }}" data-rejection_action="{{ route('admin.loan.reject', $loan) }}">
                                                <span class="dropdown-icon"><i class="ti ti-device-desktop text--base"></i></span> @lang('Details')
                                            </a>
                                        </li>

                                        @can('view loan installments')
                                            <li>
                                                <a href="{{ route('admin.loan.installments', $loan) }}" class="dropdown-item">
                                                    <span class="dropdown-icon"><i class="ti ti-calendar-dollar text--info"></i></span> @lang('Installments')
                                                </a>
                                            </li>
                                        @endcan
                                    </ul>
                                </div>
                            </td>
                        </tr>
                    @empty
                        @include('partials.noData')
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($loanList->hasPages())
            {{ paginateLinks($loanList) }}
        @endif
    </div>

    <div class="col-12">
        <div class="custom--offcanvas offcanvas offcanvas-end" tabindex="-1" id="loanApplicationInfo" aria-labelledby="loanApplicationInfoLabel">
            <div class="offcanvas-header">
                <h5 class="offcanvas-title" id="loanApplicationInfoLabel">@lang('Loan Application Details')</h5>
                <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
            </div>
            <div class="offcanvas-body">
                <p class="offcanvas__subtitle fw-semibold mb-1">@lang('Applicant Info')</p>
                <table class="table table-borderless mb-3">
                    <tbody id="applicantInfo"></tbody>
                </table>
                <div id="decisionButtonWrapper"></div>
                <div id="feedbackWrapper"></div>
            </div>
        </div>
    </div>

    <x-decisionModal />

    <div class="col-12">
        <div class="custom--modal modal fade" id="rejectionModal" tabindex="-1" aria-labelledby="rejectionModalLabel" aria-hidden="true">
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
                            <h2 class="modal-title" id="rejectionModalLabel">@lang('Make Your Decision')</h2>
                            <p class="mb-3">@lang('Do you want to reject this loan?')</p>
                            <form action="" method="POST">
                                @csrf
                                <label class="form--label">@lang('Reason'):</label>
                                <textarea class="form--control form--control--sm" name="admin_feedback" required></textarea>
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
    <x-searchForm placeholder="Search Here..." />
@endpush

@push('page-script')
    <script>
        (function ($) {
            'use strict'

            $(function () {
                let loanStatus = parseInt(`{{ ManageStatus::LOAN_PENDING }}`)

                $('.view-details').on('click', function () {
                    let data = $(this).data()

                    if (data.form_data) {
                        let applicantInfoHtml = []

                        $.each(data.form_data, function (index, element) {
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
                                applicantInfoHtml.push(`
                                    <tr>
                                        <td colspan="100%" class="text-start">
                                            <p class="fw-bold mb-1">${name}</p>
                                            <p>${value}</p>
                                        </td>
                                    </tr>
                                `)
                            } else {
                                applicantInfoHtml.push(`
                                    <tr>
                                        <td class="fw-bold">${name}</td>
                                        <td>${value}</td>
                                    </tr>
                                `)
                            }
                        })

                        $('#applicantInfo').html(applicantInfoHtml.join(''))
                    }

                    if (data.status === loanStatus) {
                        $('#decisionButtonWrapper').html(`
                            <div class="d-flex justify-content-center flex-wrap gap-2 pt-1">
                                <button type="button" class="btn btn--sm btn--success decisionBtn" data-question="${data.approval_question}" data-action="${data.approval_action}">
                                    <i class="ti ti-circle-check"></i> @lang('Approve')
                                </button>
                                <button type="button" class="btn btn--sm btn--danger btn-reject" data-action="${data.rejection_action}">
                                    <i class="ti ti-circle-x"></i> @lang('Reject')
                                </button>
                            </div>
                        `)
                    } else {
                        $('#decisionButtonWrapper').html('')
                    }

                    if (data.admin_feedback) {
                        $('#feedbackWrapper').html(`
                            <p class="offcanvas__subtitle fw-semibold mb-1">@lang('Admin Feedback')</p>
                            <div class="custom--card h-auto">
                                <div class="card-body p-3">
                                    <p>${data.admin_feedback}</p>
                                </div>
                            </div>
                        `)
                    } else {
                        $('#feedbackWrapper').html('')
                    }
                })
            })

            $(document).on('click', '.btn-reject', function () {
                let modal = $('#rejectionModal')

                modal.find('form').attr('action', $(this).data('action'))
                modal.modal('show')
            })
        })(jQuery)
    </script>
@endpush
