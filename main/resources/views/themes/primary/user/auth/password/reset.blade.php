@extends($activeTheme . 'layouts.app')

@section('content')
    <section class="account">
        <div class="account__bg bg-img" data-background-image="{{ getImage($activeThemeTrue . 'images/site/password_reset/' . $passwordResetContent->data_info->first_background_image, '960x1080') }}"></div>
        <div class="account__thumb">
            <img src="{{ getImage($activeThemeTrue . 'images/site/password_reset/' . $passwordResetContent->data_info->image, '635x485') }}" alt="password reset">
        </div>
        <div class="account__form">
            <div class="account__form__bg bg-img" data-background-image="{{ getImage($activeThemeTrue . 'images/site/password_reset/' . $passwordResetContent->data_info->second_background_image, '1080x1080') }}"></div>
            <div class="account__form__wrap">
                @include($activeTheme . 'partials.basicAccountTop')

                <div class="custom--card h-auto no-shadow">
                    <div class="card-body">
                        <div class="account__form__content mb-3">
                            <h3 class="account__form__title mb-0">{{ __($passwordResetContent->data_info->form_heading) }}</h3>
                        </div>
                        <form action="{{ route('user.password.reset') }}" method="POST">
                            @csrf
                            <input type="hidden" name="email" value="{{ $email }}">
                            <input type="hidden" name="code" value="{{ $code }}">
                            <div class="row">
                                <div class="col-sm-12 form-group">
                                    <label class="form--label required">@lang('New Password')</label>
                                    <div class="position-relative">
                                        <input type="password" @class(['form--control', 'secure-password' => $setting->strong_pass]) name="password" id="new-password" required>
                                        <span class="password-show-hide ti ti-eye toggle-password" id="#new-password"></span>
                                    </div>
                                </div>
                                <div class="col-sm-12 form-group">
                                    <label class="form--label required">@lang('Confirm Password')</label>
                                    <div class="position-relative">
                                        <input type="password" class="form--control" name="password_confirmation" id="confirm-password" required>
                                        <span class="password-show-hide ti ti-eye toggle-password" id="#confirm-password"></span>
                                    </div>
                                </div>
                                <div class="col-sm-12 form-group">
                                    <button type="submit" class="btn btn--base w-100">
                                        {{ __($passwordResetContent->data_info->submit_button_text) }}
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@pushIf($setting->strong_pass, 'page-style-lib')
    <link rel="stylesheet" href="{{ asset('assets/universal/css/strongPassword.css') }}">
@endPushIf

@pushIf($setting->strong_pass, 'page-script-lib')
    <script src="{{ asset('assets/universal/js/strongPassword.js') }}"></script>
@endPushIf
