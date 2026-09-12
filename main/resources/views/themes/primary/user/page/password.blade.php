@extends($activeTheme . 'layouts.auth')

@section('auth')
    <div class="row justify-content-center">
        <div class="col-xl-7 col-lg-8">
            <div class="custom--card">
                <div class="card-header">
                    <h3 class="title">@lang('Secure Your Account')</h3>
                </div>
                <div class="card-body">
                    <form action="" method="post" class="row g-4">
                        @csrf
                        <div class="col-12">
                            <label class="form--label required">@lang('Current Password')</label>
                            <div class="position-relative">
                                <input type="password" class="form--control" name="current_password" id="current-password" required>
                                <span class="password-show-hide ti ti-eye toggle-password" id="#current-password"></span>
                            </div>
                        </div>
                        <div class="col-12">
                            <label class="form--label required">@lang('New Password')</label>
                            <div class="position-relative">
                                <input type="password" @class(['form--control', 'secure-password' => $setting->strong_pass]) name="password" id="new-password" required>
                                <span class="password-show-hide ti ti-eye toggle-password" id="#new-password"></span>
                            </div>
                        </div>
                        <div class="col-12">
                            <label class="form--label required">@lang('Confirm Password')</label>
                            <div class="position-relative">
                                <input type="password" class="form--control" name="password_confirmation" id="confirm-password" required>
                                <span class="password-show-hide ti ti-eye toggle-password" id="#confirm-password"></span>
                            </div>
                        </div>
                        <div class="col-12">
                            <button type="submit" class="btn btn--base w-100">@lang('Submit')</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@pushIf($setting->strong_pass, 'page-style-lib')
    <link rel="stylesheet" href="{{ asset('assets/universal/css/strongPassword.css') }}">
@endPushIf

@pushIf($setting->strong_pass, 'page-script-lib')
    <script src="{{ asset('assets/universal/js/strongPassword.js') }}"></script>
@endPushIf
