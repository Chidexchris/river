@php
    $featuresContent  = getSiteData('features.content', true);
    $featuresElements = getSiteData('features.element', false, null, true)
@endphp

<section class="feature py-120" id="feature">
    <div class="container">
        <div class="row justify-content-center" data-aos="fade-up" data-aos-duration="1000" data-aos-offset="100">
            <div class="col-md-7">
                <div class="section-heading text-center">
                    <span class="section-heading__vector" data-aos="fade-up" data-aos-duration="1000" data-aos-offset="100">{{ __($featuresContent->data_info->section_second_subtitle) }}</span>
                    <p class="section-heading__subtitle">{{ __($featuresContent->data_info->section_first_subtitle) }}</p>
                    <h2 class="section-heading__title">{{ __($featuresContent->data_info->section_title) }} <span class="styled-title" data-mask-image="{{ asset($activeThemeTrue . 'images/title-vector.png') }}">{{ __($featuresContent->data_info->highlighted_part) }}</span></h2>
                </div>
            </div>
        </div>
        <div class="row g-4 justify-content-lg-between justify-content-center">
            <div class="col-lg-5 col-md-8 col-sm-8 col-xsm-8 col-10">
                <div class="feature__thumb" data-aos="fade-right" data-aos-duration="1000" data-aos-offset="100">
                    <img src="{{ getImage($activeThemeTrue . 'images/site/features/' . $featuresContent->data_info->image, '601x512') }}" alt="image">
                </div>
            </div>
            <div class="col-xl-6 col-lg-7 col-md-10">
                <div class="feature__list">
                    @forelse($featuresElements as $feature)
                        <div class="feature__card" data-aos="fade-left" data-aos-duration="1000" data-aos-offset="100">
                            <div class="feature__card__bg bg-img" data-background-image="{{ getImage($activeThemeTrue . 'images/site/features/' . $featuresContent->data_info->feature_background_image, '415x280') }}"></div>
                            <div class="feature__card__icon">
                                @php echo $feature->data_info->icon @endphp
                            </div>
                            <h3 class="feature__card__title">{{ __($feature->data_info->title) }}</h3>
                            <p class="feature__card__desc">{{ __($feature->data_info->short_description) }}</p>
                        </div>
                    @empty
                        @include($activeTheme . 'partials.basicNoData')
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</section>
