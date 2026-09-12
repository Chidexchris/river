@php
    $servicesContent  = getSiteData('services.content', true);
    $servicesElements = getSiteData('services.element', false, null, true)
@endphp

<section class="service">
    <div class="service__bg bg-img" data-background-image="{{ getImage($activeThemeTrue . 'images/site/services/' . $servicesContent->data_info->background_image, '1920x1280') }}"></div>
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6">
                <div class="py-120" data-aos="fade-up" data-aos-duration="1000" data-aos-offset="100">
                    <div class="section-heading">
                        <span class="section-heading__vector" data-aos="fade-up" data-aos-duration="1000" data-aos-offset="100">{{ __($servicesContent->data_info->section_second_subtitle) }}</span>
                        <h6 class="section-heading__subtitle">{{ __($servicesContent->data_info->section_first_subtitle) }}</h6>
                        <h2 class="section-heading__title">{{ __($servicesContent->data_info->section_title) }} <span class="styled-title" data-mask-image="{{ asset($activeThemeTrue . 'images/title-vector.png') }}">{{ __($servicesContent->data_info->highlighted_part) }}</span></h2>
                    </div>
                    <ul class="service__list">
                        @foreach($servicesElements as $service)
                            <li @class(['active' => $loop->first]) data-service="{{ 'service__' . $loop->iteration }}"><span data-aos="fade-right" data-aos-duration="1000" data-aos-offset="100">{{ __($service->data_info->title) }}</span></li>
                        @endforeach
                    </ul>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="service__details py-120">
                    @foreach($servicesElements as $service)
                        <div @class(['service__single', 'active' => $loop->first]) id="{{ 'service__' . $loop->iteration }}">
                            <div class="service__thumb" @if($loop->first) data-aos="fade-left" data-aos-duration="500" data-aos-offset="100" @endif>
                                <img src="{{ getImage($activeThemeTrue . 'images/site/services/' . $service->data_info->image) }}" alt="Image">
                            </div>
                            <h3 class="service__title" @if($loop->first) data-aos="fade-left" data-aos-duration="1000" data-aos-offset="100" @endif>
                                {{ __($service->data_info->title) }}
                            </h3>
                            <p class="service__desc" @if($loop->first) data-aos="fade-left" data-aos-duration="1500" data-aos-offset="100" @endif>
                                {{ __($service->data_info->description) }}
                            </p>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</section>
