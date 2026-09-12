@php
    $testimonialsContent  = getSiteData('testimonials.content', true);
    $testimonialsElements = getSiteData('testimonials.element', false, null, true)
@endphp

<section class="testimonial py-120 section-bg">
    <div class="container">
        <div class="row justify-content-center" data-aos="fade-up" data-aos-duration="1000" data-aos-offset="100">
            <div class="col-md-7">
                <div class="section-heading text-center">
                    <span class="section-heading__vector" data-aos="fade-up" data-aos-duration="1000" data-aos-offset="100">{{ __($testimonialsContent->data_info->section_second_subtitle) }}</span>
                    <p class="section-heading__subtitle">{{ __($testimonialsContent->data_info->section_first_subtitle) }}</p>
                    <h2 class="section-heading__title">{{ __($testimonialsContent->data_info->section_title) }} <span class="styled-title" data-mask-image="{{ asset($activeThemeTrue . 'images/title-vector.png') }}">{{ __($testimonialsContent->data_info->highlighted_part) }}</span></h2>
                </div>
            </div>
        </div>
        <div class="row g-4 justify-content-between align-items-center">
            @if(count($testimonialsElements))
                <div class="col-lg-5 col-md-6">
                    <div class="testimonial-img-slider__wrap" data-aos="fade-up" data-aos-duration="1000" data-aos-offset="100">
                        <span class="testimonial-img-slider__wrap__vector-1">
                            <img src="{{ getImage($activeThemeTrue . 'images/site/testimonials/' . $testimonialsContent->data_info->first_vector_image, '200x200') }}" alt="vector">
                        </span>
                        <span class="testimonial-img-slider__wrap__vector-2">
                            <img src="{{ getImage($activeThemeTrue . 'images/site/testimonials/' . $testimonialsContent->data_info->second_vector_image, '200x200') }}" alt="vector">
                        </span>
                        <div class="testimonial-img-slider">
                            @foreach($testimonialsElements as $testimonial)
                                <div class="testimonial-img-slider__slide">
                                    <img src="{{ getImage($activeThemeTrue . 'images/site/testimonials/' . $testimonial->data_info->client_image, '635x425') }}" alt="@lang('client')">
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 col-md-6">
                    <div class="testimonial-txt-slider bg-img" data-background-image="{{ getImage($activeThemeTrue . 'images/site/testimonials/' . $testimonialsContent->data_info->background_image, '300x284') }}" data-aos="fade-up" data-aos-duration="1000" data-aos-offset="100">
                        @foreach($testimonialsElements as $testimonial)
                            <div class="testimonial-card">
                                <span class="testimonial-card__icon"><i class="ti ti-quote"></i></span>
                                <p class="testimonial-card__desc">{{ __($testimonial->data_info->client_review) }}</p>
                                <h5 class="testimonial-card__name">{{ __($testimonial->data_info->client_name) }}</h5>
                                <span class="testimonial-card__designation">{{ __($testimonial->data_info->client_designation) }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            @else
                @include($activeTheme . 'partials.basicNoData')
            @endif
        </div>
    </div>
</section>
