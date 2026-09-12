@php $aboutUsContent = getSiteData('about_us.content', true) @endphp

<section class="about py-120">
    <div class="container">
        <div class="row g-4 justify-content-md-between justify-content-center align-items-center">
            <div class="col-lg-5 col-md-6 col-sm-8 col-xsm-8 col-10">
                <div class="about__thumb" data-aos="fade-up" data-aos-duration="1000" data-aos-offset="100">
                    <img src="{{ getImage($activeThemeTrue . 'images/site/about_us/' . $aboutUsContent->data_info->image, '601x662') }}" alt="about">
                </div>
            </div>
            <div class="col-md-6">
                <div class="about__txt" data-aos="fade-up" data-aos-duration="1000" data-aos-offset="100">
                    <div class="section-heading">
                        <span class="section-heading__vector" data-aos="fade-up" data-aos-duration="1000" data-aos-offset="100">{{ __($aboutUsContent->data_info->section_second_subtitle) }}</span>
                        <h6 class="section-heading__subtitle">{{ __($aboutUsContent->data_info->section_first_subtitle) }}</h6>
                        <h2 class="section-heading__title">{{ __($aboutUsContent->data_info->section_title) }} <span class="styled-title" data-mask-image="{{ asset($activeThemeTrue . 'images/title-vector.png') }}">{{ __($aboutUsContent->data_info->highlighted_part) }}</span></h2>
                    </div>
                    <div class="about__desc" data-aos="fade-up" data-aos-duration="1000" data-aos-offset="100">
                        @php echo strLimit($aboutUsContent->data_info->description, 500); @endphp
                    </div>
                    <a href="{{ route('about.us') }}" class="btn btn--base">{{ __($aboutUsContent->data_info->button_text) }} @php echo $aboutUsContent->data_info->button_icon @endphp</a>
                </div>
            </div>
        </div>
    </div>
</section>
