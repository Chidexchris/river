@extends($activeTheme . 'layouts.app')

@section('content')
    <section class="account">
        <div class="account__bg bg-img" data-background-image="{{ getImage($activeThemeTrue . 'images/site/forgot_password/' . $forgotPasswordContent->data_info->first_background_image, '960x1080') }}"></div>
        <div class="account__thumb">
            <img src="{{ getImage($activeThemeTrue . 'images/site/forgot_password/' . $forgotPasswordContent->data_info->image, '635x435') }}" alt="forgot password">
        </div>
        <div class="account__form">
            <div class="account__form__bg bg-img" data-background-image="{{ getImage($activeThemeTrue . 'images/site/forgot_password/' . $forgotPasswordContent->data_info->second_background_image, '1080x1080') }}"></div>
            <div class="account__form__wrap account__form__wrap-2">
                @include($activeTheme . 'partials.basicAccountTop')

                <div class="custom--card h-auto no-shadow">
                    <div class="card-body">
                        <div class="account__form__content mb-3">
                            <h3 class="account__form__title mb-0">{{ __($forgotPasswordContent->data_info->form_heading) }}</h3>
                        </div>
                        <form action="" method="POST" class="verify-gcaptcha">
                            @csrf
                            <div class="row">
                                <div class="col-sm-12 form-group">
                                    <label class="form--label required">@lang('Username or Email Address')</label>
                                    <input type="text" class="form--control" name="value" value="{{ old('value') }}" required>
                                </div>

                                <x-captcha />

                                <div class="col-sm-12 form-group">
                                    <button type="submit" class="btn btn--base w-100" id="recaptcha">
                                        {{ __($forgotPasswordContent->data_info->submit_button_text) }}
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
