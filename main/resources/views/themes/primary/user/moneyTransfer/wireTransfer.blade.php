@extends("{$activeTheme}layouts.auth")

@section('auth')
    <div class="row g-4">
        @if($wireTransferSettings->instruction)
            <div class="col-12">
                <div class="custom--card h-auto">
                    <div class="card-header">
                        <h3 class="title">@lang('Instruction')</h3>
                    </div>
                    <div class="card-body py-3">
                        @php echo $wireTransferSettings->instruction @endphp
                    </div>
                </div>
            </div>
        @endif

        <div class="col-lg-5">
            <div class="custom--card h-auto mb-4">
                <div class="card-header">
                    <h3 class="title">@lang('Transfer Information')</h3>
                </div>
                <div class="card-body">
                    <table class="table table-borderless no-shadow">
                        <tbody>
                            <tr>
                                <td><span class="fw-bold">@lang('Per Transaction Min Limit')</span></td>
                                <td>{{ $setting->cur_sym . showAmount($wireTransferSettings->per_transaction_min_amount) }}</td>
                            </tr>
                            <tr>
                                <td><span class="fw-bold">@lang('Per Transaction Max Limit')</span></td>
                                <td>{{ $setting->cur_sym . showAmount($wireTransferSettings->per_transaction_max_amount) }}</td>
                            </tr>
                            <tr>
                                <td><span class="fw-bold">@lang('Daily Transaction Max Amount')</span></td>
                                <td>{{ $setting->cur_sym . showAmount($wireTransferSettings->daily_transaction_max_amount) }}</td>
                            </tr>
                            <tr>
                                <td><span class="fw-bold">@lang('Daily Transaction Limit')</span></td>
                                <td>{{ $wireTransferSettings->daily_transaction_limit }}</td>
                            </tr>
                            <tr>
                                <td><span class="fw-bold">@lang('Monthly Transaction Max Amount')</span></td>
                                <td>{{ $setting->cur_sym . showAmount($wireTransferSettings->monthly_transaction_max_amount) }}</td>
                            </tr>
                            <tr>
                                <td><span class="fw-bold">@lang('Monthly Transaction Limit')</span></td>
                                <td>{{ $wireTransferSettings->monthly_transaction_limit }}</td>
                            </tr>
                        </tbody>
                    </table>
                    <p class="mt-1">
                        <small class="text--danger">* @lang('Charge'): {{ $setting->cur_sym . showAmount($wireTransferSettings->fixed_charge) . ' + ' . showAmount($wireTransferSettings->percentage_charge) . '%' }}</small>
                    </p>
                </div>
            </div>
        </div>
        <div class="col-lg-7">
            <div class="custom--card h-auto">
                <div class="card-body">
                    <form action="{{ route('user.money.transfer.wire.transfer.submit') }}" method="post" enctype="multipart/form-data" class="row g-4">
                        @csrf
                        <div class="col-12">
                            <label for="wireTransferAmount" class="form--label required">@lang('Amount')</label>
                            <div class="input--group">
                                <input type="number" step="any" min="0" id="wireTransferAmount" class="form--control" name="amount" value="{{ old('amount') }}" required>
                                <span class="input-group-text">{{ __($setting->site_cur) }}</span>
                            </div>
                        </div>

                        <x-phinixForm identifier="act" identifierValue="wire_transfer" />

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
                            <button type="submit" class="btn btn--base w-100">@lang('Submit')</button>
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
