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
            <table class="table table--striped table-borderless table--responsive--md">
                <thead>
                    <tr>
                        <th>@lang('S.N.')</th>
                        <th>@lang('Account Number')</th>
                        <th>@lang('Account Name')</th>
                        <th>@lang('Short Name')</th>
                        <th>@lang('Action')</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($beneficiaries as $beneficiary)
                        <tr>
                            <td>{{ $beneficiaries->firstItem() + $loop->index }}</td>
                            <td>{{ $beneficiary->details['account_number'] }}</td>
                            <td>{{ __($beneficiary->details['account_name']) }}</td>
                            <td>{{ __($beneficiary->details['short_name']) }}</td>
                            <td>
                                <div class="d-flex justify-content-end gap-2">
                                    <button type="button" class="btn btn-outline--base btn--icon btn-edit" data-bs-toggle="modal" data-bs-target="#editBeneficiaryModal" data-action="{{ route('user.money.transfer.beneficiary.update', $beneficiary) }}" data-account_number="{{ $beneficiary->details['account_number'] }}" data-account_name="{{ __($beneficiary->details['account_name']) }}" data-short_name="{{ __($beneficiary->details['short_name']) }}">
                                        <i class="ti ti-edit transform-1"></i>
                                    </button>
                                    <button type="button" class="btn btn--base btn--icon btn-transfer" data-modal_heading="{{ trans('Transfer Money to') . ' ' . __($beneficiary->details['short_name']) . trans('\'s Account') }}" data-beneficiaryable_id="{{ $beneficiary->beneficiaryable_id }}" data-bs-toggle="modal" data-bs-target="#transferMoneyModal">
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

            @if ($beneficiaries->hasPages())
                {{ $beneficiaries->links() }}
            @endif
        </div>
    </div>
@endsection

@push('user-panel-modal')
    {{-- Add Beneficiary Modal --}}
    <div class="custom--modal modal fade" id="addBeneficiaryModal" tabindex="-1" aria-labelledby="addBeneficiaryLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title" id="addBeneficiaryLabel">{{ trans('Add a Beneficiary to RiverWind Bank') }}</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('user.money.transfer.beneficiary.store') }}" method="post">
                        @csrf
                        <input type="hidden" name="beneficiary_type" value="own_bank">
                        <div class="form-group">
                            <label for="addAccountNumber" class="form--label required">@lang('Account Number')</label>
                            <input type="text" id="addAccountNumber" class="form--control form--control--sm" name="account_number" required>
                            <small class="text--danger" id="addAccountError"></small>
                        </div>
                        <div class="form-group">
                            <label for="addAccountName" class="form--label">@lang('Account Name')</label>
                            <input type="text" id="addAccountName" class="form--control form--control--sm" data-original_name="">
                        </div>
                        <div class="form-group">
                            <label for="addShortName" class="form--label required">@lang('Short Name')</label>
                            <input type="text" id="addShortName" class="form--control form--control--sm" name="short_name" required>
                        </div>
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
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title" id="editBeneficiaryLabel">{{ trans('Edit Beneficiary of RiverWind Bank') }}</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="" method="post">
                        @csrf
                        <input type="hidden" name="beneficiary_type" value="own_bank">
                        <div class="form-group">
                            <label for="editAccountNumber" class="form--label required">@lang('Account Number')</label>
                            <input type="text" id="editAccountNumber" class="form--control form--control--sm" name="account_number" required>
                            <small class="text--danger" id="editAccountError"></small>
                        </div>
                        <div class="form-group">
                            <label for="editAccountName" class="form--label">@lang('Account Name')</label>
                            <input type="text" id="editAccountName" class="form--control form--control--sm" data-original_name="">
                        </div>
                        <div class="form-group">
                            <label for="editShortName" class="form--label required">@lang('Short Name')</label>
                            <input type="text" id="editShortName" class="form--control form--control--sm" name="short_name" required>
                        </div>
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
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title" id="transferMoneyLabel"></h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('user.money.transfer.within.bank.transfer') }}" method="post">
                        @csrf
                        <input type="hidden" id="beneficiaryableId" name="beneficiaryable_id">
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

                        <div class="form-group">
                            <table class="table table-borderless no-shadow">
                                <tbody>
                                    <tr>
                                        <td class="fw-bold">@lang('Per Transaction Limit')</td>
                                        <td>{{ $setting->cur_sym . showAmount($setting->per_transaction_min_amount) . ' - ' . $setting->cur_sym . showAmount($setting->per_transaction_max_amount) }}</td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold">@lang('Daily Transaction Limit')</td>
                                        <td>{{ $setting->cur_sym . showAmount($setting->daily_transaction_max_amount) }}</td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold">@lang('Monthly Transaction Limit')</td>
                                        <td>{{ $setting->cur_sym . showAmount($setting->monthly_transaction_max_amount) }}</td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold">@lang('Per Transaction Charge')</td>
                                        <td class="text--danger">
                                            <span title="@lang('Fixed Charge')">{{ $setting->cur_sym . showAmount($setting->fixed_charge) }}</span> + <span title="@lang('Percentage Charge')">{{ showAmount($setting->percentage_charge) . '%' }}</span>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <button type="submit" class="btn btn--sm btn--base w-100">
                            @lang('Submit')
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endpush

