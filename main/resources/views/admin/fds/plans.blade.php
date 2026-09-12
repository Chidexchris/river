@extends('admin.layouts.master')

@section('master')
    <div class="col-12">
        <div class="table-responsive scroll">
            <table class="table table--striped table-borderless table--responsive--sm">
                <thead>
                    <tr>
                        <th>@lang('Name')</th>
                        <th>@lang('Interest Rate')</th>
                        <th>@lang('Interest Payout Interval')</th>
                        <th>@lang('Lock-In Period')</th>
                        <th>@lang('Minimum Amount')</th>
                        <th>@lang('Maximum Amount')</th>
                        <th>@lang('Status')</th>

                        @canany(['edit fds plan', 'change fds plan status'])
                            <th>@lang('Action')</th>
                        @endcanany
                    </tr>
                </thead>
                <tbody>
                    @forelse ($fdsPlans as $fdsPlan)
                        <tr>
                            <td>{{ __($fdsPlan->name) }}</td>
                            <td>{{ showAmount($fdsPlan->interest_rate) . '%' }}</td>
                            <td>{{ $fdsPlan->interest_payout_interval . ' ' . trans('Days') }}</td>
                            <td>{{ $fdsPlan->lock_in_period . ' ' . trans('Days') }}</td>
                            <td>{{ $setting->cur_sym . showAmount($fdsPlan->minimum_amount) }}</td>
                            <td>{{ $setting->cur_sym . showAmount($fdsPlan->maximum_amount) }}</td>
                            <td>
                                @php echo $fdsPlan->status_badge @endphp
                            </td>

                            @canany(['edit fds plan', 'change fds plan status'])
                                <td>
                                    <div class="custom--dropdown">
                                        <button type="button" class="btn btn--icon btn--sm btn--base" data-bs-toggle="dropdown" aria-expanded="false">
                                            <i class="ti ti-dots-vertical"></i>
                                        </button>
                                        <ul class="dropdown-menu">
                                            @can('edit fds plan')
                                                <li>
                                                    <button type="button" class="dropdown-item btn-edit" data-action="{{ route('admin.fds.update', ['fixedDepositSchemePlan' => $fdsPlan]) }}" data-fds_plan="{{ $fdsPlan }}">
                                                        <span class="dropdown-icon"><i class="ti ti-edit text--base"></i></span> @lang('Edit Plan')
                                                    </button>
                                                </li>
                                            @endcan

                                            @can('change fds plan status')
                                                @if($fdsPlan->status)
                                                    <li>
                                                        <button type="button" class="dropdown-item decisionBtn" data-action="{{ route('admin.fds.status', $fdsPlan->id) }}" data-question="@lang('Are you sure you want to inactive this plan?')">
                                                            <span class="dropdown-icon"><i class="ti ti-circle-x text--warning"></i></span> @lang('Inactive Plan')
                                                        </button>
                                                    </li>
                                                @else
                                                    <li>
                                                        <button type="button" class="dropdown-item decisionBtn" data-action="{{ route('admin.fds.status', $fdsPlan->id) }}" data-question="@lang('Are you sure you want to active this plan?')">
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

        @if ($fdsPlans->hasPages())
            {{ paginateLinks($fdsPlans) }}
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
                                <label class="form--label required">@lang('Interest Rate')</label>
                                <div class="input--group">
                                    <input type="number" step="any" min="0" class="form--control" id="interestRate" name="interest_rate" required>
                                    <span class="input-group-text">%</span>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <label class="form--label required">@lang('Interest Payout Interval')</label>
                                <div class="input--group">
                                    <input type="number" min="0" class="form--control" id="interestPayoutInterval" name="interest_payout_interval" required>
                                    <span class="input-group-text">@lang('Days')</span>
                                </div>
                            </div>
                            <div class="col-12">
                                <label class="form--label required">@lang('Lock-In Period')</label>
                                <div class="input--group">
                                    <input type="number" min="0" class="form--control" name="lock_in_period" required>
                                    <span class="input-group-text">@lang('Days')</span>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <label class="form--label required">@lang('Minimum Amount')</label>
                                <div class="input--group">
                                    <input type="number" step="any" min="0" class="form--control" id="minimumAmount" name="minimum_amount" required>
                                    <span class="input-group-text">{{ __($setting->site_cur) }}</span>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <label class="form--label required">@lang('Maximum Amount')</label>
                                <div class="input--group">
                                    <input type="number" step="any" min="0" class="form--control" id="maximumAmount" name="maximum_amount" required>
                                    <span class="input-group-text">{{ __($setting->site_cur) }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-body border-top d-none" id="profitAmountMessage">
                        <div class="row">
                            <div class="col-12">
                                <p>
                                    <span><i class="ti ti-info-circle charge-info-icon"></i></span> @lang('Users will receive a payout of') <span class="fw-bold">{{ $setting->cur_sym }}<span id="minProfitAmount">0.00</span></span> @lang('to') <span class="fw-bold">{{ $setting->cur_sym }}<span id="maxProfitAmount">0.00</span></span> @lang('every') <span class="fw-bold"><span id="interestInterval">0</span> @lang('days')</span>@lang(', depending on the deposited amount.')
                                </p>
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
                                <label class="form--label required">@lang('Interest Rate')</label>
                                <div class="input--group">
                                    <input type="number" step="any" min="0" class="form--control" id="editInterestRate" name="interest_rate" required>
                                    <span class="input-group-text">%</span>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <label class="form--label required">@lang('Interest Payout Interval')</label>
                                <div class="input--group">
                                    <input type="number" min="0" class="form--control" id="editInterestPayoutInterval" name="interest_payout_interval" required>
                                    <span class="input-group-text">@lang('Days')</span>
                                </div>
                            </div>
                            <div class="col-12">
                                <label class="form--label required">@lang('Lock-In Period')</label>
                                <div class="input--group">
                                    <input type="number" min="0" class="form--control" id="editLockInPeriod" name="lock_in_period" required>
                                    <span class="input-group-text">@lang('Days')</span>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <label class="form--label required">@lang('Minimum Amount')</label>
                                <div class="input--group">
                                    <input type="number" step="any" min="0" class="form--control" id="editMinimumAmount" name="minimum_amount" required>
                                    <span class="input-group-text">{{ __($setting->site_cur) }}</span>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <label class="form--label required">@lang('Maximum Amount')</label>
                                <div class="input--group">
                                    <input type="number" step="any" min="0" class="form--control" id="editMaximumAmount" name="maximum_amount" required>
                                    <span class="input-group-text">{{ __($setting->site_cur) }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-body border-top d-none" id="editProfitAmountMessage">
                        <div class="row">
                            <div class="col-12">
                                <p>
                                    <span><i class="ti ti-info-circle charge-info-icon"></i></span> @lang('Users will receive a payout of') <span class="fw-bold">{{ $setting->cur_sym }}<span id="editMinProfitAmount">0.00</span></span> @lang('to') <span class="fw-bold">{{ $setting->cur_sym }}<span id="editMaxProfitAmount">0.00</span></span> @lang('every') <span class="fw-bold"><span id="editInterestInterval">0</span> @lang('days')</span>@lang(', depending on the deposited amount.')
                                </p>
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

    @can('create fds plan')
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

        .charge-info-icon {
            font-size: 1.3em;
            transform: translateY(10%);
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
                let fractionDigit = parseInt(`{{ $fractionDigit }}`)

                $('.iconPicker').iconpicker().on('iconpickerSelected', function (event) {
                    $(this).closest('.input--group').find('.iconpicker-input').val(`<i class="${event.iconpickerValue}"></i>`)
                })

                function calculateAndToggleMessage(interestRate, interestPayoutInterval, minAmount, maxAmount, selectors) {
                    const minProfitAmount = (interestRate * minAmount) / 100
                    $(selectors.minProfit).text(minProfitAmount.toFixed(fractionDigit))

                    const maxProfitAmount = (interestRate * maxAmount) / 100
                    $(selectors.maxProfit).text(maxProfitAmount.toFixed(fractionDigit))

                    $(selectors.interval).text(interestPayoutInterval)

                    if (interestRate && interestPayoutInterval && minProfitAmount && maxProfitAmount) {
                        $(selectors.message).removeClass('d-none')
                    } else {
                        $(selectors.message).addClass('d-none')
                    }
                }

                function handleCalculation(event) {
                    const interestRate = parseFloat($(event.data.interestRate).val()) || 0
                    const interestPayoutInterval = parseInt($(event.data.interestPayoutInterval).val()) || 0
                    const minAmount = parseFloat($(event.data.minAmount).val()) || 0
                    const maxAmount = parseFloat($(event.data.maxAmount).val()) || 0

                    calculateAndToggleMessage(interestRate, interestPayoutInterval, minAmount, maxAmount, event.data.selectors)
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
                    interestRate: '#interestRate',
                    interestPayoutInterval: '#interestPayoutInterval',
                    minAmount: '#minimumAmount',
                    maxAmount: '#maximumAmount',
                    selectors: {
                        message: '#profitAmountMessage',
                        minProfit: '#minProfitAmount',
                        maxProfit: '#maxProfitAmount',
                        interval: '#interestInterval'
                    }
                }

                $(`${mainSelectors.interestRate}, ${mainSelectors.interestPayoutInterval}, ${mainSelectors.minAmount}, ${mainSelectors.maxAmount}`)
                    .on('keyup', mainSelectors, handleCalculation)

                // edit action
                $('.btn-edit').on('click', function () {
                    let data = $(this).data()
                    let editModal = $('#editModal')

                    editModal.find('form').attr('action', data.action)

                    let fdsPlan = data.fds_plan

                    for (const property in fdsPlan) {
                        let value = fdsPlan[property]

                        if (['interest_rate', 'minimum_amount', 'maximum_amount'].includes(property)) {
                            value = parseFloat(value).toFixed(fractionDigit)
                        }

                        let pascalCase = toPascalCase(property)
                        editModal.find(`input[id="edit${pascalCase}"]`).val(value)

                        if (property === 'icon') $('#editIconAddon').html(value)
                    }

                    const selectors = {
                        message: '#editProfitAmountMessage',
                        minProfit: '#editMinProfitAmount',
                        maxProfit: '#editMaxProfitAmount',
                        interval: '#editInterestInterval'
                    }

                    const interestRate = parseFloat(fdsPlan.interest_rate)
                    const interestPayoutInterval = parseInt(fdsPlan.interest_payout_interval)
                    const minAmount = parseFloat(fdsPlan.minimum_amount)
                    const maxAmount = parseFloat(fdsPlan.maximum_amount)

                    calculateAndToggleMessage(interestRate, interestPayoutInterval, minAmount, maxAmount, selectors)

                    editModal.modal('show')
                })

                // edit calculation
                const editSelectors = {
                    interestRate: '#editInterestRate',
                    interestPayoutInterval: '#editInterestPayoutInterval',
                    minAmount: '#editMinimumAmount',
                    maxAmount: '#editMaximumAmount',
                    selectors: {
                        message: '#editProfitAmountMessage',
                        minProfit: '#editMinProfitAmount',
                        maxProfit: '#editMaxProfitAmount',
                        interval: '#editInterestInterval'
                    }
                }

                $(`${editSelectors.interestRate}, ${editSelectors.interestPayoutInterval}, ${editSelectors.minAmount}, ${editSelectors.maxAmount}`)
                    .on('keyup', editSelectors, handleCalculation)
            })
        })(jQuery)
    </script>
@endpush
