@extends("{$activeTheme}layouts.auth")

@section('auth')
    <div class="row g-4 justify-content-center">
        @include("{$activeTheme}partials.balanceAndCharges")

        <div class="col-lg-6 col-md-8 col-sm-10">
            <div class="custom--card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="title">@lang('Deposit Money To Your Account')</h3>
                    <a href="/user/deposit-plan/create" class="btn btn-sm btn--base">
                        @lang('Create Plan')
                    </a>
                </div>
                <div class="card-body">
                    <form action="{{ route('user.deposit.insert') }}" method="post" class="row g-4">
                        @csrf
                        <input type="hidden" value="NGN" name="currency">

                        <!-- Plan Selection -->
                        <div class="col-12">
                            <label class="form--label">@lang('Select Deposit Plan')</label>
                            {{-- <select class="form--control form-select select-2" name="deposit_plan_id"> --}}
                            <select class="form-control" name="deposit_plan_id">
                                <option value="" data-amount="0">@lang('No Plan')</option>
                                @foreach ($depositPlans as $plan)
                                    <option value="{{ $plan->id }}" data-amount="{{ $plan->daily_amount }}">
                                        {{ $plan->plan_name }} ({{ number_format($plan->daily_amount, 2) }}
                                        {{ $plan->currency }}/day)
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Gateway -->
                        <div class="col-12">
                            <label class="form--label required">@lang('Gateway')</label>
                            {{-- <select class="form--control form-select select-2" name="gateway" required> --}}
                            <select class="form-control" name="gateway" required>
                                <option value="0" disabled>@lang('Select Gateway')</option>
                                {{-- <option value="cash" @selected(old('gateway', 'cash') == 'cash')>@lang('Cash')</option> --}}
                                @foreach ($gatewayCurrencies as $data)
                                    <option value="{{ $data->method_code }}" @selected(old('gateway') == $data->method_code)
                                        data-gateway="{{ $data }}">
                                        {{ __($data->name) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Amount -->
                        <div class="col-12">
                            <label class="form--label">@lang('Amount')</label>
                            <div class="input--group">
                                <input type="number" step="any" min="0" name="amount" class="form--control"
                                    placeholder="@lang('Enter Amount')" value="{{ old('amount') }}">
                                <span class="input-group-text">{{ $setting->site_cur }}</span>
                            </div>
                        </div>

                        <!-- Charges Table -->
                        <div class="col-12">
                            <table class="table table-borderless table-light no-shadow">
                                <tbody>
                                    <tr>
                                        <td><span class="fw-bold">@lang('Limit'):</span></td>
                                        <td><span class="min">0</span> {{ __($setting->site_cur) }} - <span
                                                class="max">0</span> {{ __($setting->site_cur) }}</td>
                                    </tr>
                                    <tr>
                                        <td><span class="fw-bold">@lang('Charge'):</span></td>
                                        <td><span class="charge">0</span> {{ __($setting->site_cur) }}</td>
                                    </tr>
                                    <tr>
                                        <td><span class="fw-bold">@lang('Payable'):</span></td>
                                        <td><span class="payable">0</span> {{ __($setting->site_cur) }}</td>
                                    </tr>
                                    <tr>
                                        <td><span class="fw-bold">@lang('Conversion Rate'):</span></td>
                                        <td>1 {{ __($setting->site_cur) }} = <span class="rate">1</span> <span
                                                class="method-currency">{{ __($setting->site_cur) }}</span></td>
                                    </tr>
                                    <tr>
                                        <td><span class="fw-bold">@lang('In') <span
                                                    class="method-currency">{{ __($setting->site_cur) }}</span>:</span>
                                        </td>
                                        <td><span class="in-method-cur">0</span></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <div class="col-12">
                            <button type="submit" class="btn btn--base w-100">@lang('Deposit Now')</button>
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
    <script src="{{ asset('assets/universal/js/jquery.min.js') }}"></script>
    <script src="{{ asset('assets/universal/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('assets/universal/js/select2.min.js') }}"></script>
@endpush

@push('page-script')
    <script>
        (function($) {
            'use strict';
            $('.select-2').each(function() {
                $(this).select2({
                    dropdownParent: $(document.body)
                });
            });

            function reset() {
                $('#fixedCharge').text('0.00');
                $('#percentageCharge').text('0');
                $('.min').text('0');
                $('.max').text('0');
                $('.charge').text('0');
                $('.payable').text('0');
                $('.rate').text('1');
                $('.method-currency').text('{{ $setting->site_cur }}');
                $('.in-method-cur').text('0');
            }

            // Handle deposit plan selection
            $('[name=deposit_plan_id]').on('change', function() {
                let amountInput = $('[name=amount]');
                let selectedPlan = $(this).find('option:selected');
                let dailyAmount = parseFloat(selectedPlan.data('amount')) || 0;

                if (dailyAmount > 0) {
                    amountInput.val(dailyAmount).prop('readonly', true);
                } else {
                    amountInput.val('').prop('readonly', false);
                }
                $('[name=gateway]').trigger('change'); // Trigger gateway change to update charges
            });

            // Handle gateway selection
            $('[name=gateway]').on('change', function() {
                if (!$(this).val()) {
                    reset();
                    return false;
                }
                let resource = $(this).find('option:selected').data('gateway');
                let fixed_charge = parseFloat(resource.fixed_charge || 0);
                let percent_charge = parseInt(resource.percent_charge || 0);
                let rate = parseFloat(resource.rate || 1);
                let toFixedDigit = resource.method && resource.method.crypto ? 8 : 2;
                let amount = parseFloat($('[name=amount]').val()) || 0;

                if (!amount) {
                    reset();
                    return false;
                }

                $('#fixedCharge').text(fixed_charge.toFixed(2));
                $('#percentageCharge').text(percent_charge);
                $('.min').text(parseFloat(resource.min_amount || 0).toFixed(2));
                $('.max').text(parseFloat(resource.max_amount || 0).toFixed(2));
                let charge = parseFloat(fixed_charge + (amount * percent_charge / 100));
                $('.charge').text(charge.toFixed(2));
                let payable = amount + charge;
                $('.payable').text(payable.toFixed(2));
                let finalAmount = payable * rate;
                $('.rate').text(rate);
                $('.method-currency').text(resource.currency || '{{ $setting->site_cur }}');
                $('.in-method-cur').text(finalAmount.toFixed(toFixedDigit));
                $('[name=currency]').val(resource.currency || '{{ $setting->site_cur }}');
            });

            // Trigger gateway change on amount input
            $('[name=amount]').on('input', function() {
                if (!$(this).prop('readonly')) { // Only trigger if not readonly
                    $('[name=gateway]').trigger('change');
                }
            });
        })(jQuery);
    </script>
@endpush