@push('page-script')
    <script>
        (function ($) {
            'use strict'

            function checkAccountNumber(inputSelector, nameSelector, errorSelector) {
                $(inputSelector).on('focusout', function () {
                    let accountNumber = $(this).val()

                    if (accountNumber) {
                        let data = {
                            account_number: accountNumber,
                            _token: "{{ csrf_token() }}",
                        }

                        $.ajax({
                            url: "{{ route('user.money.transfer.within.bank.check.account') }}",
                            type: 'POST',
                            data: data,
                            dataType: 'json',
                            success: function (response) {
                                if (response.name) {
                                    $(errorSelector).text('')
                                    $(nameSelector).val(response.name).data('original_name', response.name)
                                }
                            },
                            error: function (xhr) {
                                if (xhr.status === 404) {
                                    $(errorSelector).text(xhr.responseJSON.message)
                                    $(nameSelector).val('').data('original_name', '')
                                } else {
                                    $(errorSelector).text('An unexpected error occurred.')
                                }
                            }
                        })
                    } else {
                        $(errorSelector).text('')
                        $(nameSelector).val('').data('original_name', '')
                    }
                })
            }

            function toPascalCase(str) {
                return str
                    .replace(/_([a-z])/g, (match, letter) => letter.toUpperCase())
                    .replace(/^./, (match) => match.toUpperCase())
            }

            $(function () {
                checkAccountNumber('#addAccountNumber', '#addAccountName', '#addAccountError')
                checkAccountNumber('#editAccountNumber', '#editAccountName', '#editAccountError')

                $('#addAccountName, #editAccountName').on('focusout', function () {
                    let originalName = $(this).data('original_name')

                    if ($(this).val() !== originalName) $(this).val(originalName)
                })

                $('#addBeneficiaryModal').on('hidden.bs.modal', function () {
                    $(this).find('form')[0].reset()
                    $('#addAccountError').text('')
                })

                $('.btn-edit').on('click', function () {
                    let data = $(this).data()
                    let editModal = $('#editBeneficiaryModal')

                    editModal.find('form').attr('action', data.action)

                    for (const property in data) {
                        let key = toPascalCase(property)

                        editModal.find(`#edit${key}`).val(data[property])
                    }
                })

                $('.btn-transfer').on('click', function () {
                    let data = $(this).data()

                    $('#transferMoneyLabel').text(data.modal_heading)
                    $('#beneficiaryableId').val(data.beneficiaryable_id)
                })

                $('#transferMoneyModal').on('hidden.bs.modal', function () {
                    $('#transferAmount').val('')
                    $('#authorizationMode').val('@lang("Select One")').niceSelect('update')
                })
            })
        })(jQuery)
    </script>
@endpush
