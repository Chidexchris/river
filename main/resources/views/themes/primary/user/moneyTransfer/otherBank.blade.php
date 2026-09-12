@extends("{$activeTheme}layouts.auth")

@section('auth')
    <div class="row g-xxl-4 g-3">
        <div class="col-12">
            <div class="d-flex flex-wrap align-items-end justify-content-end gap-3">
                <button type="button" class="btn btn--sm btn--base" data-bs-toggle="modal" data-bs-target="#addBeneficiaryModal">
                    <i class="ti ti-circle-plus"></i> @lang('Add Beneficiary')
                </button>
            </div>
        </div>
        <div class="col-12">
            <div class="table-responsive">
                <table class="table table--striped table-borderless table--responsive--md">
                    <thead>
                        <tr>
                            <th>@lang('S.N.')</th>
                            <th>@lang('Bank')</th>
                            <th>@lang('Account Name')</th>
                            <th>@lang('Account Number')</th>
                            <th>@lang('Short Name')</th>
                            <th>@lang('Action')</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($beneficiaries as $beneficiary)
                            @php $bank = $beneficiary->beneficiaryable @endphp

                            <tr>
                                <td>{{ $beneficiaries->firstItem() + $loop->index }}</td>
                                <td>{{ __($bank->name) }}</td>
                                <td>{{ __($beneficiary->account_name) }}</td>
                                <td>{{ $beneficiary->account_number }}</td>
                                <td>{{ __($beneficiary->short_name) }}</td>
                                <td>
                                    <div class="d-flex justify-content-end gap-2">
                                        <button type="button" class="btn btn-outline--base btn--icon btn-edit" data-action="{{ route('user.money.transfer.beneficiary.update', $beneficiary) }}" data-other_bank="{{ $beneficiary->beneficiaryable_id }}" data-details="{{ json_encode($beneficiary->details) }}" data-file_download_url="{{ route('user.money.transfer.beneficiary.file', [$beneficiary, 'data' => ':value']) }}">
                                            <i class="ti ti-edit transform-1"></i>
                                        </button>
                                        <button type="button" class="btn btn--base btn--icon btn-transfer" data-modal_heading="{{ trans('Transfer Money to') . ' ' . __($beneficiary->short_name) . trans('\'s Account') }}" data-beneficiaryable_id="{{ $beneficiary->beneficiaryable_id }}" data-bs-toggle="modal" data-bs-target="#transferMoneyModal" data-bank_name="{{ __($bank->name) }}" data-per_transaction_min_amount="{{ $setting->cur_sym . showAmount($bank->per_transaction_min_amount) }}" data-per_transaction_max_amount="{{ $setting->cur_sym . showAmount($bank->per_transaction_max_amount) }}" data-daily_transaction_max_amount="{{ $setting->cur_sym . showAmount($bank->daily_transaction_max_amount) }}" data-daily_transaction_limit="{{ $bank->daily_transaction_limit }}" data-monthly_transaction_max_amount="{{ $setting->cur_sym . showAmount($bank->monthly_transaction_max_amount) }}" data-monthly_transaction_limit="{{ $bank->monthly_transaction_limit }}" data-fixed_charge="{{ $setting->cur_sym . showAmount($bank->fixed_charge) }}" data-percentage_charge="{{ $bank->percentage_charge . '%' }}" data-processing_time="{{ __($bank->processing_time) }}" data-instruction="{{ __($bank->instruction) }}" data-recipient="{{ __($beneficiary->account_name) }}">
                                            <i class="ti ti-transfer transform-1"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            @include('partials.noData')
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($beneficiaries->hasPages())
                {{ $beneficiaries->links() }}
            @endif
        </div>
    </div>
@endsection

