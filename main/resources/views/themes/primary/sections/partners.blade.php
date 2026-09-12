@php
    $partnerContent  = getSiteData('partner.content', true);
    $partnerElements = getSiteData('partner.element', false, null, true)
@endphp

<section class="partner py-120">
    <div class="container">
        <div class="row justify-content-center" data-aos="fade-up" data-aos-duration="1500">
            <div class="col-md-7">
                <div class="section-heading text-center">
                    <span class="section-heading__vector" data-aos="fade-up" data-aos-duration="1500">{{ __($partnerContent->data_info->section_second_subtitle) }}</span>
                    <p class="section-heading__subtitle">{{ __($partnerContent->data_info->section_first_subtitle) }}</p>
                    <h2 class="section-heading__title">{{ __($partnerContent->data_info->section_title) }} <span class="styled-title" data-mask-image="{{ asset($activeThemeTrue . 'images/title-vector.png') }}">{{ __($partnerContent->data_info->highlighted_part) }}</span></h2>
                </div>
            </div>
        </div>

        @if(count($partnerElements))
            <div class="partner__slider" data-aos="fade-up" data-aos-duration="1500">
                @foreach($partnerElements as $partner)
                    <div class="partner__slide">
                        <img src="{{ getImage($activeThemeTrue . 'images/site/partner/' . $partner->data_info->image) }}" alt="Image">
                    </div>
                @endforeach
            </div>
        @else
            @include($activeTheme . 'partials.basicNoData')
        @endif
    </div>
</section>
