@extends('admin.layouts.master')

@section('master')
    <div class="col-12">
        <form action="{{ route('admin.loan.plans.update', $plan) }}" method="post" class="row g-4">
            @csrf
            <div class="col-12">
                <div class="custom--card">
                    <div class="card-body">
                        <div class="row g-lg-4 g-3">
                            <div class="col-xl-3 col-sm-6">
                                <label class="form--label required">@lang('Name')</label>
                                <input type="text" class="form--control" name="name" value="{{ old('name', $plan->name) }}" required>
                            </div>
                            <div class="col-xl-3 col-sm-6">
                                <label class="form--label required">@lang('Icon')</label>
                                <div class="input--group">
                                    <input type="text" class="form--control iconPicker" name="icon" value="{{ old('icon', $plan->icon) }}" required autocomplete="off">
                                    <span class="input-group-text input-group-addon">
                                        @php echo old('icon', $plan->icon) @endphp
                                    </span>
                                </div>
                            </div>
                            <div class="col-xl-3 col-lg-4 col-sm-6">
                                <label class="form--label required">@lang('Minimum Amount')</label>
                                <div class="input--group">
                                    <input type="number" step="any" min="0" class="form--control" name="minimum_amount" value="{{ old('minimum_amount', getAmount($plan->minimum_amount)) }}" required>
                                    <span class="input-group-text">{{ $setting->site_cur }}</span>
                                </div>
                            </div>
                            <div class="col-xl-3 col-lg-4 col-sm-6">
                                <label class="form--label required">@lang('Maximum Amount')</label>
                                <div class="input--group">
                                    <input type="number" step="any" min="0" class="form--control" name="maximum_amount" value="{{ old('maximum_amount', getAmount($plan->maximum_amount)) }}" required>
                                    <span class="input-group-text">{{ $setting->site_cur }}</span>
                                </div>
                            </div>
                            <div class="col-xl-3 col-lg-4 col-sm-6">
                                <label class="form--label required">@lang('Installment Rate')</label>
                                <div class="input--group">
                                    <input type="number" step="any" min="0" class="form--control" id="installmentRate" name="installment_rate" value="{{ old('installment_rate', getAmount($plan->installment_rate)) }}" required>
                                    <span class="input-group-text">%</span>
                                </div>
                            </div>
                            <div class="col-xl-3 col-lg-4 col-sm-6">
                                <label class="form--label required">@lang('Installment Interval')</label>
                                <div class="input--group">
                                    <input type="number" min="0" class="form--control" name="installment_interval" value="{{ old('installment_interval', $plan->installment_interval) }}" required>
                                    <span class="input-group-text">@lang('Days')</span>
                                </div>
                            </div>
                            <div class="col-xl-3 col-lg-4 col-sm-6">
                                <label class="form--label required">@lang('Total Installments')</label>
                                <div class="input--group">
                                    <input type="number" min="0" class="form--control" id="totalInstallments" name="total_installments" value="{{ old('total_installments', $plan->total_installments) }}" required>
                                    <span class="input-group-text">@lang('Times')</span>
                                </div>
                            </div>
                            <div class="col-xl-3 col-lg-4 col-sm-6">
                                <label class="form--label">{{ __($setting->site_name) . trans('\'s Profit') }}</label>
                                <div class="input--group bank-profit-input">
                                    <input type="number" class="form--control" id="bankProfit" readonly>
                                    <span class="input-group-text">%</span>
                                </div>
                            </div>
                            <div class="col-12">
                                <label class="form--label">@lang('Instruction')</label>
                                <textarea class="form--control ck-editor" name="instruction">{{ old('instruction', $plan->instruction) }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12">
                <div class="custom--card">
                    <div class="card-header">
                        <h3 class="title">
                            @lang('Delayed Installment Charge') <span title="@lang('A charge will be applied to each delayed installment. Users are required to pay the charge along with the installment amount.')"><i class="ti ti-info-circle fz-2"></i></span>
                        </h3>
                    </div>
                    <div class="card-body">
                        <div class="row g-lg-4 g-3">
                            <div class="col-lg-4 col-sm-6">
                                <label class="form--label required">@lang('Delay Duration')</label>
                                <div class="input--group">
                                    <input type="number" min="0" class="form--control" name="delay_duration" value="{{ old('delay_duration', $plan->delay_duration) }}" required>
                                    <span class="input-group-text">@lang('Days')</span>
                                </div>
                            </div>
                            <div class="col-lg-4 col-sm-6">
                                <label class="form--label">@lang('Fixed Charge')</label>
                                <div class="input--group">
                                    <input type="number" step="any" min="0" class="form--control" name="fixed_charge" value="{{ old('fixed_charge', getAmount($plan->fixed_charge)) }}">
                                    <span class="input-group-text">{{ __($setting->site_cur) }}</span>
                                </div>
                            </div>
                            <div class="col-lg-4 col-sm-6">
                                <label class="form--label">@lang('Percentage Charge')</label>
                                <div class="input--group">
                                    <input type="number" step="any" min="0" class="form--control" name="percentage_charge" value="{{ old('percentage_charge', getAmount($plan->percentage_charge)) }}">
                                    <span class="input-group-text">%</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            @include('admin.partials.formData')
        </form>
    </div>

    <x-formGenerator />
@endsection

@push('breadcrumb')
    <a href="{{ route('admin.loan.plans') }}" class="btn btn--sm btn--base">
        <i class="ti ti-circle-arrow-left"></i> @lang('Back')
    </a>
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
    <script src="{{ asset('assets/admin/js/page/ckEditor.js') }}"></script>
@endpush

@push('page-script')
    <script>
        (function ($) {
            'use strict'

            $(function () {
                function calculateBankProfit() {
                    let installmentRate = Number($('#installmentRate').val()) || 0
                    let totalInstallments = Number($('#totalInstallments').val()) || 0

                    if (installmentRate > 0 && totalInstallments > 0) {
                        let bankProfit = (installmentRate * totalInstallments) - 100

                        if (bankProfit <= 0) {
                            $('.bank-profit-input, .bank-profit-input *').addClass('border--danger')
                        } else {
                            $('.bank-profit-input, .bank-profit-input *').removeClass('border--danger')
                        }

                        $('#bankProfit').val(bankProfit.toFixed(2))
                    } else {
                        $('.bank-profit-input, .bank-profit-input *').removeClass('border--danger')
                        $('#bankProfit').val('')
                    }
                }

                $('.iconPicker').iconpicker().on('iconpickerSelected', function (event) {
                    $(this).closest('.input--group').find('.iconpicker-input').val(`<i class="${event.iconpickerValue}"></i>`)
                })

                ClassicEditor
                    .create(document.querySelector('.ck-editor'), {})
                    .then(editor => {
                        window.editor = editor
                    })
                    .catch(error => {
                        console.error(error)
                    })

                $('#installmentRate, #totalInstallments').on('input', calculateBankProfit)

                calculateBankProfit()
            })
        })(jQuery)
    </script>
@endpush