@push('user-panel-modal')
    {{-- Add Beneficiary Modal --}}
    <div class="custom--modal modal fade" id="addBeneficiaryModal" tabindex="-1" aria-labelledby="addBeneficiaryLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title" id="addBeneficiaryLabel">@lang('Add a Beneficiary for Other Bank')</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('user.money.transfer.beneficiary.store') }}" method="post" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="beneficiary_type" value="other_bank">
                        <div class="form-group">
                            <label for="addOtherBank" class="form--label required">@lang('Other Bank')</label>
                            <select id="addOtherBank" class="form--control form--control--sm form-select select-2" name="other_bank" required>
                                @if(count($otherBanks) > 0)
                                    <option selected disabled>@lang('Select One')</option>

                                    @foreach($otherBanks as $otherBank)
                                        <option value="{{ $otherBank->id }}">{{ __($otherBank->name) }}</option>
                                    @endforeach
                                @else
                                    <option selected disabled>@lang('No Bank Found')</option>
                                @endif
                            </select>
                        </div>
                        <div id="addDynamicFields"></div>
                        <button type="submit" class="btn btn--sm btn--base w-100">
                            @lang('Submit')
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- Edit Beneficiary Modal --}}
    <div class="custom--modal modal fade" id="editBeneficiaryModal" tabindex="-1" aria-labelledby="editBeneficiaryLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title" id="editBeneficiaryLabel">@lang('Edit Beneficiary of Other Bank')</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="" method="post" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="beneficiary_type" value="other_bank">
                        <div class="form-group">
                            <label for="editOtherBank" class="form--label required">@lang('Other Bank')</label>
                            <select id="editOtherBank" class="form--control form--control--sm form-select select-2" name="other_bank" required>
                                <option disabled>@lang('Select One')</option>

                                @foreach($otherBanks as $otherBank)
                                    <option value="{{ $otherBank->id }}">{{ __($otherBank->name) }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div id="editDynamicFields"></div>
                        <button type="submit" class="btn btn--sm btn--base w-100">
                            @lang('Update')
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- Money Transfer Modal --}}
    <div class="custom--modal modal fade" id="transferMoneyModal" tabindex="-1" aria-labelledby="transferMoneyLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title" id="transferMoneyLabel"></h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-4">
                        <div class="col-12 instruction-container">
                            <div class="alert alert--base">
                                <span class="alert__title">@lang('Instruction')</span>
                                <div class="alert__desc small" id="instruction"></div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <table class="table table-borderless no-shadow mb-2">
                                <tbody>
                                    <tr>
                                        <td colspan="2" class="text-center"><span class="fw-bold text--base fs-6">@lang('Transaction Limit')</span></td>
                                    </tr>
                                    <tr>
                                        <td><span class="fw-bold">@lang('Per Transaction Min Limit')</span></td>
                                        <td id="perTransactionMinAmount"></td>
                                    </tr>
                                    <tr>
                                        <td><span class="fw-bold">@lang('Per Transaction Max Limit')</span></td>
                                        <td id="perTransactionMaxAmount"></td>
                                    </tr>
                                    <tr>
                                        <td><span class="fw-bold">@lang('Daily Transaction Max Amount')</span></td>
                                        <td id="dailyTransactionMaxAmount"></td>
                                    </tr>
                                    <tr>
                                        <td><span class="fw-bold">@lang('Daily Transaction Limit')</span></td>
                                        <td id="dailyTransactionLimit"></td>
                                    </tr>
                                    <tr>
                                        <td><span class="fw-bold">@lang('Monthly Transaction Max Amount')</span></td>
                                        <td id="monthlyTransactionMaxAmount"></td>
                                    </tr>
                                    <tr>
                                        <td><span class="fw-bold">@lang('Monthly Transaction Limit')</span></td>
                                        <td id="monthlyTransactionLimit"></td>
                                    </tr>
                                </tbody>
                            </table>
                            <p class="text--danger"><small>* @lang('Processing Time'): <span id="processingTime"></span></small></p>
                            <p class="text--danger"><small>* @lang('Charge'): <span id="charge"></span></small></p>
                        </div>
                        <div class="col-lg-6">
                            <form action="{{ route('user.money.transfer.other.bank.transfer') }}" method="post">
                                @csrf
                                <input type="hidden" id="beneficiaryableId" name="beneficiaryable_id">
                                <div class="form-group">
                                    <label for="transferBankName" class="form--label">@lang('Bank')</label>
                                    <input type="text" id="transferBankName" class="form--control form--control--sm" readonly>
                                </div>
                                <div class="form-group">
                                    <label for="transferRecipientName" class="form--label">@lang('Recipient')</label>
                                    <input type="text" id="transferRecipientName" class="form--control form--control--sm" readonly>
                                </div>
                                <div class="form-group">
                                    <label for="transferAmount" class="form--label required">@lang('Amount')</label>
                                    <div class="input--group">
                                        <input type="number" step="any" min="0" id="transferAmount" class="form--control form--control--sm" name="amount" required>
                                        <span class="input-group-text">{{ __($setting->site_cur) }}</span>
                                    </div>
                                </div>

                                @if($setting->sms_based_otp || $setting->email_based_otp)
                                    <div class="form-group">
                                        <label for="authorizationMode" class="form--label required">@lang('Authorization Mode')</label>
                                        <select id="authorizationMode" class="form--control form--control--sm wide" name="authorization_mode" required>
                                            <option selected disabled>@lang('Select One')</option>

                                            @if($setting->email_based_otp)
                                                <option value="{{ ManageStatus::AUTHORIZATION_MODE_EMAIL }}">@lang('Email')</option>
                                            @endif

                                            @if($setting->sms_based_otp)
                                                <option value="{{ ManageStatus::AUTHORIZATION_MODE_SMS }}">@lang('SMS')</option>
                                            @endif
                                        </select>
                                    </div>
                                @endif

                                <button type="submit" class="btn btn--sm btn--base w-100">
                                    @lang('Submit')
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endpush

@push('page-style-lib')
    <link rel="stylesheet" href="{{ asset('assets/universal/css/select2.min.css') }}">
@endpush

@push('page-script-lib')
    <script src="{{ asset('assets/universal/js/select2.min.js') }}"></script>
@endpush

