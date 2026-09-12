@php $fdsContent = getSiteData('fds.content', true) @endphp

<section class="pricing py-120">
    <div class="container">
        <div class="row justify-content-center" data-aos="fade-up" data-aos-duration="1000" data-aos-offset="100">
            <div class="col-md-7">
                <div class="section-heading text-center">
                    <span class="section-heading__vector" data-aos="fade-up" data-aos-duration="1000" data-aos-offset="100">{{ __($fdsContent->data_info->section_second_subtitle) }}</span>
                    <p class="section-heading__subtitle">{{ __($fdsContent->data_info->section_first_subtitle) }}</p>
                    <h2 class="section-heading__title">{{ __($fdsContent->data_info->section_title) }} <span class="styled-title" data-mask-image="{{ asset($activeThemeTrue . 'images/title-vector.png') }}">{{ __($fdsContent->data_info->highlighted_part) }}</span></h2>
                </div>
            </div>
        </div>
        <div class="pricing__row row g-4 justify-content-center">
            @forelse($fdsPlans->take(3) as $fdsPlan)
                @include("{$activeTheme}partials.fdsPlan")
            @empty
                @include("{$activeTheme}partials.basicNoData")
            @endforelse
        </div>

        @if($fdsPlans->count() > 3)
            <div class="section-btn d-flex justify-content-center pt-5" data-aos="fade-up" data-aos-duration="1000" data-aos-offset="5">
                <a href="{{ route('user.fds.plans') }}" class="btn btn--base">@lang('View More') <i class="ti ti-arrow-up-right"></i></a>
            </div>
        @endif
    </div>
</section>
