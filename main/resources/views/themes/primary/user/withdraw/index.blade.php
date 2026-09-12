@extends("{$activeTheme}layouts.auth")

@section('auth')
    <div class="row g-4 justify-content-center">
        @include("{$activeTheme}partials.balanceAndCharges")

        <div class="col-lg-6 col-md-8 col-sm-10">
            <div class="custom--card">
                <div class="card-header">
                    <h3 class="title">@lang('Withdraw Money From Your Account')</h3>
                </div>
                <div class="card-body">
                    <form action="" method="post" class="row g-4">
                        @csrf
                        <div class="col-12">
                            <label class="form--label required">@lang('Method')</label>
                            <select class="form--control form-select select-2" name="method_id" required>
                                <option value="0" selected disabled>@lang('Select Method')</option>

                                @foreach($methods as $method)
                                    <option value="{{ $method->id }}" @selected(old('method_id') == $method?->id) data-resource="{{ $method }}">
                                        {{ __($method?->name) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-12">
                            <label class="form--label required">@lang('Amount')</label>
                            <div class="input--group">
                                <input type="number" step="any" min="0" class="form--control" name="amount" value="{{ old('amount') }}" required>
                                <span class="input-group-text">{{ __($setting->site_cur) }}</span>
                            </div>
                        </div>

                        @if($setting->sms_based_otp || $setting->email_based_otp)
                            <div class="col-12">
                                <label class="form--label required">@lang('Authorization Mode')</label>
                                <select class="form--control wide" name="authorization_mode" required>
                                    <option selected disabled>@lang('Select One')</option>

                                    @if($setting->email_based_otp)
                                        <option value="{{ ManageStatus::AUTHORIZATION_MODE_EMAIL }}" @selected(old('authorization_mode') == ManageStatus::AUTHORIZATION_MODE_EMAIL)>
                                            @lang('Email')
                                        </option>
                                    @endif

                                    @if($setting->sms_based_otp)
                                        <option value="{{ ManageStatus::AUTHORIZATION_MODE_SMS }}" @selected(old('authorization_mode') == ManageStatus::AUTHORIZATION_MODE_SMS)>
                                            @lang('SMS')
                                        </option>
                                    @endif
                                </select>
                            </div>
                        @endif

                        <div class="col-12">
                            <table class="table table-borderless table-light no-shadow">
                                <tbody>
                                    <tr>
                                        <td>
                                            <span class="fw-bold">@lang('Limit'):</span>
                                        </td>
                                        <td>
                                            <span class="min">0</span> {{ __($setting->site_cur) }} - <span class="max">0</span> {{ __($setting->site_cur) }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <span class="fw-bold">@lang('Charge'):</span>
                                        </td>
                                        <td>
                                            <span class="charge">0</span> {{ __($setting->site_cur) }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <span class="fw-bold">@lang('Receivable'):</span>
                                        </td>
                                        <td>
                                            <span class="receivable">0</span> {{ __($setting->site_cur) }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <span class="fw-bold">@lang('Conversion Rate'):</span>
                                        </td>
                                        <td>
                                            1 {{ __($setting->site_cur) }} = <span class="rate">1</span> <span class="method-currency">{{ __($setting->site_cur) }}</span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <span class="fw-bold">@lang('In') <span class="method-currency">{{ __($setting->site_cur) }}</span>:</span>
                                        </td>
                                        <td>
                                            <span class="in-method-cur">0</span>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="col-12">
                            <button type="submit" class="btn btn--base w-100">@lang('Withdraw Now')</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('page-style-lib')
    <link rel="stylesheet" href="{{ asset('assets/universal/css/select2.min.css') }}">
@endpush

@push('page-script-lib')
    <script src="{{ asset('assets/universal/js/select2.min.js') }}"></script>
@endpush

@push('page-script')
    <script>
        (function($) {
            'use strict'

            function reset() {
                $('#fixedCharge').text('0.00')
                $('#percentageCharge').text('0')
                $('.min').text('0')
                $('.max').text('0')
                $('.charge').text('0')
                $('.receivable').text('0')
                $('.rate').text('1')
                $('.method-currency').text('{{ $setting->site_cur }}')
                $('.in-method-cur').text('0')
            }

            $('[name=method_id]').on('change', function() {
                if (!$('[name=method_id]').val()) {
                    reset()

                    return false
                }

                let resource       = $('[name=method_id] option:selected').data('resource')
                let fixed_charge   = parseFloat(resource.fixed_charge)
                let percent_charge = parseInt(resource.percent_charge)
                let rate           = parseFloat(resource.rate)
                let toFixedDigit   = 2
                let amount         = parseFloat($('[name=amount]').val())

                if (!amount) {
                    reset()

                    return false
                }

                $('#fixedCharge').text(fixed_charge.toFixed(2))
                $('#percentageCharge').text(percent_charge)

                $('.min').text(parseFloat(resource.min_amount).toFixed(2))
                $('.max').text(parseFloat(resource.max_amount).toFixed(2))

                let charge = parseFloat(fixed_charge + (amount * percent_charge / 100))
                $('.charge').text(charge.toFixed(2))

                let receivable = amount - charge
                $('.receivable').text(receivable.toFixed(2))

                let finalAmount = receivable * rate

                $('.rate').text(rate)
                $('.method-currency').text(resource.currency)
                $('.in-method-cur').text(finalAmount.toFixed(toFixedDigit))
            })

            $('[name=amount]').on('input', function() {
                $('[name=method_id]').trigger('change')
            })
        })(jQuery)
    </script>
@endpush
