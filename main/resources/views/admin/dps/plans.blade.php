@extends('admin.layouts.master')

@section('master')
    <div class="col-12">
        <div class="table-responsive scroll">
            <table class="table table--striped table-borderless table--responsive--sm">
                <thead>
                    <tr>
                        <th>@lang('Name')</th>
                        <th>@lang('Per Installment')</th>
                        <th>@lang('Installment Interval')</th>
                        <th>@lang('Total Installment')</th>
                        <th>@lang('Deposit Amount')</th>
                        <th>@lang('Interest Rate')</th>
                        <th>@lang('Delay Duration')</th>
                        <th>@lang('Charge')</th>
                        <th>@lang('Status')</th>

                        @canany(['edit dps plan', 'change dps plan status'])
                            <th>@lang('Action')</th>
                        @endcanany
                    </tr>
                </thead>
                <tbody>
                    @forelse ($dpsPlans as $dpsPlan)
                        <tr>
                            <td>{{ __($dpsPlan->name) }}</td>
                            <td>{{ $setting->cur_sym . showAmount($dpsPlan->per_installment) }}</td>
                            <td>{{ $dpsPlan->installment_interval . ' ' . trans('Days') }}</td>
                            <td>{{ $dpsPlan->total_installment }}</td>
                            <td>{{ $setting->cur_sym . showAmount($dpsPlan->total_deposit_amount) }}</td>
                            <td>{{ showAmount($dpsPlan->interest_rate) . '%' }}</td>
                            <td>{{ $dpsPlan->delay_duration . ' ' . trans('Days') }}</td>
                            <td>
                                <div>
                                    <p><span class="fw-bold">@lang('Fixed'):</span> {{ $setting->cur_sym . showAmount($dpsPlan->fixed_charge) }}</p>
                                    <p><span class="fw-bold">@lang('Percentage'):</span> {{ showAmount($dpsPlan->percentage_charge) . '%' }}</p>
                                </div>
                            </td>
                            <td>
                                @php echo $dpsPlan->status_badge @endphp
                            </td>

                            @canany(['edit dps plan', 'change dps plan status'])
                                <td>
                                    <div class="custom--dropdown">
                                        <button type="button" class="btn btn--icon btn--sm btn--base" data-bs-toggle="dropdown" aria-expanded="false">
                                            <i class="ti ti-dots-vertical"></i>
                                        </button>
                                        <ul class="dropdown-menu">
                                            @can('edit dps plan')
                                                <li>
                                                    <button type="button" class="dropdown-item btn-edit" data-action="{{ route('admin.dps.update', ['depositPensionSchemePlan' => $dpsPlan]) }}" data-dps_plan="{{ $dpsPlan }}">
                                                        <span class="dropdown-icon"><i class="ti ti-edit text--base"></i></span> @lang('Edit Plan')
                                                    </button>
                                                </li>
                                            @endcan

                                            @can('change dps plan status')
                                                @if($dpsPlan->status)
                                                    <li>
                                                        <button type="button" class="dropdown-item decisionBtn" data-action="{{ route('admin.dps.status', $dpsPlan->id) }}" data-question="@lang('Are you sure you want to inactive this plan?')">
                                                            <span class="dropdown-icon"><i class="ti ti-circle-x text--warning"></i></span> @lang('Inactive Plan')
                                                        </button>
                                                    </li>
                                                @else
                                                    <li>
                                                        <button type="button" class="dropdown-item decisionBtn" data-action="{{ route('admin.dps.status', $dpsPlan->id) }}" data-question="@lang('Are you sure you want to active this plan?')">
                                                            <span class="dropdown-icon"><i class="ti ti-circle-check text--success"></i></span> @lang('Active Plan')
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

        @if ($dpsPlans->hasPages())
            {{ paginateLinks($dpsPlans) }}
        @endif
    </div>

    {{-- Create Modal --}}
    <div class="custom--modal modal fade" id="createModal" tabindex="-1" aria-labelledby="createModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h2 class="modal-title" id="createModalLabel">@lang('Create New Plan')</h2>
                    <button type="button" class="btn btn--sm btn--icon btn-outline--secondary modal-close" data-bs-dismiss="modal" aria-label="Close">
                        <i class="ti ti-x"></i>
                    </button>
                </div>
                <form action="" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="row g-4">
                            <div class="col-lg-6">
                                <label class="form--label required">@lang('Name')</label>
                                <input type="text" class="form--control" name="name" required>
                            </div>
                            <div class="col-lg-6">
                                <label class="form--label required">@lang('Icon')</label>
                                <div class="input--group">
                                    <input type="text" class="form--control iconPicker" name="icon" required autocomplete="off">
                                    <span class="input-group-text input-group-addon"></span>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <label class="form--label required">@lang('Per Installment')</label>
                                <div class="input--group">
                                    <input type="number" step="any" min="0" class="form--control" id="perInstallment" name="per_installment" required>
                                    <span class="input-group-text">{{ __($setting->site_cur) }}</span>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <label class="form--label required">@lang('Installment Interval')</label>
                                <div class="input--group">
                                    <input type="number" min="0" class="form--control" name="installment_interval" required>
                                    <span class="input-group-text">@lang('Days')</span>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <label class="form--label required">@lang('Total Installment')</label>
                                <input type="number" min="0" class="form--control" id="totalInstallment" name="total_installment" required>
                            </div>
                            <div class="col-lg-6">
                                <label class="form--label required">@lang('Total Deposit Amount')</label>
                                <div class="input--group">
                                    <input type="number" step="any" min="0" class="form--control" id="totalDepositAmount" readonly>
                                    <span class="input-group-text">{{ __($setting->site_cur) }}</span>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <label class="form--label required">@lang('Interest Rate')</label>
                                <div class="input--group">
                                    <input type="number" step="any" min="0" class="form--control" id="interestRate" name="interest_rate" required>
                                    <span class="input-group-text">%</span>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <label class="form--label required">@lang('Profit Amount')</label>
                                <div class="input--group">
                                    <input type="number" step="any" min="0" class="form--control" id="profitAmount" readonly>
                                    <span class="input-group-text">{{ __($setting->site_cur) }}</span>
                                </div>
                            </div>
                            <div class="col-12">
                                <label class="form--label required">@lang('Maturity Amount')</label>
                                <div class="input--group">
                                    <input type="number" step="any" min="0" class="form--control" id="maturityAmount" readonly>
                                    <span class="input-group-text">{{ __($setting->site_cur) }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-body border-top">
                        <div class="row g-4">
                            <div class="col-12">
                                <p class="text-center fw-bold">
                                    @lang('Delayed Installment Charge') <span title="@lang('This charge will be applied to each delayed installment. The total amount of charges will be deducted from the maturity amount.')"><i class="ti ti-info-circle fz-3"></i></span>
                                </p>
                            </div>
                            <div class="col-12">
                                <label class="form--label required">@lang('Delay Duration')</label>
                                <div class="input--group">
                                    <input type="number" min="0" class="form--control" name="delay_duration" required>
                                    <span class="input-group-text">@lang('Days')</span>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <label class="form--label">@lang('Fixed Charge')</label>
                                <div class="input--group">
                                    <input type="number" step="any" min="0" class="form--control" name="fixed_charge">
                                    <span class="input-group-text">{{ __($setting->site_cur) }}</span>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <label class="form--label">@lang('Percentage Charge')</label>
                                <div class="input--group">
                                    <input type="number" step="any" min="0" class="form--control" name="percentage_charge">
                                    <span class="input-group-text">%</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer gap-2">
                        <button type="button" class="btn btn--sm btn-outline--base" data-bs-dismiss="modal">@lang('Close')</button>
                        <button type="submit" class="btn btn--sm btn--base">@lang('Save')</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Edit Modal --}}
    <div class="custom--modal modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h2 class="modal-title" id="editModalLabel">@lang('Edit Plan')</h2>
                    <button type="button" class="btn btn--sm btn--icon btn-outline--secondary modal-close" data-bs-dismiss="modal" aria-label="Close">
                        <i class="ti ti-x"></i>
                    </button>
                </div>
                <form action="" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="row g-4">
                            <div class="col-lg-6">
                                <label class="form--label required">@lang('Name')</label>
                                <input type="text" class="form--control" id="editName" name="name" required>
                            </div>
                            <div class="col-lg-6">
                                <label class="form--label required">@lang('Icon')</label>
                                <div class="input--group">
                                    <input type="text" class="form--control iconPicker" id="editIcon" name="icon" required autocomplete="off">
                                    <span class="input-group-text input-group-addon" id="editIconAddon"></span>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <label class="form--label required">@lang('Per Installment')</label>
                                <div class="input--group">
                                    <input type="number" step="any" min="0" class="form--control" id="editPerInstallment" name="per_installment" required>
                                    <span class="input-group-text">{{ __($setting->site_cur) }}</span>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <label class="form--label required">@lang('Installment Interval')</label>
                                <div class="input--group">
                                    <input type="number" min="0" class="form--control" id="editInstallmentInterval" name="installment_interval" required>
                                    <span class="input-group-text">@lang('Days')</span>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <label class="form--label required">@lang('Total Installment')</label>
                                <input type="number" min="0" class="form--control" id="editTotalInstallment" name="total_installment" required>
                            </div>
                            <div class="col-lg-6">
                                <label class="form--label required">@lang('Total Deposit Amount')</label>
                                <div class="input--group">
                                    <input type="number" step="any" min="0" class="form--control" id="editTotalDepositAmount" readonly>
                                    <span class="input-group-text">{{ __($setting->site_cur) }}</span>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <label class="form--label required">@lang('Interest Rate')</label>
                                <div class="input--group">
                                    <input type="number" step="any" min="0" class="form--control" id="editInterestRate" name="interest_rate" required>
                                    <span class="input-group-text">%</span>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <label class="form--label required">@lang('Profit Amount')</label>
                                <div class="input--group">
                                    <input type="number" step="any" min="0" class="form--control" id="editProfitAmount" readonly>
                                    <span class="input-group-text">{{ __($setting->site_cur) }}</span>
                                </div>
                            </div>
                            <div class="col-12">
                                <label class="form--label required">@lang('Maturity Amount')</label>
                                <div class="input--group">
                                    <input type="number" step="any" min="0" class="form--control" id="editMaturityAmount" readonly>
                                    <span class="input-group-text">{{ __($setting->site_cur) }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-body border-top">
                        <div class="row g-4">
                            <div class="col-12">
                                <p class="text-center fw-bold">
                                    @lang('Delayed Installment Charge') <span title="@lang('This charge will be applied to each delayed installment. The total amount of charges will be deducted from the maturity amount.')"><i class="ti ti-info-circle fz-3"></i></span>
                                </p>
                            </div>
                            <div class="col-12">
                                <label class="form--label required">@lang('Delay Duration')</label>
                                <div class="input--group">
                                    <input type="number" min="0" class="form--control" id="editDelayDuration" name="delay_duration" required>
                                    <span class="input-group-text">@lang('Days')</span>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <label class="form--label">@lang('Fixed Charge')</label>
                                <div class="input--group">
                                    <input type="number" step="any" min="0" class="form--control" id="editFixedCharge" name="fixed_charge">
                                    <span class="input-group-text">{{ __($setting->site_cur) }}</span>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <label class="form--label">@lang('Percentage Charge')</label>
                                <div class="input--group">
                                    <input type="number" step="any" min="0" class="form--control" id="editPercentageCharge" name="percentage_charge">
                                    <span class="input-group-text">%</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer gap-2">
                        <button type="button" class="btn btn--sm btn-outline--base" data-bs-dismiss="modal">@lang('Close')</button>
                        <button type="submit" class="btn btn--sm btn--base">@lang('Update')</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <x-decisionModal />
@endsection

@push('breadcrumb')
    <x-searchForm placeholder="Name" />

    @can('create dps plan')
        <button type="button" class="btn btn--sm btn--base" data-bs-toggle="modal" data-bs-target="#createModal">
            <i class="ti ti-circle-plus"></i> @lang('Create New')
        </button>
    @endcan
@endpush

@push('page-style-lib')
    <link rel="stylesheet" href="{{ asset('assets/admin/css/page/iconpicker.css') }}">
@endpush

@push('page-style')
    <style>
        .iconpicker-popover.fade {
            opacity: 1;
        }
    </style>
@endpush

@push('page-script-lib')
    <script src="{{ asset('assets/admin/js/page/iconpicker.js') }}"></script>
@endpush

@push('page-script')
    <script>
        (function ($) {
            'use strict'

            $(function () {
                let fractionDigit = parseInt(`{{ $fractionDigit }}`);

                $('.iconPicker').iconpicker().on('iconpickerSelected', function (event) {
                    $(this).closest('.input--group').find('.iconpicker-input').val(`<i class="${event.iconpickerValue}"></i>`)
                })

                function calculateValues(perInstallment, totalInstallment, interestRate, selectors) {
                    const depositAmount = perInstallment * totalInstallment
                    $(selectors.totalDeposit).val(depositAmount.toFixed(fractionDigit))

                    const profitAmount = (interestRate * depositAmount) / 100
                    $(selectors.profit).val(profitAmount.toFixed(fractionDigit))

                    const maturityAmount = depositAmount + profitAmount
                    $(selectors.maturity).val(maturityAmount.toFixed(fractionDigit))
                }

                function handleCalculation(event) {
                    const perInstallment = parseFloat($(event.data.perInstallment).val()) || 0
                    const totalInstallment = parseFloat($(event.data.totalInstallment).val()) || 0
                    const interestRate = parseFloat($(event.data.interestRate).val()) || 0

                    calculateValues(perInstallment, totalInstallment, interestRate, event.data.selectors)
                }

                function toPascalCase(str) {
                    return str
                        .toLowerCase()
                        .split('_')
                        .map(word => word.charAt(0).toUpperCase() + word.slice(1))
                        .join('')
                }

                // create calculation
                const mainSelectors = {
                    perInstallment: '#perInstallment',
                    totalInstallment: '#totalInstallment',
                    interestRate: '#interestRate',
                    selectors: {
                        totalDeposit: '#totalDepositAmount',
                        profit: '#profitAmount',
                        maturity: '#maturityAmount'
                    }
                }

                $(`${mainSelectors.perInstallment}, ${mainSelectors.totalInstallment}, ${mainSelectors.interestRate}`)
                    .on('keyup', mainSelectors, handleCalculation)

                // edit action
                $('.btn-edit').on('click', function () {
                    let data = $(this).data()
                    let editModal = $('#editModal')

                    editModal.find('form').attr('action', data.action)

                    let dpsPlan = data.dps_plan

                    for (const property in dpsPlan) {
                        let value = dpsPlan[property]

                        if (
                            ['per_installment', 'total_deposit_amount', 'interest_rate', 'profit_amount',
                            'maturity_amount', 'fixed_charge', 'percentage_charge'].includes(property)
                        ) {
                            value = parseFloat(value).toFixed(fractionDigit)
                        }

                        let pascalCase = toPascalCase(property)
                        editModal.find(`input[id="edit${pascalCase}"]`).val(value)

                        if (property === 'icon') $('#editIconAddon').html(value)
                    }

                    editModal.modal('show')
                })

                // edit calculation
                const editSelectors = {
                    perInstallment: '#editPerInstallment',
                    totalInstallment: '#editTotalInstallment',
                    interestRate: '#editInterestRate',
                    selectors: {
                        totalDeposit: '#editTotalDepositAmount',
                        profit: '#editProfitAmount',
                        maturity: '#editMaturityAmount'
                    }
                }

                $(`${editSelectors.perInstallment}, ${editSelectors.totalInstallment}, ${editSelectors.interestRate}`)
                    .on('keyup', editSelectors, handleCalculation)
            })
        })(jQuery)
    </script>
@endpush
