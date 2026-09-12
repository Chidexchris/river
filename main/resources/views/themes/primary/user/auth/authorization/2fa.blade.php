@extends($activeTheme . 'layouts.app')

@php $tfaConfirmContent = getSiteData('2fa_confirm.content', true) @endphp

@section('content')
    <section class="account">
        <div class="account__bg bg-img" data-background-image="{{ getImage($activeThemeTrue . 'images/site/2fa_confirm/' . $tfaConfirmContent->data_info->first_background_image, '960x1080') }}"></div>
        <div class="account__thumb">
            <img src="{{ getImage($activeThemeTrue . 'images/site/2fa_confirm/' . $tfaConfirmContent->data_info->image, '635x485') }}" alt="2fa confirm">
        </div>
        <div class="account__form">
            <div class="account__form__bg bg-img" data-background-image="{{ getImage($activeThemeTrue . 'images/site/2fa_confirm/' . $tfaConfirmContent->data_info->second_background_image, '1080x1080') }}"></div>
            <div class="account__form__wrap">
                @include($activeTheme . 'partials.basicAccountTop')

                <div class="custom--card h-auto no-shadow">
                    <div class="card-body">
                        <div class="account__form__content mb-3">
                            <h3 class="account__form__title mb-0">{{ __($tfaConfirmContent->data_info->form_heading) }}</h3>
                        </div>
                        <p class="mb-4">{{ __($tfaConfirmContent->data_info->form_text) }}</p>
                        <form action="{{ route('user.go2fa.verify') }}" method="POST" class="verification-code-form">
                            @csrf
                            <div class="row">
                                <div class="col-sm-12 form-group">
                                    <label class="form--label required">@lang('Verification Code')</label>

                                    @include('partials.verificationCode')
                                </div>
                                <div class="col-sm-12 form-group">
                                    <button type="submit" class="btn btn--base w-100">
                                        {{ __($tfaConfirmContent->data_info->submit_button_text) }}
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
