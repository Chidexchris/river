@extends($activeTheme . 'layouts.app')

@php $userBanContent = getSiteData('user_ban.content', true) @endphp

@section('content')
    <section class="account">
        <div class="account__bg bg-img" data-background-image="{{ getImage($activeThemeTrue . 'images/site/user_ban/' . $userBanContent->data_info->first_background_image, '960x1080') }}"></div>
        <div class="account__thumb">
            <img src="{{ getImage($activeThemeTrue . 'images/site/user_ban/' . $userBanContent->data_info->image, '635x485') }}" alt="user ban">
        </div>
        <div class="account__form">
            <div class="account__form__bg bg-img" data-background-image="{{ getImage($activeThemeTrue . 'images/site/user_ban/' . $userBanContent->data_info->second_background_image, '1080x1080') }}"></div>
            <div class="account__form__wrap">
                @include($activeTheme . 'partials.basicAccountTop')

                <div class="custom--card h-auto no-shadow">
                    <div class="card-body">
                        <div class="account__form__content mb-3">
                            <h3 class="account__form__title mb-0">{{ __($userBanContent->data_info->form_heading) }}</h3>
                        </div>
                        <div class="row">
                            <div class="col-sm-12">
                                <p class="card-text">{{ $user->ban_reason }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
