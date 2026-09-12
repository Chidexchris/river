@php
    $whyChooseUsContent  = getSiteData('why_choose_us.content', true);
    $whyChooseUsElements = getSiteData('why_choose_us.element', false, null, true)
@endphp

<section class="why-choose py-120">
    <div class="container">
        <div class="row justify-content-center" data-aos="fade-up" data-aos-duration="1000" data-aos-offset="100">
            <div class="col-md-7">
                <div class="section-heading text-center">
                    <span class="section-heading__vector" data-aos="fade-up" data-aos-duration="1000" data-aos-offset="100">{{ __($whyChooseUsContent->data_info->section_second_subtitle) }}</span>
                    <h6 class="section-heading__subtitle">{{ __($whyChooseUsContent->data_info->section_first_subtitle) }}</h6>
                    <h2 class="section-heading__title">{{ __($whyChooseUsContent->data_info->section_title) }} <span class="styled-title" data-mask-image="{{ asset($activeThemeTrue . 'images/title-vector.png') }}">{{ __($whyChooseUsContent->data_info->highlighted_part) }}</span></h2>
                </div>
            </div>
        </div>
        <div class="row g-4 justify-content-center why-choose__row">
            @forelse($whyChooseUsElements as $whyChooseUs)
                <div class="col-lg-4 col-sm-6">
                    <div class="why-choose__card" data-aos="fade-up" data-aos-duration="1000" data-aos-offset="100">
                        <span class="why-choose__card__vector">
                            @php echo $whyChooseUs->data_info->icon @endphp
                        </span>
                        <span class="why-choose__card__icon">
                            @php echo $whyChooseUs->data_info->icon @endphp
                        </span>
                        <div class="why-choose__card__txt">
                            <p>{{ __($whyChooseUs->data_info->short_description) }}</p>
                        </div>
                        <h3 class="why-choose__card__title">{{ __($whyChooseUs->data_info->title) }}</h3>
                    </div>
                </div>
            @empty
                @include($activeTheme . 'partials.basicNoData')
            @endforelse
        </div>
    </div>
</section>
