@extends("{$activeTheme}layouts.frontend")

@php $bannerContent = getSiteData('banner.content', true) @endphp

@section('frontend')

    {{-- ═══════════════════════════════════════════════════════
         HERO SECTION
    ═══════════════════════════════════════════════════════ --}}
    <section class="banner-section riverwind-hero" aria-label="@lang('Hero')">
        <div class="container">
            <div class="banner-content-wrap">
                <div class="row align-items-center justify-content-lg-between justify-content-center">

                    <div class="col-lg-6 col-md-7">
                        <div class="banner-content" data-aos="fade-right" data-aos-duration="700" data-aos-offset="50">

                            <h4 class="banner-content__subtitle">
                                <i class="ti ti-shield-check" style="color:var(--pb-gold)"></i>
                                {{ __($bannerContent->data_info->subtitle) }}
                            </h4>

                            <h1 class="banner-content__title">
                                {{ __($bannerContent->data_info->title) }}
                                <span class="styled-title">{{ __($bannerContent->data_info->highlighted_part) }}</span>
                            </h1>

                            <p class="banner-content__desc">{{ __($bannerContent->data_info->description) }}</p>

                            <div class="banner-content__btn-box">
                                @auth('web')
                                    <a href="{{ route('user.home') }}" class="btn btn--base">
                                        @lang('Go to Dashboard') <i class="ti ti-arrow-up-right"></i>
                                    </a>
                                @endauth
                                @guest('web')
                                    <a href="{{ route('user.register.form') }}" class="btn btn--base">
                                        @lang('Open an Account') <i class="ti ti-arrow-up-right"></i>
                                    </a>
                                @endguest
                                <a href="{{ route('about.us') }}" class="hero-secondary-link">
                                    @lang('Discover RiverWind Bank') <i class="ti ti-arrow-right"></i>
                                </a>
                            </div>

                            <div class="hero-trust">
                                <span><i class="ti ti-shield-check"></i> @lang('Secure by design')</span>
                                <span><i class="ti ti-bolt"></i> @lang('Fast, reliable banking')</span>
                                <span><i class="ti ti-users"></i> @lang('Trusted by thousands')</span>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-5 col-md-5 col-sm-8 col-10">
                        <div class="banner-thumb riverwind-hero__visual d-flex justify-content-center justify-content-lg-end"
                             data-aos="fade-left" data-aos-duration="700" data-aos-delay="100" data-aos-offset="50">
                            <img src="{{ getImage($activeThemeTrue . 'images/site/banner/' . $bannerContent->data_info->image, '601x602') }}" alt="@lang('Banking visual')">
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </section>

    @include("{$activeTheme}sections.aboutUs")
    @include("{$activeTheme}sections.services")
    @include("{$activeTheme}sections.whyChooseUs")
    @include("{$activeTheme}sections.process")
    @include("{$activeTheme}sections.features")
    @include("{$activeTheme}sections.dps")
    @include("{$activeTheme}sections.fds")
    @include("{$activeTheme}sections.loan")
    @include("{$activeTheme}sections.faq")
    @include("{$activeTheme}sections.testimonials")
    @include("{$activeTheme}sections.counters")
    @include("{$activeTheme}sections.subscribe")
    @include("{$activeTheme}sections.partners")

@endsection

@push('page-style-lib')
    <link rel="stylesheet" href="{{ asset("{$activeThemeTrue}css/modal-video.min.css") }}">
    <link rel="stylesheet" href="{{ asset("{$activeThemeTrue}css/slick.css") }}">
@endpush

@push('page-script-lib')
    <script src="{{ asset("{$activeThemeTrue}js/modal-video.min.js") }}"></script>
    <script src="{{ asset("{$activeThemeTrue}js/slick.min.js") }}"></script>
@endpush