@push('page-script')
    <script>
        (function ($) {
            'use strict'

            let currentBeneficiaryBank, dataAttr

            function fetchOtherBankForm(otherBank, dynamicFieldsSelector, callback = null) {
                const url = "{{ route('user.money.transfer.other.bank.form', ':otherBank') }}".replace(':otherBank', otherBank)

                $.get(url)
                    .done(function (response) {
                        $(dynamicFieldsSelector).html(response.html)

                        if (typeof callback === 'function') callback()
                    })
                    .fail(function (jqXHR) {
                        showToasts('error', jqXHR.responseJSON.message)
                    })
            }

            function toSnakeCase(str) {
                return str
                    .replace(/([a-z])([A-Z])/g, '$1_$2')
                    .replace(/\s+/g, '_')
                    .replace(/-+/g, '_')
                    .toLowerCase()
            }

            function populateFormFields(data, modal) {
                $.each(data.details, function (index, element) {
                    const tagName = toSnakeCase(element.name)
                    const htmlElement = modal.find(`[name="${tagName}"]`)

                    switch (element.type) {
                        case 'checkbox':
                            const values = element.value

                            if (Array.isArray(values)) {
                                values.forEach(val => {
                                    modal.find(`[name="${tagName}[]"][value="${val}"]`).prop('checked', true)
                                })
                            }

                            break
                        case 'radio':
                            modal.find(`[name="${tagName}"][value="${element.value}"]`).prop('checked', true)

                            break
                        case 'file':
                            if (element.value) {
                                const fileDownloadUrl = decodeURIComponent(data.file_download_url).replace(':value', element.value)
                                const fileDownloadHtml = `<a href="${fileDownloadUrl}" class="small">
                                    <i class="ti ti-file-download"></i> @lang('Download')
                                </a>`

                                htmlElement.parent().append(fileDownloadHtml)
                            }

                            break
                        default:
                            htmlElement.val(element.value)
                    }
                })
            }

            $(function () {
                $('#addOtherBank').on('change', function () {
                    const otherBank = $(this).find(':selected').val()

                    if (otherBank) fetchOtherBankForm(otherBank, '#addDynamicFields')
                })

                $('#addBeneficiaryModal').on('hidden.bs.modal', function () {
                    $('#addOtherBank').val(null).trigger('change')
                    $('.select2-selection__rendered').text('@lang("Select One")')
                    $('#addDynamicFields').html('')
                    $(this).find('form')[0].reset()
                })

                $('.btn-edit').on('click', function () {
                    dataAttr = $(this).data()
                    const editModal = $('#editBeneficiaryModal')
                    const editOtherBank = $('#editOtherBank')

                    currentBeneficiaryBank = dataAttr.other_bank

                    editModal.find('form').attr('action', dataAttr.action)
                    editOtherBank.val(currentBeneficiaryBank).select2({
                        containerCssClass: ":all:",
                        dropdownParent: editOtherBank.parents('.modal'),
                    })

                    fetchOtherBankForm(currentBeneficiaryBank, '#editDynamicFields', function () {
                        populateFormFields(dataAttr, editModal)
                    })

                    editModal.modal('show')
                })

                $('#editOtherBank').on('change', function () {
                    const otherBank = $(this).find(':selected').val()
                    const editModal = $('#editBeneficiaryModal')

                    if (currentBeneficiaryBank === parseInt(otherBank)) {
                        fetchOtherBankForm(currentBeneficiaryBank, '#editDynamicFields', function () {
                            populateFormFields(dataAttr, editModal)
                        })
                    } else if (otherBank) {
                        fetchOtherBankForm(otherBank, '#editDynamicFields')
                    }
                })

                $('.btn-transfer').on('click', function () {
                    let data = $(this).data()

                    $('#transferMoneyLabel').text(data.modal_heading)
                    $('#beneficiaryableId').val(data.beneficiaryable_id)

                    if (!data.instruction) {
                        $('.instruction-container').addClass('d-none')
                        $('#instruction').html('')
                    } else {
                        $('.instruction-container').removeClass('d-none')
                        $('#instruction').html(data.instruction)
                    }

                    $('#perTransactionMinAmount').text(data.per_transaction_min_amount)
                    $('#perTransactionMaxAmount').text(data.per_transaction_max_amount)
                    $('#dailyTransactionMaxAmount').text(data.daily_transaction_max_amount)
                    $('#dailyTransactionLimit').text(data.daily_transaction_limit)
                    $('#monthlyTransactionMaxAmount').text(data.monthly_transaction_max_amount)
                    $('#monthlyTransactionLimit').text(data.monthly_transaction_limit)
                    $('#processingTime').text(data.processing_time)
                    $('#charge').text(`${data.fixed_charge} + ${data.percentage_charge}`)
                    $('#transferBankName').val(data.bank_name)
                    $('#transferRecipientName').val(data.recipient)
                })

                $('#transferMoneyModal').on('hidden.bs.modal', function () {
                    $('#transferAmount').val('')
                    $('#authorizationMode').val('@lang("Select One")').niceSelect('update')
                })
            })
        })(jQuery)
    </script>
@endpush
