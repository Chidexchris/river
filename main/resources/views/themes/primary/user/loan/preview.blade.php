@extends("{$activeTheme}layouts.auth")

@section('auth')
    <div class="row g-4">
        <div class="col-lg-5">
            <div class="custom--card h-auto">
                <div class="card-header">
                    <h3 class="title">@lang('You have requested for a Loan')</h3>
                </div>
                <div class="card-body">
                    <div class="row g-4">
                        <div class="col-12">
                            <table class="table table-borderless no-shadow mb-2">
                                <tbody>
                                    <tr>
                                        <td><span class="fw-bold">@lang('Plan')</span></td>
                                        <td>{{ __($plan->name) }}</td>
                                    </tr>
                                    <tr>
                                        <td><span class="fw-bold">@lang('Your Requested Amount')</span></td>
                                        <td>{{ $setting->cur_sym . showAmount($loanAmount) }}</td>
                                    </tr>
                                    <tr>
                                        <td><span class="fw-bold">@lang('Per Installment')</span></td>
                                        <td>{{ $setting->cur_sym . showAmount($perInstallment) }}</td>
                                    </tr>
                                    <tr>
                                        <td><span class="fw-bold">@lang('Installment Interval')</span></td>
                                        <td>{{ $plan->installment_interval . ' ' . trans(\Illuminate\Support\Str::plural('Day', $plan->installment_interval)) }}</td>
                                    </tr>
                                    <tr>
                                        <td><span class="fw-bold">@lang('Total Installments')</span></td>
                                        <td>{{ $plan->total_installments }}</td>
                                    </tr>
                                    <tr>
                                        <td><span class="fw-bold">@lang('You Need To Pay')</span></td>
                                        <td class="text--base">{{ $setting->cur_sym . showAmount($totalInstallmentAmount) }}</td>
                                    </tr>
                                </tbody>
                            </table>
                            <p class="text--danger">
                                <small>* {{ trans('If an installment is delayed by') . ' ' . $plan->delay_duration . ' ' . trans('or more days, a late fee of') . ' ' . $setting->cur_sym . showAmount($lateFee) . ' ' . trans('will be applied for each day of delay.') }}</small>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-7">
            <div class="custom--card h-auto">
                <div class="card-header">
                    <h3 class="title">@lang('Loan Application Form')</h3>
                </div>
                <div class="card-body">
                    <form action="{{ route('user.loan.plan.confirm') }}" method="post" enctype="multipart/form-data" class="row g-4">
                        @csrf
                        <div class="col-12">
                            <div class="alert alert--base">
                                <span class="alert__title">@lang('Instruction')</span>
                                <div class="alert__desc small">
                                    @php echo trans($plan->instruction) @endphp
                                </div>
                            </div>
                        </div>

                        <x-phinixForm identifier="id" identifierValue="{{ $plan->form_id }}" />

                        @if($setting->sms_based_otp || $setting->email_based_otp)
                            <div class="col-12">
                                <label for="authorizationMode" class="form--label required">@lang('Authorization Mode')</label>
                                <select id="authorizationMode" class="form--control form-select select-2" data-search="false" name="authorization_mode" required>
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
                            <div class="d-flex justify-content-center gap-2">
                                <a href="{{ route('user.loan.plans') }}" class="btn btn--sm btn--secondary px-4">@lang('Cancel')</a>
                                <button type="submit" class="btn btn--sm btn--base px-4">@lang('Confirm')</button>
                            </div>
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
